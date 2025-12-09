/**
 * SecureMsg Crypto Module with Logging
 * Wraps CryptoJS, JSEncrypt, and Forge
 */
window.SecureMsg = {

    /**
     * Log a debug message
     */
    log: function (action, details) {
        console.log(`[SecureMsg] ${action}:`, details);
        const event = new CustomEvent('securemsg-log', {
            detail: {
                timestamp: new Date().toLocaleTimeString(),
                action: action,
                details: details
            }
        });
        window.dispatchEvent(event);
    },

    /**
     * Generate RSA 2048 key pair using Web Crypto API (Blazing Fast)
     * Returns Promise<{private: string, public: string}>
     */
    generateKeys: async function () {
        this.log('KeyGen', 'Starting Native Web Crypto RSA generation...');
        try {
            const keyPair = await window.crypto.subtle.generateKey(
                {
                    name: "RSA-OAEP",
                    modulusLength: 2048,
                    publicExponent: new Uint8Array([1, 0, 1]),
                    hash: "SHA-256",
                },
                true,
                ["encrypt", "decrypt"]
            );

            // Export to PEM
            const pubData = await window.crypto.subtle.exportKey("spki", keyPair.publicKey);
            const privData = await window.crypto.subtle.exportKey("pkcs8", keyPair.privateKey);

            const pubPem = this.toPem(pubData, "PUBLIC KEY");
            const privPem = this.toPem(privData, "PRIVATE KEY");

            this.log('KeyGen', 'Native keys generated instantly');
            return {
                public: pubPem,
                private: privPem
            };
        } catch (e) {
            this.log('Error', 'Native KeyGen failed, falling back: ' + e.message);
            // Fallback to JSEncrypt if ancient browser (unlikely)
            return new Promise((resolve, reject) => {
                const crypt = new JSEncrypt({ default_key_size: 2048 });
                crypt.getKey(() => resolve({ private: crypt.getPrivateKey(), public: crypt.getPublicKey() }));
            });
        }
    },

    toPem: function (buffer, label) {
        const str = String.fromCharCode(...new Uint8Array(buffer));
        const b64 = window.btoa(str);
        const lines = b64.match(/.{1,64}/g).join('\n');
        return `-----BEGIN ${label}-----\n${lines}\n-----END ${label}-----`;
    },

    /**
     * Encrypt content using AES (CryptoJS)
     * @param {string} content 
     * @param {string} passphrase 
     * @returns {string} Encrypted content
     */
    encryptAES: function (content, passphrase) {
        this.log('EncryptAES', 'Encrypting content with AES...');
        const res = CryptoJS.AES.encrypt(content, passphrase).toString();
        this.log('EncryptAES', `Result: ${res.substring(0, 20)}...`);
        return res;
    },

    /**
     * Decrypt content using AES (CryptoJS)
     * @param {string} encryptedContent 
     * @param {string} passphrase 
     * @returns {string} Decrypted content
     */
    decryptAES: function (encryptedContent, passphrase) {
        this.log('DecryptAES', `Decrypting ${encryptedContent.substring(0, 20)}...`);
        try {
            const bytes = CryptoJS.AES.decrypt(encryptedContent, passphrase);
            const str = bytes.toString(CryptoJS.enc.Utf8);
            if (!str) throw new Error("Empty result (wrong password?)");
            this.log('DecryptAES', 'Success');
            return str;
        } catch (e) {
            this.log('Error', 'AES Decryption failed');
            throw e;
        }
    },

    /**
     * Encrypt Data (usually AES Key) using RSA Public Key
     * @param {string} content 
     * @param {string} publicKeyPEM 
     * @returns {string} Base64 encrypted string
     */
    encryptRSA: function (content, publicKeyPEM) {
        this.log('EncryptRSA', 'Encrypting AES Key with RSA Public Key...');
        const encryptor = new JSEncrypt();
        encryptor.setPublicKey(publicKeyPEM);
        const res = encryptor.encrypt(content);
        this.log('EncryptRSA', 'Encrypted key length: ' + (res ? res.length : 0));
        return res;
    },

    /**
     * Decrypt Data (usually AES Key) using RSA Private Key
     * @param {string} encryptedContent 
     * @param {string} privateKeyPEM 
     * @returns {string} Decrypted string
     */
    decryptRSA: function (encryptedContent, privateKeyPEM) {
        this.log('DecryptRSA', 'Decrypting AES Key with RSA Private Key...');
        const decryptor = new JSEncrypt();
        decryptor.setPrivateKey(privateKeyPEM);
        const res = decryptor.decrypt(encryptedContent);
        this.log('DecryptRSA', res ? 'Key Recovered' : 'Failed');
        return res;
    },

    /**
     * Sign a message (SHA256 hash) with Private Key
     * Uses Forge for robustness as JSEncrypt signing varies
     */
    signMessage: function (message, privateKeyPEM) {
        this.log('Sign', 'Signing message hash...');
        const md = forge.md.sha256.create();
        md.update(message, 'utf8');
        md.update(message, 'utf8');
        // Sanitize PEM: Forge hates \r
        const cleanKey = privateKeyPEM.trim().replace(/\r/g, '');
        const pkey = forge.pki.privateKeyFromPem(cleanKey);
        const signature = pkey.sign(md);
        const b64 = forge.util.encode64(signature);
        this.log('Sign', 'Signature generated');
        return b64;
    },

    /**
     * Verify a signature
     * @param {string} message 
     * @param {string} signatureBase64 
     * @param {string} publicKeyPEM 
     * @returns {boolean} Valid or not
     */
    verifySignature: function (message, signatureBase64, publicKeyPEM) {
        try {
            this.log('Verify', 'Verifying signature...');
            const md = forge.md.sha256.create();
            md.update(message, 'utf8');
            const signature = forge.util.decode64(signatureBase64);
            const pubKey = forge.pki.publicKeyFromPem(publicKeyPEM);
            const valid = pubKey.verify(md.digest().bytes(), signature);
            this.log('Verify', valid ? 'Signature VALID' : 'Signature INVALID');
            return valid;
        } catch (e) {
            this.log('Verify', 'Verification Error: ' + e.message);
            return false;
        }
    },

    /**
     * Generate Random AES Key
     */
    generateRandomKey: function () {
        this.log('KeyGen', 'Generating random session key...');
        return CryptoJS.lib.WordArray.random(256 / 8).toString();
    }
};
