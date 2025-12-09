<?php
// RAW PHP DIAGNOSTIC SCRIPT (No Framework)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "security_app";

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    die("❌ DB Connection Failed: " . $mysqli->connect_error);
}
echo "✅ DB Connection OK<br>";

// 1. Check Schema
echo "<h3>1. Checking Schema</h3>";
$res = $mysqli->query("SHOW COLUMNS FROM users");
$found = false;
if ($res) {
    echo "Columns in 'users':<br><ul>";
    while ($row = $res->fetch_assoc()) {
        echo "<li>" . $row['Field'] . " (" . $row['Type'] . ")</li>";
        if ($row['Field'] === 'encrypted_private_key') $found = true;
    }
    echo "</ul>";
} else {
    echo "❌ Could not show columns: " . $mysqli->error . "<br>";
}

if ($found) {
    echo "✅ 'encrypted_private_key' column EXISTS.<br>";
} else {
    echo "❌ 'encrypted_private_key' column MISSING.<br>";
}

// 2. Try Insert
echo "<h3>2. Testing Insert</h3>";
$testUser = "diag_" . time();
$testPass = password_hash("password", PASSWORD_BCRYPT);
$sql = "INSERT INTO users (username, password, public_key, encrypted_private_key) VALUES ('$testUser', '$testPass', 'dummy_pub', 'dummy_priv')";

if ($mysqli->query($sql) === TRUE) {
    echo "✅ Insert SUCCESS! (ID: " . $mysqli->insert_id . ")<br>";
} else {
    echo "❌ Insert FAILED: " . $mysqli->error . "<br>";
}

// 3. List Users
echo "<h3>3. Current Users</h3>";
$res = $mysqli->query("SELECT id, username, encrypted_private_key FROM users ORDER BY id DESC LIMIT 5");
if ($res->num_rows > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Has Key?</th></tr>";
    while ($row = $res->fetch_assoc()) {
        $hasKey = !empty($row['encrypted_private_key']) ? "YES" : "NO";
        echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>$hasKey</td></tr>";
    }
    echo "</table>";
} else {
    echo "⚠️ No users found in table.<br>";
}

$mysqli->close();
