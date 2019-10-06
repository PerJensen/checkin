<?php
/**
 * All checkins map
 *
 * @package checkin
 */

elgg_require_js('checkin/google-maps-site');
$entity = elgg_extract('entity', $vars);

checkin_register_toggle();

$results = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'checkin',
	'limit' => 0,
	'no_results' => elgg_echo("checkin:none"),
	'distinct' => false,
]);

if ($results) {
	foreach ($results as $key => $value) {
		$location[$key] = [
			$value->latitude, 
			$value->longitude, 
			$value->guid
		];
	}
}

$body .= elgg_format_element('div', ['id' => 'site-map-canvas'], '');

$location = json_encode($location);
$body .= "<script>var getalldata = $location;</script>";

echo $body;
