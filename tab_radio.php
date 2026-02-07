<?php
$TR = [
    'pl' => [
        'csq' => 'Brak (CSQ)',
        'card_title' => '📝 Konfiguracja Sprzętowa (Radio/Audio)',
        'card_desc' => 'Wybierz typ interfejsu i zdefiniuj parametry.',
        'lbl_type' => 'Typ Interfejsu',
        'type_cm108' => 'Własna konstrukcja (GPIO Maliny + Karta USB)',
        'type_sa818' => 'Gotowy Moduł SHARI / SA818 (Hidraw)',
        'lbl_desc' => 'Opis Stacji',
        'lbl_rx' => 'RX Freq (MHz)',
        'lbl_tx' => 'TX Freq (MHz)',
        'lbl_ctcss' => 'CTCSS',
        
        'sect_audio' => '🔊 Ustawienia Audio (ALSA)',
        'lbl_dev' => 'Urządzenie Audio',
        'ph_dev' => 'np. alsa:plughw:0',
        'lbl_chan' => 'Kanał Audio (0/1)',
        'help_chan' => '0=Lewy/Mono, 1=Prawy',
        
        'sect_gpio' => '⚙️ Sterowanie (GPIO / HID)',
        'lbl_ptt' => 'PTT Pin',
        'lbl_sql' => 'SQL Pin',
        'help_gpio' => 'Dla odwróconej logiki dodaj wykrzyknik, np. <b>!12</b>',
        
        'sect_shari' => '🎛️ Ustawienia SHARI / SA818',
        'lbl_serial' => 'Port Szeregowy',
        'lbl_hid' => 'Urządzenie HID (PTT)',
        'ph_hid' => 'np. /dev/hidraw0',
        
        'btn_save' => '💾 Zapisz Konfigurację i Restartuj',
        
        'info_cm108_title' => 'Tryb Własny (GPIO)',
        'info_cm108_text' => 'Sterowanie PTT/SQL odbywa się przez piny GPIO Raspberry Pi. Częstotliwość ustawiasz fizycznie na radiu.',
        'info_sa818_title' => 'Tryb SHARI (SA818)',
        'info_sa818_text' => 'Sterowanie PTT przez USB (Hidraw). Częstotliwość zostanie zaprogramowana w module przy każdym zapisie.'
    ],
    'en' => [
        'csq' => 'None (CSQ)',
        'card_title' => '📝 Hardware Config (Radio/Audio)',
        'card_desc' => 'Select interface type and define parameters.',
        'lbl_type' => 'Interface Type',
        'type_cm108' => 'Custom Build (RPi GPIO + USB Card)',
        'type_sa818' => 'SHARI Module / SA818 (Hidraw)',
        'lbl_desc' => 'Station Desc',
        'lbl_rx' => 'RX Freq (MHz)',
        'lbl_tx' => 'TX Freq (MHz)',
        'lbl_ctcss' => 'CTCSS',
        
        'sect_audio' => '🔊 Audio Settings (ALSA)',
        'lbl_dev' => 'Audio Device',
        'ph_dev' => 'e.g. alsa:plughw:0',
        'lbl_chan' => 'Audio Channel (0/1)',
        'help_chan' => '0=Left/Mono, 1=Right',
        
        'sect_gpio' => '⚙️ Control (GPIO / HID)',
        'lbl_ptt' => 'PTT Pin',
        'lbl_sql' => 'SQL Pin',
        'help_gpio' => 'For inverted logic add exclamation mark, e.g. <b>!12</b>',
        
        'sect_shari' => '🎛️ SHARI / SA818 Settings',
        'lbl_serial' => 'Serial Port',
        'lbl_hid' => 'HID Device (PTT)',
        'ph_hid' => 'e.g. /dev/hidraw0',
        
        'btn_save' => '💾 Save Config & Restart',
        
        'info_cm108_title' => 'Custom Mode (GPIO)',
        'info_cm108_text' => 'PTT/SQL controlled via RPi GPIO pins. Set frequency manually on your radio.',
        'info_sa818_title' => 'SHARI Mode (SA818)',
        'info_sa818_text' => 'PTT controlled via USB (Hidraw). Frequency will be programmed into the module on save.'
    ]
];

$jsonFile = '/var/www/html/radio_config.json';
$radio_display = [
    "type" => "cm108", 
    "rx" => "432.8000", "tx" => "432.8000", "ctcss" => "0000", "desc" => "Radio",
    "audio_dev" => "alsa:plughw:0", "audio_chan" => "0",
    "serial_port" => "/dev/ttyS2", "hid_device" => "/dev/hidraw0",
    "gpio_ptt" => "12", "gpio_sql" => "16"
];

$CTCSS_TONES = [
    "0000" => $TR[$lang]['csq'], "0670" => "67.0 Hz", "0719" => "71.9 Hz", "0744" => "74.4 Hz", "0770" => "77.0 Hz",
    "0797" => "79.7 Hz", "0825" => "82.5 Hz", "0854" => "85.4 Hz", "0885" => "88.5 Hz", "0915" => "91.5 Hz",
    "0948" => "94.8 Hz", "0974" => "97.4 Hz", "1000" => "100.0 Hz", "1035" => "103.5 Hz", "1072" => "107.2 Hz",
    "1109" => "110.9 Hz", "1148" => "114.8 Hz", "1188" => "118.8 Hz", "1230" => "123.0 Hz", "1273" => "127.3 Hz",
    "1318" => "131.8 Hz", "1365" => "136.5 Hz", "1413" => "141.3 Hz", "1462" => "146.2 Hz", "1514" => "151.4 Hz",
    "1567" => "156.7 Hz", "1622" => "162.2 Hz", "1679" => "167.9 Hz", "1738" => "173.8 Hz", "1799" => "179.9 Hz",
    "1862" => "186.2 Hz", "1928" => "192.8 Hz", "2035" => "203.5 Hz", "2107" => "210.7 Hz", "2181" => "218.1 Hz",
    "2257" => "225.7 Hz", "2336" => "233.6 Hz", "2418" => "241.8 Hz", "2503" => "250.3 Hz"
];

if (file_exists($jsonFile)) {
    $loaded = json_decode(file_get_contents($jsonFile), true);
    if ($loaded) {
        $radio_display = array_merge($radio_display, $loaded);
    }
}
?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="panel-box" style="border-top: 3px solid #2196F3;">
        <h4 class="panel-title blue"><?php echo $TR[$lang]['card_title']; ?></h4>
        <form method="post">
            <input type="hidden" name="active_tab" class="active-tab-input" value="Radio">
            
            <div class="form-group">
                <label><?php echo $TR[$lang]['lbl_type']; ?></label>
                <select name="radio_type" id="radio_type_select" onchange="toggleRadioType()">
                    <option value="cm108" <?php if($radio_display['type'] == 'cm108') echo 'selected'; ?>><?php echo $TR[$lang]['type_cm108']; ?></option>
                    <option value="sa818" <?php if($radio_display['type'] == 'sa818') echo 'selected'; ?>><?php echo $TR[$lang]['type_sa818']; ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><?php echo $TR[$lang]['lbl_desc']; ?></label>
                <input type="text" name="radio_desc" value="<?php echo htmlspecialchars($radio_display['desc']); ?>" placeholder="np. Motorola / SHARI">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                <div class="form-group">
                    <label><?php echo $TR[$lang]['lbl_rx']; ?></label>
                    <input type="text" name="rx_freq" value="<?php echo htmlspecialchars($radio_display['rx']); ?>">
                </div>
                <div class="form-group">
                    <label><?php echo $TR[$lang]['lbl_tx']; ?></label>
                    <input type="text" name="tx_freq" value="<?php echo htmlspecialchars($radio_display['tx']); ?>">
                </div>
                <div class="form-group">
                    <label><?php echo $TR[$lang]['lbl_ctcss']; ?></label>
                    <select name="ctcss_val">
                        <?php foreach($CTCSS_TONES as $code => $label): ?>
                            <option value="<?php echo $code; ?>" <?php if($radio_display['ctcss'] == $code) echo 'selected'; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <hr style="border:0; border-top:1px solid #444; margin: 15px 0;">
            
            <h4 class="panel-title green" style="font-size:14px; border:none; margin-bottom:5px;"><?php echo $TR[$lang]['sect_audio']; ?></h4>
            <div style="display: flex; gap: 10px;">
                <div class="form-group" style="flex:2;">
                    <label><?php echo $TR[$lang]['lbl_dev']; ?></label>
                    <input type="text" name="audio_dev" value="<?php echo htmlspecialchars($radio_display['audio_dev']); ?>" placeholder="<?php echo $TR[$lang]['ph_dev']; ?>">
                </div>
                <div class="form-group" style="flex:1;">
                    <label><?php echo $TR[$lang]['lbl_chan']; ?></label>
                    <input type="number" name="audio_chan" value="<?php echo htmlspecialchars($radio_display['audio_chan']); ?>" min="0" max="1" placeholder="0">
                </div>
            </div>
            <small style="color:#888; display:block; margin-top:-10px; margin-bottom:15px;"><?php echo $TR[$lang]['help_chan']; ?></small>

            <hr style="border:0; border-top:1px solid #444; margin: 15px 0;">

            <div id="group_gpio">
                <h4 class="panel-title blue" style="font-size:14px; border:none; margin-bottom:5px;"><?php echo $TR[$lang]['sect_gpio']; ?></h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group">
                        <label><?php echo $TR[$lang]['lbl_ptt']; ?></label>
                        <input type="text" name="gpio_ptt" value="<?php echo htmlspecialchars($radio_display['gpio_ptt']); ?>" placeholder="12">
                    </div>
                    <div class="form-group">
                        <label><?php echo $TR[$lang]['lbl_sql']; ?></label>
                        <input type="text" name="gpio_sql" value="<?php echo htmlspecialchars($radio_display['gpio_sql']); ?>" placeholder="!16">
                    </div>
                </div>
                <small style="color:#aaa;"><?php echo $TR[$lang]['help_gpio']; ?></small>
            </div>

            <div id="group_shari" style="display:none;">
                <h4 class="panel-title blue" style="font-size:14px; border:none; margin-bottom:5px;"><?php echo $TR[$lang]['sect_shari']; ?></h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group">
                        <label><?php echo $TR[$lang]['lbl_serial']; ?></label>
                        <input type="text" name="serial_port" value="<?php echo htmlspecialchars($radio_display['serial_port']); ?>" placeholder="/dev/ttyS2">
                    </div>
                    <div class="form-group">
                        <label><?php echo $TR[$lang]['lbl_hid']; ?></label>
                        <input type="text" name="hid_device" value="<?php echo isset($radio_display['hid_device']) ? htmlspecialchars($radio_display['hid_device']) : '/dev/hidraw0'; ?>" placeholder="/dev/hidraw0">
                    </div>
                </div>
            </div>

            <button type="submit" name="save_radio" class="btn btn-blue" style="margin-top:20px;"><?php echo $TR[$lang]['btn_save']; ?></button>
        </form>
    </div>

    <div>
        <div id="info_cm108" class="panel-box" style="border-left: 5px solid #FF9800; background: #26201b;">
            <h4 class="panel-title" style="color: #FF9800; border: none;"><?php echo $TR[$lang]['info_cm108_title']; ?></h4>
            <div style="font-size: 13px; color: #ddd; line-height: 1.6;">
                <?php echo $TR[$lang]['info_cm108_text']; ?>
            </div>
        </div>

        <div id="info_sa818" class="panel-box" style="border-left: 5px solid #4CAF50; background: #1b261b; display:none;">
            <h4 class="panel-title" style="color: #4CAF50; border: none;"><?php echo $TR[$lang]['info_sa818_title']; ?></h4>
            <div style="font-size: 13px; color: #ddd; line-height: 1.6;">
                <?php echo $TR[$lang]['info_sa818_text']; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleRadioType() {
        var type = document.getElementById('radio_type_select').value;
        var groupGpio = document.getElementById('group_gpio');
        var groupShari = document.getElementById('group_shari');
        var infoCm = document.getElementById('info_cm108');
        var infoSa = document.getElementById('info_sa818');

        if (type === 'sa818') {
            groupGpio.style.display = 'none';
            groupShari.style.display = 'block';
            infoCm.style.display = 'none';
            infoSa.style.display = 'block';
        } else {
            groupGpio.style.display = 'block';
            groupShari.style.display = 'none';
            infoCm.style.display = 'block';
            infoSa.style.display = 'none';
        }
    }

    toggleRadioType();
</script>