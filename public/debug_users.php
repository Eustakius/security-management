<?php
$mysqli = new mysqli("localhost", "root", "", "security_app");
$result = $mysqli->query("SELECT id, username, LENGTH(password) as pass_len, public_key, encrypted_private_key FROM users");

echo "<h1>User Debug Info</h1>";
echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Pass Hash Len</th><th>Has Pub Key</th><th>Has Priv Key</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
    echo "<td>" . $row['pass_len'] . " (Should be ~60)</td>";
    echo "<td>" . (strlen($row['public_key']) > 50 ? 'YES' : 'NO') . "</td>";
    echo "<td>" . (strlen($row['encrypted_private_key']) > 50 ? 'YES' : 'NO') . "</td>";
    echo "</tr>";
}
echo "</table>";
