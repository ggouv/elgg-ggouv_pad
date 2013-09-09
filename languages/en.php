<?php
/**
 * Etherpads English language file
 *
 * package ElggPad
 */

$english = array(

	/**
	 * Menu items and titles
	 */

	'etherpad' => "Pads",
	'etherpad:owner' => "%s's pads",
	'etherpad:friends' => "Friends' pads",
	'etherpad:all' => "All site pads",
	'etherpad:add' => "Add pad",
	'etherpad:timeslider' => 'History',
	'etherpad:fullscreen' => 'Fullscreen',
	'etherpad:none' => 'No pads created yet',
	'etherpad:status:closed' => "Closed",
	'etherpad:infos:closed' => "&nbsp; %s by %s",

	'etherpad:group' => 'Group pads',
	'groups:enablepads' => 'Enable group pads',
	'etherpad:toggle_comment' => "Show/hide comments",
	'etherpad:infos' => "Informations",
	'etherpad:lastedited' => "Edited %s",
	'etherpad:revisions' => "%s revisions",
	'etherpad:contributors' => "Contributors of this pad :",
	'etherpad:convert:markdown_wiki' => "Convert this pad to wiki page",
	'etherpad:convert:markdown_blog' => "Convert this pad to blog article",
	'etherpad:create:info' => "Pads are not permanant, don't use it like a definitive article.",


	/**
	 * River
	 */
	'river:create:object:etherpad' => '%s created a new collaborative pad %s',
	'river:update:object:etherpad' => '%s updated the collaborative pad %s',
	'river:comment:object:etherpad' => '%s commented on the collaborative pad %s',

	'item:object:etherpad' => 'Pads',

	/**
	 * Status messages
	 */

	'etherpad:saved' => "Your pad was successfully saved.",
	'etherpad:delete:success' => "Your pad was successfully deleted.",
	'etherpad:delete:failure' => "Your pad could not be deleted. Please try again.",
	'etherpad:Empty or No Response from the server' => "Empty or No Response from the server",

	/**
	 * Edit page
	 */

	 'etherpad:title' => "Title",
	 'etherpad:tags' => "Tags",
	 'etherpad:description' => "Description",
	 'etherpad:access_id' => "Read access",
	 'etherpad:write_access_id' => "Write access",

	/**
	 * Admin settings
	 */

	'etherpad:etherpadhost' => "Etherpad lite host address:",
	'etherpad:etherpadkey' => "Etherpad lite api key:",
	'etherpad:showchat' => "Show chat?",
	'etherpad:linenumbers' => "Show line numbers?",
	'etherpad:showcontrols' => "Show controls?",
	'etherpad:monospace' => "Use monospace font?",
	'etherpad:showcomments' => "Show comments?",
	'etherpad:newpadtext' => "New pad text:",
	'etherpad:pad:message' => 'New pad created successfully.',
	'etherpad:cron:mail:subject' => 'Errors with cron etherpad',

	/**
	 * Widget
	 */
	'etherpad:profile:numbertodisplay' => "Number of pads to display",
	'etherpad:profile:widgetdesc' => "Display your latest pads",

);

add_translation('en', $english);
