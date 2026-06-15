#!/usr/bin/env python3
import sys
import os
import json
import time
import shutil

SYSTEM_NAMESPACE_UUID = "5072696d-654e-6f64-6520-535137555450"

def _generate_hw_hash(data):
    import hashlib
    return hashlib.md5((SYSTEM_NAMESPACE_UUID + str(data)).encode()).hexdigest()

CONFIG_FILE = "/etc/svxlink/svxlink.conf"
INPUT_JSON = "/tmp/svx_new_settings.json"
RADIO_JSON = "/var/www/html/radio_config.json"
NODE_INFO_FILE = "/etc/svxlink/node_info.json"
LOG_FILE_RAM = "/dev/shm/svxlink.log"

def format_coords(val, is_lat=True):
    if not val: return None
    try:
        val_str = str(val).strip().replace(',', '.')

        if any(c in val_str.upper() for c in ['N', 'S', 'E', 'W']):
            return val_str
        decimal = float(val_str)
        degrees = int(abs(decimal))
        minutes_float = (abs(decimal) - degrees) * 60
        minutes = int(minutes_float)
        seconds = int(round((minutes_float - minutes) * 60))
        if seconds >= 60:
            seconds -= 60
            minutes += 1
        if minutes >= 60:
            minutes -= 60
            degrees += 1
        if is_lat:
            direction = 'N' if decimal >= 0 else 'S'
            return f"{degrees:02d}.{minutes:02d}.{seconds:02d}{direction}"
        else:
            direction = 'E' if decimal >= 0 else 'W'
            return f"{degrees:03d}.{minutes:02d}.{seconds:02d}{direction}"
    except Exception as e:

        return None

def load_lines(path):
    if not os.path.exists(path): return []
    with open(path, 'r', encoding='utf-8', errors='ignore') as f: return f.readlines()

def save_lines(path, lines):
    with open(path, 'w', encoding='utf-8') as f: f.writelines(lines)

def sanitize_lines(lines):
    seen_headers = set()
    clean_lines = []
    skip_mode = False
    for line in lines:
        stripped = line.strip()
        if stripped.startswith("[") and stripped.endswith("]"):
            if stripped in seen_headers:
                skip_mode = True
            else:
                seen_headers.add(stripped)
                skip_mode = False
                clean_lines.append(line)
        else:
            if not skip_mode:
                clean_lines.append(line)

    final_lines = []
    current_section = ""
    for line in clean_lines:
        stripped = line.strip()
        if stripped.startswith("[") and stripped.endswith("]"):
            current_section = stripped
            final_lines.append(line)
            continue
        if stripped.startswith("HOSTS=") or stripped.startswith("HOST_PORT="):
            continue
        if stripped.startswith("HOST=") or stripped.startswith("PORT="):
            if current_section == "[ReflectorLogic]":
                final_lines.append(line)
        else:
            final_lines.append(line)
    return final_lines

def remove_garbage(lines, section, garbage_keys):
    new_lines = []
    in_section = False
    section_header = f"[{section}]"
    for line in lines:
        stripped = line.strip()
        if stripped.startswith("[") and stripped.endswith("]"):
            in_section = (stripped == section_header)
            new_lines.append(line)
            continue
        if in_section and "=" in stripped:
            key = stripped.split("=")[0].strip()
            if key in garbage_keys:
                continue 
        new_lines.append(line)
    return new_lines

def update_key_in_lines(lines, section, key, value):
    new_lines = []
    in_section = False
    key_found = False
    section_header = f"[{section}]"
    section_exists = False
    for line in lines:
        if line.strip() == section_header:
            section_exists = True
            break
    if not section_exists:
        lines.append(f"\n{section_header}\n")
    for line in lines:
        stripped = line.strip()
        if stripped.startswith("[") and stripped.endswith("]"):
            in_section = (stripped == section_header)
            new_lines.append(line)
            continue
        if in_section:
            if stripped.startswith(key + "="):
                new_lines.append(f"{key}={value}\n")
                key_found = True
            else:
                new_lines.append(line)
        else:
            new_lines.append(line)
    if section_exists and not key_found:
        final_lines = []
        for line in new_lines:
            final_lines.append(line)
            if line.strip() == section_header:
                final_lines.append(f"{key}={value}\n")
        return final_lines
    return new_lines

def main():
    if not os.path.exists(LOG_FILE_RAM):
        with open(LOG_FILE_RAM, 'w') as f: pass
    try:
        os.chmod(LOG_FILE_RAM, 0o666)
    except:
        pass

    data = {}
    if os.path.exists(INPUT_JSON):
        with open(INPUT_JSON, 'r') as f: data = json.load(f)

    lines = load_lines(CONFIG_FILE)
    lines = sanitize_lines(lines)
    lines = update_key_in_lines(lines, "GLOBAL", "LOGFILE", LOG_FILE_RAM)
    lines = update_key_in_lines(lines, "GLOBAL", "CARD_CHANNELS", "1")

    radio_data = {}
    if os.path.exists(RADIO_JSON):
        try:
            with open(RADIO_JSON, 'r') as rf:
                radio_data = json.load(rf)
        except:
            pass

    backup_info = {}
    if os.path.exists(NODE_INFO_FILE):
        try:
            with open(NODE_INFO_FILE, 'r') as nf:
                backup_info = json.load(nf)
        except:
            pass

    def get_val(keys_input, key_radio, key_backup, default=""):
        val = data.get(keys_input)
        if val is not None: return val
        val = radio_data.get(key_radio)
        if val: return val
        return backup_info.get(key_backup, default)

    qth_name = get_val('qth_name', 'qth_name', 'Sysop')
    qth_city = get_val('qth_city', 'qth_city', 'Location')
    qth_loc  = get_val('qth_loc',  'qth_loc',  'Locator')

    gpio_ptt = data.get('GpioPtt') or radio_data.get('gpio_ptt', '19')
    gpio_sql = data.get('GpioSql') or radio_data.get('gpio_sql', '!4')

    if "rx" in data: radio_data["rx"] = data["rx"]
    if "tx" in data: radio_data["tx"] = data["tx"]
    if "ctcss" in data: radio_data["ctcss"] = data["ctcss"]
    if "desc" in data: radio_data["desc"] = data["desc"]
    if "radio_type" in data: radio_data["radio_type"] = data["radio_type"]
    if "svx_deemph" in data: radio_data["svx_deemph"] = data["svx_deemph"]
    if "svx_preemph" in data: radio_data["svx_preemph"] = data["svx_preemph"]
    if "sa_bw" in data: radio_data["sa_bw"] = data["sa_bw"]
    if "sa_vol" in data: radio_data["sa_vol"] = data["sa_vol"]
    if "sa_prede" in data: radio_data["sa_prede"] = data["sa_prede"]
    if "sa_hpf" in data: radio_data["sa_hpf"] = data["sa_hpf"]
    if "sa_lpf" in data: radio_data["sa_lpf"] = data["sa_lpf"]
    rx_freq = radio_data.get("rx", "")
    tx_freq = radio_data.get("tx", "")
    ctcss = radio_data.get("ctcss", "0")

    if ctcss == "0000":
        ctcss = "0"
    elif len(ctcss) == 4 and ctcss.isdigit():
        ctcss = str(float(ctcss) / 10.0)

    modules_str = data.get('Modules')
    modules_clean = modules_str if modules_str is not None else backup_info.get('Modules', 'ModuleHelp,Parrot,ModuleEchoLink')
    is_echolink = "1" if "EchoLink" in modules_clean else "0"
    
    current_default_tg = data.get('DefaultTG') or backup_info.get('DefaultTG', '0')

    node_info_data = {
        "Location": qth_city, "Locator": qth_loc, "Sysop": qth_name,
        "LAT": "0.0", "LONG": "0.0", "TXFREQ": tx_freq, "RXFREQ": rx_freq, "CTCSS": ctcss,
        "DefaultTG": current_default_tg, "Mode": "FM", "Type": "1",
        "Echolink": is_echolink, "Website": "https://github.com/ArduUTP", "LinkedTo": "PrimeNode"
    }
    try:
        with open(NODE_INFO_FILE, 'w') as nf:
            json.dump(node_info_data, nf, indent=4)
        os.chmod(NODE_INFO_FILE, 0o644)
    except:
        pass

    loc_parts = []
    if qth_city: loc_parts.append(qth_city)
    if qth_loc: loc_parts.append(qth_loc)
    if qth_name: loc_parts.append(f"(Op: {qth_name})")
    location_str = ", ".join(loc_parts)

    main_callsign = data.get('Callsign')
    announce_call = data.get('AnnounceCall', '1')
    reflector_callsign = main_callsign
    simplex_callsign = main_callsign if announce_call=="1" else ""

    ident_int = "60"
    if not main_callsign:
        ident_int = "0"
        simplex_callsign = ""

    hidraw_port = "/dev/hidraw0"
    if os.path.exists("/dev/SVX-CM"):
        hidraw_port = "/dev/SVX-CM"
    elif os.path.exists("/sys/class/hidraw"):
        for dev in os.listdir("/sys/class/hidraw"):
            with open(f"/sys/class/hidraw/{dev}/device/uevent") as f:
                content = f.read()
                if "HID_NAME=C-Media" in content or "0D8C:0012" in content or "0D8C:013A" in content:
                    hidraw_port = f"/dev/{dev}"
                    break

    radio_type = radio_data.get("radio_type", "gpio")

    lines = remove_garbage(lines, "Rx1", [
        "SQL_GPIOD_LINE", "SQL_GPIOD_CHIP", "SQL_GPIOD_OPEN_THRESH", "GPIO_SQL_PIN", 
        "HID_SQL_PIN", "CTCSS_MODE", "CTCSS_FQ", 
        "HID_PIN", "HID_DEVICE", "CTCSS_OPEN_THRESH", "CTCSS_CLOSE_THRESH"
    ])
    lines = remove_garbage(lines, "Tx1", [
        "PTT_GPIOD_LINE", "PTT_GPIOD_CHIP", "PTT_PIN", 
        "HID_PTT_PIN", "HID_DEVICE", "PTT_TYPE"
    ])

    svx_deemph = str(data.get("svx_deemph", radio_data.get("svx_deemph", "0")))
    svx_preemph = str(data.get("svx_preemph", radio_data.get("svx_preemph", "0")))

    if radio_type == "shari":
        rx1_map = {
            "SQL_DET": "CTCSS",
            "CTCSS_FQ": ctcss if float(ctcss) > 0 else "100.0",
            "CTCSS_MODE": "0",
            "CTCSS_OPEN_THRESH": "12",
            "CTCSS_CLOSE_THRESH": "5",
            "DTMF_PTY": "/dev/shm/dtmf_ctrl",
            "DEEMPHASIS": svx_deemph
        }
        if ctcss == "0" or ctcss == "0000":
            rx1_map["SQL_DET"] = "HIDRAW"
            rx1_map["HID_DEVICE"] = hidraw_port
            rx1_map["HID_SQL_PIN"] = "!VOL_DN"

        tx1_map = {
            "PTT_TYPE": "Hidraw",
            "HID_DEVICE": hidraw_port,
            "HID_PTT_PIN": "GPIO3",
            "PREEMPHASIS": svx_preemph
        }

        try:
            orig_ctcss = data.get("ctcss") or radio_data.get("ctcss", "0000")
            shari_sql = data.get("shari_sql") or radio_data.get("shari_sql", "4")
            sa_bw = str(data.get("sa_bw", radio_data.get("sa_bw", "1")))
            sa_vol = str(data.get("sa_vol", radio_data.get("sa_vol", "8")))
            sa_prede = str(data.get("sa_prede", radio_data.get("sa_prede", "0")))
            sa_hpf = str(data.get("sa_hpf", radio_data.get("sa_hpf", "0")))
            sa_lpf = str(data.get("sa_lpf", radio_data.get("sa_lpf", "0")))

            cmd = f"sudo /usr/bin/python3 /usr/local/bin/setup_radio.py {rx_freq} {tx_freq} {orig_ctcss} {shari_sql} {sa_bw} {sa_vol} {sa_prede} {sa_hpf} {sa_lpf}"
            os.system(cmd)
        except Exception as e:
            pass

    elif radio_type == "cm108":
        cm108_ptt = data.get('GpioPtt') or radio_data.get('cm108_ptt', 'GPIO3')
        cm108_sql = data.get('GpioSql') or radio_data.get('cm108_sql', '!VOL_DN')
        
        rx1_map = {
            "SQL_DET": "HIDRAW",
            "HID_DEVICE": hidraw_port,
            "HID_SQL_PIN": cm108_sql,
            "DTMF_PTY": "/dev/shm/dtmf_ctrl",
            "DEEMPHASIS": svx_deemph,
            "DTMF_MAX_REV_TWIST": "18",
            "DTMF_MAX_FWD_TWIST": "18"
        }
        tx1_map = {
            "PTT_TYPE": "Hidraw",
            "HID_DEVICE": hidraw_port,
            "HID_PTT_PIN": cm108_ptt,
            "PREEMPHASIS": svx_preemph
        }

    else:
        rx1_map = {
            "SQL_DET": "GPIOD",
            "SQL_GPIOD_CHIP": "gpiochip0",
            "SQL_GPIOD_LINE": gpio_sql,
            "SQL_GPIOD_OPEN_THRESH": "10",
            "DTMF_PTY": "/dev/shm/dtmf_ctrl",
            "DEEMPHASIS": svx_deemph
        }
        tx1_map = {
            "PTT_TYPE": "GPIOD",
            "PTT_GPIOD_CHIP": "gpiochip0",
            "PTT_GPIOD_LINE": gpio_ptt,
            "PREEMPHASIS": svx_preemph
        }

    lat_val = data.get('AprsLat')
    lon_val = data.get('AprsLon')
    lat_fixed = format_coords(lat_val, True) if lat_val else None
    lon_fixed = format_coords(lon_val, False) if lon_val else None
    
    aprs_ssid = data.get('AprsSsid')
    if aprs_ssid and str(aprs_ssid).strip() != "":
        aprs_callsign = f"{main_callsign}-{str(aprs_ssid).strip()}"
    else:
        aprs_callsign = main_callsign

    aprs_icon_raw = data.get('AprsIcon')
    if aprs_icon_raw:
        if aprs_icon_raw.startswith('\\'):
            aprs_icon_raw = '1' + aprs_icon_raw[1:]
    else:
        aprs_icon_raw = None

    user_comment = data.get('AprsComment')
    if user_comment is not None:
        user_comment = user_comment.replace(' - PrimeNode', '').strip()
        full_aprs_comment = f'"{user_comment[:36]}"'
    else:
        full_aprs_comment = None

    height_val = data.get('AprsHeight')
    height_fixed = f"{height_val}m" if height_val else None

    lines = remove_garbage(lines, "LocationInfo", ["SYMBOL_TABLE", "SYMBOL_CODE"])
    aprs_interval_val = data.get('AprsInterval')
    if aprs_interval_val is not None:
        try:
            if int(aprs_interval_val) < 10:
                aprs_interval_val = "10"
        except ValueError:
            aprs_interval_val = "10"

    aprs_enable_raw = data.get('AprsEnable')

    def check_aprs_enabled(config_lines):
        in_g = False
        for l in config_lines:
            s = l.strip()
            if s == "[GLOBAL]": in_g = True
            elif s.startswith("[") and s.endswith("]"): in_g = False
            elif in_g and s.startswith("LOCATION_INFO="):
                if "LocationInfo" in s: return True
        return False

    aprs_was_enabled = check_aprs_enabled(lines)

    if aprs_enable_raw is not None:
        aprs_enable = aprs_enable_raw
        aprs_passcode = data.get('AprsPasscode', '')
        if aprs_enable == '1':
            if not lat_fixed or not lon_fixed or not aprs_passcode.strip():
                aprs_enable = '0'
    else:
        aprs_enable = '1' if aprs_was_enabled else '0'
        aprs_passcode = None

    mapping = {
        "ReflectorLogic": {
            "CALLSIGN": reflector_callsign, "AUTH_KEY": data.get('Password'),
            "HOST": data.get('Host'), "PORT": data.get('Port'),
            "DEFAULT_TG": data.get('DefaultTG'), "MONITOR_TGS": data.get('MonitorTGs'),
            "TG_SELECT_TIMEOUT": data.get('TgTimeout'), "TMP_MONITOR_TIMEOUT": data.get('TmpTimeout'),
            "TGSTBEEP_ENABLE": data.get('Beep3Tone'), "TGREANON_ENABLE": data.get('AnnounceTG'),
            "REFCON_ENABLE": data.get('RefStatusInfo'), 
            "UDP_HEARTBEAT_INTERVAL": "5",
            "KEEPALIVE_INTERVAL": "5",
            "LOCATION": f'"{location_str}"' if location_str else None, "NODE_INFO_FILE": NODE_INFO_FILE,
            "DEFAULT_LANG": data.get('AudioLang')
        },
        "SimplexLogic": {
            "CALLSIGN": simplex_callsign, "RGR_SOUND_ALWAYS": data.get('RogerBeep'),
            "MODULES": modules_clean if data.get('Modules') is not None else None, 
            "SHORT_IDENT_INTERVAL": ident_int,
            "LONG_IDENT_INTERVAL": ident_int, "DEFAULT_LANG": data.get('AudioLang'),
            "DTMF_CTRL_PTY": "/dev/shm/dtmf_ctrl"
        },
        "ModuleEchoLink": {
            "CALLSIGN": data.get('EL_Callsign'), "PASSWORD": data.get('EL_Password'),
            "SYSOPNAME": data.get('EL_Sysop'), "LOCATION": data.get('EL_Location'),
            "DESCRIPTION": data.get('EL_Desc'), "PROXY_SERVER": data.get('EL_ProxyHost'),
            "TIMEOUT": data.get('EL_ModTimeout'), "LINK_IDLE_TIMEOUT": data.get('EL_IdleTimeout')
        },
        "LocationInfo": {
            "APRS_SERVER_LIST": "lodz.aprs2.net:14580" if (aprs_enable == '1' and aprs_enable_raw is not None) else None,
            "BEACON_INTERVAL": aprs_interval_val,
            "PASSCODE": aprs_passcode if (aprs_enable == '1' and aprs_enable_raw is not None) else None,
            "LAT_POSITION": lat_fixed,
            "LON_POSITION": lon_fixed,
            "CALLSIGN": aprs_callsign if (aprs_enable == '1' and aprs_enable_raw is not None) else None,
            "COMMENT": full_aprs_comment,
            "SYMBOL": f'"{aprs_icon_raw}"' if aprs_icon_raw else None,
            "FREQUENCY": tx_freq if aprs_enable == '1' else None,
            "TX_POWER": data.get('AprsPower'),
            "ANTENNA_HEIGHT": height_fixed,
            "ANTENNA_GAIN": data.get('AprsGain'),
            "ANTENNA_DIR": "-1" if (aprs_enable == '1' and aprs_enable_raw is not None) else None,
            "TONE": (ctcss if ctcss != "0" else "") if aprs_enable == '1' else None
        },
        "Rx1": rx1_map,
        "Tx1": tx1_map
    }

    for section, keys in mapping.items():
        for cfg_key, json_val in keys.items():
            if json_val is not None:
                lines = update_key_in_lines(lines, section, cfg_key, str(json_val))

    if aprs_enable == '1':
        lines = update_key_in_lines(lines, "GLOBAL", "LOCATION_INFO", "LocationInfo")
    else:
        lines = remove_garbage(lines, "GLOBAL", ["LOCATION_INFO"])
    if aprs_enable_raw is not None:
        if aprs_enable == '1':
            lines = update_key_in_lines(lines, "GLOBAL", "LOCATION_INFO", "LocationInfo")
        else:
            lines = remove_garbage(lines, "GLOBAL", ["LOCATION_INFO"])

    radio_data['qth_name'] = qth_name
    radio_data['qth_city'] = qth_city
    radio_data['qth_loc'] = qth_loc

    if radio_type == "cm108":
        if data.get('GpioPtt'): radio_data['cm108_ptt'] = data.get('GpioPtt')
        if data.get('GpioSql'): radio_data['cm108_sql'] = data.get('GpioSql')
    elif radio_type == "gpio":
        if gpio_ptt: radio_data['gpio_ptt'] = gpio_ptt
        if gpio_sql: radio_data['gpio_sql'] = gpio_sql

    if lat_val: radio_data['aprs_lat_raw'] = lat_val
    if lon_val: radio_data['aprs_lon_raw'] = lon_val

    shari_sql_val = data.get('shari_sql')
    if shari_sql_val: radio_data['shari_sql'] = shari_sql_val

    node_api_url = data.get('node_api_url')
    if node_api_url is not None:
        radio_data['node_api_url'] = node_api_url

    with open(RADIO_JSON, 'w') as f:
        json.dump(radio_data, f, indent=4)

    REF_SOUNDS_DIR = "/usr/local/share/svxlink/sounds/ref_sounds"
    CORE_DIR = "/usr/local/share/svxlink/sounds/PL/Core"
    TARGET_FILE = os.path.join(CORE_DIR, "online.wav")
    DEFAULT_FILE = os.path.join(CORE_DIR, "online_PN.wav")

    forced_net_id = 0
    if "--netid" in sys.argv:
        try:
            idx = sys.argv.index("--netid")
            forced_net_id = int(sys.argv[idx + 1])
        except:
            pass

    chosen_audio = data.get('audio_file', '')

    NETWORKS_JSON = "/etc/svxlink/networks.json"
    if os.path.exists(NETWORKS_JSON):
        try:
            with open(NETWORKS_JSON, 'r') as nf:
                net_data = json.load(nf)
                active_id = forced_net_id if forced_net_id > 0 else net_data.get('active', 0)
                
                if active_id > 0:
                    for net in net_data.get('list', []):
                        if int(net.get('id')) == int(active_id):
                            if net.get('audio'):
                                chosen_audio = net.get('audio')
                            break
        except Exception as e:
            pass

    try:
        source_path = os.path.join(REF_SOUNDS_DIR, chosen_audio) if chosen_audio else ""
        
        if chosen_audio and os.path.exists(source_path):
            shutil.copy2(source_path, TARGET_FILE)
        else:
            if os.path.exists(DEFAULT_FILE):
                shutil.copy2(DEFAULT_FILE, TARGET_FILE)
        
        if os.path.exists(TARGET_FILE):
            os.chmod(TARGET_FILE, 0o666)
    except Exception as e:
        pass

    save_lines(CONFIG_FILE, lines)
    print("DONE")

if __name__ == "__main__":
    main()
