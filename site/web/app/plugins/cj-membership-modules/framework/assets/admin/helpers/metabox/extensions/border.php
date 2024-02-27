<?php
// Border
add_action( 'cmb_render_border', 'cjfm_cmb_render_border', 10, 5 );
function cjfm_cmb_render_border( $field_args, $escaped_value, $object_id, $object_type, $field_type_object ) {

    $saved_value = get_post_meta($_GET['post'], $field_args['id'], true);

    $saved_value = wp_parse_args( $saved_value, array(
        'border-top' => '',
        'border-right' => '',
        'border-bottom' => '',
        'border-left' => '',
        'border-style' => '',
        'border-color' => '',
    ) );

    echo '<div id="'.$field_args['id'].'_border" style="width:97%;">';

    echo '<div class="margin-5-bottom one_sixth">';
    echo '<label for="'.$field_args['id'].'_border_top" style="margin-bottom:5px; display:inline-block;">'.__('Border Top (px)', 'cjfm').'</label><br>';
    echo '<input type="text" id="'.$field_args['id'].'border_top" name="'.$field_args['id'].'[border-top]" style="width:100%;" value="'.$saved_value['border-top'].'" />';
    echo '</div>';

    echo '<div class="margin-5-bottom one_sixth">';
    echo '<label for="'.$field_args['id'].'_border_right" style="margin-bottom:5px; display:inline-block;">'.__('Border Right (px)', 'cjfm').'</label><br>';
    echo '<input type="text" id="'.$field_args['id'].'border_right" name="'.$field_args['id'].'[border-right]" style="width:100%;" value="'.$saved_value['border-right'].'" />';
    echo '</div>';

    echo '<div class="margin-5-bottom one_sixth">';
    echo '<label for="'.$field_args['id'].'_border_bottom" style="margin-bottom:5px; display:inline-block;">'.__('Border Bottom (px)', 'cjfm').'</label><br>';
    echo '<input type="text" id="'.$field_args['id'].'border_bottom" name="'.$field_args['id'].'[border-bottom]" style="width:100%;" value="'.$saved_value['border-bottom'].'" />';
    echo '</div>';

    echo '<div class="margin-5-bottom one_sixth">';
    echo '<label for="'.$field_args['id'].'_border_left" style="margin-bottom:5px; display:inline-block;">'.__('Border Left (px)', 'cjfm').'</label><br>';
    echo '<input type="text" id="'.$field_args['id'].'border_left" name="'.$field_args['id'].'[border-left]" style="width:100%;" value="'.$saved_value['border-left'].'" />';
    echo '</div>';

    echo '<div class="margin-5-bottom one_sixth">';
    echo '<label for="'.$field_args['id'].'_border_styles" style="margin-bottom:5px; display:inline-block;">'.__('Border Style', 'cjfm').'</label><br>';
    echo '<select id="'.$field_args['id'].'_border_styles" name="'.$field_args['id'].'[border-style]" style="width:100%;">';
    foreach (cjfm_border_styles() as $key => $value) {
        if($key == $saved_value['border-style']){
            echo '<option selected value="'.$key.'">'.$value.'</option>';
        }else{
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    echo '</select>';
    echo '</div>';

    echo '<div class="margin-5-bottom one_sixth last">';
    echo '<label for="'.$field_args['id'].'_border_color" style="margin-bottom:5px; display:inline-block;">'.__('Border Color (#HEX)', 'cjfm').'</label><br>';
    echo '<input type="text" id="'.$field_args['id'].'border_color" name="'.$field_args['id'].'[border-color]" style="width:100%;" value="'.$saved_value['border-color'].'" />';
    echo '</div>';

    echo '<div>';
    echo $field_type_object->_desc( true );

}