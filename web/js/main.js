function html_ajax_load() {
    return '<div id="ajax_loading" style="background:#fff; border:1px solid #dddddd; position:fixed; left:45%; top: 25%; padding:20px 30px; z-index:99999" class="all_loading"><img height="66" width="66" alt=""  src="/img/ajax-loader.gif" /></div>';
}
function tableFixLayout() {
    var w = $('.sonata-ba-list table.table').addClass('table-hover').width() + 71;
    if (w > 110) {
        $('#container').css('min-width', w + 'px');
    }
}

function init_home_page() {
    $(function () {
        $('#clientList').tablesorter({
            cancelSelection:false
        });
        $('#clientList').columnFilters({
            wildCard:false,
            underline:true,
            alternateRowClassNames:['rowa', 'rowb']
        });
    });
    $(function () {
        var $table = $('#clientList tbody:first');
        $table.find('tr').css('cursor', 'pointer').click(function () {
            var objectid = $(this).children(':last').attr('objectid');
            if (objectid) {
                location.href = editObjectAbstractUrl.replace("__id__", objectid);
            }
        });
    });
    $(function () {
        $('#show_hide_all_clients').click(function () {
            if ($.cookie('show_all_clients')) {
                $.removeCookie('show_all_clients', { path:'/' });
            }
            else {
                $.cookie('show_all_clients', $.cookie('PHPSESSID'), { path:'/' });
            }
            location.reload();
        });
    });
}

$(function () {
    $('input#username[name="_username"]').focus();
    if ($.cookie('show_all_clients') != $.cookie('PHPSESSID')){
        $.removeCookie('show_all_clients', { path:'/' });
    }
});