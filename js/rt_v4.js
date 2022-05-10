/*****************************************************
 * Projet : Okovision - Supervision chaudiere OeKofen
 * Auteur : Stawen Dronek
 * Utilisation commerciale interdite sans mon accord
 ******************************************************/
/* global lang, Highcharts, $ */
$(document).ready(function() {

    $.api('GET', 'graphique.getGraphe').done(function(json) {

        $.each(json.data, function(key, val) {
            $('#select_graphique').append('<option value="' + val.id + '">' + val.name + '</option>');
        });

    });

    $.IDify = function(text) {
        text = text.replace(/CAPPL:LOCAL\.|[\[\]]|CAPPL:/g, "");
        text = text.replace(/[\.\/]+/g, "_");
        return text;
    };

    function isArray(obj) {
        return Object.prototype.toString.call(obj) === '[object Array]';
    }

    function splat(obj) {
        return isArray(obj) ? obj : [obj];
    }

    $.connectBoiler = function() {

        $.api('GET', 'rt.getIndic').done(function(json) {

            if (json.response) {

                $.each(json.data, function(key, val) {
                    var id = $.IDify(key);
                    $('#' + id).html(val);
                    $('#' + id).attr("data-livevalue", val);
                });
                $('#logginprogress').hide();
                $('#communication').show();


            }
            else {
                $('#logginprogress').hide();
                $.growlErreur(lang.error.connectBoiler);
            }

            $.getListConfigboiler();
        });
    };
	
	function dec2bin(dec){
		var str = (dec >>> 0).toString(2);
		return str.split("").reverse().join("");
	}
		
	$.connectBoiler_v4 = function() {

		$.get('_include/bin_v4/test_boiler.php').done(function(jsdata) {

			const jsArray = JSON.parse(jsdata);
			const jsResp = JSON.parse(jsArray.data);

            if (jsArray.response) {

                $.each(jsResp, function(key1, val1) {
                    
					$.each(jsResp[key1], function(key2, val2) {
                    
						if($('#' + key1 + '\\.' + key2).length ) {
							
							if(key2 == 'L_state' && !val2.format) {
								
								const binStat = dec2bin(val2.val);
										
								if(key1 == 'hk1') {
									
									if(binStat[5] == '1') $('#' + key1 + '\\.' + key2).html('Confort');
									else if(binStat[4] == '1') $('#' + key1 + '\\.' + key2).html('R&eacute;duit');
									else if(binStat[7] == '1') $('#' + key1 + '\\.' + key2).html('Vacances');
									else $('#' + key1 + '\\.' + key2).html('Arr&ecirc;t');

								}
								
								if(key1 == 'ww1') {
									
									/*if(binStat[5]) $('#' + key1 + '\\.' + key2).html('Confort');
									else if(binStat[4]) $('#' + key1 + '\\.' + key2).html('R&eacute;duit');
									else if(binStat[7]) $('#' + key1 + '\\.' + key2).html('Vacances');
									else $('#' + key1 + '\\.' + key2).html('Arr&ecirc;t');*/

								}

							} else {
								
								if(val2.unit) {
									
									const value = (val2.val*val2.factor);
									
									if(value % 1 === 0){
									  $('#' + key1 + '\\.' + key2).html( value.toFixed(0)+' '+val2.unit);
								    } else{
									  $('#' + key1 + '\\.' + key2).html( value.toFixed(1)+' '+val2.unit);
								    }
									
								} else {
									
									if(val2.format){	
										var typeArray = val2.format.split('|');
										var typeStr = typeArray[val2.val].split(':');
										$('#' + key1 + '\\.' + key2).html( typeStr[1] );
									} 
									
									else $('#' + key1 + '\\.' + key2).html( val2.val );

								}
							}
						}
						
					});
					
                });
								
                $('#logginprogress').hide();
                $('#communication').show();


            }
            else {
                $('#logginprogress').hide();
                $.growlErreur(lang.error.connectBoiler);
            }
        });
    };

    $.hideData = function() {
        $('#logginprogress').show();
        $('#communication').hide();
    };



    var liveChart, refreshData;

    $.drawChart = function(idGraphe) {
        //console.log(liveChart);
        if (typeof liveChart !== 'undefined') {
            liveChart.destroy();
            clearInterval(refreshData);
        }

        liveChart = new Highcharts.Chart({
            chart: {
                renderTo: 'rt',
                type: 'spline',
                zoomType: 'x',
                panning: true,
                panKey: 'shift'
            },
            plotOptions: {
                spline: {
                    marker: {
                        enabled: false
                    }
                }
            },
            title: {
                text: ''
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    minute: '%H:%M',
                    hour: '%H:%M'

                },
                labels: {
                    rotation: -45,
                },
                title: {
                    text: lang.graphic.hour
                }
            },
            yAxis: [{
                title: {
                    text: '...',
                },
                min: 0
            }],
            tooltip: {
                shared: true,
                crosshairs: true,
                followPointer: true,
                formatter: function(tooltip) {
                    var items = this.points || splat(this);
                    // sort the values
                    items.sort(function(a, b) {
                        return ((a.y < b.y) ? -1 : ((a.y > b.y) ? 1 : 0));
                    });
                    items.reverse();

                    return tooltip.defaultFormatter.call(this, tooltip);
                }
            },
            exporting: {
                enabled: false
            }

        });

    };

    $("#grapheValidate").click(function() {
        //console.log('ici');
        var idGraphe = $('#select_graphique').val();

        $.drawChart(idGraphe);
        liveChart.showLoading(lang.graphic.loading);

        $.getUpdateData(idGraphe);

    });

    $.getUpdateData = function(idGraphe) {

        var firstData = 0;
        refreshData = setInterval(function() {

            $.api('GET', 'rt.getData', {
                id: idGraphe
            }).done(function(json) {

                // add the point
                $.each(json, function(key, val) {

                    if (typeof liveChart.series[key] == 'undefined') {
                        liveChart.addSeries(val, false);
                    }
                    else {
                        liveChart.series[key].addPoint(val.data, false);
                    }


                });

                liveChart.redraw();

                if (firstData < 2) {

                    $.each(liveChart.series, function(key) {
                        liveChart.series[key].removePoint(0);
                    });
                    firstData = firstData + 1;

                }
                else if (firstData === 2) {
                    liveChart.hideLoading();
                }

            });
        }, 3000);
    };



    $("#btconfirm").click(function(e) {
        var user = $('#okologin').val();
        var pass = $('#okopassword').val();

        if (user !== '' && pass !== '') {

            $.api('POST', 'rt.setOkoLogin', {
                user: user,
                pass: pass
            }).done(function(json) {
                
                $("#modal_boiler").modal('hide');
                $.hideData();
                
                if (!json.response) {
                    e.preventDefault();
                    $.growlErreur(lang.error.save);
                }
                else {
                    $.growlValidate(lang.valid.save);
                    $.connectBoiler();
                }

            });
        }

    });

	$.connectBoiler_v4();

    $("a[class~='change']").click(function() {

        var id = $(this).closest('.row').find('.huge').attr("id");
        var name = $(this).closest('.panel').find('.labelbox').text();
        var value = $(this).closest('.row').find('.huge').text().split(" ")[0];

        $.api('POST', 'rt.getSensorInfo', {
            sensor: id
        }).done(function(json) {

            var max = json.upperLimit / json.divisor;
            var min = json.lowerLimit / json.divisor;

            $("#sensorId").val(id);
            $("#sensorUnitText").val(json.unitText);
            $("#sensorTitle").html(name);
            $("#sensorMax").html('Max : ' + max);
            $("#sensorMin").html('Min : ' + min);

            $("#sensorValue").attr({
                "max": max,
                "min": min
            });

            $("#sensorValue").val(value);

            $("#modal_change").modal('show');
        });


    });
	
	$("a[class~='change_v4']").click(function() {

		var id = $(this).closest('.row').find('.huge').attr("id");
		var name = $(this).closest('.panel').find('.labelbox').text();
		var value = parseFloat($(this).closest('.row').find('.huge').text().split(" ")[0]);
				
		//alert(value);
		
		$.get( "_include/bin_v4/get_captor_lim.php?id="+id, function( data ) {
			
			const jsArray = JSON.parse(data);
		  
		    var max = jsArray.max * jsArray.factor;
            var min = jsArray.min * jsArray.factor;
		  
		    $("#sensorId").val(id);
		  
		    $("#sensorDivisor").val(jsArray.factor);
			$("#sensorUnitText").val(jsArray.unit);
            $("#sensorTitle").html(name);
            $("#sensorMax").html('Max : ' + max);
            $("#sensorMin").html('Min : ' + min);

            $("#sensorValue").attr({
                "max": max,
                "min": min
            });

            $("#sensorValue").val(value);

            $("#modal_change").modal('show');
		});
		
    });
	
	$("a[class~='change_list_v4']").click(function() {

		var id = $(this).closest('.row').find('.huge').attr("id");
		var name = $(this).closest('.panel').find('.labelbox').text();
		//var value = $(this).closest('.row').find('.huge').text().split(" ")[0];
				
		$.get( "_include/bin_v4/get_captor_lim.php?id="+id, function( data ) {
			
			
			const jsArray = JSON.parse(data);
			const oldValue = jsArray.val;
			const strFormat = jsArray.format;
			const arrayFormat = strFormat.split('|');
			//data.format
			
			//alert(oldValue);
			
			const listChoise = document.getElementById("listChoise");
			var opt;
			
			
			while (listChoise.length > 0) {
				listChoise.remove(0);
			}
						
			arrayFormat.forEach((item, index) => {
				
				var mode = item.split(':');

				opt = document.createElement("option");
				opt.value = mode[0];
				opt.text = mode[1];
				if(oldValue == mode[0]) opt.selected = "selected";

				listChoise.add(opt, null);
			})
			
		    $("#sensorId").val(id);
			
			$("#sensorTitle_list").html(name);
            $("#modal_change_list").modal('show');
		});
		
    });
	
	$("a[class~='refresh_v4']").click(function() {

		var id = $(this).closest('.row').find('.huge').attr("id");
				
		$.get( "_include/bin_v4/get_captor_lim.php?id="+id, function( data ) {
			
			
			const jsArray = JSON.parse(data);
			const newValue = jsArray.val;
						
			var idArray = id.split('.');
			$('#'+idArray[0]+'\\.'+idArray[1]).html(newValue.toFixed(1));
			
			
							if(idArray[1] == 'L_state' && !jsArray.format) {
								
								const binStat = dec2bin(jsArray.val);
											
								if(idArray[0] == 'hk1') {
									
									if(binStat[5] == '1') $('#' + idArray[0] + '\\.' + idArray[1]).html(lang.mode.conf);
									else if(binStat[4] == '1') $('#' + idArray[0] + '\\.' + idArray[1]).html(lang.mode.red);
									else if(binStat[7] == '1') $('#' + idArray[0] + '\\.' + idArray[1]).html(lang.mode.vac);
									else $('#' + idArray[0] + '\\.' + idArray[1]).html(lang.mode.off);

								}
								
								if(idArray[0] == 'ww1') {
									
									

								}

							} else {
								
								if(jsArray.unit) {
									
									const value = (jsArray.val*jsArray.factor);
									
									if(value % 1 === 0){
									  $('#' + idArray[0] + '\\.' + idArray[1]).html( value.toFixed(0)+' '+jsArray.unit);
								    } else{
									  $('#' + idArray[0] + '\\.' + idArray[1]).html( value.toFixed(1)+' '+jsArray.unit);
								    }
									
								} else {
									
									if(jsArray.format){	
										var typeArray = jsArray.format.split('|');
										var typeStr = typeArray[jsArray.val].split(':');
										$('#' + idArray[0] + '\\.' + idArray[1]).html( typeStr[1] );
									} 
									
									else $('#' + idArray[0] + '\\.' + idArray[1]).html( jsArray.val );

								}
							}
			
			
		});
		
    });

    $("#btConfirmSensor").click(function() {
        var id = $("#sensorId").val();
        var newValue = $("#sensorValue").val() + ' ' + $("#sensorUnitText").val();

        $.changeSensorValue(id, newValue);

        $("#modal_change").modal('hide');
    });
	
	$("#btConfirmSensor_v4").click(function() {
        var id = $("#sensorId").val();
		var unit = $("#sensorUnitText").val();
        var newValue = $("#sensorValue").val() * (1 / $("#sensorDivisor").val());
		
		$.get( "_include/bin_v4/set_captor.php?id="+id+"&val="+newValue, function( data ) {
			var retValue = data.split('=');
			
			newValue = retValue[1] * $("#sensorDivisor").val();
			
			if(isNaN(newValue)){
				alert(id+' '+data);
			}else{
				var idArray = id.split('.');
				
				if(newValue % 1 === 0){
					$('#'+idArray[0]+'\\.'+idArray[1]).html(newValue.toFixed(0)+' '+unit);
				} else{
					$('#'+idArray[0]+'\\.'+idArray[1]).html(newValue.toFixed(1)+' '+unit);
				}
			}
			
		});
		
        $("#modal_change").modal('hide');
    });
	
	$("#btConfirmList_v4").click(function() {
        var id = $("#sensorId").val();
        var newValue = $("#listChoise").val();
		
		const listChoise = document.getElementById("listChoise");
		
		$.get( "_include/bin_v4/set_captor.php?id="+id+"&val="+newValue, function( data ) {
			var retValue = data.split('=');
			
			newValue = retValue[1];
			
			if(isNaN(newValue)){
				alert(id+' '+data);
			}else{
				var idArray = id.split('.');
				
				$('#'+idArray[0]+'\\.'+idArray[1]).html(listChoise.options[newValue].text);
			}
			
		});
	
		 $("#modal_change_list").modal('hide');
    });

    $.viewMessageMustsave = function(b) {
        if (b) {
            $("#mustSaving").show('pulsate');
            $("a[href~='#config']").toggleClass("bg-warning");
        }
        else {
            $("#mustSaving").hide();
            $("a[href~='#config']").removeClass("bg-warning");
            $(".panel").switchClass('panel-warning', 'panel-primary', 0);
        }
    };

    $.changeSensorValue = function(id, value) {

        var oldValue = $("#" + id).data('livevalue');

        if (value.trim() !== $.trim(oldValue)) {
            $("#" + id).closest(".row").find('.huge').text(value);
            $("#" + id).closest(".panel").switchClass('panel-primary', 'panel-warning', 0);
            $.viewMessageMustsave(true);
        }
        else {
            $("#" + id).closest(".row").find('.huge').text(oldValue);
            $("#" + id).closest(".panel").switchClass('panel-warning', 'panel-primary', 0);

        }
    };

    $.getConfigBoiler = function(updateDataLive) {
        var json = {};

        $.each($(".2save"), function(key) {
            json[$(this).attr('id')] = $(this).html();
            if (updateDataLive) $("#" + $(this).attr('id')).attr("data-livevalue", $(this).html());

        });
        return json;
    };

    $.setConfigBoiler = function(json) {
        console.log(json);

        $.each(json, function(id, value) {
            $.changeSensorValue(id, value);
            $.viewMessageMustsave(true);
        });
    };

    $.getConfigToApply = function() {
        var json = {};

        $.each($(".panel-warning"), function(key) {
            //console.log($(this));
            json[$(this).find('.huge').attr('id')] = $(this).find('.huge').html();
        });
        console.log(json);
        return json;
    };

    $.getListConfigboiler = function() {

        $.api('GET', 'rt.getListConfigBoiler').done(function(json) {
            if (json.response) {
                $("#listConfig > tbody").html("");

                $.each(json.data, function(key, val) {
                    //console.log(val);
                    $('#listConfig > tbody:last').append('<tr id="' + val.timestamp + '"> \
				                                        	<td>' + val.date + '</td>\
				                                        	<td>' + val.description + '</td>\
				                                        	<td>\
				                                        	    <button type="button" class="btn btn-default btn-sm"> \
                                                                    <span class="glyphicon glyphicon-floppy-open" aria-hidden="true"></span> \
                                                                </button> \
                                                                <button type="button" id="delete" class="btn btn-default btn-sm"> \
                                                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> \
                                                                </button> \
				                                        	</td> \
				                                        </tr>');
                });

            }
            else {
                $.growlWarning(lang.error.getListConfigBoiler);
            }
        });
    };


    $("#configDescriptionSave").click(function() {

        var applyToBoiler = ($("#configTime").is(":visible")) ? false : true;
        //console.log('applyToBoiler::' + applyToBoiler);

        var config = $.getConfigBoiler(applyToBoiler);
        var desc = $("#configDescription").val();
        var date = '';

        if (desc == '') {
            $.growlWarning(lang.error.commentConfigBoiler);
        }
        else {
            //test si la date est visible ou non

            if (!applyToBoiler) {
                date = $("#configTimeSelect").val();
            }
            $.api('POST', 'rt.saveBoilerConfig', {
                config: config,
                description: desc,
                date: date
            }).done(function(json) {

                if (json.response) {

                    if (applyToBoiler) {
                        var config = $.getConfigToApply();
                        
                        if(!$.isEmptyObject(config)){
                            
                            $.api('POST', 'rt.applyBoilerConfig', { config: config}).done(function(json) {
                                $.growlValidate(lang.valid.applyConfigboiler);
                            });
                        }

                    }

                    $.growlValidate('Configuration sauvegardée ');
                    $("#configDescription").val("");
                    $.getListConfigboiler();

                    $.viewMessageMustsave(false);
                }
                else {
                    $.growlErreur(lang.error.saveBoilerConfig);
                }
            });
        }

    });

    $("body").on("click", ".btn", function() {
        //console.log($(this));
        if ($(this).is('#delete')) {
            $("#deleteid").val($(this).closest("tr").attr('id'));
            $("#modal_delete").modal('show');
        }

        if ($(this).is('#deleteConfirm')) {

            $.api('POST', 'rt.deleteConfigBoiler', {
                timestamp: $("#deleteid").val()
            }).done(function(json) {
                
                if (json.response) {
                    $("#modal_delete").modal('hide');
                    $.growlValidate(lang.valid.delete);
                    $.getListConfigboiler();
                }
                else {
                    $.growlErreur(lang.error.deleteBoilerConfig);
                }
                
            });
        }
        //relord config
        if ($(this).children().is('.glyphicon-floppy-open')) {

            $.api('POST', 'rt.getConfigBoiler', {
                timestamp: $(this).closest("tr").attr('id')
            }).done(function(json) {

                if (json.response) {
                    //$.connectBoiler();
                    $.setConfigBoiler(json.data);
                    $.viewMessageMustsave(true);
                }
                else {
                    $.growlErreur('rt.getConfigBoiler.error');
                }
            });
        }

    });

    $('#configTimeSelect').datetimepicker({
        timeFormat: "HH:mm:ss"
    });


    $("#btConfigTime").click(function() {
        // console.log('ici');
        $("#configTime").toggle();
        $('#configTimeSelect').datetimepicker('setDate', (new Date()));
    });





});
