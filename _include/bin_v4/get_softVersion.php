<?php 
/*
* Projet : Okovision - Supervision chaudiere OeKofen
* Auteur : skydarc
* Utilisation commerciale interdite sans mon accord
*/
	
	$ip = $_GET['ip'];
	$port = $_GET['port'];
	$mdp = $_GET['mdp'];
	
	$json = '';
	
	if ($fp = @fsockopen($ip, 80, $errCode, $errStr, 1)) {
        // It worked
        
		$ch = curl_init('http://'.$ip.':'.$port.'/'.$mdp.'/');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json charset=UTF-8'));

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$json = utf8_encode(curl_exec($ch));
		
		$resp['version'] = substr($json, strpos($json, '  V')+3, 5);
		
		sleep(3);
		
		$ch = curl_init('http://'.$ip.':'.$port.'/'.$mdp.'/all');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json charset=UTF-8'));

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$json = utf8_encode(curl_exec($ch));
		
		
		$resp['data'] = $json;
		
		print_r(json_encode($resp, JSON_HEX_AMP));
		
    } else {
		
        echo "";
    }
    @fclose($fp);
	
?>