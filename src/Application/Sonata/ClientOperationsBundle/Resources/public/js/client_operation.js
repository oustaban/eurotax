jQuery(document).ready(function ($) {
    $('#error_repost_show').die().live('click', function () {
        $('#importReportModal').modal('toggle');
        return false;
    });

    
    //links are disabled when scrolled down, hence activate it
    $('.sonata-ba-list .nav-pills li a').live('click', function(){
    	window.location = $(this).attr('href');
    });
    
    if (typeof symfony_ajax != 'undefined'){
    	
    	
        symfony_ajax.behaviors.PaiementDateCloneMoisdeTVA = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();

                //-- V01-TVA || A02-TVA
                //The default value should be null for “Mois de TVA”  When we select a “Date de paiement” .. we must initialize the value of “Mois de TVA”
                if (($('#' + _uniqid + '_paiement_date', context).size() || $('#' + _uniqid + '_date_piece', context).size()) && $('#' + _uniqid + '_mois_mois', context).size()) {
                    $('#' + _uniqid + '_date_piece, #' + _uniqid + '_paiement_date', context).change(function(){
                    	var date = $('#' + _uniqid + '_paiement_date');
                        if(date.val()) {
                        	if(!$('#' + _uniqid + '_mois_mois').attr('disabled')) {
                    			$('#' + _uniqid + '_mois_mois option:last').attr('selected', true).trigger('change');
                        	}
                        } else if (symfony_ajax.is_new()) {
                        	$('#' + _uniqid + '_mois_mois option:last').removeAttr('selected');
                        }
                    });//.trigger('change');
                }
                
                
                // v03283i
                $('#' + _uniqid + '_taux_de_change').keyup(function() {
                	$('#' + _uniqid + '_HT').val( euro_num_format(  real_num($('#' + _uniqid + '_montant_HT_en_devise').val()) / real_num($('#' + _uniqid + '_taux_de_change').val()) ) );
                });            

                
                
                $('#' + _uniqid + '_montant_HT_en_devise').blur(function() {
                	$(this).val( euro_num_format($(this).val()) );
                }).keyup(function() {
                	var result = real_num($(this).val()) / real_num($('#' + _uniqid + '_taux_de_change').val());
                	if (result != Number.POSITIVE_INFINITY && result != Number.NEGATIVE_INFINITY) {
                		$('#' + _uniqid + '_HT').val( euro_num_format( result ) );
                	}
                	
                });
                if ( ( $('#' + _uniqid + '_date_piece', context).size() || $('#' + _uniqid + '_devise', context).size() ) && $('#' + _uniqid + '_mois_mois', context).size() && $('#' + _uniqid + '_paiement_date', context).size() == 0 ) {
                    $('#' + _uniqid + '_date_piece, #' + _uniqid + '_devise, #' + _uniqid + '_mois_mois', context).change(function(){
                        //$('#' + _uniqid + '_mois_mois option:last').attr('selected', true).trigger('change');
                        var devise = $('#' + _uniqid + '_devise :selected').val();
                        var date_piece = $('#' + _uniqid + '_date_piece').val();
                        var montant_HT_en_devise = $('#' + _uniqid + '_montant_HT_en_devise').val();
                        if(devise && date_piece) {
                        	
	                        $.ajax({
	                            url:Sonata.url.rdevises,
	                            type:'POST',
	                            data:{
	                                devise:devise,
	                                date:date_piece
	                            },
	                            dataType:'json',
	                            async:false,
	                            success:function (i) {
	                                $('#' + _uniqid + '_taux_de_change').val(i.value ? (i.value) : '');
	                                $('#' + _uniqid + '_HT').val( euro_num_format( real_num(montant_HT_en_devise) / parseFloat(i.value) ) );
	                            }
	                        });
                        }

                        
                        
                    }).trigger('change');
                }
                
                
            }
        };

        symfony_ajax.behaviors.rDevises = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();
                if (_uniqid) {
                	
                	 //V01-TVA
                	/*
                	 * when changing the Devise, copy the same value on Devise du paiement (  but we can change after “Devise de paiemen” if we want that the data are different
                	 */
                    $('#' + _uniqid + '_devise').change(function() {                    	
                    	$('#' + _uniqid + '_paiement_devise').val($(this).val()).trigger('change');                    	
                    });
                	
                	
                	
                    $('#' + _uniqid + '_paiement_devise, #' + _uniqid + '_paiement_date', context).change(function () {
                        var _uniqid = symfony_ajax.get_uniqid();

                        var devise = $('#' + _uniqid + '_paiement_devise :selected').val();
                        var paiement_date = $('#' + _uniqid + '_paiement_date').val();
                        var paiement_montant = $('#' + _uniqid + '_paiement_montant').val();
                        
                        //If « Date du paiement » is empty.. « Mois de TVA » should be empty too
                        // When I delete the data “Date de paiement”, I should empty the field “Taux de change”, “HT” and “TVA”
                        if(paiement_date == '') {
                        	//$('#' + _uniqid + '_mois_mois').val('');
                        	
                        	$('#' + _uniqid + '_mois_mois option').removeAttr('selected');
                        	
                        	$('#' + _uniqid + '_mois_month option').removeAttr('selected');
                        	$('#' + _uniqid + '_mois_day option').removeAttr('selected');
                        	$('#' + _uniqid + '_mois_year option').removeAttr('selected');
                        	
                        	
                        	$('#' + _uniqid + '_taux_de_change').val('');
                        	$('#' + _uniqid + '_TVA').val('');
                        	$('#' + _uniqid + '_HT').val('');
                        	
                        	
                        }
                        
                        //V01-TVA
                        /*//When "Devise du paiement" is changed... put on "Devise" the same value thate "Devise du paiement"
                        if(devise) {
                        	$('#' + _uniqid + '_devise').val(devise);
                        }*/
                        if (devise && paiement_date && paiement_montant) {
                            $.ajax({
                                url:Sonata.url.rdevises,
                                type:'POST',
                                data:{
                                    devise:devise,
                                    date:paiement_date
                                },
                                dataType:'json',
                                async:false,
                                success:function (i) {
                                    $('#' + _uniqid + '_taux_de_change').val(i.value ? (i.value) : '').trigger('change');
                                }
                            });
                        }
                    }).trigger('change');
                }
            }
        };

        symfony_ajax.behaviors.calcMontantTVAfrancaiseAndMontantTTC = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();
                if (_uniqid) {
                    var $montant_HT_en_devise = $('#' + _uniqid + '_montant_HT_en_devise', context);
                    var $taux_de_TVA = $('#' + _uniqid + '_taux_de_TVA', context);
                    var $montant_TTC = $('#' + _uniqid + '_montant_TTC', context);
                    var $paiement_montant = $('#' + _uniqid + '_paiement_montant', context);
                    var $taux_de_change = $('#' + _uniqid + '_taux_de_change', context);
                    var $HT = $('#' + _uniqid + '_HT', context);

                    if ($montant_HT_en_devise.length && $taux_de_TVA.length){
                        $montant_HT_en_devise.change(this.calc).keyup(this.calc);
                        $taux_de_TVA.change(this.calc).trigger('change');
                    }
                    if($montant_TTC.length){
                        $montant_TTC.change(this.calc_paiement_montant).keyup(this.calc_paiement_montant).trigger('change');
                    }
                    if($paiement_montant.length && $taux_de_change.length && $taux_de_TVA.length){
                        $paiement_montant.change(this.calc_HT).keyup(this.calc_HT);
                        $taux_de_change.change(this.calc_HT).keyup(this.calc_HT);
                        $taux_de_TVA.change(this.calc_HT).trigger('change');
                    }
                    if($HT.length && $taux_de_TVA.length){
                        $HT.change(this.calc_TVA).keyup(this.calc_TVA);
                        $taux_de_TVA.change(this.calc_TVA).trigger('change');
                    }
                }
            },
            number_limit:function (e) {
                var chr = String.fromCharCode(e.charCode == undefined ? e.keyCode : e.charCode);
                return (chr < ' ' || (chr >= '0' && chr <= '9') || chr == ',' || chr == '.');
            },
            calc_TVA:function(e){
                var m = 100000;

                var _uniqid = symfony_ajax.get_uniqid();

                var $HT = $('#' + _uniqid + '_HT');
                var $taux_de_TVA = $('#' + _uniqid + '_taux_de_TVA');

                var taux_de_TVA = real_num($taux_de_TVA.val());
                var HT = real_num($HT.val());

                var taux_de_TVA_X_m = Math.round(parseFloat(taux_de_TVA)*m);
                var HT_X_m = Math.round(parseFloat(HT)*m);

                if (taux_de_TVA_X_m && HT_X_m){
                	//TVA = HT x Taux de TVA%
                	var TVA = HT * taux_de_TVA;
                    //var TVA = (taux_de_TVA_X_m * HT_X_m) / m / m; //old formula
                    TVA = TVA ? TVA.toString().replace('.', ',') : '';
                    
                    if (TVA != Number.POSITIVE_INFINITY && TVA != Number.NEGATIVE_INFINITY) {
                    
                    	$('#' + _uniqid + '_TVA').val(euro_num_format(TVA)).trigger('change');
                    }
                }
            },
            calc_HT:function(e){
                var m = 10000000;

                var _uniqid = symfony_ajax.get_uniqid();

                
                var $montant_TTC = $('#' + _uniqid + '_montant_TTC');
                
                var $taux_de_TVA = $('#' + _uniqid + '_taux_de_TVA');
                var $paiement_montant = $('#' + _uniqid + '_paiement_montant');
                var $taux_de_change = $('#' + _uniqid + '_taux_de_change');

                var montant_TTC = real_num($montant_TTC.val());
                var taux_de_TVA = real_num($taux_de_TVA.val());
                var paiement_montant = real_num($paiement_montant.val());
                var taux_de_change = real_num($taux_de_change.val());

                var taux_de_TVA_X_m = Math.round(parseFloat(taux_de_TVA)*m);
                var paiement_montant_X_m = Math.round(parseFloat(paiement_montant)*m);
                var taux_de_change_X_m = Math.round(parseFloat(taux_de_change)*m);

                if (montant_TTC && taux_de_TVA_X_m && paiement_montant_X_m && taux_de_change_X_m){
                	// HT = paiement_montant / (1+Taux de TVA%) / Taux de Change
                	var HT = paiement_montant / (1 + parseFloat(taux_de_TVA)) / taux_de_change;
                    //var HT = paiement_montant_X_m * m / ((m + taux_de_TVA_X_m) * taux_de_change_X_m); //old formula
                    HT = HT ? HT.toString().replace('.', ',') : '';

                    $('#' + _uniqid + '_HT').val(euro_num_format(HT)).trigger('change');
                }
            },
            calc_paiement_montant:function(e){
            	
            	//console.log('calc_paiement_montant');
            	
                var _uniqid = symfony_ajax.get_uniqid();
                var $montant_TTC = $('#' + _uniqid + '_montant_TTC');
                var $paiement_montant = $('#' + _uniqid + '_paiement_montant');
                var pm = $montant_TTC.val();
                

                if(pm && symfony_ajax.is_new()) {
                	$paiement_montant.val(euro_num_format(pm)).trigger('change');
                } else {
                	//format value only
                	/*if($paiement_montant.val()) {
                		$paiement_montant.val(euro_num_format($paiement_montant.val()));
                	}*/
                }
                
            },
            calc:function (e) {
                var m = 100000;
                var _uniqid = symfony_ajax.get_uniqid();
                var $montant_HT_en_devise = $('#' + _uniqid + '_montant_HT_en_devise');
                var $taux_de_TVA = $('#' + _uniqid + '_taux_de_TVA');
                var montant_HT_en_devise = real_num($montant_HT_en_devise.val());
                var taux_de_TVA = real_num($taux_de_TVA.val());
                var montant_HT_en_devise_X_m = Math.round(parseFloat(montant_HT_en_devise)*m);
                var taux_de_TVA_X_m = Math.round(parseFloat(taux_de_TVA)*m);
                var montant_TVA_francaise = (montant_HT_en_devise_X_m * taux_de_TVA_X_m) / m / m;
                var montant_TTC = montant_HT_en_devise_X_m / m + montant_TVA_francaise;
                montant_TVA_francaise = montant_TVA_francaise ? euro_num_format(montant_TVA_francaise) : '';
                montant_TTC = montant_TTC ? euro_num_format(montant_TTC) : '';
                $('#' + _uniqid + '_montant_TVA_francaise').val(montant_TVA_francaise).trigger('change');
                $('#' + _uniqid + '_montant_TTC').val(montant_TTC).trigger('change');
            }
        };
        
        
        
        
        
        symfony_ajax.behaviors.hideDelUpdateButtons = {
                attach:function (context) {
                    var _uniqid = symfony_ajax.get_uniqid();
                    if (_uniqid) {
                    	var status = $('#' + _uniqid + '_status_id', context);
                    	//var locking = $('#' + _uniqid + '_locking', context);
                    	
                    	if(status && status.val() == 1) { // status = Vérouillé
                    		$('.action-buttons').hide();
                    		$('.popup-body .content').prepend('<div class="alert alert-error">MOIS-TVA cloturé - Aucune modification possible.</div>');
                    	} else {
                    		$('.action-buttons').show();
                    	}
                    }
                }
                
        };
        
        
        symfony_ajax.behaviors.debEntityTopInfo = {
                attach:function (context) {
                    var _uniqid = symfony_ajax.get_uniqid();
                    if (_uniqid) {
                    	if(typeof Sonata.active_tab !== 'undefined') {
                    		if(Sonata.active_tab == 'debexped' || Sonata.active_tab == 'debintro') {
                    			$('.form-horizontal').before('<div class="alert">Certains champs ne sont obligatoires que selon le Niveau de DEB et le Régime</div>');
                    		}
                    	}
                    }
                }
                
        };
        
        
        
        
        symfony_ajax.behaviors.positiveNumberOnly = {
                attach:function (context) {
                    var _uniqid = symfony_ajax.get_uniqid();
                    if (_uniqid) {
                    
                    	
                    	if($('#' + _uniqid + '_masse_mette, #' + _uniqid + '_unites_supplementaires', context).size()) {
                    		
                    		
                    		$('#' + _uniqid + '_masse_mette, #' + _uniqid + '_unites_supplementaires', context).keydown(function(event) {
                    			var key = event.charCode || event.keyCode || 0;
                    			// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
                    			// home, end, period, and numpad decimal

                    			//console.log(key);

                    			// Allow: backspace, delete, tab, escape, and enter
                    			if ( key == 46 || key == 8 || key == 9 || key == 27 || key == 13 || 
                    					// Allow: Ctrl+A
                    					(key == 65 && event.ctrlKey === true) || 
                    					// Allow: home, end, left, right
                    					(key >= 35 && key <= 40)) {
                    				// let it happen, don't do anything
                    				return;
                    			}
                    			else {
                    				// Ensure that it is a number and stop the keypress
                    				if (event.shiftKey || (key < 48 || key > 57) && (key < 96 || key > 105 )) {
                    					event.preventDefault(); 
                    				}   
                    			}

                    		});
                    		
                    	}
                    	
                    }
                }
        };
        
    }
    
    
});


function substr_replace($object, limit) {
    if ($object && $object.length){
        var result = $object.val().split(',');
        var result2 = $object.val().split('.');
        if ((result[1] && result[1].length > limit) || result2[1] && result2[1].length > limit) {
            $object.val($object.val().substr(0, $object.val().length - 1)).trigger('change');
        }
    }
}

function init_clientoperations_buttons(o) {

	if(typeof Sonata !== 'undefined' && typeof Sonata.locked !== 'undefined' && typeof Sonata.active_tab !== 'undefined') {
		if(Sonata.locked == 1 && (Sonata.active_tab == 'debexped' || Sonata.active_tab == 'debintro')) {
			$('#block_actions .btn-add').addClass('disabled').attr('href', 'javascript:void(0);');
			
		}
		
		if(Sonata.locked == 1) {
			$('#toggleImportModal').addClass('disabled').attr('href', 'javascript:void(0);');
		}
	}
	
    var btn_client_alert_click = false;
    $('#btn_client_alert').live('click', function (event) {

        symfony_ajax.behaviors.alert_popo = {
            attach:function (context) {
                $('.modal-body .table td').removeAttr('objectid');
            }
        }

        field_dialog_form_add__id(event, o.alert.link, {title:o.alert.title });
        return false;
    });

  
    
    if (o.blocking.permit) {
        $("#btn_locking").click(function () {
            if (o.blocking.isBlocked && o.blocking.hasBlocking && $('#btn_client_alert').size()) {
                $('#btn_client_alert').click();
            }
            else {
                if (confirm(o.blocking.text)) {
                	if(o.blocking.link)
                    	location.href = o.blocking.link;
                	else
                		return true;
                }
            }
            
            return false;
        });
    }


    $('input[id=inputFile]').change(function () {
        $('#inputFileCover').val($(this).val());
    });

    $('#deleteImport').click(function () {
        $.ajax({
            url:o.import.link,
            dataType:'json',
            success:function (json) {
                var $table = $('<table class="table table-bordered table-striped table-hover" style="width: auto;"><thead></thead></table>');
                $('<tr class="sonata-ba-list-field-header" />')
                    .append('<th class="sonata-ba-list-field-header-integer">N°</th>')
                    .append('<th class="sonata-ba-list-field-header-date">Date</th>')
                    .append('<th class="sonata-ba-list-field-header-orm_many_to_one">Utilisateur</th>')
                    .append('<th class="sonata-ba-list-field-header-string">Fichier</th>')
                    .append('<th class="sonata-ba-list-field-header-actions">Action</th>')
                    .appendTo($table.children('thead'));

                var $tbody = $('<tbody />');
                for (i in json.imports) {
                    var import_id = json.imports[i].id;
                    var deleteUrl = o.import.delete.replace("__id__", import_id);
                    $('<tr />')
                        .append('<td class="sonata-ba-list-field-integer">' + import_id + '</td>')
                        .append('<td class="sonata-ba-list-field-date"><b>' + json.imports[i].date.date + '</b></td>')
                        .append('<td class="sonata-ba-list-field-orm_many_to_one">' + json.imports[i].username + '</td>')
                        .append('<td class="sonata-ba-list-field-string">' + json.imports[i].filename + '</td>')
                        .append('<td class="sonata-ba-list-field-actions"><a style="display: inline;" class="import_del" href="' + deleteUrl + '">Supprimer</a></td>')
                        .appendTo($tbody);
                }
                $table.append($tbody);

                // populate the popup container
                field_dialog__id.find('.popup-body').html('').append($table);
                field_dialog__id.find('.title').html(json.title);
                field_dialog__id.modal('toggle');

                $table.find('a.import_del').click(function () {
                    return confirm(o.import.confirm);
                });
            }
        });
    });
}

function init_rapprochement_sums() {

	
	 //Total 
    var TOTAL_DEBExped_valeur_fiscale_HT = 0, TOTAL_DEBExped_valeur_stat_HT = 0;
    $('#DEB-Exped').find('.totals').each(function() {
    	var num = real_num($(this).html());
    	/*var isRegime25 = $(this).attr('rel') == 'deb-25' ? true : false;
		if(isRegime25) {
			num = real_num($(this).html()) * -1;
				
		}*/
		
		if($(this).hasClass('v1')) {
			TOTAL_DEBExped_valeur_fiscale_HT+=num;
		} else if($(this).hasClass('v2')) {
			TOTAL_DEBExped_valeur_stat_HT+=num;
		}
    });
    
    var NEWTR = '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr> \
    <tr> \
    <td class="sonata-ba-list-field sonata-ba-list-field-text total" title="TOTAL DEB-Exped"><b>TOTAL DEB-Exped </b> </td> \
    <td class="sonata-ba-list-field sonata-ba-list-field-money total"> \
        <div class="TOTAL_DEBExped_valeur_fiscale_HT" rel=""><b>' + euro_num_format(TOTAL_DEBExped_valeur_fiscale_HT) + '</b></div> \
    </td> \
    <td class="sonata-ba-list-field sonata-ba-list-field-integer total">&nbsp;</td> \
    <td class="sonata-ba-list-field sonata-ba-list-field-money total"> \
                            <div class="TOTAL_DEBExped_valeur_stat_HT" rel=""><b>' + euro_num_format(TOTAL_DEBExped_valeur_stat_HT) + '</b></div> \
	    </td></tr>\
	    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
    
    $('#DEB-Exped table tbody').prepend(NEWTR);
    
    
    var TOTAL_DEBIntro_valeur_fiscale_HT = 0, TOTAL_DEBIntro_valeur_stat_HT = 0;
    $('#DEB-Intro').find('.totals').each(function() {
    	var num = real_num($(this).html());
    		
		if($(this).hasClass('v1')) {
			TOTAL_DEBIntro_valeur_fiscale_HT+=num;
		} else if($(this).hasClass('v2')) {
			TOTAL_DEBIntro_valeur_stat_HT+=num;
		}
    });
    
    var NEWTR = '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr> \
    <tr> \
    <td class="sonata-ba-list-field sonata-ba-list-field-text total" title="TOTAL DEB-Intro"><b>TOTAL DEB-Intro </b> </td> \
    <td class="sonata-ba-list-field sonata-ba-list-field-money total"> \
        <div class="TOTAL_DEBIntro_valeur_fiscale_HT" rel=""><b>' + euro_num_format(TOTAL_DEBIntro_valeur_fiscale_HT) + '</b></div> \
    </td> \
    <td class="sonata-ba-list-field sonata-ba-list-field-integer total">&nbsp;</td> \
    <td class="sonata-ba-list-field sonata-ba-list-field-money total"> \
                            <div class="TOTAL_DEBIntro_valeur_stat_HT" rel=""><b>' + euro_num_format(TOTAL_DEBIntro_valeur_stat_HT) + '</b></div> \
	    </td></tr>\
	    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
    
    $('#DEB-Intro table tbody').prepend(NEWTR);
	
	
	
	
    var ECARTsumm = 0;

    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_input table.table tr.totals_row div b :first');
    $plus = $plus.length ? $plus.html() : '0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_input table.table tr.totals_row div b :first');
    $minus = $minus.length ? $minus.html() : '0';
    var diff = Number(real_num($minus))-Number(real_num($plus)) ;
    ECARTsumm += diff;
    
    var result = Math.round((diff) * 100) / 100;
    if (isNaN(result)) {
    	result = 0;
    }
    
    $('#totals_input_v1').html(
    	euro_num_format(result, 2, true)
    );

    if(result != 0) {
    	$('#totals_input_v1').parent().css('background-color', '#ff69b4');
    }
    
    totals_input_v1 = result;
        
    
    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_input table.table tr.totals_row div b :last');
    $plus = $plus.length ? $plus.html() : '0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_input table.table tr.totals_row div b :last');
    $minus = $minus.length ? $minus.html() : '0';
    var diff = Number(real_num($minus))-Number(real_num($plus)) ;
    ECARTsumm += diff;
    
    
    var result = Math.round((diff) * 100) / 100;
    if (isNaN(result)) {
    	result = 0;
    }
    $('#totals_input_v2').html(
    	euro_num_format(result, 2, true)
    );
    
    if(result != 0) {
    	$('#totals_input_v2').parent().css('background-color', '#ff69b4');
    }

    totals_input_v2 = result;
    
    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_output table.table tr.totals_row div b :first');
    $plus = $plus.length ? $plus.html() : '0';
   
    var $minus = $('.rapprochement_content_deb .rapprochement_content_output table.table tr.totals_row div b :first');
    $minus = $minus.length ? $minus.html() : '0';
    
    var diff = Number(real_num($minus))-Number(real_num($plus)) ;
    ECARTsumm += diff;
    
    var result = Math.round((diff) * 100) / 100;
    if (isNaN(result)) {
    	result = 0;
    }
    
    
    
    $('#totals_output_v1').html(
    	euro_num_format(result, 2, true)
    );

    if(result != 0) {
    	$('#totals_output_v1').parent().css('background-color', '#ff69b4');
    }
    
    totals_output_v1 = result;
    
    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_output table.table tr.totals_row div b :last');
    $plus = $plus.length ? $plus.html() : '0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_output table.table tr.totals_row div b :last');
    $minus = $minus.length ? $minus.html() : '0';
    var diff = Number(real_num($minus))-Number(real_num($plus)) ;
    ECARTsumm += diff;
    
    var result = Math.round((diff) * 100) / 100;
    if (isNaN(result)) {
    	result = 0;
    }
    $('#totals_output_v2').html(
    	euro_num_format(result, 2, true)
    );
    
    if(result != 0) {
    	$('#totals_output_v2').parent().css('background-color', '#ff69b4');
    }
    
    totals_output_v2 = result;

     
    if (ECARTsumm == 0) {
        $("#rapprochement_validation").show();
    }
    
    //#rapprochement_form
    if($('#rapprochement_form').size()) {
    	$('#rapprochement_intro_info_number, #rapprochement_intro_info_number2, #rapprochement_exped_info_number, #rapprochement_exped_info_number2').hide();
    	$('input[name="rapprochement\[intro_info_id\]"], input[name="rapprochement\[exped_info_id\]"]').removeAttr('checked');
    	$('input[name="rapprochement\[intro_info_id\]"]').change(function() {
    		if($(this).val()<4) {
    			
    			//$('#totals_input_calcu1, #totals_input_calcu2').html('<b>0,00</b>');
    			$('#totals_input_calcu1, #totals_input_calcu2').parent().css('background', '#fff'); 
    			$('#rapprochement_intro_info_number, #rapprochement_intro_info_number2').hide().removeAttr('required');
    			
    			$('#rapprochement_intro_info_number').val(totals_input_v1);
    			$('#rapprochement_intro_info_number2').val(totals_input_v2);
    			
    			//recalculer();
    			
    		} else {
    			
    			$('#rapprochement_intro_info_number').val('');
    			$('#rapprochement_intro_info_number2').val('');
    			
    			//recalculer();
    			$('#rapprochement_intro_info_number, #rapprochement_intro_info_number2').show().attr('required', true);
    		}
    		
    		
    		calc('#totals_input_v1', '#rapprochement_intro_info_number', '#totals_input_calcu1');
    		calc('#totals_input_v2', '#rapprochement_intro_info_number2', '#totals_input_calcu2');
    		cloturer();
    		
    	});
    	
    	$('input[name="rapprochement\[exped_info_id\]"]').change(function() {
    		if($(this).val()<3) {
    			//$('#totals_output_calcu1, #totals_output_calcu2').html('<b>0,00</b>');
    			$('#totals_output_calcu1, #totals_output_calcu2').parent().css('background', '#f9f9f9');
   			 	$('#rapprochement_exped_info_number, #rapprochement_exped_info_number2').hide().removeAttr('required');
   			 	
   			 	
   			 	$('#rapprochement_exped_info_number').val(totals_output_v1);
   			 	$('#rapprochement_exped_info_number2').val(totals_output_v2);
   			 	//recalculer();
   			 	
    		} else {
    			
    			$('#rapprochement_exped_info_number').val('');
   			 	$('#rapprochement_exped_info_number2').val('');
    			
    			//recalculer();
    			$('#rapprochement_exped_info_number, #rapprochement_exped_info_number2').show().attr('required', true);
    		}
    		
    		
    		calc('#totals_output_v1', '#rapprochement_exped_info_number', '#totals_output_calcu1');
    		calc('#totals_output_v2', '#rapprochement_exped_info_number2', '#totals_output_calcu2');
    		cloturer();
    	});

    	
    	var cloturer = (function() {
    		var i1 = real_num($('#totals_input_calcu1 b').html()), 
    			i2 = real_num($('#totals_input_calcu2 b').html()),
    			o1 = real_num($('#totals_output_calcu1 b').html()), 
    			o2 = real_num($('#totals_output_calcu2 b').html());
    		
    		if(i1 != 0 || i2 != 0 || o1 != 0 || o2 != 0) {
    			$('#btn_locking').attr('disabled', true);
    		} else {
    			$('#btn_locking').attr('disabled', false);
    		}
    	});
    	
    	
    	var calc = function(numSel, numInputSel, outputSel) {
    		var sum = Number(real_num($(numSel).html())) - Number(real_num( $(numInputSel).val()));
    		var val = euro_num_format(sum, 2, true);
    		$(outputSel).html('<b>'+ val +'</b>');
    		if(sum) {
    			$(outputSel).parent().css('background-color', '#ff69b4');
    		} else {
    			$(outputSel).parent().css('background-color', '#f9f9f9');
    		}
		};
    	
    	
    	var recalculer = function() {
    		calc('#totals_input_v1', '#rapprochement_intro_info_number', '#totals_input_calcu1');
    		calc('#totals_input_v2', '#rapprochement_intro_info_number2', '#totals_input_calcu2');
    		
    		calc('#totals_output_v1', '#rapprochement_exped_info_number', '#totals_output_calcu1');
    		calc('#totals_output_v2', '#rapprochement_exped_info_number2', '#totals_output_calcu2');
    		
    		cloturer();
    		
    	};
    	
    	
    	var toEuroFormat = function(object) {
    		$(object).val( euro_num_format($(object).val()) );
    	};
    	
    	
    	/*$('#btn_recalculer').click(function(){
    		recalculer();
    		return false;
    	}).trigger('click');*/
    	
    	recalculer();
    	$('#rapprochement_intro_info_number, #rapprochement_intro_info_number2').keyup(function(){
    		calc('#totals_input_v1', '#rapprochement_intro_info_number', '#totals_input_calcu1');
    		calc('#totals_input_v2', '#rapprochement_intro_info_number2', '#totals_input_calcu2');
    		cloturer();
    		
    		return false;
    	}).blur(function() {
    		toEuroFormat(this);
    	});
    	
    	
    	$('#rapprochement_exped_info_number, #rapprochement_exped_info_number2').keyup(function(){
    		calc('#totals_output_v1', '#rapprochement_exped_info_number', '#totals_output_calcu1');
    		calc('#totals_output_v2', '#rapprochement_exped_info_number2', '#totals_output_calcu2');
    		cloturer();
    		
    		return false;
    	}).blur(function() {
    		toEuroFormat(this);
    	});
    	
    	
    	
    
	    $('#rapprochement_exped_info_text').hide();
	    $('#rapprochement_intro_info_text').hide();
	    
	    
	    if(totals_input_v1 === 0 && totals_input_v2 === 0) {
	    	$('.rapprochement_intro').hide();
	    	$('#rapprochement_intro_info_text').removeAttr('required');
	    	$('#rapprochement_intro_info_number').removeAttr('required');
	    	$('#rapprochement_intro_info_number2').removeAttr('required');
	    	$('input[name="rapprochement\[intro_info_id\]"]').removeAttr('required');
	    } else {
	    	$('#rapprochement_intro_info_number').attr('required', 'required');
	    	$('input[name="rapprochement\[intro_info_id\]"]').change(function() {
	    		$('#rapprochement_intro_info_number').val('');
		    	if($('input[name="rapprochement\[intro_info_id\]"]:checked').val() == 5) { // Autre
		    		$('#rapprochement_intro_info_text').show().attr('required', 'required');
		    	} else {
		    		$('#rapprochement_intro_info_text').hide().val('').removeAttr('required');
		    	}
		    	
		    	if($('input[name="rapprochement\[intro_info_id\]"]:checked').val() == 5 || $('input[name="rapprochement\[intro_info_id\]"]:checked').val() == 4) {
		    		var i1 = real_num($('#totals_input_calcu1 b').html()),
		    			i2 = real_num($('#totals_input_calcu2 b').html());

		    		if(i1 == 0) {
		    			$('#rapprochement_intro_info_number').removeAttr('required').hide();
		    		} else {
		    			$('#rapprochement_intro_info_number').attr('required', 'required').show();
		    		}
		    		
		    		if(i2 == 0) {
		    			$('#rapprochement_intro_info_number2').removeAttr('required').hide();
		    		} else {
		    			$('#rapprochement_intro_info_number2').attr('required', 'required').show();
		    		}
		    		
		    	}
		    	
		    	
	    	});
	    }
	    if(totals_output_v1 === 0 && totals_output_v2 === 0) {
	    	$('.rapprochement_exped').hide();
	    	$('#rapprochement_exped_info_text').removeAttr('required');
	    	$('#rapprochement_exped_info_number').removeAttr('required');
	    	$('#rapprochement_exped_info_number2').removeAttr('required');
	    	$('input[name="rapprochement\[exped_info_id\]"]').removeAttr('required');
	    	
	    } else {
	    	$('#rapprochement_exped_info_number').attr('required', 'required');
	    	
	    	$('input[name="rapprochement\[exped_info_id\]"]').change(function() {
	    		$('#rapprochement_exped_info_number').val('');
	    		
		    	if($('input[name="rapprochement\[exped_info_id\]"]:checked').val() == 4) { // Autre
		    		$('#rapprochement_exped_info_text').show().attr('required', 'required');
		    	} else {
		    		$('#rapprochement_exped_info_text').hide().val('').removeAttr('required');
		    	}
		    	
		    	if($('input[name="rapprochement\[exped_info_id\]"]:checked').val() == 3 || $('input[name="rapprochement\[exped_info_id\]"]:checked').val() == 4) {
		    		var o1 = real_num($('#totals_output_calcu1 b').html()), 
	    				o2 = real_num($('#totals_output_calcu2 b').html());

		    		if(o1 == 0) {
		    			$('#rapprochement_exped_info_number').removeAttr('required').hide();
		    		} else {
		    			$('#rapprochement_exped_info_number').attr('required', 'required').show();
		    		}
		    		
		    		if(o2 == 0) {
		    			$('#rapprochement_exped_info_number2').removeAttr('required').hide();
		    		} else {
		    			$('#rapprochement_exped_info_number2').attr('required', 'required').show();
		    		}
		    	}
	    	});
	    	
	    }
	    if( (totals_input_v1 === 0 && totals_input_v2 === 0) && (totals_output_v1 === 0 && totals_output_v2 === 0)) {
	    	$('#rapprochement_form').hide();
	    }
    
    }
    
    
    
    
}

function init_tabs_filters_sticky_header() {
    var $thead = $('.sonata-ba-list .table thead:first');
    if ($thead.length == 0) {
        var $table = $('<table class="table table-bordered table-striped table-hover" />').prependTo('.sonata-ba-list:first');
        $thead = $('<thead />').appendTo($table);
    }

    var $tr = $('<tr />').prependTo($thead);
    var $th = $('<th />')
        .attr('colspan', $thead.children(':last').children().length)
        .append($('.clientoperations_tabs_types_block'))
        .appendTo($tr);

    $('.clientoperations_tabs_types_block #client-topinfo').after($('#block_actions'));

    var depth = 0;
    $thead.children().addClass('sticky_header').each(function () {
        $(this).attr('depth', depth++);
    });
    $('.sonata-ba-list .table').stickyHeader({
        'headerClassName':'sticky_header',
        'depth':[1, 1, 1]
    });
    $('.sticky_header_clone ._filterText').keyup(function () {
        var id = $(this).attr('id').replace("clone_", "_");
        $('#' + id).val($(this).val()).keyup();
    }).each(function () {
            $(this).attr('id', 'clone' + $(this).attr('id'));
        });
    $('.sticky_header_orig ._filterText').keyup(function () {
        $('#clone' + $(this).attr('id')).val($(this).val());
    });
    $('.sticky_header_clone a').click(function () {
        var href = $(this).attr('href');
        $('.sticky_header_orig a[href="' + href + '"]').click();

        return false;
    });

    $('.emptyColumnFilters').tooltip();
    $(".emptyColumnFilters").click(function () {
        $(this).parent().parent().find("._filterText").val("").keyup();

        return false;
    });

    var $all_elements = $('input[name="all_elements"]');
    var $list_batch_checkbox_cloned = $('.sticky_header_clone .sonata-ba-list-field-header-batch input[type="checkbox"]');
    $list_batch_checkbox_cloned.attr('id', 'list_batch_checkbox_cloned');
    var $list_batch_checkbox = $('#list_batch_checkbox');

    var all_elements_checkbox_toggle = function(checked){
        $list_batch_checkbox.attr('checked', checked).click().attr('checked', checked);
    };

    $all_elements.click(function(){
        all_elements_checkbox_toggle($(this).is(':checked'));
    });

    $list_batch_checkbox_cloned.click(function(){
        all_elements_checkbox_toggle($(this).is(':checked'));
    });

    $list_batch_checkbox.click(function(){
        $().add($all_elements).add($list_batch_checkbox_cloned).attr('checked', $(this).is(':checked'));
    });


}




