<?php
  require_once(dirname(__FILE__) . '/../token.php');

  $website = "https://api.telegram.org/bot".$botToken;

  $update = file_get_contents($website."/getupdates");
  $dati = json_decode($update, TRUE);

  print_r($dati);

  $chatId = $updateArray['message']['from']['id'];
  $name = $updateArray['message']['from']['first_name'];
  $text = $updateArray['message']['text'];

  if($text == "Ciao")
    http_request("https://api.telegram.org/bot{$botToken}/sendMessage?chat_id=".$chatId."&text=ciao");
 ?>
