jQuery(document).ready(function ($) {

    $('#sonata-ba-field-container-' + uniqid + '_N_TVA_CEE, #sonata-ba-field-container-' + uniqid + '_N_TVA_CEE_facture').after('<hr />');

    $('.form-horizontal table td div.control-group').each(function (i) {
        $(this).addClass('field-' + i);
        $('.hidden').parent().parent().remove();
    });

    $('#' + uniqid + '_nom')
        .keypress(required_spaces)
        .blur(replace_spaces)
        .trigger('blur');

    $('#sonata-ba-field-container-' + uniqid + '_contact').before('<input type="button" value="Copier les information postales" name="clone_address" id="clone_address" class="btn" />');

    copy_address();

    $('#' + uniqid + '_autre_destinataire_de_facturation').change(function () {

        if ($(this).attr('checked') != 'checked') {

            $('#clone_address').die();
            $.each(fields_address, function (i, field) {
                $('#' + uniqid + '_location_facturation_' + field + '_facturation').attr('disabled', 'disabled').val('');
                rm_label_required($('#sonata-ba-field-container-' + uniqid + '_location_facturation_' + field + '_facturation label'));
            });
            $('#' + uniqid + '_raison_sociale_2').attr('disabled', 'disabled').val('');
            $('#' + uniqid + '_N_TVA_CEE_facture').attr('disabled', 'disabled').val('');


        }
        else {
            copy_address();
            $.each(fields_address, function (i, field) {
                $('#' + uniqid + '_location_facturation_' + field + '_facturation').removeAttr('disabled');
                add_label_required($('#sonata-ba-field-container-' + uniqid + '_location_facturation_' + field + '_facturation label'));
            });
            $('#' + uniqid + '_raison_sociale_2').removeAttr('disabled');
            $('#' + uniqid + '_N_TVA_CEE_facture').removeAttr('disabled');
        }


    }).trigger('change');


    $('#' + uniqid + '_location_postal_pays_id_postal').change(function () {

        var N_TVA_CEE = $('#' + uniqid + '_N_TVA_CEE');
        var N_TVA_CEE_label = $('#sonata-ba-field-container-' + uniqid + '_N_TVA_CEE label');

        if (Sonata.country_eu[$(this).val()]) {
            N_TVA_CEE.attr('required', 'required');
            add_label_required(N_TVA_CEE_label);
        }
        else {
            N_TVA_CEE.removeAttr('required');
            rm_label_required(N_TVA_CEE_label);
        }

    }).trigger('change');


    $('#' + uniqid + '_niveau_dobligation_id').change(function () {

        $('#sonata-ba-field-container-' + uniqid + '_niveau_dobligation_id .help-block').text(Sonata.niveau_dobligation[$(this).val()] ? Sonata.niveau_dobligation[$(this).val()] : '');

    }).trigger('change');


    $('#' + uniqid + '_nature_du_client').change(function () {
        //DEB => 2 id
        if ($(this).val() != 2) {

            if ($('#' + uniqid + '_niveau_dobligation_id').val() == 4) {
                $('#' + uniqid + '_niveau_dobligation_id').val('');
                $('#sonata-ba-field-container-' + uniqid + '_niveau_dobligation_id .help-block').text('');
            }

            $('#' + uniqid + '_niveau_dobligation_id option').each(function () {
                if ($(this).val() == 4) {
                    $(this).hide();
                }
            });
        }
        else {
            $('#' + uniqid + '_niveau_dobligation_id option').each(function () {
                if ($(this).val() == 4) {
                    $(this).show();
                }
            });
        }
    }).trigger('change');

});

var fields_address = ['adresse_1', 'adresse_2', 'code_postal', 'ville', 'pays_id'];

function copy_address() {
    $('#clone_address').live('click', function () {

        $.each(fields_address, function (i, field) {
            $('#' + uniqid + '_location_facturation_' + field + '_facturation').val($('#' + uniqid + '_location_postal_' + field + '_postal').val());
        });

        $('#' + uniqid + '_raison_sociale_2').val($('#' + uniqid + '_raison_sociale').val());
        $('#' + uniqid + '_N_TVA_CEE_facture').val($('#' + uniqid + '_N_TVA_CEE').val());

        return false;
    });
}


function add_label_required(field) {

    if (field.text().indexOf('*') < 0) {
        field.text(field.text() + '*');
    }
}

function rm_label_required(field) {
    field.text(field.text().replace('*', ''));
}

/**
 * @param event
 * @return {Boolean}
 */
function required_spaces(e) {
    var chr = String.fromCharCode(e.charCode == undefined ? e.keyCode : e.charCode);
    return chr != ' ';
}

/**
 * @param event
 */
function replace_spaces(e) {
    $(this).val($(this).val().replace(/\s+/, ''));
}