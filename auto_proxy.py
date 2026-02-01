#!/usr/bin/env python3
import urllib.request
import re
import random
import subprocess
import sys
import ssl

CONFIG_FILE = "/etc/svxlink/svxlink.conf"
URL = "https://www.echolink.org/proxylist.jsp"

BLACKLIST = [
    "192.68.17.154",
    "127.0.0.1",
    "0.0.0.0"
]

def get_best_proxy():
    print("Pobieranie listy proxy (HTTPS)...")
    try:
        ctx = ssl.create_default_context()
        ctx.check_hostname = False
        ctx.verify_mode = ssl.CERT_NONE

        req = urllib.request.Request(
            URL, 
            data=None, 
            headers={
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            }
        )
        html = urllib.request.urlopen(req, context=ctx, timeout=15).read().decode('utf-8', errors='ignore')
    except Exception as e:
        print(f"BŁĄD pobierania: {e}")
        return None

    candidates = []
    
    parts = html.split("Ready")
    
    for part in parts[:-1]:
        ips = re.findall(r'\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b', part)
        
        if ips:
            ip = ips[-1]
            
            is_blacklisted = ip in BLACKLIST
            is_private = ip.startswith("192.168.") or ip.startswith("10.") or ip.startswith("172.16.")
            
            if not is_blacklisted and not is_private and ip not in candidates:
                candidates.append(ip)

    if not candidates:
        print("Nie znaleziono serwera Ready (błąd parsowania?)")
        return None
    
    print(f"Znaleziono {len(candidates)} serwerów Ready.")
    
    chosen = random.choice(candidates)
    print(f"Wylosowano: {chosen}")
    return chosen

def update_config(new_ip):
    print(f"Aktualizacja pliku {CONFIG_FILE}...")
    try:
        with open(CONFIG_FILE, 'r') as f:
            lines = f.readlines()
        
        new_lines = []
        updated = False
        
        for line in lines:
            if line.strip().upper().startswith("PROXY_SERVER"):
                new_lines.append(f"PROXY_SERVER={new_ip}\n")
                updated = True
            else:
                new_lines.append(line)
        
        with open(CONFIG_FILE, 'w') as f:
            f.writelines(new_lines)
        return updated
    except Exception as e:
        print(f"Błąd zapisu: {e}")
        return False

def restart_svxlink():
    print("Restart SvxLink...")
    subprocess.run(["systemctl", "restart", "svxlink"])

if __name__ == "__main__":
    ip = get_best_proxy()
    if ip:
        if update_config(ip):
            restart_svxlink()
            print(f"SUKCES: Nowe proxy: {ip}")
        else:
            print("BŁĄD aktualizacji pliku.")
    else:
        print("BŁĄD: Pusta lista.")
