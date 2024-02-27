( function() {

	jQuery.fn.wpBookletPdfUploader = function(settings) {
		
		settings = jQuery.extend({
			postId:'',
			onPageComplete: null
		},settings);
		
		return jQuery(this).each( function(i,v) {
			PdfUploader( jQuery(v), settings );
		});
	
	}
	
	function PdfUploader(elem,settings) {
		
		var button = jQuery(elem);
		var pdf_uploader;
		var attachment;
		var postId;
		
		function __construct() {
			
			postId = parseInt( settings.postId );

			/* Do checks */
			if ( isNaN( postId ) ) {
				console.log('postId is not a number');
				return;
			}
			
			/* Run functions */
			prepareElement();
			initMediaUploader();
			initButton();
			
		}
		
		function prepareElement() {
			
			if ( jQuery(".wp-booklet-preloader-overlay").length == 0 ) {
				jQuery("body").append(	'<div class="wp-booklet-preloader-overlay">'+
											'<div class="wp-booklet-preloader-note"></div>'+
										'</div>');
			}
			
		}
		
		function initButton() {
			
			button.bind("click",function(e) {
				e.preventDefault();
				pdf_uploader.open();
			});	
			
		}
		
		function initMediaUploader() {
			
			pdf_uploader = wp.media({
				'title':'Choose PDF',
				'button': {
					text:'Choose PDF'
				},
				multiple:false
			});
			
			pdf_uploader.on('select', function() {
				attachment = pdf_uploader.state().get('selection').first().toJSON();
				processPDF(attachment);
			});
			
		}
		
		function showPreloader(text) {
			
			updatePreloader(text);
			jQuery(".wp-booklet-preloader-overlay").show().animate({opacity:0.8},200);
		
		}
		
		function updatePreloader(text) {
			
			jQuery(".wp-booklet-preloader-overlay .wp-booklet-preloader-note").html(text);
		
		}	
		
		function hidePreloader() {
			
			jQuery(".wp-booklet-preloader-overlay").animate({opacity:0},200).hide();
		
		}
		
		function processPDF(attachment) {
		
			showPreloader('Validating PDF. Please keep this window open');
			
			var data = {
				'pdf_id':attachment.id,
				'action':'verify_pdf'
			}
			
			jQuery.post(ajaxurl,data,function(verification) {
				if ( verification.wpb_success ) {
					showPreloader('Processing PDF. Please keep this window open.');
					var data = {
						'pdf_id':attachment.id,
						'post_id':postId,
						'action':'process_pdf'
					}
					var pageCount = verification.wpb_page_count;
					var pageOffset = -1;
					
					function processPage() {
						
						pageOffset = pageOffset + 1;
						data.pdf_offset = pageOffset;
						
						updatePreloader('Processing page ' + (pageOffset + 1) + '/' + pageCount + '. Please keep this window open.');

						jQuery.post(ajaxurl,data,function(response) {
							if ( response.wpb_success ) {
								for( var ctr = 0; ctr < response.images.length; ctr++ ) {
									if ( typeof settings.onPageComplete == 'function' ) {
										settings.onPageComplete.call( this, response.images[ctr].id, response.images[ctr].width, response.images[ctr].height, response.images[ctr].src );
									}
								}
								
								setTimeout(processPage,500);
								
								if (pageOffset == ( pageCount - 1 ) ) {
									hidePreloader();
								}
									
							}
							else {
								alert(response.wpb_message);
								hidePreloader();
							}
							
						},'json');

					}

					processPage();
					
				}
				else {
					hidePreloader();
					alert(verification.wpb_message);
				}
			},'json');
		}
	
		
		__construct();
		
	}

})();