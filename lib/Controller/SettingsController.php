<?php

/**
 * ownCloud - Epubreader App.
 *
 * @author Frank de Lange
 * @copyright 2014,2018 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */

namespace OCA\Epubreader\Controller;

use OCA\Epubreader\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;

class SettingsController extends Controller
{
	private string $userId;
	private IL10N $l10n;
	private IConfig $configManager;

	public function __construct(
		string $appName,
		IRequest $request,
		string $userId,
		IL10N $l10n,
		IConfig $configManager
	) {
		parent::__construct($appName, $request);
		$this->userId = $userId;
		$this->l10n = $l10n;
		$this->configManager = $configManager;
	}

	/**
	 * @brief set preference for file type association
	 *
	 * @NoAdminRequired
	 */
	public function setPreference(string $EpubEnable, string $PdfEnable, string $CbxEnable): JSONResponse
	{
		$this->configManager->setUserValue($this->userId, Application::APP_ID, 'epub_enable', $EpubEnable);
		$this->configManager->setUserValue($this->userId, Application::APP_ID, 'pdf_enable', $PdfEnable);
		$this->configManager->setUserValue($this->userId, Application::APP_ID, 'cbx_enable', $CbxEnable);

		$response = [
			'data' => ['message' => $this->l10n->t('Settings updated successfully.')],
			'status' => 'success',
		];

		return new JSONResponse($response);
	}
}
