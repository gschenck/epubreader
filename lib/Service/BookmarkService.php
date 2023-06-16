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

use OCA\Epubreader\Db\BookmarkMapper;
use OCA\Epubreader\Db\ReaderEntity;

class BookmarkService extends Service {

	// "bookmark" name to use for the cursor (current reading position)
	public const CURSOR = '__CURSOR__';
	public const bookmark_type = 'bookmark';

	private BookmarkMapper $bookmarkMapper;

	public function __construct(BookmarkMapper $bookmarkMapper) {
		parent::__construct($bookmarkMapper);
		$this->bookmarkMapper = $bookmarkMapper;
	}

	/**
	 * @brief get bookmark
	 *
	 * bookmark type is format-dependent, eg CFI for epub, page number for CBR/CBZ, etc
	 *
	 * @param int $fileId
	 * @param ?string $name
	 * @param ?string $type
	 */
	public function get($fileId, ?string $name = null, ?string $type = null): array {
		$result = $this->bookmarkMapper->get($fileId, $name, $type);
		return array_map(
			function (ReaderEntity $entity) {
				return $entity->toService();
			}, $result);
	}

	/**
	 * @brief write bookmark
	 *
	 * position type is format-dependent, eg CFI for epub, page number for CBR/CBZ, etc
	 *
	 * @param int $fileId
	 * @param ?string $name
	 * @param string $value
	 * @param ?string $type
	 * @param ?string $content
	 */
	public function set(int $fileId, ?string $name = null, string $value, ?string $type = null, ?string $content = null): ReaderEntity {
		return $this->bookmarkMapper->set($fileId, $name, $value, $type, $content);
	}

	/**
	 * @brief get cursor (current position in book)
	 *
	 * @param int $fileId
	 */
	public function getCursor(int $fileId): array {
		$result = $this->get($fileId, static::CURSOR);
		if (count($result) === 1) {
			return $result[0];
		}
		return [];
	}

	/**
	 * @brief set cursor (current position in book)
	 *
	 * @param int $fileId
	 * @param string $value
	 */
	public function setCursor(int $fileId, string $value): ReaderEntity {
		return $this->bookmarkMapper->set($fileId, static::CURSOR, $value, static::bookmark_type);
	}

	/**
	 * @brief delete bookmark
	 *
	 * @param int $fileId
	 * @param ?string $name
	 * @param ?string $type
	 */
	public function delete($fileId, ?string $name = null, ?string $type = null): void {
		foreach ($this->bookmarkMapper->get($fileId, $name, $type) as $bookmark) {
			$this->bookmarkMapper->delete($bookmark);
		}
	}

	/**
	 * @brief delete cursor
	 *
	 * @param int $fileId
	 */
	public function deleteCursor(int $fileId): void {
		$this->delete($fileId, static::CURSOR, static::bookmark_type);
	}
}
