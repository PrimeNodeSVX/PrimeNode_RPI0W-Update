<?php
$H = [
    'pl' => [
        'title' => 'Centrum Sterowania PrimeNode by SQ7UTP',
        'subtitle' => 'Uniwersalny system dla platform:',
        'hw_desc' => '<strong style="color: #4CAF50;">Raspberry Pi (Zero W, 2W, 3, 3B, 3B+, 4)</strong>',
        
        's1_title' => '1. Twój Kokpit (Dashboard)',
        's1_text' => 'To serce Twojego urządzenia, gdzie monitorujesz parametry w czasie rzeczywistym i widzisz aktualny stan połączenia.',
        's1_msg' => '📢 Pasek Komunikatów:',
        's1_msg_d' => 'Niebieski pasek na samej górze strony to bezpośrednie wiadomości od Administratora sieci o pracach technicznych lub aktualizacjach.',
        's1_stat' => '🚦 Pasek Statusu:',
        's1_stat_d' => 'Kolor pod nagłówkiem informuje o kondycji SvxLink: <span style="color:#4CAF50; font-weight:bold;">ZIELONY</span> oznacza poprawną pracę, natomiast <span style="color:#F44336; font-weight:bold;">CZERWONY</span> sygnalizuje błąd usługi.',
        's1_mon' => '📺 Monitor Live:',
        's1_mon_stby' => '⚪ <strong>Cisza (Standby):</strong> Hotspot czuwa i nasłuchuje.',
        's1_mon_rx' => '🟢 <span style="color:#4CAF50; font-weight:bold;">ODBIERANIE (RX):</span> Lokalna stacja nadaje do Hotspota (Ty mówisz).',
        's1_mon_tx' => '🟠 <span style="color:#FF9800; font-weight:bold;">NADAWANIE (TX):</span> Hotspot emituje sygnał z sieci internetowej.',
        's1_mon_info' => '✨ <strong>Inteligentne Info:</strong> System automatycznie identyfikuje rozmówcę, wyświetlając jego Imię oraz Miasto pod znakiem.',
        
        's2_title' => '2. Tryby Radiowe (Hardware)',
        's2_text' => 'PrimeNode wspiera różne konfiguracje sprzętowe, które wybierzesz w zakładce <strong>Radio</strong>:',
        's2_rfguru' => '🌟 <strong>Moduł RF Guru (I2S):</strong> Zaawansowana płytka ze sprzętowym kodekiem WM8960. Oferuje rewelacyjną jakość dźwięku. Przy pierwszej konfiguracji system przeprowadzi automatyczną, bezpieczną instalację niezbędnych sterowników.',
        's2_shari' => '🛰️ <strong>Moduł SHARI / SA818:</strong> Zintegrowane rozwiązanie radiowe. Częstotliwość, kody CTCSS oraz Squelch programujesz bezpośrednio z poziomu panelu WWW. System sam wyśle dane do modułu po kliknięciu Zapisz.',
        's2_cm108' => '📻 <strong>Karta USB (CM108) + Radio:</strong> Klasyczny tryb dla zewnętrznych radiotelefonów (np. Baofeng/Quansheng). Pozwala na użycie własnych pinów GPIO dla sygnałów PTT oraz SQL.',

        's3_title' => '3. Roaming i Zarządzanie Sieciami (Reflektor)',
        's3_text' => 'System pozwala na błyskawiczne przełączanie się między wieloma serwerami (Reflektorami).',
        's3_mgr' => '🛠️ <strong>Menedżer Sieci:</strong> W zakładce <strong>Config</strong> możesz zdefiniować własną listę serwerów, przypisując im unikalne nazwy, domyślne grupy TG oraz powitania audio.',
        's3_switch' => '📞 <strong>Przełączanie z Radia (Kod 555):</strong> Nie musisz otwierać strony WWW! Wpisz na radiu kod: <span style="color:#FF9800; font-weight:bold;">555 + ID sieci + #</span> (np. 5551# włączy serwer o ID 1). Możesz też użyć przycisku PRZEŁĄCZ na stronie.',
        's3_auto' => '♻️ <strong>Automatyzacja:</strong> Po wydaniu komendy system samodzielnie zrestartuje usługę i połączy się z nowym reflektorem, wchodząc od razu na Twoją domyślną grupę TG.',

        's4_title' => '4. Zakładka DTMF (Interaktywny Pilot)',
        's4_text' => 'Pełna wolność w sterowaniu i personalizacji interfejsu.',
        's4_move' => '✨ <strong>Drag & Drop:</strong> Możesz dowolnie zmieniać układ przycisków! Po prostu przytrzymaj kafelek myszką lub palcem i przesuń go w nowe miejsce.',
        's4_tabs' => '📂 <strong>Własne Zakładki:</strong> Twórz własne grupy przycisków (np. "Ulubione TG", "Zarządzanie").',
        's4_add' => '🎛️ <strong>Edytor:</strong> Na dole zakładki DTMF znajdziesz formularz, który pozwala dodać nowy przycisk do aktualnie wybranej listy.',

        's5_title' => '5. Terminal SSH i Administracja',
        's5_text' => 'Zarządzaj systemem bezpośrednio z przeglądarki.',
        's5_ssh' => '💻 <strong>Web Terminal:</strong> Nowoczesna konsola SSH dostępna w osobnej zakładce. Pozwala na wpisywanie komend systemowych bez użycia zewnętrznych programów.',
        's5_ssh_hint' => '💡 <strong>Pamiętaj:</strong> Uruchamiaj terminal przyciskiem "START" tylko wtedy, gdy go potrzebujesz. Po zakończeniu pracy kliknij "STOP", aby zwolnić pamięć RAM urządzenia.',
        's5_pwr' => '🔄 <strong>Zasilanie:</strong> Korzystaj z opcji Reboot/Shutdown w panelu. Nagłe odłączenie zasilania może uszkodzić kartę pamięci.',

        's6_title' => '6. Nowości i Funkcje V1.4',
        's6_pulse' => '🔴 <strong>Wizualizacja Nadawania:</strong> Kafelki w zakładce Nodes oraz na mapie pulsują na czerwono, gdy dana stacja mówi.',
        's6_smart' => '🎛️ <strong>Smart Config:</strong> W konfiguracji nie musisz wpisywać numerów TG ręcznie. Kliknij w pole, a otworzy się panel wyboru z Twoimi grupami.',
        's6_backup' => '💾 <strong>Moduł Kopii Zapasowych:</strong> Pobierz wszystkie swoje ustawienia, przyciski i sieci do pliku ZIP, by łatwo odtworzyć je w przyszłości.',

        's7_title' => '7. Tryb Ratunkowy (Access Point Wi-Fi)',
        's7_text' => 'Gdy Hotspot nie znajdzie żadnej znanej sieci Wi-Fi, po około 2 minutach automatycznie stworzy własną sieć ratunkową, abyś nie stracił do niego dostępu.',
        's7_step1' => '📶 <strong>Nazwa sieci (SSID):</strong> PrimeNode_AP',
        's7_step2' => '🔑 <strong>Hasło:</strong> <code>primenode123</code>',
        's7_step3' => '🌍 <strong>Adres panelu:</strong> Połącz się z tą siecią i wpisz w przeglądarce: <code>192.168.4.1</code>',

        's8_title' => '8. EchoLink i Wyszukiwarka Stacji',
        's8_text' => 'PrimeNode posiada wbudowany inteligentny system obsługi sieci EchoLink.',
        's8_search' => '🔍 <strong>Wyszukiwarka Live:</strong> W zakładce DTMF wpisz minimum 3 znaki (znak wywoławczy lub miasto), aby przeszukać bazę tysięcy aktywnych węzłów z całego świata w czasie rzeczywistym.',
        's8_add' => '➕ <strong>Książka Adresowa:</strong> Znalezioną stację możesz natychmiast wywołać klikając "Połącz" lub dodać jako stały przycisk do swojej prywatnej grupy EchoLink klikając "+ Grupa".',

        's9_title' => '9. System Raportowania APRS',
        's9_text' => 'Twój hotspot może automatycznie wysyłać swoją pozycję oraz parametry radiowe do ogólnoświatowej sieci APRS-IS, dzięki czemu będzie widoczny na mapach (np. aprs.fi).',
        's9_req' => '🔑 <strong>Wymagania:</strong> Aby funkcja działała, musisz włączyć ją w zakładce Konfiguracja i wpisać swój unikalny "APRS Passcode" (kod dostępu dla Twojego znaku).',
        's9_cfg' => '📍 <strong>Konfiguracja:</strong> Wpisz współrzędne w dowolnym formacie (system sam przekonwertuje je na standard APRS), wybierz dedykowaną ikonę (np. węzeł Echolink) oraz określ interwał wysyłania ramek beacon.',

        'qa_title' => 'Szybka Pomoc (Q&A)',
        'qa_q1' => '❓ EchoLink nie chce się połączyć (Status: Disconnected).',
        'qa_a1' => '✅ Jeśli używasz internetu z telefonu (LTE), operatorzy często blokują porty. Użyj funkcji <strong>♻️ Auto-Proxy</strong> w zakładce <strong>Config</strong>.',
        'qa_q2' => '❓ Widzę na ekranie, że ktoś mówi (RX), ale nic nie słyszę w radiu.',
        'qa_a2' => '✅ Najprawdopodobniej masz ustawiony inny kod <strong>CTCSS</strong> w radiu niż w Hotspocie. Na czas testów ustaw kod na 00.0 (Brak) w obu urządzeniach.',
        'qa_q3' => '❓ Jak odzyskać dostęp do panelu w terenie?',
        'qa_a3' => '✅ Włącz urządzenie z dala od domowej sieci, poczekaj 2 minuty i podłącz się do utworzonej sieci WiFi <strong>PrimeNode_AP</strong>.'
    ],
    'en' => [
        'title' => 'PrimeNode Command Center by SQ7UTP',
        'subtitle' => 'Universal system for platforms:',
        'hw_desc' => '<strong style="color: #4CAF50;">Raspberry Pi (Zero W, 2W, 3, 3B, 3B+, 4)</strong>',
        
        's1_title' => '1. Your Dashboard',
        's1_text' => 'This is the heart of your device, where you monitor parameters in real-time and see the current connection status.',
        's1_msg' => '📢 Message Bar:',
        's1_msg_d' => 'The blue bar at the top displays direct messages from the Network Administrator.',
        's1_stat' => '🚦 Status Bar:',
        's1_stat_d' => 'The color indicates SvxLink health: <span style="color:#4CAF50; font-weight:bold;">GREEN</span> means working properly, <span style="color:#F44336; font-weight:bold;">RED</span> signals a service error.',
        's1_mon' => '📺 Live Monitor:',
        's1_mon_stby' => '⚪ <strong>Standby:</strong> Silence on the air, hotspot is listening.',
        's1_mon_rx' => '🟢 <span style="color:#4CAF50; font-weight:bold;">RECEIVING (RX):</span> A local station is transmitting to the Hotspot (You are talking).',
        's1_mon_tx' => '🟠 <span style="color:#FF9800; font-weight:bold;">TRANSMITTING (TX):</span> The hotspot is transmitting a signal from the internet.',
        's1_mon_info' => '✨ <strong>Smart Info:</strong> Automatically identifies the speaker, displaying their Name and City.',
        
        's2_title' => '2. Radio Modes (Hardware)',
        's2_text' => 'PrimeNode supports various hardware configurations in the <strong>Radio</strong> tab:',
        's2_rfguru' => '🌟 <strong>RF Guru Module (I2S):</strong> Advanced board with hardware WM8960 audio codec. Offers excellent sound quality. On first setup, the system performs an automated, safe installation of required drivers.',
        's2_shari' => '🛰️ <strong>SHARI / SA818 Module:</strong> Integrated radio solution. Program the frequency and CTCSS directly from the web panel.',
        's2_cm108' => '📻 <strong>USB Card (CM108) + Radio:</strong> Classic mode for external radios. Allows the use of custom GPIO pins for PTT and SQL.',

        's3_title' => '3. Roaming and Network Management (Reflector)',
        's3_text' => 'The system allows for instant switching between multiple servers (Reflectors).',
        's3_mgr' => '🛠️ <strong>Network Manager:</strong> Define your own list of servers, default TG groups, and audio announcements in the <strong>Config</strong> tab.',
        's3_switch' => '📞 <strong>Radio Switching (Code 555):</strong> Type the code on your radio: <span style="color:#FF9800; font-weight:bold;">555 + network ID + #</span> (e.g., 5551# switches to server 1). Or use the SWITCH button.',
        's3_auto' => '♻️ <strong>Automation:</strong> The system automatically restarts the service, connects to the new reflector, and enters your default TG.',

        's4_title' => '4. DTMF Tab (Interactive Remote)',
        's4_text' => 'Total freedom in controlling and personalizing the interface.',
        's4_move' => '✨ <strong>Drag & Drop:</strong> Freely change the button layout! Click and drag a tile to a new location.',
        's4_tabs' => '📂 <strong>Custom Tabs:</strong> Create your own button groups (e.g., "Favorite TGs").',
        's4_add' => '🎛️ <strong>Editor:</strong> Add a new button to the currently selected list using the form at the bottom.',

        's5_title' => '5. SSH Terminal and Administration',
        's5_text' => 'Manage the system directly from your browser.',
        's5_ssh' => '💻 <strong>Web Terminal:</strong> SSH console available in a separate tab for system commands.',
        's5_ssh_hint' => '💡 <strong>Remember:</strong> Only start the terminal when needed. Click "STOP" after finishing to free up RAM.',
        's5_pwr' => '🔄 <strong>Power:</strong> Use the Reboot/Shutdown options in the panel to prevent SD card corruption.',

        's6_title' => '6. What\'s New & V1.4 Features',
        's6_pulse' => '🔴 <strong>TX Visualization:</strong> Station tiles and map dots pulse red when a person is talking.',
        's6_smart' => '🎛️ <strong>Smart Config:</strong> No need to type TG numbers manually. Click the field to open your custom DTMF groups.',
        's6_backup' => '💾 <strong>Backup System:</strong> Download all your settings, buttons, and networks to a ZIP file for easy restoration.',

        's7_title' => '7. Rescue Mode (Wi-Fi Access Point)',
        's7_text' => 'When the Hotspot cannot find a known Wi-Fi network, it automatically creates its own network after 2 minutes.',
        's7_step1' => '📶 <strong>Network Name (SSID):</strong> PrimeNode_AP',
        's7_step2' => '🔑 <strong>Password:</strong> <code>primenode123</code>',
        's7_step3' => '🌍 <strong>Panel Address:</strong> Connect and type in your browser: <code>192.168.4.1</code>',

        's8_title' => '8. EchoLink & Station Search',
        's8_text' => 'PrimeNode has a built-in smart management system for the EchoLink network.',
        's8_search' => '🔍 <strong>Live Search:</strong> Type at least 3 characters in the DTMF tab to instantly search thousands of active global nodes.',
        's8_add' => '➕ <strong>Address Book:</strong> Connect to found stations instantly or add them permanently to your custom EchoLink groups with one click.',

        's9_title' => '9. APRS Reporting System',
        's9_text' => 'Your hotspot can automatically send its position and radio parameters to the global APRS-IS network to appear on maps like aprs.fi.',
        's9_req' => '🔑 <strong>Requirements:</strong> Enable the feature in Config and enter your unique APRS Passcode.',
        's9_cfg' => '📍 <strong>Configuration:</strong> Enter coordinates (automatically converted to APRS format), choose an icon, and set your beacon interval.',

        'qa_title' => 'Quick Help (Q&A)',
        'qa_q1' => '❓ EchoLink won\'t connect (Status: Disconnected).',
        'qa_a1' => '✅ Mobile internet often blocks ports. Use the <strong>♻️ Auto-Proxy</strong> function in the Config tab.',
        'qa_q2' => '❓ I see someone talking (RX), but I hear nothing on the radio.',
        'qa_a2' => '✅ You likely have a different <strong>CTCSS</strong> code set on your radio. Set it to 0 for testing.',
        'qa_q3' => '❓ How do I access the panel without my WiFi router?',
        'qa_a3' => '✅ Wait 2 minutes for Rescue mode and connect to <strong>PrimeNode_AP</strong> WiFi network.'
    ]
];
?>

<h3>🎓 <?php echo $H[$lang]['title']; ?></h3>
<div style="text-align: center; margin-bottom: 20px; font-size: 0.9em; color: #888; background: #222; padding: 10px; border-radius: 4px; border: 1px solid #444;">
    ℹ️ <?php echo $H[$lang]['subtitle']; ?> <?php echo $H[$lang]['hw_desc']; ?>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🖥️</span> <?php echo $H[$lang]['s1_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s1_text']; ?>
        <ul>
            <li><strong><?php echo $H[$lang]['s1_msg']; ?></strong> <?php echo $H[$lang]['s1_msg_d']; ?></li>
            <li><strong><?php echo $H[$lang]['s1_stat']; ?></strong> <?php echo $H[$lang]['s1_stat_d']; ?></li>
            <li><strong><?php echo $H[$lang]['s1_mon']; ?></strong>
                <ul>
                    <li><?php echo $H[$lang]['s1_mon_stby']; ?></li>
                    <li><?php echo $H[$lang]['s1_mon_rx']; ?></li>
                    <li><?php echo $H[$lang]['s1_mon_tx']; ?></li>
                    <li style="color:#4CAF50;"><?php echo $H[$lang]['s1_mon_info']; ?></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<div class="help-section" style="border-left: 5px solid #2196F3; background: rgba(33,150,243,0.05);">
    <div class="help-title" style="color:#2196F3;"><span class="help-icon">📻</span> <?php echo $H[$lang]['s2_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s2_text']; ?>
        <p><?php echo $H[$lang]['s2_rfguru']; ?></p>
        <p><?php echo $H[$lang]['s2_shari']; ?></p>
        <p><?php echo $H[$lang]['s2_cm108']; ?></p>
    </div>
</div>

<div class="help-section" style="border-left: 5px solid #FF9800; background: rgba(255,152,0,0.05);">
    <div class="help-title" style="color:#FF9800;"><span class="help-icon">🌐</span> <?php echo $H[$lang]['s3_title']; ?></div>
    <div class="help-text">
        <p><?php echo $H[$lang]['s3_text']; ?></p>
        <ul>
            <li><?php echo $H[$lang]['s3_mgr']; ?></li>
            <li><?php echo $H[$lang]['s3_switch']; ?></li>
            <li><?php echo $H[$lang]['s3_auto']; ?></li>
        </ul>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">📱</span> <?php echo $H[$lang]['s4_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s4_text']; ?>
        <ul>
            <li><?php echo $H[$lang]['s4_move']; ?></li>
            <li><?php echo $H[$lang]['s4_tabs']; ?></li>
            <li><?php echo $H[$lang]['s4_add']; ?></li>
        </ul>
    </div>
</div>

<div class="help-section" style="border-left: 5px solid #E91E63; background: rgba(233,30,99,0.05);">
    <div class="help-title" style="color:#E91E63;"><span class="help-icon">🔗</span> <?php echo $H[$lang]['s8_title']; ?></div>
    <div class="help-text">
        <p><?php echo $H[$lang]['s8_text']; ?></p>
        <ul>
            <li><?php echo $H[$lang]['s8_search']; ?></li>
            <li><?php echo $H[$lang]['s8_add']; ?></li>
        </ul>
    </div>
</div>

<div class="help-section" style="border-left: 5px solid #00BCD4; background: rgba(0,188,212,0.05);">
    <div class="help-title" style="color:#00BCD4;"><span class="help-icon">🌍</span> <?php echo $H[$lang]['s9_title']; ?></div>
    <div class="help-text">
        <p><?php echo $H[$lang]['s9_text']; ?></p>
        <ul>
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s9_req']; ?></li>
            <li><?php echo $H[$lang]['s9_cfg']; ?></li>
        </ul>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">⚡</span> <?php echo $H[$lang]['s5_title']; ?></div>
    <div class="help-text">
        <?php echo $H[$lang]['s5_text']; ?>
        <ul>
            <li style="margin-bottom:10px;"><?php echo $H[$lang]['s5_ssh']; ?> <?php echo $H[$lang]['s5_ssh_hint']; ?></li>
            <li><?php echo $H[$lang]['s5_pwr']; ?></li>
        </ul>
    </div>
</div>

<div class="help-section" style="background: rgba(76, 175, 80, 0.05); border: 1px solid #4CAF50; padding: 15px; border-radius: 8px;">
    <div class="help-title" style="color:#4CAF50;"><span class="help-icon">🚀</span> <?php echo $H[$lang]['s6_title']; ?></div>
    <div class="help-text">
        <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 12px;"><?php echo $H[$lang]['s6_pulse']; ?></li>
            <li style="margin-bottom: 12px;"><?php echo $H[$lang]['s6_smart']; ?></li>
            <li><?php echo $H[$lang]['s6_backup']; ?></li>
        </ul>
    </div>
</div>

<div class="help-section" style="border-left: 5px solid #F44336; background: rgba(244,67,54,0.05);">
    <div class="help-title" style="color:#F44336;"><span class="help-icon">🆘</span> <?php echo $H[$lang]['s7_title']; ?></div>
    <div class="help-text">
        <p><?php echo $H[$lang]['s7_text']; ?></p>
        <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s7_step1']; ?></li>
            <li style="margin-bottom: 8px;"><?php echo $H[$lang]['s7_step2']; ?></li>
            <li><?php echo $H[$lang]['s7_step3']; ?></li>
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
        <div style="background: rgba(244,67,54,0.1); padding: 15px; border-radius: 5px; border: 1px solid #F44336;">
            <strong><?php echo $H[$lang]['qa_q3']; ?></strong><br>
            <?php echo $H[$lang]['qa_a3']; ?>
        </div>
    </div>
</div>