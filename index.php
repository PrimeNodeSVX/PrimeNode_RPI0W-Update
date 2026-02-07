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
            'audio_saved' => '✅ Ustawienia Audio ZAPISANE.',
            'saved_restart' => 'Zapisano! Restart...',
            'radio_gpio_saved' => 'Konfiguracja Radio i GPIO Zapisana! Restart...',
            'restart_svc' => 'Restart Usługi...',
            'rebooting' => '🔄 Reboot...',
            'shutting_down' => '🛑 Shutdown...',
            'proxy_started' => '♻️ Uruchomiono Proxy Hunter.',
            'proxy_missing' => 'Brak pliku proxy_hunter.py!',
            'update_success' => '✅ AKTUALIZACJA ZAKOŃCZONA SUKCESEM!',
            'restarting_soon' => 'System zostanie zrestartowany za',
            'restarting_now' => 'Trwa ponowne uruchamianie...',
            'wait_refresh' => 'Poczekaj chwilę i odśwież stronę.',
            'up_to_date' => '⚠️ SYSTEM JEST JUŻ AKTUALNY',
            'no_changes' => 'Brak nowych zmian.',
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
            'tab_ssh' => 'SSH'
        ],
        'en' => [
            'audio_saved' => '✅ Audio SAVED.',
            'saved_restart' => 'Saved! Restarting...',
            'radio_gpio_saved' => 'Radio & GPIO Config Saved! Restarting...',
            'restart_svc' => 'Restarting Service...',
            'rebooting' => '🔄 Rebooting...',
            'shutting_down' => '🛑 Shutting down...',
            'proxy_started' => '♻️ Proxy Hunter started.',
            'proxy_missing' => 'Missing proxy_hunter.py!',
            'update_success' => '✅ UPDATE SUCCESSFUL!',
            'restarting_soon' => 'System will reboot in',
            'restarting_now' => 'Rebooting system...',
            'wait_refresh' => 'Please wait a moment and refresh.',
            'up_to_date' => '⚠️ SYSTEM IS UP TO DATE',
            'no_changes' => 'No new changes.',
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
            'tab_ssh' => 'SSH'
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
        header("Location: " . $_SERVER['PHP_SELF']); exit;
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
        header("Location: " . $_SERVER['PHP_SELF']); exit;
    }

    $jsonRadio = '/var/www/html/radio_config.json';
    $radio_conf = [];
    if (file_exists($jsonRadio)) {
        $radio_conf = json_decode(file_get_contents($jsonRadio), true);
    }
    
    $radio_type = isset($radio_conf['type']) ? $radio_conf['type'] : 'cm108';
    $card_str = isset($radio_conf['audio_dev']) ? $radio_conf['audio_dev'] : '';
    $CARD_ID = 0; // Domyślnie 0
    if (preg_match('/plughw:(\d+)/', $card_str, $m)) {
        $CARD_ID = (int)$m[1];
    } elseif (is_numeric($card_str)) {
        $CARD_ID = (int)$card_str;
    }


    $AUDIO_MAP = [
        'cm108' => [
            'sliders' => [
                'Spk_Vol' => ['name' => 'Speaker', 'type' => 'playback'],
                'Mic_Vol' => ['name' => 'Mic', 'type' => 'capture']
            ],
            'switches' => [
                'Spk_Sw' => ['name' => 'Speaker', 'type' => 'playback'],
                'Mic_Sw' => ['name' => 'Mic', 'type' => 'capture'],
                'AGC'    => ['name' => 'Auto Gain Control', 'type' => 'capture']
            ],
            'enums' => []
        ],
        'sa818' => [

            'sliders' => [
                'LineOut_Vol' => ['name' => 'Line Out', 'type' => 'playback'],
                'Mic1_Boost'  => ['name' => 'Mic1 Boost', 'type' => 'playback'],
                'Mic2_Boost'  => ['name' => 'Mic2 Boost', 'type' => 'playback'],
                'DAC_Vol'     => ['name' => 'DAC', 'type' => 'playback'],
                'ADC_Gain'    => ['name' => 'ADC Gain', 'type' => 'capture']
            ],
            'switches' => [
                'LineOut_Sw' => ['name' => 'Line Out', 'type' => 'playback'],
                'Mic1_Cap'   => ['name' => 'Mic1', 'type' => 'capture'],
                'Mic2_Cap'   => ['name' => 'Mic2', 'type' => 'capture'],
                'LineIn_Cap' => ['name' => 'Line In', 'type' => 'capture'],
                'DAC_Rev'    => ['name' => 'DAC Reverse', 'type' => 'playback']
            ],
            'enums' => [
                'LineOut_Mode' => ['name' => 'Line Out', 'options' => ['Stereo', 'Mono Differential']]
            ]
        ]
    ];

    $current_map = isset($AUDIO_MAP[$radio_type]) ? $AUDIO_MAP[$radio_type] : $AUDIO_MAP['cm108'];
    $audio_msg = '';


    if (isset($_POST['save_audio'])) {

        if (isset($current_map['sliders'])) {
            foreach ($current_map['sliders'] as $key => $cfg) {
                if (isset($_POST[$key])) {
                    $val = (int)$_POST[$key];
                    $cmd = "sudo /usr/bin/amixer -c $CARD_ID sset '{$cfg['name']}' $val%";
                    shell_exec($cmd);
                }
            }
        }

        if (isset($current_map['switches'])) {
            foreach ($current_map['switches'] as $key => $cfg) {

                $state = isset($_POST[$key]) ? 'on' : 'off';
                if ($key == 'Mic1_Cap' || $key == 'Mic2_Cap' || $key == 'LineIn_Cap') $state = isset($_POST[$key]) ? 'cap' : 'nocap';
                
                $cmd = "sudo /usr/bin/amixer -c $CARD_ID sset '{$cfg['name']}' $state";
                shell_exec($cmd);
            }
        }

        if (isset($current_map['enums'])) {
            foreach ($current_map['enums'] as $key => $cfg) {
                if (isset($_POST[$key])) {
                    $val = escapeshellarg($_POST[$key]);
                    $cmd = "sudo /usr/bin/amixer -c $CARD_ID sset '{$cfg['name']}' $val";
                    shell_exec($cmd);
                }
            }
        }
        
        shell_exec("sudo /usr/sbin/alsactl store $CARD_ID");
        $audio_msg = '<div class="alert alert-success">'.$TR[$lang]['audio_saved'].'</div>';
    }

    $audio_vals = [];
    
    function get_alsa_percent($card, $name) {
        $out = shell_exec("amixer -c $card sget '$name' 2>/dev/null");
        if (preg_match('/\[(\d+)%\]/', $out, $m)) return (int)$m[1];
        return 0;
    }
    
    function get_alsa_switch($card, $name) {
        $out = shell_exec("amixer -c $card sget '$name' 2>/dev/null");
        if (preg_match('/\[on\]/', $out)) return true;
        return false;
    }

    function get_alsa_enum($card, $name) {
        $out = shell_exec("amixer -c $card sget '$name' 2>/dev/null");
        if (preg_match("/Item0: '([^']+)'/", $out, $m)) return $m[1];
        return '';
    }

    if (isset($current_map['sliders'])) {
        foreach($current_map['sliders'] as $k => $cfg) {
            $audio_vals[$k] = get_alsa_percent($CARD_ID, $cfg['name']);
        }
    }

    if (isset($current_map['switches'])) {
        foreach($current_map['switches'] as $k => $cfg) {
            $audio_vals[$k] = get_alsa_switch($CARD_ID, $cfg['name']);
        }
    }

    if (isset($current_map['enums'])) {
        foreach($current_map['enums'] as $k => $cfg) {
            $audio_vals[$k] = get_alsa_enum($CARD_ID, $cfg['name']);
        }
    }

    if (isset($_GET['ajax_stats'])) {
        header('Content-Type: application/json');
        $stats = [];
        $model = @file_get_contents('/sys/firmware/devicetree/base/model');
        $stats['hw'] = $model ? str_replace("\0", "", trim($model)) : "System";
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
        $stats['el_error'] = file_exists('/var/www/html/el_error.flag');
        $stats['el_online'] = file_exists('/var/www/html/el_online.flag');
        echo json_encode($stats);
        exit;
    }

    if (isset($_POST['ajax_dtmf'])) {
        $code = $_POST['ajax_dtmf'];
        if (preg_match('/^[0-9A-D*#]+$/', $code)) {
            shell_exec("sudo /usr/local/bin/send_dtmf.sh " . escapeshellarg($code));
            echo "OK: $code";
        } else { echo "ERROR"; }
        exit;
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
        'Callsign' => $ref['CALLSIGN'] ?? 'N0CALL', 'Host' => $ref['HOSTS'] ?? '', 'Port' => $ref['HOST_PORT'] ?? '', 'Password' => $ref['AUTH_KEY'] ?? '',
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

    $radio = [
        "rx" => "432.8000", "tx" => "432.8000", "ctcss" => "0000", "desc" => "Brak opisu",
        "gpio_ptt" => $tx1['PTT_GPIOD_LINE'] ?? '12',
        "gpio_sql" => $rx1['SQL_GPIOD_LINE'] ?? '16'
    ];
    
    if (file_exists($jsonRadio)) { 
        $loaded = json_decode(file_get_contents($jsonRadio), true); 
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
        $updateData = [
            "rx" => $_POST['rx_freq'],
            "tx" => $_POST['tx_freq'],
            "ctcss" => $_POST['ctcss_val'],
            "desc" => $_POST['radio_desc'],
            "audio_dev" => $_POST['audio_dev'],
            "audio_chan" => $_POST['audio_chan'],
            "gpio_ptt" => $_POST['gpio_ptt'] ?? '12',
            "gpio_sql" => $_POST['gpio_sql'] ?? '16',
            "radio_type" => $_POST['radio_type'] ?? 'cm108',
            "serial_port" => $_POST['serial_port'] ?? '',
            "hid_device" => $_POST['hid_device'] ?? ''
        ];
        
        file_put_contents('/tmp/svx_new_settings.json', json_encode($updateData));
        shell_exec('sudo /usr/bin/python3 /usr/local/bin/update_svx_full.py 2>&1');
        
        shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &');
        echo "<div class='alert alert-success'>".$TR[$lang]['radio_gpio_saved']."</div><meta http-equiv='refresh' content='3'>";
    }

    if (isset($_POST['restart_srv'])) { shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &'); echo "<div class='alert alert-success'>".$TR[$lang]['restart_svc']."</div>"; }
    if (isset($_POST['reboot_device'])) { shell_exec('sudo /usr/sbin/reboot > /dev/null 2>&1 &'); echo "<div class='alert alert-warning'>".$TR[$lang]['rebooting']."</div>"; }
    if (isset($_POST['shutdown_device'])) { shell_exec('sudo /usr/sbin/shutdown -h now > /dev/null 2>&1 &'); echo "<div class='alert alert-error'>".$TR[$lang]['shutting_down']."</div>"; }
    
    if (isset($_POST['auto_proxy'])) { 
        if (file_exists('/usr/local/bin/auto_proxy.py')) {
             shell_exec('sudo /usr/bin/python3 /usr/local/bin/auto_proxy.py > /dev/null 2>&1 &');
             echo "<div class='alert alert-warning'>".$TR[$lang]['proxy_started']."</div><meta http-equiv='refresh' content='8'>";
        } else {
             echo "<div class='alert alert-error'>".$TR[$lang]['proxy_missing']."</div>";
        }
    }
    
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotspot <?php echo $vals['Callsign']; ?></title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="lang-switcher">
        <a href="?lang=pl" class="<?php echo $lang == 'pl' ? 'active' : ''; ?>"><img src="flags/pl.svg" alt="PL"></a>
        <a href="?lang=en" class="<?php echo $lang == 'en' ? 'active' : ''; ?>"><img src="flags/gb.svg" alt="EN"></a>
    </div>
    
    <header>
        <div style="position: relative; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100px; gap: 10px;">
            <img src="primenode_logo.png" alt="PrimeNode" style="height: 120px; width: auto; display: block;">
            <h1 style="margin: 0; z-index: 2;">Hotspot <?php echo $vals['Callsign']; ?></h1>
        </div>
        <div class="status-bar" style="flex-direction: column; gap: 5px; margin-top:5px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span id="main-status-dot" class="status-dot red"></span>
                <span id="main-status-text" class="status-text inactive">SYSTEM START...</span>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span id="el-status-dot" class="status-dot" style="background-color: #444;"></span>
                <span id="el-status-text" class="status-text" style="color: #666; font-size: 0.85em; font-weight:normal;">EchoLink Init...</span>
            </div>
        </div>
    </header>

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
        <button id="btn-Help" class="tab-btn" onclick="openTab(event, 'Help')"><?php echo $TR[$lang]['tab_help']; ?></button>
        <button id="btn-SSH" class="tab-btn" onclick="openTab(event, 'SSH')"><?php echo $TR[$lang]['tab_ssh']; ?></button>
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
    SvxLink v1.9.99.36@master Copyright (C) 2003-<?php echo date("Y"); ?> Tobias Blomberg / <span class="callsign-blue">SM0SVX</span><br>
    PrimeNode System • By SQ7UTP<br>
    Copyright © 2025-<?php echo date("Y"); ?>
</div>
<script> const GLOBAL_CALLSIGN = "<?php echo $vals['Callsign']; ?>"; </script>
<script src="script.js?v=<?php echo time(); ?>"></script>
</body>
</html>