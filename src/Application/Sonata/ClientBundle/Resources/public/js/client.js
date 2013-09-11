jQuery(document).ready(function ($) {


    //add class body
    if (active_tab) {
        $('body').addClass('js-' + active_tab);
    }

    if ($('.js-client').size()) {
    	//Place voir les operations button next to Ajouter / Modifier un client title
    	$('.voir-les-operations').css({marginLeft: '280px', position: 'absolute'});
    } else if($('.js-impression').size()) {	
    	$('.voir-les-operations').css({marginLeft: '110px', position: 'absolute'});
    } else {
    	
    	$('.voir-les-operations').css({marginLeft: '80px', position: 'absolute'});
    	
    }
    
    
    /**
     * import initial
     */
    $('input[id=inputFile]').change(function () {
        $('#inputFileCover').val($(this).val());
    });
    
    
    
    $('#error_repost_show').die().live('click', function () {
        $('#importReportModal').modal('toggle');
        return false;
    });
    
    
    
    
    /**
     * document
     * */
    if ($('.js-document').size()) {

        symfony_ajax.behaviors.type_document = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();

                if (_uniqid) {
                    $('#' + _uniqid + '_type_document', context).change(function () {

                    	$('#' + _uniqid + '_date_notaire, #' + _uniqid + '_date_apostille').attr('disabled', true);
                    	//$('#' + _uniqid + '_statut_document_notaire option:first, #' + _uniqid + '_statut_document_apostille option:first').attr('selected', true);
                        $('#sonata-ba-field-container-' + _uniqid + '_date_notaire, #sonata-ba-field-container-' + _uniqid + '_date_apostille, #sonata-ba-field-container-' + _uniqid + '_statut_document_notaire, #sonata-ba-field-container-' + _uniqid + '_statut_document_apostille')[['show', 'hide'][($(this).val() == 2 || $(this).val() == 6) ? 0 : 1]]();
                        $('#sonata-ba-field-container-' + _uniqid + '_preavis')[['show', 'hide'][($(this).val() == 1 || $(this).val() == 3) ? 0 : 1]]();

                        if ($(this).val() == 6){
                        	//$('#' + _uniqid + '_date_notaire, #' + _uniqid + '_date_apostille').removeAttr('disabled');
                            $('#' + _uniqid + '_statut_document_notaire, #' + _uniqid + '_statut_document_apostille').each(function(){
                                if ($(this).val() == ''){
                                    $(this).val(1);
                                }
                            });
                        }
                        else {
                            //$('#' + _uniqid + '_statut_document_notaire, #' + _uniqid + '_statut_document_apostille').val('');
                        }
                        
                        
                        
                        if ($(this).val() > 2) {
                        	$('#' + _uniqid + '_date_notaire').removeAttr('required');
                        	$('#' + _uniqid + '_date_apostille').removeAttr('required');
                        }
                        
                        
                        
                        if($(this).val() == 2 || $(this).val() == 6) {
                			console.log('test');
	                		 $('#' + _uniqid + '_statut_document_notaire').change(function(){
	 		                	if($(this).val() != '') {
	 		                		$('#' + _uniqid + '_date_notaire').attr('disabled', true);
	 		                	} else {
	
	 		                		$('#' + _uniqid + '_date_notaire').removeAttr('disabled');
	
	 		                	}
	 		                }).trigger('change');
	 		                
	 		
	 		                $('#' + _uniqid + '_statut_document_apostille').change(function(){
	 		                	if($(this).val() != '') {
	 		                		$('#' + _uniqid + '_date_apostille').attr('disabled', true);
	
	 		                	} else {
	 		                		$('#' + _uniqid + '_date_apostille').removeAttr('disabled');
	
	 		                	}
	 		                }).trigger('change');

                		
                		
                        }
                        
                        
                        

                    }).trigger('change');

                    $('#sonata-ba-field-container-' + _uniqid + '_date_notaire').addClass('date_notaire');
                    $('#sonata-ba-field-container-' + _uniqid + '_statut_document_notaire').after('<div style="clear:both"></div>').addClass('statut_document_notaire');
                    $('#sonata-ba-field-container-' + _uniqid + '_date_apostille').addClass('date_apostille');
                    $('#sonata-ba-field-container-' + _uniqid + '_statut_document_apostille').addClass('statut_document_apostille');
                }
                
                
                if(Sonata.client.nature_du_client == '6e' && typeof Sonata.client.country_eu[Sonata.client.pays_code] === 'undefined') {
                	/*
                	 * If Status = A obtenir or  Non demandé => We must not see the date "Notaire" and  "Apostille (champ de "Date") 
                	 * 
                	 * On "If Statut = empty => Date  "Notaire" et "Apostille (champ de "Date") are mandatory
					The default value should be : "A obtenir"

                	 */
                	console.log('nature_du_client: ' + Sonata.client.nature_du_client);
                	
                	 $('#' + _uniqid + '_type_document', context).change(function () {
                		 $('#' + _uniqid + '_date_notaire, #' + _uniqid + '_date_apostille').removeAttr('disabled');
                		 
                		 if($(this).val() == 3) { //Accord
                			 if($('.date_document_help-block').size() == 0) {
                			 	$('#' + _uniqid + '_date_document').after('<span class="help-block sonata-ba-field-help date_document_help-block">date d\'effet en clause 2</span>');
                			 }
                			 $('.date_document_help-block').show();
                		 } else {
                			 $('.date_document_help-block').hide();
                		 }
                	 });
                	
                	
                	$('#' + _uniqid + '_statut_document_notaire').change(function(){
	                	if($(this).val() != '') {
	                		rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_notaire label', context));
	                		$('#' + _uniqid + '_date_notaire').removeAttr('required');
	                		$('#sonata-ba-field-container-' + _uniqid + '_date_notaire', context).css('visibility', 'hidden');
	                	} else {
	                		add_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_notaire label', context));
	                		$('#' + _uniqid + '_date_notaire').attr('required', true);
	                		$('#sonata-ba-field-container-' + _uniqid + '_date_notaire', context).css('visibility', 'visible');
	                	}
	                }).trigger('change');
                	
                	
                	$('#' + _uniqid + '_statut_document_apostille').change(function(){
	                	if($(this).val() != '') {
	                		rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_apostille label', context));
	                		$('#' + _uniqid + '_date_apostille').removeAttr('required');
	                		$('#sonata-ba-field-container-' + _uniqid + '_date_apostille', context).css('visibility', 'hidden');
	                	} else {
	                		add_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_apostille label', context));
	                		$('#' + _uniqid + '_date_apostille').attr('required', true);
	                		$('#sonata-ba-field-container-' + _uniqid + '_date_apostille', context).css('visibility', 'visible');
	                	}
	                }).trigger('change');
                	
                	
                	
                } else {
                	
                	    $('#' + _uniqid + '_statut_document_notaire').change(function(){
		                	if($(this).val() != '') {
		                		$('#' + _uniqid + '_date_notaire').removeAttr('disabled');
		                	} else {
		                		$('#' + _uniqid + '_date_notaire').attr('disabled', true);
		                	}
		                }).trigger('change');
		                
		
		                $('#' + _uniqid + '_statut_document_apostille').change(function(){
		                	if($(this).val() != '') {
		                		$('#' + _uniqid + '_date_apostille').removeAttr('disabled');
		                	} else {
		                		$('#' + _uniqid + '_date_apostille').attr('disabled', true);
		                	}
		                }).trigger('change');
                	
                }
               
                
            }
        };

        //$('.sonata-ba-list-field-text').removeAttr('objectid');
    }

    /**
     * garantie
     * */

    if ($('.js-garantie').size()) {

        /**
         * @type {Object}
         */
        symfony_ajax.behaviors.garantie = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();

                $('.form-horizontal div.control-group [name]', context).each(function (i) {
                    var name = $(this).attr('name').split('[').pop();
                    $(this).addClass(name.substr(0, name.length - 1));
                });

                $('#' + _uniqid + '_type_garantie', context).change(function () {
                    symfony_ajax.garantie(_uniqid, context);
                }).trigger('change');


                $('#' + _uniqid + '_nom_de_la_banques_id', context).change(function () {
                    symfony_ajax.garantie(_uniqid, context);
                }).trigger('change');


               /* $('#' + _uniqid + '_nom_de_lemeteur', context).keyup(function () {
                    $('#sonata-ba-field-container-' + _uniqid + '_nom_de_la_banques_id')[['hide', 'show'][$(this).val() ? 0 : 1]]();
                }).keyup();
                */
                
                //console.log(status);
                var status = $('#' + _uniqid + '_nom_de_la_banques_id :selected', context).val();
                
                if(status == 0 && symfony_ajax.is_new() === false) {
                	$('#' + _uniqid + '_nom_de_la_banques_id').attr('disabled', true);
                }
                
                
            }
        };

        /**
         * @param _uniqid
         * @param context
         * @param id
         */
        symfony_ajax.garantie_valute = function (_uniqid, context, id) {

            $('#' + _uniqid + '_montant_devise option', context).each(function () {
                if (id == 0) {
                    $(this).show();
                }
                else {
                    if ($(this).val() == id) {
                        $(this).show();
                    }
                    else {
                        $(this).hide();
                    }
                }
            });
        }

        /**
         * @param _uniqid
         * @param context
         */
        symfony_ajax.garantie = function (_uniqid, context) {

            var type = $('#' + _uniqid + '_type_garantie :selected', context).val();
            var status = $('#' + _uniqid + '_nom_de_la_banques_id :selected', context).val();

            var $show_block = $('#sonata-ba-field-container-' + _uniqid + '_nom_de_lemeteur, ' +
                '#sonata-ba-field-container-' + _uniqid + '_num_de_ganrantie,' +
                '#sonata-ba-field-container-' + _uniqid + '_date_demission,' +
                '#sonata-ba-field-container-' + _uniqid + '_date_decheance,' +
                '#sonata-ba-field-container-' + _uniqid + '_expire', context);

            var $require_block = $('#sonata-ba-field-container-' + _uniqid + '_nom_de_lemeteur input, ' +
                '#sonata-ba-field-container-' + _uniqid + '_num_de_ganrantie input,' +
                '#sonata-ba-field-container-' + _uniqid + '_date_demission input,' +
                '#sonata-ba-field-container-' + _uniqid + '_date_decheance input', context);

           
            
            
            if ((type == 1 || type == 3)) {

                var valute_all = 0;
                symfony_ajax.garantie_valute(_uniqid, context, valute_all);

                $('#sonata-ba-field-container-' + _uniqid + '_nom_de_la_banques_id').show();

                if (status == 1) {
                    $require_block.removeAttr('required');

                    rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_nom_de_lemeteur label', context));
                    rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_num_de_ganrantie label', context));
                    rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_demission label', context));
                    rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_decheance label', context));

                    $show_block.hide();
                }
                else {
                    $require_block.attr('required', 'required');

                    add_label_required($('#sonata-ba-field-container-' + _uniqid + '_nom_de_lemeteur label', context));
                    add_label_required($('#sonata-ba-field-container-' + _uniqid + '_num_de_ganrantie label', context));
                    add_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_demission label', context));
                    add_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_decheance label', context));

                    $show_block.show();
                }
            }
            else if (type == 2) {
                $require_block.removeAttr('required');

                $(  '#sonata-ba-field-container-' + _uniqid + '_nom_de_lemeteur, ' +
                    '#sonata-ba-field-container-' + _uniqid + '_num_de_ganrantie, ' +
                    '#sonata-ba-field-container-' + _uniqid + '_date_decheance, ' +
                    '#sonata-ba-field-container-' + _uniqid + '_expire', context).hide();

                rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_nom_de_lemeteur label', context));
                rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_num_de_ganrantie label', context));
                rm_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_decheance label', context));

                add_label_required($('#sonata-ba-field-container-' + _uniqid + '_date_demission label', context));
                $('#sonata-ba-field-container-' + _uniqid + '_date_demission', context).show();
                $('#sonata-ba-field-container-' + _uniqid + '_date_demission input', context).attr('required', 'required');

                //Euro
                var valute_euro = 1;
                symfony_ajax.garantie_valute(_uniqid, context, valute_euro);
                $('#' + _uniqid + '_montant_devise', context).val(valute_euro);
                
                if(status == 1) {
                	 $('#sonata-ba-field-container-' + _uniqid + '_date_demission', context).hide();
                	 $('#' + _uniqid + '_date_demission', context).removeAttr('required');
                } else {
                	 $('#sonata-ba-field-container-' + _uniqid + '_date_demission', context).show();
                	 $('#' + _uniqid + '_date_demission', context).attr('required', 'required');
                }
                
                
            }
        }
    }

    if ($('.js-tarif').size()) {

        $('.form-horizontal div.control-group').each(function (i) {
            var name = $(this).children().children().attr('name').split('[').pop();
            $(this).addClass('groups-' + name.substr(0, name.length - 1));
        });

        $('.js-tarif .form-client-invoicing .field-4 label').remove();
    }
    ;

    /*
     * coordonnees
     * */
    if ($('.js-coordonnees').size()) {

        var $tbody = $(' .sonata-ba-list .table tbody');
        var handle = '<td><div href="#" title="' + Sonata.drag_text + '" class="tabledrag-handle handle">&nbsp;</div></td>';

        $('.sonata-ba-list .table thead tr').append('<th></th>');

        $('.sonata-ba-list .table tbody tr').append(handle);

        $('.sonata-ba-list .table thead a').click(function () {
            return false;
        })

        $tbody.sortable({
            handle:'.handle',
            helper:function (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            },
            update:function (event, ui) {
                var sort_data = [];
                $('.sonata-ba-list .ui-sortable tr').each(function (i) {
                    var objectid = $(this).find('td[objectid]:last').attr('objectid');
                    if (objectid) {
                        sort_data.push(objectid);
                    }
                });

                $.ajax({
                    url:Sonata.url.sortable,
                    type:'POST',
                    data:{'ids':sort_data},
                    dataType:'json',
                    async:false,
                    success:function (i) {
                        Admin.log(i);
                    }
                });
            }
        }).disableSelection();
    }


    /**
     * tarif
     */
    if ($('.js-tarif').size()) {
        symfony_ajax.behaviors.tarif = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();

                if (_uniqid) {
                    var tarif_value_percentage = $('#' + _uniqid + '_value_percentage');
                    var tarif_value = $('#' + _uniqid + '_value');

                    $('#' + _uniqid + '_mode_de_facturation').change(function () {

                        if (Sonata.mode_de_facturation[$(this).val()]) {

                            switch (Sonata.mode_de_facturation[$(this).val()].unit) {

                                case '%':
                                    tarif_value_percentage.removeAttr('disabled');
                                    tarif_value.attr('disabled', 'disabled').val('');
                                    Admin.log('%');
                                    break;

                                case 'V':
                                    tarif_value.removeAttr('disabled');
                                    tarif_value_percentage.attr('disabled', 'disabled').val('');
                                    Admin.log('V');
                                    break;
                            }
                        }
                    }).trigger('change');
                }
            }
        };
    }

    /**
     * compte & compte_de_depot
     */
    if ($('.js-compte, .js-compte_de_depot').size()) {
    	
    	//pink bg for rows w/ statut = Prévisionnel
    	$('.sonata-ba-list').find('.statut-p').parent().parent().find('td').css('background', '#98B756');
    	
    	//$('.sonata-ba-list').find('.solde').hide();
    	if(typeof Sonata !== 'undefined') {
	    	if(typeof Sonata.isSuperviseur === 'undefined' || (typeof Sonata.isSuperviseur !== 'undefined' && Sonata.isSuperviseur === false)) {
		        var $inactiveTr = $('div.inactive_compte').parent().parent();
		        $inactiveTr.find('.sonata-ba-list-field-action').html('');
		        $inactiveTr.find('td').addClass('no-edit');
	    	}
    	}
        if (typeof symfony_ajax != 'undefined'){
	        symfony_ajax.behaviors.compte = {
	                attach:function (context) {
	                    if(symfony_ajax.is_new()) {
	                		$('#' + symfony_ajax.get_uniqid() + '_statut option:eq(1)').attr('selected', 'selected');
	                	}
	                }
	        };
        }
        
        
    }
	
    
    if ($('.js-compte, .js-coordonnees').size()) {
    	
    	if($('#virement-btn').size() ) {
    		$('.voir-les-operations').css({marginLeft: '230px', position: 'absolute'});
    	}
    		
    	if($('#virement-btn').size()  && !$('#virement-btn').hasClass('disabled')) {
    		
    		
    		if($('#virement_coordonnees option').length <= 2) {
    			$('#virement_coordonnees option:eq(1)').attr('selected', true);
    		}
    		
	    	$('#virement-btn').toggle(function(){$('#virement_form').show();}, function(){$('#virement_form').hide();});
	        $('#virement_form').submit(function() {
	        	var amount = real_num($('#virement_amount').val());
	        	if(amount < 0 || isNaN(amount)) {
	        		$('#virement_form .alert-error').html('<p>Le montant doit être positif.</p>');
	        		return false;
	        	}
	        	
	        	var popup = window.open("about:blank", "myPopup");
	        	$.post($(this).attr('action'), $(this).serialize(), function(json) {
	        		if(json.result == 'ok') {
	        			//window.open(json.url);
	        			popup.location = json.url;
	        			location.reload();
	        			return false;
	        		}
	        		
	        	}, 'json');
	        	
		        return false;
	        });
    	}
	      
    }
    
    
});