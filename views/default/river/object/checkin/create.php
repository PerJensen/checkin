<?php
/**
 * New checkin river entry
 *
 * @package checkin
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$object = $item->getObjectEntity();

$image = elgg_view('output/img', [
	'src' => $object->getIconURL('small'),
	'alt' => $object->getDisplayName(),
]);

$attachment = elgg_view('output/url', [
	'href' => $object->getURL(),
	'text' => $image,
]);

$vars['message'] = elgg_get_excerpt($object->description);
$vars['attachments'] = $attachment;

echo elgg_view('river/elements/layout', $vars);
