function WPBookletEditor(elem,settings) {
	
	var that = this;
	
	settings = jQuery.extend({
		sortable: jQuery(elem).find(".wp-booklet-sortable"),
		addPages: jQuery(elem).find(".wp-booklet-sortable-add-pages"),
		uploadPdf: jQuery(".wp-booklet-sortable-upload-pdf"),
		replaceImage: jQuery(elem).find(".wp-booklet-image-upload"),
		bookletWidthInput: jQuery("input[name='wp-booklet-metas[wp-booklet-width]']"),
		bookletHeightInput: jQuery("input[name='wp-booklet-metas[wp-booklet-height]']")
	},settings);
	
	that.newPage = '',
	that.sortable = jQuery(settings.sortable),
	that.postId =  jQuery(elem).attr("data-post-id");
	that.bookletAddPagesButton = jQuery(settings.addPages);
	that.bookletWidthInput = jQuery(settings.bookletWidthInput),
	that.bookletHeightInput = jQuery(settings.bookletHeightInput)
	that.bookletUploadPdfButton = jQuery(settings.uploadPdf);
	that.bookletUploadImageButton = jQuery(settings.replaceImage);
	
	function __construct() {
		
		that.definePageMarkup();
		that.initializeSortable();
		that.initializeAddPagesButton();
		that.initializeUploadPdfButton();
		that.initializeReplaceImageButton();
		
	}
	
	__construct();
	
}

WPBookletEditor.prototype.initializeReplaceImageButton = function() {
	
	var that = this;
	
	var current_page;
	var current_page_frame;
	
	jQuery("body").on( "click", ".wp-booklet-image-upload", function(e) {
		e.preventDefault();
		
		current_page = jQuery(e.currentTarget).parents(".wp-booklet-portlet");
		
		if ( current_page_frame ) {
			current_page_frame.open();
			return;
		};
		
		current_page_frame = wp.media({
			multiple: false,
			title: 'Select image',
			library: {
				type:'image'
			},
			button: {
				text:'Use image'
			}
		});
		
		current_page_frame.on('select',function() {
			var media_attachment;
			
			media_attachment = current_page_frame.state().get('selection').first().toJSON();

			that.updatePage(
				current_page.attr("data-id"),
				media_attachment.id,
				media_attachment.width,
				media_attachment.height,
				media_attachment.url
			);
			
			that.bookletWidthInput.val( that.getOptimalPageWidth() );
			that.bookletHeightInput.val( that.getOptimalPageHeight() );
			
		});
		
		current_page_frame.open();
	});
	
}

WPBookletEditor.prototype.initializeUploadPdfButton = function() {
	
	var that = this;
	
	that.bookletUploadPdfButton.wpBookletPdfUploader({
		postId: that.postId,
		onPageComplete: function(id, width, height, src) {
			
			that.addPage(
				id,
				width,
				height,
				src
			);
			
			that.bookletWidthInput.val( that.getOptimalPageWidth() );
			that.bookletHeightInput.val( that.getOptimalPageHeight() );
			
		}
	});
	
}

WPBookletEditor.prototype.initializeAddPagesButton = function() {
	
	var that = this;
	
	var pages_uploader = wp.media({
		'title':'Choose images',
		'button':{
			'text':'Choose images'
		},
		'library':{
			'type':'image'
		},
		'multiple':true
	});
	
	pages_uploader.on('select', function() {
		attachments = pages_uploader.state().get('selection').toJSON();
		for( var ctr = 0; ctr < attachments.length; ctr++ ) {
			
			that.addPage(
				attachments[ctr].id,
				attachments[ctr].width,
				attachments[ctr].height,
				attachments[ctr].url
			);
			
			that.bookletWidthInput.val( that.getOptimalPageWidth() );
			that.bookletHeightInput.val( that.getOptimalPageHeight() );

		}
	});
	
	that.bookletAddPagesButton.on('click', function(e) {
		e.preventDefault();
		
		if ( pages_uploader ) {
			pages_uploader.open();
			return;
		}
		
		pages_uploader.open();
	});
	
}

WPBookletEditor.prototype.initializeSortable = function() {
		
	var that = this;	
		
	that.sortable.sortable();
	
	that.sortable.find(".wp-booklet-portlet-header").bind("click", function(e){
		jQuery(e.currentTarget).parent().toggleClass("wp-booklet-portlet-hidden");
	});
	
	that.sortable.find(".wp-booklet-header-remove").bind("click", function(e) {
		jQuery(e.currentTarget).parents('.wp-booklet-portlet').remove();
	});
		
}

WPBookletEditor.prototype.definePageMarkup = function() {
	
	var that = this;
	
	that.newPage = '<div class="wp-booklet-portlet">' +
						'<div class="wp-booklet-portlet-header">' +
							'Page' +
							'<span class="wp-booklet-portlet-header-buttons">' +
								'<span class="wp-booklet-header-visibility"></span>' +
								'<span class="wp-booklet-header-remove"></span>' +
							'</span>' +
						'</div>' +
						'<div class="wp-booklet-portlet-content">' +
							'<div class="wp-booklet-portlet-content-left">' +
								'<div class="wp-booklet-page-placeholder"></div>' +
								'<input class="wp-booklet-attachment-id" name="wp-booklet-attachment[]" type="hidden"/>' +
								'<input class="button-secondary wp-booklet-image-upload" type="button" value="Upload image"/>' +
							'</div>' +
							'<div class="wp-booklet-portlet-content-right">' +
								'<p>' +
									'<label>Page Link</label><br/>' +
									'<input class="widefat" type="text" value="" name="wp-booklet-attachment-properties[wp-booklet-page-link][]"/>' +
								'</p>' +
							'</div>' +
							'<div class="clearfix"></div>' +
						'</div>' +
					'</div>';
	
}

WPBookletEditor.prototype.addPage = function(id, width, height, src) {
	
	var that = this;
	
	that.sortable.append(that.newPage);
	var currPage = that.sortable.find(".wp-booklet-portlet").last();
	currPage.find('.wp-booklet-portlet-content-left').prepend('<img data-width='+width+' data-height='+height+' src='+src+' class="wp-booklet-img"/> ');
	currPage.find('.wp-booklet-attachment-id').val(id);
	currPage.find('.wp-booklet-image-upload').val('Replace image');
	currPage.find('.wp-booklet-page-placeholder').remove();
	
	currPage.find(".wp-booklet-portlet-header").bind("click", function(e){
		jQuery(e.currentTarget).parent().toggleClass("wp-booklet-portlet-hidden");
	});
	
	currPage.find(".wp-booklet-header-remove").bind("click", function(e) {
		jQuery(e.currentTarget).parents('.wp-booklet-portlet').remove();
	});
	
}

WPBookletEditor.prototype.updatePage = function(oldId, newId, width, height, src) {
	
	var that = this;
	
	var currPage = that.sortable.find(".wp-booklet-portlet[data-id='"+oldId+"']");
	
	if( currPage.find(".wp-booklet-img").length > 0 ) {
			
		var img = currPage.find('.wp-booklet-img');
		
		img.attr('src',src);
		img.attr('data-width',width);
		img.attr('data-height',height);
		
	}
	else {
		currPage.find('.wp-booklet-portlet-content-left').prepend('<img data-width='+width+' data-height='+height+' src='+src+' class="wp-booklet-img"/> ');
	}
	
	currPage.find('.wp-booklet-attachment-id').val(newId);
	currPage.find('.wp-booklet-image-upload').val('Replace image');
	currPage.find('.wp-booklet-page-placeholder').remove(); 
	
}

WPBookletEditor.prototype.getOptimalPageWidth = function() {
	
	var that = this;
	
	var optimalWidth = 600;
	var highestProportion = 0;
	
	that.sortable.find(".wp-booklet-img").each( function(i,v) {
		
		var width = parseInt( jQuery(v).attr("data-width") );
		var height = parseInt( jQuery(v).attr("data-height") );
		
		var imgProportion = height / width;
		
		if ( imgProportion > highestProportion ) {
			highestProportion = imgProportion;
			optimalWidth = width;
		}
		
	});
	
	return optimalWidth;
	
}

WPBookletEditor.prototype.getOptimalPageHeight = function() {
	
	var that = this;

	var optimalHeight = 400;
	var highestProportion = 0;
	
	that.sortable.find(".wp-booklet-img").each( function(i,v) {
		
		var width = parseInt( jQuery(v).attr("data-width") );
		var height = parseInt( jQuery(v).attr("data-height") );
		
		var imgProportion = height / width;
		
		if ( imgProportion > highestProportion ) {
			highestProportion = imgProportion;
			optimalHeight = height;
		}
		
	});
	
	return optimalHeight;

}
	
