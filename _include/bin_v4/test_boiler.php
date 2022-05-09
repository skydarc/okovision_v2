<?php 
/*
* Projet : Okovision - Supervision chaudiere OeKofen
* Auteur : skydarc
* Utilisation commerciale interdite sans mon accord
*/

	$config = json_decode(file_get_contents("../../config.json"), true);
	DEFINE('CHAUDIERE',$config['chaudiere']); 
	DEFINE('PORT_JSON',$config['port_json']); 
	DEFINE('PASSWORD_JSON',$config['password_json']);
	
	$ch = curl_init('http://'.CHAUDIERE.':'.PORT_JSON.'/'.PASSWORD_JSON.'/all?');

	// Return response instead of outputting
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$capt = utf8_encode(curl_exec($ch));
	
	if($capt) {
		$json['data'] = $capt;
		$json['response'] = true;

		print_r(json_encode($json, JSON_HEX_AMP));

	} else {
		$json['response'] = false;
		print_r($json);
	}
?>