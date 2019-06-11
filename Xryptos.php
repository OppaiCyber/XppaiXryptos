<?php
require_once __DIR__.'/src/PHPTelebot.php';
require_once __DIR__.'/src/Function.php';
$bot = new PHPTelebot('ur bot token', 'ur bot username');

// Command without Function
$bot->cmd('hontoni','yokatta');

// Command with Function

$bot->cmd('/gas', function(){
        $options = ['parse_mode' => 'html','reply' => true,'disable_web_page_preview' => true];
                return Bot::sendMessage(gasChecker(),$options);
});

$bot->cmd('/p', function(){
        
        $options = ['parse_mode' => 'html','reply' => true,'disable_web_page_preview' => true];
                return Bot::sendMessage(priceChecker(),$options);
});


$bot->run();
