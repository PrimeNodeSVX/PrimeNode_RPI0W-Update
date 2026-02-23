#!/bin/bash

GIT_URL="https://github.com/sqlinkgit/SQLink_RPI0W-Update.git"
GIT_DIR="/root/SQLink_RPI0W-Update"
WWW_DIR="/var/www/html"
SVX_CONF="/etc/svxlink/svxlink.conf"
SOUNDS_DIR="/usr/local/share/svxlink/sounds"

echo "--- START UPDATE ---"
date

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

if [ -d "$GIT_DIR/PL" ]; then
    if [ -d "$SOUNDS_DIR/pl_PL" ]; then
        rm -rf "$SOUNDS_DIR/pl_PL"
    fi

    mkdir -p "$SOUNDS_DIR"
    
    if command -v rsync >/dev/null 2>&1; then
        rsync -av --delete "$GIT_DIR/PL/" "$SOUNDS_DIR/PL/"
    else
        if [ ! -d "$SOUNDS_DIR/PL" ]; then
            cp -R "$GIT_DIR/PL" "$SOUNDS_DIR/"
        else
            cp -Ru "$GIT_DIR/PL/"* "$SOUNDS_DIR/PL/"
        fi
    fi

    chmod -R 777 "$SOUNDS_DIR/PL"

    if [ -f "$SVX_CONF" ]; then
        sed -i '/^\[SimplexLogic\]/,/^\[/ s/DEFAULT_LANG=pl_PL/DEFAULT_LANG=PL/' "$SVX_CONF"
        sed -i '/^\[ReflectorLogic\]/,/^\[/ s/DEFAULT_LANG=pl_PL/DEFAULT_LANG=PL/' "$SVX_CONF"
    fi
fi

if [ -d "$GIT_DIR/en_US" ]; then
    mkdir -p "$SOUNDS_DIR/en_US"
    
    if command -v rsync >/dev/null 2>&1; then
        rsync -av --delete "$GIT_DIR/en_US/" "$SOUNDS_DIR/en_US/"
    else
        if [ ! -d "$SOUNDS_DIR/en_US" ]; then
            cp -R "$GIT_DIR/en_US" "$SOUNDS_DIR/"
        else
            cp -Ru "$GIT_DIR/en_US/"* "$SOUNDS_DIR/en_US/"
        fi
    fi
    chmod -R 777 "$SOUNDS_DIR/en_US"
fi

cp $GIT_DIR/*.css $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.js $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.png $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.php $WWW_DIR/
cp $GIT_DIR/*.svg $WWW_DIR/ 2>/dev/null
chmod 644 $WWW_DIR/*.svg 2>/dev/null

if [ ! -f "$WWW_DIR/radio_config.json" ] && [ -f "$GIT_DIR/radio_config.json" ]; then
    cp $GIT_DIR/radio_config.json $WWW_DIR/
fi

if compgen -G "$GIT_DIR/*.py" > /dev/null; then
    cp $GIT_DIR/*.py /usr/local/bin/
    chmod +x /usr/local/bin/*.py
fi

if [ -f "$GIT_DIR/auto_proxy.py" ]; then
    echo "Updating auto_proxy.py..."
    cp "$GIT_DIR/auto_proxy.py" /usr/local/bin/
    chmod +x /usr/local/bin/auto_proxy.py
fi

for script in $GIT_DIR/*.sh; do
    filename=$(basename "$script")
    if [ "$filename" != "update_dashboard.sh" ]; then
        cp "$script" /usr/local/bin/
        chmod +x "/usr/local/bin/$filename"
    fi
done

chown -R www-data:www-data $WWW_DIR
chmod -R 755 $WWW_DIR

if [ -f "$SVX_CONF" ]; then
    if grep -q "^\[EchoLink\]" "$SVX_CONF"; then
        cp "$SVX_CONF" "${SVX_CONF}.bak_update"
        sed -i '/^\[EchoLink\]$/,/^\[/ { /^\[EchoLink\]$/d; /^\[/!d; }' "$SVX_CONF"
    fi
fi

cat <<EOF > /usr/local/bin/proxy_watchdog.sh
#!/bin/bash
if [ -f "/var/www/html/el_error.flag" ]; then
    echo "\$(date): WATCHDOG" >> /var/log/svxlink_watchdog.log
    /usr/bin/python3 /usr/local/bin/auto_proxy.py >> /var/log/svxlink_watchdog.log 2>&1
fi
EOF
chmod +x /usr/local/bin/proxy_watchdog.sh

CRON_CMD="*/5 * * * * /usr/local/bin/proxy_watchdog.sh"
(crontab -l 2>/dev/null | grep -v "proxy_watchdog.sh"; echo "$CRON_CMD") | crontab -

cat <<EOF > /usr/local/bin/clean_logs_on_boot.sh
#!/bin/bash
LOG_FILE="/var/log/svxlink"
EVENT_FILE="/var/www/html/svx_events.log"
ARCHIVE_DIR="/var/log/svxlink_history"
MAX_ARCHIVES=3
systemctl stop svxlink
if [ ! -d "\$ARCHIVE_DIR" ]; then
    mkdir -p "\$ARCHIVE_DIR"
    chown svxlink:daemon "\$ARCHIVE_DIR"
    chmod 755 "\$ARCHIVE_DIR"
fi
if [ -s "\$LOG_FILE" ]; then
    mv "\$LOG_FILE" "\$ARCHIVE_DIR/svxlink_\$(date +"%Y%m%d_%H%M%S").log"
fi
touch "\$LOG_FILE"
chown svxlink:daemon "\$LOG_FILE"
chmod 644 "\$LOG_FILE"
echo "" > "\$EVENT_FILE"
chown www-data:www-data "\$EVENT_FILE"
chmod 644 "\$EVENT_FILE"
ls -1t "\$ARCHIVE_DIR"/svxlink_*.log 2>/dev/null | tail -n +\$((MAX_ARCHIVES + 1)) | xargs -r rm --
ps -ef | grep "tail" | grep "/var/log/svxlink" | grep -v grep | awk '{print \$2}' | xargs -r kill -9
pkill -9 -f "svx_event_logger.sh"
/usr/local/bin/svx_event_logger.sh > /dev/null 2>&1 &
sleep 2
systemctl start svxlink
EOF
chmod +x /usr/local/bin/clean_logs_on_boot.sh

cat <<EOF > /usr/local/bin/svx_event_logger.sh
#!/bin/bash
LOG_SOURCE="/var/log/svxlink"
LOG_DEST="/var/www/html/svx_events.log"
FLAG_ONLINE="/var/www/html/el_online.flag"
FLAG_ERROR="/var/www/html/el_error.flag"

for pid in \$(pgrep -f "svx_event_logger.sh"); do
    if [ "\$pid" != "\$\$" ]; then
        kill -9 "\$pid" 2>/dev/null
    fi
done

ps -ef | grep "tail" | grep "/var/log/svxlink" | grep -v grep | awk '{print \$2}' | xargs -r kill -9

touch \$LOG_DEST
chown www-data:www-data \$LOG_DEST
chmod 644 \$LOG_DEST

tail -F -n 0 "\$LOG_SOURCE" 2>/dev/null | \
grep --line-buffered -E "ReflectorLogic|EchoLink|Tx1|Rx1|Node|Talker|Underrun|Clipping|Distortion|ModuleEchoLink" | \
while read -r line; do
    
    LAST_ENTRY=\$(tail -n 1 "\$LOG_DEST" 2>/dev/null)
    if [ "\$line" == "\$LAST_ENTRY" ]; then
        continue
    fi

    echo "\$line" >> "\$LOG_DEST"

    case "\$line" in
        *"EchoLink directory status changed to ON"*)
            touch "\$FLAG_ONLINE"
            rm -f "\$FLAG_ERROR"
            chown www-data:www-data "\$FLAG_ONLINE"
            ;;
        *"EchoLink directory status changed to"*"OFF"*)
            rm -f "\$FLAG_ONLINE"
            ;;
        *"EchoLink authentication failed"*|*"Connection failed"*|*"Disconnected from EchoLink proxy"*)
            rm -f "\$FLAG_ONLINE"
            touch "\$FLAG_ERROR"
            chown www-data:www-data "\$FLAG_ERROR"
            ;;
    esac
done &
EOF
chmod +x /usr/local/bin/svx_event_logger.sh

cat <<EOF > /etc/rc.local
#!/bin/sh -e
/usr/local/bin/clean_logs_on_boot.sh &
/usr/local/bin/wifi_guard.sh &
exit 0
EOF
chmod +x /etc/rc.local

ps -ef | grep "tail" | grep "/var/log/svxlink" | grep -v grep | awk '{print $2}' | xargs -r kill -9
pkill -9 -f "svx_event_logger.sh"
echo "" > /var/www/html/svx_events.log
sleep 1
/usr/local/bin/svx_event_logger.sh > /dev/null 2>&1 &

if [[ "$SELF_UPDATED" == "1" ]]; then
    echo "STATUS: SUCCESS"
elif [[ "$NEW_HASH" == "CLONED" ]]; then
    echo "STATUS: SUCCESS"
elif [[ "$OLD_HASH" != "$NEW_HASH" ]]; then
    echo "STATUS: SUCCESS"
else
    echo "STATUS: UP_TO_DATE"
fi

exit 0