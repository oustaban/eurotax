jQuery(document).ready(function ($) {
    $('#error_repost_show').die().live('click', function () {

        $('#importReportModal').modal('toggle');

        return false;
    });
});