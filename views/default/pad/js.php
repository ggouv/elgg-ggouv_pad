
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
		var $ie = $('iframe.pad-iframe');

		if ($ie.length ) {
			$ie.add('#md-preview-pad').height($(window).height() - $ie.position().top - 48);
		}
	});
};
elgg.register_hook_handler('init', 'system', elgg.ggouv_pad.init);



/**
 * Reload pad for full ajax website
 * @return {[type]} [description]
 */
elgg.ggouv_pad.reload = function() {
	if ($('iframe.pad-iframe').length) {
		var $b = $('body').addClass('fixed-pad'),
			$ie = $('iframe.pad-iframe'),
			$et = $('.elgg-content'),
			$ec = $('.elgg-comments').addClass('hidden'),
			$mp = $('.elgg-menu-item-toggle-markdown-preview').removeClass('hidden'),
			$pm = $('.pad-wrapper .pane-markdown, .pad-wrapper .markdown-menu'),
			Height = $(window).height() - $ie.position().top - 48,
			getAceFrame = function() {
				return $('.pad-iframe')[0].contentWindow.$('iframe[name="ace_outer"]')[0].contentWindow.document.getElementsByTagName('iframe')[0].contentWindow;
			};

		$('.elgg-layout-one-sidebar').css('margin-right', '-=10px');
		$ie.height(Height);
		$('#md-preview-pad, .pad-wrapper .help-markdown').height(Height-10);

		$('.elgg-content .elgg-subtext a[href*="comments"], .elgg-menu-item-toggle-comment a').die().live('click', function() {
			if ($et.hasClass('hidden')) {
				$b.addClass('fixed-pad');
				$et.removeClass('hidden');
				$ec.addClass('hidden');
			} else {
				$b.removeClass('fixed-pad');
				$et.addClass('hidden');
				$ec.removeClass('hidden');
			}
			return false;
		});

		$('.elgg-menu-item-toggle-markdown-preview a').die().live('click', function() {
			if ($pm.hasClass('hidden')) {

				// ugly way to get pad text. @todo find another way. http://stackoverflow.com/questions/4039384/how-do-i-programatically-fetch-the-live-plaintext-contents-of-an-etherpad doesn't work padeditor is undefined.
				if ($('#md-preview-pad').html() == '') {
					var padHtml = getAceFrame().$('#innerdocbody').html();
					$('#md-preview-pad').html(elgg.markdown_wiki.ShowdownConvert($('<div>').html(padHtml.replace(/<div id="magic/g, '\n<div id="magic')).text()));
				}

				$pm.removeClass('hidden');
				$ie.css('width', '50%');
			} else {
				$pm.addClass('hidden');
				$ie.css('width', '100%');
			}
			return false;
		});

		// menus
		$('.pad-wrapper .elgg-menu-markdown li').click(function() { // yes use click not live
			$(this).parent().find('li').removeClass('elgg-state-selected');
			var paneName = $(this).addClass('elgg-state-selected').attr('class').split(' ')[0].split('-').pop(-1);

			$('.pane-markdown .pane').removeClass('hidden').not('.'+paneName+'-markdown').addClass('hidden');
		});

		// over contributors
		var getAuthorClassName = function(author) {
				return "author-" + author.replace(/[^a-y0-9]/g, function(c) {
					if (c == ".") return "-";
					return 'z' + c.charCodeAt(0) + 'z';
				});
			};

		$('#pad-authors .elgg-avatar').die().live({
			mouseenter: function() {
				var authorClass = getAuthorClassName($(this).attr('class').match(/\S*$/)[0].replace('-', '.')),
					aceHead = getAceFrame().$('head');

				aceHead.find('#hoveredContributors').remove();
				aceHead.append('<style id="hoveredContributors" type="text/css">#innerdocbody span:not(.'+authorClass+') {opacity:0.3;}</style>');
			},
			mouseleave: function() {
				getAceFrame().$('head').find('#hoveredContributors').remove();
			}
		});

	} else {
		$('body').removeClass('fixed-pad');
		//$('.pad-iframe')[0].contentWindow.pad.socket.$events.disconnect();
	}
}



// End of js for elgg-ggouv_pad plugin
