<?php
  require_once(dirname(__FILE__).'/token.php');

  function getDatas($infos,$website,$chat_id,&$stato,$text,$via_utente,$latitudine,$longitudine){
    if(isset($infos)){
      $i = 0;
      $j = 0;
      $contatore = 0;
      $stringa = "";
      $string = "";
      $coordinate = "";

      foreach($infos as $info){
        $comune[$i] = $info['ccomune'];
        $nome[$i] = $info['cnome'];
        $provincia[$i] = $info['cprovincia'];
        $regione[$i] = $info['cregione'];
        $lat[$i] = $info['clatitudine'];
        $lon[$i] = $info['clongitudine'];
        $dati_google = http_request("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat[$i].",".$lon[$i]."&key=AIzaSyCtNEV0lStU80DF9xGOx0GfX30WFA0qZtY");
        $address[$i] = $dati_google['results'][0]['formatted_address'];
        $dato = explode ("," , $address[$i]);
        $via[$i] = $dato[0];
        if($stato[(string)$chat_id] == 2 && $text == $i+1){
          $string = $string."Comune: {$comune[$i]}\nProvincia: {$provincia[$i]}\nRegione: {$regione[$i]}\nNome stazione: {$nome[$i]}\nAddress: {$address[$i]}\n";
        }
        $i++;
      }

      if($stato[(string)$chat_id] == 1){
        while($j != $i){
          $contatore++;
          if($nome[$j] == '')
            $nome[$j] = "/";
          $stringa = $stringa."\n$contatore)"."$via[$j]";
          $j++;
        }

        http_request($website."/sendmessage?chat_id=".$chat_id."&text=A ".$comune[0]." ci sono ".$contatore." stazioni e sono: ".urlencode($stringa)."");
        http_request($website."/sendmessage?chat_id=".$chat_id."&text=Inserisci il numero della stazione");
        $stato[(string)$chat_id] = 2;
      }else if ($stato[(string)$chat_id] == 2){
        if(is_numeric($text) && ($text >= 1 && $text <= $i)){
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($string));
          http_request($website."/sendLocation?chat_id=".$chat_id."&latitude=".$lat[$text-1]."&longitude=".$lon[$text-1]."");
          $stato[(string)$chat_id] = 0;
        }else{
          $stringerr = "La stazione selezionata è errata!\nDigita un comando valido\n";
          http_request($website."/sendmessage?chat_id=".$chat_id."&text=".urlencode($stringerr));
          $stato[(string)$chat_id] = 2;
        }
      }
    }else {
      http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ops.. non è stata trovata alcuna stazione di benzina in questa città. Vuoi aggiungerla tu? Digita /add e aiutaci a completare la nostra mappatura!");
      $stato[(string)$chat_id] = 0;
    }
  }


?>
