jQuery(document).ready(function ($) {

    $('.form-horizontal table td div.control-group').each(function (i) {
        $(this).addClass('field-' + i);
    });

    $('.field-22').before('<input type="button" value="Copier les information postales" name="clone_address" id="clone_address" class="btn" />');

    copy_address();

    $('#' + uniqid + '_autre_destinataire_de_facturation').change(function () {

        if ($(this).attr('checked') != 'checked') {

            $('#clone_address').die();
            $.each(fields_address, function (i, field) {
                $('#' + uniqid + '_location_facturation_' + field + '_facturation').attr('readonly', 'readonly').val('').addClass('disabeld');
            });
            $('#' + uniqid + '_raison_sociale_2').attr('readonly', 'readonly').val('').addClass('disabeld');
        }
        else {
            copy_address();
            $.each(fields_address, function (i, field) {
                $('#' + uniqid + '_location_facturation_' + field + '_facturation').removeAttr('readonly').removeClass('disabeld');
            });
            $('#' + uniqid + '_raison_sociale_2').removeAttr('readonly').removeClass('disabeld');
        }


    }).change();
});

var fields_address = ['adresse_1', 'adresse_2', 'code_postal', 'ville', 'pays_id'];

function copy_address() {
    $('#clone_address').live('click', function () {

        $.each(fields_address, function (i, field) {
            $('#' + uniqid + '_location_facturation_' + field + '_facturation').val($('#' + uniqid + '_location_postal_' + field + '_postal').val());
        });

        $('#' + uniqid + '_raison_sociale_2').val($('#' + uniqid + '_raison_sociale').val());

        return false;
    });
}