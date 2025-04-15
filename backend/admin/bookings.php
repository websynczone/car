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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['booking_id'])) {
        $bookingId = $_POST['booking_id'];
        $action = $_POST['action'];
        
        switch ($action) {
            case 'confirm':
                $db->update(
                    'bookings',
                    ['status' => BOOKING_STATUS_CONFIRMED],
                    'id = ?',
                    [$bookingId]
                );
                break;
                
            case 'cancel':
                $db->update(
                    'bookings',
                    ['status' => BOOKING_STATUS_CANCELLED],
                    'id = ?',
                    [$bookingId]
                );
                break;
                
            case 'complete':
                $db->update(
                    'bookings',
                    ['status' => BOOKING_STATUS_COMPLETED],
                    'id = ?',
                    [$bookingId]
                );
                break;
        }
        
        header('Location: bookings.php');
        exit();
    }
}

// Get all bookings with vehicle and customer details
$bookings = $db->fetchAll(
    "SELECT b.*, v.name as vehicle_name, v.model, c.name as customer_name, c.email as customer_email 
     FROM bookings b 
     JOIN vehicles v ON b.vehicle_id = v.id 
     JOIN customers c ON b.customer_id = c.id 
     ORDER BY b.created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - <?php echo SITE_NAME; ?></title>
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
                <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="vehicles.php"><i class="fas fa-car"></i> Vehicles</a>
                <a href="bookings.php" class="active"><i class="fas fa-calendar"></i> Bookings</a>
                <a href="customers.php"><i class="fas fa-users"></i> Customers</a>
                <a href="testimonials.php"><i class="fas fa-star"></i> Testimonials</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-3">
                <h2 class="mb-4">Manage Bookings</h2>

                <!-- Bookings Table -->
                <div class="card">
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
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td><?php echo $booking['id']; ?></td>
                                        <td>
                                            <?php echo $booking['customer_name']; ?><br>
                                            <small class="text-muted"><?php echo $booking['customer_email']; ?></small>
                                        </td>
                                        <td>
                                            <?php echo $booking['vehicle_name']; ?><br>
                                            <small class="text-muted"><?php echo $booking['model']; ?></small>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($booking['start_date'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($booking['end_date'])); ?></td>
                                        <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
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
                                            <?php if ($booking['status'] == BOOKING_STATUS_PENDING): ?>
                                            <form action="bookings.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="confirm">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Confirm
                                                </button>
                                            </form>
                                            <?php endif; ?>

                                            <?php if ($booking['status'] == BOOKING_STATUS_CONFIRMED): ?>
                                            <form action="bookings.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="complete">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-info">
                                                    <i class="fas fa-flag-checkered"></i> Complete
                                                </button>
                                            </form>
                                            <?php endif; ?>

                                            <?php if ($booking['status'] != BOOKING_STATUS_CANCELLED && $booking['status'] != BOOKING_STATUS_COMPLETED): ?>
                                            <form action="bookings.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="cancel">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                            <?php endif; ?>

                                            <a href="booking_details.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
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