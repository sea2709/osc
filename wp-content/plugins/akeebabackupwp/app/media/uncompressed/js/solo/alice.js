/*
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

var AKEEBA_ANALYZE_SUCCESS = 1;
var AKEEBA_ANALYZE_WARNING = 0;
var AKEEBA_ANALYZE_FAILURE = -1;

// Object initialisation
if (typeof akeeba === 'undefined')
{
	var akeeba = {};
}

if (typeof akeeba.Alice === 'undefined')
{
	akeeba.Alice = {
		log_selector: null,
		analyze:      null,
		download:     null,
		raw_output:   null,
		translations: {},
		akeebaUrl:    '',
        translateUrl: ''
	}
}

akeeba.Alice.onLogChange = function ()
{
	var thisValue = this.options[this.selectedIndex].value;

	akeeba.Alice.analyze.style.display  = 'none';
	akeeba.Alice.download.style.display = 'none';

	if (thisValue)
	{
		akeeba.Alice.analyze.style.display  = 'inline-block';
		akeeba.Alice.download.style.display = 'inline-block';
	}
};

akeeba.Alice.onDownload = function (e)
{
	if (e.preventDefault)
	{
		e.preventDefault();
	}
	else
	{
		e.returnValue = false;
	}

	var url = akeeba.System.data.get(this, 'url', '');
	var tag = akeeba.Alice.log_selector.options[akeeba.Alice.log_selector.selectedIndex].value;

	window.location = url + tag;

	return false;
};

akeeba.Alice.onAnalyze = function (e)
{
	if (e.preventDefault)
	{
		e.preventDefault();
	}
	else
	{
		e.returnValue = false;
	}

	if (akeeba.System.data.get(akeeba.Alice.analyze, 'started', false) == true)
	{
		return false;
	}

	akeeba.Alice.analyze.className = 'akeeba-btn--dark';
	akeeba.System.data.set(akeeba.Alice.analyze, 'started', true);

	akeeba.Alice.log_selector.setAttribute('disabled', 'disabled');

	// Remove previous messages
	var rows = document.querySelectorAll('#alice-messages tr');

	for (var i = 0; i < rows.length; i++)
	{
        rows[i].parentNode.removeChild(rows[i]);
    }

	akeeba.Alice.raw_output.style.display = 'none';
	document.getElementById('stepper-complete').style.display = 'none';

	var stepper = new AkeebaStepper({
        'akeebaUrl': akeeba.Alice.akeebaUrl,
		onBeforeStart: function (polling)
					   {
						   polling.data.log = akeeba.Alice.log_selector.options[akeeba.Alice.log_selector.selectedIndex].value;
					   },
		onComplete:    function (result)
					   {
						   var failuresLangKeys = [];
						   var tableBody          = document.getElementById('alice-messages');

						   var results    = JSON.parse(result.Results);
						   var successTxt = akeeba.Alice.translations['SOLO_ALICE_SUCCESSS'];
						   var warningTxt = akeeba.Alice.translations['SOLO_ALICE_WARNING'];
						   var errorTxt   = akeeba.Alice.translations['SOLO_ALICE_ERROR'];

						   for (var idx = 0; idx < results.length; idx++)
						   {
							   var item = results[idx];

							   var tableRow         = document.createElement('tr');

							   var lblResult = 'akeeba-label--success';
							   var text      = successTxt;

							   if (item.result == AKEEBA_ANALYZE_WARNING)
							   {
								   text      = warningTxt;
								   lblResult = 'akeeba-label--warning';
							   }
							   else if (item.result == AKEEBA_ANALYZE_FAILURE)
							   {
								   text      = errorTxt;
								   lblResult = 'akeeba-label--failure';
							   }

							   var descriptionCell       = document.createElement('td');
							   descriptionCell.innerHTML = item.check;
							   tableRow.appendChild(descriptionCell);

							   var resultCell       = document.createElement('td');
							   var resultSpan       = document.createElement('span');
							   resultSpan.className = lblResult;
							   resultSpan.innerHTML = text;
							   resultCell.appendChild(resultSpan);
							   tableRow.appendChild(resultCell);

							   if (item.result != AKEEBA_ANALYZE_SUCCESS)
							   {
								   failuresLangKeys.push({
										 'check': item.raw.check,
										 'error': item.raw.error
								   });

								   var holder       = document.createElement('div');
								   holder.className = 'akeeba-panel--red';
								   descriptionCell.appendChild(holder);

								   var errorElement       = document.createElement('p');
								   errorElement.innerHTML = item.error;
								   descriptionCell.appendChild(errorElement);

								   var solutionElement       = document.createElement('p');
								   solutionElement.innerHTML = '<em>' + item.solution + '</em>';
								   descriptionCell.appendChild(solutionElement);
							   }

							   tableBody.appendChild(tableRow);
						   }

						   document.getElementById('stepper-progress-pane').style.display = 'none';
						   document.getElementById('stepper-complete').style.display      = 'block';

						   akeeba.Alice.className = 'akeeba-btn--primary';
						   akeeba.System.data.set(akeeba.Alice.analyze, 'started', false);
						   akeeba.Alice.log_selector.removeAttribute('disabled');

						   // Got the language keys, now ask ALICE to always translate them to English
						   if (failuresLangKeys.length > 0)
						   {
							   akeeba.Ajax.ajax(akeeba.Alice.translateUrl, {
								   data:    {
									   'keys': JSON.stringify(failuresLangKeys)
								   },
								   success: function (translations)
											{
												var temp = translations.match(/###(.*?)###/);

												if (temp[1] === undefined)
												{
													return;
												}

												translations = JSON.parse(temp[1]);

												var raw = '------ BEGIN OF ALICE RAW OUTPUT -----\n';

												for (var idx = 0; idx < translations.length; idx++)
												{
													item = translations[idx];

													raw += '[b]' + item.check + '[/b]\n' + item.error + '\n\n';
												}


												raw += '------ END OF ALICE RAW OUTPUT -----\n';

												akeeba.Alice.raw_output.querySelector('textarea').innerHTML = raw;
												akeeba.Alice.raw_output.style.display                       = 'block';
											}
							   });
						   }
					   }
	});

	stepper.init();

	return false;
};
