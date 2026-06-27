#!/bin/bash -e
LOG_FILE="/var/www/html/ram/rfguru_install.log"
mkdir -p /var/www/html/ram
chmod 777 /var/www/html/ram

echo "=== START INSTALACJI RF GURU (WM8960) ===" > $LOG_FILE
date >> $LOG_FILE

echo "Aktualizacja repozytoriów i pakietów (git, dkms)..." >> $LOG_FILE
apt-get update >> $LOG_FILE 2>&1
apt-get install git dkms -y >> $LOG_FILE 2>&1

cd /tmp
rm -rf WM8960-Audio-HAT
echo "Klonowanie repozytorium sterownika..." >> $LOG_FILE
git clone https://github.com/waveshare/WM8960-Audio-HAT >> $LOG_FILE 2>&1

cd WM8960-Audio-HAT
echo "Rozpoczęto kompilację modułu kernela (DKMS)." >> $LOG_FILE
echo "UWAGA: To zajmie około 10-15 minut na Raspberry Pi..." >> $LOG_FILE
sudo ./install.sh >> $LOG_FILE 2>&1

echo "=== KOMPILACJA ZAKONCZONA SUCESEM ===" >> $LOG_FILE

echo "Tworzenie skryptu jednorazowej inicjalizacji Audio po restarcie..." >> $LOG_FILE

cat << 'EOF' > /usr/local/bin/rfguru_audio_init.sh
#!/bin/bash
sleep 15

amixer -c 0 cset numid=1 0
amixer -c 0 cset numid=36 231
amixer -c 0 cset numid=10 231
amixer -c 0 cset numid=13 115
amixer -c 0 cset numid=11 115
amixer -c 0 cset numid=9 0
amixer -c 0 cset numid=8 0
amixer -c 0 cset numid=3 on
amixer -c 0 cset numid=45 on
amixer -c 0 cset numid=48 on
amixer -c 0 cset numid=49 on
amixer -c 0 cset numid=50 on
amixer -c 0 cset numid=51 on
amixer -c 0 cset numid=54 on
amixer -c 0 cset numid=25 off
amixer -c 0 cset numid=26 0
amixer -c 0 cset numid=35 off
amixer -c 0 cset numid=16 0
amixer -c 0 cset numid=15 0

alsactl --file=/etc/wm8960-soundcard/wm8960_asound.state store 0
alsactl store 0

sed -i '/rfguru_audio_init.sh/d' /etc/rc.local
rm -f /usr/local/bin/rfguru_audio_init.sh
EOF

chmod +x /usr/local/bin/rfguru_audio_init.sh

if ! grep -q "rfguru_audio_init.sh" /etc/rc.local; then
    sed -i '/exit 0/i /usr/local/bin/rfguru_audio_init.sh &' /etc/rc.local
fi

date >> $LOG_FILE
echo "Restart systemu za 5 sekund..." >> $LOG_FILE
sleep 5
sudo reboot