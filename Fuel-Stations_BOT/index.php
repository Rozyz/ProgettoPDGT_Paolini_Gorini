<?php
  require_once(dirname(__FILE__).'/token.php');
  require_once(dirname(__FILE__).'/curl-lib.php');

  $stato = [];

  while(1){

  $last_update_filename = dirname(__FILE__) . '/last-update-id.txt';
  if(file_exists($last_update_filename)) {
    $last_update = intval(@file_get_contents($last_update_filename));
  }
  else {
    $last_update = 0;
  }

  $website = "https://api.telegram.org/bot".$botToken;
  $update = http_request($website."/getupdates?offset=". ($last_update + 1) . "&limit=1");

  print_r($update);

  if(isset($update['result'][0])){
    $update_id = $update['result'][0]['update_id'];
    $chat_id = $update['result'][0]['message']['from']['id'];
    $name = $update['result'][0]['message']['from']['first_name'];
    $text = $update['result'][0]['message']['text'];

    echo "Chat_id: $chat_id\n";
    echo "Name: $name\n";
    echo "Text: $text\n";

    if(isset($text) && (!isset($stato[$chat_id])) || $stato[(string)$chat_id] == 0){
      switch($text) {
        case "/start":
          $msg = "Ciao $name e benvenuto!\nEcco a te la lista dei comandi disponibili su questo bot.
                 \n/stazione\n/add";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg)."");
          break;

        case "/stazione":
          $msg2 = "Inserisci il nome del comune del quale vuoi conoscere i benzinai disponibili\n";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg2)."");
          $stato[(string)$chat_id] = 1;
          break;

        case "/goro":
          $infos = http_request("https://fuel-stations-italy.herokuapp.com/comune/".$text."");
          //print_r($info);
          $i = 0;
          $contatore = 0;
          $stringa = "";
          foreach($infos as $info){
            $comune[$i] = $info['ccomune'];
            $nome[$i] = $info['cnome'];
            $provincia[$i] = $info['cprovincia'];
            $i++;
            $contatore++;
          }
          while($i != 0){
            $i--;
            if($nome[$i] == '')
              $nome[$i] = "/";
            $stringa = $stringa."Nome: {$nome[$i]}\n";

          }
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=A ".$comune[0]." ci sono ".$contatore." stazioni e sono: ".urlencode($stringa)."");
          break;

        default:
          $infoMsg = "Digita un comando valido, coglione";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($infoMsg)."");
      }
    }else if($stato[(string)$chat_id] == 1){
      $infos = http_request("https://fuel-stations-italy.herokuapp.com/comune/".$text."");
      //print_r($info);
      $i = 0;
      $contatore = 0;
      $stringa = "";
      foreach($infos as $info){
        $comune[$i] = $info['ccomune'];
        $nome[$i] = $info['cnome'];
        $provincia[$i] = $info['cprovincia'];
        $i++;
        $contatore++;
      }
      while($i != 0){
        $i--;
        if($nome[$i] == '')
          $nome[$i] = "/";
        $stringa = $stringa."Nome: {$nome[$i]}\n";

      }
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=A ".$comune[0]." ci sono ".$contatore." stazioni e sono: ".urlencode($stringa)."");
      $stato[(string)$chat_id] = 0;
    }
    file_put_contents($last_update_filename, $update_id);
  }
?>
