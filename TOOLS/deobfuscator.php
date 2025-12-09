<?php
// De-obfuscator using PHP Tokenizer
// By Colton Silva (chinawaterstealers).
// Usage: php deobfuscator.php <input_file> <output_file>

echo "[!] By Colton Silva (chinawaterstealers).\n";

if ($argc < 3) {
    die("Usage: php deobfuscator.php <input> <output>\n");
}

$input = $argv[1];
$output = $argv[2];

$code = file_get_contents($input);

// Tokenizer requires <?php tag to work correctly if it's missing
$has_tag = false;
if (strpos($code, '<?php') === false) {
    $code = "<?php\n" . $code;
    $has_tag = true;
}

$tokens = token_get_all($code);
$new_code = '';

foreach ($tokens as $token) {
    if (is_array($token)) {
        // [ID, Content, Line]
        $id = $token[0];
        $text = $token[1];

        if ($id === T_CONSTANT_ENCAPSED_STRING) {
            // This is a string literal like "\x41..."
            // We want to evaluate it to its raw form, then re-export it cleanly.
            
            // Check if it's double quoted
            if ($text[0] === '"') {
                // Dangerous: eval() the string to decode hex
                // Safe approach: strip quotes and stripslashes? No, complex.
                // Let's rely on PHP to parse the hex for us.
                try {
                    // We wrap it in a variable assignment to eval it safely
                    // $val = "\x41"; return $val;
                    $eval_code = "return " . $text . ";";
                    $decoded = eval($eval_code);
                    
                    // Now re-export it as a clean string
                    // var_export uses single quotes usually, which is cleaner
                    $text = var_export($decoded, true);
                } catch (Exception $e) {
                    // If eval fails, keep original
                } catch (ParseError $e) {
                    // Syntax error in string? Keep original
                }
            }
        }
        $new_code .= $text;
    } else {
        // Single character tokens like ; { }
        $new_code .= $token;
    }
}

// Remove the temp tag if we added it
if ($has_tag) {
    // Remove "<?php\n" (6 chars)
    $new_code = substr($new_code, 6);
}

file_put_contents($output, $new_code);
echo "[+] Deobfuscation Complete -> $output\n";
?>
