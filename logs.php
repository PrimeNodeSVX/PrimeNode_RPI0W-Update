<?php
session_start();
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'pl';

$TR = [
    'pl' => [
        'empty' => 'Log pusty.',
        'waiting' => 'Oczekiwanie na dane z loggera...'
    ],
    'en' => [
        'empty' => 'Log is empty.',
        'waiting' => 'Waiting for logger data...'
    ]
];

$logFile = '/var/www/html/svx_events.log';

if (file_exists($logFile)) {

    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if ($lines !== false) {
        
        $lines = array_unique($lines);
        $lines = array_reverse($lines);
        
        $output = array_slice($lines, 0, 60);
        
        $output = array_reverse($output);
        
        echo implode("\n", $output);
    } else {
        echo $TR[$lang]['empty'];
    }
} else {
    echo $TR[$lang]['waiting'];
}
?>
