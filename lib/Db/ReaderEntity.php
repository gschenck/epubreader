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
abstract class ReaderEntity extends Entity
{
	protected int $lastModified; // modification timestamp

	/**
	 * returns decoded json if input is json, otherwise returns input.
	 *
	 * @return array|string
	 */
	public function conditional_json_decode(string $el): mixed
	{
		/** @var array $result */
		$result = json_decode($el);
		if (JSON_ERROR_NONE === json_last_error()) {
			return $result;
		}

		return $el;
	}

	public function getLastModified(): int
	{
		return $this->lastModified;
	}

	public function setLastModified(int $lastModified): void
	{
		$this->lastModified = $lastModified;
		$this->markFieldUpdated('lastModified');
	}

	/**
	 * @return SerializedEntity
	 */
	abstract public function toService(): array;

	/**
	 * @return SerializedEntity
	 */
	abstract public function jsonSerialize(): array;
}
