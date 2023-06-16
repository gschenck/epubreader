<?php
/**
 * @author Frank de Lange
 * @copyright 2015 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Epubreader\Db;

use OCP\AppFramework\Db\Entity;

class ReaderEntity extends Entity {

	private $lastModified;

	/* returns decoded json if input is json, otherwise returns input */
	public static function conditional_json_decode($el) {
		$result = json_decode($el);
		if (json_last_error() === JSON_ERROR_NONE) {
			return $result;
		} else {
			return $el;
		}
	}

	public function getLastModified() {
		return $this->lastModified;
	}

	public function setLastModified($lastModified) {
		$this->lastModified = $lastModified;
	}

}
