<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   chesstable
 * Version    1.0.0
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2013
 */

class DOMGrabber extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_domgrabber';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \FrontendTemplate('be_domgrabber');

			$objTemplate->wildcard = '### DOMGRABBER ###';
			$objTemplate->url = $this->domgrabber_url;
			$objTemplate->element = $this->domgrabber_element;

			return $objTemplate->parse();
		}

		return parent::generate(); // Weitermachen mit dem Modul
	}
	
	/**
	 * Generate the module
	 */
	protected function compile()
	{

		// Parameter zuweisen, wegen Sonderzeichen das Element nach normalen HTML zurück konvertieren
		$url = $this->domgrabber_url;
		$element = html_entity_decode($this->domgrabber_element);
		$urlparam = parse_url($url);
		
		// Include Simple HTML Dom Parser
		require_once(TL_ROOT . '/system/helper/simple_html_dom.php');

		// Externe URL laden
		$html = file_get_html($url);

		// Links modifizieren
		foreach($html->find('a') as $item)
		{
			if(substr($item->href,0,4) != 'http' && substr($item->href,0,7) != 'mailto:')
			{
				$item->href = $urlparam['scheme'].'://'.$urlparam['host'].'/'.$item->href;
				$item->target = '_blank';
			}
		}

		// Bildquellen modifizieren
		foreach($html->find('img') as $item)
		{
			if(substr($item->src,0,4) != 'http')
				$item->src = $urlparam['scheme'].'://'.$urlparam['host'].'/'.$item->src;
		}

		// Formulare modifizieren
		foreach($html->find('form') as $item)
		{
			if(substr($item->action,0,4) != 'http' && substr($item->action,0,7) != 'mailto:')
				$item->action = $urlparam['scheme'].'://'.$urlparam['host'].'/'.$item->action;
		}


		// Gewünschtes Element ausgeben
		foreach($html->find($element) as $item)
			$content .= $item->innertext;

		// Template ausgeben
		$this->Template = new \FrontendTemplate($this->strTemplate);
		$this->Template->content = $content;
		$this->Template->css = $this->domgrabber_css;

		return;

	}

}
