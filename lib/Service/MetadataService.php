<?php
/**
 * @author Frank de Lange
 * @copyright 2017 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Epubreader\Service;

use OCP\App\IAppManager;

class MetadataService {

	private $appManager;

	/**
	 * @param IAppManager $appManager
	 */
	public function __construct(IAppManager $appManager) {
		$this->appManager = $appManager;
	}

	/**
	 * @brief get metadata item(s)
	 *
	 * @param int $fileId
	 * @param string $name
	 *
	 * @return array
	 */
	public function get($fileId, $name = null) {
		return [];
	}

	/**
	 * @brief write metadata to database
	 *
	 * @param int $fileId
	 * @param array $value
	 *
	 * @return array
	 */
	public function setAll($fileId, $value) {
		// no-op for now
		return [];
	}

	/**
	 * @brief write metadata item to database
	 *
	 * @param int $fileId
	 * @param string $name
	 * @param array $value
	 *
	 * @return array
	 */
	public function set($fileId, $name, $value) {
		// no-op for now
		return [];
	}
}
