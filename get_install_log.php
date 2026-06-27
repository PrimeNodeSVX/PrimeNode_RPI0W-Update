<?php
$log_file = '/var/www/html/ram/rfguru_install.log';
if (file_exists($log_file)) {
    echo file_get_contents($log_file);
} else {
    echo "Oczekiwanie na start instalacji...";
}
?>