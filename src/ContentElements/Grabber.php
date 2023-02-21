<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2023 Leo Feyer
 *
 * PHP version 5-8
 * @copyright  Frank Hoppe
 * @author     Frank Hoppe
 * @package    domgrabber
 * @license    LGPL
 * @filesource
 */

namespace Schachbulle\ContaoDomgrabberBundle\ContentElements;

// Symfony-Cache einbinden
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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
		$beginn = microtime(true);

		if($this->includeCache)
		{
			// Cache ist eingeschaltet, dann Symfony-Cache aktivieren
			$cache = new FilesystemAdapter();

			$content = $cache->get('domgrabber_'.$this->domgrabber_url, function(ItemInterface $item)
			{
				$item->expiresAfter($this->cache);
				$value = self::getURL();

				return $value;
			});
		}
		else
		{
			// Cache ist nicht eingeschaltet
			$content = self::getURL();
		}

		$dauer = microtime(true) - $beginn;
		//echo "Verarbeitung des Skripts: $dauer Sek.";

		// Template ausgeben
		$this->Template->skriptdauer = $dauer;
		$this->Template->content = $content;
		$this->Template->css = $this->domgrabber_css;

		return;
	}

	public function getURL()
	{
		$value = '';
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
			$value .= $item->innertext;

		return $value;
	}
}
