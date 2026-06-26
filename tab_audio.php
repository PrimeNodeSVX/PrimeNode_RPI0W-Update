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
            
            <div class="switch-row" <?php if(!isset($MIXER_IDS['Mic_Cap_Sw'])) echo 'style="opacity:0.3; pointer-events:none;"'; ?>>
                <div class="switch-label"><?php echo $TA[$lang]['lbl_mic_cap']; ?></div>
                <input type="checkbox" name="Mic_Cap_Sw" value="1" <?php if(isset($audio['Mic_Cap_Sw']) && $audio['Mic_Cap_Sw']) echo "checked"; ?>>
            </div>

            <div class="slider-group">
                <div class="slider-header">
                    <span><?php echo $is_wm8960 ? "Czułość Analogowa (Pre-Amp)" : $TA[$lang]['lbl_mic_vol']; ?></span>
                    <span class="slider-val"><span id="v_rx"><?php echo $audio['Mic_Cap_Vol']; ?></span>/<?php echo $max_rx; ?></span>
                </div>
                <input type="range" name="mic_cap_vol" min="0" max="<?php echo $max_rx; ?>" value="<?php echo $audio['Mic_Cap_Vol']; ?>" oninput="document.getElementById('v_rx').innerText=this.value">
            </div>

            <?php if($is_wm8960): ?>
            <div class="slider-group">
                <div class="slider-header">
                    <span>Czułość Cyfrowa (ADC Volume)</span>
                    <span class="slider-val"><span id="v_adc"><?php echo $audio['ADC_Vol']; ?></span>/255</span>
                </div>
                <input type="range" name="adc_vol" min="0" max="255" value="<?php echo $audio['ADC_Vol']; ?>" oninput="document.getElementById('v_adc').innerText=this.value">
            </div>
            <?php else: ?>
            <div class="switch-row" <?php if(!isset($MIXER_IDS['Auto_Gain_Ctrl'])) echo 'style="opacity:0.3; pointer-events:none;"'; ?>>
                <div class="switch-label"><?php echo $TA[$lang]['lbl_agc']; ?></div>
                <input type="checkbox" name="Auto_Gain_Ctrl" value="1" <?php if(isset($audio['Auto_Gain_Ctrl']) && $audio['Auto_Gain_Ctrl']) echo "checked"; ?>>
            </div>
            <small style="color:#FF9800;"><?php echo $TA[$lang]['warn_agc']; ?></small>
            <?php endif; ?>
        </div>

        <div class="audio-card highlight" style="border-color:#2196F3;">
            <h4 class="audio-title" style="color:#2196F3;"><?php echo $TA[$lang]['tx_title']; ?></h4>
            
            <?php if(!$is_wm8960): ?>
            <div class="switch-row" <?php if(!isset($MIXER_IDS['Spk_Play_Sw'])) echo 'style="opacity:0.3; pointer-events:none;"'; ?>>
                <div class="switch-label"><?php echo $TA[$lang]['lbl_spk_play']; ?></div>
                <input type="checkbox" name="Spk_Play_Sw" value="1" <?php if(isset($audio['Spk_Play_Sw']) && $audio['Spk_Play_Sw']) echo "checked"; ?>>
            </div>
            <?php endif; ?>

            <div class="slider-group">
                <div class="slider-header">
                    <span><?php echo $is_wm8960 ? "Głośność Cyfrowa (PCM/DAC)" : $TA[$lang]['lbl_spk_vol']; ?></span>
                    <span class="slider-val"><span id="v_tx"><?php echo $audio['Spk_Play_Vol']; ?></span>/<?php echo $max_tx; ?></span>
                </div>
                <input type="range" name="spk_play_vol" min="0" max="<?php echo $max_tx; ?>" value="<?php echo $audio['Spk_Play_Vol']; ?>" oninput="document.getElementById('v_tx').innerText=this.value">
            </div>

            <?php if($is_wm8960): ?>
            <div class="slider-group">
                <div class="slider-header">
                    <span>Wzmocnienie Analogowe (Out Vol)</span>
                    <span class="slider-val"><span id="v_hp"><?php echo $audio['HP_Vol']; ?></span>/127</span>
                </div>
                <input type="range" name="hp_vol" min="0" max="127" value="<?php echo $audio['HP_Vol']; ?>" oninput="document.getElementById('v_hp').innerText=this.value">
            </div>
            <small style="color:#2196F3; font-size:11px;">Skręć Wzmocnienie Analogowe, jeśli sygnał jest przesterowany (charczy).</small>
            <?php endif; ?>
        </div>

    </div>
    <button type="submit" name="save_audio" class="btn btn-green"><?php echo $TA[$lang]['btn_save']; ?></button>
</form>
