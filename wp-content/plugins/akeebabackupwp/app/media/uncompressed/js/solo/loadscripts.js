/*
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

if (typeof akeeba === 'undefined')
{
	var akeeba = {};
}

if (typeof akeeba.loadScripts === 'undefined')
{
	akeeba.loadScripts = [];
}

akeeba.replayedScripts = 0;

akeeba.replayLoadScripts = function()
{
    for (var i = akeeba.replayedScripts; i < akeeba.loadScripts.length; i++)
    {
        akeeba.loadScripts[i]();
    }

	akeeba.replayedScripts = i;

    window.setTimeout(akeeba.replayLoadScripts, 1000);
};

akeeba.System.documentReady(function ()
{
	akeeba.replayLoadScripts();
});
