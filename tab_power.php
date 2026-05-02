<?php
$TP = [
    'pl' => [
        'load_title' => 'TRWA AKTUALIZACJA...',
        'load_text' => 'Pobieranie plików z GitHub.<br>Proszę nie zamykać okna ani nie odświeżać strony.',
        'title_pwr' => 'Zarządzanie Zasilaniem',
        'btn_svx' => 'Restart Usługi SvxLink',
        'ask_reb' => 'Czy na pewno chcesz zrestartować CAŁY system?',
        'btn_reb' => '🔄 Restart Urządzenia',
        'ask_off' => 'Czy na pewno chcesz WYŁĄCZYĆ urządzenie?',
        'btn_off' => '🛑 Wyłącz Urządzenie',
        'title_upd' => 'Aktualizacja Systemu',
        'btn_upd' => '☁️ Pobierz Aktualizację Dashboardu (GitHub)',
        'update_avail' => '✨ Dostępna nowa aktualizacja! Możesz ją pobrać poniżej.'
    ],
    'en' => [
        'load_title' => 'UPDATING...',
        'load_text' => 'Downloading files from GitHub.<br>Please do not close or refresh the page.',
        'title_pwr' => 'Power Management',
        'btn_svx' => 'Restart SvxLink Service',
        'ask_reb' => 'Are you sure you want to reboot the WHOLE system?',
        'btn_reb' => '🔄 Reboot Device',
        'ask_off' => 'Are you sure you want to SHUT DOWN the device?',
        'btn_off' => '🛑 Shutdown Device',
        'title_upd' => 'System Update',
        'btn_upd' => '☁️ Download Dashboard Update (GitHub)',
        'update_avail' => '✨ New update available! You can download it below.'
    ]
];

$update_flag_file = '/var/www/html/ram/update_status.txt';
$update_available = false;

if (isset($_POST['git_update'])) {
    @unlink($update_flag_file); 
}

if (!file_exists($update_flag_file)) {
    $remote_hash = trim(shell_exec("timeout 4 git ls-remote https://github.com/PrimeNodeSVX/PrimeNode_RPI0W-Update.git HEAD | awk '{print $1}' 2>/dev/null"));
    $local_hash = trim(shell_exec("git --git-dir=/root/PrimeNode_RPI0W-Update/.git rev-parse HEAD 2>/dev/null"));
    
    if (!empty($local_hash) && !empty($remote_hash)) {
        if ($local_hash !== $remote_hash) {
            @file_put_contents($update_flag_file, "UPDATE_AVAILABLE");
        } else {
            @file_put_contents($update_flag_file, "UP_TO_DATE");
        }
        @chmod($update_flag_file, 0666);
    } else {
        if (file_exists($update_flag_file)) {
            @unlink($update_flag_file); 
        }
    }
}

if (trim(@file_get_contents($update_flag_file)) === "UPDATE_AVAILABLE") {
    $update_available = true;
}
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

<h4 class="panel-title"><?php echo $TP[$lang]['title_pwr']; ?></h4>
<form method="post" id="power-form">
    <input type="hidden" name="active_tab" class="active-tab-input" value="Power">
    
    <button type="submit" name="restart_srv" class="btn btn-blue" style="margin-bottom:15px;"><?php echo $TP[$lang]['btn_svx']; ?></button>
    
    <div style="height:10px;"></div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom: 20px;">
        <button type="submit" name="reboot_device" class="btn btn-orange" onclick="return confirm('<?php echo $TP[$lang]['ask_reb']; ?>')"><?php echo $TP[$lang]['btn_reb']; ?></button>
        <button type="submit" name="shutdown_device" class="btn btn-red" onclick="return confirm('<?php echo $TP[$lang]['ask_off']; ?>')"><?php echo $TP[$lang]['btn_off']; ?></button>
    </div>

    <hr style="border: 0; border-top: 1px solid #444; margin: 20px 0;">
    <h4 class="panel-title" style="color: #FF9800; border: none;"><?php echo $TP[$lang]['title_upd']; ?></h4>
    
    <?php if ($update_available): ?>
        <style>
            @keyframes pulse-update {
                0% { background-color: rgba(76, 175, 80, 0.2); border-color: #4CAF50; color: #4CAF50; box-shadow: 0 0 10px rgba(76, 175, 80, 0.5); transform: scale(1); }
                50% { background-color: rgba(255, 152, 0, 0.3); border-color: #FF9800; color: #FF9800; box-shadow: 0 0 20px rgba(255, 152, 0, 0.8); transform: scale(1.02); }
                100% { background-color: rgba(76, 175, 80, 0.2); border-color: #4CAF50; color: #4CAF50; box-shadow: 0 0 10px rgba(76, 175, 80, 0.5); transform: scale(1); }
            }
            .persistent-update-box {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 6px;
                border: 2px solid;
                text-align: center;
                font-weight: bold;
                font-size: 14px;
                text-transform: uppercase;
                letter-spacing: 1px;
                animation: pulse-update 2s infinite ease-in-out;
            }
        </style>
        <div class="persistent-update-box">
            <?php echo $TP[$lang]['update_avail']; ?>
        </div>
    <?php endif; ?>
    
    <button type="submit" name="git_update" class="btn btn-green" onclick="showLoader()"><?php echo $TP[$lang]['btn_upd']; ?></button>
</form>

<script>
    function showLoader() {
        document.getElementById('loading-overlay').style.display = 'flex';
    }
</script>
