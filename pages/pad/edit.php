<?php
/**
 * Edit a pad
 *
 * @package ElggPad
 */

gatekeeper();

$pad_guid = (int)get_input('guid');
$pad = get_entity($pad_guid);
if (!$pad) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

$container = $pad->getContainerEntity();
if (!$container) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($pad->title, $pad->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo('pad:edit', array($pad->title));

if ($pad->canEdit()) {
	$content = elgg_view('output/longtext', array(
		'value' => elgg_echo('pad:create:info'),
		'class' => 'pbm'
	));
	$vars = pad_prepare_form_vars($pad);
	$content .= elgg_view_form('pad/save', array(), $vars);
} else {
	$content = elgg_echo("pages:noaccess");
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
