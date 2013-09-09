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
		$container = get_entity($pad->container_guid);

		if ($pad->closePad()) {
			system_message(elgg_echo('etherpad:close:success'));

			forward($pad->getURL());

		}
	}
}

register_error(elgg_echo('etherpad:close:failure'));
forward(REFERER);
