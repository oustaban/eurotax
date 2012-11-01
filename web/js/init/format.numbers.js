$(function () {
    $('td.sonata-ba-list-field-money, td.sonata-ba-list-field-integer, td.sonata-ba-list-field-number').each(function () {
        var $this = $(this);
        var val = $this.text().trim().replace(/[^\d\.]+/, '');
        if (val == ''){
            return;
        }
        var repl = Number(val);
        if ($this.hasClass('sonata-ba-list-field-money')) {
            repl = repl.toFixed(2);
        }
        repl = repl.toString().split('.');
        var pattern = /(-?\d+)(\d{3})/;
        while (pattern.test(repl[0])){
            repl[0] = repl[0].replace(pattern, "$1 $2");
        }
        repl = repl.join(',');

        $this.html($this.html().replace(val, repl));
    });

    $('.sonata-ba-list-field-percent').each(function(){
        if ($(this).html().replace(/[\n\r %,\.0]*/g, '') == ''){
            $(this).html('');
        }
    });
});