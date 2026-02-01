#!/usr/bin/env python3
import sys
import os
import json

CONFIG_FILE = "/etc/svxlink/svxlink.conf"
INPUT_JSON = "/tmp/svx_new_settings.json"
RADIO_JSON = "/var/www/html/radio_config.json"
NODE_INFO_FILE = "/etc/svxlink/node_info.json"

def load_lines(path):
    if not os.path.exists(path): return []
    with open(path, 'r', encoding='utf-8', errors='ignore') as f: return f.readlines()

def save_lines(path, lines):
    with open(path, 'w', encoding='utf-8') as f: f.writelines(lines)

def remove_key_from_section(lines, section, key):
    new_lines = []
    in_section = False
    section_header = f"[{section}]"
    
    for line in lines:
        stripped = line.strip()
        if stripped.startswith("[") and stripped.endswith("]"):
            in_section = (stripped == section_header)

        if in_section and stripped.startswith(f"{key}=") and not stripped.startswith(("#", ";")):
            continue
            
        new_lines.append(line)
    return new_lines

def update_key_in_lines(lines, section, key, value):
    new_lines = []
    in_section = False
    key_updated = False
    
    section_header = f"[{section}]"
    
    section_exists = any(line.strip() == section_header for line in lines)
    if not section_exists:
        lines.append(f"\n{section_header}\n")

    for line in lines:
        stripped = line.strip()
        if stripped.startswith("[") and stripped.endswith("]"):
            in_section = (stripped == section_header)
        
        if in_section and "=" in stripped and not stripped.startswith(("#", ";")):
            parts = stripped.split("=", 1)
            current_key = parts[0].strip()
            
            if current_key == key:
                if not key_updated:
                    new_lines.append(f"{key}={value}\n")
                    key_updated = True
                else:
                    pass 
                continue 

        new_lines.append(line)

    if not key_updated:
        final_lines = []
        in_tgt_sec = False
        added = False
        for l in new_lines:
            s = l.strip()
            if s == section_header:
                in_tgt_sec = True
                final_lines.append(l)
                continue
            if in_tgt_sec and s.startswith("["):
                if not added:
                    final_lines.append(f"{key}={value}\n")
                    added = True
                in_tgt_sec = False
            final_lines.append(l)
        if in_tgt_sec and not added:
             final_lines.append(f"{key}={value}\n")
             added = True
        return final_lines

    return new_lines

def main():
    if not os.path.exists(INPUT_JSON): sys.exit(1)
    with open(INPUT_JSON, 'r') as f: data = json.load(f)

    lines = load_lines(CONFIG_FILE)
    lines = remove_key_from_section(lines, "GLOBAL", "DEFAULT_LANG")

    serial_port = data.get('SerialPort', '/dev/ttyS2')
    gpio_ptt = data.get('GpioPtt', '12')
    gpio_sql = data.get('GpioSql', '16')

    modules_str = data.get('Modules')
    if modules_str is not None:
        raw_list = [m.strip() for m in modules_str.split(',')]
        fixed_list = []
        for m in raw_list:
            if m in ["Help", "Parrot", "EchoLink"]:
                fixed_list.append("Module" + m)
            elif m: 
                fixed_list.append(m)

        el_pass = data.get('EL_Password', '')
        if not el_pass:
            fixed_list = [m for m in fixed_list if 'EchoLink' not in m]
            
        data['Modules'] = ",".join(fixed_list)

    qth_name = data.get('qth_name')
    qth_city = data.get('qth_city')
    qth_loc = data.get('qth_loc')

    rx_freq = ""
    tx_freq = ""
    ctcss = "0"
    
    radio_data = {}
    if os.path.exists(RADIO_JSON):
        try:
            with open(RADIO_JSON, 'r') as rf:
                radio_data = json.load(rf)
                rx_freq = radio_data.get("rx", "")
                tx_freq = radio_data.get("tx", "")
                ctcss = radio_data.get("ctcss", "0")
        except: pass
    
    is_echolink = "0"
    if data.get('Modules') and ("EchoLink" in data['Modules']):
        is_echolink = "1"

    location_conf_val = None

    if qth_city is not None:
        s_name = qth_name if qth_name else ""
        s_city = qth_city if qth_city else ""
        s_loc = qth_loc if qth_loc else ""

        node_info_data = {
            "Location": s_city,
            "Locator": s_loc,
            "Sysop": s_name,
            "LAT": "0.0", "LONG": "0.0",
            "TXFREQ": tx_freq, "RXFREQ": rx_freq, "CTCSS": ctcss,
            "DefaultTG": data.get('DefaultTG', '0'),
            "Mode": "FM", "Type": "1", 
            "Echolink": is_echolink,
            "Website": "http://sqlink.pl",
            "LinkedTo": "SQLink"
        }

        try:
            with open(NODE_INFO_FILE, 'w') as nf: json.dump(node_info_data, nf, indent=4)
            os.chmod(NODE_INFO_FILE, 0o644) 
        except Exception as e: print(f"Error writing node_info.json: {e}")

        loc_parts = []
        if s_city: loc_parts.append(s_city)
        if s_loc: loc_parts.append(s_loc)
        if s_name: loc_parts.append(f"(Op: {s_name})")
        location_conf_val = f'"{", ".join(loc_parts)}"'

    main_callsign = data.get('Callsign')
    announce_call = data.get('AnnounceCall', '1')
    
    reflector_callsign = None
    simplex_callsign = None
    short_ident = None
    long_ident = None

    if main_callsign is not None:
        reflector_callsign = main_callsign
        if announce_call == "1":
            simplex_callsign = main_callsign
            short_ident = "60"
            long_ident = "60"
        else:
            simplex_callsign = ""
            short_ident = "0"
            long_ident = "0"

    mapping = {
        "ReflectorLogic": {
            "CALLSIGN": reflector_callsign,
            "AUTH_KEY": data.get('Password'),
            "HOSTS": data.get('Host'),
            "HOST_PORT": data.get('Port'),
            "DEFAULT_TG": data.get('DefaultTG'),
            "MONITOR_TGS": data.get('MonitorTGs'),
            "TG_SELECT_TIMEOUT": data.get('TgTimeout'),
            "TMP_MONITOR_TIMEOUT": data.get('TmpTimeout'),
            "TGSTBEEP_ENABLE": data.get('Beep3Tone'),
            "TGREANON_ENABLE": data.get('AnnounceTG'),
            "REFCON_ENABLE": data.get('RefStatusInfo'),
            "UDP_HEARTBEAT_INTERVAL": "15",
            "LOCATION": location_conf_val,
            "NODE_INFO_FILE": NODE_INFO_FILE,
            "DEFAULT_LANG": data.get('DEFAULT_LANG')
        },
        "SimplexLogic": {
            "CALLSIGN": simplex_callsign,
            "RGR_SOUND_ALWAYS": data.get('RogerBeep'),
            "MODULES": data.get('Modules'),
            "SHORT_IDENT_INTERVAL": short_ident,
            "LONG_IDENT_INTERVAL": long_ident,
            "DEFAULT_LANG": data.get('DEFAULT_LANG')
        },
        "ModuleEchoLink": {
            "CALLSIGN": data.get('EL_Callsign'),
            "PASSWORD": data.get('EL_Password'),
            "SYSOPNAME": data.get('EL_Sysop'),
            "LOCATION": data.get('EL_Location'),
            "DESCRIPTION": data.get('EL_Desc'),
            "PROXY_SERVER": data.get('EL_ProxyHost'),
            "TIMEOUT": data.get('EL_ModTimeout'),
            "LINK_IDLE_TIMEOUT": data.get('EL_IdleTimeout')
        },
        "Rx1": {
            "DTMF_SERIAL": serial_port,
            "SQL_GPIOD_LINE": gpio_sql
        },
        "Tx1": {
            "PTT_GPIOD_LINE": gpio_ptt
        }
    }

    for section, keys in mapping.items():
        for cfg_key, json_val in keys.items():
            if json_val is not None:
                lines = update_key_in_lines(lines, section, cfg_key, str(json_val))

    save_lines(CONFIG_FILE, lines)

    if 'qth_name' in data: radio_data['qth_name'] = qth_name if qth_name else ""
    if 'qth_city' in data: radio_data['qth_city'] = qth_city if qth_city else ""
    if 'qth_loc' in data: radio_data['qth_loc'] = qth_loc if qth_loc else ""

    radio_data['serial_port'] = serial_port
    radio_data['gpio_ptt'] = gpio_ptt
    radio_data['gpio_sql'] = gpio_sql

    with open(RADIO_JSON, 'w') as f: json.dump(radio_data, f, indent=4)

    print("SUKCES")

if __name__ == "__main__":
    main()