# Fix <b>UGLY</b> LPB System

If you own a LPB Piso WiFi machine, READ THIS!

Because, at this time of writing (Dec. 2025), LPB is still stuck at version 15.5 released by 2023, and still no fix on their design flaws.

I might suggest you to use other Piso WiFi software brands with recent update and security fixes (preferably ADOPiSoft), or if you still want to use LPB because you bought their crappy license (in which I can use LPB without license), then secure your Piso WiFi system by these advice.

This section will guides you to protect your LPB system.

# Why Changing Admin Password is Useless?

Because it is proven that it can be easily harvested by any means, by SSH, Database or by vulnerabilities. In version 15.5, the phpMyAdmin even existed at the root of the LPB directory which anyone can get the admin password if they get the database username and password. If somehow got managed to enter SSH by bruteforce, and/or default ssh password, that means trouble.

### TIP 1: Internet Must Up and Active

You must ensure that the internet from ISP router is consistent. One event that if you experienced LOS, got disconnected to the internet, or your ethernet cable is damaged, intentionally cut by "<b>TAMBAY</b>" or connected loosely then anyone can abuse the Fail Open vulnerability. if your admin dashboard says "NO INTERNET CONNECTION", you must power off your Piso WiFi immediately to avoid this.

Another thing is don't let your neighbor connect to your main router, in which the LPB is also connected to that router via LAN port as they can perform ARP Spoofing and do the same Fail Open. If you just connect them, kick them. They might know the top-secret.

If you are using VLAN, honestly I am not sure if this works, so let me know if VLAN is also applicable here.

### TIP 2: Change SSH Password

In the event that you installed the LPB by yourself or you just bought it from distributor, by online platform, etc; you can change the root password into something strong, hard to guess ones as they are suspected to bruteforcing attacks.

### TIP 3: Remove phpMyAdmin

If you are using version 15.5 and phpMyAdmin is existed, you must remove it inside `/etc/bluetooth/bluetooth` as this is the easiest way to get your admin password, regardless if you change it.

# Why Changing database Password is not Recommended?

Database Password is hardcoded in /etc/txt and other backend files, so changing it might break other functionalities of LPB. Not really sure about this so I will investigate it further.
