#!/bin/bash

GIT_URL="https://github.com/PrimeNodeSVX/PrimeNode_RPI0W-Update.git"
GIT_DIR="/root/PrimeNode_RPI0W-Update"
WWW_DIR="/var/www/html"
SVX_CONF="/etc/svxlink/svxlink.conf"
SOUNDS_DIR="/usr/local/share/svxlink/sounds"

echo "--- START UPDATE PrimeNode (RPi Edition) ---"
date

if ! command -v shellinaboxd >/dev/null 2>&1; then
    echo ">> Instalacja ShellInABox..."
    apt update && apt install shellinabox -y
fi

systemctl stop shellinabox
systemctl disable shellinabox

OLD_HASH=""
NEW_HASH=""

if [ ! -d "$GIT_DIR" ]; then
    cd /root
    git clone $GIT_URL
    NEW_HASH="CLONED"
else
    cd $GIT_DIR
    git config core.fileMode false
    OLD_HASH=$(git rev-parse HEAD)
    git fetch --all
    git reset --hard origin/main
    NEW_HASH=$(git rev-parse HEAD)
    
    echo "Old Hash: $OLD_HASH"
    echo "New Hash: $NEW_HASH"
    
    if [ $? -ne 0 ]; then 
        echo "STATUS: FAILURE"; 
        exit 1; 
    fi
fi

SCRIPT_PATH="/usr/local/bin/update_dashboard.sh"
REPO_SCRIPT="$GIT_DIR/update_dashboard.sh"

if [ -f "$SCRIPT_PATH" ] && [ -f "$REPO_SCRIPT" ]; then
    if ! cmp -s "$REPO_SCRIPT" "$SCRIPT_PATH"; then
        cp "$REPO_SCRIPT" "$SCRIPT_PATH"
        chmod +x "$SCRIPT_PATH"
        export SELF_UPDATED=1
        exec "$SCRIPT_PATH"
        exit 0
    fi
fi

cp $GIT_DIR/*.css $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.js $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.png $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.jpg $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.php $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.svg $WWW_DIR/ 2>/dev/null

if [ -d "$GIT_DIR/flags" ]; then
    cp -R $GIT_DIR/flags $WWW_DIR/ 2>/dev/null
fi

[ -f "$GIT_DIR/radio_config.json" ] && [ ! -f "$WWW_DIR/radio_config.json" ] && cp "$GIT_DIR/radio_config.json" "$WWW_DIR/"
[ -f "$GIT_DIR/dtmf_custom.json" ] && [ ! -f "$WWW_DIR/dtmf_custom.json" ] && cp "$GIT_DIR/dtmf_custom.json" "$WWW_DIR/"
[ -f "$GIT_DIR/wm8960_asound.state" ] && cp "$GIT_DIR/wm8960_asound.state" "/root/wm8960_asound.state"

if compgen -G "$GIT_DIR/*.py" > /dev/null; then
    echo ">> Instalacja skryptów Python..."
    cp $GIT_DIR/*.py /usr/local/bin/
    chmod +x /usr/local/bin/*.py
fi

if [ -f "$GIT_DIR/dtmf_switch.py" ]; then
    cp "$GIT_DIR/dtmf_switch.py" "/usr/local/bin/dtmf_switch.py"
    chmod +x "/usr/local/bin/dtmf_switch.py"
    echo ">> Zaktualizowano skrypt dtmf_switch.py"
fi

echo ">> Konfiguracja dynamicznych zapowiedzi audio..."
REF_DIR="$SOUNDS_DIR/ref_sounds"
CORE_DIR="$SOUNDS_DIR/PL/Core"
DEFAULT_DIR="$SOUNDS_DIR/PL/Default"

mkdir -p "$REF_DIR"
mkdir -p "$CORE_DIR"
mkdir -p "$DEFAULT_DIR"

if [ -d "$GIT_DIR/ref_sounds" ]; then
    cp -R "$GIT_DIR/ref_sounds/"* "$REF_DIR/" 2>/dev/null
fi

if [ -d "$GIT_DIR/PL/Core" ]; then
    cp -R "$GIT_DIR/PL/Core/"* "$CORE_DIR/" 2>/dev/null
fi

if [ -d "$GIT_DIR/PL/Default" ]; then
    cp -R "$GIT_DIR/PL/Default/"* "$DEFAULT_DIR/" 2>/dev/null
fi

if [ -f "$GIT_DIR/online_PN.wav" ]; then
    cp "$GIT_DIR/online_PN.wav" "$CORE_DIR/online_PN.wav"
elif [ -f "$REF_DIR/online_PN.wav" ]; then
    cp "$REF_DIR/online_PN.wav" "$CORE_DIR/online_PN.wav"
fi

chmod 755 "$REF_DIR"
find "$REF_DIR" -type f -exec chmod 644 {} \; 2>/dev/null
find "$CORE_DIR" -type f -exec chmod 644 {} \; 2>/dev/null
find "$DEFAULT_DIR" -type f -exec chmod 644 {} \; 2>/dev/null

echo ">> Zabezpieczenie ustawień znaku (AnnounceCall)..."
if [ ! -s "/tmp/svx_new_settings.json" ]; then
    CUR_CALL=$(grep -A 10 "^\[ReflectorLogic\]" /etc/svxlink/svxlink.conf | grep -m 1 "^CALLSIGN=" | cut -d'=' -f2)
    SIMP_CALL=$(grep -A 10 "^\[SimplexLogic\]" /etc/svxlink/svxlink.conf | grep -m 1 "^CALLSIGN=" | cut -d'=' -f2)
    
    ANN_CALL="0"
    if [ -n "$SIMP_CALL" ] && [ "$SIMP_CALL" != '""' ] && [ "$SIMP_CALL" != 'None' ]; then 
        ANN_CALL="1"
    fi
    echo "{\"Callsign\":\"$CUR_CALL\", \"AnnounceCall\":\"$ANN_CALL\"}" > /tmp/svx_new_settings.json
fi

echo ">> Synchronizacja konfiguracji radia (Python)..."
python3 /usr/local/bin/update_svx_full.py

for script in $GIT_DIR/*.sh; do
    if [ -f "$script" ]; then
        filename=$(basename "$script")
        if [ "$filename" != "update_dashboard.sh" ]; then
            cp "$script" /usr/local/bin/
            chmod +x "/usr/local/bin/$filename"
        fi
    fi
done

WIFI_FLAG="/etc/.primenode_wifi_v1.flag"

if [ ! -f "$WIFI_FLAG" ]; then
    echo ">> Wdrażanie nowej, stabilnej konfiguracji Rescue_AP..."
    nmcli connection delete Rescue_AP 2>/dev/null
    nmcli connection add type wifi ifname wlan0 con-name Rescue_AP autoconnect no ssid primenode_ap
    nmcli connection modify Rescue_AP 802-11-wireless.mode ap
    nmcli connection modify Rescue_AP 802-11-wireless.band bg
    nmcli connection modify Rescue_AP 802-11-wireless.channel 6
    nmcli connection modify Rescue_AP ipv4.method shared
    nmcli connection modify Rescue_AP ipv4.addresses 192.168.4.1/24
    nmcli connection modify Rescue_AP ipv6.method disabled
    nmcli connection modify Rescue_AP wifi-sec.key-mgmt wpa-psk
    nmcli connection modify Rescue_AP wifi-sec.psk "primenode123"
    nmcli connection modify Rescue_AP 802-11-wireless-security.psk-flags 0
    nmcli connection modify Rescue_AP 802-11-wireless-security.wps-method 1
    nmcli connection modify Rescue_AP 802-11-wireless-security.pmf 1
    systemctl restart NetworkManager
    touch "$WIFI_FLAG"
    echo ">> Konfiguracja Rescue_AP zakończona sukcesem."
else
    echo ">> Konfiguracja Rescue_AP jest już aktualna (pomijam)."
fi

usermod -aG sudo www-data
chown -R www-data:www-data $WWW_DIR
chmod -R 755 $WWW_DIR
mkdir -p $WWW_DIR/ram
chmod -R 777 $WWW_DIR/ram
chmod 666 $WWW_DIR/dtmf_custom.json 2>/dev/null
chmod 666 $WWW_DIR/radio_config.json 2>/dev/null
chmod 666 /etc/svxlink/networks.json 2>/dev/null

if ! grep -q "/bin/cp, /usr/bin/cp" /etc/sudoers; then
    echo ">> Dodawanie uprawnień sudo dla www-data do pliku sudoers..."
    echo "www-data ALL=(ALL) NOPASSWD: /bin/rm, /usr/bin/rm" >> /etc/sudoers
    echo "www-data ALL=(ALL) NOPASSWD: /bin/cp, /usr/bin/cp" >> /etc/sudoers
    echo "www-data ALL=(ALL) NOPASSWD: /bin/chown, /usr/bin/chown" >> /etc/sudoers
    echo "www-data ALL=(ALL) NOPASSWD: /bin/chmod, /usr/bin/chmod" >> /etc/sudoers
    echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/python3, /usr/bin/amixer, /usr/sbin/alsactl, /usr/bin/systemctl, /usr/sbin/reboot, /usr/sbin/shutdown" >> /etc/sudoers
    
    echo ">> Restartowanie usługi apache2..."
    systemctl restart apache2
else
    echo ">> Uprawnienia sudo dla www-data są już aktualne (pomijam)."
fi

cat << 'EOF' > /usr/local/bin/svx_event_logger.sh
#!/bin/bash
LOG_DEST="/var/www/html/ram/svx_events.log"
FLAG_ONLINE="/var/www/html/ram/el_online.flag"
FLAG_ERROR="/var/www/html/ram/el_error.flag"

mkdir -p /var/www/html/ram
touch "$LOG_DEST"
chmod 666 "$LOG_DEST"
rm -f "$FLAG_ONLINE" "$FLAG_ERROR"

tail -F -n 50 /dev/shm/svxlink.log 2>/dev/null | while read -r line; do
    echo "$line" >> "$LOG_DEST"
    tail -n 50 "$LOG_DEST" > "$LOG_DEST.tmp" && mv "$LOG_DEST.tmp" "$LOG_DEST"
    chmod 666 "$LOG_DEST"

    case "$line" in
        *"EchoLink directory status changed to ON"*)
            touch "$FLAG_ONLINE" && rm -f "$FLAG_ERROR" ;;
        *"EchoLink directory status changed to"*"OFF"*)
            rm -f "$FLAG_ONLINE" ;;
        *"EchoLink authentication failed"*|*"Connection failed"*|*"Could not connect"*|*"Access denied"*|*"Login failed"*|*"Cannot resolve"*|*"Disconnected from EchoLink proxy"*)
            rm -f "$FLAG_ONLINE" && touch "$FLAG_ERROR" ;;
        *"SvxLink v"*|*"Starting SvxLink"*)
            rm -f "$FLAG_ONLINE" "$FLAG_ERROR" ;;
    esac
done
EOF
chmod +x /usr/local/bin/svx_event_logger.sh

if ! grep -q "svx_event_logger.sh" /etc/rc.local; then
    sed -i '/exit 0/i mkdir -p /var/www/html/ram && chmod 777 /var/www/html/ram' /etc/rc.local
    sed -i '/exit 0/i /usr/local/bin/svx_event_logger.sh &' /etc/rc.local
fi

if [ ! -f "/etc/cron.d/echolink_update" ]; then
    cat << 'EOF' > /etc/cron.d/echolink_update
0 * * * * root /usr/bin/python3 /usr/local/bin/fetch_echolink.py >/dev/null 2>&1
EOF
    chmod 644 /etc/cron.d/echolink_update
    systemctl restart cron
fi

if [ -x "/usr/local/bin/fetch_echolink.py" ]; then
    /usr/bin/python3 /usr/local/bin/fetch_echolink.py >/dev/null 2>&1
fi

NEED_RELOAD=0

echo ">> Aplikowanie optymalizacji sieci i audio dla PrimeNode..."

echo ">> Sprawdzanie i aktualizacja pliku Logic.tcl (DTMF 997)..."
LOGIC_TCL="/usr/local/share/svxlink/events.d/Logic.tcl"

if [ -f "$LOGIC_TCL" ]; then
    if ! grep -q "NASZ NOWY KOD WYŁĄCZAJĄCY (997)" "$LOGIC_TCL"; then
        echo ">> Wdrażanie komendy DTMF 997 (Bezpieczne wyłączanie)..."
        cat << 'EOF' > /tmp/dtmf_997.tcl
  if {$cmd == "997"} {
      puts ">>> Zamykanie systemu (kod 997) <<<"
      catch {
          if {${::active_module} != ""} {
              ${::active_module}::deactivate
          }
      }
      catch {playFile "/usr/local/share/svxlink/sounds/PL/Core/poweroff.wav"}
      playSilence 500
      catch {exec sudo bash -c "sleep 5 && shutdown -h now" &}
      
      return 1
  }
EOF

        sed -i '/proc dtmf_cmd_received {cmd} {/r /tmp/dtmf_997.tcl' "$LOGIC_TCL"
        rm -f /tmp/dtmf_997.tcl
        NEED_RELOAD=1
    else
        echo ">> Komenda DTMF 997 jest już wdrożona (pomijam)."
    fi
fi

echo ">> Aktualizacja dzwięków dla komendy 555 (Roaming)..."
if [ -f "$LOGIC_TCL" ]; then
    if ! grep -q "playTone 880" "$LOGIC_TCL"; then
        echo ">> Wdrażanie sekwencji tonowej i zapowiedzi dla 555..."
        
        sed -i '/exec sudo \/usr\/bin\/python3 \/usr\/local\/bin\/dtmf_switch.py/i \
      catch {playTone 880 100 100}\
      playSilence 50\
      catch {playTone 1000 100 100}\
      playSilence 50\
      catch {playTone 1200 100 100}\
      playSilence 10\
      catch {playMsg "Core" "connecting_to"}\
      catch {playMsg "Core" "online"}' "$LOGIC_TCL"

        sed -i 's/exec sudo \/usr\/bin\/python3 \/usr\/local\/bin\/dtmf_switch.py $net_id &/catch {exec sudo \/usr\/bin\/python3 \/usr\/local\/bin\/dtmf_switch.py $net_id \&}/g' "$LOGIC_TCL"
        
        NEED_RELOAD=1
    else
        echo ">> Dzwieki dla DTMF 555 juz istnieja (pomijam)."
    fi
fi

echo ">> Konfiguracja dzwieku Roger Beep (ptt.wav)..."
if [ -f "$LOGIC_TCL" ]; then
    if ! grep -q 'ptt.wav' "$LOGIC_TCL"; then
        echo ">> Calkowita podmiana bloku funkcji send_rgr_sound na twardo..."
        sed -i '/^proc send_rgr_sound {} {/,/^}/c\
proc send_rgr_sound {} {\
  variable sql_rx_id\
  set sql_rx_id "?"\
  playFile "/usr/local/share/svxlink/sounds/PL/Core/ptt.wav"\
  playSilence 100\
}' "$LOGIC_TCL"

        NEED_RELOAD=1
    else
        echo ">> Dzwieki Roger Beep (ptt.wav) sa juz wdrozone (pomijam)."
    fi
fi

sh -c 'cat << EOF > /etc/modprobe.d/alsa-blacklist.conf
blacklist snd_bcm2835
EOF'

pkill -f .nat_keepalive.sh 2>/dev/null
rm -f /usr/local/bin/.nat_keepalive.sh

sed -i '/nat_keepalive.sh/d' /etc/rc.local 2>/dev/null
crontab -l 2>/dev/null | grep -v 'nat_keepalive.sh' | crontab - 2>/dev/null

sh -c 'cat << EOF > /etc/sysctl.d/99-primenode-net.conf
net.core.rmem_max = 2097152
net.core.wmem_max = 2097152
net.core.rmem_default = 2097152
net.core.wmem_default = 2097152
EOF'
sysctl -p /etc/sysctl.d/99-primenode-net.conf 2>/dev/null

for svc_file in /etc/systemd/system/svxlink.service /etc/systemd/system/svxlink.service.d/override.conf; do
    if [ -f "$svc_file" ]; then
        if grep -q "\-\-logfile=" "$svc_file" 2>/dev/null; then
            echo ">> Naprawiam plik systemd: $svc_file"
            sed -i -E 's|--logfile=[^ ]+|--logfile=/dev/shm/svxlink.log|g' "$svc_file"
            NEED_RELOAD=1
        fi
    fi
done

if [ "$NEED_RELOAD" = "1" ]; then
    echo ">> Zastosowano łatki ścieżki logów. Restart usług..."
    systemctl daemon-reload
    rm -f /dev/shm/svxlink.log 
    systemctl restart svxlink
    sleep 2
fi

pkill -9 -f "svx_event_logger.sh"
mkdir -p /var/www/html/ram
chmod 777 /var/www/html/ram
nohup /usr/local/bin/svx_event_logger.sh > /dev/null 2>&1 &

if [[ "$SELF_UPDATED" == "1" || "$NEW_HASH" == "CLONED" || "$OLD_HASH" != "$NEW_HASH" ]]; then
    echo "STATUS: SUCCESS"
else
    echo "STATUS: UP_TO_DATE"
fi
exit 0