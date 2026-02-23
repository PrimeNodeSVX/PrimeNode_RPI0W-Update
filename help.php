<?php
$H = [
    'pl' => [
        'title' => 'Centrum Dowodzenia i Pomocy (SQLink RPi Zero Edition)',
        'subtitle' => 'System zaprojektowany wyłącznie dla:',
        's1_title' => '1. Twój Kokpit (Dashboard)',
        's1_text' => 'To tutaj sprawdzasz puls swojego urządzenia. Wszystko powinno świecić na zielono!',
        's1_msg' => '📢 Pasek Komunikatów:',
        's1_msg_d' => 'Jeśli na samej górze strony zobaczysz niebieski pasek z tekstem, to <strong>ważna wiadomość od Administratora</strong> (np. o dostępnej aktualizacji, awarii sieci lub pracach technicznych).',
        's1_stat' => '🚦 Pasek Statusu:',
        's1_stat_d' => 'To ten kolorowy pasek pod nagłówkiem. Jeśli jest <span style="color:#4CAF50; font-weight:bold;">ZIELONY</span>, system działa. Jeśli <span style="color:#F44336; font-weight:bold;">CZERWONY</span>, coś się popsuło (zrób restart w zakładce Zasilanie).',
        's1_temp' => '🌡️ Temperatura:',
        's1_temp_d' => 'Raspberry Pi Zero to mały twardziel, ale nie lubi upałów.',
        's1_temp_ok' => '✅ 30°C - 55°C: Jest super!',
        's1_temp_hot' => '🔥 > 70°C: Za gorąco! Zapewnij malinie trochę powietrza.',
        's1_mon' => '📺 Wielki Monitor (Live):',
        's1_mon_d' => 'Tu widzisz, co się dzieje w eterze:',
        's1_mon_stby' => '⚪ <strong>Cisza (Standby):</strong> Nikt nie gada, nuda.',
        's1_mon_rx' => '🟢 <span style="color:#4CAF50; font-weight:bold;">ODBIERANIE (RX):</span> Ty mówisz do radia (Hotspot Cię słyszy).',
        's1_mon_tx' => '🟠 <span style="color:#FF9800; font-weight:bold;">NADAWANIE (TX):</span> Ktoś mówi z internetu (Słyszysz to w radiu).',
        
        's2_title' => '2. Tryb Ratunkowy WiFi (Ważne!)',
        's2_text' => 'Twoje Raspberry Pi Zero W nie ma gniazda Ethernet, więc co zrobić, gdy zmienisz router lub pójdziesz z nim w teren?',
        's2_box_title' => '🚨 Jak odzyskać łączność bez monitora?',
        's2_step1' => '1. Włącz Hotspota tam, gdzie nie ma Twojej domowej sieci WiFi.',
        's2_step2' => '2. Poczekaj cierpliwie około <strong>2 minuty</strong> (system musi "zrozumieć", że nie ma internetu).',
        's2_step3' => '3. Hotspot automatycznie stworzy własną sieć WiFi!',
        's2_ssid' => '📱 <strong>Szukaj sieci (SSID):</strong>',
        's2_pass' => '🔐 <strong>Hasło:</strong>',
        's2_addr' => '🌐 <strong>Adres strony:</strong>',
        's2_end' => 'Połącz się telefonem, wejdź na ten adres, skonfiguruj nowe WiFi w zakładce "WiFi" i zrób Restart. Gotowe!',

        's3_title' => '3. Dwa Światy: Reflektor i EchoLink',
        's3_text' => 'Pamiętaj: Możesz być tylko w jednym miejscu naraz!',
        's3_a_title' => '🅰️ Świat A: Reflektor (SQLink)',
        's3_a_desc' => 'To jest Twój "dom". Jesteś tu zawsze po uruchomieniu.<br>Rozmawiasz z polskimi stacjami na grupach (np. Ogólnopolska).',
        's3_b_title' => '🅱️ Świat B: EchoLink (Światowy)',
        's3_b_desc' => 'Chcesz pogadać z kimś z USA, Japonii czy innego miasta?',
        's3_b_step1' => '1. Wejdź w zakładkę EchoLink.',
        's3_b_step2' => '2. Wybierz numer węzła i kliknij <strong>📞 Połącz</strong>.',
        's3_warn' => '🛑 <strong>BARDZO WAŻNE:</strong> Kiedy skończysz rozmawiać, <strong>MUSISZ SIĘ ROZŁĄCZYĆ!</strong>',
        's3_disc' => '👉 Kliknij przycisk <span style="color:#F44336; font-weight:bold;">📵 Rozłącz (#)</span> lub wpisz <strong>#</strong> na klawiaturze radia.<br>Dopiero gdy usłyszysz "Deactivating module EchoLink", wracasz do polskiej sieci.',

        's4_title' => '4. Zakładka DTMF (Pilot)',
        's4_text' => 'Tutaj sterujesz hotspotem bez dotykania mikrofonu radia.',
        's4_tg' => '<strong>👥 Grupy Rozmowne:</strong> Kliknięcie kafelka (np. TG 260) natychmiast przełącza Cię na tę grupę.',
        's4_info' => '<strong>ℹ️ Tryb Info / Status (*#):</strong> Kliknij przycisk <strong>Status</strong> lub wklep kod <code style="background:#333; padding:2px 5px; border-radius:3px;">*#</code> na radiu. Hotspot przemówi do Ciebie i poda: aktualną godzinę, swój adres IP oraz status logowania.',
        's4_parrot' => '<strong>🦜 Papuga (Test Audio):</strong> Narzędzie do sprawdzania, jak Cię słychać.',
        's4_key' => '<strong>⌨️ Klawiatura:</strong> Pozwala wpisać dowolny kod DTMF (np. ukryte funkcje SVXLink).',

        's5_title' => '5. Audio (CM108) - Nie kręć bez potrzeby!',
        's5_text' => 'Suwaki w zakładce Audio są bardzo czułe i skalibrowane pod karty CM108.',
        's5_diag' => '<strong>🎧 Diagnostyka CM108:</strong> Na górze zakładki Audio jest niebieski panel.',
        's5_diag_d' => '👉 Użyj go, jeśli po zmianie sprzętu lub wgraniu kopii zapasowej <strong>nie masz dźwięku</strong>.<br>Kliknij <strong>🔍 Znajdź i Napraw Audio</strong> - system sam ustawi odpowiedni port USB dla Twojej karty.',
        's5_tx' => '<strong>📢 Suwak TX (Głośność):</strong> To jak głośno koledzy "krzyczą" z Twojego radia.',
        's5_mic' => '<strong>🎙️ Suwak MIC (Czułość):</strong> Reguluje poziom Twojego głosu wysyłanego w świat.',
        's5_knob' => '<strong>🔊 Pokrętło głośności w Radiu:</strong> W radiach Quansheng/Baofeng działa ono jak "wstępne wzmocnienie mikrofonu".',
        's5_knob_d' => '👉 Ustaw je raz na ok. 1/3 zakresu i staraj się go nie dotykać. Jeśli je przekręcisz na MAX, hotspot będzie "pierdział" i nikt Cię nie zrozumie!',
        's5_hint' => '💡 <strong>Testuj mądrze:</strong> Użyj funkcji <strong>🦜 Papuga</strong> w zakładce DTMF, żeby posłuchać samego siebie. To najlepszy tester!',

        's6_title' => '6. Zasilanie i Aktualizacje',
        's6_text' => 'W zakładce <strong>Zasilanie</strong> masz centrum sterowania życiem maliny.',
        's6_reb' => '<strong>🔄 Reboot / Wyłącz:</strong> Bezpieczne zamykanie systemu. Nie wyrywaj wtyczki z prądu, bo karta pamięci tego nie lubi!',
        's6_upd' => '<strong>☁️ Aktualizuj System:</strong> Kliknij zielony przycisk, żeby pobrać nowości. Hotspot sam połączy się z GitHubem i ściągnie poprawki.',
        's6_rst' => '<strong>♻️ Restart Usługi SvxLink:</strong> "Lekarstwo na wszystko". Jeśli dashboard się zawiesi albo dźwięk zniknie - kliknij to. Trwa to tylko 5-10 sekund.',

        's7_title' => '7. Wskazówki i Nowe Funkcje (Warto wiedzieć)',
        's7_text' => 'Oto kilka przydatnych funkcji, które ułatwią Ci życie z Hotspotem:',
        's7_lang' => '<strong>🌐 Zmiana Języka (PL/EN):</strong>',
        's7_lang_d' => 'Kliknij flagę w prawym górnym rogu, aby zmienić język napisów. Głos lektora (gadaczka) zmienisz w zakładce <strong>Config</strong> (Zaawansowane).',
        's7_mute' => '<strong>🔇 Cisza w Eterze (Recytacja Znaku):</strong>',
        's7_mute_d' => 'Denerwuje Cię ciągłe "Stefan Paweł..."? W zakładce <strong>Config</strong> (sekcja Zaawansowane) możesz wyłączyć opcję <strong>Recytowanie Znaku</strong>. Hotspot przestanie się przedstawiać głosowo (identyfikacja telegrafią CW pozostaje aktywna).',
        's7_gpio' => '<strong>🛠️ Własne GPIO (Dla Konstruktorów):</strong>',
        's7_gpio_d' => 'Budujesz niestandardowy interfejs? W zakładce <strong>Radio</strong> możesz teraz swobodnie zmieniać piny <strong>GPIO PTT i SQL</strong> bezpośrednio z panelu, bez edycji plików systemowych.',
        's7_card' => '<strong>🌍 Twoja Wizytówka w Sieci:</strong>',
        's7_card_d' => 'W zakładce <strong>Config</strong> uzupełnij nową sekcję <em>"Lokalizacja i Operator"</em>. Dzięki temu Twoje Imię i Miasto będą widoczne dla innych kolegów w sieci (w dymkach informacyjnych i na mapie).',
        's7_map' => '<strong>🗺️ Grid Mapper (Mapa Sieci):</strong>',
        's7_map_d' => 'W zakładce <strong>Nodes</strong> znajdziesz przycisk otwierający mapę aktywnych stacji. Możesz zmienić jej wygląd (Ciemna / Jasna / Kolorowa) w zakładce <strong>Config</strong>.',
        's7_qrz' => '<strong>🖱️ Szybki Podgląd QRZ:</strong>',
        's7_qrz_d' => 'W zakładce <strong>Nodes</strong> (Węzły) kafelki stacji są interaktywne. <strong>Kliknij w znak stacji</strong>, aby natychmiast otworzyć jej profil na QRZ.com w nowym oknie.',
        's7_mod' => '<strong>🎛️ Wygodne Moduły:</strong>',
        's7_mod_d' => 'W Konfiguracji nie musisz już wpisywać nazw modułów ręcznie. Użyj przycisków, aby włączać/wyłączać funkcje (Help, Parrot, EchoLink).<br><span style="color:#4CAF50; font-weight:bold;">Zielony</span> = Włączony, <span style="color:#666; font-weight:bold;">Szary</span> = Wyłączony.',

        'qa_title' => 'Szybka Pomoc (Q&A)',
        'qa_q1' => '❓ Wgrałem backup na inną kartę i nie mam dźwięku!',
        'qa_a1' => '✅ Spokojnie! Każde RPi Zero może inaczej zaindeksować kartę USB. Wejdź w zakładkę <strong>Audio</strong> i kliknij niebieski przycisk <strong>🔍 Znajdź i Napraw Audio</strong>. System znajdzie Twoją kartę CM108.',
        'qa_q2' => '❓ EchoLink nie łączy (Status: Disconnected).',
        'qa_a2' => '✅ Masz internet z telefonu (LTE)? Operatorzy często blokują porty. Wejdź w zakładkę <strong>Config</strong> i kliknij zielony przycisk <strong>♻️ Auto-Proxy</strong>. To "magiczny przycisk", który omija blokady.',
        'qa_q3' => '❓ Radio milczy, a na ekranie widać, że ktoś gada (RX).',
        'qa_a3' => '✅ Sprawdź w swoim radiu ręcznym kody <strong>CTCSS / Tone Squelch</strong>. Muszą być identyczne jak w ustawieniach Hotspota (zakładka Radio). Najlepiej na początek wyłącz kody w radiu i w Hotspocie (ustaw 0).',
        'qa_q4' => '❓ W logach widzę "Distortion detected".',
        'qa_a4' => '✅ Twoje radio krzyczy do Hotspota za głośno! Ścisz radio (jeśli masz kabel) lub zmniejsz suwak <strong>MIC / ADC Gain</strong> w zakładce Audio.'
    ],
    'en' => [
        'title' => 'Command & Help Center (SQLink RPi Zero Edition)',
        'subtitle' => 'System designed exclusively for:',
        's1_title' => '1. Your Dashboard',
        's1_text' => 'This is where you check the pulse of your device. Everything should be green!',
        's1_msg' => '📢 Message Bar:',
        's1_msg_d' => 'If you see a blue bar with text at the very top, it is an <strong>important message from the Administrator</strong> (e.g., about updates, network failure, or maintenance).',
        's1_stat' => '🚦 Status Bar:',
        's1_stat_d' => 'The colorful bar below the header. If it is <span style="color:#4CAF50; font-weight:bold;">GREEN</span>, the system works. If <span style="color:#F44336; font-weight:bold;">RED</span>, something is broken (try restarting in the Power tab).',
        's1_temp' => '🌡️ Temperature:',
        's1_temp_d' => 'Raspberry Pi Zero is a tough cookie, but it hates heat.',
        's1_temp_ok' => '✅ 30°C - 55°C: Perfect!',
        's1_temp_hot' => '🔥 > 70°C: Too hot! Give the Pi some air.',
        's1_mon' => '📺 Big Monitor (Live):',
        's1_mon_d' => 'Here you see what is happening on air:',
        's1_mon_stby' => '⚪ <strong>Silence (Standby):</strong> No one is talking, boring.',
        's1_mon_rx' => '🟢 <span style="color:#4CAF50; font-weight:bold;">RECEIVING (RX):</span> You are talking to the radio (Hotspot hears you).',
        's1_mon_tx' => '🟠 <span style="color:#FF9800; font-weight:bold;">TRANSMITTING (TX):</span> Someone is talking from the internet (You hear it on the radio).',
        
        's2_title' => '2. WiFi Rescue Mode (Important!)',
        's2_text' => 'Your Raspberry Pi Zero W has no Ethernet port, so what if you change router or go outdoors?',
        's2_box_title' => '🚨 How to regain connection without a monitor?',
        's2_step1' => '1. Turn on the Hotspot where your home WiFi is unavailable.',
        's2_step2' => '2. Wait patiently for about <strong>2 minutes</strong> (system must "realize" there is no internet).',
        's2_step3' => '3. The Hotspot will automatically create its own WiFi network!',
        's2_ssid' => '📱 <strong>Look for SSID:</strong>',
        's2_pass' => '🔐 <strong>Password:</strong>',
        's2_addr' => '🌐 <strong>Web Address:</strong>',
        's2_end' => 'Connect with your phone, go to this address, configure new WiFi in the "WiFi" tab and Restart. Done!',

        's3_title' => '3. Two Worlds: Reflector & EchoLink',
        's3_text' => 'Remember: You can only be in one place at a time!',
        's3_a_title' => '🅰️ World A: Reflector (SQLink)',
        's3_a_desc' => 'This is your "home". You are always here after startup.<br>You talk to stations on Talkgroups.',
        's3_b_title' => '🅱️ World B: EchoLink (Global)',
        's3_b_desc' => 'Want to talk to someone from USA, Japan, or another city?',
        's3_b_step1' => '1. Go to the EchoLink tab.',
        's3_b_step2' => '2. Select a node number and click <strong>📞 Connect</strong>.',
        's3_warn' => '🛑 <strong>VERY IMPORTANT:</strong> When finished, <strong>YOU MUST DISCONNECT!</strong>',
        's3_disc' => '👉 Click <span style="color:#F44336; font-weight:bold;">📵 Disconnect (#)</span> or type <strong>#</strong> on your radio keypad.<br>Only when you hear "Deactivating module EchoLink", you return to the main network.',

        's4_title' => '4. DTMF Tab (Remote)',
        's4_text' => 'Control the hotspot without touching the radio microphone.',
        's4_tg' => '<strong>👥 Talkgroups:</strong> Clicking a tile (e.g., TG 260) instantly switches you to that group.',
        's4_info' => '<strong>ℹ️ Info / Status Mode (*#):</strong> Click <strong>Status</strong> or type <code style="background:#333; padding:2px 5px; border-radius:3px;">*#</code> on radio. Hotspot will speak to you: time, IP address, and login status.',
        's4_parrot' => '<strong>🦜 Parrot (Audio Test):</strong> Tool to check how you sound.',
        's4_key' => '<strong>⌨️ Keypad:</strong> Allows typing any DTMF code (e.g., hidden SVXLink functions).',

        's5_title' => '5. Audio (CM108) - Do not tweak unnecessarily!',
        's5_text' => 'Sliders in the Audio tab are very sensitive and calibrated for CM108 cards.',
        's5_diag' => '<strong>🎧 CM108 Diagnostics:</strong> Blue panel at the top of Audio tab.',
        's5_diag_d' => '👉 Use it if you have <strong>no sound</strong> after changing hardware or restoring backup.<br>Click <strong>🔍 Find & Fix Audio</strong> - system will set the correct USB port for your card.',
        's5_tx' => '<strong>📢 TX Slider (Volume):</strong> How loud colleagues "shout" from your radio.',
        's5_mic' => '<strong>🎙️ MIC Slider (Sensitivity):</strong> Regulates your voice level sent to the world.',
        's5_knob' => '<strong>🔊 Radio Volume Knob:</strong> On Quansheng/Baofeng radios, it acts as "pre-amp".',
        's5_knob_d' => '👉 Set it once to ~1/3 range and try not to touch it. If maxed out, hotspot audio will be distorted and unreadable!',
        's5_hint' => '💡 <strong>Test wisely:</strong> Use <strong>🦜 Parrot</strong> in DTMF tab to hear yourself. It is the best tester!',

        's6_title' => '6. Power & Updates',
        's6_text' => 'In the <strong>Power</strong> tab, you control the life of your Pi.',
        's6_reb' => '<strong>🔄 Reboot / Shutdown:</strong> Safe system shutdown. Do not pull the plug, SD card hates it!',
        's6_upd' => '<strong>☁️ Update System:</strong> Click the green button to get new features. Hotspot pulls fixes from GitHub.',
        's6_rst' => '<strong>♻️ Restart SvxLink Service:</strong> "Cure for everything". If dashboard freezes or sound is lost - click this. Takes 5-10 seconds.',

        's7_title' => '7. Tips & New Features (Good to know)',
        's7_text' => 'Here are some useful features to make your life easier:',
        's7_lang' => '<strong>🌐 Language Switch (PL/EN):</strong>',
        's7_lang_d' => 'Click the flag in the top right corner to change text language. Voice announcements (speech) are changed in the <strong>Config</strong> tab (Advanced).',
        's7_mute' => '<strong>🔇 Silence on Air (Callsign Recitation):</strong>',
        's7_mute_d' => 'Annoyed by constant "Steven Paul..."? In <strong>Config</strong> (Advanced section) you can disable <strong>Callsign Recitation</strong>. Hotspot stops introducing itself verbally (CW ID remains active).',
        's7_gpio' => '<strong>🛠️ Custom GPIO (For Builders):</strong>',
        's7_gpio_d' => 'Building a custom interface? In <strong>Radio</strong> tab you can now freely change <strong>GPIO PTT & SQL</strong> pins directly from the panel, without editing system files.',
        's7_card' => '<strong>🌍 Your Network Card:</strong>',
        's7_card_d' => 'In <strong>Config</strong> fill in the new section <em>"Location & Operator"</em>. Your Name and City will be visible to other colleagues on the network (in info bubbles and map).',
        's7_map' => '<strong>🗺️ Grid Mapper (Network Map):</strong>',
        's7_map_d' => 'In <strong>Nodes</strong> tab find the button opening the map of active stations. You can change its look (Dark / Light / Color) in <strong>Config</strong>.',
        's7_qrz' => '<strong>🖱️ Quick QRZ Preview:</strong>',
        's7_qrz_d' => 'In <strong>Nodes</strong> tab, station tiles are interactive. <strong>Click the callsign</strong> to instantly open its QRZ.com profile in a new window.',
        's7_mod' => '<strong>🎛️ Easy Modules:</strong>',
        's7_mod_d' => 'In Config you don\'t have to type module names manually. Use buttons to toggle functions (Help, Parrot, EchoLink).<br><span style="color:#4CAF50; font-weight:bold;">Green</span> = ON, <span style="color:#666; font-weight:bold;">Gray</span> = OFF.',

        'qa_title' => 'Quick Help (Q&A)',
        'qa_q1' => '❓ Restored backup to another card and no sound!',
        'qa_a1' => '✅ Relax! Every RPi Zero might index USB cards differently. Go to <strong>Audio</strong> tab and click blue button <strong>🔍 Find & Fix Audio</strong>. System will find your CM108.',
        'qa_q2' => '❓ EchoLink not connecting (Status: Disconnected).',
        'qa_a2' => '✅ Using mobile internet (LTE)? Carriers often block ports. Go to <strong>Config</strong> tab and click green button <strong>♻️ Auto-Proxy</strong>. It is a "magic button" that bypasses blocks.',
        'qa_q3' => '❓ Radio is silent, but screen shows RX.',
        'qa_a3' => '✅ Check your handheld radio <strong>CTCSS / Tone Squelch</strong> codes. They must match Hotspot settings (Radio tab). Best to disable codes on both radio and Hotspot initially (set 0).',
        'qa_q4' => '❓ Logs show "Distortion detected".',
        'qa_a4' => '✅ Your radio is shouting too loud! Turn down volume knob (if using cable) or decrease <strong>MIC / ADC Gain</strong> slider in Audio tab.'
    ]
];
?>
<h3>🎓 <?php echo $H[$lang]['title']; ?></h3>
<div style="text-align: center; margin-bottom: 20px; font-size: 0.9em; color: #888; background: #222; padding: 5px; border-radius: 4px; border: 1px solid #444;">
    ℹ️ <?php echo $H[$lang]['subtitle']; ?> <strong style="color: #4CAF50;">Raspberry Pi Zero W</strong> + Card <strong style="color: #2196F3;">CM108 USB</strong>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🖥️</span> <?php echo $H[$lang]['s1_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s1_text']; ?>
        <ul>
            <li style="margin-bottom: 5px;"><strong><?php echo $H[$lang]['s1_msg']; ?></strong> <?php echo $H[$lang]['s1_msg_d']; ?></li>
            <li><strong><?php echo $H[$lang]['s1_stat']; ?></strong> <?php echo $H[$lang]['s1_stat_d']; ?></li>
            <li><strong><?php echo $H[$lang]['s1_temp']; ?></strong> <?php echo $H[$lang]['s1_temp_d']; ?>
                <br><small><?php echo $H[$lang]['s1_temp_ok']; ?><br><?php echo $H[$lang]['s1_temp_hot']; ?></small>
            </li>
            <li><strong><?php echo $H[$lang]['s1_mon']; ?></strong> <?php echo $H[$lang]['s1_mon_d']; ?>
                <ul>
                    <li><?php echo $H[$lang]['s1_mon_stby']; ?></li>
                    <li><?php echo $H[$lang]['s1_mon_rx']; ?></li>
                    <li><?php echo $H[$lang]['s1_mon_tx']; ?></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<div class="help-section" style="border-left: 5px solid #FF9800;">
    <div class="help-title"><span class="help-icon">🆘</span> <?php echo $H[$lang]['s2_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s2_text']; ?>
        <br><br>
        <div style="border: 3px solid #FF9800; padding: 15px; border-radius: 8px;">
            <strong><?php echo $H[$lang]['s2_box_title']; ?></strong><br><br>
            <?php echo $H[$lang]['s2_step1']; ?><br>
            <?php echo $H[$lang]['s2_step2']; ?><br>
            <?php echo $H[$lang]['s2_step3']; ?><br><br>
            <?php echo $H[$lang]['s2_ssid']; ?> <span style="color:#FF9800; font-size:1.1em; font-weight:bold;">SQLink_WiFi_AP</span><br>
            <?php echo $H[$lang]['s2_pass']; ?> <code>sqlink123</code><br>
            <?php echo $H[$lang]['s2_addr']; ?> <a href="http://192.168.4.1" target="_blank" style="color:#FF9800; font-weight:bold;">192.168.4.1</a><br><br>
            <?php echo $H[$lang]['s2_end']; ?>
        </div>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🔄</span> <?php echo $H[$lang]['s3_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s3_text']; ?>
        <div class="help-step">
            <strong><?php echo $H[$lang]['s3_a_title']; ?></strong><br>
            <?php echo $H[$lang]['s3_a_desc']; ?>
        </div>
        <div class="help-step" style="border-left-color: #2196F3;">
            <strong><?php echo $H[$lang]['s3_b_title']; ?></strong><br>
            <?php echo $H[$lang]['s3_b_desc']; ?><br>
            <?php echo $H[$lang]['s3_b_step1']; ?><br>
            <?php echo $H[$lang]['s3_b_step2']; ?><br>
            <hr style="border: 0; border-top: 1px dashed #ccc; margin: 10px 0;">
            <?php echo $H[$lang]['s3_warn']; ?><br>
            <?php echo $H[$lang]['s3_disc']; ?>
        </div>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">📱</span> <?php echo $H[$lang]['s4_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s4_text']; ?>
        <ul>
            <li><?php echo $H[$lang]['s4_tg']; ?></li>
            <li><?php echo $H[$lang]['s4_info']; ?></li>
            <li><?php echo $H[$lang]['s4_parrot']; ?></li>
            <li><?php echo $H[$lang]['s4_key']; ?></li>
        </ul>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🎚️</span> <?php echo $H[$lang]['s5_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s5_text']; ?>
        <ul>
            <li><?php echo $H[$lang]['s5_diag']; ?> 
                <br><?php echo $H[$lang]['s5_diag_d']; ?>
            </li>
            <li><?php echo $H[$lang]['s5_tx']; ?></li>
            <li><?php echo $H[$lang]['s5_mic']; ?></li>
            <li><?php echo $H[$lang]['s5_knob']; ?>
                <br><small><?php echo $H[$lang]['s5_knob_d']; ?></small>
            </li>
        </ul>
        <div class="help-warn">
            <?php echo $H[$lang]['s5_hint']; ?>
        </div>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">⚡</span> <?php echo $H[$lang]['s6_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s6_text']; ?>
        <ul>
            <li><?php echo $H[$lang]['s6_reb']; ?></li>
            <li><?php echo $H[$lang]['s6_upd']; ?></li>
            <li><?php echo $H[$lang]['s6_rst']; ?></li>
        </ul>
    </div>
</div>

<div class="help-section">
    <div class="help-title" style="color: #BA68C8;"><span class="help-icon">💡</span> <?php echo $H[$lang]['s7_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s7_text']; ?>
        <ul>
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s7_lang']; ?>
                <br><?php echo $H[$lang]['s7_lang_d']; ?>
            </li>
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s7_mute']; ?>
                <br><?php echo $H[$lang]['s7_mute_d']; ?>
            </li>
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s7_gpio']; ?>
                <br><?php echo $H[$lang]['s7_gpio_d']; ?>
            </li>
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s7_card']; ?>
                <br><?php echo $H[$lang]['s7_card_d']; ?>
            </li>
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s7_map']; ?>
                <br><?php echo $H[$lang]['s7_map_d']; ?>
            </li>
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s7_qrz']; ?>
                <br><?php echo $H[$lang]['s7_qrz_d']; ?>
            </li>
            <li><?php echo $H[$lang]['s7_mod']; ?>
                <br><?php echo $H[$lang]['s7_mod_d']; ?>
            </li>
        </ul>
    </div>
</div>

<div class="help-section" style="border:none;">
    <div class="help-title"><span class="help-icon">🔧</span> <?php echo $H[$lang]['qa_title']; ?></div>
    <div class="help-text">
        <strong><?php echo $H[$lang]['qa_q1']; ?></strong><br>
        <?php echo $H[$lang]['qa_a1']; ?><br><br>
        <strong><?php echo $H[$lang]['qa_q2']; ?></strong><br>
        <?php echo $H[$lang]['qa_a2']; ?><br><br>
        <strong><?php echo $H[$lang]['qa_q3']; ?></strong><br>
        <?php echo $H[$lang]['qa_a3']; ?><br><br>
        <strong><?php echo $H[$lang]['qa_q4']; ?></strong><br>
        <?php echo $H[$lang]['qa_a4']; ?>
    </div>
</div>