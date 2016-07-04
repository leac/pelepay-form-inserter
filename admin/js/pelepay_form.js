(function () {
    tinymce.PluginManager.add('pelepay_tc_button', function (editor, url) {
        editor.addButton('pelepay_tc_button', {
            title: editor.getLang('pelepay_tc_button.button_label'),
            icon: 'icon dashicons-clock', /*dashicons-cart*/
            onclick: function () {
                editor.windowManager.open({
                    title: editor.getLang('pelepay_tc_button.button_label'),
                    body: [{
                            type: 'textbox',
                            name: 'title',
                            label: editor.getLang('pelepay_tc_button.first_option_text')
                        },
                        {
                            type: 'textbox',
                            name: 'price_list',
                            label: editor.getLang('pelepay_tc_button.price_list')
                        },
                        {
                            type: 'textbox',
                            name: 'price_text',
                            label: editor.getLang('pelepay_tc_button.price_text')
                        },
                        {
                            type: 'textbox',
                            name: 'payment_for',
                            label: editor.getLang('pelepay_tc_button.payment_for')
                        },
                        {
                            type: 'textbox',
                            name: 'payments',
                            label: editor.getLang('pelepay_tc_button.payments_text')
                        },
                        {
                            type: 'listbox',
                            name: 'principles_page',
                            label: editor.getLang('pelepay_tc_button.page_redirect_text'),
                            'values': editor.settings.principlesPagesList


                        }
                    ],
                    onsubmit: function (e) {
                        /* enclose string in double quotes, so attribute values can be enclosed in single quotes, so that double quotes can be used in values*/
                        editor.insertContent("[pelepay_form first_option='" + e.data.title + "' price_list='" + e.data.price_list + "' price_text='" + e.data.price_text + "' payment_for='" + e.data.payment_for + "' payments='" + e.data.payments + "' principles_page='" + e.data.page_rprinciples_pageedirect + "' ]");
                        /* old code, enclosed in single quotes:
                         * editor.insertContent('[pelepay_form price_list="' + e.data.price_list + '" price_text="' + e.data.price_text + '"'+ ' payments="' + e.data.payments + '" ]');*/
                    }
                });
            }
        });
    });
})();

