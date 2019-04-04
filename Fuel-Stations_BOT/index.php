<?php
  require_once(dirname(__FILE__).'/token.php');
  require_once(dirname(__FILE__).'/curl-lib.php');
  require_once(dirname(__FILE__).'/funzioni.php');


  $stato = [];
  $inizio = 0;
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

  if(isset($update['result'][0])){
    $update_id = $update['result'][0]['update_id'];
    $chat_id = $update['result'][0]['message']['from']['id'];
    $name = $update['result'][0]['message']['from']['first_name'];
    $last_name = $update['result'][0]['message']['from']['last_name'];
    $text = $update['result'][0]['message']['text'];


    echo "Chat_id: $chat_id\n";
    echo "Name: $name\n";
    echo "Text: $text\n";

    if(isset($text) && (!isset($stato[$chat_id])) || $stato[(string)$chat_id] == 0){
      switch($text) {
        case "/start":
          if($inizio == 0){
            $msg = "Ciao $name e benvenuto!\nEcco a te la lista dei comandi disponibili su questo bot.
                   \n/stazione\n/add";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg)."");
            $datiAuth = http_request_post("Localhost:3000/login",$name,$last_name,$chat_id);
            $inizio = 1;
          }
          else{
            $msg = "Ti sei già autenticato! Puoi utilizzare qualunque comando disponibile";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg)."");
          }

          //echo $token;
          break;

        case "/stazione":
          $msg2 = "Inserisci il nome del comune del quale vuoi conoscere i benzinai disponibili\n";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg2)."");
          $stato[(string)$chat_id] = 1;
          break;

        case "/add":
          $msg3 = "Inserisci il nome del comune";
          /*http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg3)."");
          $comune_utente = $text;
          $stato[(string)$chat_id] = 3;*/
          //http_request("https://fuel-stations-italy.herokuapp.com/token/".$chat_id."");
          //http_request_post("https://fuel-stations-italy.herokuapp.com/stazione/add",$token, $first_name, $id);

          $stato[(string)$chat_id] = 0;
          break;

        default:
          $infoMsg = "Comando non valido!";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($infoMsg)."");
      }
    }else if($stato[(string)$chat_id] == 1){
      $infos = http_request("https://fuel-stations-italy.herokuapp.com/comune/".$text."");
      getDatas($infos,$website,$chat_id,$stato,$text);
      if(isset($infos))
        $stato[(string)$chat_id] = 2;
      else {
        $stato[(string)$chat_id] = 0;
      }
    }else if($stato[(string)$chat_id] == 2){
      getDatas($infos,$website,$chat_id,$stato,$text);
      $stato[(string)$chat_id] = 0;
    }
    /*}else if($stato[(string)$chat_id] == 2){
      $msg4 = "Inserisci la provincia del comune";
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg4)."");
      $provincia_utente = $text;
      $stato[(string)$chat_id] = 3;
    }else if($stato[(string)$chat_id] == 3){
      $msg5 = "Inserisci la regione del comune";
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg5)."");
      $regione_utente = $text;
      $stato[(string)$chat_id] = 4;
    }else if($stato[(string)$chat_id] == 4){
      $msg6 = "Inserisci il nome della stazione";
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg6)."");
      $nomestazione_utente = $text;
      sendDatasStazioni($comune_utente,$provincia_utente,$regione_utente,$nomestazione_utente);
      $stato[(string)$chat_id] = 0;
    }
    */
    file_put_contents($last_update_filename, $update_id);
  }
}
?>
