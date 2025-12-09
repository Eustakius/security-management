<?php

// Save this as check_db.php in public root
$mysqli = new mysqli("localhost", "root", "", "security_app");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

echo "<h3>Table: messages</h3>";
$result = $mysqli->query("DESCRIBE messages");

echo "<pre>";
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";

echo "<h3>Existing Rows (First 5)</h3>";
$result = $mysqli->query("SELECT * FROM messages LIMIT 5");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}

$mysqli->close();
