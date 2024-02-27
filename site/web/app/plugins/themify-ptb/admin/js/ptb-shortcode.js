(function ($, window, document, undefined) {
    'use strict';
    if (typeof shortcodes_button!='undefined' && shortcodes_button && shortcodes_button.length > 0) {
        
        tinymce.PluginManager.add('ptb', function (editor, url) {
            var $items = [];
            for (var k in shortcodes_button) {

                var $item = {
                    'text': shortcodes_button[k].name,
                    'body': {
                        'type': shortcodes_button[k].type
                    },
                    onclick: function (e) {
                        var $settings = this.settings.body;
                        $.event.trigger("PTB.before_ajax_shortcode",{'settings':$settings});
                        $.ajax({
                            url: $ptb_url,
                            type: 'POST',
                            dataType: 'json',
                            data: {'post_type': $settings.type},
                            success: function (resp) {
                                if (resp) {
                                    $.event.trigger("PTB.after_ajax_shortcode",{'settings':$settings,'result':resp});
                                    var post_data = [];
                                    var $data = resp.data;
                                    for (var $key in $data) {

                                        var $form_items  = {
                                            'name': $key,
                                            'values': $data[$key].values ? $data[$key].values : '',
                                        };
                                       
                                        $form_items = $.extend(true,$form_items,$data[$key]);
                                        post_data.push($form_items);
                                    }
                                    if (resp.taxes) {
                                        for (var i in resp.taxes) {
                                            var $list;
                                            $list = {
                                                'label': resp.taxes[i].label,
                                                'values': [],
                                                'type': 'listbox',
                                                'name': 'ptb_tax_' + resp.taxes[i].name
                                            };
                                            $list.values.push({
                                                'text': '---',
                                                'value': false
                                            });
                                            for (var $i in resp.taxes[i].values) {
                                                $list.values.push({
                                                    'text': resp.taxes[i].values[$i].name,
                                                    'value': resp.taxes[i].values[$i].slug
                                                });
                                            }
                                            post_data.push($list);
                                        }
                                    }
                                    editor.windowManager.open({
                                        'body': post_data,
                                        'title':resp.title,
                                        onsubmit: function (e) {
                                            var $short = '';
                                            var $trigger_short = $.event.trigger("PTB.insert_shortcode",{'shortcode':$short,'setting':$settings,'data':e.data});
                                            if($trigger_short){
                                                $short = $trigger_short;
                                            }
                                            else{
                                                $short = '[ptb type="' + $settings.type + '"';
                                                for (var $k in e.data) {
                                                    if (e.data[$k]) {
                                                        $short += ' ' + $k + '="' + e.data[$k] + '"';
                                                    }
                                                }
                                                if($('#ptb_post_filer').val()){ 
                                                    $short+= ' post_filer="'+$('#ptb_post_filer').val().toString()+'" ';
                                                }
                                                $short += ']';
                                            }
                                            editor.insertContent($short);
                                        }
                                    });
                                }
                            }
                        });
                    },
                    classes:shortcodes_button[k].classes
                };
                $items.push($item);
            }
            editor.addButton('ptb', {
                icon: 'ptb-favicon',
                type: 'menubutton',
                title: 'PTB Shortcodes',
                menu: $items
            });
        });
    }
}(jQuery, window, document));
