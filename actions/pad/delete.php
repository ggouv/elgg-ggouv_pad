<?php
/**
 * Remove a pad
 *
 * Subpages are not deleted but are moved up a level in the tree
 *
 * @package ElggPad
 */

$guid = get_input('guid');
$pad = new ElggPad($guid);
if ($pad) {
	if ($pad->canEdit()) {
		$container = get_entity($pad->container_guid);

		if ($pad->delete()) {
			system_message(elgg_echo('pad:delete:success'));

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
