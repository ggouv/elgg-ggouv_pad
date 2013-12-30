<?php
/**
 * Elgg EtherPad
 *
 *
 */
class ElggPad extends ElggObject {

	protected $pad;
	protected $groupID;
	protected $authorID;

	/**
	 * Initialise the attributes array to include the type,
	 * title, and description.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "etherpad";
	}

	function save(){
		$guid = parent::save();

		try {
			$sessionID = $this->startSession();
			$groupID = $this->groupID;

			// Create a pad if not exists
			if (!$this->pname) {
				$name = uniqid();
				$this->get_pad_client()->createGroupPad($groupID, $name, elgg_get_plugin_setting('new_pad_text', 'elgg-ggouv_pad'));
				$this->setMetaData('pname', $groupID . "$" . $name);
			}

			$padID = $this->getMetadata('pname');

			//set etherpad permissions
			if($this->access_id == ACCESS_PUBLIC) {
				$this->get_pad_client()->setPublicStatus($padID, "true");
			} else {
				$this->get_pad_client()->setPublicStatus($padID, "false");
			}

			$this->get_pad_client()->deleteSession($sessionID);

		} catch (Exception $e){
			return false;
		}

		return $guid;
	}

	function delete(){
		if ($this->getPrivateSetting('status') == 'open') {
			try {
				$this->startSession();
				$this->get_pad_client()->deletePad($this->getMetaData('pname'));
			} catch(Exception $e) {
				return false;
			}
		}
		return parent::delete();
	}

	function closePad(){
		$authors = $this->listAuthorsNamesOfPad();
		$authors = array_unique($authors);

		$revisions = $this->getRevisionsCount();
		$lastedit = round($this->getLastEdited()/1000);

		// delete pad on etherpad database
		try {
			$this->startSession();
			$text = $this->getPadHTML();
			$this->get_pad_client()->deletePad($this->getMetaData('pname'));
		} catch(Exception $e) {
			return false;
		}

		set_private_setting($this->getGUID(), 'status', 'closed');

		$authors_guid = array();
		foreach($authors as $author) {
			$user = get_user_by_username($author);
			add_entity_relationship($user->getGUID(), 'contributed_to', $this->getGUID());
		}

		$ia = elgg_set_ignore_access(true);
		$this->deleteMetadata('pname');
		elgg_set_ignore_access($ia);

		create_metadata($this->getGUID(), 'text', $text, 'text');
		create_metadata($this->getGUID(), 'infos', serialize(array($lastedit, $revisions)), 'text');

		return true;
	}

	protected function get_pad_client(){
		if($this->pad){
			return $this->pad;
		}

		require_once(elgg_get_plugins_path() . 'elgg-ggouv_pad/vendors/etherpad-lite-client.php');

		// Etherpad: Create an instance
		$apikey = elgg_get_plugin_setting('etherpad_key', 'elgg-ggouv_pad');
		$apiurl = elgg_get_plugin_setting('etherpad_host', 'elgg-ggouv_pad') . "/api";
		$this->pad = new EtherpadLiteClient($apikey, $apiurl);
		return $this->pad;
	}

	protected function startSession(){
		if($this->container_guid) {
			$container_guid = $this->container_guid;
		} else {
			$container_guid = elgg_get_logged_in_user_guid();
		}
		//Etherpad: Create an etherpad group for the elgg container
		$mappedGroup = $this->get_pad_client()->createGroupIfNotExistsFor($container_guid);
		$this->groupID = $mappedGroup->groupID;

		//Etherpad: Create an author(etherpad user) for logged in user
		$author = $this->get_pad_client()->createAuthorIfNotExistsFor(elgg_get_logged_in_user_entity()->username);
		$this->authorID = $author->authorID;

		//Etherpad: Create session
		$validUntil = mktime(date("H"), date("i")+5, 0, date("m"), date("d"), date("y")); // 5 minutes in the future
		$session = $this->get_pad_client()->createSession($this->groupID, $this->authorID, $validUntil);
		$sessionID = $session->sessionID;

		$domain = "." . parse_url(elgg_get_site_url(), PHP_URL_HOST);

		if(!setcookie('sessionID', $sessionID, $validUntil, '/', $domain)){
			throw new Exception(elgg_echo('etherpad:error:cookies_required'));
		}

		return $sessionID;
	}

	protected function getAddress(){
		return elgg_get_plugin_setting('etherpad_host', 'elgg-ggouv_pad') . "/p/". $this->getMetadata('pname');
	}

	protected function getTimesliderAddress(){
		return $this->getAddress() . "/timeslider";
	}

	protected function getReadOnlyAddress(){
		if($this->getMetadata('readOnlyID')){
			$readonly = $this->getMetadata('readOnlyID');
		} else {
			$padID = $this->getMetadata('pname');
			$readonly = $this->get_pad_client()->getReadOnlyID($padID)->readOnlyID;
			$this->setMetaData('readOnlyID', $readonly);
		}
		return elgg_get_plugin_setting('etherpad_host', 'elgg-ggouv_pad') . "/ro/". $readonly;
	}

	function getPadPath($timeslider = false){
		$settings = array('show_controls', 'monospace_font', 'show_chat', 'line_numbers');

		if(elgg_is_logged_in()) {
			$name = elgg_get_logged_in_user_entity()->name;
		} else {
			$name = 'undefined';
		}

		array_walk($settings, function(&$setting) {
			if(elgg_get_plugin_setting($setting, 'elgg-ggouv_pad') == 'no') {
				$setting = 'false';
			} else {
				$setting = 'true';
			}
		});

		$options = '?' . http_build_query(array(
			'userName' => $name,
			'showControls' => $settings[0],
			'useMonospaceFont' => $settings[1],
			'showChat' => $settings[2],
			'showLineNumbers' => $settings[3],
		));

		$this->startSession();

		$container = $this->getContainerEntity();

		if($container->canWriteToContainer() && !$timeslider) {
			return $this->getAddress() . $options;
		} elseif ($container->canWriteToContainer() && $timeslider) {
			return $this->getTimesliderAddress() . $options;
		} else {
			return $this->getReadOnlyAddress() . $options;
		}
	}

	function getPadText(){
		$padID = $this->getMetadata('pname');
		return $this->get_pad_client()->getText($padID)->text;
	}

	function getPadHTML(){
		$padID = $this->getMetadata('pname');
		return $this->get_pad_client()->getHTML($padID)->html;
	}

	function getPadMarkdown(){
		elgg_load_library('etherpad:markdownify');

		$html = $this->getPadHTML();
		if (ini_get('magic_quotes_gpc')) {
			$html = stripslashes($html);
		}

		$md = new Markdownify_Extra(false, false, false);
		return $md->parseString($html);
	}

	function getPadUsers(){ // doesn't work ??
		$padID = $this->getMetadata('pname');
		return $this->get_pad_client()->padUsers($padID);
	}

	function getLastEdited(){
		$padID = $this->getMetadata('pname');
		return $this->get_pad_client()->getLastEdited($padID)->lastEdited;
	}

	function getPadUsersCount(){
		$padID = $this->getMetadata('pname');
		return $this->get_pad_client()->padUsersCount($padID)->padUsersCount;
	}

	function sendClientsMessage($msg){
		$padID = $this->getMetadata('pname');
		return $this->get_pad_client()->sendClientsMessage($padID, $msg);
	}

	function listAuthorsOfPad(){
		$padID = $this->getMetadata('pname');
		return $this->get_pad_client()->listAuthorsOfPad($padID)->authorIDs;
	}

	function listAuthorsNamesOfPad(){
		$authorsID = $this->listAuthorsOfPad();
		foreach($authorsID as $authorID) {
			$authorsNames[] = $this->getAuthorName($authorID);
		}
		return $authorsNames;
	}

	function getRevisionsCount(){
		$padID = $this->getMetadata('pname');
		return $this->get_pad_client()->getRevisionsCount($padID)->revisions;
	}

	function getAuthorName($authorID){
		return $this->get_pad_client()->getAuthorName($authorID);
	}
}



