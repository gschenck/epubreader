/*
 * Copyright (c) 2015-2017 Frank de Lange
 * Copyright (c) 2013-2014 Lukas Reschke <lukas@owncloud.com>
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */


(function(OCA) {

	OCA.Epubreader = OCA.Epubreader || {};

	var isMobile = navigator.userAgent.match(/Mobi/i);
	var hasTouch = 'ontouchstart' in document.documentElement;

	function actionHandler(fileName, mime, context) {
		var downloadUrl = '';
		if($('#isPublic').val()) {
			var sharingToken = $('#sharingToken').val();
			downloadUrl = OC.generateUrl('/s/{token}/download?files={files}&path={path}', {
				token: sharingToken,
				files: fileName,
				path:  context.dir
			});
		} else {
			downloadUrl = Files.getDownloadUrl(fileName, context.dir);
		}
		OCA.Epubreader.Plugin.show(downloadUrl, mime, true);
	}

	/**
	 * @namespace OCA.Epubreader.Plugin
	 */
	OCA.Epubreader.Plugin = {

		/**
		 * @param fileList
		 */
		attach: function(fileList) {
			this._extendFileActions(fileList.fileActions);
		},

		hideControls: function() {
			$('#app-content #controls').hide();
            // and, for NC12...
            $('#app-navigation').css("display", "none");
		},

		hide: function() {
			if ($('#fileList').length) {
				FileList.setViewerMode(false);
			}
			$("#controls").show();
			$('#app-content #controls').removeClass('hidden');
            // NC12...
            $('#app-navigation').css("display", "");
			if ($('#isPublic').val()) {
				$('#imgframe').show();
				$('footer').show();
				$('.directLink').show();
				$('.directDownload').show();
			}
			$('iframe').remove();
			$('body').off('focus.filesreader');
			$(window).off('popstate.filesreader');
		},

		/**
		 * @param downloadUrl
		 * @param isFileList
		 */
		show: function(downloadUrl, mimeType, isFileList) {
			var self = this;
      var viewer = OC.generateUrl('/apps/epubreader/?file={file}&type={type}', {file: downloadUrl, type: mimeType});
			// launch in new window on all devices
			window.open(viewer, downloadUrl);
    },

		/**
		 * @param fileActions
		 * @private
		 */
		_extendFileActions: function(fileActions) {
			var self = this;
			var cbxMime = [
				'application/x-cbr',
				'application/comicbook+7z',
				'application/comicbook+ace',
				'application/comicbook+rar',
				'application/comicbook+tar',
				'application/comicbook+truecrypt',
				'application/comicbook+zip'
			];

			fileActions.registerAction({
				name: 'view-epub',
				displayName: 'View',
				mime: 'application/epub+zip',
				permissions: OC.PERMISSION_READ,
				actionHandler: function(fileName, context){
					return actionHandler(fileName, 'application/epub+zip', context);
				}
			});

			cbxMime.forEach(function(mime, i){
				fileActions.registerAction({
					name: 'view-cbr-' + i,
					displayName: 'View',
					mime: mime,
					permissions: OC.PERMISSION_READ,
					actionHandler: function (fileName, context) {
						return actionHandler(fileName, 'application/x-cbr', context);
					}
				});

				if (oc_appconfig.filesReader.enableCbx === 'true')
					fileActions.setDefault(mime, 'view-cbr-' + i);
			});

			fileActions.registerAction({
				name: 'view-pdf',
				displayName: 'View',
				mime: 'application/pdf',
				permissions: OC.PERMISSION_READ,
				actionHandler: function(fileName, context) {
					return actionHandler(fileName, 'application/pdf', context);
				}
			});

            if (oc_appconfig.filesReader.enableEpub === 'true')
                fileActions.setDefault('application/epub+zip', 'view-epub');
            if (oc_appconfig.filesReader.enablePdf === 'true')
                fileActions.setDefault('application/pdf', 'view-pdf');
		}
	};

})(OCA);

OC.Plugins.register('OCA.Files.FileList', OCA.Epubreader.Plugin);

// FIXME: Hack for single public file view since it is not attached to the fileslist
window.addEventListener('DOMContentLoaded', function () {
	var supported_mimetypes = ['application/epub+zip', 'application/pdf', 'application/x-cbr'];
	if (!$('#isPublic').val() || !supported_mimetypes.includes($('#mimetype').val())
	) {
		return;
	}

	var sharingToken = $('#sharingToken').val();
	var downloadUrl = OC.generateUrl('/s/{token}/download', {token: sharingToken});

	var content = $('#files-public-content');
	var footerElmt = document.querySelector('body > footer') || document.querySelector('#app-content > footer')
	var mainContent = document.querySelector('#content')

	var mime = $('#mimetype').val();
	var viewerUrl = OC.generateUrl('/apps/epubreader/?file={file}&type={type}', {file: downloadUrl, type: mime});

	// Create viewer frame
	const viewerNode = document.createElement('iframe')
	viewerNode.style.height = '100%'
	viewerNode.style.width = '100%'
	viewerNode.style.position = 'absolute'

	// Inject viewer
	content.empty()
	content.append(viewerNode)
	viewerNode.src = viewerUrl
	footerElmt.style.display = 'none'
	mainContent.style.minHeight = 'calc(100% - var(--header-height))' // Make the viewer take the whole height as the footer is now hidden.
	// overwrite style in order to fix the viewer on public pages
	mainContent.style.marginLeft = '0'
	mainContent.style.marginRight = '0'
	mainContent.style.width = '100%'
	mainContent.style.borderRadius = 'unset'
});
