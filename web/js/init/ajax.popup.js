/**
 * @type {Boolean}
 * @private
 */
var field_dialog__id = false;
/**
 * @type {*}
 */
var symfony_ajax = symfony_ajax || {'behaviors':{}};

/**
 * @param context
 */
symfony_attach_behaviors = function (context) {
    context = context || document;
    $.each(symfony_ajax.behaviors, function () {
        if ($.isFunction(this.attach)) {
            this.attach(context);
        }
    });
};

/**
 * @return {*}
 */
symfony_ajax.get_uniqid = function () {

    var _uniqid = null;
    var client_id_name = $('.client_id').attr('name');

    if (client_id_name) {
        _uniqid = client_id_name.replace('[client]', '').replace('[client_id]', '');
    }
    return _uniqid;
}

/**
 * @private
 */
function initialize_popup__id() {
    // initialize component
    if (!field_dialog__id) {
        field_dialog__id = jQuery("#field_dialog__id");

        // move the dialog as a child of the root element, nested form breaks html ...
        jQuery(document.body).append(field_dialog__id);

        field_dialog__id.on('shown', function () {
            $(this).find('form input[type="text"], form select').first().focus();
        });
    }
}

// handle the add link
var field_dialog_form_add__id = function (event, link, options) {

    options = options || {};
    options = $.extend({
        title:false
    }, options);

    if (options.title) {
        field_dialog__id.find('.title').html(options.title);
    }
    else {
        field_dialog__id.find('.title').html('[[no_title]]');
    }
    event.preventDefault();
    event.stopPropagation();

    var a = jQuery(this);

    field_dialog__id.find('.popup-body').html('');

    // retrieve the form element from the related admin generator
    jQuery.ajax({
        url:link,
        dataType:'html',
        success:function (html) {
            field_dialog_form_content__id(html);

            field_dialog__id.modal('toggle');
        }
    });
};



var field_dialog_form_content__id = function (html) {
    // populate the popup container
    field_dialog__id.find('.popup-body').html(html);


    jQuery('.sonata-actions', field_dialog__id).parent().remove();

    var dialog_title = field_dialog__id.find('.title').html();

    if (dialog_title == '[[no_title]]') {
        dialog_title = jQuery('.popup-body legend', field_dialog__id).text();
        if (dialog_title == '') {
            dialog_title = jQuery('.popup-body h1', field_dialog__id).text();
        }
    }

    if (dialog_title == '') {
        dialog_title = '&nbsp;';
    }

    jQuery('.popup-body legend', field_dialog__id).remove();
    jQuery('.popup-body h1', field_dialog__id).remove();
    jQuery('.popup-body .span5', field_dialog__id).removeClass('span5');

    field_dialog__id.find('.title').html(dialog_title);

    jQuery('.datepicker').datepicker();

    // capture the submit event to make an ajax call, ie : POST data to the
    // related create admin
    jQuery('a', field_dialog__id).die().live('click', field_dialog_form_action__id);
    jQuery('form', field_dialog__id).die().live('submit', field_dialog_form_action__id);

    field_dialog__id.find('.form-actions').each(function () {
        jQuery('.action-buttons', field_dialog__id).html($(this).html());
    });

    jQuery('.action-buttons input', field_dialog__id).each(function () {
        $(this).click(function () {
            jQuery('form input[name="' + $(this).attr('name') + '"]', field_dialog__id).click();
        });
    });

    symfony_attach_behaviors(field_dialog__id.find('.popup-body'));
};

// handle the post data
var field_dialog_form_action__id = function (event) {

    event.preventDefault();
    event.stopPropagation();

    initialize_popup__id();

    var element = jQuery(this);

    var url;
    var type;

    if (this.nodeName == 'FORM') {
        url = element.attr('action');
        type = element.attr('method');
    } else if (this.nodeName == 'A') {
        url = element.attr('href');
        type = 'GET';
    } else {
        alert('unexpected element : @' + this.nodeName + '@');
        return;
    }

    if (element.hasClass('sonata-ba-action')) {
        return;
    }

    var data = {
        _xml_http_request:true
    };

    var form = jQuery(this);

    // the ajax post
    jQuery(form).ajaxSubmit({
        url:url,
        type:type,
        data:data,
        success:function (data) {

            if (typeof data == 'string') {
                field_dialog_form_content__id(data);
                return;
            }

            // if the crud action return ok, then the element has been added
            // so the widget container must be refresh with the last option available
            if (data.result == 'ok') {
                $(html_ajax_load()).show().appendTo('body');
                field_dialog__id.dialog('close');
                window.location.reload();
                return;
            }

            // otherwise, display form error
            field_dialog_form_content__id(data);
            Admin.add_pretty_errors(field_dialog__id);

            // reattach the event
            jQuery('form', field_dialog__id).submit(field_dialog_form_action__id);
        }
    });

    return false;
};

$(function () {
    initialize_popup__id();
    symfony_attach_behaviors(document);
});

function init_ajax_edit_popup()
{
    var $table = $('.sonata-ba-list .table tbody:first');
    $table.find('tr:not(.totals_row) td[objectid]').css('cursor', 'pointer').live('click', function (event) {
        var objectid = $(this).attr('objectid');
        if (objectid) {
            var link = editObjectAbstractUrl.replace("__id__", objectid).replace('&amp;', '&');
            field_dialog_form_add__id(event, link);
        }
    });
}

function init_ajax_create_popup()
{
    $('.sonata-actions .sonata-action-element').live('click', function (event) {
        var link = $(this).attr('href');
        field_dialog_form_add__id(event, link);
        return false;
    });
}

function init_ajax_delete_popup()
{
    $('.sonata-ba-list .delete_link').live('click', function (event) {
        var link = $(this).attr('href');
        field_dialog_form_add__id(event, link);
        return false;
    });
}