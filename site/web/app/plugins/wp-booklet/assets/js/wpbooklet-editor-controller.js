jQuery(document).ready( function() {
	
	/* Define variables */
	
	var booklet = jQuery(".wp-booklet-editor");
	
	var bookletCalculateWidthButton = jQuery(".wp-booklet-property-calculate-width");
	var bookletCalculateHeightButton = jQuery(".wp-booklet-property-calculate-height");
	var bookletShortcodeDisplay = jQuery(".wp-booklet-shortcode-display");
	var bookletWidthInput = jQuery("input[name='wp-booklet-metas[wp-booklet-width]']");
	var bookletHeightInput = jQuery("input[name='wp-booklet-metas[wp-booklet-height]']");
	
	/* Initialize booklet editor */
	
	var bookletEditor = new WPBookletEditor( booklet );
	
	/* Initialize auto-calculate buttons */
	
	bookletCalculateWidthButton.click( function(e) {
		
		e.preventDefault();
		bookletWidthInput.val( bookletEditor.getOptimalPageWidth() );
		
	});
	
	bookletCalculateHeightButton.click( function(e) {
		
		e.preventDefault();
		bookletHeightInput.val( bookletEditor.getOptimalPageHeight() );
		
	});
	
	/* Shortcode auto-select */
	
	bookletShortcodeDisplay.click( function(e) {
		jQuery(e.currentTarget).select();
	});
	
});