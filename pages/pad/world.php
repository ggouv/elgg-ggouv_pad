<?php
/**
 * List all pads
 *
 * @package ElggPad
 */

$title = elgg_echo('pad:all');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('pad'));

elgg_register_title_button();

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'pad',
	'show_group' => true,
	'full_view' => false,
));
if (!$content) {
	$content = '<p>' . elgg_echo('pad:none') . '</p>';
}

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pad/sidebar'),
));

echo elgg_view_page($title, $body);
