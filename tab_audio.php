<?php echo isset($audio_msg) ? $audio_msg : ''; ?>

<?php
$TA = [
    'pl' => [
        'mode_info' => 'Tryb Audio:',
        'cm108' => 'Prosty (CM108)',
        'sa818' => 'Rozszerzony (SA818/Allwinner)',
        'btn_save' => 'Zapisz Ustawienia Audio',
        'vol' => 'Głośność',
        'sw' => 'Przełącznik'
    ],
    'en' => [
        'mode_info' => 'Audio Mode:',
        'cm108' => 'Simple (CM108)',
        'sa818' => 'Extended (SA818/Allwinner)',
        'btn_save' => 'Save Audio Settings',
        'vol' => 'Volume',
        'sw' => 'Switch'
    ]
];

$mode_label = ($radio_type == 'sa818') ? $TA[$lang]['sa818'] : $TA[$lang]['cm108'];
?>

<div style="background:#222; padding:10px; margin-bottom:15px; border-left:4px solid #4CAF50;">
    <strong style="color:#aaa;"><?php echo $TA[$lang]['mode_info']; ?></strong> 
    <span style="color:#fff; font-weight:bold; margin-left:5px;"><?php echo $mode_label; ?></span>
    <span style="float:right; font-size:12px; color:#888;">Card: <?php echo $CARD_ID; ?></span>
</div>

<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="Audio">
    
    <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap:20px;">
        
        <div class="audio-card highlight">
            <h4 class="audio-title green"><?php echo $TA[$lang]['vol']; ?></h4>
            <?php if(isset($current_map['sliders'])): foreach($current_map['sliders'] as $key => $cfg): ?>
                <div class="slider-group">
                    <div class="slider-header">
                        <span><?php echo $cfg['name']; ?></span>
                        <span class="slider-val"><span id="v_<?php echo $key; ?>"><?php echo $audio_vals[$key]; ?></span>%</span>
                    </div>
                    <input type="range" name="<?php echo $key; ?>" min="0" max="100" value="<?php echo $audio_vals[$key]; ?>" oninput="document.getElementById('v_<?php echo $key; ?>').innerText=this.value">
                </div>
            <?php endforeach; endif; ?>
        </div>

        <div class="audio-card highlight" style="border-color:#2196F3;">
            <h4 class="audio-title" style="color:#2196F3;"><?php echo $TA[$lang]['sw']; ?></h4>
            
            <?php if(isset($current_map['switches'])): foreach($current_map['switches'] as $key => $cfg): ?>
                <div class="switch-row">
                    <div class="switch-label"><?php echo $cfg['name']; ?></div>
                    <input type="checkbox" name="<?php echo $key; ?>" value="1" <?php if($audio_vals[$key]) echo "checked"; ?>>
                </div>
            <?php endforeach; endif; ?>

            <?php if(isset($current_map['enums'])): foreach($current_map['enums'] as $key => $cfg): ?>
                <div class="switch-row" style="display:block;">
                    <div class="switch-label" style="margin-bottom:5px;"><?php echo $cfg['name']; ?></div>
                    <select name="<?php echo $key; ?>" style="width:100%; padding:5px; background:#111; color:#fff; border:1px solid #444;">
                        <?php foreach($cfg['options'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php if($audio_vals[$key] == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; endif; ?>
        </div>

    </div>
    
    <button type="submit" name="save_audio" class="btn btn-green" style="margin-top:20px;"><?php echo $TA[$lang]['btn_save']; ?></button>
</form>