<?php
declare(ticks = 1);

require_once 'Srun4k.php';
require_once 'Tools.php';

pcntl_signal(SIGINT,  "sig_handler");

$options = getopt("u:p:m:t:", ["help"]);

help_info($options);
$interval = $options['t']??300;

$Srun = new Srun4k($options['u'], $options['p']);

while (true){
    $Srun->login();
    sleep($interval);
};