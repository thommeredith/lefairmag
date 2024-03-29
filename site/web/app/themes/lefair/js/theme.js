/*
 Name: Porto Theme Javascript
 Writtern By: SW-THEMES
 Javascript Version: 1.1.1
 */

// Theme
window.theme = {};

// Configuration
(function(theme, $) {

    theme = theme || {};

    var initialized = false;

    $.extend(theme, {

        rtl: js_porto_vars.rtl == '1' ? true : false,

        ajax_url: js_porto_vars.ajax_url,
        request_error: js_porto_vars.request_error,

        change_logo: js_porto_vars.change_logo == '1' ? true : false,

        show_sticky_header: js_porto_vars.show_sticky_header == '1' ? true : false,
        show_sticky_header_tablet: js_porto_vars.show_sticky_header_tablet == '1' ? true : false,
        show_sticky_header_mobile: js_porto_vars.show_sticky_header_mobile == '1' ? true : false,

        post_zoom: js_porto_vars.post_zoom == '1' ? true : false,
        portfolio_zoom: js_porto_vars.portfolio_zoom == '1' ? true : false,
        member_zoom: js_porto_vars.member_zoom == '1' ? true : false,

        category_ajax: js_porto_vars.category_ajax == '1' ? true : false,
        prdctfltr_ajax: js_porto_vars.prdctfltr_ajax == '1' ? true : false,
        show_minicart: js_porto_vars.show_minicart == '1' ? true : false,

        container_width: parseInt(js_porto_vars.container_width),
        grid_gutter_width: parseInt(js_porto_vars.grid_gutter_width),
        screen_lg: parseInt(js_porto_vars.screen_lg),
        slider_loop: js_porto_vars.slider_loop == '1' ? true : false,
        slider_autoplay: js_porto_vars.slider_autoplay == '1' ? true : false,
        slider_speed: js_porto_vars.slider_speed ? js_porto_vars.slider_speed : 5000,
        slider_nav: js_porto_vars.slider_nav == '1' ? true : false,
        slider_nav_hover: js_porto_vars.slider_nav_hover == '1' ? true : false,
        slider_margin: js_porto_vars.slider_margin == '1' ? 40 : 0,
        slider_dots: js_porto_vars.slider_dots == '1' ? true : false,
        slider_animatein: js_porto_vars.slider_animatein ? js_porto_vars.slider_animatein : '',
        slider_animateout: js_porto_vars.slider_animateout ? js_porto_vars.slider_animateout : '',
        product_thumbs_count: js_porto_vars.product_thumbs_count ? js_porto_vars.product_thumbs_count : 4,
        product_zoom: js_porto_vars.product_zoom == '1' ? true : false,
        product_zoom_mobile: js_porto_vars.product_zoom_mobile == '1' ? true : false,
        product_image_popup: js_porto_vars.product_image_popup == '1' ? 'fadeOut' : false,

        hoverIntentConfig: {
            sensitivity: 2,
            interval: 0,
            timeout: 0
        },

        owlConfig: {
            rtl: js_porto_vars.rtl == '1' ? true : false,
            loop : js_porto_vars.slider_loop == '1' ? true : false,
            autoplay : js_porto_vars.slider_autoplay == '1' ? true : false,
            autoplayTimeout: js_porto_vars.slider_speed ? js_porto_vars.slider_speed : 5000,
            autoplayHoverPause : true,
            items : 1,
            autoHeight : true,
            lazyLoad: true,
            nav: js_porto_vars.slider_nav == '1' ? true : false,
            navText: ["", ""],
            dots: js_porto_vars.slider_dots == '1' ? true : false,
            rewind: true,
            stagePadding: js_porto_vars.slider_margin == '1' ? 40 : 0,
            animateOut: js_porto_vars.slider_animateout ? js_porto_vars.slider_animateout : '',
            animateIn: js_porto_vars.slider_animatein ? js_porto_vars.slider_animatein : ''
        },

        infiniteConfig: {
            navSelector  : "div.pagination",
            nextSelector : "div.pagination a.next",
            loading      : {
                finishedMsg: "",
                msgText: "<em class='infinite-loading'></em>",
                img: "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
            }
        },

        getScrollbarWidth: function() {
            if (initialized)
                return this.scrollbar_width;

            initialized = true;
            this.scrollbar_width = window.innerWidth-$(window).width();
            return this.scrollbar_width;
        },

        isTablet: function() {
            var win_width = $(window).width();
            if (win_width < 992 - theme.scrollbar_width)
                return true;
            return false;
        },

        isMobile: function() {
            var win_width = $(window).width();
            if (win_width <= 480 - theme.scrollbar_width)
                return true;
            return false;
        },

        refreshVCContent: function($elements) {
            var panel = $elements,
                $pie_charts = panel.find( '.vc_pie_chart:not(.vc_ready)' ),
                $round_charts = panel.find( '.vc_round-chart' ),
                $line_charts = panel.find( '.vc_line-chart' ),
                $carousel = panel.find( '[data-ride="vc_carousel"]' ),
                $ui_panel, $google_maps;
            if ( 'function' !== typeof(window[ 'vc_carouselBehaviour' ]) ) {
                vc_carouselBehaviour();
            }
            if ( 'function' !== typeof(window[ 'vc_plugin_flexslider' ]) ) {
                vc_plugin_flexslider( panel );
            }
            if ( panel.find( '.vc_masonry_media_grid, .vc_masonry_grid' ).length ) {
                panel.find( '.vc_masonry_media_grid, .vc_masonry_grid' ).each( function () {
                    var grid = $( this ).data( 'vcGrid' );
                    grid && grid.gridBuilder && grid.gridBuilder.setMasonry && grid.gridBuilder.setMasonry();
                } );
            }
            $pie_charts.length && $.fn.vcChat && $pie_charts.vcChat();
            $round_charts.length && $.fn.vcRoundChart && $round_charts.vcRoundChart( { reload: false } );
            $line_charts.length && $.fn.vcLineChart && $line_charts.vcLineChart( { reload: false } );
            $carousel.length && $.fn.carousel && $carousel.carousel( 'resizeAction' );
            $ui_panel = panel.find( '.isotope, .wpb_image_grid_ul' ); // why var name '$ui_panel'?
            $google_maps = panel.find( '.wpb_gmaps_widget' );
            if ( 0 < $ui_panel.length ) {
                $ui_panel.isotope( "layout" );
            }
            if ( $google_maps.length && ! $google_maps.is( '.map_ready' ) ) {
                var $frame = $google_maps.find( 'iframe' );
                $frame.attr( 'src', $frame.attr( 'src' ) );
                $google_maps.addClass( 'map_ready' );
            }
            if ( panel.parents( '.isotope' ).length ) {
                panel.parents( '.isotope' ).each( function () {
                    $( this ).isotope( "layout" );
                } );
            }
        },

        adminBarHeight: function() {
            var $admin_bar = $('#wpadminbar');
            var adminbar_height = 0;
            if ($admin_bar.length) {
                adminbar_height = $('#wpadminbar').css('position') == 'fixed' ? $('#wpadminbar').height() : 0;
            }

            return adminbar_height;
        }
    });

}).apply(this, [window.theme, jQuery]);


// Theme Functions
function portoCalcSliderMargin($parent, padding) {
    $parent.css({
        'margin-left': '-' + padding,
        'margin-right': '-' + padding
    });
}

function portoCalcSliderButtonsPosition($parent, padding) {
    var $buttons = $parent.find('.show-nav-title .owl-nav');
    if ($buttons.length) {
        if (window.theme.rtl) {
            $buttons.css('left', padding);
        } else {
            $buttons.css('right', padding);
        }
    }
}

function portoCalcSliderTitleLine($parent) {
    var c_w = $parent.width();
    var $title = $parent.parent().find('.slider-title');
    if (!$title.length) return;

    var $l = $title.find('.line');
    var $t = $title.find('.inline-title');

    if (!$t.length || !$l.length) return;

    var title_w = $title.width();
    var t_w = $t.width();
    if (title_w > t_w + 200) {
        if (window.theme.rtl) {
            $l.css({
                display: 'block',
                right: t_w + 20,
                width: title_w - t_w - 75
            });
        } else {
            $l.css({
                display: 'block',
                left: t_w + 20,
                width: title_w - t_w - 75
            });
        }
    } else {
        $l.css({
            display: 'none'
        });
    }
}


// Mobile Check
(function(theme, $) {

    theme = theme || {};

    var apple_phone         = /iPhone/i,
        apple_ipod          = /iPod/i,
        apple_tablet        = /iPad/i,
        android_phone       = /(?=.*\bAndroid\b)(?=.*\bMobile\b)/i, // Match 'Android' AND 'Mobile'
        android_tablet      = /Android/i,
        windows_phone       = /IEMobile/i,
        windows_tablet      = /(?=.*\bWindows\b)(?=.*\bARM\b)/i, // Match 'Windows' AND 'ARM'
        other_blackberry    = /BlackBerry/i,
        other_blackberry_10 = /BB10/i,
        other_opera         = /Opera Mini/i,
        other_firefox       = /(?=.*\bFirefox\b)(?=.*\bMobile\b)/i, // Match 'Firefox' AND 'Mobile'
        seven_inch = new RegExp(
            '(?:' +         // Non-capturing group

                'Nexus 7' +     // Nexus 7

                '|' +           // OR

                'BNTV250' +     // B&N Nook Tablet 7 inch

                '|' +           // OR

                'Kindle Fire' + // Kindle Fire

                '|' +           // OR

                'Silk' +        // Kindle Fire, Silk Accelerated

                '|' +           // OR

                'GT-P1000' +    // Galaxy Tab 7 inch

                ')',            // End non-capturing group

            'i');           // Case-insensitive matching

    var match = function(regex, userAgent) {
        return regex.test(userAgent);
    };

    var IsMobileClass = function(userAgent) {
        var ua = userAgent || navigator.userAgent;

        this.apple = {
            phone:  match(apple_phone, ua),
            ipod:   match(apple_ipod, ua),
            tablet: match(apple_tablet, ua),
            device: match(apple_phone, ua) || match(apple_ipod, ua) || match(apple_tablet, ua)
        };
        this.android = {
            phone:  match(android_phone, ua),
            tablet: !match(android_phone, ua) && match(android_tablet, ua),
            device: match(android_phone, ua) || match(android_tablet, ua)
        };
        this.windows = {
            phone:  match(windows_phone, ua),
            tablet: match(windows_tablet, ua),
            device: match(windows_phone, ua) || match(windows_tablet, ua)
        };
        this.other = {
            blackberry:   match(other_blackberry, ua),
            blackberry10: match(other_blackberry_10, ua),
            opera:        match(other_opera, ua),
            firefox:      match(other_firefox, ua),
            device:       match(other_blackberry, ua) || match(other_blackberry_10, ua) || match(other_opera, ua) || match(other_firefox, ua)
        };
        this.seven_inch = match(seven_inch, ua);
        this.any = this.apple.device || this.android.device || this.windows.device || this.other.device || this.seven_inch;
        // excludes 'other' devices and ipods, targeting touchscreen phones
        this.phone = this.apple.phone || this.android.phone || this.windows.phone;
        // excludes 7 inch devices, classifying as phone or tablet is left to the user
        this.tablet = this.apple.tablet || this.android.tablet || this.windows.tablet;

        if (typeof window === 'undefined') {
            return this;
        }
    };

    var instantiate = function() {
        var IM = new IsMobileClass();
        IM.Class = IsMobileClass;
        return IM;
    };

    $.extend(theme, {

        MobileCheck: instantiate()

    });

}).apply(this, [window.theme, jQuery]);


// Loading Overlay
(function(theme, $) {

    'use strict';

    theme = theme || {};

    var loadingOverlayTemplate = [
        '<div class="loading-overlay">',
        '<div class="loader"></div>',
        '</div>'
    ].join('');

    var LoadingOverlay = function( $wrapper, options ) {
        return this.initialize( $wrapper, options );
    };

    LoadingOverlay.prototype = {

        options: {
            css: {}
        },

        initialize: function( $wrapper, options ) {
            this.$wrapper = $wrapper;

            this
                .setVars()
                .setOptions( options )
                .build()
                .events();

            this.$wrapper.data( 'loadingOverlay', this );
        },

        setVars: function() {
            this.$overlay = this.$wrapper.find('.loading-overlay');

            return this;
        },

        setOptions: function( options ) {
            if ( !this.$overlay.get(0) ) {
                this.matchProperties();
            }
            this.options     = $.extend( true, {}, this.options, options );
            this.loaderClass = this.getLoaderClass( this.options.css.backgroundColor );

            return this;
        },

        build: function() {
            if ( !this.$overlay.closest(document.documentElement).get(0) ) {
                if ( !this.$cachedOverlay ) {
                    this.$overlay = $( loadingOverlayTemplate ).clone();

                    if ( this.options.css ) {
                        this.$overlay.css( this.options.css );
                        this.$overlay.find( '.loader' ).addClass( this.loaderClass );
                    }
                } else {
                    this.$overlay = this.$cachedOverlay.clone();
                }

                this.$wrapper.append( this.$overlay );
            }

            if ( !this.$cachedOverlay ) {
                this.$cachedOverlay = this.$overlay.clone();
            }

            return this;
        },

        events: function() {
            var _self = this;

            if ( this.options.startShowing ) {
                _self.show();
            }

            if ( this.$wrapper.is('body') || this.options.hideOnWindowLoad ) {
                $( window ).on( 'load error', function() {
                    _self.hide();
                });
            }

            if ( this.options.listenOn ) {
                $( this.options.listenOn )
                    .on( 'loading-overlay:show beforeSend.ic', function( e ) {
                        e.stopPropagation();
                        _self.show();
                    })
                    .on( 'loading-overlay:hide complete.ic', function( e ) {
                        e.stopPropagation();
                        _self.hide();
                    });
            }

            this.$wrapper
                .on( 'loading-overlay:show beforeSend.ic', function( e ) {
                    e.stopPropagation();
                    _self.show();
                })
                .on( 'loading-overlay:hide complete.ic', function( e ) {
                    e.stopPropagation();
                    _self.hide();
                });

            return this;
        },

        show: function() {
            this.build();

            this.position = this.$wrapper.css( 'position' ).toLowerCase();
            if ( this.position != 'relative' || this.position != 'absolute' || this.position != 'fixed' ) {
                this.$wrapper.css({
                    position: 'relative'
                });
            }
            this.$wrapper.addClass( 'loading-overlay-showing' );
        },

        hide: function() {
            var _self = this;

            this.$wrapper.removeClass( 'loading-overlay-showing' );
            setTimeout(function() {
                if ( this.position != 'relative' || this.position != 'absolute' || this.position != 'fixed' ) {
                    _self.$wrapper.css({ position: '' });
                }
            }, 500);
        },

        matchProperties: function() {
            var i,
                l,
                properties;

            properties = [
                'backgroundColor',
                'borderRadius'
            ];

            l = properties.length;

            for( i = 0; i < l; i++ ) {
                var obj = {};
                obj[ properties[ i ] ] = this.$wrapper.css( properties[ i ] );

                $.extend( this.options.css, obj );
            }
        },

        getLoaderClass: function( backgroundColor ) {
            if ( !backgroundColor || backgroundColor === 'transparent' || backgroundColor === 'inherit' ) {
                return 'black';
            }

            var hexColor,
                r,
                g,
                b,
                yiq;

            var colorToHex = function( color ){
                var hex,
                    rgb;

                if( color.indexOf('#') >- 1 ){
                    hex = color.replace('#', '');
                } else {
                    rgb = color.match(/\d+/g);
                    hex = ('0' + parseInt(rgb[0], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[1], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[2], 10).toString(16)).slice(-2);
                }

                if ( hex.length === 3 ) {
                    hex = hex + hex;
                }

                return hex;
            };

            hexColor = colorToHex( backgroundColor );

            r = parseInt( hexColor.substr( 0, 2), 16 );
            g = parseInt( hexColor.substr( 2, 2), 16 );
            b = parseInt( hexColor.substr( 4, 2), 16 );
            yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;

            return ( yiq >= 128 ) ? 'black' : 'white';
        }

    };

    // expose to scope
    $.extend(theme, {
        LoadingOverlay: LoadingOverlay
    });

    // expose as a jquery plugin
    $.fn.loadingOverlay = function( opts ) {
        return this.each(function() {
            var $this = $( this );

            var loadingOverlay = $this.data( 'loadingOverlay' );
            if ( loadingOverlay ) {
                return loadingOverlay;
            } else {
                var options = opts || $this.data( 'loading-overlay-options' ) || {};
                return new LoadingOverlay( $this, options );
            }
        });
    }

    // auto init
    $(function() {
        $('[data-loading-overlay]').loadingOverlay();
    });

}).apply(this, [window.theme, jQuery]);


// Mega Menu
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        MegaMenu: {

            defaults: {
                menu: $('.mega-menu')
            },

            initialize: function($menu) {
                this.$menu = ($menu || this.defaults.menu);

                this.build()
                    .events();

                return this;
            },

            popupWidth: function() {
                var winWidth = $(window).width() + theme.getScrollbarWidth();
                var popupWidth = $(window).width() - theme.grid_gutter_width;
                if (winWidth >= theme.container_width + theme.grid_gutter_width - 1)
                    popupWidth = theme.container_width - theme.grid_gutter_width;
                else if (winWidth >= 992)
                    popupWidth = 940;
                else if (winWidth >= 768)
                    popupWidth = 720;
                return popupWidth;
            },

            build: function() {
                var self = this;

                self.$menu.each( function() {
                    var $menu = $(this);
                    var $menu_container = $menu.closest('.container');
                    var container_width = self.popupWidth();
                    var offset = 0;

                    if ($menu_container.length) {
                        if (theme.rtl) {
                            offset = ($menu_container.offset().left + $menu_container.width()) - ($menu.offset().left + $menu.width()) + parseInt($menu_container.css('padding-right'));
                        } else {
                            offset = $menu.offset().left - $menu_container.offset().left - parseInt($menu_container.css('padding-left'));
                        }
                        offset = (offset == 1) ? 0 : offset;
                    }

                    var $menu_items = $menu.find('> li');

                    $menu_items.each( function() {
                        var $menu_item = $(this);
                        var $popup = $menu_item.find('> .popup');
                        if ($popup.length > 0) {
                            $popup.css('display', 'block');
                            if ($menu_item.hasClass('wide')) {
                                $popup.css('left', 0);
                                var padding = parseInt($popup.css('padding-left')) + parseInt($popup.css('padding-right')) +
                                    parseInt($popup.find('> .inner').css('padding-left')) + parseInt($popup.find('> .inner').css('padding-right'));

                                var row_number = 4;

                                if ($menu_item.hasClass('col-2')) row_number = 2;
                                if ($menu_item.hasClass('col-3')) row_number = 3;
                                if ($menu_item.hasClass('col-4')) row_number = 4;
                                if ($menu_item.hasClass('col-5')) row_number = 5;
                                if ($menu_item.hasClass('col-6')) row_number = 6;

                                if ($(window).width() < 992 - theme.scrollbarWidth)
                                    row_number = 1;

                                var col_length = 0;
                                $popup.find('> .inner > ul > li').each(function() {
                                    var cols = parseInt($(this).attr('data-cols'));
                                    if (cols < 1)
                                        cols = 1;

                                    if (cols > row_number)
                                        cols = row_number;

                                    col_length += cols;
                                });

                                if (col_length > row_number) col_length = row_number;

                                var popup_max_width = $popup.find('.inner').css('max-width');
                                var col_width = container_width / row_number;
                                if ('none' !== popup_max_width && popup_max_width < container_width) {
                                    col_width = parseInt(popup_max_width) / row_number;
                                }

                                $popup.find('> .inner > ul > li').each(function() {
                                    var cols = parseFloat($(this).attr('data-cols'));
                                    if (cols < 1)
                                        cols = 1;

                                    if (cols > row_number)
                                        cols = row_number;

                                    if ($menu_item.hasClass('pos-center') || $menu_item.hasClass('pos-left') || $menu_item.hasClass('pos-right'))
                                        $(this).css('width', (100 / col_length * cols) + '%');
                                    else
                                        $(this).css('width', (100 / row_number * cols) + '%');
                                });

                                if ($menu_item.hasClass('pos-center')) { // position center
                                    $popup.find('> .inner > ul').width(col_width * col_length - padding);
                                    var left_position = $popup.offset().left - ($(window).width() - col_width * col_length) / 2;
                                    $popup.css({
                                        'left': -left_position
                                    });
                                } else if ($menu_item.hasClass('pos-left')) { // position left
                                    $popup.find('> .inner > ul').width(col_width * col_length - padding);
                                    $popup.css({
                                        'left': 0
                                    });
                                } else if ($menu_item.hasClass('pos-right')) { // position right
                                    $popup.find('> .inner > ul').width(col_width * col_length - padding);
                                    $popup.css({
                                        'left': 'auto',
                                        'right': 0
                                    });
                                } else { // position justify
                                    $popup.find('> .inner > ul').width(container_width - padding);
                                    if (theme.rtl) {
                                        $popup.css({
                                            'right': 0,
                                            'left': 'auto'
                                        });
                                        var right_position = ($popup.offset().left + $popup.width()) - ($menu.offset().left + $menu.width()) - offset;
                                        $popup.css({
                                            'right': right_position,
                                            'left': 'auto'
                                        });
                                    } else {
                                        $popup.css({
                                            'left': 0,
                                            'right': 'auto'
                                        });
                                        var left_position = $popup.offset().left - $menu.offset().left + offset;
                                        $popup.css({
                                            'left': -left_position,
                                            'right': 'auto'
                                        });
                                    }
                                }
                            }
                            if (!($menu.hasClass('effect-down')))
                                $popup.css('display', 'none');

                            $menu_item.hoverIntent(
                                $.extend({}, theme.hoverIntentConfig, {
                                    over: function(){
                                        if (!($menu.hasClass('effect-down')))
                                            $menu_items.find('.popup').hide();
                                        $popup.show();
                                    },
                                    out: function(){
                                        if (!($menu.hasClass('effect-down')))
                                            $popup.hide();
                                    }
                                })
                            );
                        }
                    });
                });

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.build();
                });

                setTimeout(function() {
                    self.build();
                }, 400);

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Sidebar Menu
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        SidebarMenu: {

            defaults: {
                menu: $('.sidebar-menu'),
                toggle: $('.widget_sidebar_menu .widget-title .toggle'),
                menu_toggle: $('#main-toggle-menu .menu-title')
            },

            rtl: theme.rtl,

            initialize: function($menu, $toggle, $menu_toggle) {
                this.$menu = ($menu || this.defaults.menu);
                this.$toggle = ($toggle || this.defaults.toggle);
                this.$menu_toggle = ($menu_toggle || this.defaults.menu_toggle);

                this.build()
                    .events();

                return this;
            },

            isRightSidebar: function($menu) {
                var flag = false;
                if (this.rtl) {
                    flag = !($('#main').hasClass('column2-right-sidebar') || $('#main').hasClass('column2-wide-right-sidebar'));
                } else {
                    flag = $('#main').hasClass('column2-right-sidebar') || $('#main').hasClass('column2-wide-right-sidebar');
                }

                if ($menu.closest('#main-toggle-menu').length) {
                    if (this.rtl) {
                        flag = true;
                    } else {
                        flag = false;
                    }
                }

                if ($header_wrapper = $menu.closest('.header-wrapper')) {
                    if ($header_wrapper.length && $header_wrapper.hasClass('header-side-nav')) {
                        if (this.rtl) {
                            flag = true;
                        } else {
                            flag = false;
                        }
                    }
                }

                return flag;
            },

            popupWidth: function() {
                var winWidth = $(window).width() + theme.getScrollbarWidth();
                var popupWidth = $(window).width() - theme.grid_gutter_width;
                if (winWidth >= theme.container_width + theme.grid_gutter_width - 1)
                    popupWidth = theme.container_width - theme.grid_gutter_width;
                else if (winWidth >= 992)
                    popupWidth = 940;
                else if (winWidth >= 768)
                    popupWidth = 720;
                return popupWidth;
            },

            build: function() {
                var self = this;

                self.$menu.each( function() {
                    var $menu = $(this);
                    var $menu_container = $menu.closest('.container');
                    var container_width;
                    if ($(window).width() < 992 - theme.getScrollbarWidth())
                        container_width = self.popupWidth();
                    else
                        container_width = self.popupWidth() - $menu.width() - 45;

                    var is_right_sidebar = self.isRightSidebar($menu);

                    var $menu_items = $menu.find('> li');

                    $menu_items.each( function() {
                        var $menu_item = $(this);
                        var $popup = $menu_item.find('> .popup');
                        if ($popup.length > 0) {
                            $popup.css('display', 'block');
                            if ($menu_item.hasClass('wide')) {
                                $popup.css('left', 0);
                                var padding = parseInt($popup.css('padding-left')) + parseInt($popup.css('padding-right')) +
                                    parseInt($popup.find('> .inner').css('padding-left')) + parseInt($popup.find('> .inner').css('padding-right'));

                                var row_number = 4;

                                if ($menu_item.hasClass('col-2')) row_number = 2;
                                if ($menu_item.hasClass('col-3')) row_number = 3;
                                if ($menu_item.hasClass('col-4')) row_number = 4;
                                if ($menu_item.hasClass('col-5')) row_number = 5;
                                if ($menu_item.hasClass('col-6')) row_number = 6;

                                if ($(window).width() < 992 - theme.getScrollbarWidth())
                                    row_number = 1;

                                var col_length = 0;
                                $popup.find('> .inner > ul > li').each(function() {
                                    var cols = parseInt($(this).attr('data-cols'));
                                    if (cols < 1)
                                        cols = 1;

                                    if (cols > row_number)
                                        cols = row_number;

                                    col_length += cols;
                                });

                                if (col_length > row_number) col_length = row_number;

                                var popup_max_width = $popup.find('.inner').css('max-width');
                                var col_width = container_width / row_number;
                                if ('none' !== popup_max_width && popup_max_width < container_width) {
                                    col_width = parseInt(popup_max_width) / row_number;
                                }

                                $popup.find('> .inner > ul > li').each(function() {
                                    var cols = parseFloat($(this).attr('data-cols'));
                                    if (cols < 1)
                                        cols = 1;

                                    if (cols > row_number)
                                        cols = row_number;

                                    if ($menu_item.hasClass('pos-center') || $menu_item.hasClass('pos-left') || $menu_item.hasClass('pos-right'))
                                        $(this).css('width', (100 / col_length * cols) + '%');
                                    else
                                        $(this).css('width', (100 / row_number * cols) + '%');
                                });

                                $popup.find('> .inner > ul').width(col_width * col_length + 1);
                                if (is_right_sidebar) {
                                    $popup.css({
                                        'left': 'auto',
                                        'right': $(this).width()
                                    });
                                } else {
                                    $popup.css({
                                        'left': $(this).width(),
                                        'right': 'auto'
                                    });
                                }
                            }
                            if (!($menu.hasClass('subeffect-down')))
                                $popup.css('display', 'none');

                            $menu_item.hoverIntent(
                                $.extend({}, theme.hoverIntentConfig, {
                                    over: function(){
                                        if (!($menu.hasClass('subeffect-down')))
                                            $menu_items.find('.popup').hide();
                                        $popup.show();
                                        $popup.parent().addClass('open');
                                    },
                                    out: function(){
                                        if (!($menu.hasClass('subeffect-down')))
                                            $popup.hide();
                                        $popup.parent().removeClass('open');
                                    }
                                })
                            );
                        }
                    });
                });

                return self;
            },

            events: function() {
                var self = this;

                self.$toggle.click(function() {
                    var $widget = $(this).parent().parent();
                    var $this = $(this);
                    if ($this.hasClass('closed')) {
                        $this.removeClass('closed');
                        $widget.removeClass('closed');
                        $widget.find('.sidebar-menu-wrap').stop().slideDown(400, function() {
                            self.build();
                        });
                    } else {
                        $this.addClass('closed');
                        $widget.addClass('closed');
                        $widget.find('.sidebar-menu-wrap').stop().slideUp(400);
                    }
                });

                this.$menu_toggle.click(function() {
                    var $toggle_menu = $(this).parent();
                    var $this = $(this);
                    if ($this.hasClass('closed')) {
                        $this.removeClass('closed');
                        $toggle_menu.removeClass('closed');
                        $toggle_menu.find('.toggle-menu-wrap').stop().slideDown();

                        self.build();

                    } else {
                        $this.addClass('closed');
                        $toggle_menu.addClass('closed');
                        $toggle_menu.find('.toggle-menu-wrap').stop().slideUp();
                    }
                });

                $(window).on('resize', function() {
                    self.build();
                });

                setTimeout(function() {
                    self.build();
                }, 400);

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Accordion Menu
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        AccordionMenu: {

            defaults: {
                menu: $('.accordion-menu')
            },

            initialize: function($menu) {
                this.$menu = ($menu || this.defaults.menu);

                this.events()
                    .build();

                return this;
            },

            build: function() {
                var self = this;

                self.$menu.find('li.menu-item.active').each(function() {
                    if ($(this).find('> .arrow').length)
                        $(this).find('> .arrow').click();
                });

                return self;
            },

            events: function() {
                var self = this;

                self.$menu.find('.arrow').click(function() {
                    var $parent = $(this).closest('li');
                    $(this).next().stop().slideToggle();
                    if ($parent.hasClass('open')) {
                        $parent.removeClass('open');
                    } else {
                        $parent.addClass('open');
                    }
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);

// Sticky Header
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        StickyHeader: {

            defaults: {
                header: $('#header')
            },

            initialize: function($header) {
                this.$header = ($header || this.defaults.header);
                this.sticky_height = 0;
                this.sticky_offset = 0;
                this.sticky_pos = 0;
                this.change_logo = theme.change_logo;

                if (!theme.show_sticky_header || !this.$header.length)
                    return this;

                var self = this;

                var $header_top = self.$header.find('> .header-top');
                if ($header_top.length) {
                    self.$header_top = $header_top;
                    self.top_height = $header_top.height();
                } else {
                    self.$header_top = false;
                }

                var $menu_wrap = self.$header.find('> .main-menu-wrap');
                if ($menu_wrap.length) {
                    self.$menu_wrap = $menu_wrap;
                    self.menu_height = $menu_wrap.height();
                } else {
                    self.$menu_wrap = false;
                }

                self.$header_main = self.$header.find('.header-main');

                self.is_sticky = false;

                self.reset()
                    .build()
                    .events();

                return self;
            },

            build: function() {
                var self = this;

                if (!self.is_sticky && ($(window).height() + self.header_height + theme.adminBarHeight() + parseInt(self.$header.css('border-top-width')) >= $(document).height())) {
                    return self;
                }

                var scroll_top = $(window).scrollTop();

                if (self.$menu_wrap && !theme.isTablet()) {

                    self.$header_main.stop().css('top', 0);

                    if (self.$header.parent().hasClass('fixed-header'))
                        self.$header.parent().attr('style', '');

                    if (scroll_top > self.sticky_pos) {
                        if (!self.$header.hasClass('sticky-header')) {
                            self.$header.addClass('sticky-header');
                            self.$header.css('padding-bottom', self.menu_height);
                            self.$menu_wrap.stop().css('top', theme.adminBarHeight());

                            if (self.$header.parent().hasClass('fixed-header')) {
                                self.$header_main.hide();
                                self.$header.css('padding-bottom', 0);
                            }

                            if (!self.init_toggle_menu) {
                                self.init_toggle_menu = true;
                                theme.MegaMenu.build();
                                if ($('#main-toggle-menu').length) {
                                    if ($('#main-toggle-menu').hasClass('show-always')) {
                                        $('#main-toggle-menu').data('show-always', true);
                                        $('#main-toggle-menu').removeClass('show-always');
                                    }
                                    $('#main-toggle-menu').addClass('closed');
                                    $('#main-toggle-menu .menu-title').addClass('closed');
                                    $('#main-toggle-menu .toggle-menu-wrap').attr('style', '');
                                }
                            }
                            self.is_sticky = true;
                        }
                    } else {
                        if (self.$header.hasClass('sticky-header')) {
                            self.$header.removeClass('sticky-header');
                            self.$header.css('padding-bottom', 0);
                            self.$menu_wrap.stop().css('top', 0);
                            self.$header_main.show();

                            if (self.init_toggle_menu) {
                                self.init_toggle_menu = false;
                                theme.MegaMenu.build();
                                if ($('#main-toggle-menu').length) {
                                    if ($('#main-toggle-menu').data('show-always')) {
                                        $('#main-toggle-menu').addClass('show-always');
                                        $('#main-toggle-menu').removeClass('closed');
                                        $('#main-toggle-menu .menu-title').removeClass('closed');
                                        $('#main-toggle-menu .toggle-menu-wrap').attr('style', '');
                                    }
                                }
                            }
                            self.is_sticky = false;
                        }
                    }
                } else {
                    self.$header_main.show();
                    if (self.$header.parent().hasClass('fixed-header') && $('#wpadminbar').length && $('#wpadminbar').css('position') == 'absolute') {
                        self.$header.parent().css('top', ($('#wpadminbar').height() - scroll_top) < 0 ? -$('#wpadminbar').height() : -scroll_top);
                    } else if (self.$header.parent().hasClass('fixed-header')) {
                        self.$header.parent().attr('style', '');
                    } else {
                        if (self.$header.parent().hasClass('fixed-header'))
                            self.$header.parent().attr('style', '');
                    }
                    if (self.$header.hasClass('sticky-menu-header') && !theme.isTablet()) {
                        self.$header_main.stop().css('top', 0);
                        if (self.change_logo) self.$header_main.removeClass('change-logo');
                        self.$header_main.removeClass('sticky');
                        self.$header.removeClass('sticky-header');
                        self.is_sticky = false;
                        self.sticky_height = 0;
                        self.sticky_offset = 0;
                    } else {
                        if (self.$menu_wrap)
                            self.$menu_wrap.stop().css('top', 0);
                        if (scroll_top > self.sticky_pos && (!theme.isTablet() || (theme.isTablet() && (!theme.isMobile() && theme.show_sticky_header_tablet) || (theme.isMobile() && theme.show_sticky_header_tablet && theme.show_sticky_header_mobile)))) {
                            if (!self.$header.hasClass('sticky-header')) {
                                self.$header.addClass('sticky-header');
                                self.$header.css('padding-bottom', self.main_height);
                                self.$header_main.addClass('sticky');
                                if (self.change_logo) self.$header_main.addClass('change-logo');
                                self.$header_main.stop().css('top', theme.adminBarHeight());

                                if (!self.init_toggle_menu) {
                                    self.init_toggle_menu = true;
                                    theme.MegaMenu.build();
                                    if ($('#main-toggle-menu').length) {
                                        if ($('#main-toggle-menu').hasClass('show-always')) {
                                            $('#main-toggle-menu').data('show-always', true);
                                            $('#main-toggle-menu').removeClass('show-always');
                                        }
                                        $('#main-toggle-menu').addClass('closed');
                                        $('#main-toggle-menu .menu-title').addClass('closed');
                                        $('#main-toggle-menu .toggle-menu-wrap').attr('style', '');
                                    }
                                }
                                self.is_sticky = true;
                            }
                        } else {
                            if (self.$header.hasClass('sticky-header')) {
                                if (self.change_logo) self.$header_main.removeClass('change-logo');
                                self.$header_main.removeClass('sticky');
                                self.$header.removeClass('sticky-header');
                                self.$header.css('padding-bottom', 0);
                                self.$header_main.stop().css('top', 0);

                                if (self.init_toggle_menu) {
                                    self.init_toggle_menu = false;
                                    theme.MegaMenu.build();
                                    if ($('#main-toggle-menu').length) {
                                        if ($('#main-toggle-menu').data('show-always')) {
                                            $('#main-toggle-menu').addClass('show-always');
                                            $('#main-toggle-menu').removeClass('closed');
                                            $('#main-toggle-menu .menu-title').removeClass('closed');
                                            $('#main-toggle-menu .toggle-menu-wrap').attr('style', '');
                                        }
                                    }
                                }
                                self.is_sticky = false;
                            }
                        }
                    }
                }

                return self;
            },

            reset: function() {
                var self = this;

                if (self.$menu_wrap && !theme.isTablet()) {
                    // show main menu
                    self.$header.addClass('sticky-header sticky-header-calc');
                    self.$header_main.addClass('sticky');
                    if (self.change_logo) self.$header_main.addClass('change-logo');

                    self.sticky_height = self.$menu_wrap.height() + parseInt(self.$menu_wrap.css('padding-top')) + parseInt(self.$menu_wrap.css('padding-bottom'));
                    self.sticky_offset = parseInt(self.$menu_wrap.css('padding-top')) + parseInt(self.$menu_wrap.css('padding-bottom'));

                    if (self.change_logo) self.$header_main.removeClass('change-logo');
                    self.$header_main.removeClass('sticky');
                    self.$header.removeClass('sticky-header sticky-header-calc');
                    self.header_height = self.$header.height();
                    self.menu_height = self.$menu_wrap.height() + parseInt(self.$menu_wrap.css('padding-top')) + parseInt(self.$menu_wrap.css('padding-bottom'));

                    self.sticky_pos = (self.header_height - self.menu_height) + $('.banner-before-header').height() + parseInt($('body').css('padding-top')) + parseInt(self.$header.css('border-top-width'));
                } else {
                    // show header main
                    self.$header.addClass('sticky-header sticky-header-calc');
                    self.$header_main.addClass('sticky');
                    if (self.change_logo) self.$header_main.addClass('change-logo');
                    self.sticky_main_height = self.$header_main.height();

                    if (self.change_logo) self.$header_main.removeClass('change-logo');
                    self.$header_main.removeClass('sticky');
                    self.$header.removeClass('sticky-header sticky-header-calc');
                    self.header_height = self.$header.height();
                    self.main_height = self.$header_main.height();

                    self.sticky_height = self.sticky_main_height;
                    self.sticky_offset = self.main_height - self.sticky_main_height;

                    if (!(!theme.isTablet() || (theme.isTablet() && (!theme.isMobile() && theme.show_sticky_header_tablet) || (theme.isMobile() && theme.show_sticky_header_tablet && theme.show_sticky_header_mobile)))) {
                        self.sticky_height = 0;
                        self.sticky_offset = 0;
                    }

                    self.sticky_pos = (self.header_height - self.sticky_main_height) + $('.banner-before-header').height() + parseInt($('body').css('padding-top')) + parseInt(self.$header.css('border-top-width'));
                }

                self.init_toggle_menu = false;

                self.$header.removeAttr('style');

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.reset()
                        .build();
                });

                $(window).on('scroll', function() {
                    self.build();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);

// Sticky Header
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        SideNav: {

            defaults: {
                side_nav: $('.header-side-nav #header')
            },

            bc_pos_top: 0,

            initialize: function($side_nav) {
                this.$side_nav = ($side_nav || this.defaults.side_nav);

                if (!this.$side_nav.length)
                    return this;

                var self = this;

                self.$side_nav.addClass("initialize");

                self.reset()
                    .build()
                    .events();

                return self;
            },

            build: function() {
                var self = this;

                $page_top = $('.page-top');
                $main = $('#main');

                if (theme.isTablet()) {
                    self.$side_nav.removeClass("fixed-bottom");
                    $page_top.removeClass("fixed-pos");
                    $page_top.attr('style', '');
                    $main.attr('style', '');
                } else {
                    var side_height = self.$side_nav.innerHeight();
                    var window_height = $(window).height();
                    var scroll_top = $(window).scrollTop();

                    if (side_height - window_height + theme.adminBarHeight() <= scroll_top) {
                        if (!self.$side_nav.hasClass("fixed-bottom"))
                            self.$side_nav.addClass("fixed-bottom");
                    } else {
                        if (self.$side_nav.hasClass("fixed-bottom"))
                            self.$side_nav.removeClass("fixed-bottom");
                    }

                    if ($page_top.length) {
                        if (self.page_top_offset == theme.adminBarHeight() || self.page_top_offset <= scroll_top) {
                            if (!$page_top.hasClass("fixed-pos")) {
                                $page_top.addClass("fixed-pos");
                                $page_top.css('top', theme.adminBarHeight());
                                $main.css('padding-top', $page_top.outerHeight());
                            }
                        } else {
                            if ($page_top.hasClass("fixed-pos")) {
                                $page_top.removeClass("fixed-pos");
                                $page_top.attr('style', '');
                                $main.attr('style', '');
                            }
                        }
                    }
                }

                return self;
            },

            reset: function() {
                var self = this;

                if (theme.isTablet()) {

                    self.$side_nav.attr('style', '');

                } else {

                    var w_h = $(window).height();

                    $side_bottom = self.$side_nav.find('.side-bottom');

                    self.$side_nav.css({
                        'min-height': w_h - theme.adminBarHeight(),
                        'padding-bottom': $side_bottom.height() + parseInt($side_bottom.css('margin-top')) + parseInt($side_bottom.css('margin-bottom'))
                    });

                    var appVersion			= navigator.appVersion;
                    var webkitVersion_positionStart	= appVersion.indexOf("AppleWebKit/") + 12;
                    var webkitVersion_positionEnd	= webkitVersion_positionStart + 3;
                    var webkitVersion		= appVersion.slice(webkitVersion_positionStart,webkitVersion_positionEnd);
                    if (webkitVersion  < 537) {
                        self.$side_nav.css('-webkit-backface-visibility', 'hidden');
                        self.$side_nav.css('-webkit-perspective', '1000');
                    }
                }

                $page_top = $('.page-top');
                $main = $('#main');

                if ($page_top.length) {
                    $page_top.removeClass("fixed-pos");
                    $page_top.attr('style', '');
                    $main.attr('style', '');
                    self.page_top_offset = $page_top.offset().top;
                }

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.reset()
                        .build();
                });

                $(window).on('scroll', function() {
                    self.build();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Search
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Search: {

            defaults: {
                popup: $('.searchform-popup'),
                form: $('.searchform')
            },

            initialize: function($popup, $form) {
                this.$popup = ($popup || this.defaults.popup);
                this.$form = ($form || this.defaults.form);

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

                return this;
            },

            events: function() {
                var self = this;

                self.$popup.click(function(e) {
                    e.stopPropagation();
                });
                self.$popup.find('.search-toggle').click(function(e) {
                    $(this).next().toggle();
                    e.stopPropagation();
                });

                if (!('ontouchstart' in document)) {
                    $('html,body').click(function() {
                        self.removeFormStyle();
                    });

                    $(window).on('resize', function() {
                        self.removeFormStyle();
                    });
                }

                return self;
            },

            removeFormStyle: function() {
                this.$form.removeAttr('style');
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Mobile Panel
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Panel: {

            initialize: function() {

                this.events();

                return this;
            },

            events: function() {
                var self = this;

                $('.mobile-toggle').click(function(e) {
                    var $html = $('html');
                    if ($html.hasClass('panel-opened')) {
                        $html.removeClass('panel-opened');
                        $('.panel-overlay').removeClass('active');
                    } else {
                        $html.addClass('panel-opened');
                        $('.panel-overlay').addClass('active');
                    }
                });

                $('.panel-overlay').click(function() {
                    var $html = $('html');
                    $html.removeClass('panel-opened');
                    $(this).removeClass('active');
                });

                $('#nav-panel-close').click(function() {
                    $('.panel-overlay').click();
                });

                $(window).on('resize', function() {
                    var winWidth = $(window).width();
                    if (winWidth > 991 - theme.getScrollbarWidth()) {
                        $('.panel-overlay').click();
                    }
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Hash Scroll
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        HashScroll: {

            initialize: function() {

                this.sticky_nav_height = theme.Sticky.height;

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

                try {
                    var hash = window.location.hash;
                    var target = $(hash);
                    if (target.length && !(hash == '#review_form' || hash == '#reviews' || hash.indexOf('#comment-') != -1)) {
                        var delay = 400;
//                        if ($(window).scrollTop() < theme.StickyHeader.sticky_pos + self.sticky_nav_height) {
//                            delay += 250;
//                            $('html, body').animate({
//                                scrollTop: theme.StickyHeader.sticky_pos + self.sticky_nav_height + 1
//                            }, 100);
//                        }
                        setTimeout(function() {
                            $('html, body').stop().animate({
                                scrollTop: target.offset().top - theme.StickyHeader.sticky_height - theme.adminBarHeight() - self.sticky_nav_height
                            }, 600, 'easeOutQuad', function() {
                                self.activeMenuItem();
                            });
                        }, delay);
                    }

                    return self;
                } catch (err) {
                    return self;
                }
            },

            activeMenuItem: function() {
                var self = this;

                if ($('body').hasClass('scrolling'))
                    return self;

                var scroll_pos = $(window).scrollTop();

                var $menu_items = $('.menu-item > a[href*="#"], .porto-sticky-nav .nav > li > a[href*="#"]');
                if ($menu_items.length) {
                    $menu_items.each(function() {
                        var $this = $(this);
                        var href = $this.attr('href');
                        var target = {};

                        if (href.indexOf('#') == 0) {
                            target = $(href);
                        } else {
                            var url = window.location.href;
                            url = url.substring(url.indexOf('://') + 3);
                            if (url.indexOf('#') != -1)
                                url = url.substring(0, url.indexOf('#'));
                            href = href.substring(href.indexOf('://') + 3);
                            href = href.substring(href.indexOf(url) + url.length);
                            if (href.indexOf('#') == 0) {
                                target = $(href);
                            }
                        }
                        if (target.length) {
                            var scroll_to = target.offset().top - theme.StickyHeader.sticky_height - theme.adminBarHeight() - self.sticky_nav_height + 1;
                            if (scroll_to <= theme.StickyHeader.sticky_pos + self.sticky_nav_height) {
                                scroll_to = theme.StickyHeader.sticky_pos + self.sticky_nav_height + 1;
                            }
                            var $parent = $this.parent();
                            if (scroll_to <= scroll_pos + 5) {
                                $parent.siblings().removeClass('active');
                                $parent.addClass('active');
                            } else {
                                $parent.removeClass('active');
                            }
                        }
                    });
                }

                return self;
            },

            events: function() {
                var self = this;

                $('.menu-item > a[href*="#"], .porto-sticky-nav .nav > li > a[href*="#"], a[href^="#"].hash-scroll, .hash-scroll-wrap a[href^="#"]').on('click', function(e) {
                    e.preventDefault();

                    var $this = $(this);
                    var href = $this.attr('href');
                    var target = {};

                    if (href.indexOf('#') == 0) {
                        target = $(href);
                    } else {
                        var url = window.location.href;
                        url = url.substring(url.indexOf('://') + 3);
                        if (url.indexOf('#') != -1)
                            url = url.substring(0, url.indexOf('#'));
                        href = href.substring(href.indexOf('://') + 3);
                        href = href.substring(href.indexOf(url) + url.length);
                        if (href.indexOf('#') == 0) {
                            target = $(href);
                        }
                    }

                    if (target.length) {

                        var $parent = $this.parent();
                        $parent.siblings().removeClass('active');
                        $parent.addClass('active');
                        $('body').addClass('scrolling');

                        var delay = 200;
                        setTimeout(function() {
                            var scroll_to = target.offset().top - theme.StickyHeader.sticky_height - theme.adminBarHeight() - self.sticky_nav_height + 1;
                            if (scroll_to <= theme.StickyHeader.sticky_pos + self.sticky_nav_height) {
                                scroll_to = theme.StickyHeader.sticky_pos + self.sticky_nav_height + 1;
                            }
                            $('html, body').stop().animate({
                                scrollTop: scroll_to
                            }, 600, 'easeOutQuad', function() {
                                $('body').removeClass('scrolling');
                                self.activeMenuItem();
                            });
                        }, delay);
                    } else {
                        window.location.href = $this.attr('href');
                    }
                });

                $(window).on('scroll', function() {
                    self.activeMenuItem();
                });

                $(window).on('resize', function() {
                    self.sticky_nav_height = theme.Sticky.height;
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Flickr Zoom
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        FlickrZoom: {

            defaults: {
                wrapper: $('.wpb_flickr_widget')
            },

            initialize: function($wrapper) {
                this.$wrapper = ($wrapper || this.defaults.wrapper);

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$wrapper.each(function() {
                    var links = [];
                    var i = 0;
                    var $flickr_links = $(this).find('.flickr_badge_image > a');
                    $flickr_links.each(function() {
                        var slide = {};
                        var $image = $(this).find('> img');
                        slide.title = $image.attr('title');
                        slide.href = $image.attr('src').replace('_s.', '_b.');
                        slide.thumbnail = $image.attr('src');
                        links[i] = slide;
                        i++;
                    });
                    $flickr_links.unbind('click').click(function(e) {
                        e.preventDefault();
                        var options = {index: $flickr_links.index($(this)), event: e};
                        blueimp.Gallery(links, options);
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Way Points
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        WayPoints: {

            defaults: {
                wrapper: $('body')
            },

            initialize: function($wrapper) {
                this.$wrapper = ($wrapper || this.defaults.wrapper);

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                if($().waypoint) {

                    var $animations = self.$wrapper.find('.appear-animation');

                    $animations.waypoint(function() {

                        // this code is executed for each appeared element
                        var $this = $(this);
                        var animation_type = $this.attr('data-appear-animation');
                        var animation_duration = $this.attr('data-appear-animation-duration');
                        var animation_delay = $this.attr('data-appear-animation-delay');

                        $this.addClass('appear-animation-visible ' + animation_type);

                        if (animation_duration) {
                            $this.css('-moz-animation-duration', animation_duration+'ms');
                            $this.css('-webkit-animation-duration', animation_duration+'ms');
                            $this.css('-ms-animation-duration', animation_duration+'ms');
                            $this.css('-o-animation-duration', animation_duration+'ms');
                            $this.css('animation-duration', animation_duration+'ms');
                        }
                        if (animation_delay) {
                            $this.css('-moz-animation-delay', animation_delay+'ms');
                            $this.css('-webkit-animation-delay', animation_delay+'ms');
                            $this.css('-ms-animation-delay', animation_delay+'ms');
                            $this.css('-o-animation-delay', animation_delay+'ms');
                            $this.css('animation-delay', animation_delay+'ms');
                        }
                    }, {
                        triggerOnce: true,
                        offset: '85%'
                    });

                    // circular bar
                    var circular_bars = self.$wrapper.find('.circular-bar');
                    circular_bars.waypoint(function() {

                        var $chart = $(this).find('.circular-bar-chart');

                        var barcolor = $chart.attr('data-barcolor');
                        var trackcolor = $chart.attr('data-trackcolor');
                        var scalecolor = $chart.attr('data-scalecolor');
                        var linecap = $chart.attr('data-linecap');
                        var linewidth = $chart.attr('data-linewidth');
                        var size = $chart.attr('data-size');
                        var speed = $chart.attr('data-speed');
                        var percent = $chart.attr('data-percent');
                        var label_value = $chart.attr('data-label-value');
                        if (!label_value)
                            label_value = percent;

                        var $label = $chart.find('.percent');
                        $chart.easyPieChart({
                            barColor: barcolor,
                            trackColor: trackcolor,
                            scaleColor: scalecolor,
                            scaleLength: 5,
                            lineCap: linecap,
                            lineWidth: linewidth,
                            size: size,
                            rotate: 0,
                            animate: {
                                duration: speed,
                                enabled: true
                            },
                            onStep: function(from, to, current_value) {
                                $label.html(parseInt(label_value * current_value / percent));
                            }
                        });
                    }, {
                        triggerOnce: true,
                        offset: '85%'
                    });

                    // Progress bar tooltip
                    var progress_bar_tooltips = self.$wrapper.find('.progress-bar-tooltip');
                    progress_bar_tooltips.waypoint(function() {

                        var $this = $(this);
                        setTimeout(function() {
                            $this.animate({
                                opacity: 1
                            });
                        }, 500);
                    }, {
                        triggerOnce: true,
                        offset: '85%'
                    });
                }

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Scroll to Top
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        ScrollToTop: {

            defaults: {
                html: '<i class="fa fa-chevron-up"></i>',
                offsetx: 10,
                offsety: 0
            },

            initialize: function(html, offsetx, offsety) {
                this.html = (html || this.defaults.html);
                this.offsetx = (offsetx || this.defaults.offsetx);
                this.offsety = (offsety || this.defaults.offsety);

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                if (typeof scrolltotop !== 'undefined') {
                    // scroll top control
                    scrolltotop.controlHTML = self.html;
                    scrolltotop.controlattrs = {offsetx: self.offsetx, offsety: self.offsety};
                    scrolltotop.init();
                }

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Fit Videos
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        FitVideos: {

            defaults: {
                wrapper: $('body')
            },

            initialize: function($wrapper) {
                this.$wrapper = ($wrapper || this.defaults.wrapper);

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

                var $videos = self.$wrapper.find('.fit-video');
                if ($videos.length)
                    $videos.fitVids();

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.build();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Refresh Video Sizes
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        RefreshVideoSizes: {

            defaults: {
                wrapper: $('.video-cover .upb_video-src')
            },

            initialize: function($wrapper) {
                this.$wrapper = ($wrapper || this.defaults.wrapper);

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

                self.$wrapper.each(function() {
                    var $this = $(this);
                    $('<img src="'+ $this.attr('poster') +'">').load(function() {
                        $(this).css({
                            'position': 'absolute',
                            'z-index': -1,
                            'bottom': 0
                        });
                        $('body').append($(this));
                        var height = $(this).height();
                        var width = $(this).width();
                        var ratio = height / width;
                        var $parent = $this.closest('.vc_row');
                        var parentWidth = $parent.width();
                        var parentHeight = $parent.height();
                        $this.css({
                            'width': parentWidth,
                            'min-width': parentWidth,
                            'height': parentWidth * ratio
                        });
                        $this.parent().css({
                            'width': parentWidth,
                            'min-width': parentWidth
                        });
                        if (parentWidth * ratio < parentHeight) {
                            $this.css({
                                'height': parentHeight,
                                'min-width': parentHeight / ratio,
                                'width': parentHeight * ratio
                            });
                            $this.parent().css({
                                'height': parentHeight,
                                'min-width': parentHeight / ratio,
                                'width': parentHeight * ratio
                            });
                        }
                        $(this).remove();
                    });
                });

                return self;
            },

            events: function() {
                var self = this;

                setTimeout(function() {
                    self.build();
                }, 600);

                $(window).on('resize', function() {
                    self.build();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Accordion
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Accordion: {

            defaults: {
                elements: '.panel-group'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function(){
                    var $this = $(this), $collapse = $this.find('.collapse');
                    var collapsible = $this.data('collapsible'), active_num = $this.data('active-tab');
                    if ( $collapse.length > 0 ) {
                        if ( collapsible !== 'yes' && !isNaN(active_num) && active_num == parseInt(active_num) && $this.find('.collapse').length > active_num ) {
                            var $current = $this.find('.collapse').eq(active_num-1);
                            $current.addClass('collapse in');
                        }
                    }
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Toggle
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Toggle: {

            defaults: {
                elements: 'section.toggle'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    if ($this.hasClass('active'))
                        $this.find("> div.toggle-content").stop().slideDown(350, function() {});

                    $this.find("> label").unbind('click').click(function(e) {
                        var parentSection = $(this).parent(),
                            parentWrapper = $(this).closest("div.toogle"),
                            parentToggles = $(this).closest(".porto-toggles"),
                            isAccordion = parentWrapper.hasClass("toogle-accordion");
                        if(isAccordion && typeof(e.originalEvent) != "undefined") {
                            parentWrapper.find("section.toggle.active > label").trigger("click");
                        }

                        // Content
                        var toggleContent = parentSection.find("> div.toggle-content");
                        // Preview Paragraph
                        if(!parentSection.hasClass("active")) {
                            if (parentToggles.length) {
                                if (parentToggles.data('view') == 'one-toggle') {
                                    parentToggles.find('.toggle').each(function() {
                                        $(this).removeClass('active');
                                        $(this).find("> div.toggle-content").stop().slideUp(350, function() {});
                                    });
                                }
                            }
                            toggleContent.stop().slideDown(350, function() {
                                theme.refreshVCContent(toggleContent);
                            });
                            parentSection.addClass("active");
                        } else {
                            if (!parentToggles.length || parentToggles.data('view') != 'one-toggle') {
                                toggleContent.stop().slideUp(350, function() {});
                                parentSection.removeClass("active");
                            }
                        }
                    });
                });

                return self;
            }
        }
    });

}).apply(this, [window.theme, jQuery]);


// Tooltip
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Tooltip: {

            defaults: {
                elements: 'body'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.find("[data-toggle='tooltip']").tooltip();
                self.$elements.find('.star-rating').tooltip();

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Visual Composer Image Zoom
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        VcImageZoom: {

            defaults: {
                elements: '.porto-vc-zoom'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.unbind('click').click(function(e) {
                    e.preventDefault();

                    var $this = $(this), gallery = $this.attr('data-gallery'), links = [], i = 0, options;

                    if (gallery == 'true') {
                        var container = 'vc_row';
                        if ($this.attr('data-container'))
                            container = $this.attr('data-container');
                        var $parent = $($this.closest('.' + container).get(0));
                        $parent.find('.porto-vc-zoom').each(function() {
                            var slide = {};
                            slide.title = $(this).attr('data-caption');
                            slide.href = $(this).attr('href');
                            slide.thumbnail = $(this).attr('href');
                            links[i] = slide;
                            i++;
                        });
                        options = {index: $parent.find('.porto-vc-zoom').index($this), event: e};
                    } else {
                        var slide = {};
                        slide.title = $(this).attr('data-caption');
                        slide.href = $(this).attr('href');
                        slide.thumbnail = $(this).attr('href');
                        links[i] = slide;
                        options = {index: 0, event: e};
                    }
                    blueimp.Gallery(links, options);

                    return false;
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Slideshow
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Slideshow: {

            defaults: {
                $wrapper: $('body'),
                slider: '.post-slideshow',
                zoom: false,
                options: {}
            },

            initialize: function($wrapper, slider, zoom, options) {
                this.$wrapper = ($wrapper || this.defaults.wrapper);
                this.slider = (slider || this.defaults.slider);
                this.zoom = zoom;
                this.options = (options || this.defaults.options);

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$wrapper.find(self.slider).each(function() {
                    var $this = $(this);
                    if (typeof self.zoom !== 'undefined' && self.zoom) {
                        var links = [];
                        var i = 0;
                        $this.find('img').each(function() {
                            var slide = {};
                            slide.title = $(this).attr('data-caption');
                            slide.href = $(this).attr('data-image');
                            slide.thumbnail = $(this).attr('data-src');
                            links[i] = slide;
                            $(this).next('.zoom').attr('data-index', i);
                            i++;
                        });
                    }

                    if (theme.slider_nav_hover)
                        $this.addClass('show-nav-hover');
                    if (theme.slider_margin)
                        $this.addClass('stage-margin');

                    var count = $this.find('> *').length;

                    $this.owlCarousel($.extend({}, theme.owlConfig, {
                        loop: (theme.slider_loop && count > 1) ? true : false
                    }, self.options));

                    if (typeof self.zoom !== 'undefined' && self.zoom) {
                        var $zoom_buttons = $this.find('.zoom');
                        $zoom_buttons.unbind('click').click(function(e) {
                            e.preventDefault();
                            var options = {index: $(this).data('index'), event: e};
                            blueimp.Gallery(links, options);
                        });
                    }
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Carousel
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Carousel: {

            defaults: {
                carousel: '.post-carousel',
                options: {}
            },

            initialize: function(carousel, zoom, options) {
                this.carousel = (carousel || this.defaults.carousel);
                this.zoom = zoom;
                this.options = (options || this.defaults.options);

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                $(self.carousel).each(function() {
                    var $this = $(this);
                    if (typeof self.zoom !== 'undefined' && self.zoom) {
                        var links = [];
                        var i = 0;
                        $this.find('img').each(function() {
                            var slide = {};
                            slide.title = $(this).attr('data-caption');
                            slide.href = $(this).attr('data-image');
                            slide.thumbnail = $(this).attr('src');
                            links[i] = slide;
                            $(this).closest('.post-item').find('.zoom').attr('data-index', i);
                            i++;
                        });
                    }

                    var cols_lg = $this.data('cols-lg'), cols_md = $this.data('cols-md'), cols_sm = $this.data('cols-sm');
                    var single = $this.data('single');

                    var responsive = {}, items, scrollWidth = theme.getScrollbarWidth(), count = $this.find('> *').length;

                    if (single) {
                        items = 1;
                    } else {
                        items = cols_lg ? cols_lg : 1;
                        var w_xs = 481 - scrollWidth, w_md = 768 - scrollWidth, w_lg = 992 - scrollWidth;
                        if (cols_lg) responsive[w_lg] = { items: cols_lg, loop: (theme.slider_loop && count > cols_lg) ? true : false };
                        if (cols_md) responsive[w_md] = { items: cols_md, loop: (theme.slider_loop && count > cols_md) ? true : false };
                        if (cols_sm) responsive[w_xs] = { items: cols_sm, loop: (theme.slider_loop && count > cols_sm) ? true : false };
                        responsive[0] = { items: 1, loop: (theme.slider_loop && count > 1) ? true : false };
                    }

                    if (!$this.hasClass('show-nav-title') && theme.slider_nav_hover)
                        $this.addClass('show-nav-hover');

                    $this.find('> *:first-child').imagesLoaded(function() {
                        $this.owlCarousel($.extend({}, theme.owlConfig, {
                            items: items,
                            loop: (theme.slider_loop && count > items) ? true : false,
                            responsive: responsive,
                            autoHeight: false,
                            stagePadding: 0
                        }, self.options));

                        if (typeof self.zoom !== 'undefined' && self.zoom && links) {
                            var $zoom_buttons = $this.find('.zoom');
                            $zoom_buttons.unbind('click').click(function(e) {
                                e.preventDefault();
                                var options = {index: $(this).data('index'), event: e};
                                blueimp.Gallery(links, options);
                            });
                        }
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Grid (Isotope)
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Grid: {

            defaults: {
                elements: null,
                itemSelector: null,
                callback: null
            },

            initialize: function($elements, itemSelector, callback) {
                this.$elements = ($elements || this.defaults.elements);
                this.itemSelector = (itemSelector || this.defaults.itemSelector);
                this.callback = (callback || this.defaults.callback);

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;
                var itemSelector = self.itemSelector;
                var callback = self.callback;

                if (self.$elements.length && self.itemSelector) {
                    self.$elements.each(function() {
                        var $this = $(this);
                        if ($().isotope) {
                            $this.imagesLoaded(function() {
                                $this.isotope({
                                    itemSelector : itemSelector,
                                    isOriginLeft : !theme.rtl
                                }).isotope('layout');
                                if (callback)
                                    $this.isotope('on', 'layoutComplete', callback);
                                self.resize();
                            });
                        }
                    });
                    self.resize();
                }

                return self;
            },

            resize: function() {
                var self = this;

                if (self.resizeTimer)
                    clearTimeout(self.resizeTimer);

                self.resizeTimer = setTimeout(function() {
                    self.$elements.each(function() {
                        var $this = $(this);
                        if ($().isotope) {
                            if ($this.data('isotope')) {
                                $this.isotope('layout');
                            }
                        }
                    });
                    delete self.resizeTimer;
                }, 600);

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.resize();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Posts Infinite
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        PostsInfinite: {

            defaults: {
                elements: '.posts-infinite',
                itemSelector: '.posts-infinite .post, .posts-infinite .timeline-date'
            },

            initialize: function($elements, itemSelector) {
                this.$elements = ($elements || $(this.defaults.elements));
                this.itemSelector = (itemSelector || this.defaults.itemSelector);

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    var curr_page = $this.attr('data-pagenum');
                    var page_path = $this.attr('data-path');
                    $this.infinitescroll($.extend(theme.infiniteConfig, {
                        itemSelector : self.itemSelector,
                        state : {
                            currPage: curr_page
                        },
                        pathParse : function(a, b) {
                            return [page_path, '/'];
                        }
                    }), function(posts) {

                        var $posts = $(posts);
                        theme.refreshVCContent($posts);
                        theme.Slideshow.initialize($posts, '.post-slideshow', theme.post_zoom);
                        theme.WayPoints.initialize($posts);
                        theme.Tooltip.initialize($posts);
                        theme.FitVideos.initialize($posts);

                        if ($().isotope) {
                            if ($this.hasClass('grid')) {
                                $posts.hide();
                                $posts.imagesLoaded(function() {
                                    $posts.show();
                                    if ($this.data('isotope')) {
                                        $this.isotope('appended', $posts).isotope('layout');
                                        theme.refreshVCContent($posts);
                                        theme.Tooltip.initialize($posts);
                                        theme.FitVideos.initialize($posts);
                                        self.resize();
                                    }
                                });
                            }
                        }
                    });
                });

                self.resize();

                return self;
            },

            resize: function() {
                var self = this;

                if (self.resizeTimer)
                    clearTimeout(self.resizeTimer);
                self.resizeTimer = setTimeout(function() {
                    self.$elements.each(function() {
                        var $this = $(this);
                        if ($().isotope) {
                            if ($this.data('isotope')) {
                                $this.isotope('layout');
                            }
                        }
                    });
                    delete self.resizeTimer;
                }, 600);

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.resize();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Portfolios Infinite
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        PortfoliosInfinite: {

            defaults: {
                elements: '.portfolios-infinite',
                itemSelector: '.portfolios-infinite .portfolio, .portfolios-infinite .timeline-date'
            },

            initialize: function($elements, itemSelector) {
                this.$elements = ($elements || $(this.defaults.elements));
                this.itemSelector = (itemSelector || this.defaults.itemSelector);

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    var curr_page = $this.attr('data-pagenum');
                    var page_path = $this.attr('data-path');
                    $this.infinitescroll($.extend(theme.infiniteConfig, {
                        itemSelector : self.itemSelector,
                        state : {
                            currPage: curr_page
                        },
                        pathParse : function(a, b) {
                            return [page_path, '/'];
                        }
                    }), function(posts) {

                        var $posts = $(posts);
                        theme.refreshVCContent($posts);
                        theme.Slideshow.initialize($posts, '.portfolio-slideshow', theme.portfolio_zoom);
                        theme.WayPoints.initialize($posts);
                        theme.Tooltip.initialize($posts);
                        theme.FitVideos.initialize($posts);

                        var $parent = $this.closest('.page-portfolios');

                        if ($parent.hasClass('portfolios-grid')) {
                            if ($().isotope) {
                                $posts.hide();
                                $posts.imagesLoaded(function() {
                                    $posts.show();
                                    if ($this.data('isotope')) {
                                        $this.isotope('appended', $posts).isotope('layout');
                                        theme.refreshVCContent($posts);
                                        theme.Tooltip.initialize($posts);
                                        theme.FitVideos.initialize($posts);
                                        self.resize();
                                    }
                                });
                            }
                        } else {
                            var selected = 0;
                            if ($parent.find('.portfolio-filter').length) {
                                var selector = $parent.find('.portfolio-filter .active').attr('data-filter');
                                $posts.each(function() {
                                    var $that = $(this);
                                    if (selector == '*') {
                                        $that.stop().fadeIn(300);
                                        selected++;
                                    } else {
                                        if ($that.hasClass(selector)) {
                                            $that.stop().fadeIn(300);
                                            selected++;
                                        } else {
                                            $that.stop().hide();
                                        }
                                    }
                                });
                            }
                            if (!selected && $parent.find('.portfolios-infinite').length) {
                                $parent.find('.portfolios-infinite').infinitescroll('retrieve');
                            }
                            if ($parent.hasClass('portfolios-timeline'))
                                theme.FilterZoom.initialize($parent, theme.portfolio_zoom);
                        }
                    });
                });

                self.resize();

                return self;
            },

            resize: function() {
                var self = this;

                if (self.resizeTimer)
                    clearTimeout(self.resizeTimer);
                self.resizeTimer = setTimeout(function() {
                    self.$elements.each(function() {
                        var $this = $(this);
                        if ($().isotope) {
                            if ($this.data('isotope')) {
                                $this.isotope('layout');
                            }
                        }
                    });
                    delete self.resizeTimer;
                }, 600);

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.resize();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Portfolio Like
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        PortfolioLike: {

            initialize: function() {

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                $('body').on('click', '.portfolio-like', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var $parent = $this.parent();
                    var portfolio_id = $this.attr('data-id');
                    $.post(theme.ajax_url, {
                            portfolio_id: portfolio_id,
                            action: 'porto_portfolio-like'
                        },
                        function(data) {
                            if (data) {
                                $this.remove();
                                $parent.html(data);
                                $parent.find("[data-toggle='tooltip']").tooltip();
                            }
                        })
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Portfolio Filter
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        PortfolioFilter: {

            defaults: {
                elements: '.portfolio-filter'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    $this.find('li').on('click', function(e) {
                        e.preventDefault();

                        var selector = $(this).attr('data-filter');
                        $this.find('.active').removeClass('active');

                        var $parent = $(this).closest('.page-portfolios');

                        if ($parent.hasClass('portfolios-grid')) {
                            if (selector != '*')
                                selector = '.' + selector;
                            $parent.find('.portfolio-row').isotope({
                                filter: selector
                            });
                        } else {
                            $parent.find('.portfolio').each(function() {
                                var $that = $(this);
                                if (selector == '*') {
                                    $that.stop().fadeIn(300);
                                } else {
                                    if ($that.hasClass(selector)) {
                                        $that.stop().fadeIn(300);
                                    } else {
                                        $that.stop().fadeOut();
                                    }
                                }
                            });
                            if ($parent.hasClass('portfolios-timeline')) {
                                setTimeout(function() {
                                    theme.FilterZoom.initialize($parent, theme.portfolio_zoom);
                                }, 400);
                            }
                        }

                        $(this).addClass('active');
                        theme.WayPoints.build();
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Members Infinite
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        MembersInfinite: {

            defaults: {
                elements: '.members-infinite',
                itemSelector: '.members-infinite .member'
            },

            initialize: function($elements, itemSelector) {
                this.$elements = ($elements || $(this.defaults.elements));
                this.itemSelector = (itemSelector || this.defaults.itemSelector);

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    var curr_page = $this.attr('data-pagenum');
                    var page_path = $this.attr('data-path');
                    $this.infinitescroll($.extend(theme.infiniteConfig, {
                        itemSelector : self.itemSelector,
                        state : {
                            currPage: curr_page
                        },
                        pathParse : function(a, b) {
                            return [page_path, '/'];
                        }
                    }), function(posts) {

                        var $posts = $(posts);
                        if ($().isotope) {
                            $posts.hide();
                            $posts.imagesLoaded(function() {
                                $posts.show();
                                if ($this.data('isotope')) {
                                    $this.isotope('appended', $posts).isotope('layout');
                                    theme.refreshVCContent($posts);
                                    theme.Tooltip.initialize($posts);
                                    theme.FitVideos.initialize($posts);
                                    self.resize();
                                }
                            });
                        }
                    });
                });
                self.resize();

                return self;
            },

            resize: function() {
                var self = this;

                if (self.resizeTimer)
                    clearTimeout(self.resizeTimer);
                self.resizeTimer = setTimeout(function() {
                    self.$elements.each(function() {
                        var $this = $(this);
                        if ($().isotope) {
                            if ($this.data('isotope')) {
                                $this.isotope('layout');
                            }
                        }
                    });
                    delete self.resizeTimer;
                }, 600);

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.resize();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Member Filter
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        MemberFilter: {

            defaults: {
                elements: '.member-filter'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    $this.find('li').on('click', function(e) {
                        e.preventDefault();

                        var selector = $(this).attr('data-filter');
                        $this.find('.active').removeClass('active');

                        var $parent = $(this).closest('.page-members');

                        if (selector != '*')
                            selector = '.' + selector;
                        $parent.find('.member-row').isotope({
                            filter: selector
                        });
                        $(this).addClass('active');
                        theme.WayPoints.build();
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// FAQs Infinite
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        FaqsInfinite: {

            defaults: {
                elements: '.faqs-infinite',
                itemSelector: '.faqs-infinite section'
            },

            initialize: function($elements, itemSelector) {
                this.$elements = ($elements || $(this.defaults.elements));
                this.itemSelector = (itemSelector || this.defaults.itemSelector);

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    var curr_page = $this.attr('data-pagenum');
                    var page_path = $this.attr('data-path');
                    $this.infinitescroll($.extend(theme.infiniteConfig, {
                        itemSelector : self.itemSelector,
                        state : {
                            currPage: curr_page
                        },
                        pathParse : function(a, b) {
                            return [page_path, '/'];
                        }
                    }), function(posts) {
                        var $posts = $(posts);
                        theme.refreshVCContent($posts);
                        theme.Tooltip.initialize($posts);
                        theme.Toggle.initialize($posts);
                        theme.WayPoints.initialize($posts);
                        theme.FitVideos.initialize($posts);
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// FAQ Filter
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        FaqFilter: {

            defaults: {
                elements: '.faq-filter'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    $this.find('li').on('click', function(e) {
                        e.preventDefault();

                        var selector = $(this).attr('data-filter');
                        $this.find('.active').removeClass('active');

                        var $parent = $(this).closest('.page-faqs');

                        $parent.find('section').each(function() {
                            var $that = $(this);
                            if (selector == '*') {
                                $that.stop().fadeIn();
                            } else {
                                if ($that.hasClass(selector)) {
                                    $that.stop().fadeIn();
                                } else {
                                    $that.stop().hide();
                                }
                            }
                        });

                        $(this).addClass('active');
                        theme.WayPoints.build();
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Filter Zoom
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        FilterZoom: {

            defaults: {
                elements: null,
                zoom: false
            },

            initialize: function($elements, zoom) {
                this.$elements = ($elements || this.defaults.elements);
                this.zoom = zoom;

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                if (self.$elements.length && self.zoom) {
                    self.$elements.each(function() {
                        $(this).find('.zoom').unbind('click');
                        var links = [];
                        var i = 0;
                        $(this).find('article').each(function() {
                            if ($(this).css('display') != 'none') {
                                var $image = $(this).find('img');
                                var slide = {};
                                slide.title = $image.attr('data-caption');
                                slide.href = $image.attr('data-image');
                                slide.thumbnail = $image.attr('src');
                                links[i] = slide;
                                $(this).find('.zoom').attr('zoom-index', i);
                                i++;
                            }
                        });
                        $(this).find('article').each(function() {
                            if ($(this).css('display') != 'none') {
                                $(this).find('.zoom').click(function(e) {
                                    e.preventDefault();
                                    var options = {index: $(this).attr('zoom-index'), event: e};
                                    blueimp.Gallery(links, options);
                                });
                            }
                        });
                    });
                }

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Preview Image
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        PreviewImage: {

            defaults: {
                elements: '.thumb-info-preview .thumb-info-image'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    var image = $this.attr('data-image');
                    if (image) {
                        $this.css('background-image', 'url(' + image + ')');
                    }
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Sort Filter
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        SortFilter: {

            defaults: {
                filters: '.porto-sort-filters ul',
                elements: '.porto-sort-container .row'
            },

            initialize: function($elements, $filters) {
                this.$elements = ($elements || $(this.defaults.elements));
                this.$filters = ($filters || $(this.defaults.filters));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    $this.isotope({
                        itemSelector: '.porto-sort-item',
                        layoutMode: 'fitRows',
                        getSortData: {
                            popular: '[data-popular] parseInt'
                        },
                        sortBy: 'popular',
                        isOriginLeft : !theme.rtl
                    }).imagesLoaded(function() {
                        if ($this.data('isotope')) {
                            $this.isotope('layout');
                        }
                    });
                });

                self.$filters.each(function() {
                    var $this = $(this);
                    var id = $this.attr('data-sort-id');
                    var $container = $('#' + id);
                    if ($container.length) {
                        $this.on('click', 'li', function(e) {
                            e.preventDefault();

                            var $that = $(this);
                            $this.find('li').removeClass('active');
                            $that.addClass("active");

                            var sortByValue = $that.attr('data-sort-by');
                            $container.isotope({sortBy: sortByValue});

                            var filterByValue = $that.attr('data-filter-by');
                            if (filterByValue) {
                                $container.isotope({filter: filterByValue});
                            } else {
                                $container.isotope({filter: '.porto-sort-item'});
                            }
                            theme.WayPoints.build();
                        });

                        $this.find('li[data-active]').click();
                    }
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Sticky
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        Sticky: {

            defaults: {
                elements: '.porto-sticky, .porto-sticky-nav'
            },

            height: 0,

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    var args = {};

                    var activeClass = $this.attr('data-active-class'),
                        top = $this.attr('data-top'),
                        bottom = $this.attr('data-bottom'),
                        minWidth = $this.attr('data-min-width'),
                        containerSelector = $this.attr('data-container-selector');

                    minWidth = parseInt(minWidth) - theme.scrollbar_width;

                    if ($this.hasClass('porto-sticky-nav')) {
                        top = theme.StickyHeader.sticky_height + theme.adminBarHeight();
                        activeClass = 'sticky-active';
                        containerSelector = '.page-content';
                        self.height = $this.outerHeight();
                        if (minWidth > $(window).width())
                            self.height = 0;
                    }

                    if (activeClass) $.extend(args, { activeClass : activeClass });
                    if (top) $.extend(args, { padding: { top: parseInt(top) } });
                    if (bottom) $.extend(args, { padding: { bottom: parseInt(bottom) } });
                    if (minWidth) $.extend(args, { minWidth : minWidth });
                    if (containerSelector) $.extend(args, { containerSelector : containerSelector });

                    $this.pin(args);
                });

                return self;
            },

            events: function() {
                var self = this;

                $(window).on('resize', function() {
                    self.$elements.each(function() {
                        var $this = $(this);
                        var $parent = $this.parent();

                        $this.width($parent.width());
                        if ($this.css('position') == 'fixed') {
                            $this.css('left', $parent.offset().left);
                        }

                        var minWidth = $this.attr('data-min-width');

                        minWidth = parseInt(minWidth) - theme.scrollbar_width;

                        if ($this.hasClass('porto-sticky-nav')) {
                            self.height = $this.outerHeight();
                            if (minWidth > $(window).width())
                                self.height = 0;
                        }
                    });
                });

                return self;
            }

        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Grid List Toggle
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        WooGridListToggle: {

            initialize: function() {

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                $('#grid').unbind('click').click(function() {
                    $(this).addClass('active');
                    $('#list').removeClass('active');
                    if (($.cookie && $.cookie('gridcookie') == 'list') || !$.cookie) {
                        var $toggle = $('.gridlist-toggle');
                        if ($toggle.length) {
                            var $parent = $toggle.parent().parent();
                            var $products = $parent.find('ul.products');
                            $products.fadeOut(300, function() {
                                $products.addClass('grid').removeClass('list').fadeIn(300);
                            });
                        }
                    }
                    if ($.cookie)
                        $.cookie('gridcookie', 'grid', { path: '/' });
                    return false;
                });

                $('#list').unbind('click').click(function() {
                    $(this).addClass('active');
                    $('#grid').removeClass('active');
                    if (($.cookie && $.cookie('gridcookie') == 'grid') || !$.cookie) {
                        var $toggle = $('.gridlist-toggle');
                        if ($toggle.length) {
                            var $parent = $toggle.parent().parent();
                            var $products = $parent.find('ul.products');
                            $products.fadeOut(300, function() {
                                $products.addClass('list').removeClass('grid').fadeIn(300);
                            });
                        }
                    }
                    if ($.cookie)
                        $.cookie('gridcookie', 'list', { path: '/' });
                    return false;
                });

                if ($.cookie && $.cookie('gridcookie')) {
                    var $toggle = $('.gridlist-toggle');
                    if ($toggle.length) {
                        var $parent = $toggle.parent().parent();
                        if ($parent.find('ul.products').hasClass('grid')) {
                            $.cookie('gridcookie', 'grid', { path: '/' });
                        } else if ($parent.find('ul.products').hasClass('list')) {
                            $.cookie('gridcookie', 'list', { path: '/' });
                        } else {
                            $parent.find('ul.products').addClass($.cookie('gridcookie'));
                        }
                    }
                }

                if ($.cookie && $.cookie('gridcookie') == 'grid') {
                    $('.gridlist-toggle #grid').addClass('active');
                    $('.gridlist-toggle #list').removeClass('active');
                }

                if ($.cookie && $.cookie('gridcookie') == 'list') {
                    $('.gridlist-toggle #list').addClass('active');
                    $('.gridlist-toggle #grid').removeClass('active');
                }

                if ($.cookie && $.cookie('gridcookie') == null) {
                    var $toggle = $('.gridlist-toggle');
                    if ($toggle.length) {
                        var $parent = $toggle.parent().parent();
                        $parent.find('ul.products').addClass('grid');
                    }
                    $('.gridlist-toggle #grid').addClass('active');
                    if ($.cookie)
                        $.cookie('gridcookie', 'grid', { path: '/' });
                }

                // Viewby
                $( '.woocommerce-viewing' ).off( 'change', 'select.count' ).on( 'change', 'select.count', function() {
                    $( this ).closest( 'form' ).submit();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Category Filter
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        WooCategoryFilter: {

            initialize: function() {

                this.events();

                return this;
            },

            events: function() {
                var self = this;

                $('.filter-toggle').click(function(e) {
                    var $html = $('html');
                    if ($html.hasClass('filter-opened')) {
                        $html.removeClass('filter-opened');
                        $('.filter-overlay').removeClass('active');
                    } else {
                        $html.addClass('filter-opened');
                        $('.filter-overlay').addClass('active');
                    }
                });

                $('.filter-overlay').click(function() {
                    var $html = $('html');
                    $html.removeClass('filter-opened');
                    $(this).removeClass('active');
                });

                $(window).on('resize', function() {
                    var winWidth = $(window).width();
                    if (winWidth > 991 - theme.getScrollbarWidth()) {
                        $('.filter-overlay').click();
                    }
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Products Slider
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        WooProductsSlider: {

            defaults: {
                elements: '.products-slider'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    var cols_lg = $this.attr('data-cols-lg'), cols_md = $this.attr('data-cols-md'), cols_xs = $this.attr('data-cols-xs'), cols_ls = $this.attr('data-cols-ls');

                    var $slider_wrapper = $this.closest('.slider-wrapper');
                    if ($this.find('.product').length) {
                        portoCalcSliderMargin($slider_wrapper, $this.find('.product').css('padding-left'));
                        portoCalcSliderButtonsPosition($slider_wrapper, $this.find('.product').css('padding-left'));
                    } else if ($this.find('.product-category').length) {
                        portoCalcSliderMargin($slider_wrapper, $this.find('.product-category').css('padding-left'));
                        portoCalcSliderButtonsPosition($slider_wrapper, $this.find('.product-category').css('padding-left'));
                    }
                    portoCalcSliderTitleLine($slider_wrapper);

                    var single = $this.data('single'), autoHeight = false;

                    var pagination = $this.data('pagination') == '1' ? true : false;
                    var navigation = $this.data('navigation') == '1' ? true : false;

                    if (!navigation && !pagination)
                        navigation = true;

                    var responsive = {}, items, scrollWidth = theme.getScrollbarWidth(), count = $this.find('> *').length;

                    if (single) {
                        items = 1;
                    } else {
                        items = cols_lg ? cols_lg : 1;
                        var w_xs = 481 - scrollWidth, w_md = 768 - scrollWidth, w_lg = 992 - scrollWidth;
                        if (cols_lg) responsive[w_lg] = { items: cols_lg, loop: (theme.slider_loop && count > cols_lg) ? true : false };
                        if (cols_md) responsive[w_md] = { items: cols_md, loop: (theme.slider_loop && count > cols_md) ? true : false };
                        if (cols_xs) responsive[w_xs] = { items: cols_xs, loop: (theme.slider_loop && count > cols_xs) ? true : false };
                        if (cols_ls) responsive[0] = { items: cols_ls, loop: (theme.slider_loop && count > cols_ls) ? true : false };
                    }

                    $this.find('> *:first-child').imagesLoaded(function() {
                        $this.owlCarousel({
                            rtl: theme.rtl,
                            autoplay : theme.slider_autoplay == '1' ? true : false,
                            autoplayTimeout: theme.slider_speed ? theme.slider_speed : 5000,
                            loop: (theme.slider_loop && count > items) ? true : false,
                            nav: navigation,
                            navText: ["", ""],
                            dots: pagination,
                            rewind: true,
                            autoplayHoverPause : true,
                            items : items,
                            responsive : responsive,
                            autoHeight : autoHeight,
                            lazyLoad: true,
                            onRefresh: function() {
                                if ($this.find('.product').length) {
                                    portoCalcSliderMargin($slider_wrapper, $this.find('.product').css('padding-left'));
                                    portoCalcSliderButtonsPosition($slider_wrapper, $this.find('.product').css('padding-left'));
                                } else if ($this.find('.product-category').length) {
                                    portoCalcSliderMargin($slider_wrapper, $this.find('.product-category').css('padding-left'));
                                    portoCalcSliderButtonsPosition($slider_wrapper, $this.find('.product-category').css('padding-left'));
                                }
                                portoCalcSliderTitleLine($slider_wrapper);
                            },
                            onInitialized: function() {
                                if ($this.find('.product').length) {
                                    portoCalcSliderButtonsPosition($slider_wrapper, $this.find('.product').css('padding-left'));
                                } else if ($this.find('.product-category').length) {
                                    portoCalcSliderButtonsPosition($slider_wrapper, $this.find('.product-category').css('padding-left'));
                                }
                            }
                        });
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Product Image Slider
(function(theme, $) {

    theme = theme || {};

    var duration = 300;
    var flag = false;
    var thumbs_count = theme.product_thumbs_count;

    if (theme.product_zoom && (!('ontouchstart' in document) || (('ontouchstart' in document) && theme.product_zoom_mobile))) {
        var zoomConfig = {
            responsive: true,
            zoomWindowFadeIn: 200,
            zoomWindowFadeOut: 100,
            zoomType: js_porto_vars.zoom_type,
            cursor: 'grab'
        };

        if (js_porto_vars.zoom_type == 'lens') {
            zoomConfig.scrollZoom = js_porto_vars.zoom_scroll;
            zoomConfig.lensSize = js_porto_vars.zoom_lens_size;
            zoomConfig.lensShape = js_porto_vars.zoom_lens_shape;
            zoomConfig.containLensZoom = js_porto_vars.zoom_contain_lens;
            zoomConfig.lensBorder = js_porto_vars.zoom_lens_border;
            zoomConfig.borderColour = js_porto_vars.zoom_border_color;
        }

        if (js_porto_vars.zoom_type == 'inner') {
            zoomConfig.borderSize = 0;
        } else {
            zoomConfig.borderSize = js_porto_vars.zoom_border;
        }
    }

    $.extend(theme, {

        WooProductImageSlider: {

            defaults: {
                elements: '.product-image-slider'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);

                    var $product = $this.closest('.product');
                    var $thumbs_slider = $product.find('.product-thumbs-slider');

                    $this.find('> *:first-child').imagesLoaded(function() {
                        $thumbs_slider.owlCarousel({
                            rtl: theme.rtl,
                            loop : false,
                            autoplay : false,
                            items : thumbs_count,
                            nav: false,
                            navText: ["", ""],
                            dots: false,
                            rewind: true,
                            margin: 7,
                            stagePadding: 1,
                            onInitialized: function() {
                                self.selectThumb(null, $thumbs_slider, 0);
                                if ($thumbs_slider.find('.owl-item').length >= thumbs_count)
                                    $thumbs_slider.append('<div class="thumb-nav"><div class="thumb-prev"></div><div class="thumb-next"></div></div>');
                            }
                        }).on('click', '.owl-item', function() {
                            self.selectThumb($this, $thumbs_slider, $(this).index());
                        });

                        $thumbs_slider.find('.thumb-prev').click(function(e) {
                            var currentThumb = $thumbs_slider.data('currentThumb');
                            self.selectThumb($this, $thumbs_slider, --currentThumb);
                        });
                        $thumbs_slider.find('.thumb-next').click(function(e) {
                            var currentThumb = $thumbs_slider.data('currentThumb');
                            self.selectThumb($this, $thumbs_slider, ++currentThumb);
                        });

                        if (theme.product_image_popup) {
                            var links = [];
                            var i = 0;
                            $this.find('img').each(function() {
                                var slide = {};
                                slide.title = $(this).attr('alt');
                                slide.href = $(this).attr('href');
                                slide.thumbnail = $(this).attr('src');
                                links[i] = slide;
                                i++;
                            });
                        }

                        var currentSlide = 0, count = $this.find('> *').length;

                        $this.owlCarousel({
                            rtl: theme.rtl,
                            loop : (count > 1) ? true : false,
                            autoplay : false,
                            items : 1,
                            autoHeight : true,
                            nav: true,
                            navText: ["", ""],
                            dots: false,
                            rewind: true,
                            onInitialized : function() {
                                if (theme.product_zoom && (!('ontouchstart' in document) || (('ontouchstart' in document) && theme.product_zoom_mobile))) {
                                    $this.find('img').each(function() {
                                        var $this = $(this);
                                        zoomConfig.zoomContainer = $this.parent();
                                        $this.elevateZoom(zoomConfig);
                                    });
                                }
                            },
                            onTranslate : function(event) {
                                currentSlide = event.item.index - $this.find('.cloned').length / 2;
                                self.selectThumb(null, $thumbs_slider, currentSlide);
                            },
                            onResized: function() {
                                if (theme.product_zoom && (!('ontouchstart' in document) || (('ontouchstart' in document) && theme.product_zoom_mobile))) {
                                    $this.find('img').each(function() {
                                        var $this = $(this);
                                        var src = $this.attr('src');
                                        var elevateZoom = $this.data('elevateZoom');
                                        if (typeof elevateZoom != 'undefined') {
                                            elevateZoom.startZoom();
                                            elevateZoom.swaptheimage(src, src);
                                        }
                                    });
                                }
                            }
                        });

                        $this.data('links', links);

                        if (theme.product_image_popup) {
                            var $zoom_buttons = $this.next();
                            $zoom_buttons.unbind('click').click(function(e) {
                                e.preventDefault();
                                var options = {index: currentSlide, event: e};
                                blueimp.Gallery($this.data('links'), options);
                            });
                        }
                    });
                });

                return self;
            },

            selectThumb: function($image_slider, $thumbs_slider, index) {
                if (flag) return;

                flag = true;
                var len = $thumbs_slider.find('.owl-item').length;
                index = (index + len) % len;
                if ($image_slider) {
                    $image_slider.trigger('to.owl.carousel', [index, duration, true]);
                }
                $thumbs_slider.find('.owl-item').removeClass('selected');
                $thumbs_slider.find('.owl-item:eq(' + index + ')').addClass('selected');
                $thumbs_slider.data('currentThumb', index);
                var actives = [], i = 0;
                $thumbs_slider.find('.owl-item.active').each(function() {
                    actives[i++] = $(this).index();
                });
                if ($.inArray(index, actives) == -1) {
                    if (Math.abs(index - actives[0]) > Math.abs(index - actives[actives.length - 1])) {
                        $thumbs_slider.trigger('to.owl.carousel', [(index - actives.length + 1) % len, duration, true]);
                    } else {
                        $thumbs_slider.trigger('to.owl.carousel', [index % len, duration, true]);
                    }
                }
                flag = false;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Quick View
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        WooQuickView: {

            initialize: function() {

                this.events();

                return this;
            },

            events: function() {
                var self = this;

                $(document).on('click', '.quickview', function(e) {
                    e.preventDefault();

                    var pid = $(this).attr('data-id');

                    var image_es;
                    var slider_timer;
                    var win_width = 0;

                    function resize_porto_quickview() {
                        clearTimeout(slider_timer);
                        slider_timer = setTimeout(refresh_porto_quickview, 400);
                    }

                    function refresh_porto_quickview() {
                        var $image_slider = $('.quickview-wrap-' + pid + ' .product-image-slider');
                        if ($image_slider.length) {
                            var product_image_slider = $image_slider.data('imageSlider');
                            if (product_image_slider && product_image_slider.slideController)
                                product_image_slider.slideController.locate();
                        }
                        delete slider_timer;
                    }

                    $.fancybox({
                        href : theme.ajax_url,
                        ajax : {
                            data: {
                                action: 'porto_product_quickview',
                                pid: pid
                            }
                        },
                        type : 'ajax',
                        helpers : {
                            overlay: {
                                locked: true
                            }
                        },
                        error    : '<p class="fancybox-error">' + theme.request_error + '</p>',
                        autoSize: true,
                        autoWidth : true,
                        afterShow : function() {
                            $(window).bind('resize', resize_porto_quickview);
                            setTimeout(function() {
                                theme.Tooltip.initialize($('.quickview-wrap-' + pid));
                                theme.WooProductImageSlider.initialize($('.quickview-wrap-' + pid).find('.product-image-slider'));
                            }, 200);
                        },
                        afterClose : function() {
                            $(window).unbind('resize', resize_porto_quickview);
                        }
                    });
                    return false;
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Qty Field
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        WooQtyField: {

            initialize: function() {

                this.build()
                    .events();

                return this;
            },

            build: function() {
                var self = this;

// Quantity buttons
                $( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<input type="button" value="+" class="plus" />' ).prepend( '<input type="button" value="-" class="minus" />' );

                // Target quantity inputs on product pages
                $( 'input.qty:not(.product-quantity input.qty)' ).each( function() {
                    var min = parseFloat( $( this ).attr( 'min' ) );

                    if ( min && min > 0 && parseFloat( $( this ).val() ) < min ) {
                        $( this ).val( min );
                    }
                });

                $( document ).off('click', '.plus, .minus').on( 'click', '.plus, .minus', function() {

                    // Get values
                    var $qty		= $( this ).closest( '.quantity' ).find( '.qty' ),
                        currentVal	= parseFloat( $qty.val() ),
                        max			= parseFloat( $qty.attr( 'max' ) ),
                        min			= parseFloat( $qty.attr( 'min' ) ),
                        step		= $qty.attr( 'step' );

                    // Format values
                    if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
                    if ( max === '' || max === 'NaN' ) max = '';
                    if ( min === '' || min === 'NaN' ) min = 0;
                    if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

                    // Change the value
                    if ( $( this ).is( '.plus' ) ) {

                        if ( max && ( max == currentVal || currentVal > max ) ) {
                            $qty.val( max );
                        } else {
                            $qty.val( currentVal + parseFloat( step ) );
                        }

                    } else {

                        if ( min && ( min == currentVal || currentVal < min ) ) {
                            $qty.val( min );
                        } else if ( currentVal > 0 ) {
                            $qty.val( currentVal - parseFloat( step ) );
                        }

                    }

                    // Trigger change event
                    $qty.trigger( 'change' );
                });

                return self;
            },

            events: function() {
                var self = this;

                $(document).ajaxComplete(function(event, xhr, options) {
                    self.build();
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Widget Toggle
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        WooWidgetToggle: {

            defaults: {
                elements: '.widget_product_categories .widget-title, .widget_price_filter .widget-title, .widget_layered_nav .widget-title'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    var $this = $(this);
                    $this.parent().removeClass('closed');
                    if (!$this.find('.toggle').length) {
                        $this.append('<span class="toggle"></span>');
                    }
                    $this.find('.toggle').unbind('click').click(function() {
                        if ($this.next().is(":visible")){
                            $this.parent().addClass('closed');
                        } else {
                            $this.parent().removeClass('closed');
                        }
                        $this.next().stop().slideToggle(200);
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Widget Accordion
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        WooWidgetAccordion: {

            defaults: {
                elements: '.widget_product_categories, .widget_price_filter, .widget_layered_nav'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    $(this).find('ul.children').each(function() {
                        if (!$(this).prev().hasClass('toggle')) {
                            $(this).before(
                                $('<span class="toggle"></span>').unbind('click').click(function() {
                                    if ($(this).next().is(":visible")) {
                                        $(this).parent().removeClass('open').addClass('closed');
                                    } else {
                                        $(this).parent().addClass('open').removeClass('closed');
                                    }
                                    $(this).next().stop().slideToggle(200);
                                })
                            );
                        }
                    });
                    $(this).find('li[class*="current-"]').addClass('current');
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Variation Form
(function(theme, $) {

    theme = theme || {};

    var duration = 300;

    $.extend(theme, {

        WooVariationForm: {

            initialize: function() {

                this.events();

                return this;
            },

            events: function() {
                var self = this;

                $('.variations_form').each(function() {
                    var $variation_form = $( this ),
                        $reset_variations = $variation_form.find( '.reset_variations' );

                    if ($reset_variations.css('visibility') == 'hidden')
                        $reset_variations.hide();
                });

                $( document ).on( 'check_variations', '.variations_form', function( event, exclude, focus ) {
                    var $variation_form = $( this ),
                        $reset_variations = $variation_form.find( '.reset_variations' );

                    if ($reset_variations.css('visibility') == 'hidden')
                        $reset_variations.hide();
                });

                $( document ).on( 'reset_image', '.variations_form', function(event) {
                    var $product        = $(this).closest( '.product' );
                    var $product_img    = $product.find( 'div.product-images .woocommerce-main-image' );
                    var o_src           = $product_img.attr('data-o_src');
                    var o_title         = $product_img.attr('data-o_title');
                    var o_href          = $product_img.attr('data-o_href');
                    var $thumb_img      = $product.find( '.woocommerce-main-thumb' );
                    var o_thumb_src     = $thumb_img.attr('data-o_src');

                    var $image_slider = $product.find('.product-image-slider');
                    var $thumbs_slider = $product.find('.product-thumbs-slider');

                    if ($image_slider.length) {
                        $image_slider.trigger('to.owl.carousel', [0, duration, true]);
                    }
                    if ($thumbs_slider.length) {
                        $thumbs_slider.trigger('to.owl.carousel', [0, duration, true]);
                        $thumbs_slider.find('.owl-item:eq(0)').click();
                    }

                    var links = $image_slider.data('links');

                    if ( o_src ) {
                        $product_img
                            .attr( 'src', o_src )
                            .attr( 'srcset', '' )
                            .attr( 'alt', o_title )
                            .attr( 'href', o_href );

                        $product_img.each(function() {
                            var elevateZoom = $(this).data('elevateZoom');
                            if (typeof elevateZoom != 'undefined') {
                                elevateZoom.swaptheimage($(this).attr( 'src' ), $(this).attr( 'src' ));
                            }
                        });

                        if (typeof links != 'undefined') {
                            links[0].thumbnail = o_src;
                            links[0].href = o_href;
                            links[0].title = o_title;
                        }
                    }
                    if (o_thumb_src) {
                        $thumb_img.attr( 'src', o_thumb_src );
                    }
                });

                $( document ).on( 'found_variation', '.variations_form', function(event, variation) {

                    if (typeof variation == 'undefined') {
                        return;
                    }

                    var $product              = $(this).closest( '.product' );

                    var $image_slider = $product.find('.product-image-slider');
                    var $thumbs_slider = $product.find('.product-thumbs-slider');

                    if ($image_slider.length) {
                        $image_slider.trigger('to.owl.carousel', [0, duration, true]);
                    }
                    if ($thumbs_slider.length) {
                        $thumbs_slider.trigger('to.owl.carousel', [0, duration, true]);
                        $thumbs_slider.find('.owl-item:eq(0)').click();
                    }

                    var links = $image_slider.data('links');

                    var $shop_single_image    = $product.find( 'div.product-images .woocommerce-main-image' );
                    var productimage           =  $shop_single_image.attr('data-o_src');
                    var imagetitle             =  $shop_single_image.attr('data-o_title');
                    var imagehref              =  $shop_single_image.attr('data-o_href');

                    var $shop_thumb_image = $product.find( '.woocommerce-main-thumb');
                    var thumbimage   =  $shop_thumb_image.attr('data-o_src');

                    var variation_image = variation.image_src;
                    var variation_link = variation.image_link;
                    var variation_title = variation.image_title;
                    var variation_thumb = variation.image_thumb;

                    if ( ! productimage ) {
                        productimage = ( ! $shop_single_image.attr('src') ) ? '' : $shop_single_image.attr('src');
                        $shop_single_image.attr('data-o_src', productimage );
                    }

                    if ( ! imagehref ) {
                        imagehref = ( ! $shop_single_image.attr('href') ) ? '' : $shop_single_image.attr('href');
                        $shop_single_image.attr('data-o_href', imagehref );
                    }

                    if ( ! imagetitle ) {
                        imagetitle = ( ! $shop_single_image.attr('alt') ) ? '' : $shop_single_image.attr('alt');
                        $shop_single_image.attr('data-o_title', imagetitle );
                    }

                    if ( ! thumbimage ) {
                        thumbimage = ( ! $shop_thumb_image.attr('src') ) ? '' : $shop_thumb_image.attr('src');
                        $shop_thumb_image.attr('data-o_src', thumbimage );
                    }

                    if ( variation_image ) {
                        $shop_single_image.attr( 'src', variation_image )
                        $shop_single_image.attr( 'srcset', '' )
                        $shop_single_image.attr( 'alt', variation_title )
                        $shop_single_image.attr( 'href', variation_link );
                        $shop_thumb_image.attr( 'src', variation_thumb );
                        if (typeof links != 'undefined') {
                            links[0].thumbnail = variation_image;
                            links[0].href = variation_link;
                            links[0].title = variation_title;
                        }
                    } else {
                        $shop_single_image.attr( 'src', productimage )
                        $shop_single_image.attr( 'srcset', '' )
                        $shop_single_image.attr( 'alt', imagetitle )
                        $shop_single_image.attr( 'href', imagehref );
                        $shop_thumb_image.attr( 'src', thumbimage );
                        if (typeof links != 'undefined') {
                            links[0].thumbnail = productimage;
                            links[0].href = imagehref;
                            links[0].title = imagetitle;
                        }
                    }
                    $shop_single_image.each(function() {
                        var elevateZoom = $(this).data('elevateZoom');
                        if (typeof elevateZoom != 'undefined') {
                            elevateZoom.swaptheimage($(this).attr( 'src' ), $(this).attr( 'src' ));
                        }
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Woocommerce Events
(function(theme, $) {

    /**
     Copyright (c) 2010, All Right Reserved, Wong Shek Hei @ shekhei@gmail.com
     License: GNU Lesser General Public License (http://www.gnu.org/licenses/lgpl.html)
     **/
    var expr = /[.#\w].([\S]*)/g, classexpr = /(?!(\[))(\.)[^.#[]*/g, idexpr = /(#)[^.#[]*/, tagexpr = /^[\w]+/, varexpr = /(\w+?)=(['"])([^\2$]*?)\2/, simpleselector = /^[\w]+$/, parseSelector = function (d) {
        for (var c = {sel: [], val: []}, a = [], j = !1, h = "", e = [], f = 0, m = d.length; f < m; f++) {
            var g = d.charAt(f);
            if (j)if ("\\" === g && f + 1 < d.length)e.push(d.charAt(++f)); else if (h === g)h = "", e.push(g); else if (("'" === g || '"' === g) && "" === h)h = g, e.push(g); else if ("]" === g && "" === h)c.val.push(e.join("")), e = [], j = !1; else {
                if ("]" !== g || "" !== h)"" === h && "," === g ? (c.val.push(e.join("")),
                    e = []) : e.push(g)
            } else"\\" === g && f + 1 < d.length ? j && e.push(d.charAt(++f)) : "[" === g && "" === h ? j = !0 : " " === g || "+" === g ? (c.sel = c.sel.join(""), a.push(c), "+" === g && a.push({sel: "+", val: ""}), c = {sel: [], val: []}) : " " !== g && "]" !== g && c.sel.push(g)
        }
        if (0 != c.sel.length || 0 != c.val.length)c.sel = c.sel.join(""), a.push(c);
        for (f = 0; f < a.length; f++) {
            c = a[f].sel;
            if ("+" === c)b.tag = c; else {
                var b = [];
                b.tag = tagexpr.exec(c);
                b.id = idexpr.exec(c);
                b.id && $.isArray(b.id) && (b.id = b.id[0].substr(1));
                b.tag || (b.tag = "div");
                b.vars = [];
                for (d = 0; d < a[f].val.length; d++)h =
                    a[f].val[d].indexOf("="), j = a[f].val[d].substr(0, h), h = a[f].val[d].substr(h + 1), h = h.replace(/^[\s]*[\"\']*|[\"\']*[\s]*$/g, ""), "text" === j ? b.text = h : b.vars.push([j, h]);
                c = c.match(classexpr);
                j = [];
                if (c) {
                    for (d = 0; d < c.length; d++)j.push(c[d].substr(1));
                    b.className = j.join(" ")
                }
            }
            a[f] = b
        }
        return a
    }, rmFromParent = function (d) {
        var c = d.parentNode, a = d.nextSibling;
        c.removeChild(d);
        return a ? function () {
            c.insertBefore(d, a)
        } : function () {
            c.appendChild(d)
        }
    }, nonArrVer = function (d, c) {
        var a = [], a = simpleselector.test(d) ? [
                {tag: d}
            ] : parseSelector(d),
            j = [];
        "undefined" === typeof c && (c = 1);
        for (var h = [], e = [], f = [], m = document.createElement("div"), g = 0, b = 0; b < a.length; b++) {
            if ("+" == a[b].tag)e = f.slice(), --g; else {
                for (var l = 0; l < c; l++)if ("input" == a[b].tag) {
                    var k = [];
                    k.push("<" + a[b].tag);
                    a[b].id && k.push("id='" + a[b].id + "'");
                    a[b].className && (k.push("class='" + a[b].className), b + 1 === a.length && k.push(lastClass), k.push("'"));
                    if (a[b].vars)for (var n = 0; n < a[b].vars.length; n++)k.push(a[b].vars[n][0] + "='" + a[b].vars[n][1] + "'");
                    a[b].text && k.push("value='" + a[b].text + "'");
                    k.push("/>");
                    f[l] = e[l];
                    e[l] ? (e[l].innerHTML += k.join(" "), e[l] = e[l].lastChild) : (m.innerHTML = k.join(" "), e[l] = m.removeChild(m.firstChild))
                } else {
                    k = document.createElement(a[b].tag);
                    if (a[b].vars)for (n = 0; n < a[b].vars.length; n++)k.setAttribute(a[b].vars[n][0], a[b].vars[n][1]);
                    a[b].id && (k.id = a[b].id);
                    a[b].className && (k.className = a[b].className);
                    a[b].text && k.appendChild(document.createTextNode(a[b].text));
                    f[l] = e[l];
                    e[l] = e[l] ? e[l].appendChild(k) : k
                }
                g++ || Array.prototype.push.apply(h, e)
            }
            j =
                $.merge(j, e)
        }
        return $(h)
    }, arrVer = function (d, c, a) {
        for (var j = d.match(/%[^%]*%/g) || [], h = [], e = 0; e < c.length; e++) {
            for (var f = d, m = 0; m < j.length; m++)var g = j[m].substr(1, j[m].length - 2), f = f.replace(j[m], c[e][g]);
            h = $.merge(h, nonArrVer(f, a))
        }
        return $(h)
    };

    $.porto_jseldom = function (d) {
        if (2 == arguments.length && $.isPlainObject(arguments[1]))return arrVer.apply(this, [arguments[0], [arguments[1]]]);
        if (1 == arguments.length || 2 == arguments.length && !$.isArray(arguments[1]))return nonArrVer.apply(this, arguments);
        if (2 == arguments.length)return arrVer.apply(this, arguments)
    };

    var $supports_html5_storage;
    try {
        $supports_html5_storage = ( 'sessionStorage' in window && window.sessionStorage !== null );

        window.sessionStorage.setItem( 'wc', 'test' );
        window.sessionStorage.removeItem( 'wc' );
    } catch( err ) {
        $supports_html5_storage = false;
    }

    theme = theme || {};

    $.extend(theme, {

        WooEvents: {

            initialize: function() {

                this.events();

                return this;
            },

            events: function() {
                var self = this;

                // refresh cart fragment
                self.refreshCartFragment();

                // add ajax cart loading
                $(document).on('click', '.add_to_cart_button', function(e) {
                    var $this = $(this);
                    if ( $this.is('.product_type_simple') ) {
                        if ( $this.attr('data-product_id') ) {
                            $this.addClass('product-adding');
                        }
                    }
                });
                $(document).on('click', 'span.add_to_cart_button', function(e) {
                    var $this = $(this);
                    if ( $this.is('.product_type_simple') ) {
                        if ( !$this.attr('data-product_id') ) {
                            window.location.href = $this.attr('href');
                        }
                    } else {
                        window.location.href = $this.attr('href');
                    }
                });

                $('body').bind('added_to_cart', function() {
                    $('ul.products li.product .added_to_cart').remove();
                    theme.Tooltip.initialize();
                    self.initAjaxRemoveCartItem();
                });

                $(document.body).bind('wc_fragments_refreshed wc_fragments_loaded', function() {
                    self.refreshCartFragment();
                });

                // view cart link
                $(document).on( 'click', '.product-image .viewcart', function( e ){
                    var link = $(this).attr('data-link');
                    window.location.href = link;
                    e.preventDefault();
                });

                // add added cart
                $( document ).on( 'added_to_cart', 'body', function(event) {
                    $('.add_to_cart_button.product-adding').each(function() {
                        var $link = $(this);
                        $link.removeClass('product-adding');
                        $link.closest('.product').find('.viewcart').addClass('added');
                    });
                });

                if (typeof yith_wcan != 'undefined') {
                    yith_wcan.container = '.archive-products .products';
                    yith_wcan.pagination = '.shop-loop-before';
                    yith_wcan.result_count = '.shop-loop-after';
                }

                /* yith ajax navigation callback */
                $(document).on('click', '.yith-wcan a', function(e){
                    // add price filter loading
                    $('.yith-woo-ajax-navigation, .yith-wcan-list-price-filter').addClass('loading');
                    if (typeof yith_wcan != 'undefined') {
                        self.scrollToProductsContainer($(yith_wcan.container));
                    }
                });
                $(document).on('ready yith-wcan-ajax-filtered', function() {
                    if (typeof yith_wcan != 'undefined') {
                        if ($(yith_wcan.container).find('.product').length) {
                            $(yith_wcan.pagination).show();
                            $(yith_wcan.result_count).show();
                        } else {
                            $(yith_wcan.pagination).hide();
                            $(yith_wcan.result_count).hide();
                        }
                    }
                    theme.WooWidgetToggle.initialize();
                    theme.WooWidgetAccordion.initialize();
                    theme.WooGridListToggle.initialize();
                    theme.Tooltip.initialize();

                    if ($.cookie('gridcookie')) {
                        $('.archive-products .products').addClass($.cookie('gridcookie'));
                    }

                    $( '.woocommerce-ordering' ).off( 'change', 'select.orderby' ).on( 'change', 'select.orderby', function() {
                        $( this ).closest( 'form' ).submit();
                    });

                    // category ajax
                    self.refreshPriceSlider();
                    self.categoryAjax();

                    var scroll_to;
                });

                // wcml currency switcher
                $('.wcml-switcher li').on('click', function(){
                    if ($(this).parent().attr('disabled') == 'disabled')
                        return;
                    var currency = $(this).attr('rel');
                    self.loadCurrency(currency);
                });

                // woocommerce currency switcher
                $('.woocs-switcher li').on('click', function(){
                    if ($(this).parent().attr('disabled') == 'disabled')
                        return;
                    var currency = $(this).attr('rel');
                    self.loadWoocsCurrency(currency);
                });

                // category ajax
                self.categoryAjax();

                // product filter ajax
                if (theme.prdctfltr_ajax) {
                    // select count
                    $(document).on( 'change', '.woocommerce-viewing select.count', function() {
                        $( this ).closest( 'form' ).submit();
                    });
                    // page number
                    $(document).on( 'click', '.woocommerce-pagination a.page-numbers', function(e) {
                        self.scrollToProductsContainer($('.shop-loop-before'));
                    });
                }

                return self;
            },

            setCartHash: function(cart_hash) {
                if ( $supports_html5_storage ) {
                    localStorage.setItem( 'wc_cart_hash', cart_hash );
                    sessionStorage.setItem( 'wc_cart_hash', cart_hash );
                }
            },

            setCartCreationTimestamp: function() {
                if ( $supports_html5_storage ) {
                    sessionStorage.setItem( 'wc_cart_created', ( new Date() ).getTime() );
                }
            },

            updateCartFragment: function(data) {
                var self = this;
                if (data && data.fragments) {
                    var fragments = data.fragments;
                    var cart_hash = data.cart_hash;

                    $.each(fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                    if ( typeof wc_cart_fragments_params === 'undefined' ) {
                        return;
                    }
                    /* Storage Handling */
                    if ( $supports_html5_storage ) {
                        var prev_cart_hash = sessionStorage.getItem( 'wc_cart_hash' );

                        if ( prev_cart_hash === null || prev_cart_hash === undefined || prev_cart_hash === '' ) {
                            self.setCartCreationTimestamp();
                        }
                        sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( fragments ) );
                        self.setCartHash( cart_hash );
                    }
                }
            },

            refreshCartFragment: function() {
                var self = this;

                self.initAjaxRemoveCartItem();
                if ( $.cookie( 'woocommerce_items_in_cart' ) > 0 ) {
                    $( '.hide_cart_widget_if_empty' ).closest( '.widget_shopping_cart' ).show();
                } else {
                    $( '.hide_cart_widget_if_empty' ).closest( '.widget_shopping_cart' ).hide();
                }
            },

            initAjaxRemoveCartItem: function() {
                var self = this;

                $('#mini-cart .cart_list').scrollbar();
                $(document).off('click', '.widget_shopping_cart .remove-product, .shop_table.cart .remove-product').on('click', '.widget_shopping_cart .remove-product, .shop_table.cart .remove-product', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var cart_id = $this.data("cart_id");
                    var product_id = $this.data("product_id");
                    $this.closest('li').find('.ajax-loading').show();

                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: theme.ajax_url,
                        data: {
                            action: "porto_cart_item_remove",
                            cart_id: cart_id
                        },
                        success: function( response ) {
                            self.updateCartFragment(response);
                            $( document.body ).trigger( 'wc_fragments_refreshed' );
                            $('.viewcart-' + product_id).removeClass('added');
                            $('.porto_cart_item_' + cart_id).remove();

                            var this_page = window.location.toString();
                            this_page = this_page.replace( 'add-to-cart', 'added-to-cart' );

                            var item_count = $(response.fragments['div.widget_shopping_cart_content']).find('.mini_cart_item').length;

                            // Block widgets and fragments
                            if ( item_count == 0 && ($('body').hasClass('woocommerce-cart') || $('body').hasClass('woocommerce-checkout')) ) {
                                $( '.page-content' ).fadeTo( '400', '0.6' ).block({
                                    message: null,
                                    overlayCSS: {
                                        opacity: 0.6
                                    }
                                });
                            } else {
                                $( '.shop_table.cart, .shop_table.review-order, .updating, .cart_totals' ).fadeTo( '400', '0.6' ).block({
                                    message: null,
                                    overlayCSS: {
                                        opacity: 0.6
                                    }
                                });
                            }

                            // Unblock
                            $( '.widget_shopping_cart, .updating' ).stop( true ).css( 'opacity', '1' ).unblock();

                            // Cart page elements
                            if ( item_count == 0 && ($('body').hasClass('woocommerce-cart') || $('body').hasClass('woocommerce-checkout')) ) {
                                $( '.page-content' ).load( this_page + ' .page-content:eq(0) > *', function() {
                                    $( '.page-content' ).stop( true ).css( 'opacity', '1' ).unblock();
                                });
                            } else {
                                $( '.shop_table.cart' ).load( this_page + ' .shop_table.cart:eq(0) > *', function() {
                                    $( '.shop_table.cart' ).stop( true ).css( 'opacity', '1' ).unblock();
                                });

                                $( '.cart_totals' ).load( this_page + ' .cart_totals:eq(0) > *', function() {
                                    $( '.cart_totals' ).stop( true ).css( 'opacity', '1' ).unblock();
                                });

                                // Checkout page elements
                                $( '.shop_table.review-order' ).load( this_page + ' .shop_table.review-order:eq(0) > *', function() {
                                    $( '.shop_table.review-order' ).stop( true ).css( 'opacity', '1' ).unblock();
                                });
                            }
                        }
                    });

                    return false;
                });
            },

            loadCurrency : function(currency) {
                $('.wcml-switcher').attr('disabled', 'disabled');
                $('.wcml-switcher').append('<li class="loading"></li>');
                var data = {action: 'wcml_switch_currency', currency: currency}
                $.ajax({
                    type : 'post',
                    url : theme.ajax_url,
                    data : {
                        action: 'wcml_switch_currency',
                        currency : currency
                    },
                    success: function(response) {
                        $('.wcml-switcher').removeAttr('disabled');
                        $('.wcml-switcher').find('.loading').remove();
                        window.location = window.location.href;
                    }
                });
            },

            loadWoocsCurrency : function(currency) {
                $('.woocs-switcher').attr('disabled', 'disabled');
                $('.woocs-switcher').append('<li class="loading"></li>');
                var l = window.location.href;
                l = l.split('?');
                l = l[0];
                var string_of_get = '?';
                woocs_array_of_get.currency = currency;
                
                if (Object.keys(woocs_array_of_get).length > 0) {
                    jQuery.each(woocs_array_of_get, function (index, value) {
                        string_of_get = string_of_get + "&" + index + "=" + value;
                    });
                }
                window.location = l + string_of_get;
            },

            categoryAjax : function() {
                var self = this;

                // add class in price filter widget
                $('.widget_price_filter').addClass('yith-wcan-list-price-filter');

                if (theme.category_ajax) {

                    // order by ajax
                    $( '.woocommerce-ordering' ).off( 'change', 'select.orderby' ).on( 'change', 'select.orderby', function(e) {
                        e.preventDefault();

                        var $this = $(this),
                            $form = $this.closest('form'),
                            href = '?' + $form.serialize();

                        self.categoryAjaxProcess(href);
                    });

                    // view ajax
                    $( '.woocommerce-viewing' ).off( 'change', 'select.count' ).on( 'change', 'select.count', function(e) {
                        e.preventDefault();

                        var $this = $(this),
                            $form = $this.closest('form'),
                            href = '?' + $form.serialize();

                        self.categoryAjaxProcess(href);
                    });

                    // pagination ajax
                    $( '.woocommerce-pagination' ).off( 'click', 'a.page-numbers' ).on( 'click', 'a.page-numbers', function(e) {
                        e.preventDefault();

                        var href = this.href;

                        self.categoryAjaxProcess(href);
                    });

                    // price filter ajax
                    $( '.widget_price_filter .price_slider_wrapper').off( 'click', '.button').on( 'click', '.button', function(e) {
                        e.preventDefault();

                        var $this = $(this),
                            $form = $this.closest('form'),
                            action = $form.attr('action'),
                            href = action + '?' + $form.serialize(),
                            $count = $('.woocommerce-viewing select.count');

                        if ($count.length) {
                            var count = $('.woocommerce-viewing select.count').val();
                            if (count != $count.find('option:not([disabled]):first').val()) {
                                href += '&count=' + count;
                            }
                        }

                        $('.widget_price_filter').removeClass('yith-wcan-list-price-filter');

                        self.categoryAjaxProcess(href);
                    });

                    // reset filters
                    $( '.yith-woo-ajax-reset-navigation').on( 'click', '.yith-wcan-reset-navigation', function(e) {

                    });
                }

                return self;
            },

            categoryAjaxProcess : function(href) {

                var self = this;

                var shop_before = '.shop-loop-before';
                var shop_after = '.shop-loop-after';
                var shop_container = '.archive-products .products';
                var shop_info = '.archive-products .woocommerce-info';

                //loading
                $(shop_before).hide();
                $(shop_after).hide();
                if ($(shop_container).length)
                    $(shop_container).html('').addClass('yith-wcan-loading');
                else
                    $(shop_info).html('').addClass('yith-wcan-loading products');

                self.scrollToProductsContainer($(shop_container));

                $('.yith-woo-ajax-navigation, .yith-wcan-list-price-filter').addClass('loading');

                $.ajax({
                    url    : href,
                    success: function (response) {
                        var $parent = $(shop_container).parent();

                        // products container
                        if ($(response).find(shop_container).length > 0) {
                            $parent.html($(response).find(shop_container));
                        } else {
                            $parent.html($(response).find('.woocommerce-info'));
                            $parent.find('.woocommerce-info').addClass('products');
                        }

                        // top toolbar
                        if ($(response).find(shop_before).length > 0) {
                            if ($(shop_before).length == 0) {
                                $.porto_jseldom(shop_before).insertAfter($(shop_container));
                            }

                            $(shop_before)
                                .html($(response).find(shop_before).html())
                                .show();
                        } else {
                            $(shop_before).empty();
                        }

                        // bottom toolbar
                        if ($(response).find(shop_after).length > 0) {
                            $(shop_after).html($(response).find(shop_after).html()).show();
                        }


                        var widget_reload = function (t) {
                            var id = t.attr('id');
                            t.html($(response).find('#' + id).html());

                            if (t.text() == '') {
                                t.hide();
                            } else {
                                t.show();
                            }
                        };

                        //load new widgets
                        $('.yith-woo-ajax-navigation').add('.yith-wcan-sort-by').add('.yith-wcan-stock-on-sale').add('.yith-wcan-list-price-filter').each(function () {
                            var t = $(this);
                            widget_reload(t);
                        });

                        //update browser history (IE doesn't support it)
                        if (!navigator.userAgent.match(/msie/i)) {
                            window.history.pushState({"pageTitle": response.pageTitle}, "", href);
                        }

                        //trigger ready event
                        $(document).trigger("ready");
                        $(document).trigger("yith-wcan-ajax-filtered");
                    }
                });
            },

            scrollToProductsContainer : function( $container ) {

                if ($container.length) {
                    var winWidth = $(window).width();
                    if (winWidth <= 991 - theme.getScrollbarWidth()) {
                        $('.filter-overlay').click();
                    }
                    var delay = 1;
                    if ($(window).scrollTop() < theme.StickyHeader.sticky_pos) {
                        delay += 250;
                        $('html, body').animate({
                            scrollTop: theme.StickyHeader.sticky_pos + 1
                        }, 200);
                    }
                    setTimeout(function() {
                        $('html, body').stop().animate({
                            scrollTop: $container.offset().top - theme.StickyHeader.sticky_height - theme.adminBarHeight() - theme.HashScroll.sticky_nav_height - 15
                        }, 600, 'easeOutQuad');
                    }, delay);
                }
            },

            removeParameterFromUrl : function(url, parameter) {
                return url
                    .replace(new RegExp('[?&]' + parameter + '=[^&#]*(#.*)?$'), '$1')
                    .replace(new RegExp('([?&])' + parameter + '=[^&]*&'), '$1');
            },

            refreshPriceSlider : function() {

                var $price_slider = $('.price_slider');

                if ($price_slider.length) {
                    // woocommerce_price_slider_params is required to continue, ensure the object exists
                    if ( typeof woocommerce_price_slider_params === 'undefined' ) {
                        return false;
                    }

                    // Get markup ready for slider
                    $( 'input#min_price, input#max_price' ).hide();
                    $( '.price_slider, .price_label' ).show();

                    // Price slider uses jquery ui
                    var min_price = $( '.price_slider_amount #min_price' ).data( 'min' ),
                        max_price = $( '.price_slider_amount #max_price' ).data( 'max' ),
                        current_min_price = parseInt( $( '.price_slider_amount #min_price').val() ? $( '.price_slider_amount #min_price').val() : min_price, 10 ),
                        current_max_price = parseInt( $( '.price_slider_amount #max_price').val() ? $( '.price_slider_amount #max_price').val() : max_price, 10 );

                    $( '.price_slider' ).slider({
                        range: true,
                        animate: true,
                        min: min_price,
                        max: max_price,
                        values: [ current_min_price, current_max_price ],
                        create: function() {

                            $( '.price_slider_amount #min_price' ).val( current_min_price );
                            $( '.price_slider_amount #max_price' ).val( current_max_price );

                            $( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
                        },
                        slide: function( event, ui ) {

                            $( 'input#min_price' ).val( ui.values[0] );
                            $( 'input#max_price' ).val( ui.values[1] );

                            $( document.body ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
                        },
                        change: function( event, ui ) {

                            $( document.body ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );
                        }
                    });
                }

                // remove filter loading
                $('.yith-woo-ajax-navigation, .yith-wcan-list-price-filter').removeClass('loading');
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Circle Slider
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        CircleSlider: {

            defaults: {
                elements: '.concept-slideshow'
            },

            initialize: function($elements) {
                this.$elements = ($elements || $(this.defaults.elements));

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                self.$elements.each(function() {
                    if ($.isFunction($.fn.flipshow)) {
                        var $this = $(this);

                        $this.flipshow();

                        function circleFlip() {
                            $this.data().flipshow._navigate($this.find('div.fc-right span:first'), 'right');
                            setTimeout(circleFlip, 3000);
                        }

                        setTimeout(circleFlip, 3000);
                    }
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Porto Carousel
(function(theme, $) {

    theme = theme || {};

    $.extend(theme, {

        PortoCarousel: {

            defaults: {
                carousel: '.porto-carousel',
                options: {}
            },

            initialize: function(carousel, options) {
                this.carousel = (carousel || this.defaults.carousel);
                this.options = (options || this.defaults.options);

                this.build();

                return this;
            },

            build: function() {
                var self = this;

                $(self.carousel).each(function() {
                    var $this = $(this);

                    var cols_lg = $(this).data('cols-lg'), cols_md = $(this).data('cols-md'), cols_sm = $(this).data('cols-sm');
                    var single = $(this).data('single');

                    var responsive = {}, items, scrollWidth = theme.getScrollbarWidth(), count = $(this).find('> *').length;

                    if (single) {
                        items = 1;
                    } else {
                        items = cols_lg ? cols_lg : 1;
                        var w_xs = 481 - scrollWidth, w_md = 768 - scrollWidth, w_lg = 992 - scrollWidth;
                        if (cols_lg) responsive[w_lg] = { items: cols_lg, loop: (theme.slider_loop && count > cols_lg) ? true : false };
                        if (cols_md) responsive[w_md] = { items: cols_md, loop: (theme.slider_loop && count > cols_md) ? true : false };
                        if (cols_sm) responsive[w_xs] = { items: cols_sm, loop: (theme.slider_loop && count > cols_sm) ? true : false };
                        responsive[0] = { items: 1, loop: (theme.slider_loop && count > 1) ? true : false };
                    }

                    if (!$(this).hasClass('show-nav-title') && theme.slider_nav_hover)
                        $(this).addClass('show-nav-hover');

                    $this.find('> *:first-child').imagesLoaded(function() {
                        $this.owlCarousel($.extend({}, theme.owlConfig, {
                            items: items,
                            loop: (theme.slider_loop && count > items) ? true : false,
                            responsive: responsive,
                            autoHeight: false,
                            stagePadding: 0
                        }, self.options));
                    });
                });

                return self;
            }
        }

    });

}).apply(this, [window.theme, jQuery]);


// Init Theme

(function (theme, $, undefined) {
    "use strict";

    if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
        $('body').addClass('safari');
    }

    function porto_init() {

        // Mega Menu
        if (typeof theme.MegaMenu !== 'undefined') {
            theme.MegaMenu.initialize();
        }

        // Sidebar Menu
        if (typeof theme.SidebarMenu !== 'undefined') {
            theme.SidebarMenu.initialize();
        }

        // Accordion Menu
        if (typeof theme.AccordionMenu !== 'undefined') {
            theme.AccordionMenu.initialize();
        }

        // Sticky Header
        if (typeof theme.StickyHeader !== 'undefined') {
            theme.StickyHeader.initialize();
        }

        // Side Navigation
        if (typeof theme.SideNav !== 'undefined') {
            theme.SideNav.initialize();
        }

        // Search
        if (typeof theme.Search !== 'undefined') {
            theme.Search.initialize();
        }

        // Panel
        if (typeof theme.Panel !== 'undefined') {
            theme.Panel.initialize();
        }

        // Sticky
        if (typeof theme.Sticky !== 'undefined') {
            theme.Sticky.initialize();
        }

        // Hash Scroll
        if (typeof theme.HashScroll !== 'undefined') {
            theme.HashScroll.initialize();
        }

        // FlickrZoom
        if (typeof theme.FlickrZoom !== 'undefined') {
            theme.FlickrZoom.initialize();
        }

        // Way Points
        if (typeof theme.WayPoints !== 'undefined') {
            theme.WayPoints.initialize();
        }

        // Scroll to Top
        if (typeof theme.ScrollToTop !== 'undefined') {
            theme.ScrollToTop.initialize();
        }

        // Fit Videos
        if (typeof theme.FitVideos !== 'undefined') {
            theme.FitVideos.initialize();
        }

        // Refresh Video Sizes
        if (typeof theme.RefreshVideoSizes !== 'undefined') {
            theme.RefreshVideoSizes.initialize();
        }

        // Accordion
        if (typeof theme.Accordion !== 'undefined') {
            theme.Accordion.initialize();
        }

        // Toggle
        if (typeof theme.Toggle !== 'undefined') {
            theme.Toggle.initialize();
        }

        // Tooltip
        if (typeof theme.Tooltip !== 'undefined') {
            theme.Tooltip.initialize();
        }

        // Slideshow
        if (typeof theme.Slideshow !== 'undefined') {
            // Post Slideshow
            theme.Slideshow.initialize($('body'), '.post-slideshow', theme.post_zoom);
            // Portfolio Slideshow
            theme.Slideshow.initialize($('body'), '.portfolio-slideshow', theme.portfolio_zoom);
            // Member Slideshow
            theme.Slideshow.initialize($('body'), '.member-slideshow', theme.member_zoom);
            // Page Slideshow
            theme.Slideshow.initialize($('body'), '.page-slideshow', theme.page_zoom);
        }

        // Carousel
        if (typeof theme.Carousel !== 'undefined') {
            // Post Carousel
            theme.Carousel.initialize('.post-carousel', theme.post_zoom);
            // Portfolio Carousel
            theme.Carousel.initialize('.portfolio-carousel', theme.portfolio_zoom);
            // Member Carousel
            theme.Carousel.initialize('.member-carousel', theme.member_zoom);
            // Product Carousel
            theme.Carousel.initialize('.product-carousel', theme.member_zoom);
        }

        // Grid
        if (typeof theme.Grid !== 'undefined') {
            // Posts Grid
            theme.Grid.initialize($('.posts-grid .grid'), '.post');
            // Portfolis Grid
            theme.Grid.initialize($('.portfolios-grid .portfolio-row'), '.portfolio', function() {
                setTimeout(function() {
                    theme.FilterZoom.initialize($('.portfolios-grid'), theme.portfolio_zoom);
                }, 400);
            });
            // Members Grid
            theme.Grid.initialize($('.page-members .member-row'), '.member', function() {
                setTimeout(function() {
                    theme.FilterZoom.initialize($('.page-members'), theme.member_zoom);
                }, 400);
            });
        }

        // Posts Infinite
        if (typeof theme.PostsInfinite !== 'undefined') {
            theme.PostsInfinite.initialize();
        }

        // Portfolios Infinite
        if (typeof theme.PortfoliosInfinite !== 'undefined') {
            theme.PortfoliosInfinite.initialize();
        }

        // Portfolio Like
        if (typeof theme.PortfolioLike !== 'undefined') {
            theme.PortfolioLike.initialize();
        }

        // Portfolio Filter
        if (typeof theme.PortfolioFilter !== 'undefined') {
            theme.PortfolioFilter.initialize();
        }

        // Members Infinite
        if (typeof theme.MembersInfinite !== 'undefined') {
            theme.MembersInfinite.initialize();
        }

        // Member Filter
        if (typeof theme.MemberFilter !== 'undefined') {
            theme.MemberFilter.initialize();
        }

        // FAQs Infinite
        if (typeof theme.FaqsInfinite !== 'undefined') {
            theme.FaqsInfinite.initialize();
        }

        // FAQ Filter
        if (typeof theme.FaqFilter !== 'undefined') {
            theme.FaqFilter.initialize();
        }

        // Filter Zooms
        if (typeof theme.FilterZoom !== 'undefined') {
            // Portfolio Filter Zoom
            theme.FilterZoom.initialize($('.portfolios-grid, .portfolios-timeline'), theme.portfolio_zoom);
            // Member Filter Zoom
            theme.FilterZoom.initialize($('.page-members'), theme.member_zoom);
        }

        // Visual Composer Image Zoom
        if (typeof theme.VcImageZoom !== 'undefined') {
            theme.VcImageZoom.initialize();
        }

        // Preview Image
        if (typeof theme.PreviewImage !== 'undefined') {
            theme.PreviewImage.initialize();
        }

        // Sort Filter
        if (typeof theme.SortFilter !== 'undefined') {
            theme.SortFilter.initialize();
        }

        // Woocommerce Grid List Toggle, View By
        if (typeof theme.WooGridListToggle !== 'undefined') {
            theme.WooGridListToggle.initialize();
        }

        // Woocommerce Category Filter
        if (typeof theme.WooCategoryFilter !== 'undefined') {
            theme.WooCategoryFilter.initialize();
        }

        // Woocommerce Products Slider
        if (typeof theme.WooProductsSlider !== 'undefined') {
            theme.WooProductsSlider.initialize();
        }

        // Woocommerce Products Slider
        if (typeof theme.WooProductsSlider !== 'undefined') {
            theme.WooProductsSlider.initialize();
        }

        // Woocommerce Qty Field
        if (typeof theme.WooQtyField !== 'undefined') {
            theme.WooQtyField.initialize();
        }

        // Woocommerce Quick View
        if (typeof theme.WooQuickView !== 'undefined') {
            theme.WooQuickView.initialize();
        }

        // Woocommerce Widget Toggle
        if (typeof theme.WooWidgetToggle !== 'undefined') {
            theme.WooWidgetToggle.initialize();
        }

        // Woocommerce Widget Accordion Toggle
        if (typeof theme.WooWidgetAccordion !== 'undefined') {
            theme.WooWidgetAccordion.initialize();
        }

        // Woocommerce Events
        if (typeof theme.WooEvents !== 'undefined') {
            theme.WooEvents.initialize();
        }

        // Circle Slider
        if (typeof theme.CircleSlider !== 'undefined') {
            theme.CircleSlider.initialize();
        }

        // Porto Carousel
        if (typeof theme.PortoCarousel !== 'undefined') {
            theme.PortoCarousel.initialize();
        }

        // bootstrap dropdown hover
        $('[data-toggle="dropdown"]').dropdownHover();

        // bootstrap popover
        $("[data-toggle='popover']").popover();

        // disable default hide dropdown popup
        if (!('ontouchstart' in document)) {
            $('.mini-cart').on('hide.bs.dropdown', function () {
                return false;
            });
        }

        /*
         Move Cloud
         */
        var clouds = $('.cloud');
        if (clouds.length) {
            clouds.each(function() {
                var cloud = $(this);
                var moveCloud = function() {
                    cloud.animate({
                        'top': '+=20px'
                    }, 3000, 'linear', function() {
                        cloud.animate({
                            'top': '-=20px'
                        }, 3000, 'linear', function() {
                            moveCloud();
                        });
                    });
                };

                moveCloud();
            });
        }

        /*
         Match Height
          */

        // Featured Boxes
        $('.tabs-simple .featured-box .box-content').matchHeight();
        $('.porto-content-box .featured-box .box-content').matchHeight();
        $('.vc_general.vc_cta3').matchHeight();
        $('.match-height').matchHeight();

        /* WhatsApp Sharing */
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('.share-whatsapp').css('display', 'inline-block');
        }
        $(document).ajaxComplete(function(event, xhr, options) {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                $('.share-whatsapp').css('display', 'inline-block');
            }
        });

        /* Add Ege Browser Class */
        var ua = window.navigator.userAgent;
        var ie12 = ua.indexOf('Edge/') > 0;
        if (ie12) $('html').addClass('ie12');

        /* Add wishlist popup */
        if ( $( '#yith-wcwl-popup-message' ).length == 0 ) {
            var message_div = $( '<div>' )
                    .attr( 'id', 'yith-wcwl-message' ),
                popup_div = $( '<div>' )
                    .attr( 'id', 'yith-wcwl-popup-message' )
                    .html( message_div )
                    .hide();

            $( 'body' ).prepend( popup_div );
        }

        /* Change search form values */
        var $search_form_texts = $('.searchform .text input');
        var $search_form_cats = $('.searchform .cat');
        $search_form_texts.on('change', function() {
            var $this = $(this);
            var val = $this.val();
            $search_form_texts.each(function() {
                if ($this != $(this)) $(this).val(val);
            });
        });
        $search_form_cats.on('change', function() {
            var $this = $(this);
            var val = $this.val();
            $search_form_cats.each(function() {
                if ($this != $(this)) $(this).val(val);
            });
        });

        /* Portfolios Shortcode Pagination */
        $(document).on('click', '.porto-portfolios .pagination a', function(e) {
            var $this = $(this);
            var url = $this.attr('href');
            var shortcode_id = $this.closest('.porto-portfolios').find('.shortcode-id').val();
            var $container = $this.closest('.porto-portfolios' + shortcode_id);

            if (url) {
                e.preventDefault();

                $container.addClass('porto-ajax-loading');

                var winWidth = $(window).width();
                var delay = 1;
                if ($(window).scrollTop() < theme.StickyHeader.sticky_pos) {
                    delay += 250;
                    $('html, body').animate({
                        scrollTop: theme.StickyHeader.sticky_pos + 1
                    }, 200);
                }
                setTimeout(function() {
                    $('html, body').stop().animate({
                        scrollTop: $container.offset().top - theme.StickyHeader.sticky_height - theme.adminBarHeight() - theme.HashScroll.sticky_nav_height - 14
                    }, 600, 'easeOutQuad');
                }, delay);

                $.ajax({
                    type : 'post',
                    url : url,
                    success: function(response) {
                        var $response_container = $('<div>' + response + '</div>').find('.porto-portfolios'+shortcode_id);
                        $container.html($response_container.html());
                        theme.Slideshow.initialize($container, '.portfolio-slideshow', theme.portfolio_zoom);
                        theme.Grid.initialize($container.find('.portfolios-grid .portfolio-row'), '.portfolio', function() {
                            setTimeout(function() {
                                theme.FilterZoom.initialize($container.find('.portfolios-grid'), theme.portfolio_zoom);
                            }, 400);
                        });
                        theme.WayPoints.initialize($container);
                        theme.Tooltip.initialize($container);
                        theme.FitVideos.initialize($container);
                        theme.PortfolioFilter.initialize($container.find('.portfolio-filter'));
                        theme.WayPoints.build();
                    }
                }).always(function() {
                    $container.removeClass('porto-ajax-loading');
                });

                return false;
            }
        });

        /* Members Shortcode Pagination */
        $(document).on('click', '.porto-members .pagination a', function(e) {
            var $this = $(this);
            var url = $this.attr('href');
            var shortcode_id = $this.closest('.porto-members').find('.shortcode-id').val();
            var $container = $this.closest('.porto-members' + shortcode_id);

            if (url) {
                e.preventDefault();

                $container.addClass('porto-ajax-loading');

                var winWidth = $(window).width();
                var delay = 1;
                if ($(window).scrollTop() < theme.StickyHeader.sticky_pos) {
                    delay += 250;
                    $('html, body').animate({
                        scrollTop: theme.StickyHeader.sticky_pos + 1
                    }, 200);
                }
                setTimeout(function() {
                    $('html, body').stop().animate({
                        scrollTop: $container.offset().top - theme.StickyHeader.sticky_height - theme.adminBarHeight() - theme.HashScroll.sticky_nav_height - 14
                    }, 600, 'easeOutQuad');
                }, delay);

                $.ajax({
                    type : 'post',
                    url : url,
                    success: function(response) {
                        var $response_container = $('<div>' + response + '</div>').find('.porto-members'+shortcode_id);
                        $container.html($response_container.html());
                        theme.Grid.initialize($container.find('.page-members .member-row'), '.member', function() {
                            setTimeout(function() {
                                theme.FilterZoom.initialize($container.find('.page-members'), theme.member_zoom);
                            }, 400);
                        });
                        theme.Tooltip.initialize($container);
                        theme.FitVideos.initialize($container);
                        theme.MemberFilter.initialize($container.find('.member-filter'));
                        theme.WayPoints.build();
                    }
                }).always(function() {
                        $container.removeClass('porto-ajax-loading');
                    });

                return false;
            }
        });

        /* FAQs Shortcode Pagination */
        $(document).on('click', '.porto-faqs .pagination a', function(e) {
            var $this = $(this);
            var url = $this.attr('href');
            var shortcode_id = $this.closest('.porto-faqs').find('.shortcode-id').val();
            var $container = $this.closest('.porto-faqs' + shortcode_id);

            if (url) {
                e.preventDefault();

                $container.addClass('porto-ajax-loading');

                var winWidth = $(window).width();
                var delay = 1;
                if ($(window).scrollTop() < theme.StickyHeader.sticky_pos) {
                    delay += 250;
                    $('html, body').animate({
                        scrollTop: theme.StickyHeader.sticky_pos + 1
                    }, 200);
                }
                setTimeout(function() {
                    $('html, body').stop().animate({
                        scrollTop: $container.offset().top - theme.StickyHeader.sticky_height - theme.adminBarHeight() - theme.HashScroll.sticky_nav_height - 14
                    }, 600, 'easeOutQuad');
                }, delay);

                $.ajax({
                    type : 'post',
                    url : url,
                    success: function(response) {
                        var $response_container = $('<div>' + response + '</div>').find('.porto-faqs'+shortcode_id);
                        $container.html($response_container.html());
                        theme.Tooltip.initialize($container);
                        theme.Toggle.initialize($container.find('.toggle'));
                        theme.FitVideos.initialize($container);
                        theme.FaqFilter.initialize($container.find('.faq-filter'));
                        theme.WayPoints.build();
                    }
                }).always(function() {
                        $container.removeClass('porto-ajax-loading');
                    });

                return false;
            }
        });

        // fixed video
        $('.video-fixed').each(function() {
            var $this = $(this);
            var $video = $this.find('video, iframe');
            if ($video.length) {
                $(window).on('scroll', function() {
                    var offset = $(window).scrollTop() - $this.position().top + theme.adminBarHeight();
                    $video.css("cssText", "top: " + offset + "px !important;");
                });
            }
        });

        // refresh wpb content
        $(document).on('shown.bs.collapse', '.collapse', function() {
            var panel = $(this);
            theme.refreshVCContent(panel);
        });
        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function(e) {
            var panel = $($(e.target).attr('href'));
            theme.refreshVCContent(panel);
        });
        $(document).on('tabactivate', '.woocommerce-tabs', function(e, ui) {
            var label = $(ui).attr('aria-controls');
            var panel = $('[aria-labelledby="' + label + '"');
            theme.refreshVCContent(panel);
        });

        // porto tooltip for header, footer
        $(".porto-tooltip .tooltip-icon").click(function(){
            if($(this).parent().children(".tooltip-popup").css("display")=="none"){
                $(this).parent().children(".tooltip-popup").fadeIn(200).slideDown(200);
            }else{
                $(this).parent().children(".tooltip-popup").fadeOut(200).slideUp(200);
            }
        });
        $(".porto-tooltip .tooltip-close").click(function(){
            $(this).parent().fadeOut(200).slideUp(200);
        });
    }

    function porto_woo_init() {
        // Woocommerce Variation Form
        if (typeof theme.WooVariationForm !== 'undefined') {
            theme.WooVariationForm.initialize();
        }

        // Woocommerce Product Image Slider
        if (typeof theme.WooProductImageSlider !== 'undefined') {
            theme.WooProductImageSlider.initialize();
        }
    }

    porto_woo_init();

    $(document).ready(function(){
        porto_init();

        $(window).bind('vc_reload', function() {
            porto_init();
            $('.type-product').addClass('product');
            $('.type-post').addClass('post');
            $('.type-portfolio').addClass('portfolio');
            $('.type-member').addClass('member');
            $('.type-block').addClass('block');
        });
    });
})( window.theme, jQuery );



// @koala-prepend "theme/base.js"
// @koala-prepend "theme/functions.js"
// @koala-prepend "theme/mobile_check.js"

// @koala-prepend "theme/loading_overlay.js"
// @koala-prepend "theme/mega_menu.js"
// @koala-prepend "theme/sidebar_menu.js"
// @koala-prepend "theme/accordion_menu.js"
// @koala-prepend "theme/sticky_header.js"
// @koala-prepend "theme/side_nav.js"
// @koala-prepend "theme/search.js"
// @koala-prepend "theme/panel.js"
// @koala-prepend "theme/hash_scroll.js"

// @koala-prepend "theme/flickr_zoom.js"
// @koala-prepend "theme/waypoints.js"
// @koala-prepend "theme/scroll_to_top.js"

// @koala-prepend "theme/fit_videos.js"
// @koala-prepend "theme/refresh_video_sizes.js"
// @koala-prepend "theme/accordion.js"
// @koala-prepend "theme/toggle.js"
// @koala-prepend "theme/tooltip.js"
// @koala-prepend "theme/vc_image_zoom.js"

// @koala-prepend "theme/slideshow.js"
// @koala-prepend "theme/carousel.js"

// @koala-prepend "theme/grid.js"
// @koala-prepend "theme/posts_infinite.js"
// @koala-prepend "theme/portfolios_infinite.js"
// @koala-prepend "theme/portfolio_like.js"
// @koala-prepend "theme/portfolio_filter.js"
// @koala-prepend "theme/members_infinite.js"
// @koala-prepend "theme/member_filter.js"
// @koala-prepend "theme/faqs_infinite.js"
// @koala-prepend "theme/faq_filter.js"
// @koala-prepend "theme/filter_zoom.js"

// @koala-prepend "theme/preview_image.js"
// @koala-prepend "theme/sort_filter.js"
// @koala-prepend "theme/sticky.js"

// @koala-prepend "theme/woo_grid_list_toggle.js"
// @koala-prepend "theme/woo_category_filter.js"
// @koala-prepend "theme/woo_products_slider.js"
// @koala-prepend "theme/woo_product_image_slider.js"
// @koala-prepend "theme/woo_quick_view.js"
// @koala-prepend "theme/woo_qty_field.js"
// @koala-prepend "theme/woo_widget_toggle.js"
// @koala-prepend "theme/woo_widget_accordion.js"
// @koala-prepend "theme/woo_variation_form.js"
// @koala-prepend "theme/woo_events.js"

// @koala-prepend "theme/circle_slider.js"
// @koala-prepend "theme/porto_carousel.js"

// @koala-prepend "theme/init.js"

