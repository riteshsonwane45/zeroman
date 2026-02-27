<?php
require_once 'config/db.php';

echo "<h2>Checking Admin Credentials</h2>";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Database connected successfully!<br><br>";

// Check if admins table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'admins'");
if (mysqli_num_rows($table_check) == 0) {
    die("Admins table does not exist! Please run the SQL file first.");
}

// Show all admins
$query = "SELECT id, username, email, password, created_at FROM admins";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<h3>Admins in database:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Password (MD5)</th><th>Created</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['password'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No admins found in database!";
}

// Test the default admin
echo "<h3>Testing Default Admin:</h3>";
$test_email = 'admin@zeroman.com';
$test_password = md5('admin123');

$test_query = "SELECT * FROM admins WHERE email = '$test_email' AND password = '$test_password'";
$test_result = mysqli_query($conn, $test_query);

if (mysqli_num_rows($test_result) == 1) {
    echo "<span style='color:green'>✓ Default admin exists with correct password!</span>";
} else {
    echo "<span style='color:red'>✗ Default admin not found or password incorrect!</span>";
    
    // Try to insert default admin
    echo "<br><br>Attempting to insert default admin...";
    $insert = "INSERT INTO admins (username, password, email) VALUES ('admin', MD5('admin123'), 'admin@zeroman.com')";
    if (mysqli_query($conn, $insert)) {
        echo "<br><span style='color:green'>✓ Default admin inserted successfully!</span>";
    } else {
        echo "<br><span style='color:red'>✗ Failed to insert: " . mysqli_error($conn) . "</span>";
    }
}
?>