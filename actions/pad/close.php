<?php
/**
 * Close a pad
 *
 * @package ElggPad
 */

$guid = get_input('guid');
$pad = new ElggPad($guid);

if ($pad) {

	if ($pad->canEdit()) {
		if ($pad->closePad()) {
			system_message(elgg_echo('pad:close:success'));

			add_to_river('river/object/pad/close', 'close', elgg_get_logged_in_user_guid(), $pad->getGUID());

			forward($pad->getURL());
		}
	}

}

register_error(elgg_echo('pad:close:failure'));
forward(REFERER);
