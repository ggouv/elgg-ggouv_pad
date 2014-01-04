<?php
/**
 * Elgg ggouv_pad plugin
 *
 * @package Elgg-ggouv_pad
 */

elgg_register_event_handler('init', 'system', 'pad_init');


function pad_init() {

	elgg_register_library('pad:utilities', elgg_get_plugins_path() . 'elgg-ggouv_pad/lib/utilities.php');
	elgg_register_library('pad:markdownify', elgg_get_plugins_path() . 'elgg-ggouv_pad/vendors/markdownify/markdownify_extra.php');

	$actions_base = elgg_get_plugins_path() . 'elgg-ggouv_pad/actions/pad';
	elgg_register_action("pad/save", "$actions_base/save.php");
	elgg_register_action("pad/delete", "$actions_base/delete.php");
	elgg_register_action("pad/convert-markdown_wiki", "$actions_base/convertToMarkdown_wiki.php");
	elgg_register_action("pad/convert-markdown_blog", "$actions_base/convertToMarkdown_blog.php");
	elgg_register_action("pad/close", "$actions_base/close.php");

	elgg_register_page_handler('pad', 'pad_page_handler');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'pad_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'pad_container_permission_check');

	// Extend view
	elgg_extend_view('css/elgg', 'pad/css');
	elgg_extend_view('js/elgg', 'pad/js');

	// Language short codes must be of the form "pad:key"
	// where key is the array key below
	elgg_set_config('pad', array(
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'access_id' => 'access',
		'write_access_id' => 'access',
	));

	elgg_register_entity_type('object', 'pad', 'ElggPad');

	//Widget
	elgg_register_widget_type('pad', elgg_echo('pad'), elgg_echo('pad:profile:widgetdesc'));

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'pad_owner_block_menu');

	// add to groups
	add_group_tool_option('pad', elgg_echo('groups:enablepads'), false);
	elgg_extend_view('groups/tool_latest', 'pad/group_module');

	// Register a URL handler for bookmarks
	elgg_register_entity_url_handler('object', 'pad', 'pad_url');

	// Register cron to delete pad
	elgg_register_plugin_hook_handler('cron', 'daily', 'delete_pad_cron');

}


function pad_page_handler($page, $handler) {

	elgg_load_library('pad:utilities');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('pad'), 'pad/all');

	$base_dir = elgg_get_plugins_path() . "elgg-ggouv_pad/pages/pad";

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			include "$base_dir/owner.php";
			break;
		case 'friends':
			include "$base_dir/friends.php";
			break;
		case 'view':
			set_input('guid', $page[1]);
			include "$base_dir/view.php";
			break;
		case 'add':
			set_input('guid', $page[1]);
			include "$base_dir/new.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$base_dir/edit.php";
			break;
		case 'group':
			include "$base_dir/owner.php";
			break;
		case 'history':
			set_input('guid', $page[1]);
			include "$base_dir/history.php";
			break;
		case 'all':
			include "$base_dir/world.php";
			break;
		default:
			return false;
	}
	return true;
}



/**
 * Override the pad url
 *
 * @param ElggObject $entity Pad object
 * @return string
 */
function pad_url($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "pad/view/$entity->guid/$title";
}

/**
 * Add a menu item to the user ownerblock
 */
function pad_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "pad/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('pad', elgg_echo('pad'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->pad_enable == "yes") {
			$url = "pad/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('pad', elgg_echo('pad:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}


/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function pad_write_permission_check($hook, $entity_type, $returnvalue, $params)
{
	if ($params['entity']->getSubtype() == 'pad') {

		$write_permission = $params['entity']->write_access_id;
		$user = $params['user'];

		if (($write_permission) && ($user)) {
			// $list = get_write_access_array($user->guid);
			$list = get_access_array($user->guid); // get_access_list($user->guid);

			if (($write_permission != 0) && (in_array($write_permission, $list))) {
				return true;
			}
		}
	}
}


/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function pad_container_permission_check($hook, $entity_type, $returnvalue, $params) {

	if (elgg_get_context() == 'pad') {
		if (elgg_get_page_owner_guid()) {
			if (can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) return true;
		}
		if ($page_guid = get_input('page_guid',0)) {
			$entity = get_entity($page_guid);
		} else if ($parent_guid = get_input('parent_guid',0)) {
			$entity = get_entity($parent_guid);
		}
		if ($entity instanceof ElggObject) {
			if (
					can_write_to_container(elgg_get_logged_in_user_guid(), $entity->container_guid)
					|| in_array($entity->write_access_id,get_access_list())
				) {
					return true;
			}
		}
	}

}



function delete_pad_cron($hook, $entity_type, $returnvalue, $params) {
	$errors = array();
	$time = time();
	$one_month = 60*60*24 * 30;

	$options = array(
		'types' => 'object',
		'subtype' => 'pad',
		'private_setting_name_value_pairs' => array('status' => 'open'),
		'created_time_upper' => $time - $one_month*3,
		'limit' => 0
	);

	$batch = new ElggBatch('elgg_get_entities_from_private_settings', $options);

	foreach ($batch as $pad) {
		try {
			$pad = new ElggPad($pad->guid);
			$lastEdited = $pad->getLastEdited()/1000;

			if ( $lastEdited < ($time - $one_month) ) {
				$pad->closePad();
			}
		} catch (Exception $e){
			$errors[$pad->getGUID()] = $e;
		}
		unset($pad);
	}



	// send email if error
	if (!empty($errors)) {
		$body = '';
		foreach($errors as $key => $error) {
			$body .= '<strong>' . $key . '</strong><br>';
			$body .= $error . '<br><br>';
		}

		// send mail to site email
		$mail = elgg_get_config('siteemail');
		elgg_send_email($mail, $mail, elgg_echo('pad:cron:mail:subject'), $body);
	}
}

