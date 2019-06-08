<?php
require_once __DIR__.'/src/PHPTelebot.php';
require_once 'func.php';
$bot = new PHPTelebot('ur telegram bot token', 'ur bot username'); 

// Command without Function


$bot->run();
