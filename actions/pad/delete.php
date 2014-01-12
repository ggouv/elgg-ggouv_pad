<?php
/**
 * Remove a pad
 *
 * @package ElggPad
 */

$guid = get_input('guid');
$pad = new ElggPad($guid);

if ($pad) {
	if ($pad->canEdit()) {

		if ($pad->delete()) {
			system_message(elgg_echo('pad:delete:success'));

			$container = get_entity($pad->container_guid);

			if (elgg_instanceof($container, 'group')) {
				forward("pad/group/$container->guid/all");
			} else {
				forward("pad/owner/$container->username");
			}

		}
	}
}

register_error(elgg_echo('pad:delete:failure'));
forward(REFERER);
