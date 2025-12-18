# Upload Vulnerability

There is a vulnerability where you can upload files on Portal Design within Admin Dashboard. It accepts jpg, apk, MP3, MP4, and js; and any other file extensions are not supported. Because .js is accepted, regardless of it's content (malicious), an attacker can use this advantage to execute payload inside LPB system without accessing SSH, as hacking admin dashboard is easier than SSH.

Uploaded files goes to `/assets/images` directory, and writable output file can go to `/admin`.

As we discussed on Another Vulnerability section, `www-data` is permitted to execute binaries in `/bin`, `/sbin`, or `/usr/bin` directory, view configs at `/etc` directory, or anything you can't imagine.

# Vulnerability Example:

In this example, we can do this vulnerability via modifying encrypted `terminal.js` and `termiexec.js` to launch our customized terminal browser, in which the original version does limits you to 5 commands (`wget`, `ping`, `unzip`, `rm`, and `apt`), but with our modified version, you can execute all linux commands, although other interactive TUIs, like `htop` requires you to run in Linux terminal.

> The Terminal under Extra Features is only available for version 15.5, and tested not working on 15.4 below because of it's missing internal dependencies required to communicate into internal Linux system. Sadly most of them uses stable version of 15.3, so you need to invent your own exploit in .js.

From Admin Dashboard, Navigate the menu to the right, scroll down, and then under SETTINGS, click Portal Design. Upload both already encrypted `terminal.js` and `termiexec.js` (which is located at `/EXPLOIT` directory of this repository) at the Images. And then after that, go to http://10.0.0.1/admin/index?action=../../assets/images/terminal.js. To execute a command, tap at the black input box, and do this example:

```
/bin/usr/whoami
```

where you will see the information about the current logged-in user. If you see `www-data`, then you did it!

# How about if I want to be a ROOT USER?

Easy! This does not required root password. Just do: 

```
/bin/usr/sudo binary
```

where `binary` is the command you wish to execute.

For those attackers who wish to change the SSH password easily, use this reference [here](SSH.md).

# Before you Upload .js file

You still need to encrypt .js file for proper rendering, if you make changes or add a new .js file.

# Disclaimer
Do this to your own LPB Piso WiFi.
