<?php
// Load CodeIgniter's bootstrap file (Index.php logic simplified)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

use App\Models\UserModel;

echo "<h1>Testing User Insertion</h1>";

$model = new UserModel();

$data = [
    'username' => 'testuser_' . time(),
    'password' => password_hash('password123', PASSWORD_BCRYPT),
    'public_key' => 'dummy_pub_key',
    'encrypted_private_key' => 'dummy_priv_key'
];

echo "Attempting to insert: <pre>" . print_r($data, true) . "</pre>";

if ($model->insert($data)) {
    echo "✅ Success! Insert ID: " . $model->getInsertID();
} else {
    echo "❌ Failed!";
    echo "<h3>Model Errors:</h3><pre>" . print_r($model->errors(), true) . "</pre>";
    
    // Check DB Error
    $db = \Config\Database::connect();
    $error = $db->error();
    echo "<h3>DB Error:</h3><pre>" . print_r($error, true) . "</pre>";
}
