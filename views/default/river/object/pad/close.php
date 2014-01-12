<?php
/**
 * New pad river entry
 *
 * @package Elgg-ggouv_pad
 */

$object = $vars['item']->getObjectEntity();

$desc = json_decode($object->description);
$excerpt = elgg_get_excerpt(strip_tags($desc->description), 140);

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));
