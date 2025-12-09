<?php
// LPB Wifi Encryptor
// By: Colton Silva (chinawaterstealers).
// Cipher: AES-128-CBC
// Key: $2y$12lemmuelDOE (First 16 bytes)
// IV: $2y$12bantolinao

echo"[!] By Colton Silva (chinawaterstealers).\n";

if ($argc < 3) {
    die("Usage: php encrypt_lpb.php <input_file> <output_file>\n");
}

$input_file = $argv[1];
$output_file = $argv[2];

if (!file_exists($input_file)) {
    die("Error: Input file not found.\n");
}

$data = file_get_contents($input_file);

// Configuration
$full_key_string = '$2y$12lemmuelDOEEfH6y7cYi0WqieViPg';
$key = substr($full_key_string, 0, 16);
$iv = '$2y$12bantolinao';
$cipher = 'AES-128-CBC';

echo "[*] Encrypting $input_file...\n";

$encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);

if ($encrypted === false) {
    die("Error: Encryption failed.\n");
}

$encoded = base64_encode($encrypted);

file_put_contents($output_file, $encoded);

echo "[+] Success! Encrypted data saved to $output_file\n";
?>
