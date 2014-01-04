<?php
/**
 * pads English language file
 *
 * package ElggPad
 */

$english = array(

	/**
	 * Menu items and titles
	 */

	'pad' => "Pads",
	'pad:owner' => "%s's pads",
	'pad:friends' => "Friends' pads",
	'pad:all' => "All site pads",
	'pad:add' => "Add pad",
	'pad:timeslider' => 'History',
	'pad:fullscreen' => 'Fullscreen',
	'pad:none' => 'No pads created yet',
	'pad:status:closed' => "Closed",
	'pad:infos:closed' => "&nbsp; %s by %s",

	'pad:group' => 'Group pads',
	'groups:enablepads' => 'Enable group pads',
	'pad:toggle_comment' => "Show/hide comments",
	'pad:toggle_markdown-preview' => "Show/hide preview",
	'pad:infos' => "Informations",
	'pad:lastedited' => "Edited %s",
	'pad:revisions' => "%s revisions",
	'pad:contributors' => "Contributors of this pad :",
	'pad:convert:markdown_wiki' => "Convert this pad to wiki page",
	'pad:convert:markdown_blog' => "Convert this pad to blog article",
	'pad:close' => "Close this pad",
	'pad:close:confirm' => "Voulez-vous vraiment fermer ce pad ? Il ne sera plus possible de l'éditer et l'historique sera supprimé. Le texte, les auteurs et le nombre de révisions seront conservés.",
	'pad:create:info' => "Pads are not permanant, don't use it like a definitive article.",


	/**
	 * River
	 */
	'river:create:object:pad' => '%s created a new collaborative pad %s',
	'river:update:object:pad' => '%s updated the collaborative pad %s',
	'river:comment:object:pad' => '%s commented on the collaborative pad %s',

	'item:object:pad' => 'Pads',

	/**
	 * Status messages
	 */

	'pad:saved' => "Your pad was successfully saved.",
	'pad:delete:success' => "Your pad was successfully deleted.",
	'pad:delete:failure' => "Your pad could not be deleted. Please try again.",
	'pad:Empty or No Response from the server' => "Empty or No Response from the server",
	'pad:close:success' => "Your pad was successfully closed.",
	'pad:close:failure' => "Your pad could not be closed. Please try again.",

	/**
	 * Edit page
	 */

	 'pad:title' => "Title",
	 'pad:tags' => "Tags",
	 'pad:description' => "Description",
	 'pad:access_id' => "Read access",
	 'pad:write_access_id' => "Write access",

	/**
	 * Admin settings
	 */

	'pad:padhost' => "pad lite host address:",
	'pad:padkey' => "pad lite api key:",
	'pad:showchat' => "Show chat?",
	'pad:linenumbers' => "Show line numbers?",
	'pad:showcontrols' => "Show controls?",
	'pad:monospace' => "Use monospace font?",
	'pad:showcomments' => "Show comments?",
	'pad:newpadtext' => "New pad text:",
	'pad:pad:message' => 'New pad created successfully.',
	'pad:cron:mail:subject' => 'Errors with cron pad',

	/**
	 * Widget
	 */
	'pad:profile:numbertodisplay' => "Number of pads to display",
	'pad:profile:widgetdesc' => "Display your latest pads",

);

add_translation('en', $english);
