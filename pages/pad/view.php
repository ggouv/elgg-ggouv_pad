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
	elgg_push_breadcrumb($container->name, "pad/group/{$container->guid}/all");
} else {
	elgg_push_breadcrumb($container->name, "pad/owner/$container->username");
}
elgg_push_breadcrumb($title);

$content = elgg_view_entity($pad, array('full_view' => true));


$content .= elgg_view_comments($pad, true);

if ($pad->getPrivateSetting('status') == 'open') {
	elgg_register_menu_item('page', array(
		'name' => 'toggle-comment',
		'section' => 'A',
		'href' => '#',
		'text' => elgg_echo('pad:toggle_comment'),
	));
}

elgg_register_menu_item('page', array(
	'name' => 'toggle-markdown-preview',
	'section' => 'A',
	'href' => '#',
	'text' => elgg_echo('pad:toggle_markdown-preview'),
	'item_class' => 'hidden'
));

if (elgg_instanceof($container, 'group') && $group->markdown_wiki_enable != 'no') {
	elgg_register_menu_item('page', array(
		'name' => 'convert-markdown_wiki',
		'section' => 'convert',
		'href' => elgg_add_action_tokens_to_url("/action/pad/convert-markdown_wiki?guid={$pad_guid}"),
		'text' => elgg_echo('pad:convert:markdown_wiki')
	));
}
elgg_register_menu_item('page', array(
	'name' => 'dconvert-markdown_blog',
	'section' => 'convert',
	'href' => elgg_add_action_tokens_to_url("/action/pad/convert-markdown_blog?guid={$pad_guid}"),
	'text' => elgg_echo('pad:convert:markdown_blog')
));

if ($pad->canEdit() && $pad->getPrivateSetting('status') == 'open') {
	elgg_register_menu_item('page', array(
		'name' => 'eclose',
		'section' => 'convert',
		'href' => elgg_add_action_tokens_to_url("/action/pad/close?guid={$pad_guid}"),
		'confirm' => elgg_echo('pad:close:confirm'),
		'text' => elgg_echo('pad:close')
	));
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pad/sidebar', array('pad_guid' => $pad_guid))
));

echo elgg_view_page($title, $body);
