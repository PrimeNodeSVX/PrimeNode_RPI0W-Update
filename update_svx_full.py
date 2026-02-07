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

    data = {}
    if os.path.exists(INPUT_JSON):
        try:
            with open(INPUT_JSON, 'r') as f: data = json.load(f)
        except: pass

    current_radio = {}
    if os.path.exists(RADIO_JSON):
        try:
            with open(RADIO_JSON, 'r') as rf: current_radio = json.load(rf)
        except: pass

    def get_val(key, default):
        return data.get(key, current_radio.get(key, default))

    radio_type = get_val('radio_type', 'cm108')
    serial_port = get_val('serial_port', '/dev/ttyS2')
    hid_device = get_val('hid_device', '/dev/hidraw0')
    audio_dev = get_val('audio_dev', 'alsa:plughw:0')
    audio_chan = get_val('audio_chan', '0')
    gpio_ptt = get_val('gpio_ptt', '12')
    gpio_sql = get_val('gpio_sql', '16')
    
    rx_freq = get_val('rx', '')
    tx_freq = get_val('tx', '')
    ctcss = get_val('ctcss', '0')
    desc = get_val('desc', '')

    lines = load_lines(CONFIG_FILE)
    lines = sanitize_lines(lines)
    qth_name = data.get('qth_name')
    qth_city = data.get('qth_city')
    qth_loc = data.get('qth_loc')
    
    loc_str = ""
    if qth_city:
        parts = [qth_city]
        if qth_loc: parts.append(qth_loc)
        if qth_name: parts.append(f"(Op: {qth_name})")
        loc_str = f'"{", ".join(parts)}"'

    node_info = {
        "Location": qth_city or "", "Locator": qth_loc or "", "Sysop": qth_name or "",
        "TXFREQ": tx_freq, "RXFREQ": rx_freq, "CTCSS": ctcss,
        "DefaultTG": data.get('DefaultTG', '0'), "Mode": "FM", "Type": "1",
        "Website": "http://primenode.pl"
    }
    try:
        with open(NODE_INFO_FILE, 'w') as nf: json.dump(node_info, nf, indent=4)
        os.chmod(NODE_INFO_FILE, 0o644)
    except: pass

    main_call = data.get('Callsign')
    if main_call:
        ref_call = main_call
        simp_call = main_call if data.get('AnnounceCall') == "1" else ""
    else:
        ref_call = ""
        simp_call = ""

    ref_map = {
        "CALLSIGN": ref_call, "AUTH_KEY": data.get('Password'),
        "HOSTS": data.get('Host'), "HOST_PORT": data.get('Port'),
        "DEFAULT_TG": data.get('DefaultTG'), "MONITOR_TGS": data.get('MonitorTGs'),
        "TG_SELECT_TIMEOUT": data.get('TgTimeout'), "TMP_MONITOR_TIMEOUT": data.get('TmpTimeout'),
        "TGSTBEEP_ENABLE": data.get('Beep3Tone'), "TGREANON_ENABLE": data.get('AnnounceTG'),
        "REFCON_ENABLE": data.get('RefStatusInfo'), "LOCATION": loc_str,
        "DEFAULT_LANG": data.get('DEFAULT_LANG'), "NODE_INFO_FILE": NODE_INFO_FILE
    }
    lines = update_section_map(lines, "ReflectorLogic", ref_map)

    simp_map = {
        "CALLSIGN": simp_call, "RGR_SOUND_ALWAYS": data.get('RogerBeep'),
        "MODULES": data.get('Modules'), "DEFAULT_LANG": data.get('DEFAULT_LANG')
    }
    lines = update_section_map(lines, "SimplexLogic", simp_map)

    el_map = {
        "CALLSIGN": data.get('EL_Callsign'), "PASSWORD": data.get('EL_Password'),
        "SYSOPNAME": data.get('EL_Sysop'), "LOCATION": data.get('EL_Location'),
        "DESCRIPTION": data.get('EL_Desc'), "PROXY_SERVER": data.get('EL_ProxyHost')
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
        rx1_map["CTCSS_FQ"] = "110.9"
        rx1_map["PREAMP"] = "6"
        rx1_map["DEEMPHASIS"] = "0"
        rx1_map["SQL_GPIOD_LINE"] = None
        rx1_map["SQL_GPIOD_CHIP"] = None
    else:

        rx1_map["SQL_DET"] = "GPIOD"
        rx1_map["SQL_GPIOD_CHIP"] = "gpiochip0"
        rx1_map["SQL_GPIOD_LINE"] = gpio_sql
        rx1_map["PREAMP"] = "0"
        rx1_map["DEEMPHASIS"] = "0"
        rx1_map["CTCSS_MODE"] = None

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
    else:

        tx1_map["PTT_TYPE"] = "GPIOD"
        tx1_map["PTT_GPIOD_CHIP"] = "gpiochip0"
        tx1_map["PTT_GPIOD_LINE"] = gpio_ptt
        tx1_map["PREEMPHASIS"] = "1"
        tx1_map["HID_DEVICE"] = None

    lines = update_section_map(lines, "Tx1", tx1_map)

    save_lines(CONFIG_FILE, lines)

    current_radio.update({
        'type': radio_type, 'rx': rx_freq, 'tx': tx_freq, 'ctcss': ctcss, 'desc': desc,
        'audio_dev': audio_dev, 'audio_chan': audio_chan,
        'serial_port': serial_port, 'hid_device': hid_device,
        'gpio_ptt': gpio_ptt, 'gpio_sql': gpio_sql,
        'qth_name': qth_name or "", 'qth_city': qth_city or "", 'qth_loc': qth_loc or ""
    })
    
    try:
        with open(RADIO_JSON, 'w') as f: json.dump(current_radio, f, indent=4)
    except: pass

    if radio_type == 'sa818' and rx_freq and tx_freq:
        cmd = f"/usr/local/bin/sa818.py --port {serial_port} --rx {rx_freq} --tx {tx_freq} --ctcss {ctcss} --squelch 4"
        os.system(f"{cmd} > /tmp/sa818_prog.log 2>&1")

    print("SUKCES")

if __name__ == "__main__":
    main()