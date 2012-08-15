jQuery(document).ready(function ($) {
    /**
     * document
     * */
    $('#' + uniqid + '_type_document').change(function () {
        $('#sonata-ba-field-container-' + uniqid + '_date_notaire, #sonata-ba-field-container-' + uniqid + '_date_apostille')[['show', 'hide'][$(this).val() == 2 ? 0 : 1]]();
    }).change();

    /**
     * garantie
     */
    $('#' + uniqid + '_nom_de_la_banque').keyup(function () {
        $('#sonata-ba-field-container-' + uniqid + '_nom_de_la_banques_id')[['hide', 'show'][$(this).val() ? 0 : 1]]();
    }).keyup();

});