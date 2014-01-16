<?php
/**
 * New pad river entry
 *
 * @package Elgg-ggouv_pad
 */

$object = $vars['item']->getObjectEntity();

$desc = unserialize($object->description);
$excerpt = elgg_get_excerpt(strip_tags($desc[0]), 140);

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));
