<?php
/**
 * Check In
 *
 * @property string $address URL of bookmark
 */
class ElggCheckinCover extends ElggFile {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "checkin";
	}
}
