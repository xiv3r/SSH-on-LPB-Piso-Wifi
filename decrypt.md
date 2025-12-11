# DECRYPT .js FILES
To do this, look at the `TOOLS` directory of this repository to access my ultimate tools.

To decrypt files inside `/admin/encrypted' directory, you need to execute:
```
php mass_decrypt.php
```

and you will produce a `/decrypted` folder, which contains the decrypted .js files.

Now some .js files are still unreadable and they are written as hex and octal escape sequences (still the developer is trying to obfuscate the critical parts of the code), like this one:
>```Lpbpisowifi\lpb::genmenu("\57\145\164\x63\x2f\142\154\165\x65\164\157\157\164\x68\57\x62\154\x75\145\x74\x6f\157\164\x68\57\141\x64\x6d\x69\156\x2f\145\156\143\162\x79\x70\164\145\144\x2f\x66\157\x6f\x74\145\162\x2e\152\163"); goto```

so we need to translate them into human-readable characters. To do this, execute:
```
php deobfuscator.php <input> <output>
```

where the `<input>` is the .js file you wish to traslate it. For the `<output>`, you can enter the file name for it.

> The developer used [Yak Pro](https://github.com/pk-fr/yakpro-po) to obfuscate the `/admin/encrypted` and some files at `/api`, and make the code unreadable or logic look like spaghetti (like `goto nonsenselabelname`). Although `deobfuscator.php` can do it's job properly by deobfuscating the hex and octal charachters, it cannot deobfuscate the `goto`. It can unnecessary to fix this but if you want to deobfuscate the spaghetti code (for backend), use [PHPDeobfuscator](https://github.com/simon816/PHPDeobfuscator).

Now you can inspect and modify the code as you wish.

# DEPLOY MODIFIED .js CODE
After you change or modify the code, you need to encrypt it first because the lpb does not render deobfuscated code correctly, it just shows blank webpage. To do this, execute:
```
php encrypt_lpb.php <input_file> <output_file>
```
# Screenshot
![image containing the decryption process](/IMAGES/decrypt.png)This shows the decryption process of .js files inside `/admin/encrypted` directory.
