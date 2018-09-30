<?php

if (!isset($_REQUEST)) {
return;
}

//Строка для подтверждения адреса сервера из настроек Callback API
$confirmation_token = '6df11474';

//Ключ доступа сообщества
$token = 'e4767a55247e28905ff9f64478cc1235f557804fc99afb0d5a881522ab930705a797a64d8773b9958c802';

//Получаем и декодируем уведомление
$data = json_decode(file_get_contents('php://input'));

//Проверяем, что находится в поле "type"
switch ($data->type) {
//Если это уведомление для подтверждения адреса...
case 'confirmation':
//...отправляем строку для подтверждения
echo $confirmation_token;
break;

//Если это уведомление о новом сообщении...
case 'message_new':
//...получаем id его автора
$user_id = $data->object->user_id;
//затем с помощью users.get получаем данные об авторе
$user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&access_token={$token}&v=5.85"));

//и извлекаем из ответа его имя
$user_name = $user_info->response[0]->first_name;

//С помощью messages.send отправляем ответное сообщение
$request_params = array(
'message' => "Hello, {$user_name}!",
'user_id' => $user_id,
'access_token' => $token,
'v' => '5.85' 
);

$get_params = http_build_query($request_params);

file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);

//Возвращаем "ok" серверу Callback API

echo('ok');

break;

}
?>
