/**
 * jQuery Sticky Headers for tables
 *
 * Copyright (c) 2011 Volodymyr Nakvasiuk
 *
 */
(function($) { // hide the namespace
    
    var PROP_NAME = 'sticky-header';

    function StickyHeader() {
        this.debug = false;
        this.className = 'sticky_header_target';
        this.columnsClassName = 'sticky_columns_target';
        this.columnsCloneClassName = 'sticky_columns_clone';
        this.cloneClassName = 'sticky_header_clone';
        this.rowTag = 'tr';
        this.colTag = 'td,th';
        this._defaults = { // Global defaults
            'headerClassName':'sticky_header',
            'depthProperty':'depth',
            'depth':[1],
            'fixedColumns':0,
            'disabled':false
        };
    }

    $.extend(StickyHeader.prototype, {
        log: function () {
            if (this.debug){
                var msg = '[jquery.sticky.header] ' + Array.prototype.join.call(arguments,'');
                if (window.console && window.console.log) {
                    window.console.log(msg);
                }
                else if (window.opera && window.opera.postError) {
                    window.opera.postError(msg);
                }
            }
        },
        _newInst: function(target) {
            return {
                id: target[0].id,
                target: target,
                settings:{}
            };
        },
        _get: function(inst, name) {
            return inst.settings[name] !== undefined ?
            inst.settings[name] : this._defaults[name];
        },
        _set: function(inst, name, val) {
            inst.settings[name] = val;
        },
        init: function(target, settings) {
            if (!target.id)
                target.id = 'sh' + new Date().getTime();
            var inst = this._newInst($(target));
            inst.settings = settings || {};

            if (this._get(inst,'fixedColumns')>0){
                var cb = $('<div/>')
                .attr("target_id", target.id)
                .addClass($.stickyHeader.columnsClassName)
                .css("position", "fixed")
                .css("left", 0)
                .attr("left", inst.target.offset().left)
                .attr("top", inst.target.offset().top)
                .css('z-index', 10000)
                .appendTo(document.body)
                .hide()
                ;
                
                var table = $("<table />").appendTo(cb);
                table.attr('class', inst.target.attr('class'));
                
                inst.target.find($.stickyHeader.rowTag).each(function(index){
                    if (!this.id) this.id = 'shhc' + new Date().getTime();
                    var row = $('<' + this.nodeName.toLowerCase() + ' id="' + this.id + '_clone_col" />').appendTo(table);
                    row.css('height', $(this).height()).attr('class', $(this).attr('class'));
                    var depthProperty = $.stickyHeader._get(inst,'depthProperty');
                    row.attr(depthProperty, $(this).attr(depthProperty));
                    $(this)
                    .children($.stickyHeader.colTag).filter(':lt(' + $.stickyHeader._get(inst,'fixedColumns') + ')')
                    .each(function(index){
                        $(this)
                        .clone()
                        .css("width", $(this).width())
                        .css("height", $(this).height())
                        .appendTo(row)
                    ;
                    });
                });
            }
            
            inst.target.addClass(this.className);
            this.log('.'+this._get(inst,'headerClassName'));
            
            inst.target.find('.'+this._get(inst,'headerClassName')).each(function(index){
                if (!this.id) this.id = 'shh' + new Date().getTime();
                var clone = $(this).clone()
                .attr('id', this.id+'_clone')
                .attr("left", $(this).offset().left)
                .addClass($.stickyHeader.cloneClassName)
                .css("position", "fixed")
                .css('z-index', 500+5*index)
                .insertAfter(this)
                .hide();
                
                if ($.stickyHeader._get(inst,'fixedColumns')>0){
                    clone.children($.stickyHeader.colTag).filter(':lt(' + $.stickyHeader._get(inst,'fixedColumns') + ')')
                    .each(function(index){
                        if (!this.id) this.id = 'shc' + new Date().getTime();
                        $(this)
                        .clone()
                        .addClass($.stickyHeader.columnsCloneClassName)
                        .attr('id', this.id+'_clone')
                        .css('position', 'fixed')
                        .css('z-index', Number(clone.css('z-index'))+10000)
                        .appendTo('#' + clone.attr('id') + '_col')
                        .hide();
                    });
                }
            });
            
            $.data(target, PROP_NAME, inst);
        },
        updateTableHeaders: function (){
            $('.'+$.stickyHeader.className).each(function (){
                var inst = $.data(this, PROP_NAME);
                if ($.stickyHeader._get(inst,'disabled')) {
                    //$(this).hide().find('.' + $.stickyHeader.columnsCloneClassName).hide();
                    return;
                }
                var configDepth = $.stickyHeader._get(inst,'depth');
                var $table = $(this);
                var topLine = 0;
                var depth = [];
                $(this).find('.'+$.stickyHeader._get(inst,'headerClassName')+':not(.'+$.stickyHeader.cloneClassName+')')
                .each(function(){
                    var clonedHeader = $("#"+this.id+"_clone");
                    var d = $(this).attr($.stickyHeader._get(inst, 'depthProperty'));
                    if (!d) d = 0;
                    var offset = $(this).offset();
                    var scrollTop = $(window).scrollTop();
                    if (scrollTop+topLine > offset.top && scrollTop+topLine < $table.offset().top+$table.height()){
                        if (!depth[d]) depth[d] = new Array();
                        if (!configDepth[d]) configDepth[d] = 0;
                        if (configDepth[d]>0){
                            if (configDepth[d]>=depth[d].length){
                                var removeId = depth[d].shift();
                                topLine -= $("#"+removeId).height();
                            }
                            $('td,th', this).each(function(index){
                                clonedHeader.find('td,th').eq(index).width($(this).width());
                            });
                            clonedHeader
                            .width($table.width())
                            .css("top", Math.max(topLine, offset.top-scrollTop) + "px")
                            .css("left", Number(clonedHeader.attr("left")) - $(window).scrollLeft() + "px")
                            .show();
                            topLine += $(this).height();
                            depth[d].push(this.id);
                        }
                    }
                    else {
                        clonedHeader.hide();
                    }
                });
            });
            $('.' + $.stickyHeader.columnsClassName).each(function(){
                var inst = $.data($("#" + $(this).attr("target_id"))[0], PROP_NAME);
                if ($.stickyHeader._get(inst,'disabled')) {
                    //$(this).hide().find('.' + $.stickyHeader.columnsCloneClassName).hide();
                    return;
                }
                if (inst.target.offset().left - $(window).scrollLeft() < 0)
                {
                    var top = inst.target.offset().top - $(window).scrollTop();
                    $(this).css('top' ,top).show();
                    if (top < 0){
                        var collection = $(this).find('.' + $.stickyHeader.columnsCloneClassName);
                        collection.parent().attr('leftc', 0);
                        collection.each(function(index){
                            var orig = $('#' + this.id.replace('_clone', ''));
                            var pl = Number($(this).parent().attr('leftc'));
                            $(this)
                            .css('left', pl)
                            .css('top' ,orig.offset().top - $(window).scrollTop() - 1)
                            .width(orig.width())
                            .height(orig.height())
                            .show();
                            $(this).parent().attr('leftc', $(this).width()+parseInt($(this).css('border-left-width'))+parseInt($(this).css('border-right-width'))+parseInt($(this).css('padding-left'))+parseInt($(this).css('padding-right'))+pl);
                        });
                    }
                    else
                    {
                        $(this).find('.' + $.stickyHeader.columnsCloneClassName).hide();
                    }
                }
                else
                {
                    $(this).hide().find('.' + $.stickyHeader.columnsCloneClassName).hide();
                }
            });
        },
        _enableTable: function (target, settings){
            var inst = $.data(target, PROP_NAME);
            if (!inst) return;
            $.stickyHeader._set(inst,'disabled', false);
            $(window).scroll();
        },
        _disableTable: function (target, settings){
            var inst = $.data(target, PROP_NAME);
            if (!inst) return;
            $.stickyHeader._set(inst,'disabled', true);
            $('.' + $.stickyHeader.columnsClassName).hide().find('.' + $.stickyHeader.columnsCloneClassName).hide();
            $('.'+$.stickyHeader.cloneClassName).hide();
            $(window).scroll();
        }
    });

    $.fn.stickyHeader = function(options){
        var otherArgs = Array.prototype.slice.call(arguments, 1);
        var returnValues = {};
        if (typeof options == 'string' && returnValues[options])
            return $.stickyHeader['_' + options + 'Table'].
            apply($.stickyHeader, [this[0]].concat(otherArgs));
        return this.each(function() {
            typeof options == 'string' ?
            $.stickyHeader['_' + options + 'Table'].
            apply($.stickyHeader, [this].concat(otherArgs)) :
            $.stickyHeader.init(this, options);
        });
    };

    $.stickyHeader = new StickyHeader();

    $(window)
    .scroll($.stickyHeader.updateTableHeaders)
    .resize($.stickyHeader.updateTableHeaders);
})(jQuery);
