<?php
    session_start();

    if (isset($_POST['ssh_action'])) {
        if ($_POST['ssh_action'] == 'start') {
            shell_exec("sudo systemctl start shellinabox");
            sleep(1);
        } elseif ($_POST['ssh_action'] == 'stop') {
            shell_exec("sudo systemctl stop shellinabox");
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_GET['lang'])) {
        $_SESSION['lang'] = $_GET['lang'];
    }
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'pl';

    $TR = [
        'pl' => [
            'sys_start' => 'START SYSTEMU...',
            'el_init' => 'EchoLink Inicjalizacja...',
            'audio_saved' => '✅ Audio ZAPISANE.',
            'saved_restart' => 'Zapisano! Restart...',
            'radio_gpio_saved' => 'Konfiguracja Radio i GPIO Zapisana! Restart...',
            'restart_svc' => 'Restart Usługi...',
            'rebooting' => '🔄 Reboot...',
            'shutting_down' => '🛑 Shutdown...',
            'proxy_started' => '♻️ Uruchomiono Proxy Hunter (Szukam najlepszego serwera... Czekaj).',
            'proxy_missing' => 'Brak pliku proxy_hunter.py w /usr/local/bin!',
            'update_success' => '✅ AKTUALIZACJA ZAKOŃCZONA SUKCESEM!',
            'restarting_soon' => 'System zostanie zrestartowany za',
            'restarting_now' => 'Trwa ponowne uruchamianie...',
            'wait_refresh' => 'Poczekaj chwilę i odśwież stronę.',
            'up_to_date' => '⚠️ SYSTEM JEST JUŻ AKTUALNY',
            'no_changes' => 'Brak nowych zmian do pobrania. Powrót za chwilę...',
            'update_error' => '❌ BŁĄD AKTUALIZACJI!',
            'btn_back' => 'Wróć',
            'wifi_deleted' => 'Usunięto sieć.',
            'ip_missing' => 'Brak IP',
            'cpu_temp' => 'CPU Temp',
            'ram_used' => 'RAM Used',
            'disk_used' => 'Disk Used',
            'network' => 'Network',
            'hardware' => 'Hardware',
            'logics' => 'Logiki',
            'modules' => 'Moduły',
            'tg_default' => 'TG Default',
            'tg_active' => 'TG Active',
            'reflector' => 'Reflector',
            'uptime' => 'Uptime',
            'tab_dashboard' => 'Dashboard',
            'tab_nodes' => 'Nodes',
            'tab_dtmf' => 'DTMF',
            'tab_radio' => 'Radio',
            'tab_audio' => 'Audio',
            'tab_config' => 'Konfiguracja',
            'tab_wifi' => 'WiFi',
            'tab_power' => 'Zasilanie',
            'tab_logs' => 'Logi',
            'tab_help' => 'Pomoc',
            'footer_system' => 'System PrimeNode',
            'source_code' => 'Kod źródłowy'
        ],
        'en' => [
            'sys_start' => 'SYSTEM START...',
            'el_init' => 'EchoLink Initializing...',
            'audio_saved' => '✅ Audio SAVED.',
            'saved_restart' => 'Saved! Restarting...',
            'radio_gpio_saved' => 'Radio & GPIO Config Saved! Restarting...',
            'restart_svc' => 'Restarting Service...',
            'rebooting' => '🔄 Rebooting...',
            'shutting_down' => '🛑 Shutting down...',
            'proxy_started' => '♻️ Proxy Hunter started (Searching for best server... Wait).',
            'proxy_missing' => 'Missing proxy_hunter.py in /usr/local/bin!',
            'update_success' => '✅ UPDATE SUCCESSFUL!',
            'restarting_soon' => 'System will reboot in',
            'restarting_now' => 'Rebooting system...',
            'wait_refresh' => 'Please wait a moment and refresh the page.',
            'up_to_date' => '⚠️ SYSTEM IS UP TO DATE',
            'no_changes' => 'No new changes to download. Returning...',
            'update_error' => '❌ UPDATE ERROR!',
            'btn_back' => 'Back',
            'wifi_deleted' => 'Network deleted.',
            'ip_missing' => 'No IP',
            'cpu_temp' => 'CPU Temp',
            'ram_used' => 'RAM Used',
            'disk_used' => 'Disk Used',
            'network' => 'Network',
            'hardware' => 'Hardware',
            'logics' => 'Logics',
            'modules' => 'Modules',
            'tg_default' => 'TG Default',
            'tg_active' => 'TG Active',
            'reflector' => 'Reflector',
            'uptime' => 'Uptime',
            'tab_dashboard' => 'Dashboard',
            'tab_nodes' => 'Nodes',
            'tab_dtmf' => 'DTMF',
            'tab_radio' => 'Radio',
            'tab_audio' => 'Audio',
            'tab_config' => 'Config',
            'tab_wifi' => 'WiFi',
            'tab_power' => 'Power',
            'tab_logs' => 'Logs',
            'tab_help' => 'Help',
            'footer_system' => 'PrimeNode System',
            'source_code' => 'Source Code'
        ]
    ];

    $custom_dtmf_file = '/var/www/html/dtmf_custom.json';

    if (isset($_POST['add_dtmf_name']) && isset($_POST['add_dtmf_code'])) {
        $name = trim($_POST['add_dtmf_name']);
        $tg = preg_replace('/[^0-9]/', '', $_POST['add_dtmf_code']);
        
        if (!empty($name) && !empty($tg)) {
            $current_data = [];
            if (file_exists($custom_dtmf_file)) {
                $json_content = file_get_contents($custom_dtmf_file);
                $current_data = json_decode($json_content, true) ?? [];
            }
            
            $current_data[] = ['name' => $name, 'tg' => $tg];
            
            file_put_contents($custom_dtmf_file, json_encode($current_data));
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['del_dtmf_index'])) {
        $idx = (int)$_POST['del_dtmf_index'];
        if (file_exists($custom_dtmf_file)) {
            $current_data = json_decode(file_get_contents($custom_dtmf_file), true) ?? [];
            if (isset($current_data[$idx])) {
                array_splice($current_data, $idx, 1);
                file_put_contents($custom_dtmf_file, json_encode($current_data));
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['reorder_dtmf_tab']) && isset($_POST['new_order_json'])) {
        $tab_idx = (int)$_POST['reorder_dtmf_tab'];
        $new_order = json_decode($_POST['new_order_json'], true);
        
        if (file_exists($custom_dtmf_file) && is_array($new_order)) {
            $current_data = json_decode(file_get_contents($custom_dtmf_file), true) ?? [];
            
            if (isset($current_data[$tab_idx])) {
                $current_data[$tab_idx]['buttons'] = $new_order;
                file_put_contents($custom_dtmf_file, json_encode($current_data));
                echo "SUCCESS";
            }
        }
        exit;
    }

    if (isset($_GET['ajax_stats'])) {
        header('Content-Type: application/json');
        $stats = [];
        $model = @file_get_contents('/sys/firmware/devicetree/base/model');
        $stats['hw'] = $model ? str_replace("\0", "", trim($model)) : "Raspberry Pi";
        $temp_raw = @file_get_contents('/sys/class/thermal/thermal_zone0/temp');
        $stats['temp'] = $temp_raw ? round($temp_raw / 1000, 1) : 0;
        $free = shell_exec('free -m');
        $free_arr = explode("\n", (string)trim($free));
        $mem = preg_split("/\s+/", $free_arr[1]);
        $stats['ram_percent'] = round(($mem[2] / $mem[1]) * 100, 1);
        $dt = disk_total_space('/');
        $df = disk_free_space('/');
        $stats['disk_percent'] = round((($dt - $df) / $dt) * 100, 1);
        $ip = trim(shell_exec("hostname -I | awk '{print $1}'"));
        $stats['ip'] = empty($ip) ? $TR[$lang]['ip_missing'] : $ip;
        $ssid = trim(shell_exec("iwgetid -r"));
        if (!empty($ssid)) {
            $stats['net_type'] = "WiFi";
            $stats['ssid'] = $ssid;
        } elseif (!empty($ip) && $ip != "127.0.0.1") {
            $stats['net_type'] = "LAN";
            $stats['ssid'] = "";
        } else {
            $stats['net_type'] = "Offline";
            $stats['ssid'] = "";
        }
        $ini_chk = parse_svx_conf('/etc/svxlink/svxlink.conf');
        $mods = $ini_chk['SimplexLogic']['MODULES'] ?? '';
        $stats['el_enabled'] = (strpos($mods, 'ModuleEchoLink') !== false || strpos($mods, 'EchoLink') !== false);
        $stats['el_error'] = file_exists('/var/www/html/ram/el_error.flag');
        $stats['el_online'] = file_exists('/var/www/html/ram/el_online.flag');
        
        $el_qso_lines = shell_exec('grep "EchoLink QSO state changed to" /dev/shm/svxlink.log 2>/dev/null');
        $active_el_nodes = [];
        if ($el_qso_lines) {
            $lines = explode("\n", trim($el_qso_lines));
            foreach ($lines as $line) {
                if (preg_match('/:\s+([A-Z0-9\-a-z_]+):\s+EchoLink QSO state changed to (CONNECTED|DISCONNECTED)/i', $line, $m)) {
                    $node = strtoupper($m[1]);
                    if (strtoupper($m[2]) === 'CONNECTED') {
                        $active_el_nodes[$node] = true;
                    } else {
                        unset($active_el_nodes[$node]);
                    }
                }
            }
        }
        $stats['el_nodes'] = array_keys($active_el_nodes);

        $net_file = '/etc/svxlink/networks.json';
        if (file_exists($net_file)) {
            $networks_data = json_decode(@file_get_contents($net_file), true);
            $stats['net_active'] = $networks_data['active'] ?? 0;
        } else {
            $stats['net_active'] = 0;
        }
        echo json_encode($stats);
        exit;
    }

    if (isset($_POST['ajax_dtmf'])) {
        $code = $_POST['ajax_dtmf'];
        if (preg_match('/^[0-9A-D*#]+$/', $code)) {
            
            $chunks = [];
            $current_chunk = $code[0];
            
            for ($i = 1; $i < strlen($code); $i++) {
                if ($code[$i] === $code[$i - 1]) {
                    $chunks[] = $current_chunk;
                    $current_chunk = $code[$i];
                } else {
                    $current_chunk .= $code[$i];
                }
            }
            $chunks[] = $current_chunk;

            $script_content = "#!/bin/bash\n";
            foreach ($chunks as $index => $chunk) {
                $script_content .= "sudo /usr/local/bin/send_dtmf.sh " . escapeshellarg($chunk) . "\n";
                if ($index < count($chunks) - 1) {
                    $script_content .= "sleep 0.3\n"; 
                }
            }
            
            $script_content .= "rm -f \"\$0\"\n";
            
            $tmp_file = '/tmp/dtmf_run_' . uniqid() . '.sh';
            file_put_contents($tmp_file, $script_content);
            shell_exec("nohup bash " . escapeshellarg($tmp_file) . " > /dev/null 2>&1 &");
            
            echo "OK: $code";
        } else { echo "ERROR"; }
        exit;
    }

    $radio_cfg_file = '/var/www/html/radio_config.json';
    $r_cfg = [];
    if (file_exists($radio_cfg_file)) {
        $r_cfg = json_decode(file_get_contents($radio_cfg_file), true);
    }

    $is_wm8960 = (isset($r_cfg['radio_type']) && $r_cfg['radio_type'] === 'rfguru');

    $cards = shell_exec("cat /proc/asound/cards");
    
    if ($is_wm8960 && preg_match('/(\d+)\s\[.*wm8960.*/i', $cards, $matches)) {
        $CARD_ID = (int)$matches[1];
    } elseif (!$is_wm8960 && preg_match('/(\d+)\s\[(Device|Set|USB)/i', $cards, $matches)) {
        $CARD_ID = (int)$matches[1];
    } else {
        $CARD_ID = 0;
    }

    if ($is_wm8960) {
        $MIXER_IDS = [
            'Mic_Cap_Sw' => 3,     
            'Mic_Boost_Vol' => 9
        ];
        $SCONTROLS = [
            'Mic_Cap_Vol' => 'Capture',
            'ADC_Vol' => 'ADC PCM',
            'Spk_Play_Vol' => 'Playback',
            'HP_Vol' => 'Headphone'
        ];
        $max_rx = 100;
        $max_tx = 100;
    } else {
        $MIXER_IDS = ['Mic_Cap_Sw' => 7, 'Mic_Cap_Vol' => 8, 'Auto_Gain_Ctrl' => 9, 'Spk_Play_Sw' => 5, 'Spk_Play_Vol' => 6];
        $SCONTROLS = [];
        $max_rx = 35;
        $max_tx = 37;
    }

    $audio = []; $audio_msg = '';
    
    function get_alsa_value($card, $numid) {
        $cmd = "sudo /usr/bin/amixer -c $card cget numid=$numid 2>&1";
        $output = shell_exec($cmd);
        if (preg_match('/: values=(\d+)/', $output, $matches)) return (int)$matches[1];
        if (preg_match('/: values=(on|off)/', $output, $matches)) return $matches[1] === 'on' ? 1 : 0;
        return 0;
    }

    function get_alsa_mapped_pct($card, $scontrol) {
        $cmd = "sudo /usr/bin/amixer -c $card -M sget '$scontrol' 2>&1";
        $output = shell_exec($cmd);
        if (preg_match('/\[(\d+)%\]/', $output, $matches)) return (int)$matches[1];
        return 0;
    }

    if (isset($_POST['save_audio'])) {
        $save_sliders_raw = [];
        if (!$is_wm8960) {
            $save_sliders_raw = ['mic_cap_vol' => 'Mic_Cap_Vol', 'spk_play_vol' => 'Spk_Play_Vol'];
        }
        
        foreach ($save_sliders_raw as $p => $m) {
            if (isset($_POST[$p]) && isset($MIXER_IDS[$m])) {
                $numid = $MIXER_IDS[$m]; 
                $val = (int)$_POST[$p];
                if ($numid > 0) shell_exec("sudo /usr/bin/amixer -c $CARD_ID cset numid=$numid $val");
            }
        }

        if ($is_wm8960) {
            $save_scontrols = [
                'mic_cap_vol' => 'Mic_Cap_Vol', 
                'adc_vol' => 'ADC_Vol', 
                'spk_play_vol' => 'Spk_Play_Vol', 
                'hp_vol' => 'HP_Vol'
            ];

            if (isset($_POST['mic_boost_vol']) && isset($MIXER_IDS['Mic_Boost_Vol'])) {
                $numid = $MIXER_IDS['Mic_Boost_Vol'];
                $val = (int)$_POST['mic_boost_vol'];
                shell_exec("sudo /usr/bin/amixer -c $CARD_ID cset numid=$numid $val");
            }

            foreach ($save_scontrols as $p => $m) {
                if (isset($_POST[$p]) && isset($SCONTROLS[$m])) {
                    $sctrl = $SCONTROLS[$m];
                    $val = (int)$_POST[$p];
                    if ($val >= 0 && $val <= 100) {
                        shell_exec("sudo /usr/bin/amixer -c $CARD_ID -M sset '$sctrl' {$val}%");
                    }
                }
            }
            
            if (isset($_POST['hp_vol'])) {
                $val = (int)$_POST['hp_vol'];
                shell_exec("sudo /usr/bin/amixer -c $CARD_ID -M sset 'Speaker' {$val}%");
            }
        }
        
        $switches = $is_wm8960 ? ['Mic_Cap_Sw'] : ['Mic_Cap_Sw', 'Auto_Gain_Ctrl', 'Spk_Play_Sw'];
        foreach ($switches as $m) {
            if (isset($MIXER_IDS[$m])) {
                $numid = $MIXER_IDS[$m]; 
                $state = isset($_POST[$m]) && $_POST[$m] == '1' ? 'on' : 'off';
                if ($numid > 0) shell_exec("sudo /usr/bin/amixer -c $CARD_ID cset numid=$numid $state");
            }
        }
        
        if ($is_wm8960) {
            shell_exec("sudo /usr/sbin/alsactl --file=/etc/wm8960-soundcard/wm8960_asound.state store $CARD_ID");
        }
        shell_exec("sudo /usr/sbin/alsactl store $CARD_ID");
        $audio_msg = '<div class="alert alert-success">'.$TR[$lang]['audio_saved'].'</div>';
    }
    
    if (isset($_POST['fix_audio_btn'])) {
       $audio_msg = ""; 
    }

    foreach ($MIXER_IDS as $k => $id) {
        $audio[$k] = ($id > 0) ? get_alsa_value($CARD_ID, $id) : 0;
    }
    if ($is_wm8960) {
        foreach ($SCONTROLS as $k => $sctrl) {
            $audio[$k] = get_alsa_mapped_pct($CARD_ID, $sctrl);
        }
    }

    function parse_svx_conf($file) {
        $ini = []; $curr = "GLOBAL";
        if (!file_exists($file)) return [];
        foreach (file($file) as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] == '#' || $line[0] == ';') continue;
            if ($line[0] == '[' && substr($line, -1) == ']') { $curr = substr($line, 1, -1); $ini[$curr] = []; }
            else { $parts = explode('=', $line, 2); if (count($parts) == 2) $ini[$curr][trim($parts[0])] = trim(trim($parts[1]), '"\''); }
        }
        return $ini;
    }
    $ini = parse_svx_conf('/etc/svxlink/svxlink.conf');
    $ref = $ini['ReflectorLogic'] ?? []; $simp = $ini['SimplexLogic'] ?? []; $glob = $ini['GLOBAL'] ?? []; $el = $ini['ModuleEchoLink'] ?? [];
    
    $rx1 = $ini['Rx1'] ?? [];
    $tx1 = $ini['Tx1'] ?? [];

    $currentSimplexCall = $simp['CALLSIGN'] ?? '';
    $voiceIDStatus = ($currentSimplexCall == '') ? '0' : '1';
    $simplex_call_val = $simp['CALLSIGN'] ?? '';
    $announce_status = (!empty($simplex_call_val) && $simplex_call_val !== '""') ? '1' : '0';

    $vals = [
        'Callsign' => $ref['CALLSIGN'] ?? 'N0CALL', 'Host' => $ref['HOST'] ?? $ref['HOSTS'] ?? '', 'Port' => $ref['PORT'] ?? $ref['HOST_PORT'] ?? '', 'Password' => $ref['AUTH_KEY'] ?? '',
        'DefaultTG' => $ref['DEFAULT_TG'] ?? '0', 'MonitorTGs' => $ref['MONITOR_TGS'] ?? '', 'TgTimeout' => $ref['TG_SELECT_TIMEOUT'] ?? '60',
        'TmpTimeout' => $ref['TMP_MONITOR_TIMEOUT'] ?? '3600', 'Modules' => $simp['MODULES'] ?? 'Help,Parrot,EchoLink',
        'Beep3Tone' => $ref['TGSTBEEP_ENABLE'] ?? '0', 'AnnounceTG' => $ref['TGREANON_ENABLE'] ?? '0', 'RefStatusInfo' => $ref['REFCON_ENABLE'] ?? '0',
        'RogerBeep' => $simp['RGR_SOUND_ALWAYS'] ?? '0',
        'VoiceID'   => $voiceIDStatus,
        'AnnounceCall' => $announce_status,
    ];
    $vals_el = [
        'Callsign' => $el['CALLSIGN'] ?? $vals['Callsign'], 'Password' => $el['PASSWORD'] ?? '', 'Sysop' => $el['SYSOPNAME'] ?? '',
        'Location' => $el['LOCATION'] ?? '', 'Desc' => $el['DESCRIPTION'] ?? '', 'Proxy' => $el['PROXY_SERVER'] ?? '',
        'ModTimeout' => $el['TIMEOUT'] ?? '60', 'IdleTimeout' => $el['LINK_IDLE_TIMEOUT'] ?? '300',
    ];

    $jsonFile = '/var/www/html/radio_config.json';
    $radio = [
        "rx" => "432.8000", "tx" => "432.8000", "ctcss" => "0000", "desc" => "Brak opisu",
        "gpio_ptt" => $tx1['PTT_GPIOD_LINE'] ?? '12',
        "gpio_sql" => $rx1['SQL_GPIOD_LINE'] ?? '16'
    ];
    
    if (file_exists($jsonFile)) { 
        $loaded = json_decode(file_get_contents($jsonFile), true); 
        if ($loaded) $radio = array_merge($radio, $loaded); 
    }

    if (isset($_POST['save_svx_full'])) {
        $newData = $_POST;
        unset($newData['save_svx_full'], $newData['active_tab']);
        file_put_contents('/tmp/svx_new_settings.json', json_encode($newData));
        shell_exec('sudo /usr/bin/python3 /usr/local/bin/update_svx_full.py 2>&1');
        shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &');
        echo "<div class='alert alert-success'>".$TR[$lang]['saved_restart']."</div><meta http-equiv='refresh' content='3'>";
    }

    if (isset($_POST['save_radio'])) {
        $r_type = $_POST['radio_type'] ?? 'gpio';
        $is_installing = false;
        $abort_save = false;
        
        if ($r_type === 'rfguru') {
            $check_audio = shell_exec("cat /proc/asound/cards 2>&1");
            if (strpos($check_audio, 'wm8960') === false) {
                if (!isset($_POST['confirm_rfguru_install'])) {
                    $abort_save = true;
                    echo '
                    <div id="rfguru-safe-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); backdrop-filter: blur(5px); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                        <div style="background: #222; border: 2px solid #FF9800; border-radius: 8px; padding: 25px; max-width: 500px; text-align: center; box-shadow: 0 0 20px rgba(255, 152, 0, 0.4); margin: 20px;">
                            <h2 style="color: #FF9800; margin-top: 0; font-size: 22px;">⚠️ Wymagana Instalacja</h2>
                            <p style="color: #eee; font-size: 15px; line-height: 1.5;">
                                Wybrałeś profil sprzętowy <b>RF Guru</b>, ale system nie wykrył zainstalowanych sterowników cyfrowego audio (I2S WM8960).
                            </p>
                            <p style="color: #ccc; font-size: 13px; line-height: 1.5; background: #111; padding: 15px; border-left: 3px solid #F44336; text-align: left; border-radius: 4px;">
                                <b style="color: #F44336; font-size: 14px;">UWAGA - PRZECZYTAJ UWAŻNIE:</b><br><br>
                                Kompilacja sterownika potrwa <b>od 10 do 15 minut</b>. W tym czasie urządzenie będzie mocno obciążone, a na koniec <b>samo uruchomi się ponownie</b>.<br><br>
                                Odłączenie zasilania lub wyłączenie urządzenia w trakcie tej instalacji może <b>uszkodzić system operacyjny na karcie SD</b>!
                            </p>
                            <form method="post" style="margin-top: 25px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">';
                            
                            foreach($_POST as $k => $v) {
                                echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($v).'">';
                            }
                            
                    echo '      <input type="hidden" name="confirm_rfguru_install" value="1">
                                <button type="button" style="background: #F44336; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; max-width: 200px;" onclick="document.getElementById(\'rfguru-safe-overlay\').style.display=\'none\'; window.location.href=window.location.pathname;">❌ Anuluj (Pomyłka)</button>
                                <button type="submit" style="background: #4CAF50; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; max-width: 200px;">✅ Rozumiem, Instaluj</button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $is_installing = true;
                    @mkdir('/var/www/html/ram', 0777, true);
                    @file_put_contents('/var/www/html/ram/rfguru_install.log', "Zainicjowano z poziomu WWW. Przekazywanie żądania do procesów Root...\n");
                    touch('/tmp/rfguru_install.flag');
                    echo '
                    <div id="rfguru-install-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); backdrop-filter: blur(8px); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                        <div style="background: #222; border: 2px solid #2196F3; border-radius: 8px; padding: 25px; max-width: 700px; width: 90%; text-align: center; box-shadow: 0 0 30px rgba(33, 150, 243, 0.4); margin: 20px;">
                            <h2 style="color: #2196F3; margin-top: 0; font-size: 24px;">⚙️ Trwa Instalacja Sterowników...</h2>
                            <p id="install-desc" style="color: #eee; font-size: 15px; margin-bottom: 20px;">
                                Proces kompilacji został uruchomiony. Malina zrestartuje się automatycznie po zakończeniu.<br>
                                <b style="color: #F44336;">NIE ODŁĄCZAJ ZASILANIA!</b>
                            </p>
                            
                            <div id="log-container" style="background: #000; border: 1px solid #444; border-radius: 4px; padding: 15px; text-align: left;">
                                <div style="color: #888; font-size: 11px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">Podgląd terminala na żywo:</div>
                                <div id="log-view" style="color: #0f0; font-family: monospace; font-size: 13px; height: 250px; overflow-y: auto; white-space: pre-wrap; line-height: 1.4;">Inicjalizacja środowiska instalatora...</div>
                            </div>
                            
                            <div id="install-spinner" style="margin-top: 20px; color: #888; font-size: 13px;">
                                <span style="display:inline-block; animation: pulse 1.5s infinite;">⏳ System w trakcie pracy...</span>
                            </div>
                        </div>
                    </div>
                    <style>@keyframes pulse { 0% {opacity: 0.5;} 50% {opacity: 1;} 100% {opacity: 0.5;} }</style>
                    <script>
                        window.isRestarting = false;
                        let checkInterval = setInterval(() => {
                            fetch("get_install_log.php?t=" + new Date().getTime())
                            .then(r => {
                                if(!r.ok) throw new Error("Offline");
                                return r.text();
                            })
                            .then(t => {
                                const log = document.getElementById("log-view");
                                if(window.isRestarting && t.includes("Oczekiwanie")) {
                                    clearInterval(checkInterval);
                                    document.getElementById("rfguru-install-overlay").innerHTML = `
                                        <div style="background: #222; border: 2px solid #4CAF50; border-radius: 8px; padding: 25px; max-width: 500px; text-align: center; box-shadow: 0 0 30px rgba(76, 175, 80, 0.4); margin: 20px;">
                                            <h2 style="color: #4CAF50; margin-top: 0; font-size: 24px;">✅ Instalacja Zakończona!</h2>
                                            <p style="color: #eee; font-size: 15px; margin-bottom: 20px;">System uruchomił się ponownie z nowym jądrem. Moduł RF Guru jest gotowy do pracy!</p>
                                            <button onclick="window.location.href=\'/\'" style="background: #4CAF50; color: white; border: none; padding: 12px 20px; cursor: pointer; font-weight: bold; border-radius:4px; width:100%; font-size: 16px;">Przejdź do systemu</button>
                                        </div>
                                    `;
                                    return;
                                }

                                if(t.trim().length > 0 && !window.isRestarting) {
                                    log.innerText = t;
                                    log.scrollTop = log.scrollHeight;
                                    if(t.includes("KOMPILACJA ZAKONCZONA") || t.includes("Restart systemu")) {
                                        window.isRestarting = true;
                                        document.getElementById("install-spinner").innerHTML = "<span style=\'color:#FF9800; font-weight:bold; animation: pulse 1s infinite;\'>🔄 Trwa restartowanie systemu... Czekaj na przywrócenie połączenia.</span>";
                                    }
                                }
                            })
                            .catch(e => {
                                window.isRestarting = true;
                                const log = document.getElementById("log-view");
                                if(log && !log.innerText.includes("Utracono połączenie")) {
                                    log.innerText += "\\n\\n[Utracono połączenie z serwerem! Trwa restart Maliny... Czekaj na automatyczne przywrócenie komunikacji.]";
                                    log.scrollTop = log.scrollHeight;
                                }
                                document.getElementById("install-spinner").innerHTML = "<span style=\'color:#FF9800; font-weight:bold; animation: pulse 1s infinite;\'>🔄 Uruchamianie ponowne systemu... Trwa to zazwyczaj minutę.</span>";
                            });
                        }, 1500);
                    </script>';
                }
            } else {
                echo "<div class='alert alert-success'>✅ RF Guru: Sterownik WM8960 jest aktywny i działa poprawnie!</div>";
            }
        }

        if (!$abort_save) {
            $updateData = [
                "radio_type" => $r_type,
                "rx" => $_POST['rx_freq'],
                "tx" => $_POST['tx_freq'],
                "ctcss" => $_POST['ctcss_val'],
                "desc" => $_POST['radio_desc'],
                "GpioPtt" => $_POST['gpio_ptt'] ?? '12',
                "GpioSql" => $_POST['gpio_sql'] ?? '16',
                "shari_sql" => $_POST['shari_sql'] ?? '4',
                "svx_deemph"  => $_POST['svx_deemph'] ?? '0',
                "svx_preemph" => $_POST['svx_preemph'] ?? '0',
                "sa_bw"       => $_POST['sa_bw'] ?? '1',
                "sa_vol"      => $_POST['sa_vol'] ?? '8',
                "sa_prede"    => $_POST['sa_prede'] ?? '0',
                "sa_hpf"      => $_POST['sa_hpf'] ?? '0',
                "sa_lpf"      => $_POST['sa_lpf'] ?? '0',
                "Callsign"    => $vals['Callsign'] ?? '',
                "AnnounceCall"=> $vals['AnnounceCall'] ?? '0'
            ];
            
            file_put_contents('/tmp/svx_new_settings.json', json_encode($updateData));
            shell_exec('sudo /usr/bin/python3 /usr/local/bin/update_svx_full.py 2>&1');

            if (!$is_installing) {
                shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &');
                echo "<div class='alert alert-success'>".$TR[$lang]['radio_gpio_saved']."</div><meta http-equiv='refresh' content='3'>";
            }
        }
    }

    if (isset($_POST['restart_srv'])) { shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &'); echo "<div class='alert alert-success'>".$TR[$lang]['restart_svc']."</div>"; }
    if (isset($_POST['reboot_device'])) { shell_exec('sudo /usr/sbin/reboot > /dev/null 2>&1 &'); echo "<div class='alert alert-warning'>".$TR[$lang]['rebooting']."</div>"; }
    if (isset($_POST['shutdown_device'])) { shell_exec('sudo /usr/sbin/shutdown -h now > /dev/null 2>&1 &'); echo "<div class='alert alert-error'>".$TR[$lang]['shutting_down']."</div>"; }
    if (isset($_POST['stop_srv'])) { 
        shell_exec('sudo /usr/bin/systemctl stop svxlink > /dev/null 2>&1 &'); 
        echo "<div class='alert alert-warning'>Zatrzymano usługę SvxLink!</div>"; 
    }

    if (isset($_POST['auto_proxy'])) { 
        if (file_exists('/usr/local/bin/auto_proxy.py')) {
     shell_exec('sudo /usr/bin/python3 /usr/local/bin/auto_proxy.py > /dev/null 2>&1 &');
             echo "<div class='alert alert-warning'>".$TR[$lang]['proxy_started']."</div><meta http-equiv='refresh' content='8'>";
        } else {
             echo "<div class='alert alert-error'>".$TR[$lang]['proxy_missing']."</div>";
        }
    }
    
    if (isset($_POST['git_update'])) {
        set_time_limit(300); ignore_user_abort(true);
        $out = shell_exec("sudo /usr/local/bin/update_dashboard.sh 2>&1");
        
        if (strpos($out, 'STATUS: SUCCESS') !== false) {
            shell_exec('(sleep 5; sudo /usr/sbin/reboot) > /dev/null 2>&1 &');
            echo "
            <div class='alert alert-success' style='text-align:left; margin: 20px;'>
                <strong>".$TR[$lang]['update_success']."</strong><br>
                ".$TR[$lang]['restarting_soon']." <span id='cnt'>5</span> s...
                <pre style='font-size:10px; margin-top:5px; background:#111; color:#ccc; padding:5px; border-radius:3px; max-height:300px; overflow:auto;'>$out</pre>
            </div>
            <script>
                if(document.getElementById('loading-overlay')) {
                    document.getElementById('loading-overlay').style.display = 'none';
                }
                var sec = 5;
                setInterval(function(){
                    sec--;
                    var el = document.getElementById('cnt');
                    if(el) el.innerText = sec;
                    if(sec <= 0) {
                         document.body.innerHTML = '<h2 style=\"color:white; text-align:center; margin-top:50px; font-family:sans-serif;\">".$TR[$lang]['restarting_now']."<br><span style=\"font-size:16px; font-weight:normal;\">".$TR[$lang]['wait_refresh']."</span></h2>';
                         setTimeout(function(){ window.location.href = '/'; }, 15000);
                    }
                }, 1000);
            </script>
            ";
        } elseif (strpos($out, 'STATUS: UP_TO_DATE') !== false) {
             echo "
             <div class='alert alert-warning' style='text-align:left; margin: 20px;'>
                <strong>".$TR[$lang]['up_to_date']."</strong><br>
                ".$TR[$lang]['no_changes']."
             </div>
             <script>
                if(document.getElementById('loading-overlay')) {
                    document.getElementById('loading-overlay').style.display = 'none';
                }
             </script>
             <meta http-equiv='refresh' content='4;url=/'>";
        } else {
            echo "
            <div class='alert alert-error' style='text-align:left; margin: 20px;'>
                <strong>".$TR[$lang]['update_error']."</strong><br>
                <pre style='font-size:10px; margin-top:5px; background:#300; padding:5px; border-radius:3px; max-height:300px; overflow:auto;'>$out</pre>
                <br><a href='/' class='btn btn-blue' style='display:inline-block; width:auto; padding:5px 10px;'>".$TR[$lang]['btn_back']."</a>
            </div>
            <script>
                if(document.getElementById('loading-overlay')) {
                    document.getElementById('loading-overlay').style.display = 'none';
                }
            </script>";
        }
    }
    
    $wifi_output = "";
    $wifi_scan_results = [];
    
    if (isset($_POST['wifi_scan'])) { 
        shell_exec('sudo nmcli dev wifi rescan'); 
        $raw = shell_exec('sudo LC_ALL=C.UTF-8 nmcli -t -f SSID,SIGNAL,SECURITY dev wifi list 2>&1'); 
        $lines = explode("\n", $raw); 
        foreach($lines as $line) { 
            if(empty($line)) continue; 
            $parts = explode(':', $line); 
            $sec = array_pop($parts); 
            $sig = array_pop($parts); 
            $ssid = implode(':', $parts); 
            if(!empty($ssid)) {
                $wifi_scan_results[$ssid] = ['ssid'=>$ssid, 'signal'=>$sig, 'sec'=>$sec]; 
            }
        } 
        usort($wifi_scan_results, function($a, $b) { return $b['signal'] - $a['signal']; }); 
    }
    
    if (isset($_POST['wifi_connect'])) { 
        $ssid = escapeshellarg($_POST['ssid']); 
        $pass = escapeshellarg($_POST['pass']); 
        
        if (isset($_POST['save_offline'])) {
            shell_exec("sudo LC_ALL=C.UTF-8 nmcli connection delete $ssid 2>&1"); 
            shell_exec("sudo LC_ALL=C.UTF-8 nmcli connection add type wifi ifname wlan0 con-name $ssid autoconnect yes ssid $ssid 2>&1");
            shell_exec("sudo LC_ALL=C.UTF-8 nmcli connection modify $ssid wifi-sec.key-mgmt wpa-psk wifi-sec.psk $pass 2>&1");
            $wifi_output = shell_exec("sudo LC_ALL=C.UTF-8 nmcli connection up $ssid 2>&1"); 
        } else {
            $wifi_output = shell_exec("sudo LC_ALL=C.UTF-8 nmcli dev wifi connect $ssid password $pass 2>&1"); 
        }
    }
    
    if (isset($_POST['wifi_delete'])) { 
        $ssid = escapeshellarg($_POST['ssid']); 
        $wifi_output = shell_exec("sudo LC_ALL=C.UTF-8 nmcli connection delete $ssid 2>&1"); 
        echo "<div class='alert alert-warning'>".$TR[$lang]['wifi_deleted']."</div><meta http-equiv='refresh' content='2'>"; 
    }
    
    $saved_wifi_list = [];
    $raw_saved = shell_exec("sudo LC_ALL=C.UTF-8 nmcli -t -f NAME,TYPE connection show | grep ':802-11-wireless' | cut -d: -f1");
    if ($raw_saved) {
        $lines = explode("\n", $raw_saved);
        foreach($lines as $line) {
            $line = trim($line);
            if(!empty($line) && $line !== 'Rescue_AP' && $line !== 'preconfigured') {
                $saved_wifi_list[] = $line;
            }
        }
        sort($saved_wifi_list);
    }

    $cache_file = '/tmp/primenode_alert_cache.txt';
    $cache_time = 3600; 
    $alert_msg = "";
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) { 
        $alert_msg = file_get_contents($cache_file); 
    } else { 
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: PrimeNode-Hotspot\r\n",
                "timeout" => 5
            ]
        ];
        $ctx = stream_context_create($opts);
        $remote_msg = @file_get_contents('https://raw.githubusercontent.com/PrimeNodeSVX/PrimeNode_RPI0W-Update/main/alert.txt', false, $ctx); 
        
        if ($remote_msg !== false) { 
            $alert_msg = $remote_msg; 
            file_put_contents($cache_file, $alert_msg); 
        } elseif (file_exists($cache_file)) { 
            $alert_msg = file_get_contents($cache_file); 
        } 
    }
    
    $alert_hash = md5($alert_msg);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrimeNode <?php echo $vals['Callsign']; ?></title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .btn-loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }
        .btn-loading::after {
            content: "";
            position: absolute;
            left: 50%; top: 50%;
            width: 16px; height: 16px;
            margin-left: -8px; margin-top: -8px;
            border: 2px solid #fff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>

<body>

<div id="changelog-overlay" style="display: none;">
    <div id="changelog-modal" data-version="1.8">
        <h2 style="margin-top:0; color:#4CAF50; border-bottom: 1px solid #333; padding-bottom: 10px;">🚀 PrimeNode V1.8 - System Makr i Usprawnienia EchoLink</h2>
        
        <div style="text-align: left; font-size: 14px; color: #ccc; line-height: 1.6; max-height: 50vh; overflow-y: auto; padding-right: 10px;">
            <p style="margin-top: 0; color: #eee; font-size: 14px;">
                Najnowsza aktualizacja wprowadza długo wyczekiwany system inteligentnych makr radiowych oraz poprawki wizualne dla modułu EchoLink.
            </p>
            
            <ul style="padding-left: 20px; margin-top: 15px;">
                <li style="margin-bottom: 10px;">⚡ <b>Kreator Makr (Zakładka DTMF):</b> Przypisuj 2-cyfrowe skróty do ulubionych grup Reflektora oraz węzłów EchoLink. System sam dba o brak duplikatów i odpowiednie przełączanie logik.</li>
                <li style="margin-bottom: 10px;">📻 <b>Obsługa z poziomu radia:</b> Aby wywołać zapisane makro (np. pod numerem 4), wystarczy nacisnąć PTT i wpisać na klawiaturze radia: <b>D4#</b>. Koniec z ręcznym wpisywaniem długich numerów!</li>
                <li style="margin-bottom: 10px;">🟢 <b>Dynamiczny Status EchoLink:</b> Górny pasek statusu na żywo wyświetla teraz znak i ID węzła, z którym jesteś aktualnie połączony, pozwalając na szybki podgląd bez wchodzenia w logi.</li>
                <li style="margin-bottom: 10px;">🖥️ <b>Odświeżony Interfejs:</b> Zmodernizowany widok zakładki DTMF z kaskadowym menu, własnymi suwakami i okienkami typu modal blokującymi ekran centralnie.</li>
            </ul>
            
            <div style="text-align: right; margin-top: 25px; font-size: 15px; color: #2196F3; font-weight: bold; font-style: italic;">
                VY 73! de Marcin SQ7UTP
            </div>
        </div>
        
        <button class="btn btn-green" style="margin-top: 20px; width: 100%; font-size: 16px; padding: 12px;" onclick="closeChangelog()">Super, rozumiem. Zamykam okno!</button>
    </div>
</div>

<div id="loading-overlay">
    <div class="spinner"></div>
    <div class="loading-text" id="loading-text">Aktualizacja w toku...</div>
    <div class="loading-subtext">Nie wyłączaj urządzenia i nie odświeżaj strony.</div>
</div>

<div class="container">
    <div class="lang-switcher">
        <a href="?lang=pl" class="<?php echo $lang=='pl'?'active':''; ?>"><img src="flags/pl.svg" alt="PL"></a>
        <a href="?lang=en" class="<?php echo $lang=='en'?'active':''; ?>"><img src="flags/gb.svg" alt="EN"></a>
    </div>

    <?php if (!empty(trim($alert_msg))): ?>
    <div id="pn-alert" data-hash="<?php echo $alert_hash; ?>" style="background:#2196F3; color:#fff; padding:12px; font-weight:bold; border-bottom:2px solid #1976D2; font-size:14px; box-shadow: 0 2px 10px rgba(0,0,0,0.3); display:flex; justify-content:space-between; align-items:center;">
        <button onclick="dismissAlert('<?php echo $alert_hash; ?>')" style="background:none; border:none; color:#fff; font-weight:bold; font-size:16px; cursor:pointer; padding:0 10px; opacity:0.8;">&#10005;</button>
        <span style="flex:1; text-align:center;">📢 INFO: <?php echo htmlspecialchars($alert_msg); ?></span>
    </div>
    <?php endif; ?>
    
    <header>
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding-top: 10px; padding-bottom: 10px;">
            <img src="primenode_logo.png" alt="PrimeNode" style="height: 120px; width: auto; margin-bottom: 5px;">
            <h1 style="margin: 0; z-index: 2;">Hotspot <?php echo $vals['Callsign']; ?></h1>
        </div>
        <div class="status-bar" style="flex-direction: column; gap: 5px; margin-top:5px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span id="main-status-dot" class="status-dot red"></span>
                <span id="main-status-text" class="status-text inactive"><?php echo $TR[$lang]['sys_start']; ?></span>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span id="el-status-dot" class="status-dot" style="background-color: #444;"></span>
                <span id="el-status-text" class="status-text" style="color: #666; font-size: 0.85em; font-weight:normal;"><?php echo $TR[$lang]['el_init']; ?></span>
            </div>
        </div>
    </header>

    <div class="telemetry-row">
        <div class="t-box"><div class="t-label"><?php echo $TR[$lang]['cpu_temp']; ?></div><div class="t-val" id="t-temp">...</div><div class="progress-bg"><div class="progress-fill" id="t-temp-bar" style="width: 0%;"></div></div></div>
        <div class="t-box"><div class="t-label"><?php echo $TR[$lang]['ram_used']; ?></div><div class="t-val" id="t-ram">...</div><div class="progress-bg"><div class="progress-fill" id="t-ram-bar" style="width: 0%;"></div></div></div>
        <div class="t-box"><div class="t-label"><?php echo $TR[$lang]['disk_used']; ?></div><div class="t-val" id="t-disk">...</div><div class="progress-bg"><div class="progress-fill" id="t-disk-bar" style="width: 0%;"></div></div></div>
        <div class="t-box"><div class="t-label"><?php echo $TR[$lang]['network']; ?></div><div class="t-val" id="t-net-type">...</div><div style="font-size:9px; color:#aaa;" id="t-ip">...</div></div>
        <div class="t-box"><div class="t-label"><?php echo $TR[$lang]['hardware']; ?></div><div class="t-val" id="t-hw" style="font-size:10px; margin-top:5px;">...</div></div>
    </div>

    <div class="info-panel">
        <div class="info-box"><div class="info-label"><?php echo $TR[$lang]['logics']; ?></div><div class="info-value" style="font-size:11px;"><?php echo str_replace(',', ', ', $glob['LOGICS'] ?? '-'); ?></div></div>
        <div class="info-box"><div class="info-label"><?php echo $TR[$lang]['modules']; ?></div><div class="info-value" style="font-size:11px;"><?php echo $vals['Modules']; ?></div></div>
        <div class="info-box tg-tooltip-box" style="padding: 2px;">
    <div class="info-label" style="font-size:9px;">TG Def <span style="color:#777;">|</span> Monitor</div>
    <div class="info-value hl" style="font-size:11px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
        <span style="color:#fff;"><?php echo empty($vals['DefaultTG']) ? '-' : $vals['DefaultTG']; ?></span> 
        <span style="color:#777;">|</span> 
        <span style="color:#4CAF50;"><?php echo empty($vals['MonitorTGs']) ? '-' : $vals['MonitorTGs']; ?></span>
    </div>
    <?php if(!empty($vals['MonitorTGs'])): ?>
    <div class="tg-tooltip-text">
        <strong style="color:#4CAF50;">Monitorowane Grupy:</strong><br>
        <span style="color:#ccc;"><?php echo str_replace(',', ', ', $vals['MonitorTGs']); ?></span>
    </div>
    <?php endif; ?>
</div>
        <div class="info-box"><div class="info-label"><?php echo $TR[$lang]['tg_active']; ?></div><div class="info-value hl" id="tg-active">---</div></div>
        <div class="info-box"><div class="info-label"><?php echo $TR[$lang]['reflector']; ?></div><div class="info-value" id="ref-status">---</div></div>
        <div class="info-box"><div class="info-label"><?php echo $TR[$lang]['uptime']; ?></div><div class="info-value" style="font-size:11px;"><?php echo shell_exec("uptime -p"); ?></div></div>
    </div>

    <div class="tabs">
        <button id="btn-Dashboard" class="tab-btn active" onclick="openTab(event, 'Dashboard')"><?php echo $TR[$lang]['tab_dashboard']; ?></button>
        <button id="btn-Nodes" class="tab-btn" onclick="openTab(event, 'Nodes')"><?php echo $TR[$lang]['tab_nodes']; ?></button>
        <button id="btn-DTMF" class="tab-btn" onclick="openTab(event, 'DTMF')"><?php echo $TR[$lang]['tab_dtmf']; ?></button>
        <button id="btn-Radio" class="tab-btn" onclick="openTab(event, 'Radio')"><?php echo $TR[$lang]['tab_radio']; ?></button>
        <button id="btn-Audio" class="tab-btn" onclick="openTab(event, 'Audio')"><?php echo $TR[$lang]['tab_audio']; ?></button>
        <button id="btn-SvxConfig" class="tab-btn" onclick="openTab(event, 'SvxConfig')"><?php echo $TR[$lang]['tab_config']; ?></button>
        <button id="btn-WiFi" class="tab-btn" onclick="openTab(event, 'WiFi')"><?php echo $TR[$lang]['tab_wifi']; ?></button>
        <button id="btn-Power" class="tab-btn" onclick="openTab(event, 'Power')"><?php echo $TR[$lang]['tab_power']; ?></button>
        <button id="btn-Logs" class="tab-btn" onclick="openTab(event, 'Logs')"><?php echo $TR[$lang]['tab_logs']; ?></button>
        <button id="btn-SSH" class="tab-btn" onclick="openTab(event, 'SSH')">Terminal</button>
        <button id="btn-Help" class="tab-btn" onclick="openTab(event, 'Help')"><?php echo $TR[$lang]['tab_help']; ?></button>
    </div>

    <div id="Dashboard" class="tab-content active"><?php include 'tab_dashboard.php'; ?></div>
    <div id="DTMF" class="tab-content"><?php include 'tab_dtmf.php'; ?></div>
    <div id="Audio" class="tab-content"><?php include 'tab_audio.php'; ?></div>
    <div id="Radio" class="tab-content"><?php include 'tab_radio.php'; ?></div>
    <div id="SvxConfig" class="tab-content"><?php include 'tab_config.php'; ?></div>
    <div id="WiFi" class="tab-content"><?php include 'tab_wifi.php'; ?></div>
    <div id="Power" class="tab-content"><?php include 'tab_power.php'; ?></div>
    <div id="Nodes" class="tab-content"><?php include 'tab_nodes.php'; ?></div>
    <div id="Help" class="tab-content"><?php include 'help.php'; ?></div>
    <div id="Logs" class="tab-content"><div id="log-content" class="log-box">...</div></div>
    <div id="SSH" class="tab-content"><?php include 'tab_ssh.php'; ?></div>
</div>

<div class="main-footer">
    <?php 
    $svx_ver = trim(shell_exec('/usr/bin/svxlink --version 2>/dev/null || /usr/local/bin/svxlink --version 2>/dev/null'));

    if (empty($svx_ver)) {
        $svx_ver = "1.9.99.36@master";
    }

    $svx_ver = str_ireplace(['SvxLink', 'v', ' '], '', $svx_ver);
    ?>
    SvxLink v<?php echo htmlspecialchars($svx_ver); ?> Copyright (C) 2003-<?php echo date("Y"); ?> Tobias Blomberg / <span class="callsign-blue">SM0SVX</span><br>
    PrimeNode System • By SQ7UTP <span style="color: #aaa;">| Version: <strong style="color: #4CAF50;">V1.8</strong></span><br>
    Copyright © 2025-<?php echo date("Y"); ?>
</div>

<script>
const GLOBAL_CALLSIGN = "<?php echo $vals['Callsign']; ?>"; 
const GLOBAL_HOST = "<?php echo $vals['Host']; ?>";
const GLOBAL_NET_ID = "<?php
    $net_f = '/etc/svxlink/networks.json';
    echo (file_exists($net_f) ? (json_decode(@file_get_contents($net_f), true)['active'] ?? 0) : 0);
?>";

const GLOBAL_NET_NAME = "<?php
    $net_name = '';
    if (file_exists($net_f)) {
        $ndata = json_decode(@file_get_contents($net_f), true);
        $act = $ndata['active'] ?? 0;
        if ($act > 0 && !empty($ndata['list'])) {
            foreach ($ndata['list'] as $nn) {
                if ($nn['id'] == $act) { $net_name = $nn['name']; break; }
            }
        }
    }
    echo addslashes($net_name);
?>";

function dismissAlert(hash) {
    var alertBox = document.getElementById('pn-alert');
    if(alertBox) alertBox.style.display = 'none';
    localStorage.setItem('dismissed_alert', hash);
}
function checkAlert() {
    var alertBox = document.getElementById('pn-alert');
    if (alertBox) {
        var hash = alertBox.getAttribute('data-hash');
        if (localStorage.getItem('dismissed_alert') === hash) {
            alertBox.style.display = 'none';
        }
    }
}
setTimeout(checkAlert, 500);
</script>
<script src="script.js?v=<?php echo time(); ?>"></script>
</body>
</html>