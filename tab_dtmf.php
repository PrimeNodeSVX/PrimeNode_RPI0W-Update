<?php
$DT = [
    'pl' => [
        'ref_groups' => 'Reflector / Grupy',
        'tab_sqlink' => 'SQLink',
        'tab_mine' => 'Moje',
        'btn_disconnect' => 'Rozłącz',
        'btn_status' => 'Status',
        'btn_test' => 'Testowa',
        'btn_foreign' => 'Zagraniczna',
        'empty_custom' => 'Brak własnych przycisków. Dodaj poniżej.',
        'ph_name' => 'Nazwa',
        'confirm_del' => 'Usunąć?',
        
        'btn_tg_nat' => 'Ogólnopolska',
        'btn_tg_se' => 'Sierra Echo',
        'btn_tg_ad' => 'A. Dyplomowe',
        'btn_tg_br' => 'Bridge UK',
        'btn_tg_el' => 'EchoLink',

        'sect_el' => 'EchoLink (Moduł 2)',
        'btn_activate' => '1. Aktywuj Moduł (2#)',
        'ph_node' => 'Nr Noda (np. 459342)',
        'btn_connect' => 'Połącz',
        'btn_test_echo' => 'Test Echo (9999)',
        'status_chk' => 'Sprawdzanie statusu...',
        
        'sect_parrot' => '🦜 Papuga (Test Audio)',
        'btn_parrot_on' => '▶️ Włącz Papugę (1#)',
        'btn_parrot_off' => '⏹️ Wyłącz / Stop (#)',
        'parrot_how_title' => 'Jak używać?',
        'parrot_step1' => 'Kliknij <span style="color:#FF9800; font-weight:bold;">Włącz Papugę</span>. Usłyszysz komunikat "Moduł Papuga".',
        'parrot_step2' => 'Wciśnij PTT w radiu, powiedz kilka słów ("Test, raz, dwa...") i puść PTT.',
        'parrot_step3' => 'Hotspot powinien odesłać Twój głos. Jeśli słyszysz siebie czysto – audio jest OK.',
        'parrot_step4' => 'Kliknij <span style="color:#F44336; font-weight:bold;">Wyłącz</span>, aby zakończyć test.',
        
        'keypad_title' => 'Klawiatura Numeryczna',
        'key_legend' => '<b>C</b>=Kasuj, <b>TG</b>=Ustaw Grupę (*91..#), <b>TX</b>=Wyślij Kod'
    ],
    'en' => [
        'ref_groups' => 'Reflector / Talkgroups',
        'tab_sqlink' => 'SQLink',
        'tab_mine' => 'My Buttons',
        'btn_disconnect' => 'Disconnect',
        'btn_status' => 'Status',
        'btn_test' => 'Test TG',
        'btn_foreign' => 'International',
        'empty_custom' => 'No custom buttons defined. Add below.',
        'ph_name' => 'Name',
        'confirm_del' => 'Delete?',
        
        'btn_tg_nat' => 'National',
        'btn_tg_se' => 'Sierra Echo',
        'btn_tg_ad' => 'A. Diploma',
        'btn_tg_br' => 'Bridge UK',
        'btn_tg_el' => 'EchoLink',

        'sect_el' => 'EchoLink (Module 2)',
        'btn_activate' => '1. Activate Module (2#)',
        'ph_node' => 'Node No. (e.g. 459342)',
        'btn_connect' => 'Connect',
        'btn_test_echo' => 'Echo Test (9999)',
        'status_chk' => 'Checking status...',
        
        'sect_parrot' => '🦜 Parrot (Audio Test)',
        'btn_parrot_on' => '▶️ Start Parrot (1#)',
        'btn_parrot_off' => '⏹️ Stop (#)',
        'parrot_how_title' => 'How to use?',
        'parrot_step1' => 'Click <span style="color:#FF9800; font-weight:bold;">Start Parrot</span>. You will hear "Module Parrot".',
        'parrot_step2' => 'Press PTT on radio, say a few words ("Test, one, two...") and release PTT.',
        'parrot_step3' => 'Hotspot should echo your voice. If you hear yourself clearly – audio is OK.',
        'parrot_step4' => 'Click <span style="color:#F44336; font-weight:bold;">Stop</span> to finish test.',
        
        'keypad_title' => 'Numeric Keypad',
        'key_legend' => '<b>C</b>=Clear, <b>TG</b>=Set TG (*91..#), <b>TX</b>=Send Code'
    ]
];

$custom_dtmf_file = '/var/www/html/dtmf_custom.json';
$custom_buttons = [];
if (file_exists($custom_dtmf_file)) {
    $custom_buttons = json_decode(file_get_contents($custom_dtmf_file), true);
    if (!is_array($custom_buttons)) $custom_buttons = [];
}
?>

<div class="dtmf-columns">
    <div class="panel-box">
        <h4 class="panel-title"><?php echo $DT[$lang]['ref_groups']; ?></h4>
        
        <div class="dtmf-tabs">
            <div class="dtmf-tab-btn active" id="tab-btn-SQLink" onclick="openDtmfSubTab('SQLink')"><?php echo $DT[$lang]['tab_sqlink']; ?></div>
            <div class="dtmf-tab-btn" id="tab-btn-Mine" onclick="openDtmfSubTab('Mine')"><?php echo $DT[$lang]['tab_mine']; ?></div>
        </div>

        <div id="DTMF-SQLink" class="dtmf-subtab" style="display:block;">
            <div class="macro-grid">
                <button onclick="sendInstant('*91260#')" class="macro-btn"><?php echo $DT[$lang]['btn_tg_nat']; ?><span class="dtmf-sub">TG 260</span></button>
                <button onclick="sendInstant('*9126077#')" class="macro-btn"><?php echo $DT[$lang]['btn_tg_se']; ?><span class="dtmf-sub">TG 26077</span></button>
                <button onclick="sendInstant('*91225#')" class="macro-btn"><?php echo $DT[$lang]['btn_tg_ad']; ?><span class="dtmf-sub">TG 225</span></button>
                <button onclick="sendInstant('*91235#')" class="macro-btn"><?php echo $DT[$lang]['btn_tg_br']; ?><span class="dtmf-sub">TG 235</span></button>
                <button onclick="sendInstant('*91245#')" class="macro-btn"><?php echo $DT[$lang]['btn_tg_el']; ?><span class="dtmf-sub">TG 245</span></button>
                <button onclick="sendInstant('*91999#')" class="macro-btn"><?php echo $DT[$lang]['btn_test']; ?><span class="dtmf-sub">TG 999</span></button>
                <button onclick="sendInstant('*912600#')" class="macro-btn"><?php echo $DT[$lang]['btn_foreign']; ?><span class="dtmf-sub">TG 2600</span></button>

                <button onclick="sendInstant('*#')" class="macro-btn"><?php echo $DT[$lang]['btn_status']; ?><span class="dtmf-sub">(*#)</span></button>
                <button onclick="sendInstant('910#')" class="macro-btn red"><?php echo $DT[$lang]['btn_disconnect']; ?><span class="dtmf-sub">(910#)</span></button>
            </div>
        </div>

        <div id="DTMF-Mine" class="dtmf-subtab" style="display:none;">
            <?php if (empty($custom_buttons)): ?>
                <div style="text-align:center; color:#777; padding:20px; font-size:13px;"><?php echo $DT[$lang]['empty_custom']; ?></div>
            <?php else: ?>
                <div class="macro-grid">
                    <?php foreach($custom_buttons as $idx => $btn): ?>
                        <div style="position:relative;">
                            <button onclick="sendInstant('*91<?php echo $btn['tg']; ?>#')" class="macro-btn orange" style="color:#fff;">
                                <?php echo htmlspecialchars($btn['name']); ?>
                                <span class="dtmf-sub">TG <?php echo $btn['tg']; ?></span>
                            </button>
                            <form method="post" style="position:absolute; top:-5px; right:-5px; margin:0;">
                                <input type="hidden" name="active_tab" class="active-tab-input" value="DTMF">
                                <input type="hidden" name="del_dtmf_index" value="<?php echo $idx; ?>">
                                <button type="submit" class="dtmf-del-mini" onclick="return confirm('<?php echo $DT[$lang]['confirm_del']; ?>')">x</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div style="margin-top:20px; border-top:1px solid #444; padding-top:10px;">
                <form method="post">
                    <input type="hidden" name="active_tab" class="active-tab-input" value="DTMF">
                    <div style="display:flex; gap:5px;">
                        <input type="text" name="add_dtmf_name" placeholder="<?php echo $DT[$lang]['ph_name']; ?>" class="node-input" style="flex:1; font-size:13px;" required>
                        <input type="number" name="add_dtmf_code" placeholder="TG" class="node-input" style="width:80px; font-size:13px;" required>
                        <button type="submit" class="macro-btn green" style="width:auto; min-height:40px; font-size:20px; padding:0 15px;">+</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="panel-box">
        <h4 class="panel-title blue"><?php echo $DT[$lang]['sect_el']; ?></h4>
        <div style="margin-bottom:15px; border-bottom:1px solid #444; padding-bottom:15px;">
            <button onclick="sendInstant('2#')" class="macro-btn green" style="margin-bottom:10px; height: auto;"><?php echo $DT[$lang]['btn_activate']; ?></button>
            <div class="node-input-group">
                <input type="text" id="el-node-id" class="node-input" placeholder="<?php echo $DT[$lang]['ph_node']; ?>">
                <button onclick="connectEchoLink()" class="macro-btn blue" style="width: auto; min-height: 40px;"><?php echo $DT[$lang]['btn_connect']; ?></button>
            </div>
            <div class="macro-grid">
                <button onclick="sendInstant('9999#')" class="macro-btn"><?php echo $DT[$lang]['btn_test_echo']; ?></button>
                <button onclick="sendInstant('#')" class="macro-btn red"><?php echo $DT[$lang]['btn_disconnect']; ?> (#)</button>
            </div>
        </div>
        <div id="el-live-status"><?php echo $DT[$lang]['status_chk']; ?></div>
    </div>

    <div class="panel-box" style="grid-column: 1 / -1; border-color: #FF9800;">
        <h4 class="panel-title" style="color: #FF9800; border-color: #FF9800;"><?php echo $DT[$lang]['sect_parrot']; ?></h4>
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <div class="macro-grid">
                    <button onclick="sendInstant('1#')" class="macro-btn orange"><?php echo $DT[$lang]['btn_parrot_on']; ?></button>
                    <button onclick="sendInstant('#')" class="macro-btn red"><?php echo $DT[$lang]['btn_parrot_off']; ?></button>
                </div>
            </div>
            <div style="flex: 1; font-size: 13px; color: #ccc; background: #222; padding: 10px; border-radius: 5px; border-left: 3px solid #FF9800;">
                <strong><?php echo $DT[$lang]['parrot_how_title']; ?></strong>
                <ol style="margin: 5px 0; padding-left: 20px; line-height: 1.6;">
                    <li><?php echo $DT[$lang]['parrot_step1']; ?></li>
                    <li><?php echo $DT[$lang]['parrot_step2']; ?></li>
                    <li><?php echo $DT[$lang]['parrot_step3']; ?></li>
                    <li><?php echo $DT[$lang]['parrot_step4']; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<h3 style="border-top:1px solid #444; paddingTop:20px;"><?php echo $DT[$lang]['keypad_title']; ?></h3>
<div class="dtmf-display" id="dtmf-screen">...</div>
<div class="dtmf-grid"><button onclick="typeKey('1')" class="dtmf-btn">1</button><button onclick="typeKey('2')" class="dtmf-btn">2</button><button onclick="typeKey('3')" class="dtmf-btn">3</button><button onclick="typeKey('4')" class="dtmf-btn">4</button><button onclick="typeKey('5')" class="dtmf-btn">5</button><button onclick="typeKey('6')" class="dtmf-btn">6</button><button onclick="typeKey('7')" class="dtmf-btn">7</button><button onclick="typeKey('8')" class="dtmf-btn">8</button><button onclick="typeKey('9')" class="dtmf-btn">9</button><button onclick="typeKey('*')" class="dtmf-btn">*</button><button onclick="typeKey('0')" class="dtmf-btn">0</button><button onclick="typeKey('#')" class="dtmf-btn">#</button><button onclick="clearKey()" class="dtmf-btn dtmf-clear">C</button><button onclick="submitTG()" class="dtmf-btn dtmf-tg">TG</button><button onclick="submitKey()" class="dtmf-btn dtmf-send">TX</button></div>
<p style="font-size:12px; color:#888; text-align:center;"><?php echo $DT[$lang]['key_legend']; ?></p>