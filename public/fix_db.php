<?php
$mysqli = new mysqli("localhost", "root", "", "security_app");

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Check if column exists first to avoid error
$check = $mysqli->query("SHOW COLUMNS FROM messages LIKE 'encrypted_aes_key_sender'");

if ($check->num_rows == 0) {
    $sql = "ALTER TABLE messages ADD COLUMN encrypted_aes_key_sender TEXT NULL AFTER encrypted_aes_key";
    if ($mysqli->query($sql) === TRUE) {
        echo "Column 'encrypted_aes_key_sender' added successfully";
    } else {
        echo "Error: " . $mysqli->error;
    }
    echo "Column already exists.<br>";
}

echo "<h3>Current Columns:</h3>";
$res = $mysqli->query("SHOW COLUMNS FROM messages");
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . "<br>";
}

$mysqli->close();
