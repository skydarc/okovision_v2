<?php
 $page = basename($_SERVER['SCRIPT_NAME']);
 $pageNotLogged = ['setup.php', 'index.php', 'histo.php'];

 if (!in_array($page, $pageNotLogged) && !session::getInstance()->getVar('logged')) {
     header('Location: /errors/401.php');
     exit();
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OkoVision</title>
    <script type="text/javascript">
            var sessionToken = "<?php echo session::getInstance()->getVar('sid'); ?>";		
   </script>
	<!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/jquery-ui.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/jquery-ui-timepicker-addon.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>+
    <![endif]-->
	<?php //include_once("analyticstracking.php");?>
	
	<script type="text/javascript">
		function afficheBar(){

			//var id = $(this).closest('.row').find('.huge').attr("id");
			//var name = $(this).closest('.panel').find('.labelbox').text();
			//var value = $(this).closest('.row').find('.huge').text().split(" ")[0];
			
			
			//$("#modal_change").modal('show');
			
			$.get('test.php', function(data) {
					
					alert(data);
					
					
				});
			
		
			
			/*$.api('POST', 'rt.getSensorInfo_v4', {
				sensor: id
			}).done(function(json) {
				
				//json = "eee";
				alert(json);
				
			});*/
		};
		afficheBar();
	</script>
	
	</head>

  <body role="document">
  