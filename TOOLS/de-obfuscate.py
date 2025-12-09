# By Colton Silva (chinawaterstealers]

import re
import sys
import os

print(f"[!] By Colton Silva (chinawaterstealers).")

def decode_match_hex(match):
    # \xHH -> char
    hex_value = match.group(1)
    return chr(int(hex_value, 16))

def decode_match_octal(match):
    # \OOO -> char
    oct_value = match.group(1)
    # Check if valid octal (0-7)
    try:
        return chr(int(oct_value, 8))
    except ValueError:
        return match.group(0)

def deobfuscate(content):
    # 1. Replace Hex escapes: \x41, \x4a
    # Regex looks for \x followed by 1-2 hex digits
    content = re.sub(r'\\x([0-9a-fA-F]{1,2})', decode_match_hex, content)
    
    # 2. Replace Octal escapes: \141, \012
    # Regex looks for \ followed by 1-3 octal digits
    content = re.sub(r'\\([0-7]{1,3})', decode_match_octal, content)
    
    return content

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(f"Usage: python3 {sys.argv[0]} <file_path>")
        sys.exit(1)

    input_path = sys.argv[1]
    
    if not os.path.exists(input_path):
        print(f"[!] Error: File {input_path} not found.")
        sys.exit(1)

    print(f"[*] Reading {input_path}...")
    try:
        with open(input_path, 'r', encoding='utf-8', errors='ignore') as f:
            data = f.read()
            
        cleaned_data = deobfuscate(data)
        
        output_path = input_path + "_clean.php"
        with open(output_path, 'w', encoding='utf-8') as f:
            f.write(cleaned_data)
            
        print(f"[+] Deobfuscation complete.")
        print(f"[+] Saved to: {output_path}")
        
    except Exception as e:
        print(f"[!] Error: {e}")
