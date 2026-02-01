<?php
$TR = [
    'pl' => [
        'csq' => 'Brak (CSQ)',
        'card_title' => '📝 Wizytówka Dashboardu',
        'card_desc' => 'Dane wyświetlane na stronie głównej oraz wysyłane do sieci.',
        'lbl_type' => 'Typ Radia / Interfejsu',
        'type_cm108' => 'Analogowe (CM108/GPIO)',
        'type_sa818' => 'Moduł SA818 (GURURF/ReSpeaker)',
        'lbl_desc' => 'Opis Sprzętu',
        'lbl_rx' => 'RX Freq (MHz)',
        'lbl_tx' => 'TX Freq (MHz)',
        'lbl_ctcss' => 'CTCSS',
        'lbl_serial' => 'Port Szeregowy (SA818)',
        'gpio_title' => '⚙️ Konfiguracja GPIO',
        'gpio_desc' => 'Piny sterujące PTT (nadawanie) i SQL (blokada szumu).',
        'lbl_ptt' => 'GPIO PTT',
        'lbl_sql' => 'GPIO SQL',
        'btn_save' => '💾 Zapisz Konfigurację i Restartuj',
        'info_cm108_title' => '⚠️ Tryb Analogowy (CM108)',
        'info_cm108_text' => 'W tym trybie ustawienia częstotliwości i CTCSS w panelu są <b>tylko informacyjne</b>. Musisz ustawić je fizycznie na radiu (gałką/programatorem).',
        'info_sa818_title' => '📲 Tryb Modułu SA818',
        'info_sa818_text' => 'W tym trybie częstotliwość i CTCSS zostaną <b>zaprogramowane w module</b> poprzez port szeregowy przy każdym zapisie.',
        'tip_vol' => '🔊 <b>Głośność (RX):</b> Ustaw "na słuch" używając Papugi.',
        'tip_mod' => '🎤 <b>Modulacja (TX):</b> Reguluj suwakiem w zakładce Audio.'
    ],
    'en' => [
        'csq' => 'None (CSQ)',
        'card_title' => '📝 Dashboard Card',
        'card_desc' => 'Data displayed on dashboard and sent to the network.',
        'lbl_type' => 'Radio / Interface Type',
        'type_cm108' => 'Analog (CM108/GPIO)',
        'type_sa818' => 'SA818 Module (GURURF/ReSpeaker)',
        'lbl_desc' => 'Hardware Desc',
        'lbl_rx' => 'RX Freq (MHz)',
        'lbl_tx' => 'TX Freq (MHz)',
        'lbl_ctcss' => 'CTCSS',
        'lbl_serial' => 'Serial Port (SA818)',
        'gpio_title' => '⚙️ GPIO Config',
        'gpio_desc' => 'Pins controlling PTT (TX) and SQL (Squelch).',
        'lbl_ptt' => 'GPIO PTT',
        'lbl_sql' => 'GPIO SQL',
        'btn_save' => '💾 Save Config & Restart',
        'info_cm108_title' => '⚠️ Analog Mode (CM108)',
        'info_cm108_text' => 'In this mode, Frequency and CTCSS settings are <b>informational only</b>. You must set them physically on the radio.',
        'info_sa818_title' => '📲 SA818 Module Mode',
        'info_sa818_text' => 'In this mode, Frequency and CTCSS will be <b>programmed into the module</b> via serial port on save.',
        'tip_vol' => '🔊 <b>Volume (RX):</b> Set by ear using Parrot.',
        'tip_mod' => '🎤 <b>Modulation (TX):</b> Adjust slider in Audio tab.'
    ]
];

$jsonFile = '/var/www/html/radio_config.json';
$radio_display = [
    "type" => "cm108", 
    "rx" => "432.8000", "tx" => "432.8000", "ctcss" => "0000", "desc" => "Radio",
    "serial_port" => "/dev/ttyS2",
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
                    <option value="cm108" <?php if(!isset($radio_display['type']) || $radio_display['type'] == 'cm108') echo 'selected'; ?>><?php echo $TR[$lang]['type_cm108']; ?></option>
                    <option value="sa818" <?php if(isset($radio_display['type']) && $radio_display['type'] == 'sa818') echo 'selected'; ?>><?php echo $TR[$lang]['type_sa818']; ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><?php echo $TR[$lang]['lbl_desc']; ?></label>
                <input type="text" name="radio_desc" value="<?php echo htmlspecialchars($radio_display['desc']); ?>" placeholder="np. Motorola GM360">
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
                            <option value="<?php echo $code; ?>" <?php if(isset($radio_display['ctcss']) && $radio_display['ctcss'] == $code) echo 'selected'; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group" id="serial_group" style="display:none; background:#222; padding:10px; border-radius:5px; border:1px dashed #666;">
                <label style="color:#FF9800;"><?php echo $TR[$lang]['lbl_serial']; ?></label>
                <input type="text" name="serial_port" value="<?php echo htmlspecialchars($radio_display['serial_port']); ?>" placeholder="/dev/ttyS2">
            </div>
            
            <hr style="border:0; border-top:1px solid #444; margin: 20px 0;">
            
            <h4 class="panel-title blue"><?php echo $TR[$lang]['gpio_title']; ?></h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div class="form-group">
                    <label><?php echo $TR[$lang]['lbl_ptt']; ?></label>
                    <input type="text" name="gpio_ptt" value="<?php echo htmlspecialchars($radio_display['gpio_ptt']); ?>">
                </div>
                <div class="form-group">
                    <label><?php echo $TR[$lang]['lbl_sql']; ?></label>
                    <input type="text" name="gpio_sql" value="<?php echo htmlspecialchars($radio_display['gpio_sql']); ?>">
                </div>
            </div>

            <button type="submit" name="save_radio" class="btn btn-blue" style="margin-top:15px;"><?php echo $TR[$lang]['btn_save']; ?></button>
        </form>
    </div>

    <div>
        <div id="info_cm108" class="panel-box" style="border-left: 5px solid #FF9800; background: #26201b;">
            <h4 class="panel-title" style="color: #FF9800; border: none;"><?php echo $TR[$lang]['info_cm108_title']; ?></h4>
            <div style="font-size: 13px; color: #ddd; line-height: 1.6;">
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 15px; background: rgba(0,0,0,0.2); padding: 8px; border-radius: 5px;">
                    <div style="font-size: 24px;">☝️</div>
                    <div><?php echo $TR[$lang]['info_cm108_text']; ?></div>
                </div>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 8px;"><?php echo $TR[$lang]['tip_vol']; ?></li>
                    <li><?php echo $TR[$lang]['tip_mod']; ?></li>
                </ul>
            </div>
        </div>

        <div id="info_sa818" class="panel-box" style="border-left: 5px solid #4CAF50; background: #1b261b; display:none;">
            <h4 class="panel-title" style="color: #4CAF50; border: none;"><?php echo $TR[$lang]['info_sa818_title']; ?></h4>
            <div style="font-size: 13px; color: #ddd; line-height: 1.6;">
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 15px; background: rgba(0,0,0,0.2); padding: 8px; border-radius: 5px;">
                    <div style="font-size: 24px;">📲</div>
                    <div><?php echo $TR[$lang]['info_sa818_text']; ?></div>
                </div>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 8px;">Upewnij się, że port szeregowy (np. <code>/dev/ttyS2</code>) jest poprawny.</li>
                    <li>Moduł zostanie zaprogramowany automatycznie po kliknięciu Zapisz.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleRadioType() {
        var type = document.getElementById('radio_type_select').value;
        var serialGroup = document.getElementById('serial_group');
        var infoCm = document.getElementById('info_cm108');
        var infoSa = document.getElementById('info_sa818');

        if (type === 'sa818') {
            serialGroup.style.display = 'block';
            infoCm.style.display = 'none';
            infoSa.style.display = 'block';
        } else {
            serialGroup.style.display = 'none';
            infoCm.style.display = 'block';
            infoSa.style.display = 'none';
        }
    }
    toggleRadioType();
</script>