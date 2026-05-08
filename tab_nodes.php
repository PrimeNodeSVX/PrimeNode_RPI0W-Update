<?php
$TN = [
    'pl' => [
        'operator' => 'Operator:',
        'curr_tg' => 'Aktualna Grupa (TG):',
        'qth_loc' => 'Lokator QTH:',
        'city_desc' => 'Miasto / Opis:',
        'monitored' => 'Monitorowane:',
        'ver' => 'Wersja:',
	'lbl_radio' => 'Radio (QRG):',
        'btn_close_map' => '❌ ZAMKNIJ MAPĘ',
        'header' => 'Aktywne Węzły',
        'btn_open_map' => '🌍 Grid Mapper Node (Pokaż Mapę)',
        'loading' => 'Ładowanie listy węzłów...'
    ],
    'en' => [
        'operator' => 'Operator:',
        'curr_tg' => 'Current Group (TG):',
        'qth_loc' => 'QTH Locator:',
        'city_desc' => 'City / Desc:',
        'monitored' => 'Monitored:',
        'ver' => 'Version:',
	'lbl_radio' => 'Radio (QRG):',
        'btn_close_map' => '❌ CLOSE MAP',
        'header' => 'Active Nodes',
        'btn_open_map' => '🌍 Grid Mapper Node (Show Map)',
        'loading' => 'Loading node list...'
    ]
];
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div id="node-tooltip" class="node-tooltip">
    <div class="nt-header">
        <span id="nt-callsign"></span>
        <span id="nt-sw"></span>
    </div>
    <div class="nt-body">
        
        <div class="nt-row">
            <span class="nt-label"><?php echo $TN[$lang]['operator']; ?></span>
            <span class="nt-val hl" id="nt-name">---</span>
        </div>

        <div class="nt-row">
            <span class="nt-label"><?php echo $TN[$lang]['curr_tg']; ?></span>
            <span class="nt-val" id="nt-tg">---</span>
        </div>

        <div class="nt-row">
            <span class="nt-label"><?php echo $TN[$lang]['qth_loc']; ?></span>
            <span class="nt-val" id="nt-qth">---</span>
        </div>

        <div class="nt-row">
            <span class="nt-label"><?php echo $TN[$lang]['city_desc']; ?></span>
            <span class="nt-val" id="nt-loc">---</span>
        </div>
        
        <div class="nt-row">
            <span class="nt-label"><?php echo $TN[$lang]['monitored']; ?></span>
            <span class="nt-val" id="nt-monitored">---</span>
        </div>
        
        <div class="nt-row">
            <span class="nt-label"><?php echo $TN[$lang]['ver']; ?></span>
            <span class="nt-val" id="nt-ver">---</span>
        </div>
	
	<div class="nt-row" id="row-radio" style="display:none;">
            <span class="nt-label"><?php echo $TN[$lang]['lbl_radio']; ?></span>
            <span class="nt-val hl" id="nt-radio">---</span>
        </div>
        
        <div class="qrz-logo-container">
            <img src="qrz.png" alt="QRZ" class="qrz-img">
        </div>
    </div>
</div>

<div id="map-overlay">
    <button class="map-close-btn" onclick="closeGridMapper()"><?php echo $TN[$lang]['btn_close_map']; ?></button>
    <div id="map-container"></div>
</div>

<h3><?php echo $TN[$lang]['header']; ?></h3>

<div style="text-align: center; margin-bottom: 15px;">
    <button class="map-btn-trigger" onclick="openGridMapper()"><?php echo $TN[$lang]['btn_open_map']; ?></button>
</div>

<div class="nodes-container" id="nodes-content">
    <div style="grid-column: 1/-1; text-align: center; color: #777; padding: 20px;">
        <?php echo $TN[$lang]['loading']; ?>
    </div>
</div>

<script>
    if(typeof updateNodes === "function") {
        updateNodes();
    }
</script>

<div id="node-action-menu" style="display:none; position:fixed; z-index:9999; background:#1e1e1e; border:2px solid #FF9800; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.9); padding:12px; width:220px; text-align:center;">
    <div style="font-weight:bold; color:#fff; margin-bottom:10px; border-bottom:1px solid #333; padding-bottom:5px;" id="nam-title">Wybierz akcję</div>
    
    <button id="nam-btn-tg" class="btn btn-orange" style="margin-top:0; margin-bottom:8px; padding:10px; font-size:13px;" onclick="">🎙️ Przełącz na TG</button>
    
    <button id="nam-btn-qrz" class="btn btn-blue" style="margin-top:0; margin-bottom:8px; padding:10px; font-size:13px;" onclick="">🌐 Profil QRZ.com</button>
    
    <button class="btn btn-red" style="background:#444; border-color:#555; margin-top:0; padding:8px; font-size:12px;" onclick="closeNodeActionMenu()">❌ Anuluj</button>
</div>
