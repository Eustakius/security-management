# Security Management System (Secure Messaging App)

A secure messaging application built with **CodeIgniter 4** that implements **End-to-End Encryption (E2EE)** using a Hybrid Encryption scheme (RSA + AES) and Digital Signatures for message integrity.

### 🔒 Key Features

*   **Secure User Registration**:
    *   Client-side generation of RSA (2048-bit) Public/Private key pairs.
    *   Private keys are encrypted with the user's password before being sent to the server.
    *   The server *never* stores or sees the plain-text private key.
*   **Secure Login**:
    *   Standard authentication with session management.
    *   Retrieval of encrypted keys upon login for client-side decryption usage.
*   **End-to-End Encrypted Messaging**:
    *   **Hybrid Encryption**: High-performance and secure.
        1.  **AES-256 (GCM)** is used to encrypt the actual message content (payload).
        2.  **RSA** is used to encrypt the AES key.
    *   **Forward Secrecy**: Each message is encrypted with a unique, randomly generated AES key.
*   **Message Integrity & Authentication**:
    *   All messages are **digitally signed** using the sender's Private RSA Key.
    *   Recipients verify the signature using the sender's Public Key to ensure the message was not tampered with and truly came from the sender.
*   **Inbox & Sent Box**: View your secure history with automatic client-side decryption.

### 🛠️ Technology Stack

*   **Backend Framework**: CodeIgniter 4.x (PHP 8.1+)
*   **Database**: MySQL / MariaDB
*   **Frontend**: HTML5, Bootstrap (likely), JavaScript (Web Crypto API / Custom Crypto Logic)
*   **Cryptography**:
    *   RSA-OAEP for Key Exchange.
    *   AES-GCM for Content Encryption.
    *   RSA-PSS / ECDSA (depending on JS impl) for Signatures.

### 🚀 Installation & Setup

#### 1. Prerequisites
*   PHP 8.1 or higher
*   Composer installed globally
*   Valid MySQL/MariaDB server

#### 2. Installation
1.  **Clone the repository:**
    ```bash
    git clone <your-repo-url>
    cd security-management
    ```

2.  **Install Dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Setup:**
    Copy the example environment file and configure it.
    ```bash
    cp env .env
    ```
    Open `.env` and configure your database settings:
    ```ini
    CI_ENVIRONMENT = development

    database.default.hostname = localhost
    database.default.database = security_app
    database.default.username = root
    database.default.password = 
    database.default.DBDriver = MySQLi
    ```

#### 3. Database Setup
Import the provided SQL file to create the necessary tables (`users`, `messages`).
*   Import `security_app.sql` into your database tool (phpMyAdmin, DBeaver, etc.).
*   *Alternatively*, if migrations are set up (check `php spark migrate`), use that. *Note: Use the SQL file if migrations are missing.*

#### 4. Run the Application
Start the local development server:
```bash
php spark serve
```
Access the application at: `http://localhost:8080`

### 🛡️ Security Architecture Flow

1.  **Sending a Message:**
    *   Alice wants to send a message to Bob.
    *   App generates a random **AES Key**.
    *   Message is encrypted with **AES Key**.
    *   **AES Key** is encrypted with **Bob's Public Key** (so only Bob can read it).
    *   **AES Key** is *also* encrypted with **Alice's Public Key** (so Alice can read her sent message).
    *   A **Digital Signature** is created using **Alice's Private Key**.
    *   The Encrypted Content, Encrypted Keys, IV, and Signature are sent to the server.

2.  **Receiving a Message:**
    *   Bob logs in and fetches his specific Encrypted Key for the message.
    *   Bob's browser decrypts the **AES Key** using **Bob's Private Key** (unlocked with his password).
    *   The decrypted **AES Key** is used to decrypt the **Message Content**.
    *   Bob's browser verifies the **Signature** using **Alice's Public Key**.

---
*Note: This project is for educational purposes to demonstrate secure communication principles.*
