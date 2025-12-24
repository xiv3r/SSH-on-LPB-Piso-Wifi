# SSH on LPB Piso WiFi
For tutorial on how to access LPB Piso WiFi's SSH, please refer to this page: [SSH](SSH.md). I reworked this repository to include modifications, hacking and reverse-engineering stuffs.

> LPB Piso WiFi investigation is now complete (by December 18, 2025). All of the source code, and documentation can be distributed freely in academic or personal purposes. Protected by WTFPL license.

# MODIFYING LPB PISO WIFI SECTION

This is the main page dedicated for modification of LPB Piso WiFi image file, like accessing SSH and enabling VNC server, as well as reverse-engineering their binary, hacking everything and decryption of .js files.

# FOR STARTERS
LPB Piso WiFi is just a software sitting on top of an old version of Armbian, which basically just a debian-based distribution. But the developer distributes the image file of LPB as the whole OS, so that they can easily install it by just burning it into SD cards.

# WHAT YOU NEED FOR THIS COURSE:
You need basic to intermediate knowledge of Linux/UNIX. Windows? go away! I'm allergic to Windows! MAC OS? Accepted!

Also you need to know the basic usage of debian-based and systemd.

You can use `Termux` for android users, `WSL` for Windows user (any distribution is fine, but<b> Kali Linux is for babies</b>)

`Ghidra` is optional, for disassembling `lpbpisowifi.so` as it holds the key to decrypt .js files.

Of course, you should own a LPB Piso WiFi vending machine. 

### "HELP ME or HOW TO DO THIS" SCENARIO

I will not provide any helps pertaining to usage of scripts so know them yourself.

# LOCATION OF LPB SOFTWARE
Most of the frontend scripts are located inside `/etc/bluetooth/bluetooth` directory while some are just inside `/etc` directory. There is a systemd service that is responsible for running lpb related files. While digging deeper, it turns out that there is one file inside `/usr/local/php7/lpb/php` directory where there is a file called `lpbpisowifi.so`. That file is also considered the "heart" of all operations because it had possessed a hardcoded key which it plays a big role in decrypting files located at `/etc/bluetooth/bluetooth/admin/encrypted` directory (further analysis at the Reports section).

### What exactly is this `lpbpisowifi.so`
I don't know exactly the role of `lpbpisowifi.so` aside from it can decrypt files located at `admin/encrypted/` folder or acting as DRM.

This file was written in Zephir, a high-level language designed specifically for creating PHP extensions without the pain of writing raw C code. I assumed this kind of code used based on the structure and function inside binary (like `zend_register_internal_class`), the produced error message, pointing to source files ending in .zep like this one: (`lpbpisowifi/lpb.zep:33`), and then inside compiled .so file: `php_info_print_table_row(2,"Powered by Zephir","Version 0.12.17-6724dbf");`. 

# Tested LPB Versions:
LPB Piso WiFi version 15.3 and 15.5 are tested and they are exploitable. There are no differences on the critical files, except for additional features for the latest release. Nothing new.

It uses the same hardcoded key, IV and database password, but still unknown for SSH (I don't have much time for this).

Their official account claims about their update, by reworking the whole LPB system. If they somehow fix the vulnerability issues, that will be great.

But I did not test the VLAN version, so expect that some will not work, some might.

# Sections:
[SSH](SSH.md)

[VNC + LXDE Installation](vnc.md)

[Database](database.md)

[Reports](re-eng.md)

[Decrypt](decrypt.md)

[Bruteforce Hacks](brute.md)

[Vulnerability - FAIL OPEN](vuln.md)

[Portal Design Upload Vulnerability](upload.md)

[Protect Your LPB](defense.md)

[ANOTHER VULNERABILITY CONCLUDED](vuln1.md)

[LICENSE](LICENSE.md)

# NEXT GOAL?

Here, we targeted our next brand victim, the PisoFi system. It turns out that it can easily craft RCE exploit for PisoFi than LPB because LPB's internal system somehow has sanitations with their critical code.

# Disclaimer
This information you have seen and the provided software you have obtained are for your personal use only.
