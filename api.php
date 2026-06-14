<?php
header('Content-Type: application/json');

function cleanText($text) {
    $map = [
        'ą'=>'a', 'ć'=>'c', 'ę'=>'e', 'ł'=>'l', 'ń'=>'n', 'ó'=>'o', 'ś'=>'s', 'ź'=>'z', 'ż'=>'z',
        'Ą'=>'A', 'Ć'=>'C', 'Ę'=>'E', 'Ł'=>'L', 'Ń'=>'N', 'Ó'=>'O', 'Ś'=>'S', 'Ź'=>'Z', 'Ż'=>'Z'
    ];
    $text = str_replace(array_keys($map), array_values($map), $text);
    $text = preg_replace('/[^a-zA-Z0-9\s\-\(\)\.\,\/]/', '', $text);
    return trim(preg_replace('/\s+/', ' ', $text));
}

$activeNetworkName = '';
$netFile = '/etc/svxlink/networks.json';
if (file_exists($netFile)) {
    $netData = json_decode(file_get_contents($netFile), true);
    if (isset($netData['active']) && isset($netData['list'])) {
        $activeId = $netData['active'];
        if ($activeId != 0) {
            foreach ($netData['list'] as $net) {
                if ($net['id'] == $activeId) {
                    $activeNetworkName = $net['name'];
                    break;
                }
            }
        }
    }
}

$tgNames = [];
$customDtmfFile = '/var/www/html/dtmf_custom.json';
if (file_exists($customDtmfFile)) {
    $jsonData = json_decode(file_get_contents($customDtmfFile), true);
    if ($jsonData) {
        foreach ($jsonData as $key => $val) {
            if (isset($val['tg']) && isset($val['name'])) $tgNames[$val['tg']] = $val['name'];
            elseif (isset($val['buttons']) && is_array($val['buttons'])) {
                foreach ($val['buttons'] as $btn) if (isset($btn['tg']) && isset($btn['name'])) $tgNames[$btn['tg']] = $btn['name'];
            }
            elseif (is_array($val) && isset($val['name']) && isset($val['tg'])) $tgNames[$val['tg']] = $val['name'];
        }
    }
}

$sysTgdbFile = '/etc/svxlink/tgdb';
if (file_exists($sysTgdbFile)) {
    $lines = file($sysTgdbFile);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] == '#' || $line[0] == ';') continue;
        if (strpos($line, '#') !== false) {
            $parts = explode('#', $line, 2);
            $id = trim($parts[0]);
            $name = trim($parts[1]);
            if (!empty($id) && !isset($tgNames[$id])) $tgNames[$id] = $name;
        }
    }
}

if (!isset($tgNames['0'])) $tgNames['0'] = 'Czuwanie';
if (!isset($tgNames['999'])) $tgNames['999'] = 'Echolink/Parrot';

$response = [
    'status' => 'OFFLINE',
    'network' => cleanText($activeNetworkName),
    'tg' => '0',
    'tg_name' => 'Czuwanie',
    'callsign' => '---',
    'temp' => 0
];

$temp = @file_get_contents('/sys/class/thermal/thermal_zone0/temp');
$response['temp'] = $temp ? round($temp / 1000, 1) : 0;

function getLastPos($haystack, $needle) {
    $pos = strrpos($haystack, $needle);
    return $pos === false ? -1 : $pos;
}

$logData = @file_get_contents('/var/www/html/ram/svx_events.log');

if (!$logData || strlen($logData) < 10) {
    $logData = @file_get_contents('http://127.0.0.1/logs.php');
}
if (!$logData) $logData = "";

$lastConnect = max(
    getLastPos($logData, "ReflectorLogic: Connection established"),
    getLastPos($logData, "ReflectorLogic: Connected nodes"),
    getLastPos($logData, "ReflectorLogic: Talker start")
);

$lastDisconnect = max(
    getLastPos($logData, "ReflectorLogic: Disconnected"),
    getLastPos($logData, "ReflectorLogic: Authentication failed"),
    getLastPos($logData, "ReflectorLogic: Connection failed"),
    getLastPos($logData, "ReflectorLogic: Connection timed out"),
    getLastPos($logData, "Could not load or initialize Logic"),
    getLastPos($logData, "Removing logic from link"),
    getLastPos($logData, "At least one of HOSTS")
);

$isOnline = false;
if (strlen($logData) > 20) {
    if ($lastConnect > $lastDisconnect || ($lastConnect === -1 && $lastDisconnect === -1)) {
        $isOnline = true;
    }
}

$stateFile = '/dev/shm/api_state.json';
$selectedTg = '0';
if (file_exists($stateFile)) {
    $state = json_decode(file_get_contents($stateFile), true);
    if (isset($state['tg'])) $selectedTg = $state['tg'];
}

$lines = explode("\n", $logData);
$isNetworkTalking = false;
$networkTalkerTg = '0';
$networkTalkerCall = '---';
$isLocalTalking = false;

$lines = explode("\n", $logData);

if (count($lines) > 1 && strtotime(substr($lines[0], 0, 24)) > strtotime(substr($lines[count($lines)-2], 0, 24))) {
    $lines = array_reverse($lines);
}

$isNetworkTalking = false;
$networkTalkerTg = '0';
$networkTalkerCall = '---';
$isLocalTalking = false;

foreach ($lines as $line) {
    if (strpos($line, 'SvxLink v') !== false || 
        strpos($line, 'ReflectorLogic: Disconnected') !== false ||
        strpos($line, 'Deactivating module') !== false) {
        $selectedTg = '0';
        $isNetworkTalking = false;
        $isLocalTalking = false;
    }

    if (preg_match('/ReflectorLogic: Selecting TG #(\d+)/', $line, $match)) {
        $selectedTg = $match[1];
        $isNetworkTalking = false;
    }

    if (preg_match('/Talker start on TG #(\d+): ([A-Z0-9-\/]+)/', $line, $match)) {
        $incomingTg = $match[1];
        $incomingCall = $match[2];

        if ($selectedTg === '0' || $selectedTg === $incomingTg) {
            $isNetworkTalking = true;
            $networkTalkerTg = $incomingTg;
            $networkTalkerCall = $incomingCall;
        }
    }

    if (preg_match('/Talker stop on TG #(\d+)/', $line, $match)) {
        $stoppedTg = $match[1];

        if ($isNetworkTalking && $networkTalkerTg === $stoppedTg) {
            $isNetworkTalking = false;
        }
    }
    

    if (strpos($line, 'Turning the transmitter OFF') !== false) {
        $isNetworkTalking = false;
    }
    

    if (strpos($line, 'Rx1: The squelch is OPEN') !== false) {
        $isLocalTalking = true;
    }

    if (strpos($line, 'Rx1: The squelch is CLOSED') !== false) {
        $isLocalTalking = false;
    }
}

if (!$isOnline) {
    $selectedTg = '0';
    $isNetworkTalking = false;
    $isLocalTalking = false;
}

file_put_contents($stateFile, json_encode(['tg' => $selectedTg]));

$displayTg = '0';
$displayCall = '---';
$isTalking = false;

if ($isOnline) {
    $response['status'] = 'ONLINE';

    if ($isLocalTalking) {
        $isTalking = true;
        $displayTg = $selectedTg;
        $displayCall = "LOKALNIE";
    } 

    elseif ($isNetworkTalking) {
        $isTalking = true;
        $displayTg = $networkTalkerTg;
        $displayCall = $networkTalkerCall;
    } 

    else {
        $displayTg = $selectedTg;
        $displayCall = "---";
    }
} else {
    $response['status'] = 'OFFLINE';
    $displayTg = '0';
    $displayCall = '---';
}

$response['tg'] = ($displayTg === '' || $displayTg === '---') ? '0' : $displayTg;
$response['callsign'] = $displayCall;

if (isset($tgNames[$response['tg']])) {
    $response['tg_name'] = cleanText($tgNames[$response['tg']]);
} else {
    $response['tg_name'] = 'Czuwanie';
}

$response['debug'] = [
    'log_len' => strlen($logData),
    'l_conn' => $lastConnect,
    'l_disc' => $lastDisconnect,
    'is_talk' => $isTalking
];

echo json_encode($response);
?>
