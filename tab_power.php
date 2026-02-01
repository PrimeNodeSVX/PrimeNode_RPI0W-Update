<?php
$TP = [
    'pl' => [
        'load_title' => 'TRWA AKTUALIZACJA...',
        'load_desc' => 'Pobieranie plików z GitHub.<br>Proszę nie zamykać okna ani nie odświeżać strony.',
        'pwr_mng' => 'Zarządzanie Zasilaniem',
        'btn_svc' => 'Restart Usługi SvxLink',
        'btn_reb' => '🔄 Restart Urządzenia',
        'conf_reb' => 'Czy na pewno chcesz zrestartować CAŁY system?',
        'btn_off' => '🛑 Wyłącz Urządzenie',
        'conf_off' => 'Czy na pewno chcesz WYŁĄCZYĆ urządzenie?',
        'sys_upd' => 'Aktualizacja Systemu',
        'btn_git' => '☁️ Pobierz Aktualizację Dashboardu (GitHub)'
    ],
    'en' => [
        'load_title' => 'UPDATING...',
        'load_desc' => 'Downloading files from GitHub.<br>Please do not close or refresh this page.',
        'pwr_mng' => 'Power Management',
        'btn_svc' => 'Restart SvxLink Service',
        'btn_reb' => '🔄 Reboot Device',
        'conf_reb' => 'Are you sure you want to reboot the WHOLE system?',
        'btn_off' => '🛑 Shutdown Device',
        'conf_off' => 'Are you sure you want to POWER OFF the device?',
        'sys_upd' => 'System Update',
        'btn_git' => '☁️ Get Dashboard Update (GitHub)'
    ]
];
?>
<style>
    #loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        backdrop-filter: blur(5px);
    }

    .spinner {
        border: 8px solid #333;
        border-top: 8px solid #4CAF50;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text {
        color: #fff;
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1px;
    }
    
    .loading-subtext {
        color: #ccc;
        font-size: 14px;
        margin-top: 10px;
    }
</style>

<div id="loading-overlay">
    <div class="spinner"></div>
    <div class="loading-text"><?php echo $TP[$lang]['load_title']; ?></div>
    <div class="loading-subtext"><?php echo $TP[$lang]['load_desc']; ?></div>
</div>

<h4 class="panel-title"><?php echo $TP[$lang]['pwr_mng']; ?></h4>
<form method="post" id="power-form">
    <input type="hidden" name="active_tab" class="active-tab-input" value="Power">
    
    <button type="submit" name="restart_srv" class="btn btn-blue" style="margin-bottom:15px;"><?php echo $TP[$lang]['btn_svc']; ?></button>
    
    <div style="height:10px;"></div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom: 20px;">
        <button type="submit" name="reboot_device" class="btn btn-orange" onclick="return confirm('<?php echo $TP[$lang]['conf_reb']; ?>')"><?php echo $TP[$lang]['btn_reb']; ?></button>
        <button type="submit" name="shutdown_device" class="btn btn-red" onclick="return confirm('<?php echo $TP[$lang]['conf_off']; ?>')"><?php echo $TP[$lang]['btn_off']; ?></button>
    </div>

    <hr style="border: 0; border-top: 1px solid #444; margin: 20px 0;">
    <h4 class="panel-title" style="color: #FF9800; border: none;"><?php echo $TP[$lang]['sys_upd']; ?></h4>
    
    <button type="submit" name="git_update" class="btn btn-green" onclick="showLoader()"><?php echo $TP[$lang]['btn_git']; ?></button>
</form>

<script>
    function showLoader() {
        document.getElementById('loading-overlay').style.display = 'flex';
    }
</script>
