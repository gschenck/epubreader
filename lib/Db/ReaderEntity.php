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

/**
 * @psalm-type SerializedEntity = array<string|int|array>
 */
abstract class ReaderEntity extends Entity {

	protected int $lastModified; // modification timestamp

	/**
	 * returns decoded json if input is json, otherwise returns input
	 *
	 * @return string|array
	 */
	public function conditional_json_decode(string $el) {
		/** @var array $result */
		$result = json_decode($el);
		if (json_last_error() === JSON_ERROR_NONE) {
			return $result;
		} else {
			return $el;
		}
	}

	public function getLastModified(): int {
		return $this->lastModified;
	}

	public function setLastModified(int $lastModified): void {
		$this->lastModified = $lastModified;
		$this->markFieldUpdated('lastModified');
	}

	/**
	 * @psalm-return SerializedEntity
	 */
	abstract public function toService(): array;

	/**
	 * @psalm-return SerializedEntity
	 */
	abstract public function jsonSerialize(): array;
}
