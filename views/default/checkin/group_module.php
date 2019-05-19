<?php
/**
 * Group file module
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if (!$group->isToolEnabled('checkin')) {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:checkin:group', ['guid' => $group->guid]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');
$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'checkin',
	'container_guid' => $group->guid,
	'limit' => 4,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('checkin:none'),
	'distinct' => false,
]);
elgg_pop_context();

$new_link = null;
if ($group->canWriteToContainer(0, 'object', 'checkin')) {
	$new_link = elgg_view('output/url', [
		'href' => elgg_generate_url('add:object:checkin', ['guid' => $group->guid]),
		'text' => elgg_echo('add:object:checkin'),
		'is_trusted' => true,
	]);
}

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:object:checkin:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
