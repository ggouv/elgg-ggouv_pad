<?php
/**
 * Create or edit a pad
 *
 * @package ElggPad
 */

$variables = elgg_get_config('pad');
$input = array();
foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

// Get guids
$pad_guid = (int)get_input('page_guid');
$container_guid = (int)get_input('container_guid');

$container = get_entity($container_guid);

elgg_make_sticky_form('pad');

if (!$input['title']) {
	register_error(elgg_echo('pages:error:no_title'));
	forward(REFERER);
}

if (!$container->canWriteToContainer()) {
	register_error(elgg_echo('pages:error:no_save'));
	forward(REFERER);
}

if ($pad_guid) {
	$pad = new ElggPad($pad_guid);
	if (!$pad || !$pad->canEdit()) {
		register_error(elgg_echo('pages:error:no_save'));
		forward(REFERER);
	}
	$new_pad = false;
} else {
	$pad = new ElggPad();
	$new_pad = true;
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$pad->$name = $value;
	}
}

$pad->container_guid = $container_guid;

if ($pad->save()) {

	elgg_clear_sticky_form('pad');

	system_message(elgg_echo('pad:saved'));

	if ($new_pad) {
		set_private_setting($pad->getGUID(), 'status', 'open');
		add_to_river('river/object/pad/create', 'create', elgg_get_logged_in_user_guid(), $pad->getGUID());
	}

	forward($pad->getURL());
} else {
	register_error(elgg_echo('pad:error:no_save'));
	forward(REFERER);
}
