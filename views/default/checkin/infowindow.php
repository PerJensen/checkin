<?php
/**
 * Checkin infowindow content
 *
 * @package checkin
 * 
 */

if (empty($vars['entity']) || !$vars['entity'] instanceof ElggObject) {
    return;
}

$object = $vars['entity'];
/* @var ElggObject $object */

$title = elgg_view('output/url', [
    'text' => $object->getDisplayName(),
    'href' => $object->getUrl(),
    'is_trusted' => true,
]);

$owner = $object->getOwnerEntity();
if ($owner instanceof ElggEntity) {
	$owner_text = elgg_echo('checkin:by');
	$owner_text .= elgg_view('output/url', [
		'href' => $owner->getURL(),
		'text' => $owner->getDisplayName(),
		'is_trusted' => true,
	]);
}

$location = $object->location;

// format output
$body = elgg_format_element('h3', [
	'class' => [
		'elgg-listing-summary-title',
	]
], $title);

$body .= elgg_format_element('div', [
	'class' => [
		'elgg-listing-summary-subtitle',
		'elgg-subtext',
	]
], $owner_text);

$body .= elgg_format_element('div', [
	'class' => [
		'elgg-listing-summary-content',
		'elgg-content',
	]
], $location);

$content = elgg_format_element('div', ['class' => 'elgg-body'], $body);
echo elgg_format_element('div', ['id' => 'infowindow-content', 'class' => 'elgg-image-block'], $content);
