/**
 * Display all checkins map
 *
 * @package checkin
 */

define(function(require) {
	
	// still to do: add clustering, add user icon/name to infowindows

	var elgg = require('elgg');	
	//require('google_places_library');

	var map;
	var locations = getalldata;

	function initSiteMap() {

		var infowindow = new google.maps.InfoWindow({maxWidth: 300});
		var bounds = new google.maps.LatLngBounds();

		var myLatLng = new google.maps.LatLng(57.053677, 9.923551);
		var mapOptions = {
			maxZoom: 14,
			center: myLatLng,
		}
		map = new google.maps.Map(document.getElementById('site-map-canvas'), mapOptions);

		function placeMarker(loc) {
			var latlng = new google.maps.LatLng(loc[1], loc[2]);
			var marker = new google.maps.Marker({
				position: latlng,
				map: map
			});
			
			google.maps.event.addListener(marker, 'click', function(){
				infowindow.close();
				infowindow.setContent('<div id="infowindow-content">' + loc[0] + '</div>');
				infowindow.open(map, marker);
			});
			
			bounds.extend(marker.getPosition());
			map.fitBounds(bounds);
		}
  
		for (var i = 0; i < locations.length; i++) {
			placeMarker(locations[i]);
		}
		
	}
	google.maps.event.addDomListener(window, "load", initSiteMap);
	
});