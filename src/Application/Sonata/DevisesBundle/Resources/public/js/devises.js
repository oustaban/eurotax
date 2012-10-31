jQuery(document).ready(function ($) {

    $('#' + uniqid + '_date_change').change(function () {
        location.href = $(this).val();
    });

    if ($('.form-horizontal [disabled]').size()) {
        $('.form-actions').remove();
    }
});