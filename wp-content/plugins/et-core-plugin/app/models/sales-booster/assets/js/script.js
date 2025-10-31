jQuery(document).ready(function ($) {

    var xstore_panel_options_global = {
        popup: $('.et_panel-popup'),
        closePopupIcon: '<span class="et_close-popup et-button-cancel et-button"><i class="et-icon et-delete"></i></span>',
        spinner: '<div class="et-loader ">\
					<svg class="loader-circular" viewBox="25 25 50 50">\
					<circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>\
					</svg>\
				</div>',
        i: 0,
        j: 0,
    };

    var xstore_panel_options_global_functions = {
        openPopup: function() {
            $('body').addClass('et_panel-popup-on');
            xstore_panel_options_global.popup.html(xstore_panel_options_global.spinner);
        },
        closePopup: function(response, closeIcon = true, refresh = false) {
            xstore_panel_options_global.popup.html('').addClass('loading');

            if ( closeIcon )
                xstore_panel_options_global.popup.prepend(xstore_panel_options_global.closePopupIcon);

            xstore_panel_options_global.popup.append(response.icon);
            xstore_panel_options_global.popup.append(response.msg);
            xstore_panel_options_global.popup.addClass('active').removeClass('loading');

            if ( refresh )
                window.location = window.location.href;
        }
    };
    
    $('form.xstore-panel-settings .button-upload-file').click(function (e) {
        var fileUploader = '',
            title = $(this).data('title'),
            buttonTitle = $(this).data('button-title'),
            setting = $(this).data('option-name'),
            removeButton = $(this).next('.button-remove-file'),
            parent = $(this).parents('.et-tabs-content'),
            fileType = $(this).data('file-type'),
            attachment;

        e.preventDefault();

        fileUploader = wp.media({
            title: title,
            button: {
                text: buttonTitle
            },
            multiple: false,  // Set this to true to allow multiple files to be selected.
            library:
                {
                    type: [ fileType ]
                }
            })
            .on('select', function () {
                attachment = fileUploader.state().get('selection').first().toJSON();
                if ( fileType == 'image' ) {
                    $(parent).find('.' + setting + '_preview').html('<img src="' + attachment.url + '">');
                }
                else if ( fileType == 'audio' ) {
                    $(parent).find('.' + setting + '_preview').html('<img src="' + XStoreSalesBoosterConfig.audioPlaceholder + '">');
                }
                $(parent).find('#' + setting).val(attachment.url).trigger('change');
                removeButton.show();
            })
            .open();
    });

    $('form.xstore-panel-settings .button-remove-file').click(function (e) {
        let setting = $(this).data('option-name'),
            fileType = $(this).data('file-type'),
            parent = $(this).parents('.et-tabs-content');
        $(parent).find('.' + setting + '_preview').html('');
        $(parent).find('#' + setting).val('').trigger('change');
        $(this).hide();
    });

    $('form.xstore-panel-settings .xstore-panel-option-slider input[type=range]').on("input change", function() {
        $(this).parent().find('.value').text($(this).val());
    });

    var colorPickerOptions = {
        change: function (event, ui) {
            setTimeout(function () {
                if (event.originalEvent) {
                    $(event.target).trigger('change');
                }
            }, 1);
        },
        clear: function() {
            // var defaultColor = $(this).parent().find('.color-field').data('default');
            // $(this).parent().find('.color-field').val(defaultColor);
        }
    };

    $('form.xstore-panel-settings .color-field').wpColorPicker(colorPickerOptions);

    // white label branding page
    $('form.xstore-panel-settings').on('submit', function (e) {
        e.preventDefault();

        var tabs = [],
            all_settings = [],
            tabs_names = [];

        xstore_panel_options_global_functions.openPopup();

        $(this).parent().parent().find('.et-tabs-content').each(function () {
            let tab = $(this).attr('data-tab-content');
            if (tab === 'import') return;
            tabs.push(tab);
            all_settings.push($(this).find('form').serializeArray());
        });

        ajaxSave( tabs, all_settings);

    } );

    var ajaxSave = function ( tabs, all_settings ) {
        $.ajax({
            method: "POST",
            url: ajaxurl,
            dataType: 'JSON',
            data: {
                action: 'sales_booster_save_settings',
                settings: all_settings[xstore_panel_options_global['i']],
                type: tabs[xstore_panel_options_global['i']],
            },
            success: function (response) {
                tabs.slice(xstore_panel_options_global['i'], tabs.length);
                all_settings.slice(xstore_panel_options_global['i'], all_settings.length);
                if ( xstore_panel_options_global['i'] < tabs.length ) {
                    ajaxSave( tabs, all_settings);
                    xstore_panel_options_global['i']++;
                }
                else {
                    xstore_panel_options_global_functions.closePopup(response);
                    xstore_panel_options_global['i'] = 0;
                }
            },
            error: function () {
                xstore_panel_options_global['i'] = 0;
                alert(XStoreSalesBoosterConfig.ajaxError);
            }
        });
    };

    $('form.xstore-panel-settings .xstore-panel-option-icons-select select').on("change", function() {
        $(this).parent().find('.xstore-panel-option-icon-preview .et-icon').attr('class', 'et-icon ' + $(this).val().replace('et_icon', 'et'));
    });
    
});