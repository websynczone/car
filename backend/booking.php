<?php
require_once 'config.php';
require_once 'db_connect.php';

class Booking {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createBooking($data) {
        // Validate booking data
        if (!$this->validateBookingData($data)) {
            return ['success' => false, 'message' => 'Invalid booking data'];
        }

        // Check if vehicle is available
        if (!$this->isVehicleAvailable($data['vehicle_id'], $data['start_date'], $data['end_date'])) {
            return ['success' => false, 'message' => 'Vehicle is not available for the selected dates'];
        }

        // Calculate total price
        $totalPrice = $this->calculateTotalPrice($data['vehicle_id'], $data['start_date'], $data['end_date']);

        // Prepare booking data
        $bookingData = [
            'vehicle_id' => $data['vehicle_id'],
            'customer_id' => $data['customer_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'total_price' => $totalPrice,
            'status' => BOOKING_STATUS_PENDING,
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            // Insert booking
            $bookingId = $this->db->insert('bookings', $bookingData);

            // Update vehicle status
            $this->db->update(
                'vehicles',
                ['status' => VEHICLE_STATUS_RENTED],
                'id = ?',
                [$data['vehicle_id']]
            );

            return [
                'success' => true,
                'message' => 'Booking created successfully',
                'booking_id' => $bookingId
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error creating booking: ' . $e->getMessage()];
        }
    }

    private function validateBookingData($data) {
        $requiredFields = ['vehicle_id', 'customer_id', 'start_date', 'end_date'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        // Validate dates
        $startDate = strtotime($data['start_date']);
        $endDate = strtotime($data['end_date']);
        
        if ($startDate === false || $endDate === false || $startDate >= $endDate) {
            return false;
        }

        return true;
    }

    private function isVehicleAvailable($vehicleId, $startDate, $endDate) {
        $sql = "SELECT COUNT(*) as count FROM bookings 
                WHERE vehicle_id = ? 
                AND status != ? 
                AND (
                    (start_date BETWEEN ? AND ?) 
                    OR (end_date BETWEEN ? AND ?)
                    OR (start_date <= ? AND end_date >= ?)
                )";

        $result = $this->db->fetch($sql, [
            $vehicleId,
            BOOKING_STATUS_CANCELLED,
            $startDate,
            $endDate,
            $startDate,
            $endDate,
            $startDate,
            $endDate
        ]);

        return $result['count'] == 0;
    }

    private function calculateTotalPrice($vehicleId, $startDate, $endDate) {
        // Get vehicle price per day
        $vehicle = $this->db->fetch(
            "SELECT price_per_day FROM vehicles WHERE id = ?",
            [$vehicleId]
        );

        if (!$vehicle) {
            throw new Exception('Vehicle not found');
        }

        // Calculate number of days
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        $days = $interval->days + 1;

        // Calculate total price
        return $vehicle['price_per_day'] * $days;
    }

    public function getBooking($bookingId) {
        return $this->db->fetch(
            "SELECT b.*, v.name as vehicle_name, v.model, c.name as customer_name 
             FROM bookings b 
             JOIN vehicles v ON b.vehicle_id = v.id 
             JOIN customers c ON b.customer_id = c.id 
             WHERE b.id = ?",
            [$bookingId]
        );
    }

    public function updateBookingStatus($bookingId, $status) {
        if (!in_array($status, [
            BOOKING_STATUS_PENDING,
            BOOKING_STATUS_CONFIRMED,
            BOOKING_STATUS_CANCELLED,
            BOOKING_STATUS_COMPLETED
        ])) {
            return ['success' => false, 'message' => 'Invalid status'];
        }

        try {
            $this->db->update(
                'bookings',
                ['status' => $status],
                'id = ?',
                [$bookingId]
            );

            // If booking is cancelled or completed, update vehicle status
            if ($status == BOOKING_STATUS_CANCELLED || $status == BOOKING_STATUS_COMPLETED) {
                $booking = $this->getBooking($bookingId);
                $this->db->update(
                    'vehicles',
                    ['status' => VEHICLE_STATUS_AVAILABLE],
                    'id = ?',
                    [$booking['vehicle_id']]
                );
            }

            return ['success' => true, 'message' => 'Booking status updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error updating booking status: ' . $e->getMessage()];
        }
    }
} 