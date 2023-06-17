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

class BookmarkMapper extends ReaderMapper {

	private string $userId;

	/**
	 * @param IDbConnection $db
	 * @param string $UserId
	 * @param Time $time
	 */
	public function __construct(IDBConnection $db, string $UserId, Time $time) {
		parent::__construct($db, 'reader_bookmarks', Bookmark::class, $time);
		$this->userId = $UserId;
	}

	/**
	 * @brief get bookmarks for $fileId+$userId(+$name)
	 * @param int $fileId
	 * @param ?string $name
	 * @param ?string $type
	 *
	 * @return ReaderEntity[]
	 */
	public function get(int $fileId, ?string $name = null, ?string $type = null): array {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('file_id', $query->createNamedParameter($fileId)))
			->andWhere($query->expr()->eq('user_id', $query->createNamedParameter($this->userId)));

		if ($type !== null) {
			$query->andWhere($query->expr()->eq('type', $query->createNamedParameter($type)));
		}

		if ($name !== null) {
			$query->andWhere($query->expr()->eq('name', $query->createNamedParameter($name)));
		}

		return $this->findEntities($query);
	}

	/**
	 * @brief write bookmark to database
	 *
	 * @param int $fileId
	 * @param string $name
	 * @param string $value
	 * @param ?string $type
	 * @param ?string $content
	 *
	 * @return ReaderEntity the newly created or updated bookmark
	 */
	public function set(int $fileId, string $name, string $value, ?string $type = null, ?string $content = null): ReaderEntity {
		/** @var Bookmark[] $result */
		$result = $this->get($fileId, $name);

		if(empty($result)) {
			// anonymous bookmarks are named after their contents
			if (empty($name)) {
				$name = $value;
			}

			// default type is "bookmark"
			if (null === $type) {
				$type = "bookmark";
			}

			$bookmark = new Bookmark();
			$bookmark->setFileId($fileId);
			$bookmark->setUserId($this->userId);
			$bookmark->setType($type);
			$bookmark->setName($name);
			$bookmark->setValue($value);
			$bookmark->setContent($content ?? '');

			$this->insert($bookmark);
		} else {
			$bookmark = $result[0];
			$bookmark->setValue($value);
			$bookmark->setContent($content ?? '');

			$this->update($bookmark);
		}

		return $bookmark;
	}
}
