/*
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

if (typeof akeeba === 'undefined')
{
	var akeeba = {};
}

if (typeof akeeba.Manage === 'undefined')
{
	akeeba.Manage = {
		remoteManagementModal: null,
		uploadModal:           null,
		downloadModal:         null,
		infoModal:             null
	}
}

akeeba.Manage.onRemoteManagementClick = function (managementUrl, reloadUrl)
{
	akeeba.Modal.remoteManagementModal = akeeba.Modal.open({
		iframe:        managementUrl,
		width:         '450',
		height:        '280',
		closeCallback: function ()
					   {
						   akeeba.Modal.remoteManagementModal = null;
						   window.location                  = reloadUrl;
					   }
	});
};

akeeba.Manage.onUploadClick = function (uploadURL, reloadUrl)
{
	akeeba.Modal.uploadModal = akeeba.Modal.open({
		iframe:        uploadURL,
		width:         '450',
		height:        '280',
		closeCallback: function ()
					   {
						   akeeba.Modal.remoteManagementModal = null;
						   window.location                  = reloadUrl;
					   }
	});
};

akeeba.Manage.onDownloadClick = function (inheritFrom)
{
	akeeba.Modal.downloadModal = akeeba.Modal.open({
		inherit: inheritFrom,
		width:   '450',
		height:  '280'
	});
};

akeeba.Manage.onShowInfoClick = function (inheritFrom)
{
	akeeba.Modal.infoModal = akeeba.Modal.open({
		inherit: inheritFrom,
		width:   '450',
		height:  '280'
	});
};
