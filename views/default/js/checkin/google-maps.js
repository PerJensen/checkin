/**
 * Display map
 *
 * @package checkin
 */

define(function(require) {

	var elgg = require('elgg');	
	//require('google_places_library');

	var checkinmap;
	var data = getlatandlng;

	function initMap() {
		
		var myLatLng = new google.maps.LatLng(data[0], data[1]);
		var mapOptions = {
			zoom: 14,
			center: myLatLng,
		};
		checkinmap = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: checkinmap,
		});			
		
	}
	google.maps.event.addDomListener(window, 'load', initMap);

});
