# Vulnerabilities

Do you know that you can harvest administrator's password without connecting on SSH? An attacker can gain unauthorized access to Admin Dashboard of LPB Piso WiFi through this fail-open vulnerability inside `execute.js` file.

# Where does Exactly the "Pussy" within Log-in Screen?
Within the Log-in screen of the administrator, the logic behind <b>Forgot Password</b> is already vulnerable to RCE and Fail-open.

# Vulnerability Report
  The LPB Piso Wifi system contains a critical chained vulnerability in its core backend logic (`execute.js`) that allows unauthenticated attackers to achieve Remote Code Execution (RCE) with root privileges. The primary flaw exists within the password recovery mechanism (exec=forgot), which attempts to validate a license key against a remote endpoint. This validation logic is vulnerable to a "Fail-Open" condition; if the device is unable to contact the remote server `lpb.lpbpisowifi.com` (e.g., due to network isolation or ARP spoofing, or DOS bombing), the internal request returns false. Due to the use of PHP loose comparison (==), an attacker can supply a specific payload (such as '0') that equates to false, bypassing the check and causing the system to disclose the plaintext administrator password. Once in possession of valid credentials, an attacker can exploit a secondary Command Injection vulnerability in the runspeedtest function. The serverid POST parameter is concatenated directly into a shell command executed with sudo privileges without sanitization, allowing the attacker to escape the intended logic and execute arbitrary system commands as the root user.

# The Sequence
- Sending request to `execute.js` with `exec=forgot` and `license='0'`.
- The code attempts to verify the dummy license by sending it to a remote server.
- If you blocked the internet (or the server is down), the `do_post_request` function waits, times out, and finally returns false (boolean) to indicate failure.
  
       * It does not return an error message.                                                                   
       * It does not stop execution.                                                                            
       * It simply sets $codes to false. 
- The code then checks if your input matches the result:                          
```
if ($license == $codes) { ... } 
```
- PHP evaluates `'0' == false`. This statement is TRUE.                                           
       * The system misinterprets the failure to connect `(false)` as matching your input `('0')`.                  
       * Thinking the license is valid, it proceeds to the next line: echo `'Your admin password is: '`

So instead of saying "I can't check the license, so Access Denied" (Fail-Closed), it essentially says "I tried to check, failed, and the result of that failure happens to match what you told me, so Access Granted" (Fail-Open).


# How to do this?
In order to get the admin password, we need to cut the internet from the owner's router into the Piso WiFi machine by ARP spoofing. If you are connected to owner's router, that's great. BUT if you are trying to cut the internet by connecting to Piso WiFi's wlan and using the `10.0.0.1` as gateway, this will not work. Your next move is to perform DoS attack that exhausts the CPU resource of the Piso WiFi by using `hping3`.

> You can proceed to exploiting fail-open vulnerability without doing ARP-spoof or DoS attack if you met these conditions:
> - If the owner experience LOS (or disconnected optical cable) loss of internet due to unpaid bills or unstable connection, or the lpb server is down.
> - If you unplug or cut (criminal way) the ethernet cable from router to SBC, not from USBtoLAN to outdoor AP.

## Suppose that you are Connected to the Owner's Router
This procedure needs to be in a `sudo` mode, so if your Android phone is not rooted, use laptop or pi boards. The python scripts are inside `/TOOLS` directory of this repository. Pretend you are connected to the owner's router, and you have information:
```
Router gateway: 192.168.100.1
Your held device's IP: 192.168.100.15
LPB Piso WiFi's WAN IP: 192.168.100.24
```

> Wait a minute, before you proceed, install `scapy` module from python3 by `pip` or `apt install python3-scapy`.

Execute this:
```
sudo python3 block_internet.py -t 192.168.100.24 -g 192.168.100.1
```
where `-t` is the target IP, which is the LPB, and the `-g` represents the router's IP gateway. Wait until you see the dots `....` indicating packets are being sent.

> Do not close the terminal, or close this process. Open a new terminal and proceed to exploitation.

# Exploitation Time!
For those who successfully cut the internet from the owner's router, that's great. If you are the one who just found out that the owner has no internet, you are indeed lucky. You are in this part where you actually harvest the admin password from backend.

You have to execute this:
```
python3 exploit_lpb.py -t http://192.168.100.24/admin/index?action=execute.js
```
where `-t` is the target url. Here, we used `http://192.168.100.24` from the example above, and `10.0.0.1` is also compatible here.

And then wait for it to spit out the admin password. 

# Disclaimer
This information you have seen today is for awareness only.
