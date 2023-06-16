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

use OCA\Epubreader\Service\MetadataService;
use OCP\AppFramework\Controller;

use OCP\IRequest;

class MetadataController extends Controller {

	private $metadataService;

	/**
	 * @param string $AppName
	 * @param IRequest $request
	 * @param MetadataService $metadataService
	 */
	public function __construct($AppName,
		IRequest $request,
		MetadataService $metadataService) {

		parent::__construct($AppName, $request);
		$this->metadataService = $metadataService;
	}


	/**
	 * @brief write metadata
	 *
	 * @NoAdminRequired
	 *
	 * @param int $fileId
	 * @param string $value
	 *
	 * @return array|\OCP\AppFramework\Http\JSONResponse
	 */
	public function setAll($fileId, $value) {
		return $this->metadataService->setAll($fileId, $value);
	}

	/**
	 * @brief return metadata item
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $fileId
	 * @param string $name
	 *
	 * @return array|\OCP\AppFramework\Http\JSONResponse
	 */
	public function get($fileId, $name) {
		return $this->metadataService->get($fileId, $name);
	}

	/**
	 * @brief write metadata item
	 *
	 * @NoAdminRequired
	 *
	 * @param int $fileId
	 * @param string $name
	 * @param string $value
	 *
	 * @return array|\OCP\AppFramework\Http\JSONResponse
	 */
	public function set($fileId, $name, $value) {
		return $this->metadataService->set($fileId, $name, $value);
	}

}
