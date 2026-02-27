<?php
session_start();
require_once 'config/db.php';

// Direct login without form
$email = 'admin@zeroman.com';
$password = md5('admin123');

$query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $admin = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $admin['id'];
    $_SESSION['username'] = $admin['username'];
    $_SESSION['email'] = $admin['email'];
    $_SESSION['user_type'] = 'admin';
    
    echo "Login successful! Redirecting...";
    echo "<script>
        setTimeout(function() {
            window.location.href = 'dashboard_admin.php';
        }, 2000);
    </script>";
    echo "<br>If not redirected, <a href='dashboard_admin.php'>click here</a>";
} else {
    echo "Login failed!";
}
?>