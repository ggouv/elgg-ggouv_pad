<?php
/**
 * Group pages
 *
 * @package ElggPad
 */


$group = elgg_get_page_owner_entity();

if ($group->pad_enable == 'no') {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "pad/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));


elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtypes' => 'pad',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('pad:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "pad/add/$group->guid",
	'text' => elgg_echo('pad:add'),
	'is_trusted' => true,
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('pad:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
