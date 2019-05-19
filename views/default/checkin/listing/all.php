<?php
/**
 * List all checkins
 *
 * @package checkin
 */

checkin_register_toggle();

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'checkin',
	'no_results' => elgg_echo("checkin:none"),
	'distinct' => false,
]);
