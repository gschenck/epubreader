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

use OCA\Epubreader\Service\BookmarkService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

class BookmarkController extends Controller {

	private BookmarkService $bookmarkService;

	/**
	 * @param string $AppName
	 * @param IRequest $request
	 * @param BookmarkService $bookmarkService
	 */
	public function __construct(
		string $AppName,
		IRequest $request,
		BookmarkService $bookmarkService
	) {
		parent::__construct($AppName, $request);
		$this->bookmarkService = $bookmarkService;
	}

	/**
	 * @brief return bookmark
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $fileId
	 * @param ?string $name
	 * @param ?string $type
	 */
	public function get(int $fileId, ?string $name = null, ?string $type = null): JSONResponse {
		return new JSONResponse($this->bookmarkService->get($fileId, $name, $type));
	}

	/**
	 * @brief write bookmark
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $fileId
	 * @param string $name
	 * @param string $value
	 * @param ?string $type
	 * @param ?string $content
	 */
	public function set(int $fileId, string $name, string $value, ?string $type = null, ?string $content = null): JSONResponse {
		return new JSONResponse($this->bookmarkService->set($fileId, $name, $value, $type, $content));
	}

	/**
	 * @brief return cursor for $fileId
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $fileId
	 */
	public function getCursor(int $fileId): JSONResponse {
		return new JSONResponse($this->bookmarkService->getCursor($fileId));
	}

	/**
	 * @brief write cursor for $fileId
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $fileId
	 * @param string $value
	 */
	public function setCursor(int $fileId, string $value): JSONResponse {
		return new JSONResponse($this->bookmarkService->setCursor($fileId, $value));
	}

	/**
	 * @brief delete bookmark
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $fileId
	 * @param string name
	 */
	public function delete(int $fileId, string $name): void {
		$this->bookmarkService->delete($fileId, $name);
	}

	/**
	 * @brief delete cursor
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $fileId
	 */
	public function deleteCursor(int $fileId): void {
		$this->bookmarkService->deleteCursor($fileId);
	}
}
