# What are these files:
These are used to unlock secrets of the files inside `/admin/encrypted` directory.

- `mass_decrypt.php` is used to decrypt the .js files (RECOMMENDED).
- `deobfuscator.php` is used to translate hex and octal character into ASCII/UNICODE ones, or it means human-readable (RECOMMENDED
- `encrypt_lpb.php` is used to encrypt the file.
- `db_parasite.php` is used to output selected databases
- `god_mode.php` is used to control and modify databases
- `exploit_lpb.py` is used to exploit execute.js file to give us admin password
- `block_internet.py` is used to disconnect the LPB Piso WiFi from the ISP router. It should be used first before `exploit_lpb.py` unless the router is already disconnected to internet.

# Useless Files
- `de-obfuscate.py` this can deobfuscate the character, but it can generate broken output file which it will not properly load on browser. Use `deobfuscator.php` instead.
- `decrypt_lpb.php`, a prototype version that was used to try what kind of encryption is used on this system. Not needed anymore. use `mass_decrypt.php` instead.
