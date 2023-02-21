<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2023 Leo Feyer
 *
 * @package   domgrabber
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2013
 */

$GLOBALS['TL_LANG']['tl_content']['domgrabber_legend'] = 'Einstellungen';

$GLOBALS['TL_LANG']['tl_content']['domgrabber_url'] = array('URL', 'URL der einzubettenden Webseite');
$GLOBALS['TL_LANG']['tl_content']['domgrabber_element'] = array('Element', 'HTML-Element, welches extrahiert und angezeigt werden soll, z.B. body, div (HTML-Container), #inhalt (eine CSS-ID) oder .ce_text (CSS-Klasse).');
$GLOBALS['TL_LANG']['tl_content']['domgrabber_css'] = array('CSS', 'Hier können Sie zusätzliches CSS angeben, um den eingebetteten Inhalt zu beeinflussen.');

$GLOBALS['TL_LANG']['tl_content']['cache_legend'] = 'Cache-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['includeCache'] = array('Cachezeit festlegen', 'Cachezeit für den eingebetteten Inhalt festlegen.');
$GLOBALS['TL_LANG']['tl_content']['cache'] = array('Cachezeit', 'Der Zeitraum, nach dem der Inhalt im Cache als veraltet eingestuft werden soll.');

// Optionen

$GLOBALS['TL_LANG']['tl_content']['cache_optionen'] = array
(
	'5'        => '5 Sekunden',
	'15'       => '15 Sekunden',
	'30'       => '30 Sekunden',
	'60'       => '1 Minute',
	'300'      => '5 Minuten',
	'900'      => '15 Minuten',
	'1800'     => '30 Minuten',
	'3600'     => '1 Stunde',
	'10800'    => '3 Stunden',
	'21600'    => '6 Stunden',
	'43200'    => '12 Stunden',
	'86400'    => '1 Tag',
	'259200'   => '3 Tage',
	'604800'   => '7 Tage',
	'2592000'  => '30 Tage',
);

