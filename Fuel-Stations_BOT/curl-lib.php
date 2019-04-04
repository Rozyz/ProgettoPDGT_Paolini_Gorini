<?php
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

	function http_request_post($url){
		$handle = curl_init($url);
		if($handle == false) {
			die("Ops, cURL non funziona\n");
	 	}
	 //$authorization = "Authorization: Bearer ".$token;
	 $data = "first_name=Carletto&last_name=Gigi&id=20";

	 //curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
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
