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

echo elgg_format_element('div', ['class' => 'checkin-photo'], $img);
