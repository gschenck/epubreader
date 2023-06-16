<?php
/**
 * ownCloud - Epubreader App
 *
 * @author Frank de Lange
 * @copyright 2014,2018 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */

namespace OCA\Epubreader\Settings;

use OCA\Epubreader\AppInfo\Application;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\Settings\ISettings;

class Personal implements ISettings {

	private string $userId;
	private IConfig $configManager;

	public function __construct(
		string $userId,
		IConfig $configManager
	) {
		$this->userId = $userId;
		$this->configManager = $configManager;
	}

	/**
	 * @return TemplateResponse returns the instance with all parameters set, ready to be rendered
	 * @since 9.1
	 */
	public function getForm(): TemplateResponse {
		$parameters = [
			'EpubEnable' => $this->configManager->getUserValue($this->userId, Application::APP_ID, 'epub_enable'),
			'PdfEnable' => $this->configManager->getUserValue($this->userId, Application::APP_ID, 'pdf_enable'),
			'CbxEnable' => $this->configManager->getUserValue($this->userId, Application::APP_ID, 'cbx_enable'),
		];

		return new TemplateResponse(Application::APP_ID, 'settings-personal', $parameters, '');
	}

	/**
	 * Print config section (ownCloud 10)
	 *
	 * @return TemplateResponse
	 */
	public function getPanel(): TemplateResponse {
		return $this->getForm();
	}

	/**
	 * @return string the section ID, e.g. 'sharing'
	 * @since 9.1
	 */
	public function getSection(): string {
		return Application::APP_ID;
	}

	/**
	 * Get section ID (ownCloud 10)
	 *
	 * @return string
	 */
	public function getSectionID(): string {
		return Application::APP_ID;
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is required to return a value between 0 and 100.
	 *
	 * E.g.: 70
	 * @since 9.1
	 */
	public function getPriority(): int {
		return 10;
	}
}
