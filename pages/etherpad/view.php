<?php
/**
 * View a single pad
 *
 * @package ElggPad
 */

$pad_guid = get_input('guid');
$pad = get_entity($pad_guid);
if (!$pad) {
	forward();
}

group_gatekeeper();

$container = elgg_get_page_owner_entity();

$title = $pad->title;

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "etherpad/group/{$container->guid}/all");
} else {
	elgg_push_breadcrumb($container->name, "etherpad/owner/$container->username");
}
elgg_push_breadcrumb($title);

$content = elgg_view_entity($pad, array('full_view' => true));

if ($pad->getSubtype() == 'etherpad' && elgg_get_plugin_setting('show_comments', 'elgg-ggouv_pad') == 'yes') {
	$content .= elgg_view_comments($pad, true);

	elgg_register_menu_item('page', array(
		'name' => 'toggle-comment',
		'section' => 'A',
		'href' => '#',
		'text' => elgg_echo('etherpad:toggle_comment'),
	));
}

if (elgg_instanceof($container, 'group')) {
	elgg_register_menu_item('page', array(
		'name' => 'convert-markdown_wiki',
		'section' => 'convert',
		'href' => elgg_add_action_tokens_to_url("/action/etherpad/convert-markdown_wiki?guid={$pad_guid}"),
		'text' => elgg_echo('etherpad:convert:markdown_wiki')
	));
}
elgg_register_menu_item('page', array(
	'name' => 'dconvert-markdown_blog',
	'section' => 'convert',
	'href' => elgg_add_action_tokens_to_url("/action/etherpad/convert-markdown_blog?guid={$pad_guid}"),
	'text' => elgg_echo('etherpad:convert:markdown_blog')
));

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('etherpad/sidebar', array('pad_guid' => $pad_guid))
));

echo elgg_view_page($title, $body);
