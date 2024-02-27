<?php
function cjfm_arrays($return){
	$arrays['yes_no'] = array(
		'yes' => __('Yes', 'cjfm'),
		'no' => __('No', 'cjfm'),
	);
	$arrays['enable_disable'] = array(
		'enable' => __('Enable', 'cjfm'),
		'disable' => __('Disable', 'cjfm'),
	);
	$arrays['on_off'] = array(
		'on' => __('On', 'cjfm'),
		'off' => __('Off', 'cjfm'),
	);

	$categories = get_categories();
	if(!empty($categories)){
		foreach ($categories as $key => $cat) {
			$arrays['categories'][$cat->term_id] = $cat->name;
		}
	}else{
		$arrays['categories']['none'] = __('No categories found', 'cjfm');
	}


	return $arrays[$return];
}