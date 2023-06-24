<?php
/**
 * @author Frank de Lange
 * @copyright 2017 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Epubreader;

use OCA\Epubreader\AppInfo\Application;
use OCA\Epubreader\Utility\Server;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\IUser;
use OCP\IUserSession;

class Hooks {

	private IRootFolder $rootFolder;
	private IDBConnection $dbConnection;

	public function __construct(IRootFolder $rootFolder, IDBConnection $dbConnection) {
		$this->rootFolder = $rootFolder;
		$this->dbConnection = $dbConnection;
	}

	public function register(): void {
		$this->rootFolder->listen('\OC\Files', 'preDelete', function (Node $node) {
			$this->deleteFile($node->getId());
		});

		$this->rootFolder->listen('\OC\User', 'preDelete', function (IUser $user) {
			$this->deleteUser($user->getUID());
		});
	}

	public static function announce_settings(array $settings): void {
		// Nextcloud encodes this as JSON, Owncloud does not (yet) (#75)
		// TODO: remove this when Owncloud starts encoding oc_appconfig as JSON just like it already encodes most other properties
		$user = Server::get(IUserSession::class)->getUser();
		if ($user &&
			is_array($settings['array']) &&
			array_key_exists('oc_appconfig', $settings['array'])
		) {
			$isJson = self::isJson($settings['array']['oc_appconfig']);
			/** @var array $array */
			$array = ($isJson) ? json_decode((string) $settings['array']['oc_appconfig'], true) : $settings['array']['oc_appconfig'];
			$array['filesReader'] = [
				'enableEpub' => Server::get(IConfig::class)->getUserValue($user->getUID(), Application::APP_ID, 'epub_enable', true),
				'enablePdf' => Server::get(IConfig::class)->getUserValue($user->getUID(), Application::APP_ID, 'pdf_enable', true),
				'enableCbx' => Server::get(IConfig::class)->getUserValue($user->getUID(), Application::APP_ID, 'cbx_enable', true),
			];
			$settings['array']['oc_appconfig'] = ($isJson) ? json_encode($array) : $array;
		}
	}

	protected function deleteFile(int $fileId): void {
		$queryBuilder = $this->dbConnection->getQueryBuilder();
		$queryBuilder->delete('reader_bookmarks')->where('file_id = file_id')->setParameter('file_id', $fileId);
		$queryBuilder->executeStatement();

		$queryBuilder = $this->dbConnection->getQueryBuilder();
		$queryBuilder->delete('reader_prefs')->where('file_id = file_id')->setParameter('file_id', $fileId);
		$queryBuilder->executeStatement();
	}

	protected function deleteUser(string $userId): void {
		$queryBuilder = $this->dbConnection->getQueryBuilder();
		$queryBuilder->delete('reader_bookmarks')->where('user_id = user_id')->setParameter('user_id', $userId);
		$queryBuilder->executeStatement();

		$queryBuilder = $this->dbConnection->getQueryBuilder();
		$queryBuilder->delete('reader_prefs')->where('user_id = user_id')->setParameter('user_id', $userId);
		$queryBuilder->executeStatement();
	}

	private static function isJson(mixed $string): bool {
		return is_string($string) && is_array(json_decode($string, true)) && json_last_error() == JSON_ERROR_NONE;
	}
}
