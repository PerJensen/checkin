<?php
/**
 * List all checkins of a users friends
 *
 * @uses $vars['entity'] the user to list for
 *
 * @package checkin
 */

$entity = elgg_extract('entity', $vars);

checkin_register_toggle();

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'checkin',
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'owner_guid',
	'no_results' => elgg_echo("checkin:none"),
]);
