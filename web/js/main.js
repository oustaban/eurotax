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
            headers:{ 0:{ sorter:false} },
            cancelSelection:false
        });
        $('#clientList').columnFilters({
            excludeColumns: [0],
            wildCard:false,
            underline:true,
            alternateRowClassNames:['rowa', 'rowb']
        });

        var $btn = $('<button rel="tooltip" title="Oter filtre" style="margin-right:10px;" class="btn" id="emptyColumnFilters"><i class="icon icon-remove"></i></button>');
        $('#clientList .filterColumns td:first').prepend($btn);
        $btn.tooltip();

        $("#emptyColumnFilters").click(function(){
            $(this).parent().parent().find("._filterText").val("").keyup();
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


function add_label_required(field) {

    if (field.text().indexOf('*') < 0) {
        field.text(field.text() + '*');
    }
}

function rm_label_required(field) {
    field.text(field.text().replace('*', ''));
}

function formatDate(date, format){
    var val = {
        'd': date.getDate(),
        'm': date.getMonth() + 1,
        'yy': date.getFullYear().toString().substring(2),
        'yyyy': date.getFullYear()
    };
    val['dd'] = (val.d < 10 ? '0' : '') + val.d;
    val['mm'] = (val.m < 10 ? '0' : '') + val.m;

    var parts = ['yyyy', 'mm', 'dd', 'yy', 'm', 'd'];

    for (var p in parts){
        format = format.replace(parts[p], val[parts[p]]);
    }

    return format;
}