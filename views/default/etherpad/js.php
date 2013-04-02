
/**
 *	Elgg-ggouv_pad plugin
 *	@package elgg-ggouv_pad
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-ggouv_pad
 *
 *	Elgg-ggouv_pad js
 *
 */

/**
 * Elgg-ggouv_pad initialization
 *
 * @return void
 */
elgg.provide('elgg.ggouv_pad');

elgg.ggouv_pad.init = function() {
	$(document).ready(function() {
		elgg.ggouv_pad.resize();
	});

	// for extensible template
	$(window).bind("resize", function() {
		if ( $('iframe.etherpad').length ) {
			$('iframe.etherpad').height($(window).height() - $('iframe.etherpad').position().top - 58);
		}
	});
	
	$('.elgg-content .elgg-subtext a[href*="comments"]').die().live('click', function() {
		if ($('iframe.etherpad').hasClass('hidden')) {
			$('.elgg-page-body').css('position', 'fixed');
			$('iframe.etherpad').removeClass('hidden');
			$('.elgg-comments').addClass('hidden');
		} else {
			$('.elgg-page-body').css('position', 'relative');
			$('iframe.etherpad').addClass('hidden');
			$('.elgg-comments').removeClass('hidden');
		}
	});
}
elgg.register_hook_handler('init', 'system', elgg.ggouv_pad.init);

elgg.ggouv_pad.resize = function() {
	if ( $('iframe.etherpad').length != 0) {
		$('.elgg-page-body').css('position', 'fixed');
		$('iframe.etherpad').height($(window).height() - $('iframe.etherpad').position().top - 58);
	}else
          $('.elgg-page-body').css('position', 'relative');
}



// End of js for elgg-ggouv_pad plugin
