<?php
/**
 * @author Frank de Lange
 * @copyright 2015 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Epubreader\Controller;

use OCA\Epubreader\Service\BookmarkService;
use OCA\Epubreader\Service\PreferenceService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Files\FileInfo;
use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Share\IManager;

class PageController extends Controller {

	private IURLGenerator $urlGenerator;
	private IRootFolder $rootFolder;
	private IManager $shareManager;
	private string $userId;
	private BookmarkService $bookmarkService;
	private PreferenceService $preferenceService;

	/**
	 * @param string $AppName
	 * @param IRequest $request
	 * @param IURLGenerator $urlGenerator
	 * @param IRootFolder $rootFolder
	 * @param IManager $shareManager
	 * @param string $UserId
	 * @param BookmarkService $bookmarkService
	 * @param PreferenceService $preferenceService
	 */
	public function __construct(
		string $AppName,
		IRequest $request,
		IURLGenerator $urlGenerator,
		IRootFolder $rootFolder,
		IManager $shareManager,
		string $UserId,
		BookmarkService $bookmarkService,
		PreferenceService $preferenceService,
	) {
		parent::__construct($AppName, $request);
		$this->urlGenerator = $urlGenerator;
		$this->rootFolder = $rootFolder;
		$this->shareManager = $shareManager;
		$this->userId = $UserId;
		$this->bookmarkService = $bookmarkService;
		$this->preferenceService = $preferenceService;
	}

	/**
	 * @PublicPage
	 * @NoCSRFRequired
	 *
	 * @return TemplateResponse
	 */
	public function showReader(): TemplateResponse {
		$templates = [
			'application/epub+zip' => 'epubreader',
			'application/x-cbr' => 'cbreader',
			'application/pdf' => 'pdfreader'
		];

		/**
		 * @var array{
		 *   fileId: int,
		 *   fileName: string,
		 *   fileType: string
		 * } $fileInfo
		 */
		$fileInfo = $this->getFileInfo((string) $this->request->getParam('file'));
		$fileId = $fileInfo['fileId'];
		$type = (string) $this->request->getParam('type');
		$scope = $template = $templates[$type];

		$params = [
			'urlGenerator' => $this->urlGenerator,
			'downloadLink' => $this->request->getParam('file'),
			'scope' => $scope,
			'fileId' => $fileInfo['fileId'],
			'fileName' => $fileInfo['fileName'],
			'fileType' => $fileInfo['fileType'],
			'cursor' => $this->toJson($this->bookmarkService->getCursor($fileId)),
			'defaults' => $this->toJson($this->preferenceService->getDefault($scope)),
			'preferences' => $this->toJson($this->preferenceService->get($scope, $fileId)),
			'metadata' => $this->toJson([]),
			'annotations' => $this->toJson($this->bookmarkService->get($fileId))
		];

		$policy = new ContentSecurityPolicy();
		$policy->addAllowedStyleDomain('\'self\'');
		$policy->addAllowedStyleDomain('blob:');
		$policy->addAllowedScriptDomain('\'self\'');
		$policy->addAllowedFrameDomain('\'self\'');
		$policy->addAllowedFontDomain('\'self\'');
		$policy->addAllowedFontDomain('data:');
		$policy->addAllowedFontDomain('blob:');
		$policy->addAllowedImageDomain('blob:');

		$response = new TemplateResponse($this->appName, $template, $params, 'blank');
		$response->setContentSecurityPolicy($policy);

		return $response;
	}

	/**
	 * @brief sharing-aware file info retriever
	 *
	 * Work around the differences between normal and shared file access
	 * (this should be abstracted away in OC/NC IMnsHO)
	 *
	 * @param string $path path-fragment from url
	 * @return array
	 * @throws NotFoundException
	 */
	private function getFileInfo(string $path): array {
		$count = 0;
		$shareToken = preg_replace("/(?:\/index\.php)?\/s\/([A-Za-z0-9]{15,32})\/download.*/", "$1", $path, 1, $count);

		if ($count === 1) {
			/* shared file or directory */
			$node = $this->shareManager->getShareByToken($shareToken)->getNode();
			$type = $node->getType();

			/* shared directory, need file path to continue, */
			if ($type == FileInfo::TYPE_FOLDER && $node instanceof Folder) {
				$query = [];
				parse_str(parse_url($path, PHP_URL_QUERY), $query);
				if (isset($query['path']) && is_string($query['path'])) {
					$node = $node->get($query['path']);
				} else {
					throw new NotFoundException('Shared file path or name not set');
				}
			}
			$filePath = $node->getPath();
			$fileId = $node->getId();
		} else {
			$filePath = $path;
			$fileId = $this->rootFolder->getUserFolder($this->userId)
				->get(preg_replace("/.*\/remote.php\/webdav(.*)/", "$1", rawurldecode((string) $this->request->getParam('file'))))
				->getId();
		}

		/** @var string[] $pathInfo */
		$pathInfo = pathInfo($filePath);

		return [
			'fileName' => $pathInfo['filename'],
			'fileType' => strtolower($pathInfo['extension']),
			'fileId' => $fileId
		];
	}

	/**
	 * @param mixed $value
	 */
	private function toJson($value): string {
		return htmlspecialchars(json_encode($value), ENT_QUOTES, 'UTF-8');
	}
}
