<?php
/**
 * @author Frank de Lange
 * @copyright 2017 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Epubreader\Utility;

class Time {
	public function getTime() {
		return time();
	}

	/**
	 * @return string the current unix time in miliseconds
	 */
	public function getMicroTime(): string {
		list($millisecs, $secs) = explode(" ", microtime());
		return $secs . substr($millisecs, 2, 6);
	}

}
