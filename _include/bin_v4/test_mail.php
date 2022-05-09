<?php 
/*
* Projet : Okovision - Supervision chaudiere OeKofen
* Auteur : skydarc
* Utilisation commerciale interdite sans mon accord
*/

	$host = $_GET['host'];
	$login = $_GET['login'];
	$mdp = $_GET['mdp'];
	
	error_reporting(0);
	
	$imap_connection = imap_open($host,$login,$mdp) ;
	
	if($imap_connection) echo "success";
	else echo "";
	
?>