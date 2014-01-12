<?php
/**
 * New pad river entry
 *
 * @package Elgg-ggouv_pad
 */
global $jsonexport;

$object = $vars['item']->getObjectEntity();

$desc = json_decode($object->description);
$excerpt = elgg_get_excerpt(strip_tags($desc->description), 140);

$vars['item']->summary = elgg_view('river/elements/summary', array('item' => $vars['item']), FALSE, FALSE, 'default');
$vars['item']->message = $excerpt;

$jsonexport['results'][] = $vars['item'];