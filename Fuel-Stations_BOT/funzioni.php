<?php
  require_once(dirname(__FILE__).'/token.php');

  function sendDatasStazioni($comune,$provincia,$regione,$nomestazione){

  }

  function getDatas($infos,$website,$chat_id,$google_key){
    if(isset($infos)){
      $i = 0;
      $j = 0;
      $contatore = 0;
      $stringa = "";

      foreach($infos as $info){
        $comune[$i] = $info['ccomune'];
        $nome[$i] = $info['cnome'];
        $provincia[$i] = $info['cprovincia'];
        $lat[$i] = $info['clatitudine'];
        $lon[$i] = $info['clongitudine'];
        $i++;
        $contatore++;
      }

      while($i != 0){
        $i--;
        if($nome[$i] == '')
          $nome[$i] = "/";
        $stringa = $stringa."Nome: {$nome[$i]}, Latitudine: {$lat[$i]}, Longitudine: {$lon[0]}\n";
      }

      $dati_google = http_request("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat[0].",".$lon[0]."&key=".$google_key."");
      foreach($dati_google as $via){
        $address = $via['results'][$j]['formatted_address'];
        $j++;
      }
      //$address = $dati_google['results']['formatted_address'];


      http_request($website."/sendmessage?chat_id=".$chat_id."&text=A ".$comune[0]." ci sono ".$contatore." stazioni e sono: ".urlencode($stringa)."");
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=".$address."");
    }
    else {
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ops.. non è stata trovata alcuna stazione di benzina in questa città. Vuoi aggiungerla tu? Digita /add e aiutaci a completare la nostra mappatura!");
    }
  }

?>
