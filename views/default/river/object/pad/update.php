<?php
/**
 * New pad river entry
 *
 * @package Elgg-ggouv_pad
 */

$object = $vars['item']->getObjectEntity();

if ($object->getPrivateSetting('status') == 'open') {
	$excerpt = strip_tags($object->description);
	$excerpt = elgg_get_excerpt($excerpt, 140);
} else {
	$desc = json_decode($object->description);
	$excerpt = elgg_get_excerpt(strip_tags($desc->description), 140);
}

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));
