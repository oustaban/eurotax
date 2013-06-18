$(function () {
	
	$('#DEB-Exped').find('.totals').each(function() {
		var isRegime25 = $(this).attr('rel') == 'deb-25' ? true : false;
		if(isRegime25) {
			$(this).html(  parseFloat($(this).html()) * -1 )
		}
	});
	
	
	
	
    $('.sonata-ba-list .table').each(function(){
        var $tfoot = $(this).find('tfoot:first');
        if (!$tfoot.length) {
            $tfoot = $('<tfoot />').appendTo(this);
        }
        var $tr = $(this).find('tbody tr:first').clone();
        $tr.find('.negative_value').removeClass('negative_value');
        
        var add_total_rows = false;
        $tr.addClass('totals_row').children().each(function (i) {
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
                	if(parseFloat($(this).html()))
                		sum += parseFloat($(this).html());
                });
                $div.html('<b>' + sum + '</b>');
                if (sum < 0) {
                    $div.addClass('negative_value');
                }
            }
        });

        if (add_total_rows) {
        	 var $tdFirst = $tr.find('td:first');
             var totalTitle = $tdFirst.attr('title');
             if(totalTitle) {
	            $tdFirst.html("<b>" + totalTitle + " </b> ").end();
            	//console.log(totalTitle);
             }	
        	
            $tr.appendTo($tfoot);
        }
        else {
            $tr.remove();
        }
    });
});