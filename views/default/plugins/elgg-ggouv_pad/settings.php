<?php
/**
 * Elgg-ggouv_pad plugin settings
 */

// set default value

if (!isset($vars['entity']->pad_host)) {
	$vars['entity']->pad_host = "http://beta.etherpad.org";
}
if (!isset($vars['entity']->pad_key)) {
	$vars['entity']->pad_key = 'EtherpadFTW';
}
if (!isset($vars['entity']->show_chat)) {
	$vars['entity']->show_chat = 'no';
}

if (!isset($vars['entity']->line_numbers)) {
	$vars['entity']->line_numbers = 'no';
}

if (!isset($vars['entity']->monospace_font)) {
	$vars['entity']->monospace_font = 'no';
}

if (!isset($vars['entity']->show_controls)) {
	$vars['entity']->show_controls = 'yes';
}

if (!isset($vars['entity']->new_pad_text)) {
	$vars['entity']->new_pad_text = elgg_echo('pad:pad:message');
}

?>
<div>
    <br /><label><?php echo elgg_echo('pad:padhost'); ?></label><br />
    <?php echo elgg_view('input/text',array('name' => 'params[pad_host]', 'value' => $vars['entity']->pad_host, 'class' => 'text_input',)); ?>
</div>

<div>
    <label><?php echo elgg_echo('pad:padkey'); ?></label><br />
    <?php echo elgg_view('input/text',array('name' => 'params[pad_key]', 'value' => $vars['entity']->pad_key, 'class' => 'text_input',)); ?>
</div>

<div>
    <label><?php echo elgg_echo('pad:newpadtext'); ?></label><br />
    <?php echo elgg_view('input/longtext',array('name' => 'params[new_pad_text]', 'value' => $vars['entity']->new_pad_text, 'class' => 'text_input',)); ?>
</div>

<div style="clear:both;">
    <label><?php echo elgg_echo('pad:showcontrols'); ?></label><br />
    <?php echo elgg_view('input/dropdown', array(
	'name' => 'params[show_controls]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')),
	'value' => $vars['entity']->show_controls,
	));
    ?>
</div>

<div>
    <label><?php echo elgg_echo('pad:showchat'); ?></label><br />
    <?php echo elgg_view('input/dropdown', array(
	'name' => 'params[show_chat]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')),
	'value' => $vars['entity']->show_chat,
	));
    ?>
</div>

<div>
    <label><?php echo elgg_echo('pad:linenumbers'); ?></label><br />
    <?php echo elgg_view('input/dropdown', array(
	'name' => 'params[line_numbers]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')),
	'value' => $vars['entity']->line_numbers,
	));
    ?>
</div>

<div>
    <label><?php echo elgg_echo('pad:monospace'); ?></label><br />
    <?php echo elgg_view('input/dropdown', array(
	'name' => 'params[monospace_font]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')),
	'value' => $vars['entity']->monospace_font,
	));
    ?>
</div>
