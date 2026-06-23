#!/usr/bin/env python3
import urllib.request
import json
import re
import os
import html

URL = "https://www.echolink.org/logins.jsp"
OUTPUT = "/var/www/html/echolink_db.json"

try:
    req = urllib.request.Request(URL, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
    with urllib.request.urlopen(req, timeout=20) as response:
        html_data = response.read().decode('utf-8', errors='ignore')
        
    nodes = []
    rows = re.findall(r'<tr[^>]*>(.*?)</tr>', html_data, re.IGNORECASE | re.DOTALL)
    
    for row in rows:
        cols = re.findall(r'<td[^>]*>(.*?)</td>', row, re.IGNORECASE | re.DOTALL)
        
        clean_cols = []
        for c in cols:
            text = html.unescape(re.sub(r'<[^>]+>', '', c)).strip()
            clean_cols.append(text)
            
        for i, col in enumerate(clean_cols):
            if col.isdigit() and len(col) >= 4:
                node = col
                items = [x for x in clean_cols[:i] if x]
                
                if items:
                    call = items[0]
                    loc = ""
                    
                    on_idx = -1
                    for j in range(1, len(items)):
                        if items[j] in ['ON', 'OFF', 'BUSY'] or re.search(r'\d{2}:\d{2}', items[j]):
                            on_idx = j
                            break
                    
                    if on_idx > 1:
                        loc = " ".join(items[1:on_idx])
                    elif on_idx == -1 and len(items) > 1:
                        loc = " ".join(items[1:])
                        
                    nodes.append({"call": call, "node": node, "name": "", "loc": loc})
                break
                
    with open(OUTPUT, 'w', encoding='utf-8') as f:
        json.dump(nodes, f)
        
    os.chown(OUTPUT, 33, 33)
    os.chmod(OUTPUT, 0o644)
    print(f"Baza EchoLink pobrana! Zapisano {len(nodes)} węzłów.")
except Exception as e:
    print(f"Błąd pobierania bazy: {e}")