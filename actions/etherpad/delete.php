<?php
/**
 * Remove a pad
 *
 * Subpages are not deleted but are moved up a level in the tree
 *
 * @package ElggPad
 */

$guid = get_input('guid');
$page = new ElggPad($guid);
if ($page) {
	if ($page->canEdit()) {
		$container = get_entity($page->container_guid);
		
		if ($page->delete()) {
			system_message(elgg_echo('etherpad:delete:success'));
			
			if (elgg_instanceof($container, 'group')) {
				forward("pad/group/$container->guid/all");
			} else {
				forward("pad/owner/$container->username");
			}
			
		}
	}
}

register_error(elgg_echo('etherpad:delete:failure'));
forward(REFERER);
