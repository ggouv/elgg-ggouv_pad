<?php
/**
 * Convert a pad to a markdown_wiki object.
 *
 * Plugin elgg-markdown_wiki should be activated
 *
 * @package ElggPad
 */

$guid = get_input('guid');

$page = new ElggPad($guid);

if ($page) {

	// we pass text of the pad by $_SESSION variable because it's doesn't work with @ in GET request. Bug in elgg.parse_url()
	$_SESSION['convert_markdown_wiki'] = $page->getPadMarkdown();
	forward("wiki/edit?q={$page->title}&container_guid={$page->getContainerGUID()}");

}

register_error(elgg_echo('etherpad:convert:failure'));
forward(REFERER);
