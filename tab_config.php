<?php
    $current_host = $vals['Host'];
    $current_port = $vals['Port'];

    if (strpos($current_host, ':') !== false) {
        $parts = explode(':', $current_host);
        $current_host = $parts[0];
        
        if (empty($current_port) && isset($parts[1])) {
            $current_port = $parts[1];
        }
    }

    $TC = [
        'pl' => [
            'header' => 'Konfiguracja SvxLink',
            'sect_ref' => 'Reflector (SQLink)',
            'lbl_node_call' => 'Znak Noda',
            'lbl_pass' => 'Hasło',
            'lbl_host' => 'Host',
            'lbl_port' => 'Port',
            'lbl_tg_def' => 'Domyślna Grupa (TG)',
            'lbl_tg_mon' => 'Monitorowane Grupy (TG)',
            'ph_tg_mon' => 'np. 260, 26077',
            'help_comma' => 'Oddzielone przecinkami',
            
            'sect_el' => 'EchoLink',
            'lbl_el_call' => 'Znak EchoLink',
            'lbl_el_pass' => 'Hasło EchoLink',
            'lbl_el_sysop' => 'Nazwa Sysop (Widoczna)',
            'lbl_el_desc' => 'Opis Stacji',
            'lbl_el_proxy' => 'Proxy (IP)',
            'help_proxy' => 'Zostaw puste aby wyłączyć proxy.',
            'btn_auto_proxy' => '♻️ Auto-Proxy',
            'confirm_proxy' => 'Skrypt pobierze listę publicznych proxy, znajdzie serwer Ready i zrestartuje SvxLink. Kontynuować?',
            
            'sect_loc' => 'Lokalizacja i Operator',
            'lbl_name' => 'Imię Operatora',
            'lbl_city' => 'Miasto (QTH)',
            'lbl_locator' => 'QTH Locator',
            'help_loc' => 'Dane te zostaną wysłane do sieci i będą widoczne na mapie.',
            
            'sect_map' => 'Wygląd Mapy (Grid Mapper)',
            'lbl_map_style' => 'Wybierz Styl Mapy',
            'btn_dark' => '🌑 Ciemna (Dark)',
            'btn_light' => '☀️ Jasna (Light)',
            'btn_osm' => '🗺️ Kolorowa (OSM)',
            
            'sect_adv' => 'Zaawansowane / Audio',
            'lbl_modules' => 'Aktywne Moduły',
            'btn_help' => 'Pomoc (Help)',
            'btn_parrot' => 'Papuga (Parrot)',
            'btn_el' => 'EchoLink',
            
            'lbl_voice_lang' => 'Język Komunikatów',
            'lbl_tg_timeout' => 'TG Timeout (s)',
            'lbl_tmp_timeout' => 'Tmp Timeout (s)',
            'lbl_beep' => 'Beep 3-ton',
            'lbl_ann_tg' => 'Zapowiedź TG',
            'lbl_info' => 'Info Link',
            'lbl_roger' => 'Roger Beep',
            'lbl_voice_id' => 'Recytowanie Znaku',
            'opt_yes' => 'TAK',
            'opt_no' => 'NIE',
            
            'btn_save' => 'Zapisz Wszystko i Restartuj'
        ],
        'en' => [
            'header' => 'SvxLink Configuration',
            'sect_ref' => 'Reflector (SQLink)',
            'lbl_node_call' => 'Node Callsign',
            'lbl_pass' => 'Password',
            'lbl_host' => 'Host',
            'lbl_port' => 'Port',
            'lbl_tg_def' => 'Default TG',
            'lbl_tg_mon' => 'Monitored TGs',
            'ph_tg_mon' => 'e.g. 260, 26077',
            'help_comma' => 'Comma separated',
            
            'sect_el' => 'EchoLink',
            'lbl_el_call' => 'EchoLink Callsign',
            'lbl_el_pass' => 'EchoLink Password',
            'lbl_el_sysop' => 'Sysop Name (Visible)',
            'lbl_el_desc' => 'Station Description',
            'lbl_el_proxy' => 'Proxy (IP)',
            'help_proxy' => 'Leave empty to disable proxy.',
            'btn_auto_proxy' => '♻️ Auto-Proxy',
            'confirm_proxy' => 'Script will download public proxy list, find a Ready server and restart SvxLink. Continue?',
            
            'sect_loc' => 'Location & Operator',
            'lbl_name' => 'Operator Name',
            'lbl_city' => 'City (QTH)',
            'lbl_locator' => 'QTH Locator',
            'help_loc' => 'This data will be sent to the network and visible on the map.',
            
            'sect_map' => 'Map Style (Grid Mapper)',
            'lbl_map_style' => 'Choose Map Style',
            'btn_dark' => '🌑 Dark',
            'btn_light' => '☀️ Light',
            'btn_osm' => '🗺️ Colorful (OSM)',
            
            'sect_adv' => 'Advanced / Audio',
            'lbl_modules' => 'Active Modules',
            'btn_help' => 'Help',
            'btn_parrot' => 'Parrot',
            'btn_el' => 'EchoLink',
            
            'lbl_voice_lang' => 'Voice Language',
            'lbl_tg_timeout' => 'TG Timeout (s)',
            'lbl_tmp_timeout' => 'Tmp Timeout (s)',
            'lbl_beep' => '3-Tone Beep',
            'lbl_ann_tg' => 'Announce TG',
            'lbl_info' => 'Link Info',
            'lbl_roger' => 'Roger Beep',
            'lbl_voice_id' => 'Voice ID (Callsign)',
            'opt_yes' => 'YES',
            'opt_no' => 'NO',
            
            'btn_save' => 'Save All & Restart'
        ]
    ];
?>

<h3><?php echo $TC[$lang]['header']; ?></h3>
<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="SvxConfig">
    
    <div class="form-grid-layout">
        <div class="panel-box">
            <h4 class="panel-title"><?php echo $TC[$lang]['sect_ref']; ?></h4>
            
            <div class="form-group">
                <label><?php echo $TC[$lang]['lbl_node_call']; ?></label>
                <input type="text" name="Callsign" value="<?php echo $vals['Callsign']; ?>" oninput="this.value = this.value.toUpperCase()" style="text-transform:uppercase;">
            </div>
            
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_pass']; ?></label><input type="password" name="Password" value="<?php echo $vals['Password']; ?>"></div>
            
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_host']; ?></label><input type="text" name="Host" value="<?php echo $current_host; ?>"></div>
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_port']; ?></label><input type="number" name="Port" value="<?php echo $current_port; ?>" placeholder="5300"></div>
            
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_tg_def']; ?></label><input type="text" name="DefaultTG" value="<?php echo $vals['DefaultTG']; ?>"></div>
            
            <div class="form-group">
                <label><?php echo $TC[$lang]['lbl_tg_mon']; ?></label>
                <input type="text" name="MonitorTGs" value="<?php echo $vals['MonitorTGs']; ?>" placeholder="<?php echo $TC[$lang]['ph_tg_mon']; ?>">
                <small style="color:#888; font-size:10px;"><?php echo $TC[$lang]['help_comma']; ?></small>
            </div>
        </div>
        
        <div class="panel-box">
            <h4 class="panel-title blue"><?php echo $TC[$lang]['sect_el']; ?></h4>
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_call']; ?></label><input type="text" name="EL_Callsign" value="<?php echo $vals_el['Callsign']; ?>"></div>
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_pass']; ?></label><input type="password" name="EL_Password" id="el-pass" value="<?php echo $vals_el['Password']; ?>"></div>
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_sysop']; ?></label><input type="text" name="EL_Sysop" value="<?php echo $vals_el['Sysop']; ?>"></div>
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_desc']; ?></label><input type="text" name="EL_Desc" value="<?php echo $vals_el['Desc']; ?>"></div>
            <div class="form-group"><label><?php echo $TC[$lang]['lbl_el_proxy']; ?></label><input type="text" name="EL_ProxyHost" value="<?php echo $vals_el['Proxy']; ?>" placeholder="np. 44.31.61.106"><small style="color:#888; font-size:10px;"><?php echo $TC[$lang]['help_proxy']; ?></small></div>
            <div style="margin-top:5px; display:flex; gap:10px; align-items:center;">
                <button type="submit" name="auto_proxy" class="btn btn-green" style="margin:0; padding:8px; font-size:12px;" onclick="return confirm('<?php echo $TC[$lang]['confirm_proxy']; ?>')"><?php echo $TC[$lang]['btn_auto_proxy']; ?></button>
            </div>
        </div>

        <div class="panel-box box-full">
            <h4 class="panel-title green"><?php echo $TC[$lang]['sect_loc']; ?></h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group" style="margin:0;"><label><?php echo $TC[$lang]['lbl_name']; ?></label><input type="text" name="qth_name" value="<?php echo isset($radio['qth_name']) ? $radio['qth_name'] : ''; ?>"></div>
                <div class="form-group" style="margin:0;"><label><?php echo $TC[$lang]['lbl_city']; ?></label><input type="text" name="qth_city" value="<?php echo isset($radio['qth_city']) ? $radio['qth_city'] : ''; ?>"></div>
                <div class="form-group" style="margin:0;"><label><?php echo $TC[$lang]['lbl_locator']; ?></label><input type="text" name="qth_loc" value="<?php echo isset($radio['qth_loc']) ? $radio['qth_loc'] : ''; ?>" placeholder="np. JO91SV"></div>
            </div>
            <small style="color:#888; font-size:10px; display:block; margin-top:5px;"><?php echo $TC[$lang]['help_loc']; ?></small>
        </div>

        <div class="panel-box box-full">
            <h4 class="panel-title green"><?php echo $TC[$lang]['sect_map']; ?></h4>
            <div class="form-group" style="margin-bottom: 5px;">
                <label style="text-align:center; margin-bottom:10px;"><?php echo $TC[$lang]['lbl_map_style']; ?></label>
                <div class="mod-grid">
                    <div class="mod-btn" id="btn-map-dark" onclick="setMapStyle('dark')"><?php echo $TC[$lang]['btn_dark']; ?></div>
                    <div class="mod-btn" id="btn-map-light" onclick="setMapStyle('light')"><?php echo $TC[$lang]['btn_light']; ?></div>
                    <div class="mod-btn" id="btn-map-osm" onclick="setMapStyle('osm')"><?php echo $TC[$lang]['btn_osm']; ?></div>
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

            <div class="advanced-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 10px;">
                <div class="form-group">
                    <label><?php echo $TC[$lang]['lbl_voice_lang']; ?></label>
                    <select name="DEFAULT_LANG">
                        <?php 
                            $current_lang_val = isset($ref['DEFAULT_LANG']) ? $ref['DEFAULT_LANG'] : (isset($simp['DEFAULT_LANG']) ? $simp['DEFAULT_LANG'] : 'PL');
                        ?>
                        <option value="PL" <?php if($current_lang_val == 'PL') echo 'selected'; ?>>PL (Polski)</option>
                        <option value="en_US" <?php if($current_lang_val == 'en_US') echo 'selected'; ?>>EN (English)</option>
                    </select>
                </div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_tg_timeout']; ?></label><input type="number" name="TgTimeout" value="<?php echo $vals['TgTimeout']; ?>" required min="0"></div>
                <div class="form-group"><label><?php echo $TC[$lang]['lbl_tmp_timeout']; ?></label><input type="number" name="TmpTimeout" value="<?php echo $vals['TmpTimeout']; ?>" required min="0"></div>
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