<?php
$TDTMF = [
    'pl' => [
        'h_groups' => 'Reflector / Grupy',
        'btn_status' => 'Status',
        'btn_disc' => 'Rozłącz',
        'del_ask' => 'Usunąć?',
        'ph_name' => 'Nazwa',
        'h_el' => 'EchoLink (Moduł 2)',
        'btn_act' => '1. Aktywuj Moduł (2#)',
        'ph_node' => 'Nr Noda (np. 459342)',
        'btn_conn' => 'Połącz',
        'st_check' => 'Sprawdzanie statusu...',
        'h_par' => '🦜 Papuga (Test Audio)',
        'btn_par_on' => '▶️ Włącz Papugę (1#)',
        'btn_par_off' => '⏹️ Wyłącz / Stop (#)',
        'how' => 'Jak używać?',
        's1' => 'Kliknij <span style="color:#FF9800; font-weight:bold;">Włącz Papugę</span>. Usłyszysz komunikat "Moduł Papuga".',
        's2' => 'Wciśnij PTT w radiu, powiedz kilka słów ("Test, raz, dwa...") i puść PTT.',
        's3' => 'Hotspot powinien odesłać Twój głos. Jeśli słyszysz siebie czysto – audio jest OK.',
        's4' => 'Kliknij <span style="color:#F44336; font-weight:bold;">Wyłącz</span>, aby zakończyć test.',
        'h_key' => 'Klawiatura Numeryczna',
        'leg_c' => '<b>C</b>=Kasuj',
        'leg_tg' => '<b>TG</b>=Ustaw Grupę (*91..#)',
        'leg_tx' => '<b>TX</b>=Wyślij Kod',
        'add_tab' => 'Nowa Zakładka',
        'tab_name_ph' => 'Nazwa...',
        'add' => 'DODAJ',
        'empty_tab' => 'Pusta zakładka. Dodaj przyciski poniżej.'
    ],
    'en' => [
        'h_groups' => 'Reflector / Groups',
        'btn_status' => 'Status',
        'btn_disc' => 'Disconnect',
        'del_ask' => 'Delete?',
        'ph_name' => 'Name',
        'h_el' => 'EchoLink (Module 2)',
        'btn_act' => '1. Activate Module (2#)',
        'ph_node' => 'Node No (e.g. 459342)',
        'btn_conn' => 'Connect',
        'st_check' => 'Checking status...',
        'h_par' => '🦜 Parrot (Audio Test)',
        'btn_par_on' => '▶️ Enable Parrot (1#)',
        'btn_par_off' => '⏹️ Disable / Stop (#)',
        'how' => 'How to use?',
        's1' => 'Click <span style="color:#FF9800; font-weight:bold;">Enable Parrot</span>. You will hear "Module Parrot".',
        's2' => 'Press PTT on radio, say a few words ("Test, one, two...") and release PTT.',
        's3' => 'Hotspot should echo your voice. If you hear yourself clearly – audio is OK.',
        's4' => 'Click <span style="color:#F44336; font-weight:bold;">Disable</span> to end test.',
        'h_key' => 'Numeric Keypad',
        'leg_c' => '<b>C</b>=Clear',
        'leg_tg' => '<b>TG</b>=Set Group (*91..#)',
        'leg_tx' => '<b>TX</b>=Send Code',
        'add_tab' => 'New Tab',
        'tab_name_ph' => 'Name...',
        'add' => 'ADD',
        'empty_tab' => 'Empty tab. Add buttons below.'
    ]
];

$custom_dtmf_file = '/var/www/html/dtmf_custom.json';
$tabs_data = [];

if (file_exists($custom_dtmf_file)) {
    $loaded_data = json_decode(file_get_contents($custom_dtmf_file), true);
    if (isset($loaded_data[0]) && isset($loaded_data[0]['tg'])) {
        $tabs_data = [['name' => 'Moje (Import)', 'buttons' => $loaded_data]];
        file_put_contents($custom_dtmf_file, json_encode($tabs_data));
    } elseif (is_array($loaded_data)) {
        $tabs_data = $loaded_data;
    }
} else {
    $tabs_data = [[
            'name' => 'PrimeNode',
            'buttons' => [
                ['name' => 'Ogólnopolska', 'tg' => '260'],
                ['name' => 'Testowa', 'tg' => '999'],
                ['name' => 'Status', 'tg' => '', 'code' => '*#'],
                ['name' => 'Rozłącz', 'tg' => '', 'code' => '910#', 'color' => 'red']
            ]
    ]];
    file_put_contents($custom_dtmf_file, json_encode($tabs_data));
}

if (isset($_POST['new_tab_name'])) {
    $name = trim($_POST['new_tab_name']);
    if (!empty($name)) {
        $tabs_data[] = ['name' => $name, 'buttons' => []];
        file_put_contents($custom_dtmf_file, json_encode($tabs_data));
        echo "<script>localStorage.setItem('activeTab', 'DTMF'); localStorage.setItem('activeDtmfTab', '".(count($tabs_data)-1)."'); window.location.href = window.location.href;</script>"; exit;
    }
}
if (isset($_POST['del_tab_index'])) {
    $idx = (int)$_POST['del_tab_index'];
    if (isset($tabs_data[$idx])) {
        array_splice($tabs_data, $idx, 1);
        file_put_contents($custom_dtmf_file, json_encode($tabs_data));
        echo "<script>localStorage.setItem('activeTab', 'DTMF'); localStorage.setItem('activeDtmfTab', '0'); window.location.href = window.location.href;</script>"; exit;
    }
}
if (isset($_POST['add_btn_name']) && isset($_POST['target_tab_index'])) {
    $tab_idx = (int)$_POST['target_tab_index'];
    $name = trim($_POST['add_btn_name']);
    $tg = preg_replace('/[^0-9]/', '', $_POST['add_btn_code']);
    if (isset($tabs_data[$tab_idx]) && !empty($name) && !empty($tg)) {
        $tabs_data[$tab_idx]['buttons'][] = ['name' => $name, 'tg' => $tg];
        file_put_contents($custom_dtmf_file, json_encode($tabs_data));
        echo "<script>localStorage.setItem('activeTab', 'DTMF'); localStorage.setItem('activeDtmfTab', '$tab_idx'); window.location.href = window.location.href;</script>"; exit;
    }
}
if (isset($_POST['del_btn_tab_index']) && isset($_POST['del_btn_index'])) {
    $tab_idx = (int)$_POST['del_btn_tab_index'];
    $btn_idx = (int)$_POST['del_btn_index'];
    if (isset($tabs_data[$tab_idx]['buttons'][$btn_idx])) {
        array_splice($tabs_data[$tab_idx]['buttons'], $btn_idx, 1);
        file_put_contents($custom_dtmf_file, json_encode($tabs_data));
        echo "<script>localStorage.setItem('activeTab', 'DTMF'); localStorage.setItem('activeDtmfTab', '$tab_idx'); window.location.href = window.location.href;</script>"; exit;
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<div class="dtmf-columns">
    <div class="panel-box">
        <h4 class="panel-title">
            <?php echo $TDTMF[$lang]['h_groups']; ?>
            <span style="font-size:10px; color:#666; font-weight:normal; margin-left:10px;">(Przeciągnij, aby zmienić kolejność)</span>
        </h4>
        
        <div class="dtmf-tabs">
            <?php foreach($tabs_data as $i => $tab): ?>
                <div class="dtmf-tab-btn" id="tab-btn-<?php echo $i; ?>" onclick="openDtmfSubTab('<?php echo $i; ?>')">
                    <?php echo htmlspecialchars($tab['name']); ?>
                    <form method="post" style="display:inline;" onsubmit="return confirm('<?php echo $TDTMF[$lang]['del_ask']; ?>');">
                        <input type="hidden" name="del_tab_index" value="<?php echo $i; ?>">
                        <button type="submit" class="tab-del-btn">x</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <div class="tab-add-container">
                <form method="post" style="display:flex; align-items:center;">
                    <input type="text" name="new_tab_name" placeholder="<?php echo $TDTMF[$lang]['tab_name_ph']; ?>" class="tab-add-input" required>
                    <button type="submit" class="tab-add-btn"><?php echo $TDTMF[$lang]['add']; ?></button>
                </form>
            </div>
        </div>

        <?php foreach($tabs_data as $i => $tab): ?>
        <div id="DTMF-<?php echo $i; ?>" class="dtmf-subtab">
            <?php if (empty($tab['buttons'])): ?>
                <div style="text-align:center; color:#777; padding:20px; font-size:13px;"><?php echo $TDTMF[$lang]['empty_tab']; ?></div>
            <?php else: ?>
                <div class="macro-grid" id="sortable-grid-<?php echo $i; ?>" data-tab-index="<?php echo $i; ?>">
                    <?php foreach($tab['buttons'] as $b_idx => $btn): ?>
                        <div style="position:relative; cursor:move;" class="dtmf-item" data-btn-json='<?php echo json_encode($btn); ?>'>
                            <?php 
                                $code = isset($btn['code']) ? $btn['code'] : '*91'.$btn['tg'].'#';
                                $sub = isset($btn['code']) ? $btn['code'] : 'TG '.$btn['tg'];
                                $color = isset($btn['color']) ? $btn['color'] : (isset($btn['code']) ? '' : 'green');
                            ?>
                            <button onclick="sendInstant('<?php echo $code; ?>')" class="macro-btn <?php echo $color; ?>">
                                <?php echo htmlspecialchars($btn['name']); ?>
                                <span class="dtmf-sub"><?php echo $sub; ?></span>
                            </button>
                            <form method="post" style="position:absolute; top:-5px; right:-5px; margin:0;" onmousedown="event.stopPropagation();">
                                <input type="hidden" name="del_btn_tab_index" value="<?php echo $i; ?>">
                                <input type="hidden" name="del_btn_index" value="<?php echo $b_idx; ?>">
                                <button type="submit" class="dtmf-del-mini" onclick="return confirm('<?php echo $TDTMF[$lang]['del_ask']; ?>')">x</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div style="margin-top:20px; border-top:1px solid #444; padding-top:10px;">
                <form method="post">
                    <input type="hidden" name="target_tab_index" value="<?php echo $i; ?>">
                    <div style="display:flex; gap:5px;">
                        <input type="text" name="add_btn_name" placeholder="<?php echo $TDTMF[$lang]['ph_name']; ?>" class="node-input" style="flex:1; font-size:13px;" required>
                        <input type="number" name="add_btn_code" placeholder="TG" class="node-input" style="width:80px; font-size:13px;" required>
                        <button type="submit" class="macro-btn green" style="width:auto; min-height:40px; font-size:20px; padding:0 15px;">+</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="panel-box" style="border-color: #4CAF50;">
        <h4 class="panel-title neon-green" style="border-color: #4CAF50;"><?php echo $TDTMF[$lang]['h_el']; ?></h4>
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                 <div class="node-input-group" style="margin-bottom: 10px;">
                    <input type="text" id="el-node-id" class="node-input" placeholder="<?php echo $TDTMF[$lang]['ph_node']; ?>">
                    <button onclick="connectEchoLink()" class="macro-btn blue" style="width: auto; min-height: 40px; border-color:#4CAF50; color:#4CAF50 !important; background:rgba(76,175,80,0.1);"><?php echo $TDTMF[$lang]['btn_conn']; ?></button>
                </div>
                <div class="macro-grid">
                    <button onclick="sendInstant('2#')" class="macro-btn green"><?php echo $TDTMF[$lang]['btn_act']; ?></button>
                    <button onclick="sendInstant('9999#')" class="macro-btn">Test Echo</button>
                    <button onclick="sendInstant('#')" class="macro-btn red"><?php echo $TDTMF[$lang]['btn_disc']; ?> (#)</button>
                </div>
            </div>
            <div style="flex: 1; display:flex; flex-direction:column; gap:5px; height: 100%;">
                 <div id="el-live-status"><?php echo $TDTMF[$lang]['st_check']; ?></div>
            </div>
        </div>
    </div>

    <div class="panel-box" style="border-color: #FF9800;">
        <h4 class="panel-title" style="color: #FF9800; border-color: #FF9800;"><?php echo $TDTMF[$lang]['h_par']; ?></h4>
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <div class="macro-grid">
                    <button onclick="sendInstant('1#')" class="macro-btn orange"><?php echo $TDTMF[$lang]['btn_par_on']; ?></button>
                    <button onclick="sendInstant('#')" class="macro-btn red"><?php echo $TDTMF[$lang]['btn_par_off']; ?></button>
                </div>
            </div>
            <div style="flex: 1; font-size: 13px; color: #ccc; background: #222; padding: 10px; border-radius: 5px; border-left: 3px solid #FF9800;">
                <strong><?php echo $TDTMF[$lang]['how']; ?></strong>
                <ol style="margin: 5px 0; padding-left: 20px; line-height: 1.6;">
                    <li><?php echo $TDTMF[$lang]['s1']; ?></li>
                    <li><?php echo $TDTMF[$lang]['s2']; ?></li>
                    <li><?php echo $TDTMF[$lang]['s3']; ?></li>
                    <li><?php echo $TDTMF[$lang]['s4']; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<h3 style="border-top:1px solid #444; paddingTop:20px;"><?php echo $TDTMF[$lang]['h_key']; ?></h3>
<div class="dtmf-display" id="dtmf-screen">...</div>
<div class="dtmf-grid"><button onclick="typeKey('1')" class="dtmf-btn">1</button><button onclick="typeKey('2')" class="dtmf-btn">2</button><button onclick="typeKey('3')" class="dtmf-btn">3</button><button onclick="typeKey('4')" class="dtmf-btn">4</button><button onclick="typeKey('5')" class="dtmf-btn">5</button><button onclick="typeKey('6')" class="dtmf-btn">6</button><button onclick="typeKey('7')" class="dtmf-btn">7</button><button onclick="typeKey('8')" class="dtmf-btn">8</button><button onclick="typeKey('9')" class="dtmf-btn">9</button><button onclick="typeKey('*')" class="dtmf-btn">*</button><button onclick="typeKey('0')" class="dtmf-btn">0</button><button onclick="typeKey('#')" class="dtmf-btn">#</button><button onclick="clearKey()" class="dtmf-btn dtmf-clear">C</button><button onclick="submitTG()" class="dtmf-btn dtmf-tg">TG</button><button onclick="submitKey()" class="dtmf-btn dtmf-send">TX</button></div>
<p style="font-size:12px; color:#888; text-align:center;"><?php echo $TDTMF[$lang]['leg_c']; ?>, <?php echo $TDTMF[$lang]['leg_tg']; ?>, <?php echo $TDTMF[$lang]['leg_tx']; ?></p>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var grids = document.querySelectorAll('.macro-grid[id^="sortable-grid-"]');
    
    grids.forEach(function(grid) {
        new Sortable(grid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                var tabIndex = grid.getAttribute('data-tab-index');
                var items = grid.querySelectorAll('.dtmf-item');
                var newOrder = [];
                
                items.forEach(function(item) {
                    var data = JSON.parse(item.getAttribute('data-btn-json'));
                    newOrder.push(data);
                });
                
                $.post("index.php", {
                    reorder_dtmf_tab: tabIndex,
                    new_order_json: JSON.stringify(newOrder)
                }, function(response) {
                    console.log("Reorder saved: " + response);
                });
            }
        });
    });
});
</script>