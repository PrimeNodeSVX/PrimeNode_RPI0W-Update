<?php
$TD = [
    'pl' => [
        'freq' => 'Częstotliwość',
        'host' => 'Host',
        'call' => 'Znak',
        'hw' => 'Sprzęt:',
        'ctcss' => 'CTCSS:',
        'status_standby' => 'STAN: CZUWANIE (Standby)',
        'lh_title' => 'Ostatnio Słysz&#8203;a&#8203;n&#8203;i (Last Heard)',
        'th_time' => 'Godzina',
        'th_tg' => 'TG',
        'th_call' => 'Znak',
        'loading' => 'Ładowanie...',
        'none_csq' => 'Brak (CSQ)'
    ],
    'en' => [
        'freq' => 'Frequency',
        'host' => 'Host',
        'call' => 'Callsign',
        'hw' => 'Hardware:',
        'ctcss' => 'CTCSS:',
        'status_standby' => 'STATUS: STANDBY',
        'lh_title' => 'Last Heard',
        'th_time' => 'Time',
        'th_tg' => 'TG',
        'th_call' => 'Callsign',
        'loading' => 'Loading...',
        'none_csq' => 'None (CSQ)'
    ]
];

$CTCSS_MAP = [
    "0000" => $TD[$lang]['none_csq'], "0670" => "67.0 Hz", "0693" => "69.3 Hz", "0719" => "71.9 Hz", "0744" => "74.4 Hz", 
    "0770" => "77.0 Hz", "0797" => "79.7 Hz", "0825" => "82.5 Hz", "0854" => "85.4 Hz", "0885" => "88.5 Hz", 
    "0915" => "91.5 Hz", "0948" => "94.8 Hz", "0974" => "97.4 Hz", "1000" => "100.0 Hz", "1035" => "103.5 Hz", 
    "1072" => "107.2 Hz", "1109" => "110.9 Hz", "1148" => "114.8 Hz", "1188" => "118.8 Hz", "1230" => "123.0 Hz", 
    "1273" => "127.3 Hz", "1318" => "131.8 Hz", "1365" => "136.5 Hz", "1413" => "141.3 Hz", "1462" => "146.2 Hz", 
    "1514" => "151.4 Hz", "1567" => "156.7 Hz", "1598" => "159.8 Hz", "1622" => "162.2 Hz", "1655" => "165.5 Hz", 
    "1679" => "167.9 Hz", "1713" => "171.3 Hz", "1738" => "173.8 Hz", "1773" => "177.3 Hz", "1799" => "179.9 Hz", 
    "1835" => "183.5 Hz", "1862" => "186.2 Hz", "1899" => "189.9 Hz", "1928" => "192.8 Hz", "1966" => "196.6 Hz", 
    "1995" => "199.5 Hz", "2035" => "203.5 Hz", "2065" => "206.5 Hz", "2107" => "210.7 Hz", "2181" => "218.1 Hz",
    "2257" => "225.7 Hz", "2291" => "229.1 Hz", "2336" => "233.6 Hz", "2418" => "241.8 Hz", "2503" => "250.3 Hz", 
    "2541" => "254.1 Hz"
];

function formatCtcssCode($code, $map) {
    if (isset($map[$code])) {
        return $map[$code];
    }
    if (is_numeric($code) && $code > 0) {
        return number_format($code / 10, 1) . ' Hz';
    }
    return $code;
}
?>
<style>
    .dash-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    .dash-tile {
        background: #262626;
        border: 1px solid #333;
        border-radius: 8px;
        padding: 15px 5px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }
    .dash-tile:hover {
        background: #2e2e2e;
        border-color: #4CAF50;
    }
    .dash-icon {
        font-size: 28px;
        margin-bottom: 8px;
        height: 35px;
    }
    .dash-label {
        font-size: 10px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2px;
    }
    .dash-value {
        font-size: 14px;
        font-weight: bold;
        color: #fff;
    }
    @media (max-width: 600px) {
        .dash-grid { grid-template-columns: repeat(1, 1fr); }
    }
</style>

<div class="dash-grid">
    <div class="dash-tile">
        <div class="dash-icon">📡</div>
        <div class="dash-label"><?php echo $TD[$lang]['freq']; ?></div>
        <div class="dash-value"><?php echo $radio['rx']; ?> MHz</div>
    </div>
    
    <div class="dash-tile">
        <div class="dash-icon">🌍</div>
        <div class="dash-label"><?php echo $TD[$lang]['host']; ?></div>
        <div class="dash-value"><?php echo $vals['Host']; ?></div>
    </div>

    <div class="dash-tile">
        <div class="dash-icon">🆔</div>
        <div class="dash-label"><?php echo $TD[$lang]['call']; ?></div>
        <div class="dash-value"><?php echo $vals['Callsign']; ?></div>
    </div>
</div>

<div style="text-align:center; margin-bottom:25px; display:flex; justify-content:center; gap: 15px; flex-wrap: wrap;">
    <div style="background: #222; padding: 8px 15px; border-radius: 20px; border: 1px solid #444; display:flex; align-items:center; gap:8px;">
        <span style="font-size:16px;">📻</span>
        <span style="font-size:13px; color:#aaa;"><?php echo $TD[$lang]['hw']; ?></span>
        <b style="color:#fff; font-size:14px;"><?php echo isset($radio['desc']) ? $radio['desc'] : 'USB Soundcard (CM108)'; ?></b>
    </div>

    <?php if(isset($radio['ctcss']) && $radio['ctcss'] != '0000'): ?>
    <div style="background: #222; padding: 8px 15px; border-radius: 20px; border: 1px solid #444; display:flex; align-items:center; gap:8px;">
        <span style="font-size:16px;">🔒</span>
        <span style="font-size:13px; color:#aaa;"><?php echo $TD[$lang]['ctcss']; ?></span>
        <b style="color:#FF9800; font-size:14px;">
            <?php echo formatCtcssCode($radio['ctcss'], $CTCSS_MAP); ?>
        </b>
    </div>
    <?php endif; ?>
</div>

<div id="live-monitor" class="live-box">
    <div class="live-status"><?php echo $TD[$lang]['status_standby']; ?></div>
    <div class="live-callsign">---</div>
    <div class="live-info" style="font-size: 16px; color: #4CAF50; font-weight: bold; margin-top: 2px; min-height: 20px;"></div>
    <div class="live-tg"></div>
</div>

<h3 style="color: #4CAF50; margin-top:20px;"><?php echo $TD[$lang]['lh_title']; ?></h3>
<table class="lh-table">
    <thead>
        <tr>
            <th><?php echo $TD[$lang]['th_time']; ?></th>
            <th><?php echo $TD[$lang]['th_tg']; ?></th>
            <th><?php echo $TD[$lang]['th_call']; ?></th>
        </tr>
    </thead>
    <tbody id="lh-content">
        <tr><td colspan="3"><?php echo $TD[$lang]['loading']; ?></td></tr>
    </tbody>
</table>
