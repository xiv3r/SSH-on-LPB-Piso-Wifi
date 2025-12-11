# DETAILED REPORT ABOUT THE /encrypted DIRECTORY
Here is our detailed report about the LPB Piso WiFi software. We are trying to decrypt the .js file located at `/admin/encrypted` directory.

# ENCRYPTION
I thought that the encrypted files uses `base64` (since the insides of .js files looked like base64) or a common kind of AES encryption (as per analysis by both ChatGPT or Claude Sonnet) or `AES-256-CBC` (as what Google Gemini said). I simply decode it by base64 but it produce garbage. But until I checked the `lpbpisowifi.so`, it turns out that it uses `AES-128-CBC` encryption method.

# KEY TO SUCCESS
Now we have a correct encryption method, we need to know the key to open the secrets. Inside `lpbpisowifi.so`, you can see the hardcoded master key: `$2y$12lemmuelDOEEfH6y7cYi0WqieViPg` and Initialization Vector (IV) for randomness: `$2y$12bantolinao`, where the lemmuel from the key and bantolinao from IV are came from the owner's name, `php_info_print_table_row(2,"Author","Lemmuel Bantolinao");`. This key was also used for license verification, as per portal.js. Because I retrieved the key and IV, I can able to audit the decrypted files, in which one of them are vulnerable to fail-open, and some can be easily hooked by the exploit scripts.

# NOW WHAT!
You can now decrypt the .js files by using `mass_decrypt.php`, and change everything you want.

# CONCLUSION
By seeing these vulnerabilities inside file, anyone can alter the scripts and markdowns, especially the most important ones or even they can bypass license verification. You can create your program which integrates well on the lpb backend.
