<?php
/**
 * List all user checkins
 *
 * @uses $vars['entity'] the user or group to list for
 *
 * @package checkin
 */

$owner = elgg_extract('entity', $vars);

// List checkins
$options = [
	'type' => 'object',
	'subtype' => 'checkin',
	'no_results' => elgg_echo("checkin:none"),
	'distinct' => false,
];

if ($owner instanceof ElggGroup) {
	$options['container_guid'] = $owner->guid;
} else {
	$options['owner_guid'] = $owner->guid;
}

checkin_register_toggle();

echo elgg_list_entities($options);
