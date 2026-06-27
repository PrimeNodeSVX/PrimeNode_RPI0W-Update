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

echo "Aplikowanie optymalnych ustawien Audio (SQ7UTP)..." >> $LOG_FILE
sudo amixer -c 0 cset numid=1 0
sudo amixer -c 0 cset numid=36 231
sudo amixer -c 0 cset numid=10 231
sudo amixer -c 0 cset numid=13 115
sudo amixer -c 0 cset numid=11 115
sudo amixer -c 0 cset numid=9 0
sudo amixer -c 0 cset numid=8 0
sudo amixer -c 0 cset numid=3 on
sudo amixer -c 0 cset numid=45 on
sudo amixer -c 0 cset numid=48 on
sudo amixer -c 0 cset numid=49 on
sudo amixer -c 0 cset numid=50 on
sudo amixer -c 0 cset numid=51 on
sudo amixer -c 0 cset numid=54 on
sudo amixer -c 0 cset numid=25 off
sudo amixer -c 0 cset numid=26 0
sudo amixer -c 0 cset numid=35 off
sudo amixer -c 0 cset numid=16 0
sudo amixer -c 0 cset numid=15 0

sudo alsactl store 0

date >> $LOG_FILE
echo "Restart systemu za 5 sekund..." >> $LOG_FILE
sleep 5
sudo reboot