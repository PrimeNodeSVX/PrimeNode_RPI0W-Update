<?php
$TR = [
    'pl' => [
        'csq' => 'Brak (CSQ)',
        'card_title' => '📝 Wizytówka Dashboardu',
        'card_desc' => 'Dane wyświetlane na stronie głównej. Nie wpływają na fizyczne strojenie radia (wymagane ręczne ustawienie gałki).',
        'lbl_desc' => 'Opis Sprzętu',
        'lbl_rx' => 'RX Freq (MHz)',
        'lbl_tx' => 'TX Freq (MHz)',
        'lbl_ctcss' => 'CTCSS (Info)',
        'gpio_title' => '⚙️ Konfiguracja GPIO (Hardware)',
        'gpio_desc' => 'Zdefiniuj piny Raspberry Pi sterujące radiem. Zmiana wymaga restartu usługi.',
        'lbl_ptt' => 'GPIO PTT (Nadawanie)',
        'lbl_sql' => 'GPIO SQL (Blokada)',
        'def' => 'Domyślnie:',
        'btn_save' => '💾 Zapisz Konfigurację i Restartuj',
        'warn_title' => '⚠️ Ustawienia Radia Analogowego',
        'warn_info' => 'Ten panel steruje tylko logiką SvxLink. Częstotliwość i CTCSS musisz ustawić <b>fizycznie na radiu</b>.',
        'tip_vol' => '🔊 <b>Głośność Radia (RX):</b> Ustaw tak, aby w zakładce Audio wskaźnik był na zielono, ale nie przesterowany.',
        'tip_mod' => '🎤 <b>Poziom Modulacji (TX):</b> Reguluj suwakiem "Speaker Volume" w zakładce Audio.',
        'tip_funcs' => '🚫 <b>Funkcje Radia:</b> Wyłącz <i>Battery Save</i>, <i>Roger Beep</i> i <i>VOX</i> w menu radia.',
        'schematic_title' => 'ℹ️ Schemat połączeń (GPIO):',
        'sch_ptt' => 'Sterowanie 2N7000 Mosfet (Low/High)',
        'sch_sql' => 'Sygnał COS z radia (3.3V Logic)'
    ],
    'en' => [
        'csq' => 'None (CSQ)',
        'card_title' => '📝 Dashboard Card',
        'card_desc' => 'Data displayed on the main page. Does not affect physical radio tuning (manual knob setting required).',
        'lbl_desc' => 'Hardware Desc',
        'lbl_rx' => 'RX Freq (MHz)',
        'lbl_tx' => 'TX Freq (MHz)',
        'lbl_ctcss' => 'CTCSS (Info)',
        'gpio_title' => '⚙️ GPIO Config (Hardware)',
        'gpio_desc' => 'Define Raspberry Pi pins controlling the radio. Change requires service restart.',
        'lbl_ptt' => 'GPIO PTT (Transmit)',
        'lbl_sql' => 'GPIO SQL (Squelch)',
        'def' => 'Default:',
        'btn_save' => '💾 Save Config & Restart',
        'warn_title' => '⚠️ Analog Radio Settings',
        'warn_info' => 'This panel controls only SvxLink logic. Frequency and CTCSS must be set <b>physically on the radio</b>.',
        'tip_vol' => '🔊 <b>Radio Volume (RX):</b> Set so that the meter in Audio tab is green, but not clipping.',
        'tip_mod' => '🎤 <b>Modulation Level (TX):</b> Adjust with "Speaker Volume" slider in Audio tab.',
        'tip_funcs' => '🚫 <b>Radio Functions:</b> Disable <i>Battery Save</i>, <i>Roger Beep</i>, and <i>VOX</i> in radio menu.',
        'schematic_title' => 'ℹ️ Connection Schema (GPIO):',
        'sch_ptt' => 'Control 2N7000 Mosfet (Low/High)',
        'sch_sql' => 'COS Signal from radio (3.3V Logic)'
    ]
];

$jsonFile = '/var/www/html/radio_config.json';
$radio_display = [
    "rx" => "432.8000", "tx" => "432.8000", "ctcss" => "0000", "desc" => "Brak opisu",
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
        <div style="font-size: 12px; color: #aaa; margin-bottom: 15px; font-style: italic;">
            <?php echo $TR[$lang]['card_desc']; ?>
        </div>
        <form method="post">
            <input type="hidden" name="active_tab" class="active-tab-input" value="Radio">
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
            
            <hr style="border:0; border-top:1px solid #444; margin: 20px 0;">
            
            <h4 class="panel-title blue"><?php echo $TR[$lang]['gpio_title']; ?></h4>
            <div style="font-size: 12px; color: #aaa; margin-bottom: 15px;">
                <?php echo $TR[$lang]['gpio_desc']; ?>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div class="form-group">
                    <label><?php echo $TR[$lang]['lbl_ptt']; ?></label>
                    <input type="number" name="gpio_ptt" value="<?php echo htmlspecialchars($radio_display['gpio_ptt']); ?>">
                    <small style="color:#888; font-size:9px;"><?php echo $TR[$lang]['def']; ?> 12</small>
                </div>
                <div class="form-group">
                    <label><?php echo $TR[$lang]['lbl_sql']; ?></label>
                    <input type="number" name="gpio_sql" value="<?php echo htmlspecialchars($radio_display['gpio_sql']); ?>">
                    <small style="color:#888; font-size:9px;"><?php echo $TR[$lang]['def']; ?> 16</small>
                </div>
            </div>

            <button type="submit" name="save_radio" class="btn btn-blue" style="margin-top:15px;"><?php echo $TR[$lang]['btn_save']; ?></button>
        </form>
    </div>

    <div>
        <div class="panel-box" style="border-left: 5px solid #FF9800; background: #26201b;">
            <h4 class="panel-title" style="color: #FF9800; border: none;"><?php echo $TR[$lang]['warn_title']; ?></h4>
            <div style="font-size: 13px; color: #ddd; line-height: 1.6;">
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 15px; background: rgba(0,0,0,0.2); padding: 8px; border-radius: 5px;">
                    <div style="font-size: 24px;">☝️</div>
                    <div>
                        <b style="color: #FF9800;">INFO:</b> <?php echo $TR[$lang]['warn_info']; ?>
                    </div>
                </div>
                <ul style="list-style: none; padding: 0; margin-top: 10px;">
                    <li style="margin-bottom: 8px;">
                        <?php echo $TR[$lang]['tip_vol']; ?>
                    </li>
                    <li style="margin-bottom: 8px;">
                        <?php echo $TR[$lang]['tip_mod']; ?>
                    </li>
                    <li style="margin-bottom: 8px;">
                        <?php echo $TR[$lang]['tip_funcs']; ?>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="panel-box" style="margin-top: 20px; text-align: center; color: #888; font-size: 11px; padding: 10px;">
            <strong><?php echo $TR[$lang]['schematic_title']; ?></strong><br>
            <span style="color: #E91E63;">● PTT (TX)</span>: GPIO <?php echo htmlspecialchars($radio_display['gpio_ptt']); ?> ➜ <?php echo $TR[$lang]['sch_ptt']; ?><br>
            <span style="color: #2196F3;">● SQL (RX)</span>: GPIO <?php echo htmlspecialchars($radio_display['gpio_sql']); ?> ➜ <?php echo $TR[$lang]['sch_sql']; ?><br>
        </div>
    </div>
</div>
