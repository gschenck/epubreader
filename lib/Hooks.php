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

use \OC\User\User as User;
use OCP\Files\Node;
use OCP\IDBConnection;
use OCP\Util;

class Hooks {

	public static function register(): void {
		/** @psalm-suppress DeprecatedMethod */
		Util::connectHook('\OCP\Config', 'js', 'OCA\Epubreader\Hooks', 'announce_settings');

		\OC::$server->getRootFolder()->listen('\OC\Files', 'preDelete', function (Node $node) {
			$fileId = $node->getId();
			$connection = \OC::$server->getDatabaseConnection();
			self::deleteFile($connection, $fileId);
		});
		\OC::$server->getUserManager()->listen('\OC\User', 'preDelete', function (User $user) {
			$userId = $user->getUID();
			$connection = \OC::$server->getDatabaseConnection();
			self::deleteUser($connection, $userId);
		});
	}

	public static function announce_settings(array $settings): void {
		// Nextcloud encodes this as JSON, Owncloud does not (yet) (#75)
		// TODO: rmeove this when Owncloud starts encoding oc_appconfig as JSON just like it already encodes most other properties
		if (array_key_exists('array', $settings) &&
			is_array($settings['array']) &&
			array_key_exists('oc_appconfig', $settings['array'])
		) {
			$isJson = self::isJson($settings['array']['oc_appconfig']);
			/** @var array $array */
			$array = ($isJson) ? json_decode((string) $settings['array']['oc_appconfig'], true) : $settings['array']['oc_appconfig'];
			$array['filesReader'] = [
				'enableEpub' => Config::get('epub_enable', 'true'),
				'enablePdf' => Config::get('pdf_enable', 'true'),
				'enableCbx' => Config::get('cbx_enable', 'true'),
			];
			$settings['array']['oc_appconfig'] = ($isJson) ? json_encode($array) : $array;
		}
	}

	protected static function deleteFile(IDBConnection $connection, int $fileId): void {
		$queryBuilder = $connection->getQueryBuilder();
		$queryBuilder->delete('reader_bookmarks')->where('file_id = file_id')->setParameter('file_id', $fileId);
		$queryBuilder->executeStatement();

		$queryBuilder = $connection->getQueryBuilder();
		$queryBuilder->delete('reader_prefs')->where('file_id = file_id')->setParameter('file_id', $fileId);
		$queryBuilder->executeStatement();
	}

	protected static function deleteUser(IDBConnection $connection, string $userId): void {
		$queryBuilder = $connection->getQueryBuilder();
		$queryBuilder->delete('reader_bookmarks')->where('user_id = user_id')->setParameter('user_id', $userId);
		$queryBuilder->executeStatement();

		$queryBuilder = $connection->getQueryBuilder();
		$queryBuilder->delete('reader_prefs')->where('user_id = user_id')->setParameter('user_id', $userId);
		$queryBuilder->executeStatement();
	}

	private static function isJson(mixed $string): bool {
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
}
