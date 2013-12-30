<?php
/**
 * Sidebar
 *
 * @package ElggPad
 */

$pad_guid = elgg_extract('pad_guid', $vars, FALSE);

if ($pad_guid) { // pad view

	$etherpad = get_entity($pad_guid);

	if ($etherpad->getPrivateSetting('status') == 'open') {
		try {
			$etherpad = new ElggPad($pad_guid);

			// infos
			$title = elgg_echo('etherpad:infos');

			$lastedit = elgg_view('output/friendlytime', array(
				'time' => $etherpad->getLastEdited()/1000
			));
			$body = elgg_echo('etherpad:lastedited', array($lastedit));
			$body .= '<br/>' . elgg_echo('etherpad:revisions', array('<span id="pad-revisions-count">' . $etherpad->getRevisionsCount() . '</span>'));
			echo elgg_view_module('aside', $title, $body, array('id' => 'pad-infos'));

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

		} catch (Exception $e){
			return false;
		}
	} else {
		// infos
		$title = elgg_echo('etherpad:infos');

		$md = elgg_get_metadata(array(
			'guid' => $pad_guid,
			'metadata_name' => 'infos',
			'limit' => 1,
		));
		$infos = unserialize($md[0]->value);

		$lastedit = elgg_view('output/friendlytime', array(
			'time' => $infos[0]
		));
		$body = elgg_echo('etherpad:lastedited', array($lastedit));
		$body .= '<br/>' . elgg_echo('etherpad:revisions', array($infos[1]));
		echo elgg_view_module('aside', $title, $body);

		// contributors
		$title = elgg_echo('etherpad:contributors');
		$body = '';
		$authors = elgg_get_entities_from_relationship(array(
			'relationship_guid' => $pad_guid,
			'relationship' => 'contributed_to',
			'inverse_relationship' => true,
			'limit' => 0
		));

		foreach($authors as $author) {
			$body .= elgg_view_entity_icon($author, 'small');
		}
		echo elgg_view_module('aside', $title, $body);
	}

} else {

	if ($container_guid = elgg_get_page_owner_guid()) { // group view
		$params = array(
			'subtype' => 'etherpad',
			'container_guid' => elgg_get_page_owner_guid()
		);
	} else { // all view
		$params = array(
			'subtype' => 'etherpad'
		);
	}

	echo elgg_view('page/elements/comments_block', $params);

	echo elgg_view('page/elements/tagcloud_block', $params);

}

