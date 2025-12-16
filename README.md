# SSH on LPB Piso WiFi
For tutorial on how to access LPB Piso WiFi's SSH, please refer to this page: [SSH](SSH.md). I update this repository to include modifications and reverse-engineering stuffs.

# MODIFYING LPB PISO WIFI SECTION

This is the main page dedicated for modification of LPB Piso WiFi image file, like accessing SSH and enabling VNC server, as well as reverse-engineering their binary, and decryption of .js files.

# FOR STARTERS
LPB Piso WiFi is just a software sitting on top of an old version of Armbian, which basically just a debian-based distribution. But the developer distributes the image file of LPB as the whole OS, so that they can easily install it by just burning it into SD cards.

# WHAT YOU NEED FOR THIS COURSE:
You need basic to intermediate knowledge of Linux/UNIX. Windows? go away! I'm allergic to Windows!

Also you need to know the basic usage of debian-based and systemd.

You can use `Termux` for android users, `WSL` for Windows user (any distribution is fine, but<b> Kali Linux is for babies</b>)

`Ghidra` is optional, for disassembling `lpbpisowifi.so` as it holds the key to decrypt .js files.

Of course, you should own a LPB Piso WiFi vending machine. 

# LOCATION OF LPB SOFTWARE
Most of the frontend scripts are located inside `/etc/bluetooth/bluetooth` directory while some are just inside `/etc` directory. There is a systemd service that is responsible for running lpb related files. While digging deeper, it turns out that there is one file inside `/usr/local/php7/lpb/php` directory where there is a file called `lpbpisowifi.so`. That file is also considered the "heart" of all operations because it had possessed a hardcoded key which it plays a big role in decrypting files located at `/etc/bluetooth/bluetooth/admin/encrypted` directory (further analysis at the Reports section).

### What exactly is this `lpbpisowifi.so`
I don't know exactly the role of `lpbpisowifi.so` aside from it can decrypt files located at `admin/encrypted/` folder or acting as DRM.

This file was written in Zephir, a high-level language designed specifically for creating PHP extensions without the pain of writing raw C code. I assumed this kind of code used based on the structure and function inside binary (like `zend_register_internal_class`), the produced error message, pointing to source files ending in .zep like this one: (`lpbpisowifi/lpb.zep:33`), and then inside compiled .so file: `php_info_print_table_row(2,"Powered by Zephir","Version 0.12.17-6724dbf");`. 

# Tested LPB Versions:
LPB Piso WiFi version 15.3 and 15.5 are tested and they are exploitable. There are no differences on the critical files, except for additional features for the latest release. Nothing new.

It uses the same hardcoded key, IV and database password, but still unknown for SSH (I don't have much time for this).

# Sections:
[SSH](SSH.md)

[Database](database.md)

[Reports](re-eng.md)

[Decrypt](decrypt.md)

[Bruteforce Hacks](brute.md)

[Vulnerability - FAIL OPEN](vuln.md)

[ANOTHER VULNERABILITY UNDER INVESTIGATION](vuln1.md)



# Disclaimer
This information you have seen and the provided software you have obtained are for your personal use only. Do not redistribute your own modded version of lpb anywhere as it may alarm the owner about this. Also you can fork this repository, but do not talk about this on [PHCorner](https://phcorner.org/) or facebook group pertaining to Piso WiFi groups or group chats as it can cause trouble on the owner's small time business.
