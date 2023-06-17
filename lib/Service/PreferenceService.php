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

use OCA\Epubreader\Db\PreferenceMapper;
use OCA\Epubreader\Db\ReaderEntity;

/**
 * @psalm-import-type SerializedEntity from ReaderEntity
 */
class PreferenceService extends Service {

	// (ab)use the fact that $fileId never goes below 1 by using the
	// value 0 to indicate a default preference
	private const DEFAULTS = 0;

	private PreferenceMapper $preferenceMapper;

	/**
	 * @param PreferenceMapper $preferenceMapper
	 */
	public function __construct(PreferenceMapper $preferenceMapper) {
		parent::__construct($preferenceMapper);
		$this->preferenceMapper = $preferenceMapper;
	}

	/**
	 * @brief get preference
	 *
	 * scope identifies preference source, i.e. which renderer the preference applies to
	 * preference type is format-dependent, eg CFI for epub, page number for CBR/CBZ, etc
	 *
	 * @param string $scope
	 * @param int $fileId
	 * @param ?string $name
	 *
	 * @psalm-return SerializedEntity
	 */
	public function get(string $scope, int $fileId, ?string $name = null): array {
		$result = $this->preferenceMapper->get($scope, $fileId, $name);
		return array_map(
			function (ReaderEntity $entity): array {
				return $entity->toService();
			}, $result);
	}

	/**
	 * @brief write preference
	 *
	 * scope identifies preference source, i.e. which renderer the preference applies to
	 * position type is format-dependent, eg CFI for epub, page number for CBR/CBZ, etc
	 *
	 * @param string $scope
	 * @param int $fileId
	 * @param string $name
	 * @param string $value
	 */
	public function set(string $scope, int $fileId, string $name, string $value): ReaderEntity {
		return $this->preferenceMapper->set($scope, $fileId, $name, $value);
	}

	/**
	 * @brief get default preference
	 *
	 * @param string $scope
	 * @param ?string $name
	 */
	public function getDefault(string $scope, ?string $name = null): array {
		return $this->get($scope, self::DEFAULTS, $name);
	}

	/**
	 * @brief set default preference
	 *
	 * @param string $scope
	 * @param string $name
	 * @param string $value
	 */
	public function setDefault($scope, $name, $value): ReaderEntity {
		return $this->preferenceMapper->set($scope, self::DEFAULTS, $name, $value);
	}

	/**
	 * @brief delete preference
	 *
	 * @param string $scope
	 * @param int $fileId
	 * @param ?string $name
	 *
	 */
	public function delete(string $scope, int $fileId, ?string $name = null): void {
		foreach($this->preferenceMapper->get($scope, $fileId, $name) as $preference) {
			$this->preferenceMapper->delete($preference);
		}
	}

	/**
	 * @brief delete default
	 *
	 * @param string $scope
	 * @param ?string $name
	 *
	 */
	public function deleteDefault(string $scope, ?string $name = null): void {
		$this->delete($scope, self::DEFAULTS, $name);
	}
}
