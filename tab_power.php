<?php
$TP = [
    'pl' => [
        'load_title' => 'TRWA AKTUALIZACJA...',
        'load_text' => 'Pobieranie plików z GitHub.<br>Proszę nie zamykać okna ani nie odświeżać strony.',
        'title_pwr' => 'Zarządzanie Zasilaniem',
        'btn_svx' => 'Restart Usługi SvxLink',
        'btn_stop_svx' => '🛑 Zatrzymaj SvxLink',
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
        'btn_stop_svx' => '🛑 Stop SvxLink Service',
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

if (file_exists($update_flag_file) && (time() - filemtime($update_flag_file) > 3600)) {
    @unlink($update_flag_file);
}

if (!file_exists($update_flag_file)) {
    $remote_hash = trim(shell_exec("timeout 4 git ls-remote https://github.com/PrimeNodeSVX/PrimeNode_RPI0W-Update.git HEAD | awk '{print \$1}' 2>/dev/null"));
    $local_hash = trim(shell_exec("sudo git --git-dir=/root/PrimeNode_RPI0W-Update/.git rev-parse HEAD 2>/dev/null"));
    
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

if (isset($_POST['core_update'])) {
    @mkdir('/var/www/html/ram', 0777, true);
    @file_put_contents('/var/www/html/ram/core_install.log', "Zainicjowano z poziomu WWW. Przygotowanie do pobrania zrodla...\n");
    shell_exec('sudo /usr/local/bin/update_core.sh > /dev/null 2>&1 &');
    $show_core_modal = true;
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
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom: 20px;">
        <button type="submit" name="restart_srv" class="btn btn-blue"><?php echo $TP[$lang]['btn_svx']; ?></button>
        <button type="submit" name="stop_srv" class="btn btn-orange" onclick="return confirm('Na pewno chcesz ZATRZYMAĆ usługę SvxLink?')"><?php echo $TP[$lang]['btn_stop_svx']; ?></button>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom: 20px;">
        <button type="submit" name="reboot_device" class="btn btn-red" style="opacity: 0.9;" onclick="return confirm('<?php echo $TP[$lang]['ask_reb']; ?>')"><?php echo $TP[$lang]['btn_reb']; ?></button>
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

    <hr style="border: 0; border-top: 1px solid #444; margin: 20px 0;">
    <h4 class="panel-title" style="color: #F44336; border: none;">Zaawansowane (Silnik Radiowy)</h4>
    <button type="submit" name="core_update" class="btn btn-red" style="margin-bottom:20px;" onclick="return confirm('UWAGA: Kompilacja zajmie ok. 25 minut. System zostanie w tym czasie unieruchomiony. Na pewno?')">⚙️ Aktualizuj Core SvxLink</button>
</form>

<script>
    function showLoader() {
        document.getElementById('loading-overlay').style.display = 'flex';
    }
</script>

<?php if (isset($show_core_modal) && $show_core_modal): ?>
<div id="core-install-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); backdrop-filter: blur(8px); z-index: 9999; display: flex; justify-content: center; align-items: center;">
    <div style="background: #222; border: 2px solid #F44336; border-radius: 8px; padding: 25px; max-width: 700px; width: 90%; text-align: center; box-shadow: 0 0 30px rgba(244, 67, 54, 0.4); margin: 20px;">
        <h2 style="color: #F44336; margin-top: 0; font-size: 24px;">⚙️ Trwa Kompilacja Silnika SvxLink...</h2>
        <p style="color: #eee; font-size: 15px; margin-bottom: 20px;">
            Proces będzie trwał około 20-30 minut w zależności od obciążenia procesora.<br>
            <b style="color: #FF9800;">NIE WYŁĄCZAJ STRONY I NIE ODŁĄCZAJ ZASILANIA!</b>
        </p>
        
        <div style="background: #000; border: 1px solid #444; border-radius: 4px; padding: 15px; text-align: left;">
            <div style="color: #888; font-size: 11px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">Podgląd terminala na żywo:</div>
            <div id="core-log-view" style="color: #0f0; font-family: monospace; font-size: 13px; height: 250px; overflow-y: auto; white-space: pre-wrap; line-height: 1.4;">Inicjalizacja...</div>
        </div>
        
        <div id="core-install-spinner" style="margin-top: 20px; color: #888; font-size: 13px;">
            <span style="display:inline-block; animation: pulse 1.5s infinite;">⏳ System pracuje, pobieranie...</span>
        </div>
    </div>
</div>
<script>
    window.isCoreRestarting = false;
    let checkCoreInterval = setInterval(() => {
        fetch("get_core_log.php?t=" + new Date().getTime())
        .then(r => r.text())
        .then(t => {
            const log = document.getElementById("core-log-view");
            if(t.trim().length > 0 && !window.isCoreRestarting) {
                log.innerText = t;
                log.scrollTop = log.scrollHeight;
                if(t.includes("ZAKONCZONA SUKCESEM")) {
                    window.isCoreRestarting = true;
                    document.getElementById("core-install-spinner").innerHTML = "<span style='color:#4CAF50; font-weight:bold; animation: pulse 1s infinite;'>🔄 Gotowe! Trwa restartowanie hotspota...</span>";
                    setTimeout(function(){ window.location.href = '/'; }, 15000);
                } else if(t.includes("SYSTEM JEST JUZ AKTUALNY")) {
                    window.isCoreRestarting = true;
                    document.getElementById("core-install-spinner").innerHTML = "<span style='color:#FF9800; font-weight:bold;'>⚠️ Masz już najnowszą wersję (V26.05.1). Przerywam...</span>";
                    setTimeout(function(){ window.location.href = '/'; }, 4000);
                }
            }
        }).catch(e => {});
    }, 2000);
</script>
<?php endif; ?>