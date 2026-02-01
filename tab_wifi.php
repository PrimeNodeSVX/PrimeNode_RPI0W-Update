<?php
$TW = [
    'pl' => [
        'status_title' => 'Status Połączenia',
        'loading' => 'Ładowanie...',
        'saved_title' => 'Zapamiętane Sieci',
        'th_ssid' => 'SSID',
        'th_action' => 'Akcja',
        'btn_del' => 'X Usuń',
        'confirm_del' => 'Czy na pewno usunąć zapamiętaną sieć:',
        'no_saved' => 'Brak zapamiętanych sieci.',
        'new_title' => 'Połącz z nową siecią',
        'btn_scan' => '📡 Skanuj Sieci WiFi',
        'scanning' => '⏳ Skanowanie...',
        'connecting' => '⏳ Łączenie... <span style=\'font-size:0.8em; font-weight:normal\'>(Czekaj...)</span>',
        'ph_pass' => 'Hasło',
        'btn_connect' => 'Połącz'
    ],
    'en' => [
        'status_title' => 'Connection Status',
        'loading' => 'Loading...',
        'saved_title' => 'Saved Networks',
        'th_ssid' => 'SSID',
        'th_action' => 'Action',
        'btn_del' => 'X Delete',
        'confirm_del' => 'Are you sure you want to delete saved network:',
        'no_saved' => 'No saved networks.',
        'new_title' => 'Connect to New Network',
        'btn_scan' => '📡 Scan WiFi Networks',
        'scanning' => '⏳ Scanning...',
        'connecting' => '⏳ Connecting... <span style=\'font-size:0.8em; font-weight:normal\'>(Wait...)</span>',
        'ph_pass' => 'Password',
        'btn_connect' => 'Connect'
    ]
];
?>
<div class="panel-box" style="text-align:center; margin-bottom:15px;">
    <h4 class="panel-title blue"><?php echo $TW[$lang]['status_title']; ?></h4>
    <div id="wifi-tab-status" style="font-weight:bold; font-size:18px; color:#fff;"><?php echo $TW[$lang]['loading']; ?></div>
    <div id="wifi-tab-ip" style="color:#4CAF50; margin-top:5px;">...</div>
</div>

<h3><?php echo $TW[$lang]['saved_title']; ?></h3>
<?php if (!empty($saved_wifi_list)): ?>
<table class="wifi-saved-table">
    <thead><tr><th><?php echo $TW[$lang]['th_ssid']; ?></th><th><?php echo $TW[$lang]['th_action']; ?></th></tr></thead>
    <tbody>
        <?php foreach($saved_wifi_list as $sw): ?>
        <tr>
            <td><b><?php echo htmlspecialchars($sw); ?></b></td>
            <td style="text-align:right;">
                <form method="post" style="margin:0;">
                    <input type="hidden" name="active_tab" class="active-tab-input" value="WiFi">
                    <input type="hidden" name="ssid" value="<?php echo htmlspecialchars($sw); ?>">
                    <button type="submit" name="wifi_delete" class="btn-small-del" onclick="return confirm('<?php echo $TW[$lang]['confirm_del']; ?> <?php echo htmlspecialchars($sw); ?>?')"><?php echo $TW[$lang]['btn_del']; ?></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p style="color:#888; font-style:italic;"><?php echo $TW[$lang]['no_saved']; ?></p>
<?php endif; ?>

<h3><?php echo $TW[$lang]['new_title']; ?></h3>
<form method="post" style="margin-bottom: 20px;" onsubmit="document.getElementById('scan-btn').innerText = '<?php echo $TW[$lang]['scanning']; ?>';">
    <input type="hidden" name="active_tab" class="active-tab-input" value="WiFi">
    <button type="submit" name="wifi_scan" id="scan-btn" class="btn btn-blue"><?php echo $TW[$lang]['btn_scan']; ?></button>
</form>

<?php if (!empty($wifi_scan_results)): ?>
<div style="max-height:200px; overflow-y:auto; border:1px solid #333;">
    <table class="lh-table"><tbody><?php foreach($wifi_scan_results as $w): ?><tr class="wifi-row" onclick="selectWifi('<?php echo htmlspecialchars($w['ssid']); ?>')"><td><b><?php echo $w['ssid']; ?></b></td><td><?php echo $w['signal']; ?>%</td></tr><?php endforeach; ?></tbody></table>
</div>
<?php endif; ?>

<form method="post" style="margin-top:10px;" onsubmit="document.getElementById('btn-conn').innerHTML = '<?php echo $TW[$lang]['connecting']; ?>';">
    <input type="hidden" name="active_tab" class="active-tab-input" value="WiFi">
    
    <input type="text" id="wifi-ssid" name="ssid" placeholder="SSID">
    <input type="password" name="pass" placeholder="<?php echo $TW[$lang]['ph_pass']; ?>" style="margin-top:5px;">
    
    <button type="submit" name="wifi_connect" id="btn-conn" class="btn btn-green"><?php echo $TW[$lang]['btn_connect']; ?></button>
</form>