<?php
/**
 * Check In sidebar
 *
 * @package checkin
 */

echo elgg_view('page/elements/comments_block', [
	'subtypes' => 'checkin',
	'container_guid' => elgg_get_page_owner_guid(),
]);

echo elgg_view('page/elements/tagcloud_block', [
	'subtypes' => 'checkin',
	'container_guid' => elgg_get_page_owner_guid(),
]);
