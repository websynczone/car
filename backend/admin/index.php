<?php
require_once '../config.php';
require_once '../db_connect.php';

// Check if user is logged in and is admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== USER_ROLE_ADMIN) {
    header('Location: login.php');
    exit();
}

$db = Database::getInstance();

// Get dashboard statistics
$stats = [
    'total_bookings' => $db->fetch("SELECT COUNT(*) as count FROM bookings")['count'],
    'active_bookings' => $db->fetch("SELECT COUNT(*) as count FROM bookings WHERE status = ?", [BOOKING_STATUS_CONFIRMED])['count'],
    'total_vehicles' => $db->fetch("SELECT COUNT(*) as count FROM vehicles")['count'],
    'available_vehicles' => $db->fetch("SELECT COUNT(*) as count FROM vehicles WHERE status = ?", [VEHICLE_STATUS_AVAILABLE])['count']
];

// Get recent bookings
$recentBookings = $db->fetchAll(
    "SELECT b.*, v.name as vehicle_name, c.name as customer_name 
     FROM bookings b 
     JOIN vehicles v ON b.vehicle_id = v.id 
     JOIN customers c ON b.customer_id = c.id 
     ORDER BY b.created_at DESC 
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3 text-white">
                    <h4>Admin Panel</h4>
                </div>
                <a href="index.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="vehicles.php"><i class="fas fa-car"></i> Vehicles</a>
                <a href="bookings.php"><i class="fas fa-calendar"></i> Bookings</a>
                <a href="customers.php"><i class="fas fa-users"></i> Customers</a>
                <a href="testimonials.php"><i class="fas fa-star"></i> Testimonials</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-3">
                <h2 class="mb-4">Dashboard</h2>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary text-white">
                            <h3><?php echo $stats['total_bookings']; ?></h3>
                            <p>Total Bookings</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success text-white">
                            <h3><?php echo $stats['active_bookings']; ?></h3>
                            <p>Active Bookings</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info text-white">
                            <h3><?php echo $stats['total_vehicles']; ?></h3>
                            <p>Total Vehicles</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning text-white">
                            <h3><?php echo $stats['available_vehicles']; ?></h3>
                            <p>Available Vehicles</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Bookings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Vehicle</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td><?php echo $booking['id']; ?></td>
                                        <td><?php echo $booking['customer_name']; ?></td>
                                        <td><?php echo $booking['vehicle_name']; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($booking['start_date'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($booking['end_date'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $booking['status'] == BOOKING_STATUS_CONFIRMED ? 'success' : 
                                                    ($booking['status'] == BOOKING_STATUS_PENDING ? 'warning' : 
                                                    ($booking['status'] == BOOKING_STATUS_CANCELLED ? 'danger' : 'info')); 
                                            ?>">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="booking_details.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 