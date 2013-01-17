jQuery(document).ready(function ($) {
    $('#error_repost_show').die().live('click', function () {
        $('#importReportModal').modal('toggle');

        return false;
    });

    if (typeof symfony_ajax != 'undefined'){
        symfony_ajax.behaviors.PaiementDateCloneMoisdeTVA = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();

                //-- V01-TVA || A02-TVA
                if ($('#' + _uniqid + '_paiement_date', context).size() && $('#' + _uniqid + '_mois_mois', context).size()) {
                    $('#' + _uniqid + '_paiement_date', context).change(function () {

                        if ($(this).val() && !$('#' + _uniqid + '_mois_mois :selected', context).val()) {
                            var date_arr = $(this).val().split('/');
                            var year_month = date_arr[2] + '-' + Number(date_arr[1]);

                            $('#' + _uniqid + '_mois_mois option', context).each(function () {
                                $(this).removeAttr('selected');
                            });

                            $('#' + _uniqid + '_mois_mois option', context).each(function () {
                                if ($(this).val() == year_month) {
                                    $(this).attr('selected', 'selected').trigger('change');
                                }
                            })
                        }
                    });
                }
            }
        };

        symfony_ajax.behaviors.rDevises = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();
                if (_uniqid) {
                    $('#' + _uniqid + '_paiement_devise, #' + _uniqid + '_paiement_date', context).change(function () {
                        var _uniqid = symfony_ajax.get_uniqid();

                        var devise = $('#' + _uniqid + '_paiement_devise :selected').val();
                        var paiement_date = $('#' + _uniqid + '_paiement_date').val();
                        var paiement_montant = $('#' + _uniqid + '_paiement_montant').val();

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
                                    $('#' + _uniqid + '_taux_de_change').val(i.value ? i.value : '');
                                }
                            })
                        }
                    });
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
                    var $taux_de_change = $('#' + _uniqid + '_taux_de_change', context);
                    var $HT = $('#' + _uniqid + '_HT', context);

                    if ($montant_HT_en_devise.length && $taux_de_TVA.length){
                        $montant_HT_en_devise.change(this.calc).keyup(this.calc);
                        $taux_de_TVA.change(this.calc).trigger('change');
                    }
                    if($montant_TTC.length){
                        $montant_TTC.change(this.calc_paiement_montant).keyup(this.calc_paiement_montant).trigger('change')
                    }
                    if($montant_TTC.length && $taux_de_change.length && $taux_de_TVA.length){
                        $montant_TTC.change(this.calc_HT).keyup(this.calc_HT);
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

                var taux_de_TVA = $taux_de_TVA.val().replace(',', '.').replace(/\s+/, '');
                var HT = $HT.val().replace(',', '.').replace(/\s+/, '');

                var taux_de_TVA_X_m = Math.round(parseFloat(taux_de_TVA)*m);
                var HT_X_m = Math.round(parseFloat(HT)*m);

                if (taux_de_TVA_X_m && HT_X_m){
                    var TVA = (taux_de_TVA_X_m * HT_X_m) / m / m;
                    TVA = TVA ? TVA.toString().replace('.', ',') : '';

                    $('#' + _uniqid + '_TVA').val(TVA);
                }
            },
            calc_HT:function(e){
                var m = 10000000;

                var _uniqid = symfony_ajax.get_uniqid();

                var $taux_de_TVA = $('#' + _uniqid + '_taux_de_TVA');
                var $paiement_montant = $('#' + _uniqid + '_paiement_montant');
                var $taux_de_change = $('#' + _uniqid + '_taux_de_change');

                var taux_de_TVA = $taux_de_TVA.val().replace(',', '.').replace(/\s+/, '');
                var paiement_montant = $paiement_montant.val().replace(',', '.').replace(/\s+/, '');
                var taux_de_change = $taux_de_change.val().replace(',', '.').replace(/\s+/, '');

                var taux_de_TVA_X_m = Math.round(parseFloat(taux_de_TVA)*m);
                var paiement_montant_X_m = Math.round(parseFloat(paiement_montant)*m);
                var taux_de_change_X_m = Math.round(parseFloat(taux_de_change)*m);

                if (taux_de_TVA_X_m && paiement_montant_X_m && taux_de_change_X_m){
                    var HT = (paiement_montant_X_m / ((1 + taux_de_TVA_X_m) * taux_de_change_X_m)) / m;
                    HT = HT ? HT.toString().replace('.', ',') : '';

                    $('#' + _uniqid + '_HT').val(HT);
                }
            },
            calc_paiement_montant:function(e){
                var _uniqid = symfony_ajax.get_uniqid();

                var $montant_TTC = $('#' + _uniqid + '_montant_TTC');
                $('#' + _uniqid + '_paiement_montant').val($montant_TTC.val());
            },
            calc:function (e) {
                var m = 100000;

                var _uniqid = symfony_ajax.get_uniqid();

                var $montant_HT_en_devise = $('#' + _uniqid + '_montant_HT_en_devise');
                var $taux_de_TVA = $('#' + _uniqid + '_taux_de_TVA');

                var montant_HT_en_devise = $montant_HT_en_devise.val().replace(',', '.').replace(/\s+/, '');
                var taux_de_TVA = $taux_de_TVA.val().replace(',', '.').replace(/\s+/, '');

                var montant_HT_en_devise_X_m = Math.round(parseFloat(montant_HT_en_devise)*m);
                var taux_de_TVA_X_m = Math.round(parseFloat(taux_de_TVA)*m);

                var montant_TVA_francaise = (montant_HT_en_devise_X_m * taux_de_TVA_X_m) / m / m;
                var montant_TTC = montant_HT_en_devise_X_m / m + montant_TVA_francaise;

                montant_TVA_francaise = montant_TVA_francaise ? montant_TVA_francaise.toString().replace('.', ',') : '';
                montant_TTC = montant_TTC ? montant_TTC.toString().replace('.', ',') : '';

                $('#' + _uniqid + '_montant_TVA_francaise').val(montant_TVA_francaise);
                $('#' + _uniqid + '_montant_TTC').val(montant_TTC).change();
            }
        };
    }
});


function substr_replace($object, limit) {
    if ($object && $object.length){
        var result = $object.val().split(',');
        var result2 = $object.val().split('.');
        if ((result[1] && result[1].length > limit) || result2[1] && result2[1].length > limit) {
            $object.val($object.val().substr(0, $object.val().length - 1));
        }
    }
}

function init_clientoperations_buttons(o) {

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
            if (o.blocking.isBlocked && o.blocking.hasBlocking) {
                $('#btn_client_alert').click();
            }
            else {
                if (confirm(o.blocking.text)) {
                    location.href = o.blocking.link;
                }
            }
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
    var ECARTsumm = 0;

    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_input table.table tr.totals_row div b :first');
    $plus = $plus.length ? $plus.html() : '0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_input table.table tr.totals_row div b :first');
    $minus = $minus.length ? $minus.html() : '0';
    var diff = Number($plus.replace(',', '.')) - Number($minus.replace(',', '.'));
    ECARTsumm += diff;
    $('#totals_input_v1').html(
        (Math.round((diff) * 100) / 100).toFixed(2).toString().replace('.', ',')
    );

    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_input table.table tr.totals_row div b :last');
    $plus = $plus.length ? $plus.html() : '0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_input table.table tr.totals_row div b :last');
    $minus = $minus.length ? $minus.html() : '0';
    var diff = Number($plus.replace(',', '.')) - Number($minus.replace(',', '.'));
    ECARTsumm += diff;
    $('#totals_input_v2').html(
        (Math.round((diff) * 100) / 100).toFixed(2).toString().replace('.', ',')
    );

    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_output table.table tr.totals_row div b :first');
    $plus = $plus.length ? $plus.html() : '0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_output table.table tr.totals_row div b :first');
    $minus = $minus.length ? $minus.html() : '0';
    var diff = Number($plus.replace(',', '.')) - Number($minus.replace(',', '.'));
    ECARTsumm += diff;
    $('#totals_output_v1').html(
        (Math.round((diff) * 100) / 100).toFixed(2).toString().replace('.', ',')
    );

    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_output table.table tr.totals_row div b :last');
    $plus = $plus.length ? $plus.html() : '0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_output table.table tr.totals_row div b :last');
    $minus = $minus.length ? $minus.html() : '0';
    var diff = Number($plus.replace(',', '.')) - Number($minus.replace(',', '.'));
    ECARTsumm += diff;
    $('#totals_output_v2').html(
        (Math.round((diff) * 100) / 100).toFixed(2).toString().replace('.', ',')
    );

    if (ECARTsumm == 0) {
        $("#rapprochement_validation").show();
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