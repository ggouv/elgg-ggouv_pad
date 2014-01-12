<?php
/**
 * Sidebar
 *
 * @package ElggPad
 */

$pad_guid = elgg_extract('pad_guid', $vars, FALSE);

if ($pad_guid) { // pad view

	$pad = get_entity($pad_guid);

	if ($pad->getPrivateSetting('status') == 'open') {
		// infos and contributors are filled by etherpad plugin ep_ggouv
		$lastedit = elgg_view('output/friendlytime', array(
			'time' => ''
		));
		$body = elgg_echo('pad:lastedited', array($lastedit));
		$body .= '<br/><span id="pad-revisions-count"></span><span id="pad-savedRevisions-count"></span>';
		echo elgg_view_module('aside ptm', elgg_echo('pad:infos'), $body, array(
			'id' => 'pad-infos',
			'class' => 'hidden'
		));
		echo elgg_view_module('aside', elgg_echo('pad:contributors'), '<ul></ul>', array(
			'id' => 'pad-authors',
			'class' => 'hidden'
		));
	} else {
		// infos
		$title = elgg_echo('pad:infos');

		$md = elgg_get_metadata(array(
			'guid' => $pad_guid,
			'metadata_name' => 'infos',
			'limit' => 1,
		));
		$infos = unserialize($md[0]->value);

		$lastedit = elgg_view('output/friendlytime', array(
			'time' => $infos[0]
		));
		$body = elgg_echo('pad:lastedited', array($lastedit));
		$body .= '<br/>' . elgg_echo('pad:revisions', array($infos[1]));
		echo elgg_view_module('aside ptm', $title, $body);

		// contributors
		$title = elgg_echo('pad:contributors');
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
			'subtypes' => 'pad',
			'container_guid' => $container_guid
		);
	} else { // all view
		$params = array(
			'subtypes' => 'pad'
		);
	}

	echo elgg_view('page/elements/comments_block', $params);

	echo elgg_view('page/elements/tagcloud_block', $params);

}

