$(function(){
    $.fn.datepicker.defaults = $.extend({}, $.fn.datepicker.defaults, {language: default_locale, format: default_date_format_js, autoclose:true});
    $('.datepicker').datepicker();
});