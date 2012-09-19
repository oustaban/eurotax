jQuery(document).ready(function ($) {

    //add class body
    if (active_tab) {
        $('body').addClass('js-' + active_tab);
    }

    /**
     * document
     * */
    $('#' + uniqid + '_type_document').change(function () {
        $('#sonata-ba-field-container-' + uniqid + '_date_notaire, #sonata-ba-field-container-' + uniqid + '_date_apostille')[['show', 'hide'][$(this).val() == 2 ? 0 : 1]]();
    }).change();

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
            update:function (event, ui) {
                var sort_data = [];
                $('.sonata-ba-list .table tbody .sonata-ba-list-field input[type="checkbox"]').each(function () {
                    sort_data.push($(this).val());
                });

                $.ajax({
                    url:Sonata.url.sortable,
                    type:'POST',
                    data:{'ids':sort_data},
                    dataType:'json',
                    async:false,
                    success:function (i) {
                        console.log(i);
                    }
                });
            }
        }).disableSelection();
    }
});