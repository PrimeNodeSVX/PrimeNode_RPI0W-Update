<?php echo isset($audio_msg) ? $audio_msg : ''; ?>

<?php
$TA = [
    'pl' => [
        'info' => 'Karta Audio:',
        'cm108' => 'Karta USB (CM108/Generic)',
        'sa818' => 'Wbudowane Audio (SA818)',
        'btn_save' => '💾 Zapisz Ustawienia Audio',
        'vol' => 'Głośność i Wzmocnienie',
        'sw' => 'Przełączniki i Opcje'
    ],
    'en' => [
        'info' => 'Audio Card:',
        'cm108' => 'USB Card (CM108/Generic)',
        'sa818' => 'Built-in Audio (SA818)',
        'btn_save' => '💾 Save Audio Settings',
        'vol' => 'Volume & Gain',
        'sw' => 'Switches & Options'
    ]
];

$mode_label = ($radio_type == 'sa818') ? $TA[$lang]['sa818'] : $TA[$lang]['cm108'];
?>

<div style="background:#222; padding:10px; margin-bottom:15px; border-left:4px solid #4CAF50; display:flex; justify-content:space-between; align-items:center;">
    <div>
        <strong style="color:#aaa;"><?php echo $TA[$lang]['info']; ?></strong> 
        <span style="color:#fff; font-weight:bold; margin-left:5px;"><?php echo $mode_label; ?></span>
    </div>
    <div style="font-size:12px; color:#888;">
        ID: <?php echo $CARD_ID; ?> (alsa:plughw:<?php echo $CARD_ID; ?>)
    </div>
</div>

<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="Audio">
    
    <div class="form-grid-layout"> <div class="audio-card highlight box-full">
            <h4 class="audio-title green"><?php echo $TA[$lang]['vol']; ?></h4>
            
            <?php if(isset($current_map['sliders']) && !empty($current_map['sliders'])): ?>
                <?php foreach($current_map['sliders'] as $key => $cfg): ?>
                    <div class="slider-group">
                        <div class="slider-header">
                            <span><?php echo $cfg['name']; ?></span>
                            <span class="slider-val"><span id="v_<?php echo $key; ?>"><?php echo isset($audio_vals[$key]) ? $audio_vals[$key] : 0; ?></span>%</span>
                        </div>
                        <input type="range" name="<?php echo $key; ?>" min="0" max="100" value="<?php echo isset($audio_vals[$key]) ? $audio_vals[$key] : 0; ?>" oninput="document.getElementById('v_<?php echo $key; ?>').innerText=this.value">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="padding:10px; color:#777; text-align:center;">Brak suwaków dla tego urządzenia.</div>
            <?php endif; ?>
        </div>

        <div class="audio-card highlight box-full" style="border-color:#2196F3;">
            <h4 class="audio-title" style="color:#2196F3;"><?php echo $TA[$lang]['sw']; ?></h4>
            
            <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                <?php if(isset($current_map['switches'])): foreach($current_map['switches'] as $key => $cfg): ?>
                    <div class="switch-row" style="flex:1; min-width:140px;">
                        <div class="switch-label"><?php echo $cfg['name']; ?></div>
                        <input type="checkbox" name="<?php echo $key; ?>" value="1" <?php if(isset($audio_vals[$key]) && $audio_vals[$key]) echo "checked"; ?>>
                    </div>
                <?php endforeach; endif; ?>
            </div>

            <?php if(isset($current_map['enums'])): foreach($current_map['enums'] as $key => $cfg): ?>
                <div class="switch-row" style="display:block; margin-top:10px;">
                    <div class="switch-label" style="margin-bottom:5px;"><?php echo $cfg['name']; ?></div>
                    <select name="<?php echo $key; ?>" style="width:100%; padding:5px; background:#111; color:#fff; border:1px solid #444;">
                        <?php foreach($cfg['options'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php if(isset($audio_vals[$key]) && $audio_vals[$key] == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; endif; ?>
        </div>

    </div>
    
    <button type="submit" name="save_audio" class="btn btn-green" style="margin-top:20px;"><?php echo $TA[$lang]['btn_save']; ?></button>
</form>