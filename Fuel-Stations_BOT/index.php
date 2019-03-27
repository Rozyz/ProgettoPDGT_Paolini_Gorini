<?php
  require_once(dirname(__FILE__) . '/../token.php');

  $website = "https://api.telegram.org/bot".$botToken;

  $update = file_get_contents($website."/getupdates");
  $updateArray = json_decode($update, TRUE);

  print_r($updateArray);

  $chatId = $updateArray['message']['from']['id'];
  $name = $updateArray['message']['from']['first_name'];
  $text = $updateArray['message']['text'];
 ?>
