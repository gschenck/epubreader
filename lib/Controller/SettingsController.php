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

namespace OCA\Epubreader\Controller;

use OCA\Epubreader\Config;
use OCA\Epubreader\Service\PreferenceService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\IURLGenerator;

class SettingsController extends Controller {

	private $urlGenerator;
	private $preferenceService;

	/**
	 * @param string $AppName
	 * @param IRequest $request
	 * @param IURLGenerator $urlGenerator
	 * @param PreferenceService $preferenceService
	 */
	public function __construct($AppName,
		IRequest $request,
		IURLGenerator $urlGenerator,
		PreferenceService $preferenceService) {

		parent::__construct($AppName, $request);
		$this->urlGenerator = $urlGenerator;
		$this->preferenceService = $preferenceService;
	}

	/**
	 * @brief set preference for file type association
	 *
	 * @NoAdminRequired
	 *
	 * @param int $EpubEnable
	 * @param int $PdfEnable
	 * @param int $CbxEnable
	 *
	 * @return array|\OCP\AppFramework\Http\JSONResponse
	 */
	public function setPreference(int $EpubEnable, int $PdfEnable, int $CbxEnable) {

		$l = \OC::$server->getL10N('epubreader');

		Config::set('epub_enable', $EpubEnable);
		Config::set('pdf_enable', $PdfEnable);
		Config::set('cbx_enable', $CbxEnable);

		$response = array(
			'data' => array('message' => $l->t('Settings updated successfully.')),
			'status' => 'success'
		);

		return new JSONResponse($response);
	}
}
