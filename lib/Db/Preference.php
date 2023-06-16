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

	protected $userId;  // user for whom this preference is valid
	protected $scope;   // scope (default or specific renderer)
	protected $fileId;  // file for which this preference is set
	protected $name;    // preference name
	protected $value;   // preference value
	protected $lastModified;    // modification timestamp

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

	public function toService() {
		return [
			'name' => $this->getName(),
			'value' => $this->conditional_json_decode($this->getValue()),
		];
	}

	public function getUserId() {
		return $this->userId;
	}

	public function setUserId($userId) {
		$this->userId = $userId;
	}

	public function getScope() {
		return $this->scope;
	}

	public function setScope(string $scope) {
		$this->scope = $scope;
	}

	public function getFileId() {
		return $this->fileId;
	}

	public function setFileId(int $fileId) {
		$this->fileId = $fileId;
	}

	public function getName() {
		return $this->name;
	}

	public function setName(string $name) {
		$this->name = $name;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue(string $value) {
		$this->value = $value;
	}

	public function getLastModified() {
		return $this->lastModified;
	}

	public function setLastModified($lastModified) {
		$this->lastModified = $lastModified;
	}
}
