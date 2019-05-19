<?php
/**
 * View a checkin
 *
 * @package checkin
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'checkin');

$checkin = get_entity($guid);

$owner = elgg_get_page_owner_entity();

elgg_push_entity_breadcrumbs($checkin, false);

$title = $checkin->getDisplayName();

$content = elgg_view_entity($checkin, [
	'full_view' => true,
	'show_responses' => true,
]);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'entity' => $checkin,
]);

echo elgg_view_page($title, $body);
