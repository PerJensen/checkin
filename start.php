<?php
/*
 * Check In
 *
 * @author Per Jensen
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 * @copyright Copyright (c) 2019, Per Jensen
 *
 */

return function() {
	elgg_register_event_handler('init', 'system', 'checkin_init');
};

function checkin_init() {
	
	elgg_set_entity_class('object', 'checkin', ElggCheckinCover::class);
	
	// plugin specific CSS
	elgg_extend_view('elgg.css', 'checkin/checkin.css');
	
	elgg_register_plugin_hook_handler('view_vars', 'page/default', 'checkin_context_vars');

	// Register for notifications
	elgg_register_notification_event('object', 'checkin', ['create']);
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:checkin', 'checkin_prepare_notification');

	// custom icon sizes
	elgg_register_plugin_hook_handler('entity:icon:sizes', 'object', 'checkin_custom_icon_sizes');
	elgg_register_plugin_hook_handler('entity:icon:file', 'object', 'checkin_set_icon_file');

	// cleanup thumbnails on delete. high priority because we want to try to make sure the
	// deletion will actually occur before we go through with this.
	elgg_register_event_handler('delete', 'object', 'checkin_handle_object_delete', 999);
	
	// add the group checkin tool option
	elgg()->group_tools->register('checkin');
	
	// add a check-in link to owner blocks
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'checkin_owner_block_menu');
	
	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:checkin', 'Elgg\Values::getTrue');
	
	// hack to load google libraries with async defer (checkin/google-api)	
	elgg_extend_view('page/elements/foot', 'checkin/google-api');
	
	elgg_register_ajax_view('checkin/infowindow');
	
	// menus
	elgg_register_menu_item('site', [
		'name' => 'checkin',
		'icon' => 'map-marker',
		'text' => elgg_echo('checkin:menu:item'),
		'href' => elgg_generate_url('default:object:checkin'),
	]);
	
	// add map tab to filter:checkin	
	elgg_register_menu_item('filter:checkin', [
		'name' => 'checkin_map',
		'text' => elgg_view_icon('map-marker'),
		'href' => elgg_generate_url('collection:object:checkin:map'),
		'title' => elgg_echo("checkin:map"),
		'priority' => 1200,
	]);		
}
 
/**
 * Set body tag for checkin context
 *
 * @param string $hook   'view_vars'
 * @param string $type   'page/default'
 * @param array  $vars   return value
 * @param array  $params supplied params
 *
 * @return void|array
 */
function checkin_context_vars($hook, $type, $vars, $params) {
	
	$body_vars = elgg_extract('body_attrs', $vars);

	if (elgg_in_context('checkin')) {
		$vars['body_attrs'] = array('class' => 'elgg-page-checkin');
	}
			
	return $vars;
}

/**
 * Adds a toggle to filter menu for switching between list and gallery views
 *
 * @return void
 */
function checkin_register_toggle() {

	if (get_input('list_type', 'list') == 'list') {
		$list_type = 'gallery';
		$icon = elgg_view_icon('grid');
	} else {
		$list_type = 'list';
		$icon = elgg_view_icon('list');
	}

	$url = elgg_http_add_url_query_elements(current_page_url(), ['list_type' => $list_type]);
	
	elgg_register_menu_item('filter:checkin', [
		'name' => 'checkin_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("checkin:list:$list_type"),
		'priority' => 1000,
	]);
}

/**
 * Prepare a notification message about a new checkin
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg\Notifications\Notification
 */
function checkin_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$descr = $entity->description;
	$title = $entity->getDisplayName();

	$notification->subject = elgg_echo('checkin:notify:subject', [$title], $language);
	$notification->body = elgg_echo('checkin:notify:body', [
		$owner->getDisplayName(),
		$title,
		$descr,
		$entity->getURL()
	], $language);
	$notification->summary = elgg_echo('checkin:notify:summary', [$title], $language);
	$notification->url = $entity->getURL();
	return $notification;
}

/**
 * Add a menu item to the user ownerblock
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:owner_block'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return ElggMenuItem[]
 */
function checkin_owner_block_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if ($entity instanceof ElggUser) {
		$url = elgg_generate_url('collection:object:checkin:owner', ['username' => $entity->username]);
		$item = new ElggMenuItem('checkin', elgg_echo('collection:object:checkin'), $url);
		$return[] = $item;
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->isToolEnabled('checkin')) {
			$url = elgg_generate_url('collection:object:checkin:group', ['guid' => $entity->guid]);
			$item = new ElggMenuItem('checkin', elgg_echo('collection:object:checkin:group'), $url);
			$return[] = $item;
		}
	}
	
	return $return;
}

/**
 * Set custom icon sizes for checkin objects
 *
 * @param string $hook   "entity:icon:url"
 * @param string $type   "object"
 * @param array  $return Sizes
 * @param array  $params Hook params
 * @return array
 */
function checkin_custom_icon_sizes($hook, $type, $return, $params) {

	$entity_subtype = elgg_extract('entity_subtype', $params);
	if ($entity_subtype !== 'checkin') {
		return;
	}
	
	$return['checkin_cover'] = [
		'w' => 1920,
		'h' => 1080,
		'square' => false,
		'upscale' => false,
	];
	
	$return['checkin_cover_small'] = [
		'w' => 384,
		'h' => 216,
		'square' => false,
		'upscale' => false,
	];

	$return['medium'] = [
		'w' => 480,
		'h' => 480,
		'square' => true,
		'upscale' => true,
	];
		
	$return['small'] = [
		'w' => 60,
		'h' => 60,
		'square' => true,
		'upscale' => true,
	];
		
	return $return;
}

/**
 * Set custom file thumbnail location
 *
 * @param string    $hook   "entity:icon:file"
 * @param string    $type   "object"
 * @param \ElggIcon $icon   Icon file
 * @param array     $params Hook params
 * @return \ElggIcon
 */
function checkin_set_icon_file($hook, $type, $icon, $params) {
	
	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params);
	
	if (!($entity instanceof ElggCheckinCover)) {
		return;
	}
	
	switch ($size) {
		case 'small' :
			$name = 'small';
			break;
			
		case 'medium' :
			$name = 'medium';
			break;

		case 'checkin_cover' :
			$name = 'cover';
			break;
			
		case 'checkin_cover_small' :
			$name = 'cover_small';
			break;

		default :
			$name = "{$size}";
			break;
	}
	
	$icon->owner_guid = $entity->owner_guid;

	$prefix = $entity->guid . '_';
	$filename = pathinfo($entity->getFilenameOnFilestore(), PATHINFO_FILENAME);
	$filename = "checkin/{$prefix}{$name}.jpg";
	$icon->setFilename($filename);
	
	return $icon;
}

/**
 * Handle an object being deleted
 *
 * @param string     $event Event name
 * @param string     $type  Event type
 * @param ElggObject $file  The object deleted
 * @return void
 */
function checkin_handle_object_delete($event, $type, ElggObject $file) {
	if (!$file instanceof ElggCheckinCover) {
		return;
	}
	if (!$file->guid) {
		// this is an ElggFile used as temporary API
		return;
	}

	$file->deleteIcon();
}

/**
 * Prepare the upload/edit form variables
 *
 * @param ElggCheckinCover $file the file to edit
 *
 * @return array
 */
function checkin_prepare_form_vars($checkin = null) {

	// input names => defaults
	$values = [
		'title' => '',
		'location' => '',
		'latitude' => '',
		'longitude' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $checkin,
	];

	if ($checkin) {
		foreach (array_keys($values) as $field) {
			if (isset($checkin->$field)) {
				$values[$field] = $checkin->$field;
			}
		}
	}

	if (elgg_is_sticky_form('checkin')) {
		$sticky_values = elgg_get_sticky_values('checkin');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('checkin');

	return $values;
}
