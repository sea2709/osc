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

if (typeof akeeba.Log == 'undefined')
{
	akeeba.Log = {
	}
}

akeeba.Log.onLoadDefault = function(src, height)
{
	akeeba.System.addEventListener('showlog', 'click', function() {
		var frameHolder = document.getElementById('frame-holder');
		var frame = document.createElement('iframe');
		frame.setAttribute('width', '99%');
		frame.setAttribute('height', height);
		frame.src = src;
		frameHolder.appendChild(frame);

		document.getElementById('showlog').style.display = 'none';
	});
};

akeeba.Log.miscHandler = function()
{
	return false;
};

akeeba.Log.mouseHandler = function()
{
	var isNS = (navigator.appName == "Netscape") ? 1 : 0;

	var myevent     = (isNS) ? e : event;
	var eventbutton = (isNS) ? myevent.which : myevent.button;

	if ((eventbutton == 2) || (eventbutton == 3))
	{
		return false;
	}
};

akeeba.Log.onKeyDown = function()
{
	return false;
};

akeeba.Log.onLoadIFrame = function()
{
	// Disable right-click
	if (navigator.appName == "Netscape")
	{
		document.captureEvents(Event.MOUSEDOWN || Event.MOUSEUP);
	}

	document.oncontextmenu = akeeba.Log.miscHandler;
	document.onmousedown   = akeeba.Log.mouseHandler;
	document.onmouseup     = akeeba.Log.mouseHandler;

	// Disable CTRL-C, CTRL-V
	document.onkeydown = akeeba.Log.onKeyDown;
};
