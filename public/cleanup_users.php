<?php
$mysqli = new mysqli("localhost", "root", "", "security_app");
$mysqli->query("DELETE FROM users WHERE username = 'Owner'");
$mysqli->query("DELETE FROM users WHERE username LIKE 'diag_%'");
echo "✅ Broken 'Owner' account (and test accounts) deleted.<br>You may now register 'Owner' again.";
$mysqli->close();
