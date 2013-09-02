<?php
/**
 * Sidebar
 *
 * @package ElggPad
 */

$pad_guid = elgg_extract('pad_guid', $vars, FALSE);

if ($pad_guid) {

	$etherpad = new ElggPad($pad_guid);

	// infos
	$title = elgg_echo('etherpad:infos');

	$lastedit = elgg_view('output/friendlytime', array(
		'time' => $etherpad->getLastEdited()/1000
	));
	$body = elgg_echo('etherpad:lastedited', array($lastedit));
	$body .= '<br/>' . elgg_echo('etherpad:revisions', array($etherpad->getRevisionsCount()));
	echo elgg_view_module('aside', $title, $body);

	// contributors
	$title = elgg_echo('etherpad:contributors');
	$body = '';

	$authors = $etherpad->listAuthorsNamesOfPad();
	$authors = array_unique($authors);
	foreach($authors as $author) {
		$user = get_user_by_username($author);
		$body .= elgg_view_entity_icon($user, 'small');
	}
	echo elgg_view_module('aside', $title, $body);
}
