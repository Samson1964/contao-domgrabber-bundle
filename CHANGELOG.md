# DOM-Grabber Changelog

## Version 3.1.0 (2026-06-28) - mit Claude Code

Vollständige Kompatibilität mit **Contao 5.7** und **PHP 8.4** (Symfony 7) hergestellt.

### Inhaltselement (src/ContentElements/Grabber.php)

* Fix: Die in Contao 5 entfernte Konstante `TL_MODE` durch den Scope-Matcher (`contao.routing.scope_matcher`) ersetzt. Bisher führte `TL_MODE == 'BE'` im Frontend zu einem Fatal Error („Undefined constant TL_MODE").
* Change: Backend-Vorschau vom `generate()`-Override in die `compile()`-Methode verlagert (entspricht dem Vorgehen des Contao-5-Cores) und von `\FrontendTemplate` auf `Contao\BackendTemplate` umgestellt. Die externe Seite wird im Backend nicht mehr abgerufen.
* Change: Voll qualifizierte Klassennamen verwendet (`Contao\ContentElement` statt `\ContentElement`) und `use`-Importe ergänzt.
* Fix: Cache-Schlüssel wird als MD5-Hash der URL gebildet. Rohe URLs enthalten reservierte Zeichen (`:`, `/`), die der Symfony-Cache nicht als Schlüssel zulässt (CacheException).
* Fix: Cachezeit explizit nach `int` gecastet (`expiresAfter()`).
* Fix: Null-sichere String-Behandlung (Casts) gegen PHP-8.4-Deprecations sowie Abbruch, wenn die externe Seite nicht geladen werden kann.
* Add: `declare(strict_types=1);` ergänzt.

### Service-Konfiguration (src/Resources/config/services.yml)

* Fix: `Symfony\Component\DependencyInjection\ContainerAwareInterface` entfernt – die Klasse existiert in Symfony 7 (Contao 5.7) nicht mehr.

### DCA (src/Resources/contao/dca/tl_content.php)

* Fix: Feld `guest` aus der Palette entfernt – das Feld wurde in Contao 5 aus `tl_content` entfernt.

### composer.json

* Change: PHP-Anforderung von `^8` auf `^8.3` angehoben (Contao 5.7 setzt PHP 8.3 voraus, deckt PHP 8.4 ab).
* Change: `contao/core-bundle` von `^5` auf `^5.3` präzisiert.
* Fix: `symfony/dependency-injection` von `^6.4` auf `^6.4 || ^7.0` erweitert (Symfony 7).
* Change: `symfony/cache` von `*` auf `^6.4 || ^7.0` präzisiert.
* Remove: Ungenutzte Abhängigkeit `components/flag-icon-css` entfernt.
* Remove: Abgekündigte Dev-Abhängigkeit `doctrine/doctrine-cache-bundle` entfernt (verhindert die Installation unter PHP 8.4).

## Version 3.0.1 (2026-01-30)

* Change: composer.json für Contao 5

## Version 3.0.0 (2026-01-30)

* Add: Abhängigkeit Contao 5 hinzugefügt

## Version 2.1.0 (2023-02-21)

* Add: Abhängigkeit symfony/cache
* Add: Grabber.php um Symfony-Cache-Nutzung erweitert
* Add: Cache-Einstellungen im Inhaltselement

## Version 2.0.0 (2023-02-21)

* Add: README.md
* Fix: Grabber.php tauglich für PHP 8 gemacht
* Change: Hilfetexte tl_content verbessert

## Version 1.0.2 (2023-02-21)

* Fix: composer.json -> PHP: ^5.6 || ^7 || ^8

## Version 1.0.1 (2023-02-21)

* Change: PHP-Abhängigkeit auf größer 5.6 erweitert, siehe https://github.com/Samson1964/contao-domgrabber-bundle/issues/2

## Version 1.0.0 (2021-09-10)

* Add: Abhängigkeit axllent/simplehtmldom (Fork des Original simple_html_dom.php)
* Fix: Grabber.php, Parameter bei Abruf einer URL

## Version 0.0.2 (2021-09-10)

* Fix: Plugin.php - falscher Bundle-Aufruf

## Version 0.0.1 (2021-09-10)

* Erste Version als Contao 4, übernommen von der C3-Erweiterung
