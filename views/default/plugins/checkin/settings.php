<?php
/**
 * Check In settings
 *
 * @package checkin
 */

$plugin = elgg_extract('entity', $vars);
if (!$plugin instanceof \ElggPlugin) {
	return;
}

$maps .= elgg_view_field([
	'#type' => 'fieldset',
	'id' => 'checkin-google-maps',
	'fields' => [
		[
			'#type' => 'text',
			'#label' => elgg_echo('checkin:settings:google:api:key'),
			'name' => 'params[google_api_key]',
			'value' => $plugin->google_api_key,
		],
	],
]);

echo elgg_view_module('info', elgg_echo('checkin:settings:google:maps'), $maps);
