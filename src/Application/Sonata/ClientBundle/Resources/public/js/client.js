jQuery(document).ready(function ($) {


    //add class body
    if (active_tab) {
        $('body').addClass('js-' + active_tab);
    }

    /**
     * document
     * */
    if ($('.js-document').size()) {

        symfony_ajax.behaviors.type_document = {
            attach:function (context) {
                var _uniqid = symfony_ajax.get_uniqid();

                if (_uniqid) {
                    $('#' + _uniqid + '_type_document', context).change(function () {

                        $('#sonata-ba-field-container-' + _uniqid + '_date_notaire, #sonata-ba-field-container-' + _uniqid + '_date_apostille')[['show', 'hide'][$(this).val() == 2 ? 0 : 1]]();

                    }).trigger('change');
                }
            }
        };
    }
    /**
     * garantie
     * */

    if ($('.js-garantie').size()) {


        $('#' + uniqid + '_nom_de_lemeteur').keyup(function () {
            $('#sonata-ba-field-container-' + uniqid + '_nom_de_la_banques_id')[['hide', 'show'][$(this).val() ? 0 : 1]]();
        }).keyup();

        $('.form-horizontal div.control-group').each(function (i) {
            $(this).addClass('field-' + i);
        });
        $('.field-4 label').remove();
    }


    if ($('.js-tarif').size()) {

        $('.form-horizontal div.control-group').each(function (i) {
            $(this).addClass('field-' + i);
        });

        $('.js-tarif .form-client-invoicing .field-4 label').remove();
    }
    ;

    /*
     * coordonnees
     * */
    if ($('.js-coordonnees').size()) {

        $(' .sonata-ba-list .table tbody').sortable({
            helper:function (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            },
            update: function (event, ui) {
                var sort_data = [];
                $('.sonata-ba-list .ui-sortable tr').each(function (i) {
                    var objectid = $(this).find('td[objectid]:last').attr('objectid');
                    if (objectid) {
                        sort_data.push(objectid);
                    }
                });

                ajax_dialog_load = false;
                alert(1);

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
    ;

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
});