/*****************************************************
 * Projet : Okovision - Supervision chaudiere OeKofen
 * Auteur : Stawen Dronek mod by skydarc for V2
 * Utilisation commerciale interdite sans mon accord
 ******************************************************/
/* global lang, $ */

$(document).ready(function() {

	/*
	 * Espace Information general
	 */

	$("#oko_typeconnect").change(function() {
		
		if ($(this).val() == 1) {
			$("#form-ip").show();
			$("#div_test_oko_ip").show();
			$("#form-json-port").hide();
			$("#form-json-pwd").hide();
			$("#form-mail").hide();
		}
		else if ($(this).val() == 2) {
			$("#form-ip").show();
			$("#div_test_oko_ip").hide();
			$("#form-json-port").show();
			$("#form-json-pwd").show();
			$("#form-mail").show();
		}
		else {
			$("#form-ip").hide();
			$("#div_test_oko_ip").hide();
			$("#form-json-port").hide();
			$("#form-json-pwd").hide();
			$("#form-mail").hide();
		}
		
	});

	$('#test_oko_ip').click(function() {


		//if(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test($('#oko_ip').val())){

		var ip = $('#oko_ip').val();

		$.api('GET', 'admin.testIp', {
			ip: ip
		}).done(function(json) {
			
			if (json.response) {
				
				$('#url_csv').html("");
				$.growlValidate(lang.valid.communication);
				$('#url_csv').append('<a target="_blank" href="' + json.url + '">' + lang.text.seeFileOnboiler + '</a>');
			}
			else {
				$.growlWarning(lang.error.ipNotPing);
			}
		});

		/*    
		}else{
		    $.growlErreur('Adresse Ip Invalide !');
		}
		*/
	});
	
	$('#test_oko_json').click(function() {


		var ip = $('#oko_ip').val();
		var port = $('#oko_json_port').val();
		var mdp = $('#oko_json_pwd').val();
		
		$.get('_include/bin_v4/get_softVersion.php?ip='+ip+'&port='+port+'&mdp='+mdp).done(function(jsdata) {
	
			//si ip ok
			if(jsdata != "") {
				
				const jsArray = JSON.parse(jsdata);
				
				//si port ok (si rep de la chaudiere)
				if( jsArray.data != "" ) {
					
					try {
						const jsResp = JSON.parse(jsArray.data);
					} catch (e) {
						$.growlWarning(lang.error.badpwd);
						return;
					}
					
					if (parseInt(jsArray.version[0]) >= 4) {
				
						//$('#url_csv').html("");
						$.growlValidate(lang.valid.communication);
						//$('#url_csv').append('<a target="_blank" href="' + json.url + '">' + lang.text.seeFileOnboiler + '</a>');
					} else {
						$.growlWarning(lang.error.tooldfirmware);
					}
					
					
				} else {
					$.growlWarning(lang.error.portNotRespond);
				}
			} else {
				$.growlWarning(lang.error.ipNotPing);
			}
		});
	});
	
	$('#test_mail').click(function() {

		var host = $('#mail_host').val();
		var login = $('#mail_log').val();
		var mdp = $('#mail_pwd').val();
		
		$.get('_include/bin_v4/test_mail.php?host='+host+'&login='+login+'&mdp='+mdp).done(function(jsdata) {
	
			//si ip ok
			if(jsdata == "success") {

				$.growlValidate(lang.valid.communication);
				//$('#url_csv').append('<a target="_blank" href="' + json.url + '">' + lang.text.seeFileOnboiler + '</a>');

			} else {
				$.growlWarning(lang.error.mailboxDontRespond);
			}
		});
	});
        
	$("#oko_loadingmode").change(function() {

		if ($(this).val() == 1) {
			$("#form-silo-details").show();
		}
		else {
			$("#form-silo-details").hide();
		}
	});
        
	$('#bt_save_infoge').click(function() {

		var tab = {
			oko_ip: $('#oko_ip').val(),
			oko_json_port: $('#oko_json_port').val(),
			oko_json_pwd: $('#oko_json_pwd').val(),
			mail_host: $('#mail_host').val(),
			mail_log: $('#mail_log').val(),
			mail_pwd: $('#mail_pwd').val(),
			param_tcref: $('#param_tcref').val(),
			param_poids_pellet: $('#param_poids_pellet').val(),
			surface_maison: $('#surface_maison').val(),
			oko_typeconnect: $('#oko_typeconnect').val(),
			timezone: $("#timezone").val(),
			send_to_web: 0,
            has_silo: $('#oko_loadingmode').val(),
            silo_size: $('#oko_silo_size').val(),
			ashtray : $('#oko_ashtray').val(),
			lang : $('input[name=oko_language]:checked').val()
		};
		
		$.api('POST', 'admin.saveInfoGe', tab, false).done(function(json) {
			//console.log(a);
			if (json.response) {
				$.growlValidate(lang.valid.configSave);
				setTimeout(function() {
					document.location.reload();
				  }, 1000);
				
			}
			else {
				$.growlWarning(lang.error.configNotSave);
			}
		});

	});

	


});