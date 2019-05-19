<?php
/**
 * Edit a file
 *
 * @package checkin
 */

$checkin_guid = (int) elgg_extract('guid', $vars);

$checkin = get_entity($checkin_guid);

if (!elgg_instanceof($checkin, 'object', 'checkin')) {
	throw new \Elgg\EntityNotFoundException();
}

if (!$checkin->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

$title = elgg_echo('edit:object:checkin');

elgg_push_entity_breadcrumbs($checkin);
elgg_push_breadcrumb($title);

$form_vars = ['enctype' => 'multipart/form-data'];
$body_vars = checkin_prepare_form_vars($checkin);

$content = elgg_view_form('checkin/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
]);

echo elgg_view_page($title, $body);
