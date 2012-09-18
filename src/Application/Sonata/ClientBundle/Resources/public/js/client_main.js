jQuery(document).ready(function ($) {

    $('.form-horizontal table td div.control-group').each(function (i) {
        $(this).addClass('field-' + i);
    });

    $('.field-22').before('<input type="button" value="Copier les information postales" name="clone_address" id="clone_address" class="btn" />');

    $('#clone_address').live('click', function () {

        $.each(['adresse_1', 'adresse_2', 'code_postal', 'ville', 'pays_id'], function(i, field){
            $('#'+uniqid+'_location_facturation_'+field+'_facturation').val($('#'+uniqid+'_location_postal_'+field+'_postal').val());
        });

        $('#'+uniqid+'_raison_sociale_2').val($('#'+uniqid+'_raison_sociale').val());

        return false;
    });

});