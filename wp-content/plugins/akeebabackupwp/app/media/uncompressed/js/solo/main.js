/*
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

// Object initialisation
if (typeof akeeba === 'undefined')
{
	var akeeba = {};
}

if (typeof akeeba.ControlPanel === 'undefined')
{
	akeeba.ControlPanel = {}
}

/* Warn about CloudFlare Rocket Loader */
akeeba.ControlPanel.displayCloudFlareWarning = function (testfile)
{
	if (!localStorage.getItem(testfile))
	{
		return;
	}

	document.getElementById('cloudFlareWarn').style.display = 'block';
};

akeeba.ControlPanel.showReadableFileWarnings = function (configURL, backupURL)
{
	akeeba.ControlPanel.isReadableFile(configURL, function ()
	{
		document.getElementById('config-readable-error').style.display = 'block';
	});

	akeeba.ControlPanel.isReadableFile(backupURL, function ()
	{
		document.getElementById('backup-readable-error').style.display = 'block';
	});
};

akeeba.ControlPanel.isReadableFile = function (myURL, callback)
{
	if (!myURL)
	{
		return;
	}

	akeeba.Ajax.ajax(myURL, {
		type:    'GET',
		success: function (responseText, statusText, xhr)
				 {
					 if (responseText.length > 0)
					 {
						 callback.apply();
					 }
				 }
	});
};

akeeba.ControlPanel.getUpdateInformation = function (updateInformationUrl)
{
	akeeba.Ajax.ajax(updateInformationUrl, {
		type:    'GET',
		success: function (msg)
				 {
					 // Initialize
					 var junk    = null;
					 var message = msg;

					 // Get rid of junk before the data
					 var valid_pos = msg.indexOf('###');

					 if (valid_pos == -1)
					 {
						 return;
					 }

					 if (valid_pos != 0)
					 {
						 // Data is prefixed with junk
						 message = msg.substr(valid_pos);
					 }

					 message = message.substr(3); // Remove triple hash in the beginning

					 // Get of rid of junk after the data
					 valid_pos = message.lastIndexOf('###');
					 message   = message.substr(0, valid_pos); // Remove triple hash in the end

					 try
					 {
						 var data = JSON.parse(message);
					 }
					 catch (err)
					 {
						 return;
					 }

					 var elUpdateContainer = document.getElementById('soloUpdateContainer');
					 var elUpdateIcon = document.getElementById('soloUpdateIcon');

					 if (data.hasUpdate)
					 {
						 elUpdateContainer.className = 'akeeba-action--orange';
						 elUpdateIcon.className = 'akion-android-warning';
						 document.getElementById('soloUpdateAvailable').style.display = 'inline-block';
						 document.getElementById('soloUpdateUpToDate').style.display = 'none';

						 document.getElementById('soloUpdateNotification').innerHTML = data.noticeHTML;
					 }
					 else
					 {
						 elUpdateContainer.className = 'akeeba-action--green';
						 elUpdateIcon.className = 'akion-checkmark-circled';
						 document.getElementById('soloUpdateAvailable').style.display = 'none';
						 document.getElementById('soloUpdateUpToDate').style.display = 'inline-block';
					 }
				 }
	});

};
