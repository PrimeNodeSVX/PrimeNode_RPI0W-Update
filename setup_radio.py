#!/usr/bin/env python3
import serial
import time
import sys
import os

CTCSS_MAP = {
    "0000": "0000", 
    "0670": "0001", "0719": "0002", "0744": "0003", "0770": "0004",
    "0797": "0005", "0825": "0006", "0854": "0007", "0885": "0008",
    "0915": "0009", "0948": "0010", "0974": "0011", "1000": "0012",
    "1035": "0013", "1072": "0014", "1109": "0015", "1148": "0016",
    "1188": "0017", "1230": "0018", "1273": "0019", "1318": "0020",
    "1365": "0021", "1413": "0022", "1462": "0023", "1514": "0024",
    "1567": "0025", "1622": "0026", "1679": "0027", "1738": "0028",
    "1799": "0029", "1862": "0030", "1928": "0031", "2035": "0032",
    "2107": "0033", "2181": "0034", "2257": "0035", "2336": "0036",
    "2418": "0037", "2503": "0038"
}

def program_radio(rx_freq, tx_freq, ctcss, squelch):
    try:
        rx_formatted = "{:.4f}".format(float(rx_freq))
        tx_formatted = "{:.4f}".format(float(tx_freq))

        if not ctcss or ctcss == "None": ctcss = "0000"
        
        radio_code = CTCSS_MAP.get(ctcss, "0000")

        cmd = f"AT+DMOSETGROUP=0,{tx_formatted},{rx_formatted},{radio_code},{squelch},{radio_code}\r\n"
        print(f"Wysylanie: {cmd.strip()}")

        ports_to_try = ["/dev/ttyUSB0", "/dev/ttyUSB1", "/dev/serial0", "/dev/ttyS2", "/dev/ttyAMA0"]
        ser = None
        
        for p in ports_to_try:
            if os.path.exists(p):
                try:
                    ser = serial.Serial(p, 9600, timeout=1)
                    print(f"Znaleziono aktywny port: {p}")
                    break
                except Exception:
                    pass
        
        if not ser:
            print("BLAD KRYTYCZNY: Nie znaleziono zadnego poprawnego portu szeregowego!")
            return False

        ser.flushInput()
        ser.flushOutput()
        ser.write(b"AT+DMOCONNECT\r\n")
        time.sleep(0.5)
        ser.write(cmd.encode())
        time.sleep(0.5)
        
        response = ser.read_all().decode(errors='ignore').strip()
        ser.close()

        if "0" in response:
            print(f"SUKCES: Radio zaprogramowane! (CTCSS Index: {radio_code})")
            return True
        else:
            print(f"BLAD: Radio zwrocilo: {response}")
            return False

    except Exception as e:
        print(f"BLAD KRYTYCZNY: {e}")
        return False

if __name__ == "__main__":
    if len(sys.argv) < 5:
        print("Uzycie: setup_radio.py RX TX CTCSS SQ")
        sys.exit(1)
    program_radio(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4])
