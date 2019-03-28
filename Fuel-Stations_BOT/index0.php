<?php
  require_once(dirname(__FILE__).'/token.php');
  require_once(dirname(__FILE__).'/curl-lib.php');

  while(1){
    $last_update_filename = dirname(__FILE__) . '/last-update-id.txt';
    if(file_exists($last_update_filename)) {
      $last_update = intval(@file_get_contents($last_update_filename));
    }
    else {
      $last_update = 0;
    }

    $website = "https://api.telegram.org/bot".$botToken;
    $update = file_get_contents($website."/getupdates?offset=". ($last_update + 1) . "&limit=1");
    //$update = file_get_contents("php://input");
    $dati = json_decode($update, TRUE);

    print_r($dati);

    if(isset($dati->result[0])){
      $update_id = $dati['result'][0]['update_id'];
      $chat_id = $dati['result'][0]['message']['from']['id'];
      $name = $dati['result'][0]['message']['from']['first_name'];
      $text = $dati['result'][0]['message']['text'];

      echo "Chat_id: $chat_id\n";
      echo "Name: $name\n";
      echo "Text: $text\n";

      switch($text) {
        case "/start":
          $msg = "Ciao $name e benvenuto!\nEcco a te la lista dei comandi disponibili su questo bot.
                 \n/stazione\n/add";
          file_get_contents($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg)."");
          break;
        case "/stazione":
          $msg2 = "Inserisci il nome del comune del quale vuoi conoscere i benzinai disponibili\n";
          file_get_contents($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg2)."");
          break;

        default:
          $infoMsg = "Digita un comando valido, coglione";
          file_get_contents($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($infoMsg)."");
      }
      file_put_contents($last_update_filename, $update_id);
    }
  }
?>
