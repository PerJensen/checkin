<?php
/**
 * Display an image
 *
 * @uses $vars['entity']
 * @package checkin
 */

if (!elgg_extract('full_view', $vars, false)) {
	return;
}

$file = elgg_extract('entity', $vars);

$img = elgg_format_element('img', [
	'src' => $file->getIconURL('checkin_cover'),
]);

$a = elgg_format_element([
	'#tag_name' => 'a',
	'#text' => $img,
	'href' => $file->getIconURL('checkin_cover'),
	'class' => 'elgg-lightbox-photo',
]);

echo elgg_format_element('div', ['class' => 'checkin-photo'], $a);
