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
        repl = repl.toString();

        $this.html($this.html().replace(val, repl));
        $this.html($this.html().replace('.', ','));
    });

    $('.sonata-ba-list-field-percent').each(function(){
        if ($(this).html().replace(/[\n\r %,\.0]*/g, '') == ''){
            $(this).html('');
        }
    });
});