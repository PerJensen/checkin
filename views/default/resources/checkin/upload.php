<?php
/**
 * Upload a new checkin
 *
 * @package checkin
 */

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid);

$entity = get_entity($guid);

if (!$entity->canWriteToContainer(0, 'object', 'checkin')) {
	throw new \Elgg\EntityPermissionsException();
}

$title = elgg_echo('add:object:checkin');

elgg_push_collection_breadcrumbs('object', 'checkin', $entity);
elgg_push_breadcrumb($title);

// create form
$form_vars = ['enctype' => 'multipart/form-data'];
$body_vars = checkin_prepare_form_vars();
$content = elgg_view_form('checkin/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
]);

echo elgg_view_page($title, $body);
