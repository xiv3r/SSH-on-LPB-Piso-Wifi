<?php
// LPB Wifi Mass Decryptor
// By Colton Silva (chinawaterstealers)
// Cipher: AES-128-CBC
// Key: $2y$12lemmuelDOE (First 16 bytes of full key)
// IV: $2y$12bantolinao

$input_dir = __DIR__ . '/encrypted';
$output_dir = __DIR__ . '/decrypted';

// Configuration
$full_key_string = '$2y$12lemmuelDOEEfH6y7cYi0WqieViPg';
$key = substr($full_key_string, 0, 16); // 16 bytes
$iv = '$2y$12bantolinao';               // 16 bytes
$cipher = 'AES-128-CBC';

if (!is_dir($input_dir)) {
    die("Error: Input directory $input_dir not found.\n");
}

if (!is_dir($output_dir)) {
    mkdir($output_dir, 0777, true);
}

$files = scandir($input_dir);
$count = 0;
$success = 0;

echo "[*] Starting Mass Decryption...\n";
echo "[*] Cipher: $cipher\n";
echo "[*] Key: $key\n";
echo "[*] IV:  $iv\n\n";

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    $input_path = $input_dir . '/' . $file;
    $output_path = $output_dir . '/' . $file; // Keep original filename (e.g., .js) 
    
    // Check if it's a file
    if (!is_file($input_path)) continue;

    $content = file_get_contents($input_path);
    
    // Attempt Base64 Decode (Since we saw adminexec.js was Base64)
    // Note: Some files might NOT be Base64 encoded if they are just assets?
    // But the C code treated portal.js as input to decrypt.
    
    $ciphertext = base64_decode($content);
    
    if (!$ciphertext) {
        echo "[-] Skipping $file: Not valid Base64 or empty.\n";
        continue;
    }
    
    $decrypted = openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    
    if ($decrypted === false) {
        echo "[!] Failed to decrypt: $file (OpenSSL Error)\n";
    } else {
        // Validation: Check if it looks like PHP or text
        $preview = substr($decrypted, 0, 20);
        $is_php = (strpos($decrypted, '<?php') !== false) ? "PHP" : "Data";
        
        file_put_contents($output_path, $decrypted);
        echo "[+] Decrypted: $file [$is_php] -> saved.\n";
        $success++;
    }
    $count++;
}

echo "\n[*] Complete. Processed $count files. Successful: $success.\n";
?>
