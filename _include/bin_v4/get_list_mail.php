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
	DEFINE('LANG',$config['lang']);
	
	error_reporting(0);
	
	$files = scandir('/volume1/web/okovision/_tmp/');
	
	$imap_connection = imap_open(URL_MAIL, LOGIN_MAIL, PASSWORD_MAIL) ;
	
	$emails = imap_search($imap_connection,'ALL');
	
	if($emails) {
		
		$output['response'] = true;

		foreach($emails as $value => $email_number) {

			$structure = imap_fetchstructure($imap_connection,$email_number);
			
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

					$path_parts = pathinfo($name);
					if( strtolower($path_parts['extension']) == 'csv' ) {
						
						if( array_search($name, $files) ) {
							if(LANG == 'fr') $mailArray[$email_number] = $name.' <b class="red">déjà présent</b>';
							elseif(LANG == 'en') $mailArray[$email_number] = $name.' <b class="red">allready present</b>';
						} else $mailArray[$email_number] = $name;
						
					}
					
				}
			}
		}
		
		$output['mailArray'] = json_encode($mailArray, JSON_UNESCAPED_UNICODE);
		
		echo json_encode($output, JSON_UNESCAPED_UNICODE);
		
	} else {
		
		if($imap_connection) {
			$output['response'] = 'noMail';
			$output['mailArray'] = "nc";
			echo json_encode($output, JSON_UNESCAPED_UNICODE);
		}
		
		else echo '';
		
	}

?>