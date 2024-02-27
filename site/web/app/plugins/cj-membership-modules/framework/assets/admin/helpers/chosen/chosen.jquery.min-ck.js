/* Chosen v1.0.0 | (c) 2011-2013 by Harvest | MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md */!function(){var e,t,n,r,i,s={}.hasOwnProperty,o=function(e,t){function n(){this.constructor=e}for(var r in t)s.call(t,r)&&(e[r]=t[r]);return n.prototype=t.prototype,e.prototype=new n,e.__super__=t.prototype,e};r=function(){function e(){this.options_index=0,this.parsed=[]}return e.prototype.add_node=function(e){return"OPTGROUP"===e.nodeName.toUpperCase()?this.add_group(e):this.add_option(e)},e.prototype.add_group=function(e){var t,n,r,i,s,o;for(t=this.parsed.length,this.parsed.push({array_index:t,group:!0,label:this.escapeExpression(e.label),children:0,disabled:e.disabled}),s=e.childNodes,o=[],r=0,i=s.length;i>r;r++)n=s[r],o.push(this.add_option(n,t,e.disabled));return o},e.prototype.add_option=function(e,t,n){return"OPTION"===e.nodeName.toUpperCase()?(""!==e.text?(null!=t&&(this.parsed[t].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:e.value,text:e.text,html:e.innerHTML,selected:e.selected,disabled:n===!0?n:e.disabled,group_array_index:t,classes:e.className,style:e.style.cssText})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1):void 0},e.prototype.escapeExpression=function(e){var t,n;return null==e||e===!1?"":/[\&\<\>\"\'\`]/.test(e)?(t={"<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"},n=/&(?!\w+;)|[\<\>\"\'\`]/g,e.replace(n,function(e){return t[e]||"&amp;"})):e},e}(),r.select_to_array=function(e){var t,n,i,s,o;for(n=new r,o=e.childNodes,i=0,s=o.length;s>i;i++)t=o[i],n.add_node(t);return n.parsed},t=function(){function e(t,n){this.form_field=t,this.options=null!=n?n:{},e.browser_is_supported()&&(this.is_multiple=this.form_field.multiple,this.set_default_text(),this.set_default_values(),this.setup(),this.set_up_html(),this.register_observers())}return e.prototype.set_default_values=function(){var e=this;return this.click_test_action=function(t){return e.test_active_click(t)},this.activate_action=function(t){return e.activate_field(t)},this.active_field=!1,this.mouse_on_container=!1,this.results_showing=!1,this.result_highlighted=null,this.result_single_selected=null,this.allow_single_deselect=null!=this.options.allow_single_deselect&&null!=this.form_field.options[0]&&""===this.form_field.options[0].text?this.options.allow_single_deselect:!1,this.disable_search_threshold=this.options.disable_search_threshold||0,this.disable_search=this.options.disable_search||!1,this.enable_split_word_search=null!=this.options.enable_split_word_search?this.options.enable_split_word_search:!0,this.group_search=null!=this.options.group_search?this.options.group_search:!0,this.search_contains=this.options.search_contains||!1,this.single_backstroke_delete=null!=this.options.single_backstroke_delete?this.options.single_backstroke_delete:!0,this.max_selected_options=this.options.max_selected_options||1/0,this.inherit_select_classes=this.options.inherit_select_classes||!1,this.display_selected_options=null!=this.options.display_selected_options?this.options.display_selected_options:!0,this.display_disabled_options=null!=this.options.display_disabled_options?this.options.display_disabled_options:!0},e.prototype.set_default_text=function(){return this.default_text=this.form_field.getAttribute("data-placeholder")?this.form_field.getAttribute("data-placeholder"):this.is_multiple?this.options.placeholder_text_multiple||this.options.placeholder_text||e.default_multiple_text:this.options.placeholder_text_single||this.options.placeholder_text||e.default_single_text,this.results_none_found=this.form_field.getAttribute("data-no_results_text")||this.options.no_results_text||e.default_no_result_text},e.prototype.mouse_enter=function(){return this.mouse_on_container=!0},e.prototype.mouse_leave=function(){return this.mouse_on_container=!1},e.prototype.input_focus=function(){var e=this;if(this.is_multiple){if(!this.active_field)return setTimeout(function(){return e.container_mousedown()},50)}else if(!this.active_field)return this.activate_field()},e.prototype.input_blur=function(){var e=this;return this.mouse_on_container?void 0:(this.active_field=!1,setTimeout(function(){return e.blur_test()},100))},e.prototype.results_option_build=function(e){var t,n,r,i,s;for(t="",s=this.results_data,r=0,i=s.length;i>r;r++)n=s[r],t+=n.group?this.result_add_group(n):this.result_add_option(n),(null!=e?e.first:void 0)&&(n.selected&&this.is_multiple?this.choice_build(n):n.selected&&!this.is_multiple&&this.single_set_selected_text(n.text));return t},e.prototype.result_add_option=function(e){var t,n;return e.search_match?this.include_option_in_results(e)?(t=[],e.disabled||e.selected&&this.is_multiple||t.push("active-result"),!e.disabled||e.selected&&this.is_multiple||t.push("disabled-result"),e.selected&&t.push("result-selected"),null!=e.group_array_index&&t.push("group-option"),""!==e.classes&&t.push(e.classes),n=""!==e.style.cssText?' style="'+e.style+'"':"",'<li class="'+t.join(" ")+'"'+n+' data-option-array-index="'+e.array_index+'">'+e.search_text+"</li>"):"":""},e.prototype.result_add_group=function(e){return e.search_match||e.group_match?e.active_options>0?'<li class="group-result">'+e.search_text+"</li>":"":""},e.prototype.results_update_field=function(){return this.set_default_text(),this.is_multiple||this.results_reset_cleanup(),this.result_clear_highlight(),this.result_single_selected=null,this.results_build(),this.results_showing?this.winnow_results():void 0},e.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()},e.prototype.results_search=function(){return this.results_showing?this.winnow_results():this.results_show()},e.prototype.winnow_results=function(){var e,t,n,r,i,s,o,u,a,f,l,c,h;for(this.no_results_clear(),i=0,o=this.get_search_text(),e=o.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),r=this.search_contains?"":"^",n=new RegExp(r+e,"i"),f=new RegExp(e,"i"),h=this.results_data,l=0,c=h.length;c>l;l++)t=h[l],t.search_match=!1,s=null,this.include_option_in_results(t)&&(t.group&&(t.group_match=!1,t.active_options=0),null!=t.group_array_index&&this.results_data[t.group_array_index]&&(s=this.results_data[t.group_array_index],0===s.active_options&&s.search_match&&(i+=1),s.active_options+=1),(!t.group||this.group_search)&&(t.search_text=t.group?t.label:t.html,t.search_match=this.search_string_match(t.search_text,n),t.search_match&&!t.group&&(i+=1),t.search_match?(o.length&&(u=t.search_text.search(f),a=t.search_text.substr(0,u+o.length)+"</em>"+t.search_text.substr(u+o.length),t.search_text=a.substr(0,u)+"<em>"+a.substr(u)),null!=s&&(s.group_match=!0)):null!=t.group_array_index&&this.results_data[t.group_array_index].search_match&&(t.search_match=!0)));return this.result_clear_highlight(),1>i&&o.length?(this.update_results_content(""),this.no_results(o)):(this.update_results_content(this.results_option_build()),this.winnow_results_set_highlight())},e.prototype.search_string_match=function(e,t){var n,r,i,s;if(t.test(e))return!0;if(this.enable_split_word_search&&(e.indexOf(" ")>=0||0===e.indexOf("["))&&(r=e.replace(/\[|\]/g,"").split(" "),r.length))for(i=0,s=r.length;s>i;i++)if(n=r[i],t.test(n))return!0},e.prototype.choices_count=function(){var e,t,n,r;if(null!=this.selected_option_count)return this.selected_option_count;for(this.selected_option_count=0,r=this.form_field.options,t=0,n=r.length;n>t;t++)e=r[t],e.selected&&(this.selected_option_count+=1);return this.selected_option_count},e.prototype.choices_click=function(e){return e.preventDefault(),this.results_showing||this.is_disabled?void 0:this.results_show()},e.prototype.keyup_checker=function(e){var t,n;switch(t=null!=(n=e.which)?n:e.keyCode,this.search_field_scale(),t){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices_count()>0)return this.keydown_backstroke();if(!this.pending_backstroke)return this.result_clear_highlight(),this.results_search();break;case 13:if(e.preventDefault(),this.results_showing)return this.result_select(e);break;case 27:return this.results_showing&&this.results_hide(),!0;case 9:case 38:case 40:case 16:case 91:case 17:break;default:return this.results_search()}},e.prototype.container_width=function(){return null!=this.options.width?this.options.width:""+this.form_field.offsetWidth+"px"},e.prototype.include_option_in_results=function(e){return this.is_multiple&&!this.display_selected_options&&e.selected?!1:!this.display_disabled_options&&e.disabled?!1:e.empty?!1:!0},e.browser_is_supported=function(){return"Microsoft Internet Explorer"===window.navigator.appName?document.documentMode>=8:/iP(od|hone)/i.test(window.navigator.userAgent)?!1:/Android/i.test(window.navigator.userAgent)&&/Mobile/i.test(window.navigator.userAgent)?!1:!0},e.default_multiple_text="Select Some Options",e.default_single_text="Select an Option",e.default_no_result_text="No results match",e}(),e=jQuery,e.fn.extend({chosen:function(r){return t.browser_is_supported()?this.each(function(){var t,i;t=e(this),i=t.data("chosen"),"destroy"===r&&i?i.destroy():i||t.data("chosen",new n(this,r))}):this}}),n=function(t){function n(){return i=n.__super__.constructor.apply(this,arguments)}return o(n,t),n.prototype.setup=function(){return this.form_field_jq=e(this.form_field),this.current_selectedIndex=this.form_field.selectedIndex,this.is_rtl=this.form_field_jq.hasClass("chosen-rtl")},n.prototype.set_up_html=function(){var t,n;return t=["chosen-container"],t.push("chosen-container-"+(this.is_multiple?"multi":"single")),this.inherit_select_classes&&this.form_field.className&&t.push(this.form_field.className),this.is_rtl&&t.push("chosen-rtl"),n={"class":t.join(" "),style:"width: "+this.container_width()+";",title:this.form_field.title},this.form_field.id.length&&(n.id=this.form_field.id.replace(/[^\w]/g,"_")+"_chosen"),this.container=e("<div />",n),this.is_multiple?this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>'):this.container.html('<a class="chosen-single chosen-default" tabindex="-1"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>'),this.form_field_jq.hide().after(this.container),this.dropdown=this.container.find("div.chosen-drop").first(),this.search_field=this.container.find("input").first(),this.search_results=this.container.find("ul.chosen-results").first(),this.search_field_scale(),this.search_no_results=this.container.find("li.no-results").first(),this.is_multiple?(this.search_choices=this.container.find("ul.chosen-choices").first(),this.search_container=this.container.find("li.search-field").first()):(this.search_container=this.container.find("div.chosen-search").first(),this.selected_item=this.container.find(".chosen-single").first()),this.results_build(),this.set_tab_index(),this.set_label_behavior(),this.form_field_jq.trigger("chosen:ready",{chosen:this})},n.prototype.register_observers=function(){var e=this;return this.container.bind("mousedown.chosen",function(t){e.container_mousedown(t)}),this.container.bind("mouseup.chosen",function(t){e.container_mouseup(t)}),this.container.bind("mouseenter.chosen",function(t){e.mouse_enter(t)}),this.container.bind("mouseleave.chosen",function(t){e.mouse_leave(t)}),this.search_results.bind("mouseup.chosen",function(t){e.search_results_mouseup(t)}),this.search_results.bind("mouseover.chosen",function(t){e.search_results_mouseover(t)}),this.search_results.bind("mouseout.chosen",function(t){e.search_results_mouseout(t)}),this.search_results.bind("mousewheel.chosen DOMMouseScroll.chosen",function(t){e.search_results_mousewheel(t)}),this.form_field_jq.bind("chosen:updated.chosen",function(t){e.results_update_field(t)}),this.form_field_jq.bind("chosen:activate.chosen",function(t){e.activate_field(t)}),this.form_field_jq.bind("chosen:open.chosen",function(t){e.container_mousedown(t)}),this.search_field.bind("blur.chosen",function(t){e.input_blur(t)}),this.search_field.bind("keyup.chosen",function(t){e.keyup_checker(t)}),this.search_field.bind("keydown.chosen",function(t){e.keydown_checker(t)}),this.search_field.bind("focus.chosen",function(t){e.input_focus(t)}),this.is_multiple?this.search_choices.bind("click.chosen",function(t){e.choices_click(t)}):this.container.bind("click.chosen",function(e){e.preventDefault()})},n.prototype.destroy=function(){return e(document).unbind("click.chosen",this.click_test_action),this.search_field[0].tabIndex&&(this.form_field_jq[0].tabIndex=this.search_field[0].tabIndex),this.container.remove(),this.form_field_jq.removeData("chosen"),this.form_field_jq.show()},n.prototype.search_field_disabled=function(){return this.is_disabled=this.form_field_jq[0].disabled,this.is_disabled?(this.container.addClass("chosen-disabled"),this.search_field[0].disabled=!0,this.is_multiple||this.selected_item.unbind("focus.chosen",this.activate_action),this.close_field()):(this.container.removeClass("chosen-disabled"),this.search_field[0].disabled=!1,this.is_multiple?void 0:this.selected_item.bind("focus.chosen",this.activate_action))},n.prototype.container_mousedown=function(t){return this.is_disabled||(t&&"mousedown"===t.type&&!this.results_showing&&t.preventDefault(),null!=t&&e(t.target).hasClass("search-choice-close"))?void 0:(this.active_field?this.is_multiple||!t||e(t.target)[0]!==this.selected_item[0]&&!e(t.target).parents("a.chosen-single").length||(t.preventDefault(),this.results_toggle()):(this.is_multiple&&this.search_field.val(""),e(document).bind("click.chosen",this.click_test_action),this.results_show()),this.activate_field())},n.prototype.container_mouseup=function(e){return"ABBR"!==e.target.nodeName||this.is_disabled?void 0:this.results_reset(e)},n.prototype.search_results_mousewheel=function(e){var t,n,r;return t=-(null!=(n=e.originalEvent)?n.wheelDelta:void 0)||(null!=(r=e.originialEvent)?r.detail:void 0),null!=t?(e.preventDefault(),"DOMMouseScroll"===e.type&&(t=40*t),this.search_results.scrollTop(t+this.search_results.scrollTop())):void 0},n.prototype.blur_test=function(){return!this.active_field&&this.container.hasClass("chosen-container-active")?this.close_field():void 0},n.prototype.close_field=function(){return e(document).unbind("click.chosen",this.click_test_action),this.active_field=!1,this.results_hide(),this.container.removeClass("chosen-container-active"),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},n.prototype.activate_field=function(){return this.container.addClass("chosen-container-active"),this.active_field=!0,this.search_field.val(this.search_field.val()),this.search_field.focus()},n.prototype.test_active_click=function(t){return this.container.is(e(t.target).closest(".chosen-container"))?this.active_field=!0:this.close_field()},n.prototype.results_build=function(){return this.parsing=!0,this.selected_option_count=null,this.results_data=r.select_to_array(this.form_field),this.is_multiple?this.search_choices.find("li.search-choice").remove():this.is_multiple||(this.single_set_selected_text(),this.disable_search||this.form_field.options.length<=this.disable_search_threshold?(this.search_field[0].readOnly=!0,this.container.addClass("chosen-container-single-nosearch")):(this.search_field[0].readOnly=!1,this.container.removeClass("chosen-container-single-nosearch"))),this.update_results_content(this.results_option_build({first:!0})),this.search_field_disabled(),this.show_search_field_default(),this.search_field_scale(),this.parsing=!1},n.prototype.result_do_highlight=function(e){var t,n,r,i,s;if(e.length){if(this.result_clear_highlight(),this.result_highlight=e,this.result_highlight.addClass("highlighted"),r=parseInt(this.search_results.css("maxHeight"),10),s=this.search_results.scrollTop(),i=r+s,n=this.result_highlight.position().top+this.search_results.scrollTop(),t=n+this.result_highlight.outerHeight(),t>=i)return this.search_results.scrollTop(t-r>0?t-r:0);if(s>n)return this.search_results.scrollTop(n)}},n.prototype.result_clear_highlight=function(){return this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},n.prototype.results_show=function(){return this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.container.addClass("chosen-with-drop"),this.form_field_jq.trigger("chosen:showing_dropdown",{chosen:this}),this.results_showing=!0,this.search_field.focus(),this.search_field.val(this.search_field.val()),this.winnow_results())},n.prototype.update_results_content=function(e){return this.search_results.html(e)},n.prototype.results_hide=function(){return this.results_showing&&(this.result_clear_highlight(),this.container.removeClass("chosen-with-drop"),this.form_field_jq.trigger("chosen:hiding_dropdown",{chosen:this})),this.results_showing=!1},n.prototype.set_tab_index=function(){var e;return this.form_field.tabIndex?(e=this.form_field.tabIndex,this.form_field.tabIndex=-1,this.search_field[0].tabIndex=e):void 0},n.prototype.set_label_behavior=function(){var t=this;return this.form_field_label=this.form_field_jq.parents("label"),!this.form_field_label.length&&this.form_field.id.length&&(this.form_field_label=e("label[for='"+this.form_field.id+"']")),this.form_field_label.length>0?this.form_field_label.bind("click.chosen",function(e){return t.is_multiple?t.container_mousedown(e):t.activate_field()}):void 0},n.prototype.show_search_field_default=function(){return this.is_multiple&&this.choices_count()<1&&!this.active_field?(this.search_field.val(this.default_text),this.search_field.addClass("default")):(this.search_field.val(""),this.search_field.removeClass("default"))},n.prototype.search_results_mouseup=function(t){var n;return n=e(t.target).hasClass("active-result")?e(t.target):e(t.target).parents(".active-result").first(),n.length?(this.result_highlight=n,this.result_select(t),this.search_field.focus()):void 0},n.prototype.search_results_mouseover=function(t){var n;return n=e(t.target).hasClass("active-result")?e(t.target):e(t.target).parents(".active-result").first(),n?this.result_do_highlight(n):void 0},n.prototype.search_results_mouseout=function(t){return e(t.target).hasClass("active-result")?this.result_clear_highlight():void 0},n.prototype.choice_build=function(t){var n,r,i=this;return n=e("<li />",{"class":"search-choice"}).html("<span>"+t.html+"</span>"),t.disabled?n.addClass("search-choice-disabled"):(r=e("<a />",{"class":"search-choice-close","data-option-array-index":t.array_index}),r.bind("click.chosen",function(e){return i.choice_destroy_link_click(e)}),n.append(r)),this.search_container.before(n)},n.prototype.choice_destroy_link_click=function(t){return t.preventDefault(),t.stopPropagation(),this.is_disabled?void 0:this.choice_destroy(e(t.target))},n.prototype.choice_destroy=function(e){return this.result_deselect(e[0].getAttribute("data-option-array-index"))?(this.show_search_field_default(),this.is_multiple&&this.choices_count()>0&&this.search_field.val().length<1&&this.results_hide(),e.parents("li").first().remove(),this.search_field_scale()):void 0},n.prototype.results_reset=function(){return this.form_field.options[0].selected=!0,this.selected_option_count=null,this.single_set_selected_text(),this.show_search_field_default(),this.results_reset_cleanup(),this.form_field_jq.trigger("change"),this.active_field?this.results_hide():void 0},n.prototype.results_reset_cleanup=function(){return this.current_selectedIndex=this.form_field.selectedIndex,this.selected_item.find("abbr").remove()},n.prototype.result_select=function(e){var t,n,r;return this.result_highlight?(t=this.result_highlight,this.result_clear_highlight(),this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.is_multiple?t.removeClass("active-result"):(this.result_single_selected&&(this.result_single_selected.removeClass("result-selected"),r=this.result_single_selected[0].getAttribute("data-option-array-index"),this.results_data[r].selected=!1),this.result_single_selected=t),t.addClass("result-selected"),n=this.results_data[t[0].getAttribute("data-option-array-index")],n.selected=!0,this.form_field.options[n.options_index].selected=!0,this.selected_option_count=null,this.is_multiple?this.choice_build(n):this.single_set_selected_text(n.text),(e.metaKey||e.ctrlKey)&&this.is_multiple||this.results_hide(),this.search_field.val(""),(this.is_multiple||this.form_field.selectedIndex!==this.current_selectedIndex)&&this.form_field_jq.trigger("change",{selected:this.form_field.options[n.options_index].value}),this.current_selectedIndex=this.form_field.selectedIndex,this.search_field_scale())):void 0},n.prototype.single_set_selected_text=function(e){return null==e&&(e=this.default_text),e===this.default_text?this.selected_item.addClass("chosen-default"):(this.single_deselect_control_build(),this.selected_item.removeClass("chosen-default")),this.selected_item.find("span").text(e)},n.prototype.result_deselect=function(e){var t;return t=this.results_data[e],this.form_field.options[t.options_index].disabled?!1:(t.selected=!1,this.form_field.options[t.options_index].selected=!1,this.selected_option_count=null,this.result_clear_highlight(),this.results_showing&&this.winnow_results(),this.form_field_jq.trigger("change",{deselected:this.form_field.options[t.options_index].value}),this.search_field_scale(),!0)},n.prototype.single_deselect_control_build=function(){return this.allow_single_deselect?(this.selected_item.find("abbr").length||this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>'),this.selected_item.addClass("chosen-single-with-deselect")):void 0},n.prototype.get_search_text=function(){return this.search_field.val()===this.default_text?"":e("<div/>").text(e.trim(this.search_field.val())).html()},n.prototype.winnow_results_set_highlight=function(){var e,t;return t=this.is_multiple?[]:this.search_results.find(".result-selected.active-result"),e=t.length?t.first():this.search_results.find(".active-result").first(),null!=e?this.result_do_highlight(e):void 0},n.prototype.no_results=function(t){var n;return n=e('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>'),n.find("span").first().html(t),this.search_results.append(n)},n.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()},n.prototype.keydown_arrow=function(){var e;return this.results_showing&&this.result_highlight?(e=this.result_highlight.nextAll("li.active-result").first())?this.result_do_highlight(e):void 0:this.results_show()},n.prototype.keyup_arrow=function(){var e;return this.results_showing||this.is_multiple?this.result_highlight?(e=this.result_highlight.prevAll("li.active-result"),e.length?this.result_do_highlight(e.first()):(this.choices_count()>0&&this.results_hide(),this.result_clear_highlight())):void 0:this.results_show()},n.prototype.keydown_backstroke=function(){var e;return this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.find("a").first()),this.clear_backstroke()):(e=this.search_container.siblings("li.search-choice").last(),e.length&&!e.hasClass("search-choice-disabled")?(this.pending_backstroke=e,this.single_backstroke_delete?this.keydown_backstroke():this.pending_backstroke.addClass("search-choice-focus")):void 0)},n.prototype.clear_backstroke=function(){return this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},n.prototype.keydown_checker=function(e){var t,n;switch(t=null!=(n=e.which)?n:e.keyCode,this.search_field_scale(),8!==t&&this.pending_backstroke&&this.clear_backstroke(),t){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(e),this.mouse_on_container=!1;break;case 13:e.preventDefault();break;case 38:e.preventDefault(),this.keyup_arrow();break;case 40:e.preventDefault(),this.keydown_arrow()}},n.prototype.search_field_scale=function(){var t,n,r,i,s,o,u,f,l;if(this.is_multiple){for(r=0,u=0,s="position:absolute; left: -1000px; top: -1000px; display:none;",o=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"],f=0,l=o.length;l>f;f++)i=o[f],s+=i+":"+this.search_field.css(i)+";";return t=e("<div />",{style:s}),t.text(this.search_field.val()),e("body").append(t),u=t.width()+25,t.remove(),n=this.container.outerWidth(),u>n-10&&(u=n-10),this.search_field.css({width:u+"px"})}},n}(t)}.call(this);