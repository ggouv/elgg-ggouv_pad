<?php
/**
 * Create a new pad
 *
 * @package ElggPad
 */

gatekeeper();

$title = elgg_echo('pad:add');
elgg_push_breadcrumb($title);

$content = elgg_view('output/longtext', array(
	'value' => elgg_echo('etherpad:create:info'),
	'class' => 'pbm'
));
$vars = etherpad_prepare_form_vars(null);
$content .= elgg_view_form('etherpad/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
