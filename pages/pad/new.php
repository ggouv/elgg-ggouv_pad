<?php
/**
 * Create a new pad
 *
 * @package ElggPad
 */

gatekeeper();

$container = elgg_get_page_owner_entity();
if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "pad/group/{$container->guid}/all");
} else {
	elgg_push_breadcrumb($container->name, "pad/owner/$container->username");
}

$title = elgg_echo('pad:add');
elgg_push_breadcrumb($title);

$content = elgg_view('output/longtext', array(
	'value' => elgg_echo('pad:create:info'),
	'class' => 'pbm'
));
$vars = pad_prepare_form_vars(null);
$content .= elgg_view_form('pad/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
