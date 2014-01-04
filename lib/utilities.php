<?php
/**
 * Pages function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $pad
 * @return array
 */
function pad_prepare_form_vars($pad = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $pad,
	);

	if ($pad) {
		foreach (array_keys($values) as $field) {
			if (isset($pad->$field)) {
				$values[$field] = $pad->$field;
			}
		}
	}

	if (elgg_is_sticky_form('pad')) {
		$sticky_values = elgg_get_sticky_values('pad');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('pad');

	return $values;
}