<?php

function porto_ct_default_view_meta_fields() {

    $theme_layouts = porto_ct_layouts();
    $sidebar_options = porto_ct_sidebars();
    $banner_pos = porto_ct_banner_pos();
    $banner_type = porto_ct_banner_type();
    $header_view = porto_ct_header_view();
    $footer_view = porto_ct_footer_view();
    $master_sliders = porto_ct_master_sliders();
    $rev_sliders = porto_ct_rev_sliders();

    // Get menus
    $menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
    $menu_options = array();
    if (!empty($menus)) {
        foreach ($menus as $menu) {
            $menu_options[$menu->term_id] = $menu->name;
        }
    }

    return apply_filters('porto_view_meta_fields', array(
        // Loading Overlay
        'loading_overlay'=> array(
            'name' => 'loading_overlay',
            'title' => __('Loading Overlay', 'porto'),
            'type' => 'radio',
            'default' => '',
            'options' => porto_ct_show_options()
        ),
        // Breadcrumbs
        'breadcrumbs'=> array(
            'name' => 'breadcrumbs',
            'title' => __('Breadcrumbs', 'porto'),
            'desc' => __('Hide breadcrumbs', 'porto'),
            'type' => 'checkbox'
        ),
        // Page Title
        'page_title' => array(
            'name' => 'page_title',
            'title' => __('Page Title', 'porto'),
            'desc' => __('Hide page title', 'porto'),
            'type' => 'checkbox'
        ),
        // Page Sub Title
        'page_sub_title' => array(
            'name' => 'page_sub_title',
            'title' => __('Page Sub Title', 'porto'),
            'type' => 'text',
            'required' => array(
                'name' => 'page_title',
                'value' => ''
            )
        ),
        // Header
        'header' => array(
            'name' => 'header',
            'title' => __('Header', 'porto'),
            'desc' => __('Hide header', 'porto'),
            'type' => 'checkbox'
        ),
        // Sticky Header
        'sticky_header'=> array(
            'name' => 'sticky_header',
            'title' => __('Sticky Header', 'porto'),
            'type' => 'radio',
            'default' => '',
            'required' => array(
                'name' => 'header',
                'value' => ''
            ),
            'options' => porto_ct_show_options()
        ),
        // Header View
        'header_view'=> array(
            'name' => 'header_view',
            'title' => __('Header View', 'porto'),
            'type' => 'radio',
            'default' => '',
            'required' => array(
                'name' => 'header',
                'value' => ''
            ),
            'options' => $header_view
        ),
        // Footer
        'footer' => array(
            'name' => 'footer',
            'title' => __('Footer', 'porto'),
            'desc' => __('Hide footer', 'porto'),
            'type' => 'checkbox'
        ),
        // Footer View
        'footer_view'=> array(
            'name' => 'footer_view',
            'title' => __('Footer View', 'porto'),
            'type' => 'radio',
            'default' => '',
            'required' => array(
                'name' => 'footer',
                'value' => ''
            ),
            'options' => $footer_view
        ),
        // Main Menu
        'main_menu' => array(
            'name' => 'main_menu',
            'title' => __('Main Menu', 'porto'),
            'type' => 'select',
            'default' => '',
            'options' => $menu_options
        ),
        // Sidebar Menu
        'sidebar_menu' => array(
            'name' => 'sidebar_menu',
            'title' => __('Sidebar Menu', 'porto'),
            'type' => 'select',
            'default' => '',
            'options' => $menu_options
        ),
        // Layout, Sidebar
        'default'=> array(
            'name' => 'default',
            'title' => __('Layout & Sidebar', 'porto'),
            'desc' => __('Use selected layout and sidebar options.', 'porto'),
            'type' => 'checkbox'
        ),
        // Layout
        'layout' => array(
            'name' => 'layout',
            'title' => __('Layout', 'porto'),
            'type' => 'radio',
            'default' => 'right-sidebar',
            'required' => array(
                'name' => 'default',
                'value' => 'default'
            ),
            'options' => $theme_layouts
        ),
        // Sidebar
        'sidebar'=> array(
            'name' => 'sidebar',
            'title' => __('Sidebar', 'porto'),
            'desc' => __('<strong>Note</strong>: You can create the sidebar under <strong>Appearance > Sidebars</strong>', 'porto'),
            'type' => 'select',
            'default' => '',
            'required' => array(
                'name' => 'default',
                'value' => 'default'
            ),
            'options' => $sidebar_options
        ),
        // Sticky Sidebar
        'sticky_sidebar'=> array(
            'name' => 'sticky_sidebar',
            'title' => __('Sticky Sidebar', 'porto'),
            'type' => 'radio',
            'default' => '',
            'options' => porto_ct_enable_options()
        ),
        // Banner Position
        'banner_pos'=> array(
            'name' => 'banner_pos',
            'title' => __('Banner Position', 'porto'),
            'type' => 'radio',
            'default' => '',
            'options' => $banner_pos
        ),
        // Banner Type
        'banner_type'=> array(
            'name' => 'banner_type',
            'title' => __('Banner Type', 'porto'),
            'type' => 'select',
            'options' => $banner_type
        ),
        // Revolution Slider
        'rev_slider'=> array(
            'name' => 'rev_slider',
            'title' => __('Revolution Slider', 'porto'),
            'desc' => __('Please select <strong>Banner Type</strong> to <strong>Revolution Slider</strong> and select a slider.', 'porto'),
            'type' => 'select',
            'required' => array(
                'name' => 'banner_type',
                'value' => 'rev_slider'
            ),
            'options' => $rev_sliders
        ),
        // Master Slider
        'master_slider'=> array(
            'name' => 'master_slider',
            'title' => __('Master Slider', 'porto'),
            'desc' => __('Please select <strong>Banner Type</strong> to <strong>Master Slider</strong> and select a slider.', 'porto'),
            'type' => 'select',
            'required' => array(
                'name' => 'banner_type',
                'value' => 'master_slider'
            ),
            'options' => $master_sliders
        ),
        // Banner
        'banner_block'=> array(
            'name' => 'banner_block',
            'title' => __('Banner Block', 'porto'),
            'desc' => __('Please select <strong>Banner Type</strong> to <strong>Banner Block</strong> and input a block slug name. You can create a block in <strong>Blocks/Add New</strong>.', 'porto'),
            'type' => 'text',
            'required' => array(
                'name' => 'banner_type',
                'value' => 'banner_block'
            ),
        ),
        // Content Top
        'content_top'=> array(
            'name' => 'content_top',
            'title' => __('Content Top', 'porto'),
            'desc' => __('Please input comma separated block slug names.', 'porto'),
            'type' => 'text'
        ),
        // Content Inner Top
        'content_inner_top'=> array(
            'name' => 'content_inner_top',
            'title' => __('Content Inner Top', 'porto'),
            'desc' => __('Please input comma separated block slug names.', 'porto'),
            'type' => 'text'
        ),
        // Content Inner Bottom
        'content_inner_bottom'=> array(
            'name' => 'content_inner_bottom',
            'title' => __('Content Inner Bottom', 'porto'),
            'desc' => __('Please input comma separated block slug names.', 'porto'),
            'type' => 'text'
        ),
        // Content Bottom
        'content_bottom'=> array(
            'name' => 'content_bottom',
            'title' => __('Content Bottom', 'porto'),
            'desc' => __('Please input comma separated block slug names.', 'porto'),
            'type' => 'text'
        )
    ));
}

function porto_ct_default_skin_meta_fields($tax_meta_fields = false) {

    $bg_repeat = porto_ct_bg_repeat();
    $bg_size = porto_ct_bg_size();
    $bg_attachment = porto_ct_bg_attachment();
    $bg_position = porto_ct_bg_position();

    if (!$tax_meta_fields) {
        $tabs = array(
            'body' => array('body', __('Body', 'porto')),
            'header' => array('header', __('Header', 'porto')),
            'sticky_header' => array('sticky_header', __('Sticky Header', 'porto')),
            'breadcrumbs' => array('breadcrumbs', __('Breadcrumbs', 'porto')),
            'page' => array('page', __('Page Content', 'porto')),
            'content_bottom' => array('content_bottom', __('Content Bottom Widgets Area', 'porto')),
            'footer_top' => array('footer_top', __('Footer Top Widget Area', 'porto')),
            'footer' => array('footer', __('Footer', 'porto')),
            'footer_bottom' => array('footer_bottom', __('Footer Bottom Widget Area', 'porto')),
        );
    } else {
        $tabs = array(
            'body' => array('body', __('Body Background', 'porto')),
            'header' => array('header', __('Header Background', 'porto')),
            'sticky_header' => array('sticky_header', __('Sticky Header Background', 'porto')),
            'breadcrumbs' => array('breadcrumbs', __('Breadcrumbs Background', 'porto')),
            'page' => array('page', __('Page Content Background', 'porto')),
            'content_bottom' => array('content_bottom', __('Content Bottom Widgets Area Background', 'porto')),
            'footer_top' => array('footer_top', __('Footer Top Widget Area Background', 'porto')),
            'footer' => array('footer', __('Footer Background', 'porto')),
            'footer_bottom' => array('footer_bottom', __('Footer Bottom Widget Area Background', 'porto')),
        );
    }

    $return = array();

    foreach ($tabs as $key => $value) {
        $return[$key . '_bg_color'] = array(
            'name' => $key . '_bg_color',
            'title' => __('Background Color', 'porto'),
            'type' => 'color',
            'tab' => $value
        );
        $return[$key . '_bg_image'] = array(
            'name' => $key . '_bg_image',
            'title' => __('Background Image', 'porto'),
            'type' => 'upload',
            'tab' => $value
        );
        $return[$key . '_bg_repeat'] = array(
            'name' => $key . '_bg_repeat',
            'title' => __('Background Repeat', 'porto'),
            'type' => 'select',
            'options' => $bg_repeat,
            'tab' => $value
        );
        $return[$key . '_bg_size'] = array(
            'name' => $key . '_bg_size',
            'title' => __('Background Size', 'porto'),
            'type' => 'select',
            'options' => $bg_size,
            'tab' => $value
        );
        $return[$key . '_bg_attachment'] = array(
            'name' => $key . '_bg_attachment',
            'title' => __('Background Attachment', 'porto'),
            'type' => 'select',
            'options' => $bg_attachment,
            'tab' => $value
        );
        $return[$key . '_bg_position'] = array(
            'name' => $key . '_bg_position',
            'title' => __('Background Position', 'porto'),
            'type' => 'select',
            'options' => $bg_position,
            'tab' => $value
        );
    }

    return apply_filters('porto_skin_meta_fields', $return);
}
