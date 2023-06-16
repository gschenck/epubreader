<?php

/**
 * ownCloud - Epubreader App
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
use OCP\Util;

class Application extends App {

	public const APP_ID = 'epubreader';

	public function __construct() {
		parent::__construct(self::APP_ID);

		$l = \OC::$server->getL10N('epubreader');
		Hooks::register();
		Util::addscript('epubreader', 'plugin');
	}
}
