jQuery(document).ready(function($){



    $('#'+uniqid+'_type_document').change(function(){
        switch($(this).val()){
            case '2':
            $('#sonata-ba-field-container-'+uniqid+'_date_notaire, #sonata-ba-field-container-'+uniqid+'_date_apostille').show();
            break;

            default:
            $('#sonata-ba-field-container-'+uniqid+'_date_notaire, #sonata-ba-field-container-'+uniqid+'_date_apostille').hide();
            break;
        }

    }).change();

});