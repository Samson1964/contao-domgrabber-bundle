<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2017 Leo Feyer
 *
 * PHP version 5
 * @copyright  Frank Hoppe
 * @author     Frank Hoppe
 * @package    references
 * @license    LGPL
 * @filesource
 */

namespace Schachbulle\ContaoDomgrabberBundle\ContentElements;

/**
 * Class Reference
 *
 */
class Grabber extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_domgrabber';

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
	 * Generate the content element
	 */
	protected function compile()
	{
		// Parameter zuweisen, wegen Sonderzeichen das Element nach normalen HTML zurück konvertieren
		$url = $this->domgrabber_url;
		$element = html_entity_decode($this->domgrabber_element);
		$urlparam = parse_url($url);
		
		// Externe URL laden
		$html = \SimpleHtmlDom\file_get_html($url, false, null, 0);

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
		$this->Template->content = $content;
		$this->Template->css = $this->domgrabber_css;

		return;
	}
}
