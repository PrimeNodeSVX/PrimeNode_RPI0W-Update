<?php echo isset($audio_msg) ? $audio_msg : ''; ?>

<?php
$TA = [
    'pl' => [
        'diag_title' => '🎧 Diagnostyka Karty Dźwiękowej',
        'diag_desc' => 'Jeśli po zmianie portu USB lub wgraniu backupu <b>nie ma dźwięku</b>, użyj tego przycisku.<br>System sam <b>znajdzie kartę</b> i automatycznie poprawi konfigurację.',
        'btn_fix' => '🔍 Znajdź i Napraw Audio',
        'mixer_title' => 'Mikser Audio (CM108 USB)',
        'rx_title' => 'Odbiór (RX) - Czułość Mikrofonu',
        'lbl_mic_cap' => 'Mic Capture (Włącz)',
        'lbl_mic_vol' => 'Mic Volume (Czułość)',
        'lbl_agc' => 'Auto Gain Control (AGC)',
        'warn_agc' => '*Dla stabilnej pracy AGC musi być WYŁĄCZONE (off)!',
        'tx_title' => 'Nadawanie (TX) - Głośność na Radio',
        'lbl_spk_play' => 'Speaker Playback (Włącz)',
        'lbl_spk_vol' => 'Speaker Volume (Moc Audio)',
        'btn_save' => 'Zapisz Ustawienia Audio'
    ],
    'en' => [
        'diag_title' => '🎧 Audio Card Diagnostics',
        'diag_desc' => 'If there is <b>no sound</b> after changing USB port or restoring backup, use this button.<br>System will <b>find the card</b> and fix configuration automatically.',
        'btn_fix' => '🔍 Find & Fix Audio',
        'mixer_title' => 'Audio Mixer (CM108 USB)',
        'rx_title' => 'Receive (RX) - Mic Sensitivity',
        'lbl_mic_cap' => 'Mic Capture (On)',
        'lbl_mic_vol' => 'Mic Volume (Sensitivity)',
        'lbl_agc' => 'Auto Gain Control (AGC)',
        'warn_agc' => '*For stable operation AGC must be OFF!',
        'tx_title' => 'Transmit (TX) - Radio Volume',
        'lbl_spk_play' => 'Speaker Playback (On)',
        'lbl_spk_vol' => 'Speaker Volume (Audio Power)',
        'btn_save' => 'Save Audio Settings'
    ]
];
?>

<div style="border: 1px solid #444; border-left: 5px solid #2196F3; padding: 15px; margin-bottom: 25px; background: rgba(33, 150, 243, 0.1); border-radius: 4px;">
    <h4 style="margin-top: 0; margin-bottom: 10px; color: #2196F3;"><?php echo $TA[$lang]['diag_title']; ?></h4>
    
    <div style="font-size: 0.95em; color: #ccc; margin-bottom: 15px; line-height: 1.5;">
        <?php echo $TA[$lang]['diag_desc']; ?>
    </div>
    
    <form method="post">
        <input type="hidden" name="active_tab" class="active-tab-input" value="Audio">
        <button type="submit" name="fix_audio_btn" class="btn" style="background-color: #2196F3; color: white; border: none; padding: 8px 15px; cursor: pointer;"><?php echo $TA[$lang]['btn_fix']; ?></button>
    </form>

    <?php
    if (isset($_POST['fix_audio_btn'])) {
        echo '<div style="margin-top: 15px; padding: 10px; background: #111; border: 1px solid #444; color: #4CAF50; font-family: monospace; font-size: 0.9em;">';
        $output = shell_exec('sudo /usr/local/bin/fix_audio.py 2>&1');
        echo nl2br(htmlspecialchars($output));
        echo '</div>';
    }
    ?>
</div>

<h3><?php echo $TA[$lang]['mixer_title']; ?></h3>

<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="Audio">
    <div class="form-grid" style="grid-template-columns: 1fr 1fr;">

        <div class="audio-card highlight">
            <h4 class="audio-title green"><?php echo $TA[$lang]['rx_title']; ?></h4>
            
            <div class="switch-row">
                <div class="switch-label"><?php echo $TA[$lang]['lbl_mic_cap']; ?></div>
                <input type="checkbox" name="Mic_Cap_Sw" value="1" <?php if($audio['Mic_Cap_Sw']) echo "checked"; ?>>
            </div>

            <div class="slider-group">
                <div class="slider-header">
                    <span><?php echo $TA[$lang]['lbl_mic_vol']; ?></span>
                    <span class="slider-val"><span id="v_rx"><?php echo $audio['Mic_Cap_Vol']; ?></span>/35</span>
                </div>
                <input type="range" name="mic_cap_vol" min="0" max="35" value="<?php echo $audio['Mic_Cap_Vol']; ?>" oninput="document.getElementById('v_rx').innerText=this.value">
            </div>

            <div class="switch-row">
                <div class="switch-label"><?php echo $TA[$lang]['lbl_agc']; ?></div>
                <input type="checkbox" name="Auto_Gain_Ctrl" value="1" <?php if($audio['Auto_Gain_Ctrl']) echo "checked"; ?>>
            </div>
            <small style="color:#FF9800;"><?php echo $TA[$lang]['warn_agc']; ?></small>
        </div>

        <div class="audio-card highlight" style="border-color:#2196F3;">
            <h4 class="audio-title" style="color:#2196F3;"><?php echo $TA[$lang]['tx_title']; ?></h4>
            
            <div class="switch-row">
                <div class="switch-label"><?php echo $TA[$lang]['lbl_spk_play']; ?></div>
                <input type="checkbox" name="Spk_Play_Sw" value="1" <?php if($audio['Spk_Play_Sw']) echo "checked"; ?>>
            </div>

            <div class="slider-group">
                <div class="slider-header">
                    <span><?php echo $TA[$lang]['lbl_spk_vol']; ?></span>
                    <span class="slider-val"><span id="v_tx"><?php echo $audio['Spk_Play_Vol']; ?></span>/37</span>
                </div>
                <input type="range" name="spk_play_vol" min="0" max="37" value="<?php echo $audio['Spk_Play_Vol']; ?>" oninput="document.getElementById('v_tx').innerText=this.value">
            </div>
        </div>

    </div>
    <button type="submit" name="save_audio" class="btn btn-green"><?php echo $TA[$lang]['btn_save']; ?></button>
</form>
