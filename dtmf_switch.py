#!/usr/bin/env python3
import sys
import json
import os
import time

if len(sys.argv) < 2:
    sys.exit(1)

net_id = str(sys.argv[1])
net_file = '/etc/svxlink/networks.json'

try:
    with open(net_file, 'r') as f:
        networks = json.load(f)
    
    selected_net = None
    for net in networks.get('list', []):
        if str(net.get('id')) == net_id:
            selected_net = net
            break
    
    if selected_net:
        switch_data = {
            'Callsign': selected_net.get('callsign', ''),
            'Host': selected_net.get('host', ''),
            'Port': selected_net.get('port', '5300'),
            'Password': selected_net.get('pass', ''),
            'DefaultTG': selected_net.get('deftg', '0'),
            'MonitorTGs': selected_net.get('tgs', ''),
            'node_api_url': selected_net.get('api', '')
        }
        with open('/tmp/svx_new_settings.json', 'w') as f:
            json.dump(switch_data, f)
        networks['active'] = int(net_id)
        with open(net_file, 'w') as f:
            json.dump(networks, f, indent=4)
        os.system(f"sudo /usr/bin/python3 /usr/local/bin/update_svx_full.py --netid {net_id} > /dev/null 2>&1")
        time.sleep(1)
        os.system('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &')

except Exception as e:
    pass
