$(function () {
    $('td.sonata-ba-list-field-money, td.sonata-ba-list-field-number').each(function () {
        var $this = $(this);
        if($this.find('div').hasClass('valeur_statistique') || $this.find('div').hasClass('valeur_fiscale')) {
        	return;
        }
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



function euro_num_format(rnum, rlength, returnzero) { 
	if(typeof rlength === 'undefined') {
		rlength = 2;
	}
	
	if(typeof returnzero === 'undefined') {
		returnzero = false;
	}
	
	rnum = real_num(rnum);
	
	
	if(rnum == 0 && returnzero === true) {
		return '0,00';	
	}
	
	if(rnum === '') {
		return '';
	}
	
	
	var numberStr = rnum.toFixed(rlength).toString().replace('.', ',');
	var numFormatDec = numberStr.slice(-2); //decimal 00
	
	numberStr = numberStr.substring(0, numberStr.length-3); //cut last 3 strings
	
	
	
	var numFormat = [];
	while (numberStr.length > 3) {
		numFormat.unshift(numberStr.slice(-3));
		numberStr = numberStr.substring(0, numberStr.length-3);
	}
	numFormat.unshift(numberStr);
	
	
	
	return numFormat.join(' ')+','+numFormatDec; //format 000 000 000,00 
	
}


function real_num(num) {
	if(num == '' || typeof num === 'undefined') {
		return '';
	}
	num = num.toString().replace(/^\s+|\s+$/g, '').replace(',', '.').replace(/\s+/, '');
	num = encodeURIComponent(num).replace('%C2%A0', '').replace('%20', ''); // to ensure spaces are replaced w/ empty string
	
	return parseFloat(num);
}
