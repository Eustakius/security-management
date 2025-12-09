<?php
$mysqli = new mysqli("localhost", "root", "", "security_app");

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Execute commands sequentially
$commands = [
    "SET FOREIGN_KEY_CHECKS = 0",
    "TRUNCATE TABLE messages",
    "TRUNCATE TABLE users",
    "SET FOREIGN_KEY_CHECKS = 1"
];

echo "<h3>Resetting Database...</h3>";

foreach ($commands as $sql) {
    if ($mysqli->query($sql) === TRUE) {
        echo "✅ Success: $sql <br>";
    } else {
        echo "❌ Error: " . $mysqli->error . " (Query: $sql) <br>";
    }
}

echo "<h3>Done! You can now Register a new account.</h3>";
$mysqli->close();
