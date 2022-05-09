<?php
/*****************************************************
* Projet : Okovision - Supervision chaudiere OeKofen
* Auteur : skydarc (inspired by Stawen Dronek)
* Utilisation commerciale interdite sans mon accord
******************************************************/

	include_once 'config.php';
	include_once '_templates/header.php';
	include_once '_templates/menu.php';

?>
<div class="container theme-showcase" role="main">
<br/>
    <div class="page-header" >
        <h2><?php echo session::getInstance()->getLabel( 'lang.text.menu.manual.import.mail') ?></h2>
    </div>    
           
            <p><?php echo session::getInstance()->getLabel('lang.text.page.manual.mail.import') ?></p>
            <div id="inwork-remotefile" >
            <br/><span class="glyphicon glyphicon-refresh glyphicon-spin"></span><?php echo session::getInstance()->getLabel('lang.text.page.manual.workinprogress') ?></div>
                <table id="listeFichierFromMailBox" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-md-10"><?php echo session::getInstance()->getLabel('lang.text.page.manual.mail.filefromboiler') ?></th>
                        </tr>
                    </thead>
                
                    <tbody>
						
                    </tbody>
            
                </table>
            
				<button type="button" id="bt_import" class="btn btn-xs btn-default" >
					<span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span>
					<?php echo session::getInstance()->getLabel( 'lang.text.page.import.bt') ?>
				</button>
            </div>
            
    </div>

<?php
include(__DIR__ . '/_templates/footer.php');
?>
<!--appel des scripts personnels de la page -->
    <script src="js/jquery/jquery.fileupload.js"></script>
	<script src="js/amImpMail.js"></script>
    </body>
</html>
