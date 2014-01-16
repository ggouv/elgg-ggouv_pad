<?php
/**
 * View for pad object
 *
 * @package ElggPad
 *
 * @uses $vars['entity']    The pad object
 * @uses $vars['full_view'] Whether to display the full view
 */


$full = elgg_extract('full_view', $vars, FALSE);
$pad = elgg_extract('entity', $vars, FALSE);
$timeslider = elgg_extract('timeslider', $vars, FALSE);
$show_group = elgg_extract('show_group', $vars, FALSE);

if (!$pad || !elgg_instanceof($pad, 'object', 'pad')) {
	return TRUE;
}

$pad = new ElggPad($pad->guid);
$container = $pad->getContainerEntity();

// pages used to use Public for write access
if ($pad->write_access_id == ACCESS_PUBLIC) {
	// this works because this metadata is public
	$pad->write_access_id = ACCESS_LOGGED_IN;
}

//link to owners pages only if pages integration is enabled. Else link to owners pads.
$editor = get_entity($pad->owner_guid);
$editor_link = elgg_view('output/url', array(
	'href' => "pad/owner/$editor->username",
	'text' => $editor->name,
	'is_trusted' => true,
));

$date = elgg_view_friendly_time($pad->time_created);
$editor_text = elgg_echo('byline', array($editor_link));
$tags = elgg_view('output/tags', array('tags' => $pad->tags));
$categories = elgg_view('output/categories', $vars);

$comments_count = $pad->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $pad->getURL() . '#comments',
		'text' => $text,
		'is_trusted' => true,
	));
} else {
	$comments_link = '';
}

if ($show_group && elgg_instanceof($container, 'group')) {
	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	));
	$group_text = elgg_echo('groups:ingroup') . ' ' . $group_link;
} else {
	$group_text = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $pad,
	'handler' => 'pad',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-entity elgg-menu-hz',
));

$subtitle = "$editor_text $group_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {
	if ($pad->getPrivateSetting('status') == 'open') {
		try {

			$body = '<div class="pad-wrapper float">';
			if ($container->canWriteToContainer()) {
				$body .= elgg_view('output/iframe', array(
					'value' => $pad->getPadPath(),
					'class' => 'pad-iframe float',
					'width' => '100%',
					'height' => '400px',
					'frameborder' => '0'
				));

				$tabs['preview'] = array(
					'text' => elgg_echo('markdown_wiki:preview'),
					'href' => "#",
					'selected' => true,
					'priority' => 200,
				);
				$tabs['help'] = array(
					'text' => elgg_echo('markdown_wiki:syntax'),
					'href' => "#",
					'priority' => 300,
				);

				foreach ($tabs as $name => $tab) {
					$tab['name'] = $name;
					elgg_register_menu_item('markdown', $tab);
				}

				$body .= elgg_view_menu('markdown', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz markdown-menu prs t25 hidden'));
				$body .= '<div class="pane-markdown hidden"><div id="md-preview-pad" class="pane preview-markdown markdown-body mlm pas"></div><div class="pane help-markdown hidden mlm pas"></div></div>';
			} else {
				$body .= elgg_view('output/longtext', array(
					'value' => $pad->getPadMarkdown(),
					'class' => 'mt'
				));
			}

			$body .= '</div>';


		} catch(Exception $e) {
			$body = elgg_echo('pad:'. $e->getMessage());
		}
	} else {
		$md = elgg_get_metadata(array(
			'guid' => $pad->getGUID(),
			'metadata_name' => 'infos',
			'limit' => 0,
		));
		$desc = unserialize($pad->description);
		$status = '<span class="status declined">' . elgg_echo('pad:status:closed') . '</span>';
		$time = elgg_get_friendly_time($md[0]->time_created);
		$owner = get_entity($md[0]->owner_guid);
		if ($owner) { // pad closed by someone
			$owner_text = elgg_view('output/url', array(
				'text' => $owner->username,
				'href' => $owner->getURL()
			));
		} else { // pad closed by cron
			$owner_text = elgg_get_site_entity()->name;
		}
		$body = '<div class="elgg-heading-basic pam mvm">';
		$body .= elgg_view('output/longtext', array(
			'value' => $desc[0],
			'class' => 'mtn'
		));

		$body .= '<div class="ptm">' . $status . elgg_echo('pad:infos:closed', array($time, $owner_text)) . '</div></div>';
		//$body .= '<div class="markdown-body">' . $pad->getPadMarkdown($desc[1]) . '</div>';
		$body .= elgg_view('output/longtext', array(
			'value' => $pad->getPadMarkdown($desc[1])
		));
	}
	$params = array(
		'entity' => $pad,
		'metadata' => $metadata,
		'title' => false,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'entity' => $pad,
		'summary' => $summary,
		'body' => $body,
	));

} else {
	// brief view

	if ($pad->getPrivateSetting('status') == 'open') {
		$excerpt = elgg_get_excerpt($pad->description);
	} else {
		$desc = unserialize($pad->description);
		$excerpt = $desc[0];
		$status = '<span class="status declined mlm">' . elgg_echo('pad:status:closed') . '</span>';
	}

	$params = array(
		'entity' => $pad,
		'metadata' => $metadata,
		'subtitle' => $subtitle . $status,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	if ($pad->getPrivateSetting('status') == 'open') {
		echo '<div>' . elgg_view('object/elements/summary', $params) . '</div>';
	} else {
		$summary = elgg_view('object/elements/summary', $params);

		$md = elgg_get_metadata(array(
			'guid' => $pad->getGUID(),
			'metadata_name' => 'infos',
			'limit' => 1,
		));
		$infos = unserialize($md[0]->value);
		$lastedit = elgg_view('output/friendlytime', array(
			'time' => $infos[0]
		));
		$lastedit = elgg_echo('pad:lastedited', array($lastedit));
		$revs = elgg_echo('pad:revisions', array($infos[1]));

		// contributors
		$contributors = elgg_echo('pad:contributors');
		$body = '';
		$authors = elgg_get_entities_from_relationship(array(
			'relationship_guid' => $pad->getGUID(),
			'relationship' => 'contributed_to',
			'inverse_relationship' => true,
			'limit' => 0
		));

		foreach($authors as $author) {
			$body .= elgg_view_entity_icon($author, 'tiny');
		}

		echo <<<HTML
<div class="row-fluid">
	<div class="span6">
		$summary
	</div>
	<div class="elgg-heading-basic pam mvs span6">
		<div class="row-fluid">
			<div class="span6">
				<h3 class="elgg-loud mbs">$contributors</h3>
				$body
			</div>
			<div class="span6">
				<h3 class="elgg-quiet mbs">$lastedit</h3>
				<h3 class="elgg-quiet">$revs</h3>
			</div>
		</div>
	</div>
</div>
HTML;

	}

}
