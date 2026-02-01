<?php
session_start();
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'pl';

$TLH = [
    'pl' => [
        'waiting' => 'Oczekiwanie na dane...',
        'no_calls' => 'Brak ostatnich rozmów w historii.'
    ],
    'en' => [
        'waiting' => 'Waiting for data...',
        'no_calls' => 'No recent calls in history.'
    ]
];

$logFile = '/var/www/html/svx_events.log';
$limit_display = 20;

if (!file_exists($logFile)) {
    echo "<tr><td colspan='3' style='text-align:center; color:#777; padding:10px;'>" . $TLH[$lang]['waiting'] . "</td></tr>";
    exit;
}

$lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$lines = array_reverse($lines);

$count = 0;
$found_any = false;

$last_entry_hash = '';

foreach ($lines as $line) {
    if ($count >= $limit_display) break;

    $line = trim($line);

    if (strpos($line, 'Talker start on TG') !== false) {
        if (preg_match('/TG #(\d+):\s*([A-Z0-9-\/]+)/', $line, $m_call)) {
            $tg = $m_call[1];
            $callsign = trim($m_call[2]);

            $time_display = "---";
            if (preg_match('/(\d{2}:\d{2}:\d{2})/', $line, $m_time)) {
                $time_display = $m_time[1];
            }

            $current_entry_hash = $time_display . '|' . $tg . '|' . $callsign;

            if ($current_entry_hash === $last_entry_hash) {
                continue;
            }

            $last_entry_hash = $current_entry_hash;

            echo "<tr>";
            echo "<td><b style='color:#ccc;'>$time_display</b></td>";
            echo "<td><span class='badge-tg' style='background:#333; padding:2px 6px; border-radius:4px; font-size:12px; color:#FF9800;'>TG $tg</span></td>";
            echo "<td><b style='color:#4CAF50;'>$callsign</b></td>";
            echo "</tr>";

            $count++;
            $found_any = true;
        }
    }
}

if (!$found_any) {
    echo "<tr><td colspan='3' style='text-align:center; color:#777; padding:10px;'>" . $TLH[$lang]['no_calls'] . "</td></tr>";
}
?>