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
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;

class SettingsController extends Controller {

	/**
	 * @brief set preference for file type association
	 *
	 * @NoAdminRequired
	 *
	 * @param int $EpubEnable
	 * @param int $PdfEnable
	 * @param int $CbxEnable
	 */
	public function setPreference(int $EpubEnable, int $PdfEnable, int $CbxEnable): JSONResponse {
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
