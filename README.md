# MODIFYING LPB PISO WIFI SECTION

This is the main page dedicated for modification of LPB Piso WiFi image file, like accessing SSH and enabling VNC server, as well as reverse-engineering their binary, and decryption of .js files.

# LOCATION OF LPB SOFTWARE
Most of the frontend scripts are located inside `/etc/bluetooth/bluetooth` directory while some are just inside `/etc` directory. There is a systemd service that is responsible for running lpb related files. While digging deeper, it turns out that there is one file inside `/usr/local/php7/lob/php` directory where there is a file called `lpbpisowifi.so`. That file is also considered the "heart" of all operations because it had possessed a hardcoded key which it plays a big role in decrypting files located at `/etc/bluetooth/bluetooth/admin/encrypted` directory (further analysis at the Decryption section).

### What exactly is this `lpbpisowifi.so`
I don't know exactly the role of `lpbpisowifi.so` aside from it can decrypt files located at `admin/encrypted/` folder or acting as DRM.

This was written in Zephir, a high-level language designed specifically for creating PHP extensions without the pain of writing raw C code. I assumed this kind of code used based on the structure and function inside binary (like `zend_register_internal_class`), and the produced error message, pointing to source files ending in .zep like this one: (`lpbpisowifi/lpb.zep:33`). 


# Sections:
[SSH](SSH.md)
