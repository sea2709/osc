/*
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

var AkeebaStepper = function (options)
{
	this.options = {
		/* Element definition */
		holder:     '#stepper-holder',
		loading:    '#stepper-loading',
		pane:       '#stepper-progress-pane',
		content:    '#stepper-progress-content',
		steps:      '#stepper-steps',
		status:     '#stepper-status',
		step:       '#stepper-step',
		substep:    '#stepper-substep',
		percentage: '#stepper-percentage',
		timer:      '#response-timer',
		akeebaUrl:  'index.php?view=Alice',

		useIframe:  false,
		domainUrl:  '&task=domains',
		pollingUrl: '&task=ajax',

		/* Event hooks */
		onBeforeStart: function (polling)
					   {
					   },
		onBeforeStep:  function (polling, previousResult)
					   {
					   },
		onComplete:    function (result)
					   {
					   }
	};

	this.options = array_merge(this.options, options || {});

	// Private members
	var that = this;

	var pollingObj = {
		cache:    false,
		data:     {},
		dataType: 'text',
		success:  function (data)
				  {
					  var match  = new RegExp('###(.*?)###').exec(data);
					  var result = JSON.parse(match[1]);

					  that.renderBars(result.Domain);

					  document.querySelector(that.options.percentage + ' div.akeeba-progress-fill').style.width = result.Progress + '%';
					  document.querySelector(that.options.step).textContent                    = result.Step;
					  document.querySelector(that.options.substep).textContent                 = result.Substep;

					  if (result.HasRun == 1)
					  {
						  that.complete(result);
					  }
					  else
					  {
						  setTimeout(function ()
						  {
							  that.step(result)
						  }, 10);
					  }
				  }
	};

	// Private functions
	var getDomains   = function ()
	{
		akeeba.Ajax.ajax(that.options.akeebaUrl + that.options.domainUrl, {
			cache:      false,
			dataType:   'text',
			beforeSend: function ()
						{
							document.querySelector(that.options.loading).style.display = 'block';
						},
			success:    function (data)
						{
							var match    = new RegExp('###(.*?)###').exec(data);
							that.domains = JSON.parse(match[1]);

							document.querySelector(that.options.loading).style.display = 'none';
							document.querySelector(that.options.pane).style.display    = 'block';

							that.continueAfterLoad();
						}
		})
	};
	var startTimeout = function ()
	{
		var lastResponseSeconds = 0;

		var timer = setInterval(function ()
		{
			lastResponseSeconds++;
			document.querySelector(that.options.timer + ' div.text').textContent =
				akeeba.Alice.translations['UI-LASTRESPONSE'].replace('%s', lastResponseSeconds.toFixed(0));
		}, 1000);

		var timerElement = document.querySelector(that.options.timer);
		akeeba.System.data.set(timerElement, 'akeebatimer', timer);
	};
	var resetTimeout = function ()
	{
		var timerElement = document.querySelector(that.options.timer);
		var timer        = akeeba.System.data.get(timerElement, 'akeebatimer');
		clearInterval(timer);

		document.querySelector(that.options.timer + ' div.text')
			.textContent = akeeba.Alice.translations['UI-LASTRESPONSE'].replace('%s', '0');
	};

	// Public functions
	this.init = function ()
	{
		getDomains();
	};

	this.continueAfterLoad = function ()
	{
		this.renderBars();
		startTimeout();
		this.start();
	};

	this.renderBars = function (active_step)
	{
		var normal_class = 'akeeba-label--green';

		if (active_step == undefined)
		{
			normal_class = 'akeeba-label--grey';
		}
		
		var this_class   = 'akeeba-label--teal';

		var steps       = document.querySelector(that.options.steps);
		steps.innerHTML = '';

		for (var counter = 0; counter < this.domains.length; counter++)
		{
			var element = this.domains[counter];

			var step         = document.createElement('div');
			step.className   = '';
			step.textContent = element[1];
			akeeba.System.data.set(step, 'domain', element[0]);
			document.querySelector(that.options.steps).appendChild(step);

			if (element[0] == active_step)
			{
				normal_class = 'akeeba-label--grey';
				this_class   = 'akeeba-label--teal';
			}
			else
			{
				this_class = normal_class;
			}

			step.className += ' ' + this_class;
		}
	};

	this.start = function ()
	{
		this.options.onBeforeStart(pollingObj);
		pollingObj.data.ajax = 'start';
		akeeba.Ajax.ajax(that.options.akeebaUrl + this.options.pollingUrl, pollingObj);
	};

	this.step = function (previousResult)
	{
		this.options.onBeforeStep(pollingObj, previousResult);
		resetTimeout();
		startTimeout();
		pollingObj.data.ajax = 'step';
		akeeba.Ajax.ajax(that.options.akeebaUrl + this.options.pollingUrl, pollingObj);
	};

	this.complete = function (result)
	{
		resetTimeout();
		this.options.onComplete(result);
	};
};
