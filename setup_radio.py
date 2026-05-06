#!/usr/bin/env python3
import serial
import time
import sys
import os

CTCSS_MAP = {
    "0000": "0000", "0670": "0001", "0719": "0002", "0744": "0003", "0770": "0004",
    "0797": "0005", "0825": "0006", "0854": "0007", "0885": "0008", "0915": "0009",
    "0948": "0010", "0974": "0011", "1000": "0012", "1035": "0013", "1072": "0014",
    "1109": "0015", "1148": "0016", "1188": "0017", "1230": "0018", "1273": "0019",
    "1318": "0020", "1365": "0021", "1413": "0022", "1462": "0023", "1514": "0024",
    "1567": "0025", "1622": "0026", "1679": "0027", "1738": "0028", "1799": "0029",
    "1862": "0030", "1928": "0031", "2035": "0032", "2107": "0033", "2181": "0034",
    "2257": "0035", "2336": "0036", "2418": "0037", "2503": "0038"
}

def send_and_log(ser, cmd, log_f):
    ser.write(cmd.encode())
    time.sleep(1.0)
    response = ser.read_all().decode(errors='ignore').strip()
    log_f.write(f"KOMENDA: {cmd.strip()} | ODPOWIEDŹ: {response}\n")
    return response

def program_radio(rx_freq, tx_freq, ctcss, squelch, bw, vol, prede, hpf, lpf):
    log_path = "/dev/shm/sa818_flash.log"
    sq_val = 8 if int(squelch) > 8 else squelch
    
    with open(log_path, "w") as log:
        log.write(f"=== SA818 DEBUG LOG - {time.ctime()} ===\n")
        try:
            rx_f = "{:.4f}".format(float(rx_freq))
            tx_f = "{:.4f}".format(float(tx_freq))
            radio_code = CTCSS_MAP.get(ctcss, "0000")

            cmd_group = f"AT+DMOSETGROUP={bw},{tx_f},{rx_f},{radio_code},{sq_val},{radio_code}\r\n"
            cmd_vol = f"AT+DMOSETVOLUME={vol}\r\n"
            cmd_filter = f"AT+SETFILTER={prede},{hpf},{lpf}\r\n"

            ser = None
            for p in ["/dev/ttyUSB0", "/dev/ttyUSB1", "/dev/serial0"]:
                if os.path.exists(p):
                    try:
                        ser = serial.Serial(p, 9600, timeout=1)
                        log.write(f"PORT: {p} OK\n")
                        break
                    except: pass
            
            if not ser:
                log.write("BŁĄD: Moduł nieodnaleziony!\n")
                return

            ser.flush()
            send_and_log(ser, "AT+DMOCONNECT\r\n", log)
            send_and_log(ser, cmd_group, log)
            send_and_log(ser, cmd_vol, log)
            send_and_log(ser, cmd_filter, log)
            ser.close()
            log.write("=== KONIEC ===\n")
        except Exception as e:
            log.write(f"BŁĄD: {e}\n")

if __name__ == "__main__":
    if len(sys.argv) >= 10:
        program_radio(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], 
                      sys.argv[5], sys.argv[6], sys.argv[7], sys.argv[8], sys.argv[9])
