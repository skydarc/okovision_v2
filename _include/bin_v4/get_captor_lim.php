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

	$id = explode(".", $_GET['id']);
	$system = $id[0];
	$captor = $id[1];

	$ch = curl_init('http://'.CHAUDIERE.':'.PORT_JSON.'/'.PASSWORD_JSON.'/'.$system.'?');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json charset=UTF-8'));

	// Return response instead of outputting
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute the POST request
	$json = utf8_encode(curl_exec($ch));
	$json = json_decode($json, true);
	
	echo json_encode($json[$system][$captor], JSON_UNESCAPED_UNICODE);
?>