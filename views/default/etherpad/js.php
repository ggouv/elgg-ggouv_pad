
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
		elgg.ggouv_pad.reload();
	});

	// for extensible template
	$(window).bind('resize.pad', function() {
		if ($('iframe.etherpad').length ) {
			$('iframe.etherpad').height($(window).height() - $('iframe.etherpad').position().top - 58);
		}
	});
};
elgg.register_hook_handler('init', 'system', elgg.ggouv_pad.init);



/**
 * Reload pad for full ajax website
 * @return {[type]} [description]
 */
elgg.ggouv_pad.reload = function() {
	if ($('iframe.etherpad').length) {
		$('body').addClass('fixed-pad');
		$('iframe.etherpad').height($(window).height() - $('iframe.etherpad').position().top - 58);
		$('.elgg-comments').addClass('hidden');

		$('.elgg-content .elgg-subtext a[href*="comments"]').die().live('click', function() {
			console.log('uieuie');
			if ($('iframe.etherpad').hasClass('hidden')) {
				$('body').addClass('fixed-pad');
				$('iframe.etherpad').removeClass('hidden');
				$('.elgg-comments').addClass('hidden');
			} else {
				$('body').removeClass('fixed-pad');
				$('iframe.etherpad').addClass('hidden');
				$('.elgg-comments').removeClass('hidden');
			}
			return false;
		});
	} else {
		$('body').removeClass('fixed-pad');
	}
}



// End of js for elgg-ggouv_pad plugin
