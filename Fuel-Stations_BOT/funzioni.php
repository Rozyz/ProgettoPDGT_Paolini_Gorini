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
        }else if($text == "/esci"){
	  http_request($website."/sendmessage?chat_id=".$chat_id."&text=Ok"));
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

  function textCheck($text,&$stato,$chat_id){

    if (1 === preg_match('~[0-9]~', $text) || (strpos($text, '/') !== false)){
      if($text != "/esci")
        return false;
      else{
        $stato[(string)$chat_id] = 0;
        return false;
      }
    }else {
        return true;
    }
  }

  // Semplice libreria per le creazione di richieste HTTP
	function http_request($url)
	{
	    $handle = curl_init($url);
	    if($handle == false) {
	        die("Ops, cURL non funziona\n");
	    }
	    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	    // Esecuzione della richiesta, $response = contenuto della risposta testuale
	    $response = curl_exec($handle);
	    $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	    if($status != 200) {
	        die("Richiesta HTTP fallita, status {$status}\n");
	    }
	    curl_close($handle);

	    // Decodifica della risposta JSON
	    return json_decode($response, true);
	}

	function http_request_post($url,$first_name,$last_name,$id,$token,$cnome,$ccomune,$cprovincia,$cregione,$clongitudine,$clatitudine){
		$handle = curl_init($url);
		if($handle == false) {
			die("Ops, cURL non funziona\n");
	 	}

	 if(isset($token)){
		$data = "cnome=".urlencode($cnome)."&ccomune=".urlencode($ccomune)."&cprovincia=".urlencode($cprovincia)."&cregione=".urlencode($cregione)."&clongitudine=".urlencode($clongitudine)."&clatitudine=".urlencode($clatitudine);
	 }else{
	 	$data = "first_name=".$first_name."&last_name=".$last_name."&id=".$id;
	 }
	 curl_setopt($handle, CURLOPT_URL, $url);
	 curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	 curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	 // Esecuzione della richiesta, $response = contenuto della risposta testuale
	 $response = curl_exec($handle);
	 $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	 if($status != 200) {
			 die("Richiesta HTTP fallita, status {$status}\n");
	 }
	 curl_close($handle);

	 // Decodifica della risposta JSON
	 return json_decode($response, true);
	}

?>
