<?php
/**
 * List a user's friends' pads
 *
 * @package ElggPad
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('pad/all');
}

elgg_push_breadcrumb($owner->name, "pad/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

$title = elgg_echo('pad:friends');

$content = list_user_friends_objects($owner->guid, 'pad', 10, false);
if (!$content) {
	$content = elgg_echo('pad:none');
}

$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
