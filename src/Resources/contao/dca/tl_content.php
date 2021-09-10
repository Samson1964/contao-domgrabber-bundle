<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   fen
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2013
 */

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['domgrabber'] = '{type_legend},type,headline;{domgrabber_legend},domgrabber_url,domgrabber_element,domgrabber_css;{protected_legend:hide},protected;{expert_legend:hide},guest,cssID;{invisible_legend:hide},invisible,start,stop';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['domgrabber_url'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['domgrabber_url'],
	'inputType'              => 'text',
	'eval'                   => array
	(
		'tl_class'           => 'long',
		'maxlength'          => 255,
	),
	'sql'                    => "varchar(255) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['domgrabber_element'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['domgrabber_element'],
	'inputType'              => 'text',
	'eval'                   => array
	(
		'tl_class'           => 'w50',
		'maxlength'          => 48,
	),
	'sql'                    => "varchar(48) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['domgrabber_css'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['domgrabber_css'],
	'exclude'                => true,
	'search'                 => true,
	'inputType'              => 'textarea',
	'eval'                   => array
	(
		'tl_class'           => 'clr',
		'mandatory'          => false,
		'allowHtml'          => true,
		'class'              => 'monospace',
		'rte'                => 'ace|css'
	),
	'sql'                    => "mediumtext NULL"
);
