<?php
header('Content-Type: application/json');

$url = ''; 

$configFile = '/var/www/html/radio_config.json';

if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
    if (isset($config['node_api_url']) && !empty(trim($config['node_api_url']))) {
        $url = trim($config['node_api_url']);
    }
}

if (empty($url)) {
    echo json_encode(["nodes" => []]);
    exit;
}

$ctx = stream_context_create(array(
    'http' => array(
        'timeout' => 3 
    )
));

$json = @file_get_contents($url, false, $ctx);

if ($json === FALSE) {
    echo json_encode(["nodes" => []]);
} else {
    echo $json;
}
?>
