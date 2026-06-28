<?php

declare(strict_types=1);

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2023 Leo Feyer
 *
 * PHP version 8
 * @copyright  Frank Hoppe
 * @author     Frank Hoppe
 * @package    domgrabber
 * @license    LGPL
 * @filesource
 */

namespace Schachbulle\ContaoDomgrabberBundle\ContentElements;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\System;
// Symfony-Cache einbinden
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Inhaltselement "DOM-Grabber".
 *
 * Bindet das gewünschte Element einer externen Webseite in die eigene Seite ein.
 */
class Grabber extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_domgrabber';

	/**
	 * Inhaltselement aufbereiten
	 */
	protected function compile()
	{
		$request = System::getContainer()->get('request_stack')->getCurrentRequest();

		// Im Backend nur eine Vorschau anzeigen und die externe Seite NICHT abrufen.
		// (Ersetzt die in Contao 5 entfernte Konstante TL_MODE durch den Scope-Matcher.)
		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			$this->Template = new BackendTemplate('be_domgrabber');

			$this->Template->wildcard = '### DOMGRABBER ###';
			$this->Template->url = $this->domgrabber_url;
			$this->Template->element = $this->domgrabber_element;

			return;
		}

		$beginn = microtime(true);

		if ($this->includeCache)
		{
			// Cache ist eingeschaltet, dann Symfony-Cache aktivieren
			$cache = new FilesystemAdapter();

			// Der Cache-Schlüssel darf keine reservierten Zeichen (z. B. ":" oder "/") enthalten,
			// daher wird die URL als MD5-Hash abgelegt.
			$content = $cache->get('domgrabber_'.md5((string) $this->domgrabber_url), function (ItemInterface $item) {
				$item->expiresAfter((int) $this->cache);

				return $this->getURL();
			});
		}
		else
		{
			// Cache ist nicht eingeschaltet
			$content = $this->getURL();
		}

		$dauer = microtime(true) - $beginn;

		// Template ausgeben
		$this->Template->skriptdauer = $dauer;
		$this->Template->content = $content;
		$this->Template->css = $this->domgrabber_css;
	}

	/**
	 * Externe URL laden und das gewünschte Element extrahieren
	 *
	 * @return string
	 */
	public function getURL()
	{
		$value = '';
		// Parameter zuweisen, wegen Sonderzeichen das Element nach normalem HTML zurück konvertieren
		$url = (string) $this->domgrabber_url;
		$element = html_entity_decode((string) $this->domgrabber_element);
		$urlparam = parse_url($url);

		// Basis-URL (Schema + Host) für die Umschreibung relativer Pfade aufbauen
		$scheme = $urlparam['scheme'] ?? 'https';
		$host = $urlparam['host'] ?? '';
		$basis = $scheme.'://'.$host.'/';

		// Externe URL laden
		$html = \SimpleHtmlDom\file_get_html($url, false, null, 0);

		// Abbruch, wenn die Seite nicht geladen werden konnte
		if (!$html)
		{
			return $value;
		}

		// Links modifizieren
		foreach ($html->find('a') as $item)
		{
			$href = (string) $item->href;

			if (substr($href, 0, 4) != 'http' && substr($href, 0, 7) != 'mailto:')
			{
				$item->href = $basis.$href;
				$item->target = '_blank';
			}
		}

		// Bildquellen modifizieren
		foreach ($html->find('img') as $item)
		{
			$src = (string) $item->src;

			if (substr($src, 0, 4) != 'http')
				$item->src = $basis.$src;
		}

		// Formulare modifizieren
		foreach ($html->find('form') as $item)
		{
			$action = (string) $item->action;

			if (substr($action, 0, 4) != 'http' && substr($action, 0, 7) != 'mailto:')
				$item->action = $basis.$action;
		}

		// Gewünschtes Element ausgeben
		foreach ($html->find($element) as $item)
			$value .= $item->innertext;

		return $value;
	}
}
