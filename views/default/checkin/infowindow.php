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

$image = elgg_view('output/img', [
	'src' => $object->getIconURL('checkin_cover_small'),
	'alt' => $object->getDisplayName(),
]);
	
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
$cover = elgg_format_element('div', [
	'class' => 'elgg-image'
], $image);

$header = elgg_format_element('h3', [
	'class' => 'elgg-head'
], $title);

$body .= elgg_format_element('div', [
	'class' => [
		'elgg-listing-summary-subtitle',
		'elgg-subtext',
	]
], $owner_text);

$body .= elgg_format_element('div', ['class' => 'elgg-content'], $location);
$content = elgg_format_element('div', ['class' => 'elgg-body'], $body);

$contents = $header . $content;

echo $cover;
echo elgg_format_element('div', ['class' => 'elgg-module elgg-module-featured'], $contents);
