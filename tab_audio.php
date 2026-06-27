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
        'btn_save' => 'Zapisz Ustawienia Audio',
        'lbl_preamp' => 'Sprzętowy Pre-Amp (LINPUT1)',
        'warn_preamp' => 'Zalecane: 0. Podnoszenie wzmacnia sygnał o +20dB!',
        'lbl_mic_cap_analog' => 'Czułość Analogowa (Capture)',
        'lbl_adc_vol' => 'Czułość Cyfrowa (ADC Volume)',
        'lbl_spk_play_digital' => 'Głośność Cyfrowa (PCM/DAC)',
        'lbl_hp_vol' => 'Wzmocnienie Analogowe (Out Vol)',
        'warn_hp_vol' => 'Skręć Wzmocnienie Analogowe, jeśli sygnał jest przesterowany (charczy).',
        'rf_info_title' => 'ℹ️ Informacja o konfiguracji dźwięku:',
        'rf_info_p1' => 'Dashboard narzuca <b>optymalne, standardowe ustawienia cyfrowe</b> zapobiegające powszechnemu przesterowaniu płytki RF Guru. Powyższe suwaki to punkt wyjścia – każdy użytkownik powinien dostroić je pod czułość swojego radiotelefonu.',
        'rf_info_p2' => 'Dodatkowe opcje sprzętowe układu (Bramki Szumów, Bypass, Limiter ALC) są celowo ukryte w panelu. Możesz nimi zarządzać z poziomu konsoli SSH wpisując komendę:'
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
        'btn_save' => 'Save Audio Settings',
        'lbl_preamp' => 'Hardware Pre-Amp (LINPUT1)',
        'warn_preamp' => 'Recommended: 0. Increasing boosts the signal by +20dB!',
        'lbl_mic_cap_analog' => 'Analog Sensitivity (Capture)',
        'lbl_adc_vol' => 'Digital Sensitivity (ADC Volume)',
        'lbl_spk_play_digital' => 'Digital Volume (PCM/DAC)',
        'lbl_hp_vol' => 'Analog Gain (Out Vol)',
        'warn_hp_vol' => 'Turn down Analog Gain if the signal is distorted (clipping).',
        'rf_info_title' => 'ℹ️ Audio Configuration Info:',
        'rf_info_p1' => 'The dashboard imposes <b>optimal, standard digital settings</b> to prevent common clipping on the RF Guru board. The sliders above are a starting point - each user should tune them to the sensitivity of their radio.',
        'rf_info_p2' => 'Additional hardware options (Noise Gates, Bypass, ALC Limiter) are intentionally hidden. You can manage them via SSH console by typing:'
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

            <?php if($is_wm8960): ?>
            <div class="slider-group" style="background: rgba(255, 152, 0, 0.1); padding: 10px; border-radius: 5px; border-left: 3px solid #FF9800;">
                <div class="slider-header">
                    <span style="color: #FF9800; font-weight: bold;"><?php echo $TA[$lang]['lbl_preamp']; ?></span>
                    <span class="slider-val"><span id="v_boost"><?php echo $audio['Mic_Boost_Vol']; ?></span>/3</span>
                </div>
                <input type="range" name="mic_boost_vol" min="0" max="3" value="<?php echo $audio['Mic_Boost_Vol']; ?>" oninput="document.getElementById('v_boost').innerText=this.value">
                <small style="color:#aaa; font-size:10px;"><?php echo $TA[$lang]['warn_preamp']; ?></small>
            </div>
            <?php endif; ?>

            <div class="slider-group">
                <div class="slider-header">
                    <span><?php echo $is_wm8960 ? $TA[$lang]['lbl_mic_cap_analog'] : $TA[$lang]['lbl_mic_vol']; ?></span>
                    <span class="slider-val"><span id="v_rx"><?php echo $audio['Mic_Cap_Vol']; ?></span>/<?php echo $max_rx; ?><?php echo $is_wm8960 ? '%' : ''; ?></span>
                </div>
                <input type="range" name="mic_cap_vol" min="0" max="<?php echo $max_rx; ?>" value="<?php echo $audio['Mic_Cap_Vol']; ?>" oninput="document.getElementById('v_rx').innerText=this.value">
            </div>

            <?php if($is_wm8960): ?>
            <div class="slider-group">
                <div class="slider-header">
                    <span><?php echo $TA[$lang]['lbl_adc_vol']; ?></span>
                    <span class="slider-val"><span id="v_adc"><?php echo $audio['ADC_Vol']; ?></span>/100%</span>
                </div>
                <input type="range" name="adc_vol" min="0" max="100" value="<?php echo $audio['ADC_Vol']; ?>" oninput="document.getElementById('v_adc').innerText=this.value">
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
                    <span><?php echo $is_wm8960 ? $TA[$lang]['lbl_spk_play_digital'] : $TA[$lang]['lbl_spk_vol']; ?></span>
                    <span class="slider-val"><span id="v_tx"><?php echo $audio['Spk_Play_Vol']; ?></span>/<?php echo $max_tx; ?><?php echo $is_wm8960 ? '%' : ''; ?></span>
                </div>
                <input type="range" name="spk_play_vol" min="0" max="<?php echo $max_tx; ?>" value="<?php echo $audio['Spk_Play_Vol']; ?>" oninput="document.getElementById('v_tx').innerText=this.value">
            </div>

            <?php if($is_wm8960): ?>
            <div class="slider-group">
                <div class="slider-header">
                    <span><?php echo $TA[$lang]['lbl_hp_vol']; ?></span>
                    <span class="slider-val"><span id="v_hp"><?php echo $audio['HP_Vol']; ?></span>/100%</span>
                </div>
                <input type="range" name="hp_vol" min="0" max="100" value="<?php echo $audio['HP_Vol']; ?>" oninput="document.getElementById('v_hp').innerText=this.value">
            </div>
            <small style="color:#2196F3; font-size:11px;"><?php echo $TA[$lang]['warn_hp_vol']; ?></small>
            <?php endif; ?>
        </div>

    </div>

    <?php if($is_wm8960): ?>
    <div style="background: rgba(33, 150, 243, 0.05); border-left: 3px solid #2196F3; padding: 12px 15px; border-radius: 4px; margin-top: 15px; margin-bottom: 20px; font-size: 13px; color: #ccc; line-height: 1.5; text-align: left;">
        <b style="color: #2196F3;"><?php echo $TA[$lang]['rf_info_title']; ?></b><br>
        <?php echo $TA[$lang]['rf_info_p1']; ?><br><br>
        <?php echo $TA[$lang]['rf_info_p2']; ?>
        <span style="color: #4CAF50; background: #111; padding: 3px 6px; border-radius: 3px; font-family: monospace; letter-spacing: 1px;">alsamixer</span>
    </div>
    <?php endif; ?>

    <button type="submit" name="save_audio" class="btn btn-green"><?php echo $TA[$lang]['btn_save']; ?></button>
</form>