/**
 * Display all checkins map
 *
 * @package checkin
 */

define(function(require) {
	
	var elgg = require('elgg');	
	require('markerclusterer');
	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax();
	
	var map;
	var locations = getalldata;

	function initSiteMap() {
		
		var infowindow = new google.maps.InfoWindow({maxWidth: 300});
		var bounds = new google.maps.LatLngBounds();
		
		var myLatLng = new google.maps.LatLng(57.053677, 9.923551);
		var mapOptions = {
			zoom: 14,
			center: myLatLng,
		}
		map = new google.maps.Map(document.getElementById('site-map-canvas'), mapOptions);

        var markers = locations.map(function(location, i) {
			var latlng = new google.maps.LatLng(location[0], location[1]);
			bounds.extend(latlng);
			var marker = new google.maps.Marker({
				position: latlng
			});
			
			google.maps.event.addListener(marker, 'click', function() {				
				elgg.get('ajax/view/checkin/infowindow', {
					data: {
						guid: location[2]
					},
					success: function (output) {
						infowindow.setContent(output);
						infowindow.open(map, marker);
					}
				});				
			});
			return marker;
        });
		map.fitBounds(bounds);
		
		var markerCluster = new MarkerClusterer(map, markers, {imagePath: elgg.get_simplecache_url('checkin/graphics/icons/m')});		
	
	}
	google.maps.event.addDomListener(window, "load", initSiteMap);
	
});