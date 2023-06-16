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

class Bookmark extends ReaderEntity implements \JsonSerializable {

	protected string $userId; // user
	protected int $fileId; // book (identified by fileId) for which this mark is valid
	protected string $type; // type, defaults to "bookmark"
	protected string $name; // name, defaults to $location
	protected string $value; // bookmark value (format-specific, eg. page number for PDF, CFI for epub, etc)
	protected string $content; // bookmark content (annotations etc), can be empty

	public function jsonSerialize(): array {
		return [
			'id' => $this->getId(),
			'userId' => $this->getUserId(),
			'fileId' => $this->getFileId(),
			'type' => $this->getType(),
			'name' => $this->getName(),
			'value' => $this->conditional_json_decode($this->getValue()),
			'content' => $this->conditional_json_decode($this->getContent()),
			'lastModified' => $this->getLastModified()
		];
	}

	public function toService(): array {
		return [
			'name' => $this->getName(),
			'type' => $this->getType(),
			'value' => $this->conditional_json_decode($this->getValue()),
			'content' => $this->conditional_json_decode($this->getContent()),
			'lastModified' => $this->getLastModified(),
		];
	}

	public function getUserId(): string {
		return $this->userId;
	}

	public function setUserId(string $userId): void {
		$this->userId = $userId;
		$this->markFieldUpdated('userId');
	}

	public function getFileId(): int {
		return $this->fileId;
	}

	public function setFileId(int $fileId): void {
		$this->fileId = $fileId;
		$this->markFieldUpdated('fileId');
	}

	public function getType(): string {
		return $this->type;
	}

	public function setType(string $type): void {
		$this->type = $type;
		$this->markFieldUpdated('type');
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

	public function getContent(): string {
		return $this->content;
	}

	public function setContent(string $content): void {
		$this->content = $content;
		$this->markFieldUpdated('content');
	}
}
