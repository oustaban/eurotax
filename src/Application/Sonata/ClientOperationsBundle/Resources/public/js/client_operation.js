jQuery(document).ready(function ($) {
    $('#error_repost_show').die().live('click', function () {

        $('#importReportModal').modal('toggle');

        return false;
    });

    var function_alert = function () {
        $('.main-content .alert-info').empty().removeClass('alert-info').removeClass('alert');
    };

    $('#doAnnuler').die().live('click', function () {

        var import_id = $(this).attr('import_id');

        $.ajax({
            type:'GET',
            url:Sonata.url.importremove,
            data:{ id:import_id },
            success:function (i) {
                if (i.status) {
                    function_alert();
                }
            }
        });

        return false;
    });


    $('#doValider').die().live('click', function () {
        function_alert();
        return false;
    });
});