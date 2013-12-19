$(function () {
    $('td.sonata-ba-list-field-money, td.sonata-ba-list-field-number').each(function () {
    	//return;
        var $this = $(this),
        	val = $this.text().trim(),
        	formattedVal = '';
        
        if (val == ''){
            return;
        }
        if ($this.hasClass('sonata-ba-list-field-money') && (!$this.find('div').hasClass('valeur_statistique') && !$this.find('div').hasClass('valeur_fiscale'))) {
        	formattedVal = euro_num_format(real_num(val), 2, true);
        } else  if ($this.hasClass('sonata-ba-list-field-number')) {
        	formattedVal = real_num(val);
        } else {
        	formattedVal = euro_num_format(real_num(val), 0, true);
        }
        $this.html($this.html().replace(val, formattedVal));
        
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
	
	var implode = numFormat.join(' ')+','+numFormatDec; //format 000 000 000,00
	var formatted = implode.replace('- ', '-');
	return formatted;
}


function real_num(num) {
	if(num == '' || typeof num === 'undefined') {
		return '';
	}
	num = num.toString().replace(/^\s+|\s+$/g, '').replace(',', '.').replace(/\s+/, '');
	num = encodeURIComponent(num).replace('%C2%A0', '').replace('%20', ''); // to ensure spaces are replaced w/ empty string
	
	return parseFloat(num);
}
