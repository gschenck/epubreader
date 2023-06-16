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

class Preference extends ReaderEntity implements \JsonSerializable {

	protected string $userId; // user for whom this preference is valid
	protected string $scope; // scope (default or specific renderer)
	protected int $fileId; // file for which this preference is set
	protected string $name; // preference name
	protected string $value; // preference value

	public function jsonSerialize(): array {
		return [
			'id' => $this->getId(),
			'scope' => $this->getScope(),
			'fileId' => $this->getFileId(),
			'name' => $this->getName(),
			'value' => $this->conditional_json_decode($this->getValue()),
			'lastModified' => $this->getLastModified(),
		];
	}

	public function toService(): array {
		return [
			'name' => $this->getName(),
			'value' => $this->conditional_json_decode($this->getValue()),
		];
	}

	public function getUserId(): string {
		return $this->userId;
	}

	public function setUserId(string $userId): void {
		$this->userId = $userId;
		$this->markFieldUpdated('userId');
	}

	public function getScope(): string {
		return $this->scope;
	}

	public function setScope(string $scope): void {
		$this->scope = $scope;
		$this->markFieldUpdated('scope');
	}

	public function getFileId(): int {
		return $this->fileId;
	}

	public function setFileId(int $fileId): void {
		$this->fileId = $fileId;
		$this->markFieldUpdated('fileId');
	}

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name): void {
		$this->name = $name;
		$this->markFieldUpdated('name');
	}

	public function getValue(): string {
		return $this->value;
	}

	public function setValue(string $value): void {
		$this->value = $value;
		$this->markFieldUpdated('value');
	}
}
