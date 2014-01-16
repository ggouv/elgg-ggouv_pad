<?php
/**
 * New pad river entry
 *
 * @package Elgg-ggouv_pad
 */
global $jsonexport;

$mention = elgg_extract('mention', $vars, false);

$object = $vars['item']->getObjectEntity();

$desc = unserialize($object->description);
if ($mention) {
	$vars['item']->message = deck_river_highlight_mention(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $desc[0] . $desc[1]), $mention);
} else {
	$vars['item']->message = elgg_get_excerpt(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $desc[0]), 140);
}

$vars['item']->summary = elgg_view('river/elements/summary', array('item' => $vars['item']), FALSE, FALSE, 'default');

$jsonexport['results'][] = $vars['item'];