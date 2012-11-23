jQuery(document).ready(function ($) {
    $('#error_repost_show').die().live('click', function () {
        $('#importReportModal').modal('toggle');

        return false;
    });

    symfony_ajax.MontantTVAfrancaiseAndMontantTTC = symfony_ajax.MontantTVAfrancaiseAndMontantTTC || {};
    symfony_ajax.MontantTVAfrancaiseAndMontantTTC.calc = function () {

        $('#' + _uniqid + '_montant_TVA_francaise').val('2222');
        $('#' + _uniqid + '_montant_TTC').val('111');

    };

    symfony_ajax.behaviors.calcMontantTVAfrancaiseAndMontantTTC = {
        attach:function (context) {
            var _uniqid = symfony_ajax.get_uniqid();
            if (_uniqid) {
                $('#' + _uniqid + '_montant_HT_en_devise, #' + _uniqid + '_taux_de_TVA', context)
                    //state procurement
                    //.keypress(this.number_limit)
                    .keyup(this.calc)
                    .trigger('change');
            }
        },
        number_limit:function (e) {
            var chr = String.fromCharCode(e.charCode == undefined ? e.keyCode : e.charCode);
            return (chr < ' ' || (chr >= '0' && chr <= '9') || chr == ',' || chr == '.');
        },
        calc:function (e) {
            var _uniqid = symfony_ajax.get_uniqid();

            var $montant_HT_en_devise = $('#' + _uniqid + '_montant_HT_en_devise');
            var $taux_de_TVA = $('#' + _uniqid + '_taux_de_TVA');

            //TODO limit
            substr_replace($montant_HT_en_devise, 2);
            substr_replace($taux_de_TVA, 3);

            var montant_HT_en_devise = $montant_HT_en_devise.val().replace(',', '.').replace(/\s+/, '');
            var taux_de_TVA = $taux_de_TVA.val().replace(',', '.').replace(/\s+/, '');

            var montant_TVA_francaise = parseFloat(parseFloat(montant_HT_en_devise) * (parseFloat(taux_de_TVA) / 100));

            //If method validate from file Validate/ErrorElements.php function round $precision = 2, $mode = PHP_ROUND_HALF_DOWN
            montant_TVA_francaise = montant_TVA_francaise ? montant_TVA_francaise.toFixed(2) : '';

            var montant_TTC = parseFloat(parseFloat(montant_HT_en_devise) + parseFloat(montant_TVA_francaise));

            montant_TVA_francaise = montant_TVA_francaise ? montant_TVA_francaise.replace('.', ',') : '';
            montant_TTC = montant_TTC ? montant_TTC.toFixed(2).replace('.', ',') : '';

            $('#' + _uniqid + '_montant_TVA_francaise').val(montant_TVA_francaise);
            $('#' + _uniqid + '_montant_TTC').val(montant_TTC);
        }
    };

});


function substr_replace($object, limit) {

    var result = $object.val().split(',');
    var result2 = $object.val().split('.');
    if ((result[1] && result[1].length > limit) || result2[1] && result2[1].length > limit) {
        $object.val($object.val().substr(0, $object.val().length - 1));
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
                    .append('<th class="sonata-ba-list-field-header-actions">Action</th>')
                    .appendTo($table.children('thead'));

                var $tbody = $('<tbody />');
                for (i in json.imports) {
                    var import_id = json.imports[i].id;
                    var deleteUrl = o.import.delete.replace("__id__", import_id);
                    $('<tr />')
                        .append('<td class="sonata-ba-list-field-header-integer">' + import_id + '</td>')
                        .append('<td class="sonata-ba-list-field-header-date"><b>' + json.imports[i].date.date + '</b></td>')
                        .append('<td class="sonata-ba-list-field-header-orm_many_to_one">' + json.imports[i].username + '</td>')
                        .append('<td class="sonata-ba-list-field-header-actions"><a style="display: inline;" class="import_del" href="' + deleteUrl + '">Supprimer</a></td>')
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
}