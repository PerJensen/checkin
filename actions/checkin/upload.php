<?php
/**
 * Elgg checkin uploader/edit action
 *
 * @package checkin
 */

// Get variables
$title = elgg_get_title_input();
$location = get_input('location');
$tagged = (array) get_input('collection_tagged', []);
$latitude = get_input('latitude');
$longitude = get_input('longitude');
$desc = get_input('description');
$access_id = (int) get_input('access_id');
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('file_guid');
$tags = get_input('tags');

$container_guid = $container_guid ?: elgg_get_logged_in_user_guid();

elgg_make_sticky_form('checkin');

// check if upload attempted and failed
$uploaded_file = elgg_get_uploaded_file('upload', false);
if ($uploaded_file && !$uploaded_file->isValid()) {
	$error = elgg_get_friendly_upload_error($uploaded_file->getError());
	return elgg_error_response($error);
}

// check whether this is a new checkin or an edit
$new_checkin = empty($guid);

if ($new_checkin) {
	$checkin = new ElggCheckinCover();
} else {
	// load original file object
	$checkin = get_entity($guid);
	if (!elgg_instanceof($checkin, 'object', 'checkin')) {
		return elgg_error_response(elgg_echo('checkin:cannotload'));
	}

	// user must be able to edit checkin
	if (!$checkin->canEdit()) {
		return elgg_error_response(elgg_echo('checkin:noaccess'));
	}
}

if ($title) {
	$checkin->title = $title;
}
$checkin->description = $desc;
$checkin->location = $location;
$checkin->collection_tagged = $tagged;
$checkin->latitude = $latitude;
$checkin->longitude = $longitude;
$checkin->access_id = $access_id;
$checkin->container_guid = $container_guid;
$checkin->tags = string_to_tag_array($tags);

$checkin->save();

if ($uploaded_file && $uploaded_file->isValid()) {
	// save master file
	if (!$checkin->acceptUploadedFile($uploaded_file)) {
		return elgg_error_response(elgg_echo('checkin:uploadfailed'));
	}
	
	if (!$checkin->save()) {
		return elgg_error_response(elgg_echo('checkin:uploadfailed'));
	}	
	
	// update icons
	if ($checkin->getSimpleType() === 'image') {
		$checkin->saveIconFromElggFile($checkin);
	}
}

// checkin saved so clear sticky form
elgg_clear_sticky_form('checkin');

$forward = $checkin->getURL();

// handle results differently for new checkins and checkin updates
if ($new_checkin) {
	$container = get_entity($container_guid);
	if ($container instanceof ElggGroup) {
		$forward_url = elgg_generate_url('collection:object:checkin:group', ['guid' => $container->guid]);
	} else {
		$forward_url = elgg_generate_url('collection:object:checkin:owner', ['username' => $container->username]);
	}
	
	elgg_create_river_item([
		'view' => 'river/object/checkin/create',
		'action_type' => 'create',
		'object_guid' => $checkin->guid,
	]);
	
	// notify tagged user
	if ($tagged) {
		
		$trigger = elgg_get_logged_in_user_entity();
	
		foreach ($tagged as $user) {
			$user = get_entity($user);
			
			// Subject of the notification
			$subject = elgg_echo('checkin:tag:subject', array(), $user->language);
			
			// Body of the notification message
			$body = elgg_echo('checkin:tag:body', array(
				$checkin->title,
				$trigger->getDisplayName(),
				$checkin->getURL(),
			), $user->language);
			
			$params = array(
				'action' => 'create'
			);
			
			// Send the notification
			notify_user($user->guid, $trigger->guid, $subject, $body, $params);
		}	
	}
}

return elgg_ok_response('', elgg_echo('checkin:saved'), $forward);
