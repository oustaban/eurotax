var ajax_loading = false;
$(function () {
    if (!ajax_loading) {
        ajax_loading = $(html_ajax_load()).hide().appendTo('body');
    }
    $('#ajax_loading')
        .bind('ajaxSend', function () {
            ajax_loading.show();
        })
        .bind('ajaxComplete', function () {
            ajax_loading.hide();
        });
});