<?php
header('Content-Type: text/plain');
$mysqli = new mysqli("localhost", "root", "", "security_app");
if ($mysqli->connect_errno) die("Connect failed: " . $mysqli->connect_error);

$res = $mysqli->query("SELECT id, LENGTH(encrypted_aes_key_sender) as sender_key_len, encrypted_aes_key_sender FROM messages ORDER BY id DESC LIMIT 5");
while ($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . "\n";
    echo "Key Len: " . $row['sender_key_len'] . "\n";
    echo "Key Content: " . ($row['encrypted_aes_key_sender'] ?? "NULL") . "\n";
    echo "-------------------\n";
}
$mysqli->close();
