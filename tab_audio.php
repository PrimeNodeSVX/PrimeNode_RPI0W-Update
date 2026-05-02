<?php echo isset($audio_msg) ? $audio_msg : ''; ?>

<?php
$TA = [
    'pl' => [
        'mixer_title' => 'Mikser Audio',
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
        'mixer_title' => 'Audio Mixer',
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
