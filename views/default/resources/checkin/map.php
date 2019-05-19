<?php
/**
 * All checkins map
 *
 * @package checkin
 */

elgg_push_collection_breadcrumbs('object', 'checkin');

elgg_register_title_button('checkin', 'add', 'object', 'checkin');

$title = elgg_echo('collection:object:checkin:map');
$content = elgg_view('checkin/listing/map', $vars);
$sidebar = elgg_view('checkin/sidebar', $vars);

$body = elgg_view_layout('content', [
	'filter_context' => 'map',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
]);

echo elgg_view_page($title, $body);
