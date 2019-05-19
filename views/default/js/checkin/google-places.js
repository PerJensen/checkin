/**
 * Google Places Search Box
 *
 * @package checkin
 */

define(function(require) {

	var elgg = require('elgg');
	//require('google_places_library');
	  	
	function initAutocomplete() {
	  	
		var autocomplete = new google.maps.places.Autocomplete(document.getElementById('pac-input'));
		
		google.maps.event.addListener(autocomplete, 'place_changed', function() {			
			var place = autocomplete.getPlace();

			document.getElementById("pac-input-latitude").value = place.geometry.location.lat();
			document.getElementById("pac-input-longitude").value = place.geometry.location.lng();
			
		});
	};
	google.maps.event.addDomListener(window, 'load', initAutocomplete);
	
});
