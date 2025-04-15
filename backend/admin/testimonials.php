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
                    'customer_name' => $_POST['customer_name'],
                    'text' => $_POST['text'],
                    'rating' => $_POST['rating'],
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $db->insert('testimonials', $data);
                break;

            case 'edit':
                $data = [
                    'customer_name' => $_POST['customer_name'],
                    'text' => $_POST['text'],
                    'rating' => $_POST['rating'],
                    'status' => $_POST['status']
                ];
                
                $db->update('testimonials', $data, 'id = ?', [$_POST['id']]);
                break;

            case 'delete':
                $db->delete('testimonials', 'id = ?', [$_POST['id']]);
                break;

            case 'approve':
                $db->update(
                    'testimonials',
                    ['status' => 'approved'],
                    'id = ?',
                    [$_POST['id']]
                );
                break;

            case 'reject':
                $db->update(
                    'testimonials',
                    ['status' => 'rejected'],
                    'id = ?',
                    [$_POST['id']]
                );
                break;
        }
        
        header('Location: testimonials.php');
        exit();
    }
}

// Get all testimonials
$testimonials = $db->fetchAll("SELECT * FROM testimonials ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Testimonials - <?php echo SITE_NAME; ?></title>
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
        .rating {
            color: #ffc107;
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
                <a href="bookings.php"><i class="fas fa-calendar"></i> Bookings</a>
                <a href="customers.php"><i class="fas fa-users"></i> Customers</a>
                <a href="testimonials.php" class="active"><i class="fas fa-star"></i> Testimonials</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Testimonials</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
                        <i class="fas fa-plus"></i> Add New Testimonial
                    </button>
                </div>

                <!-- Testimonials Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Text</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($testimonials as $testimonial): ?>
                                    <tr>
                                        <td><?php echo $testimonial['customer_name']; ?></td>
                                        <td><?php echo substr($testimonial['text'], 0, 100) . '...'; ?></td>
                                        <td>
                                            <div class="rating">
                                                <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo $i <= $testimonial['rating'] ? '★' : '☆';
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $testimonial['status'] == 'approved' ? 'success' : 
                                                    ($testimonial['status'] == 'pending' ? 'warning' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst($testimonial['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($testimonial['created_at'])); ?></td>
                                        <td>
                                            <?php if ($testimonial['status'] == 'pending'): ?>
                                            <form action="testimonials.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="testimonials.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-sm btn-primary" onclick="editTestimonial(<?php echo htmlspecialchars(json_encode($testimonial)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteTestimonial(<?php echo $testimonial['id']; ?>)">
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

    <!-- Add Testimonial Modal -->
    <div class="modal fade" id="addTestimonialModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="testimonials.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Testimonial Text</label>
                            <textarea class="form-control" name="text" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select" name="rating" required>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Testimonial</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Testimonial Modal -->
    <div class="modal fade" id="editTestimonialModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="testimonials.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" name="customer_name" id="edit_customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Testimonial Text</label>
                            <textarea class="form-control" name="text" id="edit_text" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select" name="rating" id="edit_rating" required>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
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

    <!-- Delete Testimonial Modal -->
    <div class="modal fade" id="deleteTestimonialModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this testimonial? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <form action="testimonials.php" method="POST">
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
        function editTestimonial(testimonial) {
            document.getElementById('edit_id').value = testimonial.id;
            document.getElementById('edit_customer_name').value = testimonial.customer_name;
            document.getElementById('edit_text').value = testimonial.text;
            document.getElementById('edit_rating').value = testimonial.rating;
            document.getElementById('edit_status').value = testimonial.status;
            
            new bootstrap.Modal(document.getElementById('editTestimonialModal')).show();
        }

        function deleteTestimonial(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteTestimonialModal')).show();
        }
    </script>
</body>
</html> 