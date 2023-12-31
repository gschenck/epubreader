<?php
/**
 * @author Frank de Lange
 * @copyright 2017 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Epubreader\Db;

use OCA\Epubreader\Utility\Time;
use OCP\IDBConnection;

class PreferenceMapper extends ReaderMapper
{
	private string $userId;

	public function __construct(IDBConnection $db, string $UserId, Time $time)
	{
		parent::__construct($db, 'reader_prefs', Preference::class, $time);
		$this->userId = $UserId;
	}

	/**
	 * @brief get preferences for $scope+$fileId+$userId(+$name)
	 *
	 * @return ReaderEntity[]
	 */
	public function get(string $scope, int $fileId, ?string $name = null): array
	{
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('scope', $query->createNamedParameter($scope)))
			->andWhere($query->expr()->eq('file_id', $query->createNamedParameter($fileId)))
			->andWhere($query->expr()->eq('user_id', $query->createNamedParameter($this->userId)))
		;

		if (!empty($name)) {
			$query->andWhere($query->expr()->eq('name', $query->createNamedParameter($name)));
		}

		return $this->findEntities($query);
	}

	/**
	 * @brief write preference to database
	 *
	 * @return ReaderEntity the newly created or updated preference
	 */
	public function set(string $scope, int $fileId, string $name, string $value): ReaderEntity
	{
		$result = $this->get($scope, $fileId, $name);

		if (empty($result)) {
			$preference = new Preference();
			$preference->setScope($scope);
			$preference->setFileId($fileId);
			$preference->setUserId($this->userId);
			$preference->setName($name);
			$preference->setValue($value);

			$this->insert($preference);
		} elseif ($result[0] instanceof Preference) {
			$preference = $result[0];
			$preference->setValue($value);

			$this->update($preference);
		} else {
			$preference = new Preference();
		}

		return $preference;
	}
}
