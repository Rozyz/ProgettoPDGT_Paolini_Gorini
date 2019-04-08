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
            $datiAuth = http_request_post("Localhost:3002/login",$name,$last_name,$chat_id,null,null,null,null,null);
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
          $dati_utente = http_request("Localhost:3002/token/".$name);
          $i = 0;
          foreach($dati_utente as $dati){
            $id[$i] = $dati['id'];
            if($id[$i] == $dati['id']){
              $token = $dati['token'];
            }
            $i++;
          }
          echo $token;

          if($token != ' '){
            $msg3 = "Inserisci il nome del comune";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg3)."");
            $stato[(string)$chat_id] = 3;
          }else{
            $msg10 = "Non sei autenticato! Digita /start per iniziare la tua avventura con questo bot\n";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg10));
            $stato[(string)$chat_id] = 0;
          }
          break;

        default:
          $infoMsg = "Comando non valido!";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($infoMsg)."");
      }
    }else if($stato[(string)$chat_id] == 1){
      $infos = http_request("https://fuel-stations-italy.herokuapp.com/comune/".$text."");
      getDatas($infos,$website,$chat_id,$stato,$text,null,null,null);

    }else if($stato[(string)$chat_id] == 2){
      getDatas($infos,$website,$chat_id,$stato,$text,null,null,null);

    }else if($stato[(string)$chat_id] == 3){
      $comune_utente = $text;
      echo $comune_utente;
      $msg4 = "Inserisci la provincia del comune";
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg4)."");
      $stato[(string)$chat_id] = 4;

    }else if($stato[(string)$chat_id] == 4){
      $provincia_utente = $text;
      echo $provincia_utente;
      $msg5 = "Inserisci la regione del comune";
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg5)."");
      $stato[(string)$chat_id] = 5;

    }else if($stato[(string)$chat_id] == 5){
      $regione_utente = $text;
      echo $regione_utente;
      $msg6 = "Inserisci il nome della stazione";
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg6)."");
      $stato[(string)$chat_id] = 6;

    }else if($stato[(string)$chat_id] == 6){
      $nomestazione_utente = $text;
      echo $nomestazione_utente;
      $msg7 = "Inserisci la via della stazione";
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg7)."");
      $stato[(string)$chat_id] = 7;

    }else if($stato[(string)$chat_id] == 7){
      $via_utente = $text;
      echo $via_utente;

      $dati_google2 = http_request("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($via_utente)."&key=AIzaSyCtNEV0lStU80DF9xGOx0GfX30WFA0qZtY");

      $latitudine = $dati_google2['results'][0]['geometry']['location']['lat'];
      $longitudine = $dati_google2['results'][0]['geometry']['location']['lng'];
      http_request_post("Localhost:3002/stazione/add",$name,$last_name,$chat_id,$token,$nomestazione_utente,$comune_utente,$provincia_utente,$regione_utente,$longitudine,$latitudine);
      $msg8 = "Complimenti, hai inserito correttamente la tua stazione!\n";
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg8)."");
      $stato[(string)$chat_id] = 0;
    }
    file_put_contents($last_update_filename, $update_id);
  }
}
?>
