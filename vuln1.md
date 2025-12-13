# ANOTHER VULNERABILITY - UNDER INVESTIGATION

While the Fail-Open is proven via blocking the communication between the client and server, it seems that there is another vulnerability in which it is still under investigation. There is a misconfiguration on where an attacker can send a payload to a Piso WiFi machine and let them execute the command without using SSH through means of RCE.

# Which Misconfiguration?
It is suspected that the `www-data` user is the weakest point (`www-data ALL=NOPASSWD: ALL`), allowing any web shell to escalate to root immediately.

# OK which of them is Vulnerable?
Code in execute.js (`runspeedtest`, `saveportalsettings`) is vulnerable to command injection via `exec()`.

# But why under investigation?
The entry point for the exploit seems problematic, where the web request `POST /index.php action=execute.js` is not reaching the `exec()` call with the injected variable in which initially I abandoned it. For now, I still looking for possible workaround for `execute.js` and finding other entry points.
