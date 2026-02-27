<?php
// Start output buffering at the VERY TOP
ob_start();

require_once 'config/db.php';

// DON'T auto-redirect - let users see the login page with options
// Remove this automatic redirect section:
/*
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] == 'admin') {
        header("Location: dashboard_admin.php");
        exit();
    } elseif ($_SESSION['user_type'] == 'shop') {
        header("Location: dashboard_shop.php");
        exit();
    }
}
*/

// Instead, we'll show the login page with both options
$error = '';

// Rest of your code continues...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    $login_type = $_POST['login_type'];
    
    if ($login_type == 'admin') {
        $query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['email'] = $admin['email'];
            $_SESSION['user_type'] = 'admin';
            
            // Clear output buffer and redirect
            ob_end_clean();
            header("Location: dashboard_admin.php");
            exit();
        } else {
            $error = "Invalid admin credentials!";
        }
    } else {
        $query = "SELECT * FROM shops WHERE email = '$email' AND password = '$password' AND status = 'active'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $shop = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $shop['id'];
            $_SESSION['shop_name'] = $shop['shop_name'];
            $_SESSION['owner_name'] = $shop['owner_name'];
            $_SESSION['email'] = $shop['email'];
            $_SESSION['user_type'] = 'shop';
            
            // Clear output buffer and redirect
            ob_end_clean();
            header("Location: dashboard_shop.php");
            exit();
        } else {
            $error = "Invalid shop credentials or account inactive!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZEROMAN - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0 !important;
            padding: 30px;
        }
        .card-body {
            padding: 40px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
            color: white;
        }
        .login-type {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .login-type-btn {
            flex: 1;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
        }
        .login-type-btn.active {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <h3>ZEROMAN</h3>
                <p class="mb-0">Multi-Shop Management System</p>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="" id="loginForm">
                    <input type="hidden" name="login_type" id="login_type" value="admin">
                    
                    <div class="login-type">
                        <div class="login-type-btn active" onclick="setLoginType('admin')" id="adminBtn">
                            <i class="fas fa-user-shield"></i> Admin
                        </div>
                        <div class="login-type-btn" onclick="setLoginType('shop')" id="shopBtn">
                            <i class="fas fa-store"></i> Shop
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="admin@zeroman.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required value="admin123">
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>

                <div class="text-center mt-3">
                    <a href="register.php">Register New Shop</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setLoginType(type) {
            document.getElementById('login_type').value = type;
            
            if (type === 'admin') {
                document.getElementById('adminBtn').classList.add('active');
                document.getElementById('shopBtn').classList.remove('active');
            } else {
                document.getElementById('shopBtn').classList.add('active');
                document.getElementById('adminBtn').classList.remove('active');
            }
        }
    </script>
</body>
</html>

<?php
// End output buffering
ob_end_flush();
?>