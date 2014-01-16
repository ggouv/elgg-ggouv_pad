<?php
/**
 * Pad save form body
 *
 * @package ElggPad
 */

$variables = elgg_get_config('pad');
foreach ($variables as $name => $type) {
?>
<div class="<?php echo $name?>-block">
	<label><?php echo elgg_echo("pad:$name") ?></label>
	<?php
		if ($name == 'description' && $vars['entity']) {
			if ($vars['entity']->getPrivateSetting('status') == 'open') {
				$vars[$name] = $vars['entity']->description;
			} else {
				$desc = unserialize($vars['entity']->description);
				$vars[$name] = elgg_get_excerpt(strip_tags($desc[0]), 140);
			}
		} else {
			echo '<br />';
		}
	?>
	<?php echo elgg_view("input/$type", array(
			'name' => $name,
			'value' => $vars[$name]
		));
	?>
</div>
<?php
}

$cats = elgg_view('input/categories', $vars);
if (!empty($cats)) {
	echo $cats;
}


echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'page_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';
