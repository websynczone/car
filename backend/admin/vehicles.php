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
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $data = [
                    'name' => $_POST['name'],
                    'model' => $_POST['model'],
                    'year' => $_POST['year'],
                    'price_per_day' => $_POST['price_per_day'],
                    'status' => $_POST['status'],
                    'description' => $_POST['description'],
                    'features' => $_POST['features']
                ];
                
                // Handle image upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $targetDir = '../../images/vehicles/';
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetPath = $targetDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $data['image'] = 'images/vehicles/' . $fileName;
                    }
                }
                
                $db->insert('vehicles', $data);
                break;

            case 'edit':
                $data = [
                    'name' => $_POST['name'],
                    'model' => $_POST['model'],
                    'year' => $_POST['year'],
                    'price_per_day' => $_POST['price_per_day'],
                    'status' => $_POST['status'],
                    'description' => $_POST['description'],
                    'features' => $_POST['features']
                ];
                
                // Handle image upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $targetDir = '../../images/vehicles/';
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetPath = $targetDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $data['image'] = 'images/vehicles/' . $fileName;
                    }
                }
                
                $db->update('vehicles', $data, 'id = ?', [$_POST['id']]);
                break;

            case 'delete':
                $db->delete('vehicles', 'id = ?', [$_POST['id']]);
                break;
        }
        
        header('Location: vehicles.php');
        exit();
    }
}

// Get all vehicles
$vehicles = $db->fetchAll("SELECT * FROM vehicles ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles - <?php echo SITE_NAME; ?></title>
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
        .vehicle-image {
            width: 100px;
            height: 60px;
            object-fit: cover;
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
                <a href="vehicles.php" class="active"><i class="fas fa-car"></i> Vehicles</a>
                <a href="bookings.php"><i class="fas fa-calendar"></i> Bookings</a>
                <a href="customers.php"><i class="fas fa-users"></i> Customers</a>
                <a href="testimonials.php"><i class="fas fa-star"></i> Testimonials</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Vehicles</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                        <i class="fas fa-plus"></i> Add New Vehicle
                    </button>
                </div>

                <!-- Vehicles Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Model</th>
                                        <th>Year</th>
                                        <th>Price/Day</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vehicles as $vehicle): ?>
                                    <tr>
                                        <td>
                                            <img src="../../<?php echo $vehicle['image']; ?>" alt="<?php echo $vehicle['name']; ?>" class="vehicle-image">
                                        </td>
                                        <td><?php echo $vehicle['name']; ?></td>
                                        <td><?php echo $vehicle['model']; ?></td>
                                        <td><?php echo $vehicle['year']; ?></td>
                                        <td>$<?php echo number_format($vehicle['price_per_day'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $vehicle['status'] == VEHICLE_STATUS_AVAILABLE ? 'success' : 
                                                    ($vehicle['status'] == VEHICLE_STATUS_RENTED ? 'warning' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst($vehicle['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editVehicle(<?php echo htmlspecialchars(json_encode($vehicle)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteVehicle(<?php echo $vehicle['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

    <!-- Add Vehicle Modal -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="vehicles.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" class="form-control" name="model" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" name="year" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price per Day</label>
                            <input type="number" class="form-control" name="price_per_day" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="<?php echo VEHICLE_STATUS_AVAILABLE; ?>">Available</option>
                                <option value="<?php echo VEHICLE_STATUS_RENTED; ?>">Rented</option>
                                <option value="<?php echo VEHICLE_STATUS_MAINTENANCE; ?>">Maintenance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Features</label>
                            <textarea class="form-control" name="features" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Vehicle Modal -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="vehicles.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" class="form-control" name="model" id="edit_model" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" name="year" id="edit_year" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price per Day</label>
                            <input type="number" class="form-control" name="price_per_day" id="edit_price_per_day" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="<?php echo VEHICLE_STATUS_AVAILABLE; ?>">Available</option>
                                <option value="<?php echo VEHICLE_STATUS_RENTED; ?>">Rented</option>
                                <option value="<?php echo VEHICLE_STATUS_MAINTENANCE; ?>">Maintenance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Features</label>
                            <textarea class="form-control" name="features" id="edit_features" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Vehicle Modal -->
    <div class="modal fade" id="deleteVehicleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this vehicle? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <form action="vehicles.php" method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editVehicle(vehicle) {
            document.getElementById('edit_id').value = vehicle.id;
            document.getElementById('edit_name').value = vehicle.name;
            document.getElementById('edit_model').value = vehicle.model;
            document.getElementById('edit_year').value = vehicle.year;
            document.getElementById('edit_price_per_day').value = vehicle.price_per_day;
            document.getElementById('edit_status').value = vehicle.status;
            document.getElementById('edit_description').value = vehicle.description;
            document.getElementById('edit_features').value = vehicle.features;
            
            new bootstrap.Modal(document.getElementById('editVehicleModal')).show();
        }

        function deleteVehicle(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteVehicleModal')).show();
        }
    </script>
</body>
</html> 