#!/bin/bash
LOG_FILE="/var/www/html/ram/rfguru_install.log"
mkdir -p /var/www/html/ram

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
date >> $LOG_FILE

echo "Restart systemu za 5 sekund..." >> $LOG_FILE
sleep 5
sudo reboot