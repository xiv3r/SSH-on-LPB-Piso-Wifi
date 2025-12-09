<?php
// Trying derived keys

$file = 'encrypted/portal.js';
$key_raw = '$2y$12lemmuelDOEEfH6y7cYi0WqieViPg';
$iv_raw  = 'bantolinao';

if (!file_exists($file)) {
    die("Error: File $file not found.\n");
}

$content = file_get_contents($file);
$ciphertext = base64_decode($content);

if (!$ciphertext) die("Base64 decode failed.\n");

// Potential Key/IV Derivations
$candidates = [];

// 1. Literal + Padding
$candidates[] = [
    'cipher' => 'AES-256-CBC',
    'key'    => substr($key_raw, 0, 32),
    'iv'     => str_pad($iv_raw, 16, "\0")
];

// 2. Hashed (SHA256 Key / MD5 IV)
$candidates[] = [
    'cipher' => 'AES-256-CBC',
    'key'    => hash('sha256', $key_raw, true), // 32 bytes
    'iv'     => hash('md5', $iv_raw, true)      // 16 bytes
];

// 3. Hashed (MD5 Key / MD5 IV) - AES-128
$candidates[] = [
    'cipher' => 'AES-128-CBC',
    'key'    => hash('md5', $key_raw, true),    // 16 bytes
    'iv'     => hash('md5', $iv_raw, true)      // 16 bytes
];

// 4. Swapped? Key=bantolinao
$candidates[] = [
    'cipher' => 'AES-256-CBC',
    'key'    => hash('sha256', $iv_raw, true),
    'iv'     => substr($key_raw, 0, 16)
];

// 5. C Code specific: Maybe the IV is the first 16 bytes of file, and Key is bantolinao?
$file_iv = substr($ciphertext, 0, 16);
$file_data = substr($ciphertext, 16);

$candidates[] = [
    'cipher' => 'AES-256-CBC',
    'key'    => hash('sha256', $iv_raw, true),
    'iv'     => $file_iv,
    'data'   => $file_data
];

// 6. C Code RECONSTRUCTED IV
$iv_exact = '$2y$12bantolinao'; // 16 bytes exactly

$candidates[] = [
    'cipher' => 'AES-256-CBC',
    'key'    => substr($key_raw, 0, 32),
    'iv'     => $iv_exact
];

$candidates[] = [
    'cipher' => 'AES-256-CBC',
    'key'    => $key_raw, // Try full key just in case
    'iv'     => $iv_exact
];

// 7. AES-128 Candidates
$key_16 = substr($key_raw, 0, 16);

$candidates[] = [
    'cipher' => 'AES-128-CBC',
    'key'    => $key_16,
    'iv'     => $iv_exact
];

$candidates[] = [
    'cipher' => 'AES-128-ECB',
    'key'    => $key_16,
    'iv'     => ''
];

echo "[*] Testing " . count($candidates) . " combinations...\n";

foreach ($candidates as $i => $c) {
    $data = isset($c['data']) ? $c['data'] : $ciphertext;
    $res = openssl_decrypt($data, $c['cipher'], $c['key'], OPENSSL_RAW_DATA, $c['iv']);
    
    if ($res) {
        // Check for PHP header or readable text
        if (strpos($res, '<?php') !== false || strpos($res, 'function') !== false) {
            echo "\n[+] SUCCESS! Combination #$i worked!\n";
            echo "Cipher: " . $c['cipher'] . "\n";
            echo "--- START --- \n";
            echo substr($res, 0, 200) . "\n";
            file_put_contents('decrypted_final.php', $res);
            exit;
        }
    }
    // echo "[-] Combo $i failed.\n";
}

echo "\n[-] All combinations failed.\n";
?>
