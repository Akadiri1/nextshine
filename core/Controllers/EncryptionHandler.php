<?php

class EncryptionHandler {
    private $encryption_key;
    private $cipher = 'AES-256-CBC';  // AES-256 encryption standard

    public function __construct($key = APP_SECRET_KEY) {
        // Set the encryption key
        $this->encryption_key = hash('sha256', $key);  // Derive a 256-bit key from the input key
    }

    /**
     * Encrypt the given plaintext using AES-256-CBC
     * @param string $plaintext - The data to encrypt
     * @return string - Base64 encoded encrypted data
     */
    public function encrypt($plaintext) {
        // Generate a random initialization vector (IV)
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($iv_length);

        // Encrypt the data using AES-256-CBC
        $ciphertext = openssl_encrypt($plaintext, $this->cipher, $this->encryption_key, 0, $iv);

        // Combine the IV and the ciphertext
        $encrypted_data = base64_encode($iv . $ciphertext);

        return $encrypted_data;
    }

    /**
     * Decrypt the given encrypted data using AES-256-CBC
     * @param string $encrypted_data - Base64 encoded encrypted data
     * @return string - Decrypted plaintext
     */
    public function decrypt($encrypted_data) {
        // Decode the base64-encoded encrypted data
        $encrypted_data = base64_decode($encrypted_data);

        // Extract the IV and the ciphertext from the encrypted data
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $iv = substr($encrypted_data, 0, $iv_length);
        $ciphertext = substr($encrypted_data, $iv_length);

        // Decrypt the ciphertext
        $plaintext = openssl_decrypt($ciphertext, $this->cipher, $this->encryption_key, 0, $iv);

        return $plaintext;
    }
    
    public function hash($value){
        return hash('sha256', $value);
    }
}

// // Example usage
// $key = "your-secret-key";  // Use a strong secret key
// $handler = new EncryptionHandler($key);

// // Encrypt a message
// $encrypted_message = $handler->encrypt("This is a secure message");
// echo "Encrypted: " . $encrypted_message . "\n";

// // Decrypt the message
// $decrypted_message = $handler->decrypt($encrypted_message);
// echo "Decrypted: " . $decrypted_message . "\n";

?>
