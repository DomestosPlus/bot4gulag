<?php
//dec5cadb1d37d13bb8ea5d18868636415f05fbe4be5de822146133505ec439441d3374ea15c4f640ce4fc
define('CALLBACK_API_CONFIRMATION_TOKEN', '6df11474'); //Строка для подтверждения адреса сервера из настроек Callback API
define('VK_API_ACCESS_TOKEN', 'e4767a55247e28905ff9f64478cc1235f557804fc99afb0d5a881522ab930705a797a64d8773b9958c802'); //Ключ доступа сообщества
define('VK_API_VERSION', '5.85'); //Используемая версия API
define('VK_API_ENDPOINT', 'https://api.vk.com/method/');

function vkApi_messagesSend($peer_id, $message, $attachments = array()) {
  return _vkApi_call('messages.send', array(
    'peer_id'    => $peer_id,
    'message'    => $message,
    'attachment' => implode(',', $attachments)
  ));
}

function vkApi_usersGet($user_id) {
  return _vkApi_call('users.get', array(
    'user_id' => $user_id,
  ));
}

function _vkApi_call($method, $params = array()) {
  $params['access_token'] = VK_API_ACCESS_TOKEN;
  $params['v'] = VK_API_VERSION;

  $query = http_build_query($params);
  $url = VK_API_ENDPOINT.$method.'?'.$query;

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $json = curl_exec($curl);
  $error = curl_error($curl);

  if ($error) {
    log_error($error);
    throw new Exception("Failed {$method} request");
  }

  curl_close($curl);
  $response = json_decode($json, true);

  if (!$response || !isset($response['response'])) {
    log_error($json);
    throw new Exception("Invalid response for {$method} request");
  }

  return $response['response'];
}

function vkApi_upload($url, $file_name) {
  if (!file_exists($file_name)) {
    throw new Exception('File not found: '.$file_name);
  }

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => new CURLfile($file_name)));
  $json = curl_exec($curl);
  $error = curl_error($curl);

  if ($error) {
    log_error($error);
    throw new Exception("Failed {$url} request");
  }

  curl_close($curl);
  $response = json_decode($json, true);
  if (!$response) {
    throw new Exception("Invalid response for {$url} request");
  }

  return $response;
  
?>
