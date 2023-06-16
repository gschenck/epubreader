<?php
/**
 * @author Frank de Lange
 * @copyright 2017 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Epubreader\Controller;

use OCA\Epubreader\Service\PreferenceService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

class PreferenceController extends Controller {

	private PreferenceService $preferenceService;

	/**
	 * @param string $AppName
	 * @param IRequest $request
	 * @param PreferenceService $preferenceService
	 */
	public function __construct(
		string $AppName,
		IRequest $request,
		PreferenceService $preferenceService
	) {
		parent::__construct($AppName, $request);
		$this->preferenceService = $preferenceService;
	}

	/**
	 * @brief return preference for $fileId
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $scope
	 * @param int $fileId
	 * @param ?string $name if null, return all preferences for $scope + $fileId
	 */
	public function get(string $scope, int $fileId, ?string $name = null): JSONResponse {
		return new JSONResponse($this->preferenceService->get($scope, $fileId, $name));
	}

	/**
	 * @brief write preference for $fileId
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $scope
	 * @param int $fileId
	 * @param string $name
	 * @param string $value
	 */
	public function set(string $scope, int $fileId, string $name, string $value): JSONResponse {
		return new JSONResponse($this->preferenceService->set($scope, $fileId, $name, $value));
	}


	/**
	 * @brief return default preference
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $scope
	 * @param string $name if null, return all default preferences for scope
	 */
	public function getDefault(string $scope, string $name): JSONResponse {
		return new JSONResponse($this->preferenceService->getDefault($scope, $name));
	}

	/**
	 * @brief write default preference
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $scope
	 * @param string $name
	 * @param string $value
	 */
	public function setDefault(string $scope, string $name, string $value): JSONResponse {
		return new JSONResponse($this->preferenceService->setDefault($scope, $name, $value));
	}

	/**
	 * @brief delete preference
	 *
	 * @param string $scope
	 * @param int $fileId
	 * @param string $name
	 */
	public function delete(string $scope, int $fileId, string $name): void {
		$this->preferenceService->delete($scope, $fileId, $name);
	}

	/**
	 * @brief delete default preference
	 *
	 * @param string $scope
	 * @param string $name
	 */
	public function deleteDefault(string $scope, string $name): void {
		$this->preferenceService->deleteDefault($scope, $name);
	}
}
