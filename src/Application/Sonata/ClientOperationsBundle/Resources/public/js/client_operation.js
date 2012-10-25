jQuery(document).ready(function ($) {
    $('#error_repost_show').die().live('click', function () {

        $('#importReportModal').modal('toggle');

        return false;
    });
});

function init_clientoperations_buttons(o){
    $('#btn_client_alert').live('click', function (event) {
        field_dialog_form_add__id(event, o.alert.link, {title : o.alert.title });
        return false;
    });

    if (o.blocking.permit){
        $("#btn_locking").click(function () {
            if (o.blocking.isBlocked && o.blocking.hasBlocking){
                $('#btn_client_alert').click();
            }
            else
            {
                if (confirm(o.blocking.text)) {
                    location.href = o.blocking.link;
                }
            }
        });
    }

    $('input[id=inputFile]').change(function () {
        $('#inputFileCover').val($(this).val());
    });

    $('#deleteImport').click(function () {
        $.ajax({
            url: o.import.link,
            dataType:'json',
            success:function (json) {
                var $table = $('<table class="table table-bordered table-striped table-hover" style="width: auto;"><thead></thead></table>');
                $('<tr class="sonata-ba-list-field-header" />')
                    .append('<th class="sonata-ba-list-field-header-integer">Id</th>')
                    .append('<th class="sonata-ba-list-field-header-date">Date</th>')
                    .append('<th class="sonata-ba-list-field-header-orm_many_to_one">User</th>')
                    .append('<th class="sonata-ba-list-field-header-actions">Action</th>')
                    .appendTo($table.children('thead'));

                var $tbody = $('<tbody />');
                for (i in json.imports) {
                    var deleteUrl = o.import.delete.replace("__id__", json.imports[i].id);
                    $('<tr />')
                        .append('<td class="sonata-ba-list-field-header-integer">' + json.imports[i][0].id + '</td>')
                        .append('<td class="sonata-ba-list-field-header-date"><b>' + json.imports[i][0].date.date + '</b></td>')
                        .append('<td class="sonata-ba-list-field-header-orm_many_to_one">' + json.imports[i].username + '</td>')
                        .append('<td class="sonata-ba-list-field-header-actions"><a style="display: inline;" class="import_del" href="' + deleteUrl + '">Supprimer</a></td>')
                        .appendTo($tbody);
                }
                $table.append($tbody);

                // populate the popup container
                field_dialog__id.find('.popup-body').html('').append($table);
                field_dialog__id.find('.title').html(json.title);
                field_dialog__id.modal('toggle');

                $table.find('a.import_del').click(function () {
                    return confirm(o.import.confirm);
                });
            }
        });
    });
}

function init_rapprochement_sums()
{
    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_input table.table tr.totals_row div b :first');
    $plus = $plus.length?$plus.html():'0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_input table.table tr.totals_row div b :first');
    $minus = $minus.length?$minus.html():'0';
    $('#totals_input_v1').html(
        (Math.round((
            Number($plus.replace(',', '.')) -
                Number($minus.replace(',', '.'))
            )*100)/100).toFixed(2).toString().replace('.', ',')
    );

    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_input table.table tr.totals_row div b :last');
    $plus = $plus.length?$plus.html():'0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_input table.table tr.totals_row div b :last');
    $minus = $minus.length?$minus.html():'0';
    $('#totals_input_v2').html(
        (Math.round((
            Number($plus.replace(',', '.')) -
                Number($minus.replace(',', '.'))
            )*100)/100).toFixed(2).toString().replace('.', ',')
    );

    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_output table.table tr.totals_row div b :first');
    $plus = $plus.length?$plus.html():'0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_output table.table tr.totals_row div b :first');
    $minus = $minus.length?$minus.html():'0';
    $('#totals_output_v1').html(
        (Math.round((
            Number($plus.replace(',', '.')) -
                Number($minus.replace(',', '.'))
            )*100)/100).toFixed(2).toString().replace('.', ',')
    );

    var $plus = $('.rapprochement_content_no_deb .rapprochement_content_output table.table tr.totals_row div b :last');
    $plus = $plus.length?$plus.html():'0';
    var $minus = $('.rapprochement_content_deb .rapprochement_content_output table.table tr.totals_row div b :last');
    $minus = $minus.length?$minus.html():'0';
    $('#totals_output_v2').html(
        (Math.round((
            Number($plus.replace(',', '.')) -
                Number($minus.replace(',', '.'))
            )*100)/100).toFixed(2).toString().replace('.', ',')
    );
}

function init_tabs_filters_sticky_header(){
    var $thead = $('.sonata-ba-list .table thead:first');
    if ($thead.length > 0) {
        var $tr = $('<tr />').prependTo($thead);
        var $th = $('<th />')
            .attr('colspan', $thead.children(':last').children().length)
            .append($('.clientoperations_tabs_types_block'))
            .appendTo($tr);

        $('.clientoperations_tabs_types_block #client-topinfo').after($('#block_actions'));

        var depth = 0;
        $thead.children().addClass('sticky_header').each(function () {
            $(this).attr('depth', depth++);
        });
        $('.sonata-ba-list .table').stickyHeader({
            'headerClassName':'sticky_header',
            'depth':[1, 1, 1]
        });
        $('.sticky_header_clone ._filterText').keyup(function () {
            var id = $(this).attr('id').replace("clone_", "_");
            $('#' + id).val($(this).val()).keyup();
        }).each(function () {
                $(this).attr('id', 'clone' + $(this).attr('id'));
            });
        $('.sticky_header_orig ._filterText').keyup(function () {
            $('#clone' + $(this).attr('id')).val($(this).val());
        });
        $('.sticky_header_clone a').click(function () {
            var href = $(this).attr('href');
            $('.sticky_header_orig a[href="' + href + '"]').click();

            return false;
        });
    }
}