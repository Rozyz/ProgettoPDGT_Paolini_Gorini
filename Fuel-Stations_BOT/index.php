<?php
  require_once(dirname(__FILE__).'/token.php');
  require_once(dirname(__FILE__).'/funzioni.php');

  $stato = [];
  $inizio = [];
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

    if(isset($text) && (!isset($stato[$chat_id])) || $stato[(string)$chat_id] == 0){
      switch($text) {
        case "/start":
          if(!isset($inizio[$chat_id]) || $inzio[(string)$chat_id] == 0){
            $msg = "Ciao $name e benvenuto!\nEcco a te la lista dei comandi disponibili su questo bot.
                   \n/stazione\n/add";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg)."");
            http_request_post("https://fuel-stations-italy.herokuapp.com/login",$name,$last_name,$chat_id,null,null,null,null,null,null,null);
            $inzio[(string)$chat_id] = 1;
          }else{
            $msg = "Ti sei già autenticato! Puoi utilizzare qualunque comando disponibile";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg)."");
          }
          break;

        case "/stazione":
          $msg2 = "Inserisci il nome del comune del quale vuoi conoscere i benzinai disponibili\n";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg2)."");
          $stato[(string)$chat_id] = 1;
          break;

        case "/add":
          $dati_utente = http_request("https://fuel-stations-italy.herokuapp.com/utente/".$name);
          if(isset($dati_utente)){
            $i = 0;
            foreach($dati_utente as $dati){
              $id[$i] = $dati['id'];
              if($id[$i] == $dati['id']){
                $token = $dati['token'];
              }
              $i++;
            }
          }
          if(isset($token)){
            $msg3 = "Inserisci il nome del comune";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg3)."");
            $stato[(string)$chat_id] = 3;
          }else{
            $msg10 = "Non sei autenticato! Digita /start per iniziare la tua avventura con questo bot\n";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg10));
            $stato[(string)$chat_id] = 0;
          }
          break;

          case "/esci":
            $msg18 = "Sei già nello stato iniziale!\nUtilizza i comandi disponibili";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg18));
            $stato[(string)$chat_id] = 0;
            break;

        default:
          $infoMsg = "Comando non valido!";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($infoMsg)."");
      }
    }else if($stato[(string)$chat_id] == 1){
      if(textCheck($text,$stato,$chat_id)){
        $infos = http_request("https://fuel-stations-italy.herokuapp.com/comune/".$text."");
        getDatas($infos,$website,$chat_id,$stato,$text,null,null,null);
      }else{
        if($text == "/esci")
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ok");
        else{
          $msg11 = "Stazione errata! Inserisci un comune valido";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg11)."");
        }
      }

    }else if($stato[(string)$chat_id] == 2){
      getDatas($infos,$website,$chat_id,$stato,$text,null,null,null);

    }else if($stato[(string)$chat_id] == 3){
      if(textCheck($text,$stato,$chat_id)){
        $comune_utente = $text;
        $msg4 = "Inserisci la provincia del comune";
        http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg4)."");
        $stato[(string)$chat_id] = 4;
      }else{
        if($text == "/esci")
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ok");
        else{
          $msg12 = "ERRORE! Inserisci un comune valido";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg12)."");
        }
      }

    }else if($stato[(string)$chat_id] == 4){
      if(textCheck($text,$stato,$chat_id)){
        $provincia_utente = $text;
        $msg5 = "Inserisci la regione del comune";
        http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg5)."");
        $stato[(string)$chat_id] = 5;
      }else{
        if($text == "/esci")
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ok");
        else{
          $msg13 = "ERRORE! Inserisci una provincia valida";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg13)."");
        }
      }

    }else if($stato[(string)$chat_id] == 5){
      if(textCheck($text,$stato,$chat_id)){
        $regione_utente = $text;
        $msg6 = "Inserisci il nome della stazione";
        http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg6)."");
        $stato[(string)$chat_id] = 6;
      }else{
        if($text == "/esci")
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ok");
        else{
          $msg14 = "ERRORE! Inserisci una regione valida";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg14)."");
        }
      }

    }else if($stato[(string)$chat_id] == 6){
      if(is_numeric($text) || (strpos($text, '/') !== false)){
        if($text == "/esci"){
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ok");
          $stato[(string)$chat_id] = 0;
        }else{
          $msg15 = "ERRORE! Inserisci un nome valido";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg15)."");
        }
      }else{
        $nomestazione_utente = $text;
        $msg7 = "Inserisci la via della stazione";
        http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg7)."");
        $stato[(string)$chat_id] = 7;
      }

    }else if($stato[(string)$chat_id] == 7){
      if(is_numeric($text) || (strpos($text, '/') !== false)){
        if($text == "/esci"){
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ok");
          $stato[(string)$chat_id] = 0;
        }else{
          $msg16 = "ERRORE! Inserisci una via valida";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg16)."");
        }
      }else{
        $via_utente = $text." ".$comune_utente."";

        $dati_google2 = http_request("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($via_utente)."&key=".$google_key."");
        if($dati_google2['status'] != "ZERO_RESULTS"){
          $via_del_comune = $dati_google2['results'][0]['formatted_address'];
          if(strpos($via_del_comune, $comune_utente) !== false){
            $latitudine = $dati_google2['results'][0]['geometry']['location']['lat'];
            $longitudine = $dati_google2['results'][0]['geometry']['location']['lng'];
            http_request_post("https://fuel-stations-italy.herokuapp.com/stazione/add",$name,$last_name,$chat_id,$token,$nomestazione_utente,$comune_utente,$provincia_utente,$regione_utente,$longitudine,$latitudine);
            $msg8 = "Complimenti, hai inserito correttamente la tua stazione!\n";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg8)."");
            $stato[(string)$chat_id] = 0;
          }else{
            $msg17 = "ERRORE! Questa via non esiste a ".$comune_utente."";
            http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($msg17)."");
          }
        }else {
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=Questa via non esiste");
        }
      }
    }
    file_put_contents($last_update_filename, $update_id);
  }
}
?>
