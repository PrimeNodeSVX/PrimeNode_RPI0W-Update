#!/bin/bash -e
LOG_FILE="/var/www/html/ram/core_install.log"
mkdir -p /var/www/html/ram
chmod 777 /var/www/html/ram

echo "=== START AKTUALIZACJI RDZENIA SVXLINK (DO STABILNEJ V26.05.1) ===" > $LOG_FILE
date >> $LOG_FILE

echo "Zatrzymywanie usługi SvxLink..." >> $LOG_FILE
systemctl stop svxlink >> $LOG_FILE 2>&1 || true

echo "Pobieranie stabilnej wersji 26.05.1 z GitHuba..." >> $LOG_FILE
cd /tmp
rm -rf svxlink_build
git clone --branch 26.05.1 --depth 1 https://github.com/sm0svx/svxlink.git svxlink_build >> $LOG_FILE 2>&1

echo "Konfigurowanie środowiska kompilacji (CMake)..." >> $LOG_FILE
mkdir -p svxlink_build/src/build
cd svxlink_build/src/build
cmake -DCMAKE_INSTALL_PREFIX=/usr/local -DSYSCONF_INSTALL_DIR=/etc -DLOCAL_STATE_DIR=/var -DCMAKE_BUILD_TYPE=Release -DUSE_QT=OFF -DWITH_SYSTEMD=ON .. >> $LOG_FILE 2>&1

echo "Trwa kompilacja (to zajmie około 20-30 minut, proszę o cierpliwosć!)..." >> $LOG_FILE
make -j$(nproc) >> $LOG_FILE 2>&1

echo "Instalacja skompilowanych plików..." >> $LOG_FILE
make install >> $LOG_FILE 2>&1
ldconfig >> $LOG_FILE 2>&1

echo "Przywracanie łatek PrimeNode (DTMF 555, 997, ptt.wav) do czystego rdzenia..." >> $LOG_FILE
LOGIC_TCL="/usr/local/share/svxlink/events.d/Logic.tcl"
if [ -f "$LOGIC_TCL" ]; then
    perl -0777 -pi -e 's/\s*if \{\$cmd == "997"\} \{.*?\n\s*return 1\n\s*\}//gs' "$LOGIC_TCL"
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

    if ! grep -q "playTone 880" "$LOGIC_TCL"; then
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
    fi

    if grep -q 'CW::play $sql_rx_id' "$LOGIC_TCL"; then
        sed -i '/^proc send_rgr_sound {} {/,/^}/c\
proc send_rgr_sound {} {\
  variable sql_rx_id\
  set sql_rx_id "?"\
  playFile "/usr/local/share/svxlink/sounds/PL/Core/ptt.wav"\
  playSilence 100\
}' "$LOGIC_TCL"
    fi
fi

echo "Sprzątanie plików tymczasowych..." >> $LOG_FILE
rm -rf /tmp/svxlink_build

echo "=== KOMPILACJA I AKTUALIZACJA ZAKONCZONA SUKCESEM! ===" >> $LOG_FILE
date >> $LOG_FILE
echo "Restart systemu za 5 sekund..." >> $LOG_FILE
sleep 5
reboot