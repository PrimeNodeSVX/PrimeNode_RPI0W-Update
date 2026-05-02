<?php
$TR = [
    'pl' => [
        'csq' => 'Brak (CSQ)',
        'card_title' => '📝 Wizytówka Dashboardu',
        'card_desc_gpio' => 'Dane wyświetlane na stronie głównej. Nie wpływają na fizyczne strojenie zewnętrznego radia.',
        'card_desc_shari' => 'Wprowadzone dane programują moduł SA818 oraz wyświetlają się na stronie głównej.',
        'lbl_radio_type' => 'Typ Radia (Wymusza tryb pracy)',
        'radio_type_gpio' => 'Zewnętrzne (Sterowanie GPIO)',
        'radio_type_shari' => 'Moduł SHARI (SA818 + CM108)',
        'lbl_desc' => 'Opis Sprzętu',
        'lbl_rx' => 'RX Freq (MHz)',
        'lbl_tx' => 'TX Freq (MHz)',
        'lbl_ctcss' => 'CTCSS (Hz)',
        'lbl_sql_level' => 'Poziom Squelch (SA818)',
        'sql_lvl_0' => 'Poziom 0 (Zawsze otwarty)',
        'sql_lvl_n' => 'Poziom ',
        'gpio_title' => '⚙️ Konfiguracja GPIO (Hardware)',
        'gpio_desc' => 'Zdefiniuj piny Raspberry Pi sterujące radiem.',
        'lbl_ptt' => 'GPIO PTT (Nadawanie)',
        'lbl_sql' => 'GPIO SQL (Blokada)',
        'def' => 'Domyślnie:',
        'btn_save' => '💾 Zapisz Konfigurację i Restartuj',
        'warn_title_gpio' => '⚠️ Ustawienia Radia Analogowego',
        'warn_info_gpio' => 'Ten panel steruje tylko logiką SvxLink. Częstotliwość i CTCSS musisz ustawić <b>fizycznie na radiu</b>.',
        'tip_vol' => '🔊 <b>Głośność Radia (RX):</b> Wysteruj suwakiem "Mic Volume" w zakładce Audio, tak aby uniknąć przesterowania i zniekształceń dźwięku.',
        'tip_mod' => '🎤 <b>Poziom Modulacji (TX):</b> Reguluj suwakiem "Speaker Volume" w zakładce Audio.',
        'tip_funcs' => '🚫 <b>Funkcje Radia:</b> Wyłącz <i>Battery Save</i>, <i>Roger Beep</i> i <i>VOX</i> w menu radia.',
        'warn_title_shari' => '📡 Moduł SHARI (SA818 / CM108)',
        'warn_info_shari' => 'W trybie SHARI system automatycznie zaprogramuje chip SA818 wartościami wpisanymi po lewej stronie.',
        'shari_t1' => '✅ <b>Pełna kontrola:</b> Po kliknięciu "Zapisz", system połączy się z modułem i ustawi wybraną częstotliwość, kod CTCSS oraz blokadę SQL.',
        'shari_t2' => '🎛️ <b>Poziomy RX i TX:</b> Głośność odbioru oraz modulację nadawania reguluje się <b>wyłącznie</b> w zakładce Audio za pomocą suwaków Mic oraz Speaker.',
        'shari_t4' => '📻 <b>SQL (Squelch):</b> Blokada szumów realizowana jest sprzętowo przez chip SA818. Wybierz odpowiedni poziom (1-9) z menu konfiguracyjnego.'
    ],
    'en' => [
        'csq' => 'None (CSQ)',
        'card_title' => '📝 Dashboard Card',
        'card_desc_gpio' => 'Data displayed on the main page. Does not affect the physical tuning of the external radio.',
        'card_desc_shari' => 'The entered data programs the SA818 module and is displayed on the main page.',
        'lbl_radio_type' => 'Radio Type (Forces operation mode)',
        'radio_type_gpio' => 'External (GPIO Control)',
        'radio_type_shari' => 'SHARI Module (SA818 + CM108)',
        'lbl_desc' => 'Hardware Desc',
        'lbl_rx' => 'RX Freq (MHz)',
        'lbl_tx' => 'TX Freq (MHz)',
        'lbl_ctcss' => 'CTCSS (Hz)',
        'lbl_sql_level' => 'Squelch Level (SA818)',
        'sql_lvl_0' => 'Level 0 (Always open)',
        'sql_lvl_n' => 'Level ',
        'gpio_title' => '⚙️ GPIO Config (Hardware)',
        'gpio_desc' => 'Define Raspberry Pi pins controlling the analog radio.',
        'lbl_ptt' => 'GPIO PTT (Transmit)',
        'lbl_sql' => 'GPIO SQL (Squelch)',
        'def' => 'Default:',
        'btn_save' => '💾 Save Config & Restart',
        'warn_title_gpio' => '⚠️ Analog Radio Settings',
        'warn_info_gpio' => 'This panel controls only SvxLink logic. Frequency and CTCSS must be set <b>physically on the radio</b>.',
        'tip_vol' => '🔊 <b>Radio Volume (RX):</b> Adjust with the "Mic Volume" slider in the Audio tab to prevent clipping and distortion.',
        'tip_mod' => '🎤 <b>Modulation Level (TX):</b> Adjust with "Speaker Volume" slider in Audio tab.',
        'tip_funcs' => '🚫 <b>Radio Functions:</b> Disable <i>Battery Save</i>, <i>Roger Beep</i>, and <i>VOX</i> in radio menu.',
        'warn_title_shari' => '📡 SHARI Module (SA818 / CM108)',
        'warn_info_shari' => 'In SHARI mode, the system will automatically program the SA818 chip with the values from the left.',
        'shari_t1' => '✅ <b>Full control:</b> After clicking "Save", the system will set the chosen frequency, CTCSS, and SQL in the module.',
        'shari_t2' => '🎛️ <b>RX and TX Levels:</b> Adjust receive and transmit audio levels <b>only</b> in the Audio tab using the Mic and Speaker sliders.',
        'shari_t4' => '📻 <b>SQL (Squelch):</b> Handled by the hardware SA818 chip. Select the desired level (1-9) in the configuration panel.'
    ]
];

$jsonFile = '/var/www/html/radio_config.json';
$radio_display = [
    "radio_type" => "gpio", "rx" => "432.8000", "tx" => "432.8000", "ctcss" => "0000", "desc" => "Brak opisu",
    "gpio_ptt" => "12", "gpio_sql" => "16", "shari_sql" => "4"
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
        <div id="dynamic-card-desc" style="font-size: 12px; color: #aaa; margin-bottom: 15px; font-style: italic;">

        </div>
        <form method="post">
            <input type="hidden" name="active_tab" class="active-tab-input" value="Radio">
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: bold;"><?php echo $TR[$lang]['lbl_radio_type']; ?></label>
                <select name="radio_type" id="radio-type-selector" onchange="updateRadioHelp()">
                    <option value="gpio" <?php if(isset($radio_display['radio_type']) && $radio_display['radio_type'] == 'gpio') echo 'selected'; ?>><?php echo $TR[$lang]['radio_type_gpio']; ?></option>
                    <option value="shari" <?php if(isset($radio_display['radio_type']) && $radio_display['radio_type'] == 'shari') echo 'selected'; ?>><?php echo $TR[$lang]['radio_type_shari']; ?></option>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo $TR[$lang]['lbl_desc']; ?></label>
                <input type="text" name="radio_desc" value="<?php echo htmlspecialchars($radio_display['desc']); ?>" placeholder="np. Motorola GM360">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
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
                

                <div class="form-group" id="shari-sql-inline" style="display: none;">
                    <label><?php echo $TR[$lang]['lbl_sql_level']; ?></label>
                    <select name="shari_sql">
                        <option value="0" <?php if(isset($radio_display['shari_sql']) && $radio_display['shari_sql'] == '0') echo 'selected'; ?>><?php echo $TR[$lang]['sql_lvl_0']; ?></option>
                        <?php for($i=1; $i<=9; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php if(isset($radio_display['shari_sql']) && $radio_display['shari_sql'] == $i) echo 'selected'; ?>><?php echo $TR[$lang]['sql_lvl_n']; ?><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div id="gpio-settings-block">
                <hr style="border:0; border-top:1px solid #444; margin: 20px 0;">
                <h4 class="panel-title blue"><?php echo $TR[$lang]['gpio_title']; ?></h4>
                <div style="font-size: 12px; color: #aaa; margin-bottom: 15px;">
                    <?php echo $TR[$lang]['gpio_desc']; ?>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group">
                        <label><?php echo $TR[$lang]['lbl_ptt']; ?></label>
                        <input type="text" name="gpio_ptt" value="<?php echo htmlspecialchars($radio_display['gpio_ptt']); ?>" placeholder="np. !12">
                        <small style="color:#888; font-size:9px;"><?php echo $TR[$lang]['def']; ?> 12</small>
                    </div>
                    <div class="form-group">
                        <label><?php echo $TR[$lang]['lbl_sql']; ?></label>
                        <input type="text" name="gpio_sql" value="<?php echo htmlspecialchars($radio_display['gpio_sql']); ?>" placeholder="np. !16">
                        <small style="color:#888; font-size:9px;"><?php echo $TR[$lang]['def']; ?> 16</small>
                    </div>
                </div>
            </div>

            <button type="submit" name="save_radio" class="btn btn-blue" style="margin-top:15px;"><?php echo $TR[$lang]['btn_save']; ?></button>
        </form>
    </div>

    <div>

        <div id="help-card-gpio" class="panel-box" style="border-left: 5px solid #FF9800; background: #26201b; display: none;">
            <h4 class="panel-title" style="color: #FF9800; border: none;"><?php echo $TR[$lang]['warn_title_gpio']; ?></h4>
            <div style="font-size: 13px; color: #ddd; line-height: 1.6;">
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 15px; background: rgba(0,0,0,0.2); padding: 8px; border-radius: 5px;">
                    <div style="font-size: 24px;">☝️</div>
                    <div>
                        <b style="color: #FF9800;">INFO:</b> <?php echo $TR[$lang]['warn_info_gpio']; ?>
                    </div>
                </div>
                <ul style="list-style: none; padding: 0; margin-top: 10px;">
                    <li style="margin-bottom: 8px;"><?php echo $TR[$lang]['tip_vol']; ?></li>
                    <li style="margin-bottom: 8px;"><?php echo $TR[$lang]['tip_mod']; ?></li>
                    <li style="margin-bottom: 8px;"><?php echo $TR[$lang]['tip_funcs']; ?></li>
                </ul>
            </div>
        </div>

        <div id="help-card-shari" class="panel-box" style="border-left: 5px solid #4CAF50; background: #1b261e; display: none;">
            <h4 class="panel-title" style="color: #4CAF50; border: none;"><?php echo $TR[$lang]['warn_title_shari']; ?></h4>
            <div style="font-size: 13px; color: #ddd; line-height: 1.6;">
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 15px; background: rgba(0,0,0,0.2); padding: 8px; border-radius: 5px;">
                    <div style="font-size: 24px;">🚀</div>
                    <div>
                        <b style="color: #4CAF50;">INFO:</b> <?php echo $TR[$lang]['warn_info_shari']; ?>
                    </div>
                </div>
                <ul style="list-style: none; padding: 0; margin-top: 10px;">
                    <li style="margin-bottom: 8px; border-bottom: 1px solid #333; padding-bottom: 5px;"><?php echo $TR[$lang]['shari_t1']; ?></li>
                    <li style="margin-bottom: 8px; border-bottom: 1px solid #333; padding-bottom: 5px;"><?php echo $TR[$lang]['shari_t4']; ?></li>
                    <li style="margin-bottom: 8px;"><?php echo $TR[$lang]['shari_t2']; ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>

var cardDescGpio = "<?php echo addslashes($TR[$lang]['card_desc_gpio']); ?>";
var cardDescShari = "<?php echo addslashes($TR[$lang]['card_desc_shari']); ?>";

function updateRadioHelp() {
    var selector = document.getElementById('radio-type-selector');
    var val = selector.value;
    
    var cardGpio = document.getElementById('help-card-gpio');
    var cardShari = document.getElementById('help-card-shari');
    var gpioBlock = document.getElementById('gpio-settings-block');
    var shariSqlInline = document.getElementById('shari-sql-inline');
    var dynamicDesc = document.getElementById('dynamic-card-desc');
    
    if (val === 'shari') {
        cardGpio.style.display = 'none';
        cardShari.style.display = 'block';
        gpioBlock.style.display = 'none';
        shariSqlInline.style.display = 'block';
        dynamicDesc.innerHTML = cardDescShari;
    } else {
        cardShari.style.display = 'none';
        cardGpio.style.display = 'block';
        gpioBlock.style.display = 'block';
        shariSqlInline.style.display = 'none';
        dynamicDesc.innerHTML = cardDescGpio;
    }
}

document.addEventListener("DOMContentLoaded", function() {
    updateRadioHelp();
});
</script>
