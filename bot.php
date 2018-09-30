<?php

$token = 'e4767a55247e28905ff9f64478cc1235f557804fc99afb0d5a881522ab930705a797a64d8773b9958c802';

use VK\CallbackApi\Server\VKCallbackApiServerHandler;

class ServerHandler extends VKCallbackApiServerHandler {
    const SECRET = 'bot4gulag';
    const GROUP_ID = 171508779;
    const CONFIRMATION_TOKEN = '6df11474';

function confirmation(int $group_id, ?string $secret) {
        if ($secret === static::MY_SECRET && $group_id === static::GROUP_ID) {
            echo static::CONFIRMATION_TOKEN;
        }
    }

public function messageNew(int $group_id, ?string $secret, array $object) {
        echo 'ok';
    }
}

$handler = new ServerHandler();
$data = json_decode(file_get_contents('php://input'));
$handler->parse($data);

?>
