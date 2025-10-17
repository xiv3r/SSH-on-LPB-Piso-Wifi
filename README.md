# SSH for LPB Piso WiFi
This simple approach allows you to access internal system that are beyond LPB's admin settings, but like what?

- access processes, scripts, configuration or any
- execute installed armbian (or Linux) binaries
- monitor what's going on with it's operation, it's like giving you much more detailed logs

LPB Piso Wifi software is based on the older and extinct version of Armbian (Debian Stretch 9)

# Problems
LPB Piso WiFi images are distributed as headless server, and it does not have any kernel modules (driver) for HDMI. Also it is rare to find the specific kernel version, like from orange pi one's `4.19.62-sunxi` to be able to compile it with support for HDMI displays in accordance with patches for different SBCs.

In it's admin dashboard, it has restricted terminal tool in which even the `apt` command is useless.

So our solution is to use `ssh` to communicate to the LPB's system which is based on armbian.

# HowTo - Change Root Password
Before setting up ssh to connect remotely, we need to change the root password because we don't even know the password for it.

So we must change password directly from `shadow` file located at `/etc`.

To do this, you will need Linux distributions, a termux for Android devices, or debian-based WSL for windows system; and a card reader for your SD CARD. And don't forget to be a root user by `sudo -i` or `su`.

Then you need to generate a SHA-512 hash based on your chosen password:

```
HASH=$(openssl passwd -6 'changeme')
```

and replace `changeme` with your own password.

Then, confirm it:

```
echo "$HASH"
```

Then, you need to create a backup file for `shadow` in case you made a mistake:

```
sudo cp /mnt/lpb/etc/shadow /mnt/lpb/etc/shadow.bak
```

Now, replace the hash and change the owner of the file:
```
sudo sed -i "s|^root:[^:]*:|root:${HASH}:|" /mnt/lpb/etc/shadow
sudo chown root:root /mnt/lpb/etc/shadow
sudo chmod 600 /mnt/lpb/etc/shadow
```
Note that depending on where does your system mount your SD card, you may locate it to `media` instead of copying this with `/mnt/lpb/etc`

# HowTo - Connect it to ssh

Now you change root password, you can connect LPB Piso Wifi to ssh by these steps:

Install `ssh` if you don't have any.

Then do this:
```
ssh root@IP_ADDRESS -p 320
```
where `IP_ADDRESS` is the assigned IP Address of the Single Board Computer (LPB Piso Wifi) from your main router (ISP's GPON).

And if you ever asked for this:
```
The authenticity of host '[IP_ADDRESS]:320 ([IP_ADDRESS]:320)' can't be established.
ED25519 key fingerprint is SHA256:aU9EB/zhge9nvfzATSna9VndZHtVFy83Iiifwdug3pk.
This key is not known by any other names.
Are you sure you want to continue connecting (yes/no/[fingerprint])?

```

Just type `yes`.

Anyway, LPB Piso Wifi image distribution uses port 320 as default for ssh instead of port 22.

# FA-Q
I can't download or update anything from `apt`, why?

> Of course this uses an old and crusty version of Armbian so repositories about it and Debian stretch 9 does not exist. You need to change the URL from `sources.list` located at `/etc/apt`.

# Disclaimer
Do not EVER attempt to connect to the stationed PISO WIFI via `ssh` if you don't even owned them.
