<?php
$mysqli = new mysqli("localhost", "root", "", "security_app");

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// 1. Add encrypted_private_key column to users
echo "<h3>Updating 'users' table...</h3>";
$check = $mysqli->query("SHOW COLUMNS FROM users LIKE 'encrypted_private_key'");
if ($check->num_rows == 0) {
    $sql = "ALTER TABLE users ADD COLUMN encrypted_private_key TEXT NOT NULL AFTER public_key";
    if ($mysqli->query($sql) === TRUE) {
        echo "✅ Added 'encrypted_private_key' column.<br>";
    } else {
        echo "❌ Error adding column: " . $mysqli->error . "<br>";
    }
} else {
    echo "ℹ️ Column 'encrypted_private_key' already exists.<br>";
}

// 2. Clear old data to prevent null errors or mismatches
echo "<h3>Cleaning old broken data...</h3>";
$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");
$mysqli->query("TRUNCATE TABLE messages");
$mysqli->query("TRUNCATE TABLE users");
$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");
echo "✅ Tables Truncated (Fresh Start).<br>";

$mysqli->close();
