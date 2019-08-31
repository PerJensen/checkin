<?php
/**
 * Elgg Check In upload/save form
 *
 * @package checkin
 */

elgg_require_js('checkin/google-places');
 
// once elgg_view stops throwing all sorts of junk into $vars, we can use
$title = elgg_extract('title', $vars, '');
$location = elgg_extract('location', $vars, '');
$tagged = elgg_extract('collection_tagged', $vars);
$latitude = elgg_extract('latitude', $vars, '');
$longitude = elgg_extract('longitude', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);

if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$checkin_label = elgg_view_message('warning', elgg_echo('fileexists'), ['title' => false]);
	$submit_label = elgg_echo('save');
} else {
	$checkin_label = elgg_view_message('notice', elgg_echo('checkin:notice:file'), [
		'title' => elgg_echo('checkin:notice:file:title'),
	]);
	$checkin_label .= elgg_echo("checkin:add:file");
	$submit_label = elgg_echo('checkin:upload');
}

$categories_field = $vars;
$categories_field['#type'] = 'categories';

$fields = [
	[
		'#type' => 'file',
		'#label' => $checkin_label,
		'name' => 'upload',
		'value' => ($guid),
		'required' => (!$guid),
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('title'),
		'name' => 'title',
		'value' => $title,
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('checkin:add:location'),
		'name' => 'location',
		'id' => 'pac-input',
		'value' => $location,
		'placeholder' => elgg_echo('checkin:search:location'),
		'required' => true,
	],
	[
		'#type' => 'userpicker',
		'#label' => elgg_echo('checkin:tag:users'),
		'name' => 'collection_tagged',
		'values' => $tagged,
		'show_friends' => false,
	],
	[
		'#type' => 'hidden',
		'name' => 'latitude',
		'id' => 'pac-input-latitude',
		'value' => $latitude,
	],
	[
		'#type' => 'hidden',
		'name' => 'longitude',
		'id' => 'pac-input-longitude',
		'value' => $longitude,
	],
	[
		'#type' => 'plaintext',
		'#label' => elgg_echo('checkin:description'),
		'name' => 'description',
		'value' => $desc,
		'editor_type' => 'simple',
	],
	[
		'#type' => 'tags',
		'#label' => elgg_echo('checkin:add:tags'),
		'name' => 'tags',
		'value' => $tags,
	],
	$categories_field,
	[
		'#type' => 'access',
		'#label' => elgg_echo('access'),
		'name' => 'access_id',
		'value' => $access_id,
		'entity' => get_entity($guid),
		'entity_type' => 'object',
		'entity_subtype' => 'checkin',
	],
	[
		'#type' => 'hidden',
		'name' => 'container_guid',
		'value' => $container_guid,
	],
	[
		'#type' => 'hidden',
		'name' => 'file_guid',
		'value' => $guid,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo($submit_label),
]);

elgg_set_form_footer($footer);
