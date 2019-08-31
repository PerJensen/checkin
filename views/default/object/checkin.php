<?php
/**
 * Check In renderer
 *
 * @uses $vars['entity'] ElggFile to show
 *
 * @package checkin
 */

$full = elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars, false);
if (!elgg_instanceof($entity, 'object', 'checkin')) {
	return;
}

if ($full && !elgg_in_context('gallery')) {
	
	elgg_require_js('checkin/google-maps');

	$body = '';
	$body .= elgg_view("checkin/image", $vars);

	$field_value = elgg_view('output/longtext', ['value' => $entity->description]);	
	$body .= elgg_view('object/elements/field', [
		'label' => elgg_echo('description'),
		'value' => $field_value,
		'class' => 'checkin-field',
	]);
	
	$tagged = $entity->collection_tagged;
	$result = count($tagged);
	if ($tagged) {
		$with = elgg_echo('checkin:tagged');
		if ($result == 1) {	
			$icon = get_entity($tagged);
			$members = elgg_view_entity_icon($icon, 'tiny');			
		} else {
			foreach ($tagged as $user) {
				$icon = get_entity($user);
				$members .= elgg_view_entity_icon($icon, 'tiny');
			}
		}
	}
	
	$location_value = elgg_view('output/text', ['value' => $entity->location]);	
	$icon = elgg_view_icon('map-marker');
	$body .= elgg_view('object/elements/field', [
		'value' =>  '<div>' . $icon . $location_value . '</div>' . '<div>' . $with . $members . '</div>',
		'class' => 'checkin-field checkin-location-field',		
	]);
	$body .= elgg_format_element('div', ['id' => 'map-canvas'], '');
	
	$latlng = array($entity->latitude, $entity->longitude);	
	$latlng = json_encode($latlng);
	$body .= "<script>var getlatandlng = $latlng;</script>";
	
	$params = [
		'show_summary' => true,
		'icon_entity' => $entity->getOwnerEntity(),
		'body' => $body,
		'show_responses' => elgg_extract('show_responses', $vars, false),
		'show_navigation' => true,
	];
	$params = $params + $vars;
	
	echo elgg_view('object/elements/full', $params);
	
} elseif (elgg_in_context('gallery')) {
	
	echo '<div class="file-gallery-item">';
	echo "<h3>" . $entity->getDisplayName() . "</h3>";
	echo elgg_view_entity_icon($entity, 'medium');
	echo '</div>';
	
} else {
	
	// brief view
	$params = [
		'content' => elgg_get_excerpt($entity->description),
		'icon_entity' => $entity,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
