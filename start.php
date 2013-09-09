<?php
/**
 * Elgg Etherpad lite plugin
 *
 * @package etherpad
 */

elgg_register_event_handler('init', 'system', 'etherpad_init');


function etherpad_init() {

	elgg_register_library('etherpad:utilities', elgg_get_plugins_path() . 'elgg-ggouv_pad/lib/utilities.php');
	elgg_register_library('etherpad:markdownify', elgg_get_plugins_path() . 'elgg-ggouv_pad/vendors/markdownify/markdownify_extra.php');

	$actions_base = elgg_get_plugins_path() . 'elgg-ggouv_pad/actions/etherpad';
	elgg_register_action("etherpad/save", "$actions_base/save.php");
	elgg_register_action("etherpad/delete", "$actions_base/delete.php");
	elgg_register_action("etherpad/convert-markdown_wiki", "$actions_base/convertToMarkdown_wiki.php");
	elgg_register_action("etherpad/convert-markdown_blog", "$actions_base/convertToMarkdown_blog.php");

	elgg_register_page_handler('etherpad', 'etherpad_page_handler');

	// Extend view
	elgg_extend_view('css/elgg', 'etherpad/css');
	elgg_extend_view('js/elgg', 'etherpad/js');

	// Language short codes must be of the form "etherpad:key"
	// where key is the array key below
	elgg_set_config('etherpad', array(
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'access_id' => 'access',
		'write_access_id' => 'access',
	));

	elgg_register_plugin_hook_handler('register', 'menu:etherpad', 'etherpad_entity_menu');

	elgg_register_entity_type('object', 'etherpad', 'ElggPad');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'etherpad_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'etherpad_container_permission_check');

	//Widget
	elgg_register_widget_type('etherpad', elgg_echo('etherpad'), elgg_echo('etherpad:profile:widgetdesc'));

	// don't need it for ggouv
//	$item = new ElggMenuItem('etherpad', elgg_echo('etherpad'), 'etherpad/all');
//	elgg_register_menu_item('site', $item);

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'etherpad_owner_block_menu');

	// add to groups
	add_group_tool_option('etherpad', elgg_echo('groups:enablepads'), false);
	elgg_extend_view('groups/tool_latest', 'etherpad/group_module');

	// Register a URL handler for bookmarks
	elgg_register_entity_url_handler('object', 'etherpad', 'etherpad_url');

	// Register cron to delete etherpad
	elgg_register_plugin_hook_handler('cron', 'daily', 'delete_etherpad_cron');

}


function etherpad_page_handler($page, $handler) {

	elgg_load_library('etherpad:utilities');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('etherpad'), "etherpad/all");

	$base_dir = elgg_get_plugins_path() . "elgg-ggouv_pad/pages/etherpad";

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
 * Add timeslider to entity menu
 */
function etherpad_entity_menu($hook, $type, $return, $params) {
	$entity = $params['entity'];

	if (elgg_in_context('widgets')) {
		return $return;
	}

	if ($entity->getSubtype() != 'etherpad') {
		return $return;
	}

	// access
	$access = elgg_view('output/access', array('entity' => $entity));
	$options = array(
		'name' => 'access',
		'text' => $access,
		'item_class' => 'prm',
		'href' => false,
		'priority' => 100,
	);
	$return[] = ElggMenuItem::factory($options);

	if ($entity->canEdit()) {
		// edit link
		$options = array(
			'name' => 'edit',
			'text' => '&#9998;', // unicode 270E
			'title' => elgg_echo('edit:this'),
			'class' => 'gwf tooltip s',
			'href' => "pad/edit/{$entity->getGUID()}",
			'priority' => 200,
		);
		$return[] = ElggMenuItem::factory($options);

		// delete link
		$options = array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'title' => elgg_echo('delete:this'),
			'class' => 'tooltip s',
			'href' => "action/etherpad/delete?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 300,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
* Returns a more meaningful message
*
* @param unknown_type $hook
* @param unknown_type $entity_type
* @param unknown_type $returnvalue
* @param unknown_type $params
*/
function etherpad_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && (($entity->getSubtype() == 'etherpad'))) {
		$descr = $entity->description;
		$title = $entity->title;
		//@todo why?
		$url = elgg_get_site_url() . "view/" . $entity->guid;
		$owner = $entity->getOwnerEntity();
		return $owner->name . ' ' . elgg_echo("pages:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
	}
	return null;
}

/**
 * Override the etherpad url
 *
 * @param ElggObject $entity Pad object
 * @return string
 */
function etherpad_url($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "etherpad/view/$entity->guid/$title";
}

/**
 * Add a menu item to the user ownerblock
 */
function etherpad_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "etherpad/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('etherpad', elgg_echo('etherpad'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->etherpad_enable == "yes") {
			$url = "etherpad/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('etherpad', elgg_echo('etherpad:group'), $url);
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
function etherpad_write_permission_check($hook, $entity_type, $returnvalue, $params)
{
	if ($params['entity']->getSubtype() == 'etherpad') {

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
function etherpad_container_permission_check($hook, $entity_type, $returnvalue, $params) {

	if (elgg_get_context() == "etherpad") {
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


function delete_etherpad_cron($hook, $entity_type, $returnvalue, $params) {
	$errors = array();
	$one_month = 60*60*24 * 30;

	$options = array(
		'types' => 'object',
		'subtype' => 'etherpad',
		'private_setting_name_value_pairs' => array('status' => 'open'),
		'created_time_upper' => time() - $one_month*3,
		'limit' => 0
	);

	$batch = new ElggBatch('elgg_get_entities_from_private_settings', $options);

	foreach ($batch as $pad) {
		try {
			$pad = new ElggPad($pad->guid);
			$lastEdited = $pad->getLastEdited()/1000;

			if ( $lastEdited < (time() - $one_month) ) {
				$pad->closePad();
			}
		} catch (Exception $e){
			$errors[$pad->getGUID()] = $e;
		}
		unset($pad);
	}



	// send email if error
	if (false && !empty($errors)) {
		$body = '';
		foreach($errors as $key => $error) {
			$body .= '<strong>' . $key . '</strong><br>';
			$body .= $error . '<br><br>';
		}

		// send mail to site email
		$mail = elgg_get_config('siteemail');
		elgg_send_email($mail, $mail, elgg_echo('etherpad:cron:mail:subject'), $body);
	}
}

