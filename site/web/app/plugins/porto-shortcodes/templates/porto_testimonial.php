<?php
$output = $name = $name_color = $role = $company = $role_company_color = $author_url = $photo_url = $photo_id = $quote = $quote_color = $view = $remove_border = $color = $animation_type = $animation_duration = $animation_delay = $el_class = '';
extract(shortcode_atts(array(
    'name' => '',
    'name_color' => '',
    'role' => '',
    'company' => '',
    'role_company_color' => '',
    'author_url' => '',
    'photo_url' => '',
    'photo_id' => '',
    'quote' => '',
    'quote_color' => '',
    'view' => '',
    'style' => '',
    'remove_border' => '',
    'remove_bg' => '',
    'alt_font' => '',
    'skin' => 'custom',
    'color' => '',
    'animation_type' => '',
    'animation_duration' => 1000,
    'animation_delay' => 0,
    'el_class' => '',
), $atts));

$el_class = porto_shortcode_extract_class( $el_class );

if (!$photo_url && $photo_id)
    $photo_url = wp_get_attachment_url($photo_id);

$porto_url = str_replace(array('http:', 'https:'), '', $photo_url);

$output = '<div class="porto-testimonial wpb_content_element '. $el_class . '"';
if ($animation_type) {
    $output .= ' data-appear-animation="'.$animation_type.'"';
    if ($animation_delay)
        $output .= ' data-appear-animation-delay="'.$animation_delay.'"';
    if ($animation_duration && $animation_duration != 1000)
        $output .= ' data-appear-animation-duration="'.$animation_duration.'"';
}
$output .= '>';

if ($view == 'transparent') {
    $output .= '<div class="testimonial' . ($style ? ' ' . $style : '') . ' testimonial-with-quotes' . ($color == 'white' ? ' testimonial-light' : '') . ($remove_border ? ' testimonial-no-borders' : '') . '">';
    if ($photo_url) {
        $output .= '<img class="img-responsive img-circle" src="'.esc_url($porto_url).'" alt="' . $name . '">';
    }
    $output .= '<blockquote class="testimonial-carousel '.$color.'">';
    $output .= '<p'.($quote_color?' style="color:'.esc_attr($quote_color).'"':'').'>'.do_shortcode($content != '' ? $content : $quote).'</p>';
    $output .= '</blockquote>';
    if ($author_url) {
        $output .= '<a href="'.esc_url($author_url).'">';
    }
    $output .= '<div class="testimonial-author"><p><strong'.($name_color?' style="color:'.esc_attr($name_color).'"':'').'>'.$name.'</strong>';
    if ($author_url) {
        $output .= '</a>';
    }
    $output .= '<span'.($role_company_color?' style="color:'.esc_attr($role_company_color).'"':'').'>'.$role.(($role && $company)?' - ':'').$company.'</span>';
    $output .= '</p></div></div>';
} else if ($view == 'simple') {
    $output .= '<div class="testimonial testimonial-style-6 testimonial-with-quotes'. ($color == 'white' ? ' testimonial-light' : '') .'"><blockquote><p'.($quote_color?' style="color:'.esc_attr($quote_color).'"':'').'>'.do_shortcode($content != '' ? $content : $quote).'</p></blockquote><div class="testimonial-author"><p>';
    if ($author_url) {
        $output .= '<a href="'.esc_url($author_url).'">';
    }
    $output .= '<strong'.($name_color?' style="color:'.esc_attr($name_color).'"':'').'>'.$name.'</strong>';
    if ($author_url) {
        $output .= '</a>';
    }
    $output .= '<span'.($role_company_color?' style="color:'.esc_attr($role_company_color).'"':'').'>'.$role.(($role && $company)?' - ':'').$company.'</span></p>';
    $output .= '</div></div>';
} else {
    $output .= '<div class="testimonial' . (!$style && $skin != 'custom' ? ' testimonial-' . $skin : '') . ($style ? ' ' . $style : '') . ($remove_border ? ' testimonial-no-borders' : '') . ($remove_bg ? ' testimonial-trasnparent-background' : '') . ($alt_font ? ' testimonial-alternarive-font' : '') . '"><blockquote>';
    $output .= '<p'.($quote_color?' style="color:'.esc_attr($quote_color).'"':'').'>'.do_shortcode($content != '' ? $content : $quote).'</p>';
    $output .= '</blockquote>';
    if (!$remove_bg) $output .= '<div class="testimonial-arrow-down"></div>';
    $output .= '<div class="testimonial-author clearfix">';
    if ($photo_url) {
        switch ($style) {
            case 'testimonial-style-2':
            case 'testimonial-style-5':
            case 'testimonial-style-6':
                $output .= '<img class="img-responsive img-circle" src="'.esc_url($photo_url).'" alt="' . $name . '">';
                break;
            case 'testimonial-style-3':
            case 'testimonial-style-4':
                $output .= '<div class="testimonial-author-thumbnail"><img class="img-responsive img-circle" src="'.esc_url($photo_url).'" alt="' . $name . '"></div>';
                break;
            default:
                $output .= '<div class="testimonial-author-thumbnail img-thumbnail"><img src="'.esc_url($photo_url).'" alt="' . $name . '"></div>';
                break;
        }
    }
    $output .= '<p>';
    if ($author_url) {
        $output .= '<a href="'.esc_url($author_url).'">';
    }
    $output .= '<strong'.($name_color?' style="color:'.esc_attr($name_color).'"':'').'>'.$name.'</strong>';
    if ($author_url) {
        $output .= '</a>';
    }
    $output .= '<span'.($role_company_color?' style="color:'.esc_attr($role_company_color).'"':'').'>'.$role.(($role && $company)?' - ':'').$company.'</span></p>';
    $output .= '</div></div>';
}

$output .= '</div>';

echo $output;