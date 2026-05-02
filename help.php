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
        's2_shari' => '🛰️ <strong>Moduł SHARI / SA818:</strong> Zintegrowane rozwiązanie radiowe. Częstotliwość, kody CTCSS oraz Squelch programujesz bezpośrednio z poziomu panelu WWW. System sam wyśle dane do modułu po kliknięciu Zapisz.',
        's2_cm108' => '📻 <strong>Karta USB (CM108) + Radio:</strong> Klasyczny tryb dla zewnętrznych radiotelefonów (np. Baofeng/Quansheng). Pozwala na użycie własnych pinów GPIO dla sygnałów PTT oraz SQL.',

        's3_title' => '3. Roaming i Zarządzanie Sieciami',
        's3_text' => 'System pozwala na błyskawiczne przełączanie się między wieloma serwerami (Reflektorami).',
        's3_mgr' => '🛠️ <strong>Menedżer Sieci:</strong> W zakładce <strong>Config</strong> możesz zdefiniować własną listę serwerów (np. FM-Poland, SQLink, serwery lokalne), przypisując im unikalne nazwy i dane logowania.',
        's3_switch' => '📞 <strong>Przełączanie z Radia (Kod 555):</strong> Nie musisz otwierać strony WWW! Wpisz na radiu kod: <span style="color:#FF9800; font-weight:bold;">555 + ID sieci + #</span> (np. 5551# włączy serwer o ID 1).',
        's3_auto' => '♻️ <strong>Automatyzacja:</strong> Po wydaniu komendy system samodzielnie zrestartuje usługę i połączy się z nowym reflektorem, potwierdzając to komunikatem głosowym.',

        's4_title' => '4. Zakładka DTMF (Interaktywny Pilot)',
        's4_text' => 'Pełna wolność w sterowaniu i personalizacji interfejsu.',
        's4_move' => '✨ <strong>Drag & Drop:</strong> Możesz dowolnie zmieniać układ przycisków! Po prostu przytrzymaj kafelek myszką lub palcem i przesuń go w nowe miejsce.',
        's4_tabs' => '📂 <strong>Własne Zakładki:</strong> Twórz własne grupy przycisków (np. "Ulubione TG", "Echolink Polska").',
        's4_add' => '🎛️ <strong>Edytor:</strong> Na dole zakładki DTMF znajdziesz formularz, który pozwala dodać nowy przycisk (Nazwa + Numer TG) do aktualnie wybranej listy.',

        's5_title' => '5. Terminal SSH i Administracja',
        's5_text' => 'Zarządzaj systemem bezpośrednio z przeglądarki.',
        's5_ssh' => '💻 <strong>Web Terminal:</strong> Nowoczesna konsola SSH dostępna w osobnej zakładce. Pozwala na wpisywanie komend systemowych bez użycia programów typu PuTTY.',
        's5_ssh_hint' => '💡 <strong>Pamiętaj:</strong> Uruchamiaj terminal przyciskiem "START" tylko wtedy, gdy go potrzebujesz. Po zakończeniu pracy kliknij "STOP", aby zwolnić pamięć RAM urządzenia.',
        's5_pwr' => '🔄 <strong>Zasilanie:</strong> Korzystaj z opcji Reboot/Shutdown w panelu. Nagłe odłączenie wtyczki może uszkodzić dane na karcie SD.',

        's6_title' => '6. Nowości i Funkcje V1.3',
        's6_pulse' => '🔴 <strong>Wizualizacja Nadawania:</strong> Kafelki stacji w zakładce <em>Nodes</em> oraz kropki na mapie pulsują na czerwono, gdy dana osoba mówi.',
        's6_icons' => '📱 <strong>Ikony Urządzeń:</strong> Widzisz, kto używa Radia (📻), Telefonu (📱) czy Komputera (💻).',
        's6_smart' => '🎛️ <strong>Smart Config:</strong> W konfiguracji nie musisz wpisywać numerów TG ręcznie. Kliknij w pole, a otworzy się panel wyboru z Twoimi własnymi grupami z zakładki DTMF.',

        'qa_title' => 'Szybka Pomoc (Q&A)',
        'qa_q1' => '❓ EchoLink nie chce się połączyć (Status: Disconnected).',
        'qa_a1' => '✅ Jeśli używasz internetu z telefonu (Hotspot LTE), operatorzy często blokują porty. Użyj funkcji <strong>♻️ Auto-Proxy</strong> w zakładce <strong>Config</strong>, aby ominąć te blokady.',
        'qa_q2' => '❓ Widzę na ekranie, że ktoś mówi (RX), ale nic nie słyszę w radiu.',
        'qa_a2' => '✅ Najprawdopodobniej masz ustawiony inny kod <strong>CTCSS</strong> w radiu niż w Hotspocie. Sprawdź ustawienia w zakładce Radio. Najlepiej ustaw kod na 0 w obu urządzeniach na czas testów.',
        'qa_q3' => '❓ Jak odzyskać dostęp do panelu, gdy nie mam pod ręką routera WiFi?',
        'qa_a3' => '✅ System PrimeNode ma tryb ratunkowy. Włącz go z dala od domowej sieci, poczekaj 2 minuty, a stworzy sieć WiFi <strong>PrimeNode_AP</strong> (hasło: <code>primenode123</code>). Adres strony to wtedy <code>192.168.4.1</code>.'
    ],
    'en' => [
        'title' => 'PrimeNode Command Center by SQ7UTP',
        'subtitle' => 'Universal system for platforms:',
        'hw_desc' => '<strong style="color: #4CAF50;">Raspberry Pi (Zero W, 2W, 3, 3B, 3B+, 4)</strong>',
        's1_title' => '1. Dashboard',
        's1_text' => 'Monitor your device status and live traffic in real-time.',
        's2_title' => '2. Radio Modes',
        's2_shari' => '🛰️ <strong>SHARI / SA818:</strong> Fully software-controlled integrated radio module.',
        's2_cm108' => '📻 <strong>CM108 USB + Radio:</strong> External radio interface via GPIO pins.',
        's3_title' => '3. Roaming & Networks',
        's3_switch' => '📞 <strong>Remote Switching:</strong> Dial <span style="color:#FF9800; font-weight:bold;">555 + ID + #</span> on your radio to change servers.',
        's5_ssh' => '💻 <strong>Web Terminal:</strong> Browser-based console access. Please stop it after use to save system resources.',
        'qa_q3' => '❓ No WiFi access?',
        'qa_a3' => '✅ Connect to <strong>PrimeNode_AP</strong> (pass: <code>primenode123</code>) at <code>192.168.4.1</code>.'
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
            <li style="margin-bottom: 12px;"><?php echo $H[$lang]['s6_icons']; ?></li>
            <li><?php echo $H[$lang]['s6_smart']; ?></li>
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
