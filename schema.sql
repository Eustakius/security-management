CREATE DATABASE IF NOT EXISTS security_app;
USE security_app;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Bcrypt hash
    public_key TEXT, -- RSA Public Key (PEM format)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    encrypted_content TEXT NOT NULL, -- AES Encrypted content
    encrypted_aes_key TEXT NOT NULL, -- AES Key encrypted with Receiver's Public Key
    iv VARCHAR(255) NOT NULL, -- Initialization Vector for AES
    signature TEXT NOT NULL, -- RSA Signature of the original message hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);
