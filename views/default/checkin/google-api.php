<?php
/**
 *
 * Serve google api key
 *
 * @package checkin
 * 
 */

$api_key = elgg_get_plugin_setting('google_api_key', 'checkin');

?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&libraries=places" async defer></script>
