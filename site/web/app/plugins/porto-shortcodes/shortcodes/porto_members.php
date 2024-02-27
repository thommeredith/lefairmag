<?php

// Porto Members
add_shortcode('porto_members', 'porto_shortcode_members');
add_action('vc_after_init', 'porto_load_members_shortcode');

function porto_shortcode_members($atts, $content = null) {
    ob_start();
    if ($template = porto_shortcode_template('porto_members'))
        include $template;
    return ob_get_clean();
}

function porto_load_members_shortcode() {
    $animation_type = porto_vc_animation_type();
    $animation_duration = porto_vc_animation_duration();
    $animation_delay = porto_vc_animation_delay();
    $custom_class = porto_vc_custom_class();

    vc_map( array(
        'name' => "Porto " . __('Members', 'porto-shortcodes'),
        'base' => 'porto_members',
        'category' => __('Porto', 'porto-shortcodes'),
        'icon' => 'porto_vc_members',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Title", 'porto-shortcodes'),
                "param_name" => "title",
                "admin_label" => true
            ),
            array(
                "type" => "dropdown",
                "heading" => __("Columns", 'porto-shortcodes'),
                "param_name" => "columns",
                'std' => '4',
                "value" => porto_sh_commons('member_columns'),
                "admin_label" => true
            ),
            array(
                "type" => "dropdown",
                "heading" => __("View Type", 'porto-shortcodes'),
                "param_name" => "view",
                'std' => 'classic',
                "value" => porto_sh_commons('member_view'),
                "admin_label" => true
            ),
            array(
                'type' => 'checkbox',
                'heading' => __("Show Overview", 'porto-shortcodes'),
                'param_name' => 'overview',
                'std' => 'yes',
                'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
            ),
            array(
                'type' => 'checkbox',
                'heading' => __("Show Social Links", 'porto-shortcodes'),
                'param_name' => 'socials',
                'std' => 'yes',
                'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
            ),
            array(
                "type" => "textfield",
                "heading" => __("Category IDs", 'porto-shortcodes'),
                "description" => __("comma separated list of category ids", 'porto-shortcodes'),
                "param_name" => "cats",
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "heading" => __("Member IDs", 'porto-shortcodes'),
                "description" => __("comma separated list of member ids", 'porto-shortcodes'),
                "param_name" => "post_in"
            ),
            array(
                "type" => "textfield",
                "heading" => __("Members Count", 'porto-shortcodes'),
                "param_name" => "number",
                "value" => '8'
            ),
            array(
                'type' => 'checkbox',
                'heading' => __("Show Archive Link", 'porto-shortcodes'),
                'param_name' => 'view_more',
                'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
            ),
            array(
                'type' => 'checkbox',
                'heading' => __("Show Filter", 'porto-shortcodes'),
                'param_name' => 'filter',
                'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
            ),
            array(
                'type' => 'checkbox',
                'heading' => __("Show Pagination", 'porto-shortcodes'),
                'param_name' => 'pagination',
                'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
            ),
            $custom_class,
            $animation_type,
            $animation_duration,
            $animation_delay
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Porto_Members')) {
        class WPBakeryShortCode_Porto_Members extends WPBakeryShortCode {
        }
    }
}