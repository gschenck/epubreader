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
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Util;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Application extends App implements IBootstrap {

	public const APP_ID = 'epubreader';

	public function __construct() {
		parent::__construct(self::APP_ID);

		Util::addscript(self::APP_ID, 'plugin');
	}

	public function boot(IBootContext $context): void {
		/** @psalm-suppress DeprecatedMethod */
		Util::connectHook('\OCP\Config', 'js', 'OCA\Epubreader\Hooks', 'announce_settings');

		$context->injectFn(function (EventDispatcherInterface $dispatcher) {
			$dispatcher->addListener('OC\Files::preDelete', [Hooks::class, 'deleteFile']);
			$dispatcher->addListener('OC\User::preDelete', [Hooks::class, 'deleteUser']);
		});
	}

	public function register(IRegistrationContext $context): void {
	}
}
