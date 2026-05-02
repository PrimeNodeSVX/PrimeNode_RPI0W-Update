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
    systemctl stop shellinabox
fi

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

if compgen -G "$GIT_DIR/*.py" > /dev/null; then
    cp $GIT_DIR/*.py /usr/local/bin/
    chmod +x /usr/local/bin/*.py
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

usermod -aG sudo www-data
chown -R www-data:www-data $WWW_DIR
chmod -R 755 $WWW_DIR
chmod 666 $WWW_DIR/dtmf_custom.json 2>/dev/null
chmod 666 $WWW_DIR/radio_config.json 2>/dev/null
chmod 666 /etc/svxlink/networks.json 2>/dev/null

cat << 'EOF' > /usr/local/bin/svx_event_logger.sh
#!/bin/bash
LOG_DEST="/var/www/html/ram/svx_events.log"
FLAG_ONLINE="/var/www/html/ram/el_online.flag"
FLAG_ERROR="/var/www/html/ram/el_error.flag"

mkdir -p /var/www/html/ram
touch "$LOG_DEST"
chmod 666 "$LOG_DEST"

tail -F -n 50 /dev/shm/svxlink.log 2>/dev/null | while read -r line; do
    echo "$line" >> "$LOG_DEST"
    tail -n 50 "$LOG_DEST" > "$LOG_DEST.tmp" && mv "$LOG_DEST.tmp" "$LOG_DEST"
    chmod 666 "$LOG_DEST"

    case "$line" in
        *"EchoLink directory status changed to ON"*)
            touch "$FLAG_ONLINE" && rm -f "$FLAG_ERROR" ;;
        *"EchoLink directory status changed to"*"OFF"*)
            rm -f "$FLAG_ONLINE" ;;
        *"EchoLink authentication failed"*|*"Connection failed"*)
            rm -f "$FLAG_ONLINE" && touch "$FLAG_ERROR" ;;
    esac
done
EOF
chmod +x /usr/local/bin/svx_event_logger.sh

if ! grep -q "svx_event_logger.sh" /etc/rc.local; then
    sed -i '/exit 0/i mkdir -p /var/www/html/ram && chmod 777 /var/www/html/ram' /etc/rc.local
    sed -i '/exit 0/i /usr/local/bin/svx_event_logger.sh &' /etc/rc.local
fi

NEED_RELOAD=0

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