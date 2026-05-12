<?php
    $__config_mutex_id = "\x50\x72\x69\x6d\x65\x4e\x6f\x64\x65\x20\x53\x51\x37\x55\x54\x50";
    $net_file = '/etc/svxlink/networks.json';
    $sound_dir = '/usr/local/share/svxlink/sounds/ref_sounds';
    $available_sounds = [];
    if (is_dir($sound_dir)) {
        $files = glob($sound_dir . '/*.wav');
        if ($files !== false) {
            foreach ($files as $file) {
                $available_sounds[] = basename($file);
            }
        }
    }

    if (isset($_GET['restore']) && $_GET['restore'] == 'ok') {
        echo "<div class='alert alert-success'>✅ Konfiguracja przywrócona pomyślnie! Usługi zostały zrestartowane.</div>";
    }

    if (isset($_POST['export_backup'])) {

        $backup_name = 'PrimeNode_Backup_' . date('Y-m-d_His') . '.zip';
        $zip_path = '/dev/shm/' . $backup_name;

        $files_to_zip = [
            '/etc/svxlink/svxlink.conf' => 'svxlink.conf',
            '/var/www/html/radio_config.json' => 'radio_config.json',
            '/etc/svxlink/networks.json' => 'networks.json',
            '/var/www/html/dtmf_custom.json' => 'dtmf_custom.json',
            '/etc/svxlink/node_info.json' => 'node_info.json'
        ];

        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
                foreach ($files_to_zip as $path => $name) {
                    if (file_exists($path)) { $zip->addFile($path, $name); }
                }
                $zip->close();
            }
        } else {
            $files_str = implode(' ', array_filter(array_keys($files_to_zip), 'file_exists'));
            shell_exec("zip -j " . escapeshellarg($zip_path) . " " . $files_str);
        }

        if (file_exists($zip_path)) {
            $b64 = base64_encode(file_get_contents($zip_path));
            $filename = basename($zip_path);

            echo "<script>
                var link = document.createElement('a');
                link.href = 'data:application/zip;base64," . $b64 . "';
                link.download = '" . $filename . "';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            </script>";

            unlink($zip_path); 
        } else {
            echo "<div class='alert alert-error'>❌ Błąd: Nie udało się utworzyć pliku ZIP.</div>";
        }
    }

    if (isset($_POST['import_backup']) && isset($_FILES['backup_zip'])) {

        if ($_FILES['backup_zip']['error'] !== UPLOAD_ERR_OK) {
            echo "<div class='alert alert-error'>❌ Błąd przesyłania pliku na serwer. Kod błędu: " . $_FILES['backup_zip']['error'] . "</div>";
        } else {
            $uploaded = $_FILES['backup_zip']['tmp_name'];
            $tmp_dir = '/dev/shm/restore_pn/';
            
            shell_exec("sudo rm -rf " . escapeshellarg($tmp_dir));
            mkdir($tmp_dir, 0700, true); 

            $success = false;
            if (class_exists('ZipArchive')) {
                $zip = new ZipArchive;
                if ($zip->open($uploaded) === TRUE) {
                    $zip->extractTo($tmp_dir);
                    $zip->close();
                    $success = true;
                }
            } else {
                shell_exec("unzip -j -o " . escapeshellarg($uploaded) . " -d " . escapeshellarg($tmp_dir));
                $success = true;
            }

            if ($success) {
                $map = [
                    'svxlink.conf' => '/etc/svxlink/svxlink.conf',
                    'radio_config.json' => '/var/www/html/radio_config.json',
                    'networks.json' => '/etc/svxlink/networks.json',
                    'dtmf_custom.json' => '/var/www/html/dtmf_custom.json',
                    'node_info.json' => '/etc/svxlink/node_info.json'
                ];

                $restored_count = 0;
                $debug_log = "";

                foreach ($map as $file => $dest) {
                    $src = $tmp_dir . $file;
                    if (file_exists($src)) {

                        $out = shell_exec("sudo cp -fv " . escapeshellarg($src) . " " . escapeshellarg($dest) . " 2>&1");
                        error_log("RESTORE COPY ($file): " . trim($out));
                        $debug_log .= $out . "\n";


                        if (strpos($dest, '/etc/svxlink/') !== false) {
                            shell_exec("sudo chown root:root " . escapeshellarg($dest));
                            shell_exec("sudo chmod 644 " . escapeshellarg($dest));
                        } else {
                            shell_exec("sudo chown www-data:www-data " . escapeshellarg($dest));
                            shell_exec("sudo chmod 664 " . escapeshellarg($dest));
                        }
                        $restored_count++;
                    }
                }
                shell_exec("sudo rm -rf " . escapeshellarg($tmp_dir));
                
                if ($restored_count > 0) {

                    if (function_exists('opcache_reset')) {
                        opcache_reset();
                    }
                    clearstatcache(true);
                    shell_exec("sudo systemctl restart svxlink > /dev/null 2>&1 &");
                    usleep(500000); 

                    echo "<script>window.location.href='index.php?restore=ok';</script>";
                exit; 
                } else {
                    echo "<div class='alert alert-error'>❌ Błąd: Brak wymaganych plików w archiwum ZIP.<br><pre>$debug_log</pre></div>";
                }
            } else {
                echo "<div class='alert alert-error'>❌ Błąd otwarcia ZIP. Spróbuj pobrać i wgrać paczkę ponownie.</div>";
            }
        }
    }


    function save_networks_json($data, $path) {

        $tmp = '/dev/shm/tmp_networks.json';
        file_put_contents($tmp, json_encode($data, JSON_PRETTY_PRINT));
        shell_exec("sudo cp -f " . escapeshellarg($tmp) . " " . escapeshellarg($path));
        shell_exec("sudo chown www-data:www-data " . escapeshellarg($path));
        shell_exec("sudo chmod 664 " . escapeshellarg($path));

        @unlink($tmp);
    }

    if (!file_exists($net_file)) {
        $default_net = [ "active" => 0, "list" => [] ];
        save_networks_json($default_net, $net_file);
        shell_exec("sudo chmod 664 $net_file"); 
    }

    $networks_raw = @file_get_contents($net_file);
    $networks = json_decode($networks_raw, true);
    if(!is_array($networks)) $networks = [ "active" => 0, "list" => [] ];

    $edit_mode = false;
    $edit_data = ['id'=>'','name'=>'','host'=>'','port'=>'5300','pass'=>'','api'=>'','tgs'=>'','callsign'=>'','deftg'=>'','audio'=>''];
    $active_callsign = isset($vals['Callsign']) ? $vals['Callsign'] : ''; 
    
    if (isset($networks['active']) && $networks['active'] > 0 && isset($networks['list'])) {
        foreach ($networks['list'] as $net) {
            if ($net['id'] == $networks['active']) {
                $active_callsign = $net['callsign'];
                break;
            }
        }
    }

    if (isset($_POST['save_network'])) {
        $id_to_save = $_POST['n_id'];
        $is_new = true;

        $new_data = [
            'id' => ($id_to_save != '') ? $id_to_save : 0,
            'name' => htmlspecialchars($_POST['n_name']),
            'host' => htmlspecialchars($_POST['n_host']),
            'port' => htmlspecialchars($_POST['n_port']),
            'pass' => htmlspecialchars($_POST['n_pass']),
            'api' => htmlspecialchars($_POST['n_api']),
            'tgs' => htmlspecialchars($_POST['n_tgs']),
            'callsign' => strtoupper(htmlspecialchars($_POST['n_callsign'])),
            'deftg' => htmlspecialchars($_POST['n_deftg']),
            'audio' => htmlspecialchars($_POST['n_audio'] ?? '')
        ];

        if ($id_to_save != '') {
            foreach ($networks['list'] as $key => $net) {
                if ($net['id'] == $id_to_save) {
                    $networks['list'][$key] = $new_data;
                    $is_new = false;
                    break;
                }
            }
        }

        if ($is_new) {
            $new_id = 1;
            if (!empty($networks['list'])) {
                $ids = array_column($networks['list'], 'id');
                $new_id = max($ids) + 1;
            }
            $new_data['id'] = $new_id;
            $networks['list'][] = $new_data;
        }
        
        save_networks_json($networks, $net_file);

        if (isset($networks['active']) && $networks['active'] == $new_data['id']) {
            $switch_data = [
                'Callsign'   => $new_data['callsign'],
                'Host'       => $new_data['host'],
                'Port'       => $new_data['port'],
                'Password'   => $new_data['pass'],
                'DefaultTG'  => $new_data['deftg'],
                'MonitorTGs' => $new_data['tgs'],
                'node_api_url' => $new_data['api'],
                'audio_file' => $new_data['audio'],
                'AnnounceCall' => $vals['AnnounceCall']
            ];

            file_put_contents('/tmp/svx_new_settings.json', json_encode($switch_data));
            shell_exec('sudo /usr/bin/python3 /usr/local/bin/update_svx_full.py 2>&1');
            shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &');
            echo "<div class='alert alert-success'>Zaktualizowano parametry aktywnej sieci. Restart...</div>";
        }

        echo "<script>window.location.href='index.php';</script>";
        exit;
    }

    if (isset($_POST['del_network'])) {
        $del_id = $_POST['del_network'];
        $was_active = ($networks['active'] == $del_id); 

        foreach ($networks['list'] as $key => $net) {
            if ($net['id'] == $del_id) {
                unset($networks['list'][$key]);
            }
        }
        $networks['list'] = array_values($networks['list']);
        
        if ($was_active) {
            $networks['active'] = 0;

            $disconnect_data = [
                'Host' => '', 
                'Port' => '0',
                'Password' => '',
                'MonitorTGs' => '',
                'node_api_url' => '',
                'Callsign' => '',
                'audio_file' => ''
            ];
            file_put_contents('/tmp/svx_new_settings.json', json_encode($disconnect_data));
            shell_exec('sudo /usr/bin/python3 /usr/local/bin/update_svx_full.py 2>&1');
            shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &');
        }

        save_networks_json($networks, $net_file);
        echo "<script>window.location.href='index.php';</script>";
        exit;
    }

    if (isset($_POST['switch_network'])) {
        $target_id = $_POST['switch_network'];
        $selected_net = null;

        foreach ($networks['list'] as $net) {
            if ($net['id'] == $target_id) {
                $selected_net = $net;
                break;
            }
        }

        if ($selected_net) {
            $switch_data = [
                'Callsign'   => $selected_net['callsign'],
                'Host'       => $selected_net['host'],
                'Port'       => $selected_net['port'],
                'Password'   => $selected_net['pass'],
                'DefaultTG'  => isset($selected_net['deftg']) ? $selected_net['deftg'] : '0',
                'MonitorTGs' => isset($selected_net['tgs']) ? $selected_net['tgs'] : '',
                'node_api_url' => isset($selected_net['api']) ? $selected_net['api'] : '',
                'audio_file' => $selected_net['audio'] ?? '',
                'AnnounceCall' => $vals['AnnounceCall']
            ];

            file_put_contents('/tmp/svx_new_settings.json', json_encode($switch_data));
            shell_exec('sudo /usr/bin/python3 /usr/local/bin/update_svx_full.py 2>&1');
            $networks['active'] = $target_id;
            save_networks_json($networks, $net_file);
            
            shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &');
            echo "<div class='alert alert-success'>Przełączono na: " . htmlspecialchars($selected_net['name']) . ". Restart...</div>";
            echo "<script>setTimeout(function(){ window.location.href='index.php'; }, 3000);</script>";
        }
    }

    if (isset($_POST['edit_network'])) {
        $edit_mode = true;
        foreach ($networks['list'] as $net) {
            if ($net['id'] == $_POST['edit_network']) {
                $edit_data = $net;

                if(empty($edit_data['callsign'])) $edit_data['callsign'] = $vals['Callsign'] ?? ''; 
                if(empty($edit_data['deftg'])) $edit_data['deftg'] = $vals['DefaultTG'] ?? '';
                if(empty($edit_data['tgs'])) $edit_data['tgs'] = $vals['MonitorTGs'] ?? '';
                if(empty($edit_data['audio'])) $edit_data['audio'] = '';
                break;
            }
        }
    }

    $current_audio_lang = $simp['DEFAULT_LANG'] ?? 'PL';

    $TC = [
        'pl' => [
            'opt_default' => 'Domyślny',
            'header' => 'Konfiguracja SvxLink',
            'sect_roam' => 'Menedżer Sieci (Roaming)',
            'th_name' => 'Nazwa',
            'th_host' => 'Host',
            'th_dtmf' => 'Kod DTMF',
            'th_act' => 'Akcja',
            'btn_switch' => 'PRZEŁĄCZ',
            'btn_edit' => 'EDYTUJ',
            'lbl_active' => 'AKTYWNY',
            'lbl_add_new' => 'Edycja / Dodawanie Sieci:',
            'btn_add' => 'ZAPISZ SIEC',
            'btn_cancel' => 'ANULUJ',
            'ph_name' => 'Nazwa (np. Mój Serwer)',
            'ph_host' => 'Host (np. sqlink.pl)',
            'ph_pass' => 'Hasło',
            'ph_tgs' => 'Monitorowane TG (np. 260)',
            'ph_call' => 'Znak Noda',
            'ph_deftg' => 'Startowe TG',
            'lbl_audio_sel' => 'Zapowiedź Audio',
            
            'sect_el' => 'EchoLink',
            'lbl_el_call' => 'Znak EchoLink',
            'lbl_el_pass' => 'Hasło EchoLink',
            'lbl_el_sysop' => 'Nazwa Sysop',
            'lbl_el_desc' => 'Opis Stacji',
            'lbl_el_proxy' => 'Proxy (IP)',
            'ph_el_proxy' => 'np. 44.31.61.106',
            'btn_proxy' => '♻️ Auto-Proxy',
            'sect_loc' => 'Lokalizacja i Operator',
            'lbl_name' => 'Imię Operatora',
            'lbl_city' => 'Miasto (QTH)',
            'lbl_loc' => 'QTH Locator',
            'sect_map' => 'Wygląd Mapy',
            'btn_dark' => '🌑 Ciemna',
            'btn_light' => '☀️ Jasna',
            'btn_osm' => '🗺️ Kolorowa',
            'sect_adv' => 'Audio i Moduły',
            'lbl_modules' => 'Aktywne Moduły',
            'btn_help' => 'Pomoc',
            'btn_parrot' => 'Papuga',
            'btn_el' => 'EchoLink',
            'lbl_tg_time' => 'TG Timeout',
            'lbl_tmp_time' => 'Tmp Timeout',
            'lbl_beep' => 'Beep 3-ton',
            'lbl_ann_tg' => 'Zapowiedź TG',
            'lbl_info' => 'Info Link',
            'lbl_roger' => 'Roger Beep',
            'lbl_voice_id' => 'Recytowanie Znaku',
            'lbl_lang_audio' => 'Język Audio',
            'opt_yes' => 'TAK',
            'opt_no' => 'NIE',
            'btn_save' => 'Zapisz Ustawienia Globalne',
            'tg_modal_title' => '🎙️ Wybierz Grupy TG',
            'tg_selected' => 'Wybrane:',
            'tg_ph_manual' => 'Wpisz nr TG...',
            'btn_add_tg' => 'DODAJ',
            'btn_confirm' => '✅ ZATWIERDŹ',
            'btn_cancel_modal' => '❌ ANULUJ',

            'sect_aprs' => '🌍 Ustawienia APRS',
            'lbl_aprs_enable' => 'Włącz Raportowanie APRS',
            'lbl_aprs_pass' => 'APRS Passcode',
            'ph_aprs_pass' => 'Np. 12345',
            'lbl_aprs_ssid' => 'SSID APRS (Opcjonalnie)',
            'opt_aprs_ssid_none' => 'Brak (Główny znak)',
            'lbl_aprs_icon' => 'Ikona na mapie APRS',
            'lbl_aprs_interval' => 'Interwał Beacon (minuty)',
            'lbl_aprs_comment' => 'Twój Komentarz',
            'ph_aprs_comment' => 'Opis stacji...',
            'lbl_aprs_lat' => 'Szerokość (LAT)',
            'ph_aprs_lat' => 'Np. 51.3665 lub 51.21.59N',
            'lbl_aprs_lon' => 'Długość (LON)',
            'ph_aprs_lon' => 'Np. 19.3761 lub 019.22.34E',
            'lbl_aprs_power' => 'Moc nadajnika (W)',
            'ph_aprs_num' => 'Np. ',
            'lbl_aprs_gain' => 'Zysk anteny (dBi)',
            'lbl_aprs_height' => 'Wysokość anteny (m n.p.g.)',
            'icon_e0' => '🌐 [ E 0 ] Węzeł EchoLink (Kółko z E)',
            'icon_n' => '🎯 [ / n ] Węzeł / Node (Czarna kropka)',
            'icon_digi' => '🌟 [ / # ] Cyfrowe Digi (Gwiazdka)',
            'icon_r' => '📡 [ / r ] Przemiennik (Zielona Antena)',
            'icon_home' => '🏠 [ / - ] Dom (Stacja QTH)',
            'icon_car' => '🚗 [ / > ] Samochód (Stacja Mobile)',
            'icon_van' => '🚙 [ \ v ] Van (Niebieskie autko)',
            'icon_human' => '🚶 [ / [ ] Człowiek (Przenośna)',
        ],
        'en' => [
            'opt_default' => 'Default',
            'header' => 'SvxLink Configuration',
            'sect_roam' => 'Network Manager (Roaming)',
            'th_name' => 'Name',
            'th_host' => 'Host',
            'th_dtmf' => 'DTMF Code',
            'th_act' => 'Action',
            'btn_switch' => 'SWITCH',
            'btn_edit' => 'EDIT',
            'lbl_active' => 'ACTIVE',
            'lbl_add_new' => 'Edit / Add Network:',
            'btn_add' => 'SAVE NETWORK',
            'btn_cancel' => 'CANCEL',
            'ph_name' => 'Name',
            'ph_host' => 'Host',
            'ph_pass' => 'Password',
            'ph_tgs' => 'Monitor TGs',
            'ph_call' => 'Node Callsign',
            'ph_deftg' => 'Default TG',
            'lbl_audio_sel' => 'Voice ID',

            'sect_el' => 'EchoLink',
            'lbl_el_call' => 'EchoLink Callsign',
            'lbl_el_pass' => 'EchoLink Password',
            'lbl_el_sysop' => 'Sysop Name',
            'lbl_el_desc' => 'Description',
            'lbl_el_proxy' => 'Proxy (IP)',
            'ph_el_proxy' => 'e.g. 44.31.61.106',
            'btn_proxy' => '♻️ Auto-Proxy',
            'sect_loc' => 'Location & Operator',
            'lbl_name' => 'Operator Name',
            'lbl_city' => 'City (QTH)',
            'lbl_loc' => 'QTH Locator',
            'sect_map' => 'Map Style',
            'btn_dark' => '🌑 Dark',
            'btn_light' => '☀️ Light',
            'btn_osm' => '🗺️ Colorful',
            'sect_adv' => 'Audio & Modules',
            'lbl_modules' => 'Active Modules',
            'btn_help' => 'Help',
            'btn_parrot' => 'Parrot',
            'btn_el' => 'EchoLink',
            'lbl_tg_time' => 'TG Timeout',
            'lbl_tmp_time' => 'Tmp Timeout',
            'lbl_beep' => '3-Tone Beep',
            'lbl_ann_tg' => 'Announce TG',
            'lbl_info' => 'Link Info',
            'lbl_roger' => 'Roger Beep',
            'lbl_voice_id' => 'Voice ID',
            'lbl_lang_audio' => 'Audio Lang',
            'opt_yes' => 'YES',
            'opt_no' => 'NO',
            'btn_save' => 'Save Global Settings',
            'tg_modal_title' => '🎙️ Select TG Groups',
            'tg_selected' => 'Selected:',
            'tg_ph_manual' => 'Enter TG no...',
            'btn_add_tg' => 'ADD',
            'btn_confirm' => '✅ CONFIRM',
            'btn_cancel_modal' => '❌ CANCEL',

            'sect_aprs' => '🌍 APRS Settings',
            'lbl_aprs_enable' => 'Enable APRS Reporting',
            'lbl_aprs_pass' => 'APRS Passcode',
            'ph_aprs_pass' => 'e.g. 12345',
            'lbl_aprs_ssid' => 'APRS SSID (Optional)',
            'opt_aprs_ssid_none' => 'None (Main callsign)',
            'lbl_aprs_icon' => 'Map Icon (Symbol)',
            'lbl_aprs_interval' => 'Beacon Interval (minutes)',
            'lbl_aprs_comment' => 'Your Comment',
            'ph_aprs_comment' => 'Station description...',
            'lbl_aprs_lat' => 'Latitude (LAT)',
            'ph_aprs_lat' => 'e.g. 51.3665 or 51.21.59N',
            'lbl_aprs_lon' => 'Longitude (LON)',
            'ph_aprs_lon' => 'e.g. 19.3761 or 019.22.34E',
            'lbl_aprs_power' => 'TX Power (W)',
            'ph_aprs_num' => 'e.g. ',
            'lbl_aprs_gain' => 'Antenna Gain (dBi)',
            'lbl_aprs_height' => 'Antenna Height (m AGL)',
            'icon_e0' => '🌐 [ E 0 ] EchoLink Node (Circle with E)',
            'icon_n' => '🎯 [ / n ] Node (Black dot)',
            'icon_digi' => '🌟 [ / # ] Digital Digi (Star)',
            'icon_r' => '📡 [ / r ] Repeater (Green Antenna)',
            'icon_home' => '🏠 [ / - ] Home (QTH Station)',
            'icon_car' => '🚗 [ / > ] Car (Mobile Station)',
            'icon_van' => '🚙 [ \ v ] Van (Blue car)',
            'icon_human' => '🚶 [ / [ ] Human (Portable)',
        ]
    ];
?>

<h3><?php echo $TC[$lang]['header']; ?></h3>
<div class="panel-box box-full" style="border: 1px solid #FF9800;">
    <h4 class="panel-title" style="color:#FF9800; border-color:#FF9800;"><?php echo $TC[$lang]['sect_roam']; ?></h4>
    <table class="wifi-saved-table">
        <tr>
            <th><?php echo $TC[$lang]['th_name']; ?></th>
            <th><?php echo $TC[$lang]['th_host']; ?></th>
            <th><?php echo $TC[$lang]['th_dtmf']; ?></th>
            <th><?php echo $TC[$lang]['th_act']; ?></th>
        </tr>
        <?php if(isset($networks['list'])): foreach($networks['list'] as $net): ?>
        <tr style="<?php echo ($networks['active'] == $net['id'] ? 'background:rgba(76,175,80,0.1); border-left: 3px solid #4CAF50;' : ''); ?>">
            <td><?php echo $net['name']; ?></td>
            <td><?php echo $net['host']; ?></td>
            <td style="font-weight:bold; color:#FF9800;">555<?php echo $net['id']; ?>#</td>
            <td style="display:flex; gap:5px; align-items: center;">
                <?php if($networks['active'] != $net['id']): ?>
                    <form method="post" style="margin:0;">
                        <button type="submit" name="switch_network" value="<?php echo $net['id']; ?>" class="btn-small-del" style="background:#2196F3; font-weight:bold;"><?php echo $TC[$lang]['btn_switch']; ?></button>
                    </form>
                <?php else: ?>
                    <span style="color:#4CAF50; font-weight:bold; padding:5px; font-size:12px;"><?php echo $TC[$lang]['lbl_active']; ?></span>
                <?php endif; ?>
                
                <form method="post" style="margin:0;">
                    <button type="submit" name="edit_network" value="<?php echo $net['id']; ?>" class="btn-small-del" style="background:#FF9800;"><?php echo $TC[$lang]['btn_edit']; ?></button>
                </form>

                <form method="post" style="margin:0;">
                    <button type="submit" name="del_network" value="<?php echo $net['id']; ?>" class="btn-small-del" onclick="return confirm('Usunąć?');">X</button>
                </form>
            </td>
        </tr>
        <?php endforeach; endif; ?>
    </table>

    <div style="background:#2a2a2a; padding:15px; border-radius:5px; margin-top:15px; border:1px solid #444;">
        <label style="margin-bottom:8px; color:#ddd; font-size:14px;"><?php echo $TC[$lang]['lbl_add_new']; ?></label>
        <form method="post">
            <input type="hidden" name="n_id" value="<?php echo $edit_data['id']; ?>">
            
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
                <div style="flex:1; min-width:150px;">
                    <input type="text" name="n_name" placeholder="<?php echo $TC[$lang]['ph_name']; ?>" value="<?php echo $edit_data['name']; ?>" required>
                </div>
                <div style="flex:1; min-width:150px;">
                    <input type="text" name="n_host" placeholder="<?php echo $TC[$lang]['ph_host']; ?>" value="<?php echo $edit_data['host']; ?>" required>
                </div>
                <div style="width:80px;">
                    <input type="number" name="n_port" placeholder="5300" value="<?php echo $edit_data['port']; ?>" required>
                </div>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
                <div style="flex:1; min-width:120px;">
                    <input type="text" name="n_callsign" placeholder="<?php echo $TC[$lang]['ph_call']; ?>" value="<?php echo $edit_data['callsign']; ?>" oninput="this.value = this.value.toUpperCase()" style="text-transform:uppercase;" required>
                </div>
                <div style="flex:1; min-width:120px;">
                    <input type="password" name="n_pass" placeholder="<?php echo $TC[$lang]['ph_pass']; ?>" value="<?php echo $edit_data['pass']; ?>" required>
                </div>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
                <div style="flex:1; min-width:200px;">
                    <input type="text" name="n_api" placeholder="API URL (http://...)" value="<?php echo $edit_data['api']; ?>">
                </div>
                
                <div style="flex:1; min-width:150px;">
    <select name="n_audio" style="width:100%; cursor:pointer; color: #ccc; background: transparent; border: 1px solid #444; padding: 8px; border-radius: 4px;">
        <option value="" style="background: #2a2a2a; color: #ccc;"><?php echo $TC[$lang]['lbl_audio_sel']; ?>: <?php echo $TC[$lang]['opt_default']; ?></option>
        <?php foreach($available_sounds as $snd): ?>
            <option value="<?php echo $snd; ?>" style="background: #2a2a2a; color: #ccc;" <?php if($edit_data['audio'] == $snd) echo 'selected'; ?>><?php echo $snd; ?></option>
        <?php endforeach; ?>
    </select>
</div>

                <div style="flex:1; min-width:100px;">
                    <input type="text" id="n_deftg_input" name="n_deftg" placeholder="<?php echo $TC[$lang]['ph_deftg']; ?>" value="<?php echo $edit_data['deftg']; ?>" onclick="openTgSelector('n_deftg_input', 'single')" style="cursor: pointer;" readonly title="Kliknij, aby wybrać z listy">
                </div>
                <div style="flex:1; min-width:100px;">
                    <input type="text" id="n_tgs_input" name="n_tgs" placeholder="<?php echo $TC[$lang]['ph_tgs']; ?>" value="<?php echo $edit_data['tgs']; ?>" onclick="openTgSelector('n_tgs_input', 'multi')" style="cursor: pointer;" readonly title="Kliknij, aby wybrać z listy">
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:5px;">
                <button type="submit" name="save_network" class="btn-small-del" style="background:#4CAF50; color:#fff; font-weight:bold; width:auto; padding:10px 30px; font-size:14px;"><?php echo $TC[$lang]['btn_add']; ?></button>
                <?php if($edit_mode): ?>
                    <a href="index.php" class="btn-small-del" style="background:#777; text-decoration:none; padding:10px 20px; display:inline-block;"><?php echo $TC[$lang]['btn_cancel']; ?></a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="SvxConfig">
    
    <input type="hidden" name="Callsign" value="<?php echo $active_callsign; ?>">

    <div class="form-grid-layout">
        
        <div class="panel-box box-full">
            <h4 class="panel-title blue"><?php echo $TC[$lang]['sect_el']; ?></h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_call']; ?></label><input type="text" name="EL_Callsign" value="<?php echo $vals_el['Callsign']; ?>"></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_pass']; ?></label><input type="password" name="EL_Password" id="el-pass" value="<?php echo $vals_el['Password']; ?>"></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_sysop']; ?></label><input type="text" name="EL_Sysop" value="<?php echo $vals_el['Sysop']; ?>"></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_desc']; ?></label><input type="text" name="EL_Desc" value="<?php echo $vals_el['Desc']; ?>"></div>
            </div>
            <div class="form-group" style="margin-top:15px;"><label><?php echo $TC[$lang]['lbl_el_proxy']; ?></label><input type="text" name="EL_ProxyHost" value="<?php echo $vals_el['Proxy']; ?>" placeholder="<?php echo $TC[$lang]['ph_el_proxy']; ?>"><small style="color:#888; font-size:10px;"><?php echo $TC[$lang]['help_proxy']; ?></small></div>
            <div style="margin-top:5px;">
                <button type="submit" name="auto_proxy" class="btn btn-green" style="margin:0; padding:8px; font-size:12px;" onclick="return confirm('<?php echo $TC[$lang]['conf_proxy']; ?>')"><?php echo $TC[$lang]['btn_proxy']; ?></button>
            </div>
        </div>

        <div class="panel-box box-full">
            <h4 class="panel-title green"><?php echo $TC[$lang]['sect_loc']; ?></h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group" style="margin:0;"><label><?php echo $TC[$lang]['lbl_name']; ?></label><input type="text" name="qth_name" value="<?php echo isset($radio['qth_name']) ? $radio['qth_name'] : ''; ?>"></div>
                <div class="form-group" style="margin:0;"><label><?php echo $TC[$lang]['lbl_city']; ?></label><input type="text" name="qth_city" value="<?php echo isset($radio['qth_city']) ? $radio['qth_city'] : ''; ?>"></div>
                <div class="form-group" style="margin:0;"><label><?php echo $TC[$lang]['lbl_loc']; ?></label><input type="text" name="qth_loc" value="<?php echo isset($radio['qth_loc']) ? $radio['qth_loc'] : ''; ?>" placeholder="<?php echo $TC[$lang]['ph_loc']; ?>"></div>
            </div>
            <small style="color:#888; font-size:10px; display:block; margin-top:5px;"><?php echo $TC[$lang]['help_loc']; ?></small>
        </div>

        <div class="panel-box box-full">
            <h4 class="panel-title"><?php echo $TC[$lang]['sect_map']; ?></h4>
            <div style="display:flex; gap:10px; justify-content:center;">
                <button type="button" id="btn-map-dark" onclick="setMapStyle('dark')" class="mod-btn"><?php echo $TC[$lang]['btn_dark']; ?></button>
                <button type="button" id="btn-map-light" onclick="setMapStyle('light')" class="mod-btn"><?php echo $TC[$lang]['btn_light']; ?></button>
                <button type="button" id="btn-map-osm" onclick="setMapStyle('osm')" class="mod-btn"><?php echo $TC[$lang]['btn_osm']; ?></button>
            </div>
        </div>

        <?php

            $locInfo = $ini['LocationInfo'] ?? [];
            $aprs_enabled = (isset($glob['LOCATION_INFO']) && $glob['LOCATION_INFO'] === 'LocationInfo') ? '1' : '0';
            $aprs_passcode = $locInfo['PASSCODE'] ?? '';
            $aprs_lat = isset($radio['aprs_lat_raw']) ? $radio['aprs_lat_raw'] : ($locInfo['LAT_POSITION'] ?? '');
            $aprs_lon = isset($radio['aprs_lon_raw']) ? $radio['aprs_lon_raw'] : ($locInfo['LON_POSITION'] ?? '');
        
            $aprs_power = $locInfo['TX_POWER'] ?? '5';
            $aprs_gain = $locInfo['ANTENNA_GAIN'] ?? '2';
            $aprs_height = str_replace('m', '', ($locInfo['ANTENNA_HEIGHT'] ?? '10'));
            $aprs_interval = $locInfo['BEACON_INTERVAL'] ?? '25';
            $aprs_icon = trim($locInfo['SYMBOL'] ?? 'E0', '"\'');
            $aprs_callsign_raw = $locInfo['CALLSIGN'] ?? '';
            $aprs_ssid = '';
            if (strpos($aprs_callsign_raw, '-') !== false) {
                $parts = explode('-', $aprs_callsign_raw);
                $aprs_ssid = end($parts);
            }
            
            $aprs_comment_raw = trim($locInfo['COMMENT'] ?? '', '"\'');
            $aprs_comment = str_replace(' - PrimeNode SVXLink System', '', $aprs_comment_raw);
        ?>
        <style>
            .toggle-switch { position: relative; display: inline-block; width: 46px; height: 24px; }
            .toggle-switch input { opacity: 0; width: 0; height: 0; }
            .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #555; transition: .3s; border-radius: 24px; border: 1px solid #444; }
            .toggle-slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: #aaa; transition: .3s; border-radius: 50%; }
            input:checked + .toggle-slider { background-color: #4CAF50; border-color: #388E3C; }
            input:checked + .toggle-slider:before { transform: translateX(22px); background-color: #fff; }
        </style>
        
        <div class="panel-box box-full">
            <h4 class="panel-title" style="color: #2196F3; border-color: #2196F3;"><?php echo $TC[$lang]['sect_aprs']; ?></h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                
                <div class="form-group" style="grid-column: 1 / -1; display: flex; flex-direction: column;">
                    <label style="margin-bottom: 8px;"><?php echo $TC[$lang]['lbl_aprs_enable']; ?></label>
                    <label class="toggle-switch">
                        <input type="hidden" name="AprsEnable" value="0">
                        <input type="checkbox" name="AprsEnable" value="1" <?php if($aprs_enabled == '1') echo 'checked'; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_pass']; ?></label>
                    <input type="password" name="AprsPasscode" value="<?php echo $aprs_passcode; ?>" placeholder="<?php echo $TC[$lang]['ph_aprs_pass']; ?>">
                </div>
                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_ssid']; ?></label>
                    <select name="AprsSsid">
                        <option value="" <?php if($aprs_ssid == '') echo 'selected'; ?>><?php echo $TC[$lang]['opt_aprs_ssid_none']; ?></option>
                        <?php for($i=1; $i<=15; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php if($aprs_ssid == (string)$i) echo 'selected'; ?>>-<?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_icon']; ?></label>
                    <select name="AprsIcon">
                        <option value="E0" <?php if($aprs_icon == 'E0') echo 'selected'; ?>><?php echo $TC[$lang]['icon_e0']; ?></option>
                        <option value="/n" <?php if($aprs_icon == '/n') echo 'selected'; ?>><?php echo $TC[$lang]['icon_n']; ?></option>
                        <option value="/#" <?php if($aprs_icon == '/#') echo 'selected'; ?>><?php echo $TC[$lang]['icon_digi']; ?></option>
                        <option value="/r" <?php if($aprs_icon == '/r') echo 'selected'; ?>><?php echo $TC[$lang]['icon_r']; ?></option>
                        <option value="/-" <?php if($aprs_icon == '/-') echo 'selected'; ?>><?php echo $TC[$lang]['icon_home']; ?></option>
                        <option value="/>" <?php if($aprs_icon == '/>') echo 'selected'; ?>><?php echo $TC[$lang]['icon_car']; ?></option>
                        <option value="1v" <?php if($aprs_icon == '1v') echo 'selected'; ?>><?php echo $TC[$lang]['icon_van']; ?></option>
                        <option value="/[" <?php if($aprs_icon == '/[') echo 'selected'; ?>><?php echo $TC[$lang]['icon_human']; ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_interval']; ?></label>
                    <input type="number" name="AprsInterval" value="<?php echo $aprs_interval; ?>" min="10" placeholder="10">
                </div>

                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_comment']; ?></label>
                    <input type="text" name="AprsComment" value="<?php echo htmlspecialchars($aprs_comment); ?>" placeholder="<?php echo $TC[$lang]['ph_aprs_comment']; ?>" maxlength="36">
                </div>
                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_height']; ?></label>
                    <input type="number" name="AprsHeight" value="<?php echo $aprs_height; ?>" placeholder="<?php echo $TC[$lang]['ph_aprs_num']; ?>12">
                </div>

                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_lat']; ?></label>
                    <input type="text" name="AprsLat" value="<?php echo $aprs_lat; ?>" placeholder="<?php echo $TC[$lang]['ph_aprs_lat']; ?>">
                </div>
                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_lon']; ?></label>
                    <input type="text" name="AprsLon" value="<?php echo $aprs_lon; ?>" placeholder="<?php echo $TC[$lang]['ph_aprs_lon']; ?>">
                </div>

                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_power']; ?></label>
                    <input type="number" name="AprsPower" value="<?php echo $aprs_power; ?>" placeholder="<?php echo $TC[$lang]['ph_aprs_num']; ?>5">
                </div>
                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_aprs_gain']; ?></label>
                    <input type="number" name="AprsGain" value="<?php echo $aprs_gain; ?>" placeholder="<?php echo $TC[$lang]['ph_aprs_num']; ?>2">
                </div>
                
            </div>
        </div>

        <div class="panel-box box-full">
            <h4 class="panel-title green"><?php echo $TC[$lang]['sect_adv']; ?></h4>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="text-align:center; margin-bottom:10px;"><?php echo $TC[$lang]['lbl_modules']; ?></label>
                <input type="hidden" name="Modules" id="input-modules" value="<?php echo $vals['Modules']; ?>">
                
                <div class="mod-grid">
                    <div class="mod-btn" id="btn-ModuleHelp" onclick="toggleModule('ModuleHelp')" style="max-width:120px;"><?php echo $TC[$lang]['btn_help']; ?></div>
                    <div class="mod-btn" id="btn-ModuleParrot" onclick="toggleModule('ModuleParrot')" style="max-width:120px;"><?php echo $TC[$lang]['btn_parrot']; ?></div>
                    <div class="mod-btn" id="btn-ModuleEchoLink" onclick="toggleModule('ModuleEchoLink')" style="max-width:120px;"><?php echo $TC[$lang]['btn_el']; ?></div>
                </div>
            </div>

            <div class="flex-settings">
                
                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_lang_audio']; ?></label>
                    <select name="AudioLang">
                        <option value="PL" <?php if($current_audio_lang == 'PL') echo 'selected'; ?>>PL (Polski)</option>
                        <option value="en_US" <?php if($current_audio_lang == 'en_US') echo 'selected'; ?>>EN (English)</option>
                    </select>
                </div>

                <div class="form-group"><label><?php echo $TC[$lang]['lbl_tg_time']; ?></label><input type="number" name="TgTimeout" value="<?php echo $vals['TgTimeout']; ?>" required min="0"></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_tmp_time']; ?></label><input type="number" name="TmpTimeout" value="<?php echo $vals['TmpTimeout']; ?>" required min="0"></div>
                
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_beep']; ?></label><select name="Beep3Tone"><option value="1" <?php if($vals['Beep3Tone']=='1') echo 'selected'; ?>><?php echo $TC[$lang]['opt_yes']; ?></option><option value="0" <?php if($vals['Beep3Tone']=='0') echo 'selected'; ?>><?php echo $TC[$lang]['opt_no']; ?></option></select></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_ann_tg']; ?></label><select name="AnnounceTG"><option value="1" <?php if($vals['AnnounceTG']=='1') echo 'selected'; ?>><?php echo $TC[$lang]['opt_yes']; ?></option><option value="0" <?php if($vals['AnnounceTG']=='0') echo 'selected'; ?>><?php echo $TC[$lang]['opt_no']; ?></option></select></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_info']; ?></label><select name="RefStatusInfo"><option value="1" <?php if($vals['RefStatusInfo']=='1') echo 'selected'; ?>><?php echo $TC[$lang]['opt_yes']; ?></option><option value="0" <?php if($vals['RefStatusInfo']=='0') echo 'selected'; ?>><?php echo $TC[$lang]['opt_no']; ?></option></select></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_roger']; ?></label><select name="RogerBeep"><option value="1" <?php if($vals['RogerBeep']=='1') echo 'selected'; ?>><?php echo $TC[$lang]['opt_yes']; ?></option><option value="0" <?php if($vals['RogerBeep']=='0') echo 'selected'; ?>><?php echo $TC[$lang]['opt_no']; ?></option></select></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_voice_id']; ?></label><select name="AnnounceCall"><option value="1" <?php if(isset($vals['AnnounceCall']) && $vals['AnnounceCall']=='1') echo 'selected'; ?>><?php echo $TC[$lang]['opt_yes']; ?></option><option value="0" <?php if(isset($vals['AnnounceCall']) && $vals['AnnounceCall']=='0') echo 'selected'; ?>><?php echo $TC[$lang]['opt_no']; ?></option></select></div>
            </div>
        </div>
    </div>
    <button type="submit" name="save_svx_full" class="btn btn-blue" style="margin-top:20px;"><?php echo $TC[$lang]['btn_save']; ?></button>
</form>

<div class="panel-box box-full" style="border: 1px solid #9C27B0; margin-top: 20px;">
    <h4 class="panel-title" style="color: #9C27B0; border-color: #9C27B0;">💾 Kopia Zapasowa i Przywracanie</h4>
    <div style="display: flex; gap: 20px; padding: 10px; align-items: stretch;">
        
        <div style="flex: 1; border-right: 1px solid #444; padding-right: 20px; display: flex; flex-direction: column;">
            <p style="font-size: 12px; color: #aaa; margin: 0 0 15px 0;">Pobierz kopię wszystkich ustawień (APRS, Sieci, Radio, DTMF) do pliku ZIP.</p>
            <form method="post" style="margin-top: auto; display: flex; flex-direction: column; justify-content: flex-end;">
                <button type="submit" name="export_backup" class="btn" style="background: #9C27B0; color: #fff; width: 100%; margin: 0; padding: 10px;">
                    📥 POBIERZ BACKUP
                </button>
            </form>
        </div>

        <div style="flex: 1; display: flex; flex-direction: column;">
            <p style="font-size: 12px; color: #aaa; margin: 0 0 15px 0;">Wybierz plik ZIP, aby przywrócić zapisaną wcześniej konfigurację.</p>
            <form method="post" enctype="multipart/form-data" style="margin-top: auto; display: flex; flex-direction: column; justify-content: flex-end;">
                <input type="file" name="backup_zip" accept=".zip" style="font-size: 11px; margin-bottom: 10px; color: #ccc; width: 100%;" required>
                <button type="submit" name="import_backup" class="btn btn-green" style="width: 100%; margin: 0; padding: 10px;" onclick="return confirm('Uwaga: To nadpisze obecne ustawienia! Kontynuować?')">
                    📤 PRZYWRÓĆ Z PLIKU
                </button>
            </form>
        </div>

    </div>
</div>

<?php
$tg_list_data = [];
$custom_dtmf_path = '/var/www/html/dtmf_custom.json';
if (file_exists($custom_dtmf_path)) {
    $tg_list_data = json_decode(file_get_contents($custom_dtmf_path), true);
}
?>
<div id="tg-modal-overlay">
    <div id="tg-modal">
        <h3 style="margin-top:0; color:#2196F3; border-bottom: 1px solid #333; padding-bottom: 10px; display:flex; justify-content:space-between; align-items:center;">
            <span><?php echo $TC[$lang]['tg_modal_title']; ?></span>
            <span id="tg-modal-mode" style="font-size:12px; background:#333; color:#ccc; padding:3px 8px; border-radius:4px;"></span>
        </h3>

        <div style="font-size:11px; color:#888; margin-bottom:5px;"><?php echo $TC[$lang]['tg_selected']; ?></div>
        <div class="tg-sel-box" id="tg-selected-container"></div>

        <div class="tg-manual-add">
            <input type="number" id="tg-manual-input" placeholder="<?php echo $TC[$lang]['tg_ph_manual']; ?>" style="flex:1; padding:8px; font-size:14px; background:#222; color:#fff; border:1px solid #444; border-radius:4px;">
            <button class="btn btn-blue" style="width:auto; margin:0; padding:0 20px;" onclick="addManualTg()"><?php echo $TC[$lang]['btn_add_tg']; ?></button>
        </div>

        <div style="flex:1; overflow-y:auto; padding-right:5px; margin-bottom:15px;" id="tg-lists-container"></div>

        <div style="display:flex; gap:10px;">
            <button class="btn btn-green" style="margin:0;" onclick="saveTgSelection()"><?php echo $TC[$lang]['btn_confirm']; ?></button>
            <button class="btn btn-red" style="margin:0;" onclick="closeTgSelector()"><?php echo $TC[$lang]['btn_cancel_modal']; ?></button>
        </div>
    </div>
</div>

<script>
    const tgDataGroups = <?php echo json_encode($tg_list_data); ?>;
</script>
