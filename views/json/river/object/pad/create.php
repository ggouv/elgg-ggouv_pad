<?php
/**
 * New pad river entry
 *
 * @package Elgg-ggouv_pad
 */
global $jsonexport;

$mention = elgg_extract('mention', $vars, false);

$object = $vars['item']->getObjectEntity();

if ($object->getPrivateSetting('status') == 'open') {
	$excerpt = $object->description;
} else {
	$desc = unserialize($object->description);
	$excerpt = strip_tags($desc[0]);
}

if ($mention) {
	$vars['item']->message = deck_river_highlight_mention(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $excerpt), $mention);
} else {
	$vars['item']->message = elgg_get_excerpt(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $excerpt), 140);
}

$vars['item']->summary = elgg_view('river/elements/summary', array('item' => $vars['item']), FALSE, FALSE, 'default');

$jsonexport['results'][] = $vars['item'];