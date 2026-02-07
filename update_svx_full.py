#!/usr/bin/env python3
import sys
import os
import json
import configparser

CONFIG_FILE = "/etc/svxlink/svxlink.conf"
INPUT_JSON = "/tmp/svx_new_settings.json"
RADIO_JSON = "/var/www/html/radio_config.json"
NODE_INFO_FILE = "/etc/svxlink/node_info.json"

def read_current_config(path):
    config = configparser.ConfigParser(interpolation=None, strict=False)
    config.optionxform = str
    if os.path.exists(path):
        try:
            config.read(path)
        except:
            pass
    return config

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
    return clean_lines

def update_section_map(lines, section, key_val_map):
    new_lines = []
    in_section = False
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
            if "=" in stripped and not stripped.startswith(("#", ";")):
                current_key = stripped.split("=", 1)[0].strip()
                if current_key in key_val_map:
                    continue 
            new_lines.append(line)
        else:
            new_lines.append(line)

    final_lines = []
    in_tgt = False
    inserted = False
    
    for line in new_lines:
        final_lines.append(line)
        s = line.strip()
        if s == section_header:
            in_tgt = True
            for k, v in key_val_map.items():
                if v is not None:
                    final_lines.append(f"{k}={v}\n")
            inserted = True
    
    return final_lines

def main():

    new_data = {}
    if os.path.exists(INPUT_JSON):
        try:
            with open(INPUT_JSON, 'r') as f: new_data = json.load(f)
        except: pass

    current_conf = read_current_config(CONFIG_FILE)

    radio_cfg = {}
    if os.path.exists(RADIO_JSON):
        try:
            with open(RADIO_JSON, 'r') as rf: radio_cfg = json.load(rf)
        except: pass

    def smart_get(key, section, conf_key, default):

        if key in new_data:
            return new_data[key]

        if section and conf_key and current_conf.has_section(section):
            val = current_conf.get(section, conf_key, fallback=None)
            if val is not None: return val
            
        if key in radio_cfg:
            return radio_cfg[key]
            
        return default

    def get_radio_val(key, default):
        return new_data.get(key, radio_cfg.get(key, default))

    radio_type = get_radio_val('radio_type', 'cm108')
    serial_port = get_radio_val('serial_port', '/dev/ttyS2')
    hid_device = get_radio_val('hid_device', '/dev/hidraw0')
    default_audio = 'alsa:plughw:1' if radio_type == 'sa818' else 'alsa:plughw:0'
    audio_dev = get_radio_val('audio_dev', default_audio)
    audio_chan = get_radio_val('audio_chan', '0')
    gpio_ptt = get_radio_val('gpio_ptt', '12')
    gpio_sql = get_radio_val('gpio_sql', '16')
    rx_freq = get_radio_val('rx', '')
    tx_freq = get_radio_val('tx', '')
    ctcss = get_radio_val('ctcss', '0')
    desc = get_radio_val('desc', '')
    my_call = smart_get('Callsign', 'ReflectorLogic', 'CALLSIGN', '')
    my_pass = smart_get('Password', 'ReflectorLogic', 'AUTH_KEY', '')
    my_host = smart_get('Host', 'ReflectorLogic', 'HOSTS', 'sm3.svxlink.org')
    my_port = smart_get('Port', 'ReflectorLogic', 'HOST_PORT', '5300')
    def_tg  = smart_get('DefaultTG', 'ReflectorLogic', 'DEFAULT_TG', '0')
    mon_tgs = smart_get('MonitorTGs', 'ReflectorLogic', 'MONITOR_TGS', '')
    ann_tg = smart_get('AnnounceTG', 'ReflectorLogic', 'TGREANON_ENABLE', '1')
    beep   = smart_get('Beep3Tone', 'ReflectorLogic', 'TGSTBEEP_ENABLE', '1')
    roger  = smart_get('RogerBeep', 'SimplexLogic', 'RGR_SOUND_ALWAYS', '0')
    lang   = smart_get('DEFAULT_LANG', 'ReflectorLogic', 'DEFAULT_LANG', 'pl_PL')
    el_call = smart_get('EL_Callsign', 'ModuleEchoLink', 'CALLSIGN', '')
    el_pass = smart_get('EL_Password', 'ModuleEchoLink', 'PASSWORD', '')
    el_sys  = smart_get('EL_Sysop', 'ModuleEchoLink', 'SYSOPNAME', '')
    el_loc  = smart_get('EL_Location', 'ModuleEchoLink', 'LOCATION', '')
    el_desc = smart_get('EL_Desc', 'ModuleEchoLink', 'DESCRIPTION', '')
    el_prox = smart_get('EL_ProxyHost', 'ModuleEchoLink', 'PROXY_SERVER', '')
    qth_city = smart_get('qth_city', None, None, radio_cfg.get('qth_city', ''))
    qth_loc  = smart_get('qth_loc', None, None, radio_cfg.get('qth_loc', ''))
    qth_name = smart_get('qth_name', None, None, radio_cfg.get('qth_name', ''))
    loc_parts = []
    if qth_city: loc_parts.append(qth_city)
    if qth_loc: loc_parts.append(qth_loc)
    if qth_name: loc_parts.append(f"(Op: {qth_name})")
    location_str = f'"{", ".join(loc_parts)}"'

    node_info = {
        "Location": qth_city, "Locator": qth_loc, "Sysop": qth_name,
        "TXFREQ": tx_freq, "RXFREQ": rx_freq, "CTCSS": ctcss,
        "DefaultTG": def_tg, "Mode": "FM", "Type": "1",
        "Website": "http://primenode.pl"
    }
    try:
        with open(NODE_INFO_FILE, 'w') as nf: json.dump(node_info, nf, indent=4)
        os.chmod(NODE_INFO_FILE, 0o644)
    except: pass

    lines = load_lines(CONFIG_FILE)
    lines = sanitize_lines(lines)

    ref_map = {
        "CALLSIGN": my_call, "AUTH_KEY": my_pass,
        "HOSTS": my_host, "HOST_PORT": my_port,
        "DEFAULT_TG": def_tg, "MONITOR_TGS": mon_tgs,
        "TG_SELECT_TIMEOUT": "30", "TMP_MONITOR_TIMEOUT": "3600",
        "TGSTBEEP_ENABLE": beep, "TGREANON_ENABLE": ann_tg,
        "REFCON_ENABLE": "1", "LOCATION": location_str,
        "DEFAULT_LANG": lang, "NODE_INFO_FILE": NODE_INFO_FILE
    }
    lines = update_section_map(lines, "ReflectorLogic", ref_map)
    simp_call = my_call
    ann_call = smart_get('AnnounceCall', None, None, '1')
    if ann_call == '0':
        simp_call = ""

    simp_map = {
        "CALLSIGN": simp_call, "RGR_SOUND_ALWAYS": roger,
        "DEFAULT_LANG": lang
    }
    lines = update_section_map(lines, "SimplexLogic", simp_map)

    el_map = {
        "CALLSIGN": el_call, "PASSWORD": el_pass,
        "SYSOPNAME": el_sys, "LOCATION": el_loc,
        "DESCRIPTION": el_desc, "PROXY_SERVER": el_prox
    }
    lines = update_section_map(lines, "ModuleEchoLink", el_map)

    rx1_map = {
        "TYPE": "Local",
        "AUDIO_DEV": audio_dev,
        "AUDIO_CHANNEL": audio_chan,
        "SQL_START_DELAY": "0", "SQL_DELAY": "0", "SQL_HANGTIME": "20",
        "LIMITER_THRESH": "-6", "PEAK_METER": "1",
        "DTMF_DEC_TYPE": "INTERNAL", "DTMF_MUTING": "1", "DTMF_HANGTIME": "40",
        "DTMF_SERIAL": serial_port
    }

    if radio_type == 'sa818':

        rx1_map["SQL_DET"] = "CTCSS"
        rx1_map["CTCSS_MODE"] = "2"
        ctcss_float = "0"
        if ctcss and ctcss != "0000":
            try:
                ctcss_float = str(float(ctcss) / 10.0)
            except: pass
            
        rx1_map["CTCSS_FQ"] = ctcss_float
        rx1_map["PREAMP"] = "6"
        rx1_map["DEEMPHASIS"] = "0"
        rx1_map["SQL_GPIOD_LINE"] = None
        rx1_map["SQL_GPIOD_CHIP"] = None
        rx1_map["SQL_GPIOD_ACTIVE_LOW"] = None
    else:

        rx1_map["SQL_DET"] = "GPIOD"
        rx1_map["SQL_GPIOD_CHIP"] = "gpiochip0"
        rx1_map["SQL_GPIOD_LINE"] = gpio_sql
        rx1_map["PREAMP"] = "0"
        rx1_map["DEEMPHASIS"] = "0"
        rx1_map["CTCSS_MODE"] = None
        rx1_map["CTCSS_FQ"] = None

    lines = update_section_map(lines, "Rx1", rx1_map)

    tx1_map = {
        "TYPE": "Local",
        "AUDIO_DEV": audio_dev,
        "AUDIO_CHANNEL": audio_chan,
        "PTT_HANGTIME": "100", "TIMEOUT": "300", "TX_DELAY": "500",
        "DTMF_TONE_LENGTH": "100", "DTMF_TONE_SPACING": "50", "DTMF_DIGIT_PWR": "-15"
    }

    if radio_type == 'sa818':

        tx1_map["PTT_TYPE"] = "Hidraw"
        tx1_map["HID_DEVICE"] = hid_device
        tx1_map["HID_PTT_PIN"] = "GPIO3"
        tx1_map["PREEMPHASIS"] = "0"
        tx1_map["PTT_GPIOD_LINE"] = None
        tx1_map["PTT_GPIOD_CHIP"] = None
        tx1_map["PTT_GPIOD_ACTIVE_LOW"] = None
    else:

        tx1_map["PTT_TYPE"] = "GPIOD"
        tx1_map["PTT_GPIOD_CHIP"] = "gpiochip0"
        tx1_map["PTT_GPIOD_LINE"] = gpio_ptt
        tx1_map["PREEMPHASIS"] = "1"
        tx1_map["HID_DEVICE"] = None
        tx1_map["HID_PTT_PIN"] = None

    lines = update_section_map(lines, "Tx1", tx1_map)

    save_lines(CONFIG_FILE, lines)

    radio_cfg.update({
        'type': radio_type,
        'rx': rx_freq,
        'tx': tx_freq,
        'ctcss': ctcss,
        'desc': desc,
        'audio_dev': audio_dev,
        'audio_chan': audio_chan,
        'serial_port': serial_port,
        'hid_device': hid_device,
        'gpio_ptt': gpio_ptt,
        'gpio_sql': gpio_sql,
        'qth_name': qth_name,
        'qth_city': qth_city,
        'qth_loc': qth_loc
    })
    
    try:
        with open(RADIO_JSON, 'w') as f: json.dump(radio_cfg, f, indent=4)
    except: pass
    
    if radio_type == 'sa818' and rx_freq and tx_freq:
        os.system("chmod +x /usr/local/bin/sa818.py")
        cmd = f"/usr/local/bin/sa818.py --port {serial_port} --rx {rx_freq} --tx {tx_freq} --ctcss {ctcss} --squelch 4"
        os.system(f"{cmd} > /tmp/sa818_prog.log 2>&1 &")

    print("SUKCES")

if __name__ == "__main__":
    main()