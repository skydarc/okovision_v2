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
	
	$id = $_GET['id'];
	$val = $_GET['val'];
	
	$ch = curl_init('http://'.CHAUDIERE.':'.PORT_JSON.'/'.PASSWORD_JSON.'/'.$id.'='.$val);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json charset=UTF-8'));

	// Return response instead of outputting
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$capt = utf8_encode(curl_exec($ch));
	echo $capt;
?>