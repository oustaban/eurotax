$(function () {
    $('.sonata-ba-list .table').each(function(){
        var $tfoot = $(this).find('tfoot:first');
        if (!$tfoot.length) {
            $tfoot = $('<tfoot />').appendTo(this);
        }
        var $tr = $(this).find('tbody tr:first').clone();

        var add_total_rows = false;
        $tr.addClass('totals_row').children().each(function () {
            $(this).addClass('total').removeAttr('objectid');
            var $div = $(this).children();
            if (!$div.hasClass('totals')) {
                $(this).html('&nbsp;');
            }
            else {
                add_total_rows = true;
                var tc = $div.removeClass('totals')
                    .attr("class");
                $div.attr("class", tc + '_total');

                var sum = 0;
                $('.' + tc).each(function () {
                    sum += parseFloat($(this).html());
                });
                $div.html('<b>' + sum + '</b>');
            }
        });

        if (add_total_rows) {
            $tr.appendTo($tfoot);
        }
        else {
            $tr.remove();
        }
    });
});