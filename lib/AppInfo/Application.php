<?php

/**
 * ownCloud - Epubreader App.
 *
 * @author Frank de Lange
 * @copyright 2015 - 2017 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */

namespace OCA\Epubreader\AppInfo;

use OCA\Epubreader\Hooks;
use OCP\AppFramework\App;
use OCP\Files\IRootFolder;
use OCP\IDBConnection;
use OCP\Util;

class Application extends App
{
	public const APP_ID = 'epubreader';

	public function __construct()
	{
		parent::__construct(self::APP_ID);

		/** @psalm-suppress DeprecatedInterface */
		$container = $this->getContainer();

		/** @var IRootFolder $rootFolder */
		$rootFolder = $container->get(IRootFolder::class);

		/** @var IDBConnection $dbConnection */
		$dbConnection = $container->get(IDBConnection::class);
		$hooks = new Hooks($rootFolder, $dbConnection);
		$hooks->register();

		/** @psalm-suppress DeprecatedMethod */
		Util::connectHook('\OCP\Config', 'js', 'OCA\Epubreader\Hooks', 'announce_settings');
		Util::addscript(self::APP_ID, 'plugin');
	}
}
