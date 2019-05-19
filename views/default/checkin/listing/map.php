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
	'no_results' => elgg_echo("checkin:none"),
	'distinct' => false,
]);

if ($results) {
	foreach ($results as $key => $value) {
		$location[$key] = [$value->location, $value->latitude, $value->longitude];
	}
}

$body .= elgg_format_element('div', ['id' => 'site-map-canvas'], '');

$location = json_encode($location);
$body .= "<script>var getalldata = $location;</script>";

echo $body;
