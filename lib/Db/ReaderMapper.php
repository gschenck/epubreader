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
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

/**
 * @template-extends QBMapper<ReaderEntity>
 */
abstract class ReaderMapper extends QBMapper
{
	private Time $time;

	/**
	 * @param IDBConnection              $db     Instance of the Db abstraction layer
	 * @param string                     $table  the name of the table. set this to allow entity
	 * @param class-string<ReaderEntity> $entity the name of the entity that the sql should be mapped to queries without using sql
	 */
	public function __construct(IDBConnection $db, string $table, string $entity, Time $time)
	{
		parent::__construct($db, $table, $entity);
		$this->time = $time;
	}

	public function update(Entity $entity): Entity
	{
		$entity->setLastModified($this->time->getMicroTime());

		return parent::update($entity);
	}

	public function insert(Entity $entity): Entity
	{
		$entity->setLastModified($this->time->getMicroTime());

		return parent::insert($entity);
	}
}
