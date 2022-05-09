<?php
/*
* Projet : Okovision - Supervision chaudiere OeKofen
* Auteur : skydarc (inspired by Stawen Dronek)
* Utilisation commerciale interdite sans mon accord
*/

if (!file_exists('config.php')) {
    header('Location: setup.php');
} else {
    include_once 'config.php';
    include_once '_templates/header.php';
    include_once '_templates/menu.php';
}

?>  

    <div class="container theme-showcase" role="main">
           
         
		<div class="page-header">
		    <div class="row">
		        <div class="col-md-11 rtTitle"><?php echo session::getInstance()->getLabel('lang.text.page.rt.boilerName'); ?> <?php echo 'http://'.CHAUDIERE; ?></div>
		    </div>          
		</div>
		<div id="logginprogress" class="page-header" align="center">
            <p><span class="glyphicon glyphicon-refresh glyphicon-spin"></span>&nbsp;<?php echo session::getInstance()->getLabel('lang.text.page.rt.logginprogress'); ?></p>
        </div> 
        <div id="mustSaving" class="alert alert-warning" style="display: none;" role="alert">
              <span class="glyphicon glyphicon-floppy-save"></span>&nbsp;<?php echo session::getInstance()->getLabel('lang.text.page.rt.alertWarning'); ?>
        </div>
				
        <div id="communication" style="display: none;">
             
            <div class="tab-content">
                 
                 <div role="tabpanel" class="tab-pane active" id="indicateurs">  
                    
            		<div class="row">
					
            		    <div class="col-md-12" ><h2><small><?php echo session::getInstance()->getLabel('lang.text.page.rt.title.indic'); ?></small></h2></div>
            		    
						<div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:FA[0].L_modulationsstufe'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="pe1.L_modulation"></div>
                                        </div>
										<div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="refresh_v4"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:FA[0].L_modulationsstufe'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:FA[0].L_kesselstatus'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="pe1.L_state"></div>
                                        </div>
										<div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="refresh_v4"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:FA[0].L_kesselstatus'); ?></div>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-12" ><h2></h2></div>
            		    
						<div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    
                                    <div class="row">
                                        
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:FA[0].L_mittlere_laufzeit'); ?>" data-original-title="Tooltip"></span></div>
                                    
                                        <div class="col-xs-8 text-center">
                                            <div class="huge" id="pe1.L_avg_runtime"></div>											
                                        </div>
                                        <div class="col-xs-2"></div>
                                       
                                    </div>
                                    
                                    
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox">
										<?php 
											echo session::getInstance()->getLabel('lang.capteur.CAPPL:FA[0].L_mittlere_laufzeit'); 
										?>
									</div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:FA[0].L_brennerstarts'); ?>" data-original-title="Tooltip" ></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge" id="pe1.L_starts"></div>
                                        </div>
                                         <div class="col-xs-2"></div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:FA[0].L_brennerstarts'); ?></div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:FA[0].L_brennerlaufzeit_anzeige'); ?>" data-original-title="Tooltip"></span></div>
                                    
                                        <div class="col-xs-8 text-center">
                                            <div class="huge" id="pe1.L_runtime"></div>
                                        </div>
                                         <div class="col-xs-2"></div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:FA[0].L_brennerlaufzeit_anzeige'); ?></div>
                                </div>
                                
                            </div>
                        </div>
                        
                        <div class="col-md-12" ><h2><small><?php echo session::getInstance()->getLabel('lang.text.page.rt.title.tcambiante'); ?></small></h2></div>
                        
						<div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.hk[0].raumtemp_heizen'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="hk1.temp_heat"></div>
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="change_v4"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div> 
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.hk[0].raumtemp_heizen'); ?></div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.hk[0].raumtemp_absenken'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="hk1.temp_setback"></div>
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="change_v4"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.hk[0].raumtemp_absenken'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.hk[0].betriebsart[1]'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="hk1.mode_auto"></div>
                                        </div>
										<div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="change_list_v4"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.hk[0].betriebsart[1]'); ?></div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.L_hk[0].status'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="hk1.L_state"></div>
                                        </div>
										<div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="refresh_v4"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.L_hk[0].status'); ?></div>
                                </div>
                            </div>
                        </div>
                        
						<div class="col-md-12" ><h2><small><?php echo session::getInstance()->getLabel('lang.text.page.rt.title.ECS'); ?></small></h2></div>
                        
						<div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.L_ww[0].switch-on_sensor_actual'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="ww1.L_ontemp_act"></div>
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="refresh_v4"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div> 
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.L_ww[0].switch-on_sensor_actual'); ?></div>
                                </div>
                            </div>
                        </div>
						<div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.ww[0].temp_heizen'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="ww1.temp_max_set"></div>
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="change_v4"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div> 
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.ww[0].temp_heizen'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.ww[0].temp_absenken'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="ww1.temp_min_set"></div>
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="change_v4"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div> 
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.ww[0].temp_absenken'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.ww[0].betriebsart[1]'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="ww1.mode_auto"></div>
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="change_list_v4"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.ww[0].betriebsart[1]'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-left"><span class="glyphicon glyphicon-info-sign tip" title="<?php echo session::getInstance()->getLabel('lang.tooltip.CAPPL:LOCAL.ww[0].einmal_aufbereiten'); ?>" data-original-title="Tooltip"></span></div>
                                        <div class="col-xs-8 text-center">
                                            <div class="huge 2save" id="ww1.heat_once"></div>
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <a href="javascript:void(0)" class="change_list_v4"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="labelbox"><?php echo session::getInstance()->getLabel('lang.capteur.CAPPL:LOCAL.ww[0].einmal_aufbereiten'); ?></div>
                                </div>
                            </div>
                        </div>
						
                    </div>
                </div>
            </div>
        </div>      
        
        <div class="modal fade" id="modal_change" tabindex="-1" role="dialog" aria-labelledby="changeValue" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="sensorTitle"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="hidden">
                            <input type="text" id="sensorId">
                            <input type="number" id="sensorDivisor">
                            <input type="text" id="sensorUnitText">
                        </div>
                        <div class="col-md-6 text-center" id="sensorMin"></div>
                        <div class="col-md-6 text-center" id="sensorMax"></div>
                        <br/>
                        <form>
                                <input type="number" class="form-control text-center input-lg col-xs-10" id="sensorValue" step="0.1">
                        </form>
                        <br/> <br/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        </button>
                        <button type="button" id="btConfirmSensor_v4" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="modal fade" id="modal_change_list" tabindex="-1" role="dialog" aria-labelledby="changeValue" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="sensorTitle_list"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="hidden">
                            <input type="text" id="sensorId">
                            <input type="number" id="sensorDivisor">
                            <input type="text" id="sensorUnitText">
                        </div>
                        <br/>
                        <form>
							<select id="listChoise" class="form-control text-center input-lg col-xs-10">
							</select>	
                        </form>
                        <br/> <br/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        </button>
                        <button type="button" id="btConfirmList_v4" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

<?php
include __DIR__.'/_templates/footer.php';
?>
<!--appel des scripts personnels de la page -->
	<script src="js/jquery/jquery-ui-timepicker-addon.js"></script>
	<script src="_langs/<?php echo session::getInstance()->getLang(); ?>.datepicker.js"></script>
	<script src="js/rt_v4.js"></script>
	</body>
</html>
