<?php
require_once 'config/db.php';

// Test admin login
$email = 'admin@zeroman.com';
$password = md5('admin123');

$query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn, $query);

echo "<h2>Login Test</h2>";
echo "Email: $email<br>";
echo "Password (MD5): $password<br>";
echo "Query: $query<br><br>";

if (mysqli_num_rows($result) == 1) {
    $admin = mysqli_fetch_assoc($result);
    echo "<span style='color:green'>✓ Login successful!</span><br>";
    echo "Admin details:<br>";
    echo "ID: " . $admin['id'] . "<br>";
    echo "Username: " . $admin['username'] . "<br>";
    echo "Email: " . $admin['email'] . "<br>";
} else {
    echo "<span style='color:red'>✗ Login failed! No matching admin found.</span><br>";
    
    // Show what's in the database
    $all = mysqli_query($conn, "SELECT * FROM admins");
    echo "<br>Current admins in database:<br>";
    while($row = mysqli_fetch_assoc($all)) {
        echo "ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}, Password: {$row['password']}<br>";
    }
}
?>