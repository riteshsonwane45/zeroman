<?php
session_start();
require_once 'config/db.php';

// Check if user is logged in as shop
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'shop') {
    header("Location: login.php");
    exit();
}

$shop_id = $_SESSION['user_id'];

// Get shop details
$shop_query = mysqli_query($conn, "SELECT * FROM shops WHERE id = '$shop_id'");
$shop = mysqli_fetch_assoc($shop_query);

// Get statistics for this shop
$total_products = 0;
$total_customers = 0;
$total_sales = 0;

$products_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE shop_id = '$shop_id'");
if ($products_result) {
    $total_products = mysqli_fetch_assoc($products_result)['count'];
}

$customers_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM customers WHERE shop_id = '$shop_id'");
if ($customers_result) {
    $total_customers = mysqli_fetch_assoc($customers_result)['count'];
}

$sales_result = mysqli_query($conn, "SELECT SUM(total_amount) as total FROM sales WHERE shop_id = '$shop_id'");
if ($sales_result) {
    $total_sales = mysqli_fetch_assoc($sales_result)['total'] ?? 0;
}

// Get recent products
$recent_products = mysqli_query($conn, "SELECT * FROM products WHERE shop_id = '$shop_id' ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Dashboard - <?php echo $shop['shop_name'] ?? 'Shop Owner'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 25px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .shop-header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .quick-action-btn {
            margin: 5px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center py-4">
            <h4><?php echo $shop['shop_name'] ?? 'My Shop'; ?></h4>
            <p class="mb-0 small">Owner: <?php echo $shop['owner_name'] ?? 'Shop Owner'; ?></p>
        </div>
        <hr class="bg-white">
        <nav class="nav flex-column">
            <a class="nav-link active" href="dashboard_shop.php">
                <i class="fas fa-dashboard me-2"></i>Dashboard
            </a>
            <a class="nav-link" href="products.php">
                <i class="fas fa-boxes me-2"></i>Products
            </a>
            <a class="nav-link" href="customers.php">
                <i class="fas fa-users me-2"></i>Customers
            </a>
            <a class="nav-link" href="sales.php">
                <i class="fas fa-chart-line me-2"></i>Sales
            </a>
            <a class="nav-link" href="reports_shop.php">
                <i class="fas fa-file-alt me-2"></i>Reports
            </a>
            <a class="nav-link" href="shop_settings.php">
                <i class="fas fa-cog me-2"></i>Settings
            </a>
            <hr class="bg-white">
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="shop-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2>Welcome back, <?php echo $shop['owner_name'] ?? 'Shop Owner'; ?>! 👋</h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-envelope me-2"></i><?php echo $shop['email'] ?? 'No email'; ?> | 
                        <i class="fas fa-phone me-2"></i><?php echo $shop['phone'] ?? 'No phone'; ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-success px-3 py-2">Active Account</span>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Products</h6>
                            <h2 class="mb-0"><?php echo $total_products; ?></h2>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-boxes fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Customers</h6>
                            <h2 class="mb-0"><?php echo $total_customers; ?></h2>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Sales</h6>
                            <h2 class="mb-0">$<?php echo number_format($total_sales, 2); ?></h2>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-dollar-sign fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Products -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Products</h5>
            </div>
            <div class="card-body">
                <?php if ($recent_products && mysqli_num_rows($recent_products) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Category</th>
                                    <th>Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($product = mysqli_fetch_assoc($recent_products)): ?>
                                <tr>
                                    <td><?php echo $product['product_name']; ?></td>
                                    <td>$<?php echo $product['price']; ?></td>
                                    <td><?php echo $product['quantity']; ?></td>
                                    <td><?php echo $product['category'] ?? 'N/A'; ?></td>
                                    <td><?php echo date('d M Y', strtotime($product['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">No products yet. <a href="add_product.php">Add your first product</a></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="add_product.php" class="btn btn-primary quick-action-btn">
                            <i class="fas fa-plus-circle me-2"></i>Add Product
                        </a>
                        <a href="add_customer.php" class="btn btn-success quick-action-btn">
                            <i class="fas fa-user-plus me-2"></i>Add Customer
                        </a>
                        <a href="new_sale.php" class="btn btn-info quick-action-btn">
                            <i class="fas fa-shopping-cart me-2"></i>New Sale
                        </a>
                        <a href="inventory.php" class="btn btn-warning quick-action-btn">
                            <i class="fas fa-warehouse me-2"></i>Check Inventory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>