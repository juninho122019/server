<?php
header('Content-type: text/plain');
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}
$IP = $_SERVER['REMOTE_ADDR'] . "\n";
$file = fopen('logs.txt', 'a');
fwrite($file, $IP);
fclose($file);
?>
