<?php
/**
 * Convert a pad to a markdown_wiki object.
 *
 * Plugin elgg-markdown_wiki should be activated
 *
 * @package ElggPad
 */

$guid = get_input('guid');

$pad = new ElggPad($guid);

if ($pad) {

	// we pass text of the pad by $_SESSION variable because it's doesn't work with @ in GET request. Bug in elgg.parse_url()
	if ($pad->getPrivateSetting('status') == 'open') {
		$_SESSION['convert_markdown_wiki'] = $pad->getPadMarkdown();
	} else {
		$_SESSION['convert_markdown_wiki'] = $pad->getPadMarkdown($pad->text);
	}

	forward("wiki/edit?q={$pad->title}&container_guid={$pad->getContainerGUID()}");

}

register_error(elgg_echo('pad:convert:failure'));
forward(REFERER);
