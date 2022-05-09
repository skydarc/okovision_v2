/*****************************************************
 * Projet : Okovision - Supervision chaudiere OeKofen
 * Auteur : skydarc
 * Utilisation commerciale interdite sans mon accord
 ******************************************************/
/* global lang */
$(document).ready(function() {

	/*
	 * affichage de la liste de CSV en PJ contenu dans la mail box
	 */
	$.get('_include/bin_v4/get_list_mail.php').done(function(jsonMail) {

		const attachments = JSON.parse(jsonMail);
		
		if(attachments.response == true) {

			const mailList = JSON.parse(attachments.mailArray);
		
			var maxkey;

			$("#inwork-remotefile").hide();
			$("#listeFichierFromMailBox> tbody").html("");
			
			$('#listeFichierFromMailBox > tbody:last').append('<tr style="display: none;"><td><input type="checkbox" id="index_0"></td></tr>');
			
			$.each(mailList, function(key, mail) {
				
				maxkey = key;
				
				$('#listeFichierFromMailBox > tbody:last').append('<tr><td><input type="checkbox" id="index_'+key+'" name="'+key+'"> ' + mail + '</td><td><button type="button" id="del_mail" class="btn btn-default btn-sm" name="'+key+'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>');
				
			});
			
			$('#listeFichierFromMailBox > tbody:last').append('<tr><td><input type="checkbox" id="index_all"> ' + lang.text.importAll + '</td><td><button type="button" id="del_mail" class="btn btn-default btn-sm" name="1:'+maxkey+'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>');
			
		} else if(attachments.response == 'noMail') {
			
			$("#inwork-remotefile").hide();
			
			$.growlErreur(lang.error.noMail);
			
		} else {

			$("#inwork-remotefile").hide();
			
			$.growlErreur(lang.error.mailboxDontRespond);
			
		}
	});

	/*
	 * selectionne tout
	 */
	$("body").on("click", "[id^='index_all']:input", function() {
		if($('#index_0').is(':checked') ) {
			$('input:checkbox').prop('checked', false);
		} else {
			$('input:checkbox').prop('checked', true);
		}
	});
	
	$("#bt_import").click(function() {
        
		var mailSelected = [];
		$.each($("input:checkbox:checked"), function(){
			if( !isNaN($(this).attr('name')) ) {
				mailSelected.push($(this).attr('name'));
			}
        });
		$mailArray = mailSelected.join(",");
		
		if($mailArray != "") {
			$.get('_include/bin_v4/download_csv.php?list='+$mailArray).done(function(json) {
				if(json == 'true') $.growlValidate(lang.valid.csvImport);
				else $.growlErreur(lang.error.csvImport);
			});
		} else {
			$.growlErreur(lang.error.noSelect);
		}
    });
	

	$("body").on("click", "[id^='del_mail']:button", function() {
		
		$.get('_include/bin_v4/delete_mail.php?list='+$(this).attr('name')).done(function(json) {
			
			if(json == 'true') $.growlValidate(lang.valid.delMail);
			else $.growlErreur(lang.error.delMail);
			
			setTimeout(function () {
				location.reload(true);
			}, 2000);
			
		});
		
		
	});

	
});