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

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

class PersonalSection implements IIconSection {

	private IURLGenerator $urlGenerator;
	private IL10N $l;

	public function __construct(IURLGenerator $urlGenerator, IL10N $l) {
		$this->urlGenerator = $urlGenerator;
		$this->l = $l;
	}

	/**
	 * returns the relative path to an 16*16 icon describing the section.
	 *
	 * @return string
	 */
	public function getIcon(): string {
		return $this->urlGenerator->imagePath('epubreader', 'app.svg');
	}

	/**
	 * returns the ID of the section. It is supposed to be a lower case string,
	 *
	 * @return string
	 */
	public function getID(): string {
		return 'epubreader';
	}

	/**
	 * returns the translated name as it should be displayed
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->l->t('EPUB/CBZ/PDF ebook reader');
	}

	/**
	 * returns priority for positioning
	 *
	 * @return int
	 */
	public function getPriority(): int {
		return 20;
	}
}
