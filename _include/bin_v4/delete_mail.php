<?php 
/*
* Projet : Okovision - Supervision chaudiere OeKofen
* Auteur : skydarc
* Utilisation commerciale interdite sans mon accord
*/

	$config = json_decode(file_get_contents("../../config.json"), true);
	DEFINE('URL_MAIL',$config['url_mail']);
	DEFINE('LOGIN_MAIL',$config['login_mail']); 
	DEFINE('PASSWORD_MAIL',$config['password_mail']);
	
	$list = $_GET['list'];
	$listArray = explode(":", $list);
	
	if( sizeof($listArray) == 2) {
		for($i = 0 ; $i < intval($listArray[1]) ; $i++ ) {
			$listMail[$i] = $i+1;
		}
		
		$listArray = $listMail;
		$listMail = explode(":", $list);
	}
	
	$id = 0;
	
	error_reporting(0);
	
	$imap_connection = imap_open(URL_MAIL, LOGIN_MAIL, PASSWORD_MAIL) ;
	
	$emails = imap_search($imap_connection,'ALL');
	
	if($emails) {
		
		$output['response'] = true;
		/* for every email... */
		foreach($emails as $value => $email_number) {

			$structure = imap_fetchstructure($imap_connection,$email_number);
			
			if($email_number == $listArray[$id]) {
			
				$attachments = array();
				if(isset($structure->parts) && count($structure->parts)) {

					for($i = 0; $i < count($structure->parts); $i++) {

						$attachments[$i] = array(
							'is_attachment' => false,
							'filename' => '',
							'name' => '',
							'attachment' => ''
						);

						if($structure->parts[$i]->ifdparameters) {
							foreach($structure->parts[$i]->dparameters as $object) {
								if(strtolower($object->attribute) == 'filename') {
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['filename'] = $object->value;
								}
							}
						}

						if($structure->parts[$i]->ifparameters) {
							foreach($structure->parts[$i]->parameters as $object) {
								if(strtolower($object->attribute) == 'name') {
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['name'] = $object->value;
								}
							}
						}

						if($attachments[$i]['is_attachment']) {
							$attachments[$i]['attachment'] = imap_fetchbody($imap_connection, $email_number, $i+1);
							if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
								$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
							}
							elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
								$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
							}
						}
					}
				}

				foreach ($attachments as $key => $attachment) {
					$name = $attachment['name'];
					
					$contents = $attachment['attachment'];
					
					if($name != "") {		
						file_put_contents('/volume1/web/okovision/archives/'.$name, $contents);
						
						if( sizeof($listArray) == 1 ) {
							imap_delete($imap_connection, $listArray[$id]);
						}
						$id++;
					}
				}
			}
		}
		
		if( sizeof($listArray) > 1 ) {
			imap_delete($imap_connection, $list);
		}
		imap_expunge($imap_connection);

		echo 'true';
		
	} else echo '';
	
?>