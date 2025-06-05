# Wdir_XH

Wdir_XH ermöglicht die Anzeige von Verzeichnislistings auf Ihrer Website, so
dass sie leicht eine Menge von Dateien zum Download anbieten können.
Wdir_XH ist als Nachfolger des beliebten Wdir von Joachim Barthels gedacht,
das seit langer Zeit nicht mehr weiter entwickelt wird.
Leider erlaubt die Lizenz von Wdir keine Modifikationen,
so dass Wdir_XH von Grund auf neu geschrieben wurde.

- [Voraussetzungen](#voraussetzungen)
- [Download](#download)
- [Installation](#installation)
- [Einstellungen](#einstellungen)
- [Verwendung](#verwendung)
  - [Filtern](#filtern)
- [Einschränkungen](#einschränkungen)
- [Fehlerbehebung](#fehlerbehebung)
- [Lizenz](#lizenz)
- [Danksagung](#danksagung)

## Voraussetzungen

Wdir_XH ist ein Plugin für [CMSimple_XH](https://cmsimple-xh.org/de/).
Es benötigt CMSimple_XH ≥ 1.8 und PHP ≥ 7.4.0.

## Download

Das [aktuelle Release](https://github.com/cmb69/wdir_xh/releases/latest)
kann von Github herunter geladen werden.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple_XH-Plugins auch.

1. Sichern Sie die Daten auf Ihrem Server.
1. Entpacken Sie die ZIP-Datei auf Ihrem Computer.
1. Laden Sie den gesamten Order `wdir/` auf Ihren Server in den `plugins/`
   Ordner von CMSimple_XH hoch.
1. Vergeben Sie Schreibrechte für die Unterordner `css/`, `config/`
   und `languages/`.
<!--
1. Prüfen Sie unter `Plugins` → `Wdir` im Backend der Website
ob alle Voraussetzungen für den Betrieb erfüllt sind.
-->

## Einstellungen

Die Konfiguration des Plugins erfolgt wie bei vielen anderen
CMSimple_XH-Plugins auch im Administrationsbereich der Website.
Gehen Sie zu `Plugins` → `Wdir`.

Sie können die Original-Einstellungen von Wdir_XH unter `Konfiguration`
ändern. Beim Überfahren der Hilfe-Icons mit der Maus werden Hinweise zu den
Einstellungen angezeigt.

Die Lokalisierung wird unter `Sprache` vorgenommen. Sie können die
Zeichenketten in Ihre eigene Sprache übersetzen (falls keine entsprechende
Sprachdatei zur Verfügung steht), oder sie entsprechend Ihren Anforderungen
anpassen.

Das Aussehen von Wdir_XH kann unter `Stylesheet` angepasst werden.

## Verwendung

Wdir_XH ermöglicht die Anzeige von Dateien im Userfiles-Ordner (voreingestellt
ist `userfiles/`).

Um ein Verzeichnislisting auf einer Seite anzuzeigen, verwenden Sie:

    {{{wdir('PFAD')}}}

wobei `PFAD` ein Unterordner des Userfiles Ordners ist. Wenn Sie z.B. den
Inhalt von `userfiles/downloads/` anzeigen möchten, dann schreiben Sie:

    {{{wdir('downloads')}}}

Die Dateinamen in der Liste sind auf die Dateien verlinkt, so dass Besucher
zu den Dateien surfen können; abhängig von den Server- und Browsereinstellungen
können manche Dateien direkt im Browser angesehen werden, während andere zum
Download angeboten werden.

Wdir_XH zeigt keine Unterordner des als Argument an die Funktion übergebenen
Ordners an, so dass Besucher die Verzeichnisse nicht durchlaufen können. Wenn
Sie Besuchern erlauben möchten, einige der Unterordner einzusehen, müssen Sie
mehrere Aufrufe von `wdir()` auf einer Seite (oder verschiedenen Seiten)
platzieren.

### Filtern

Sie können `wdir()` ein zweites Argument übergeben, um die Dateien zu filtern;
d.h. nur die Dateinamen, die sich mit dem Filter decken, werden angezeigt.

Im Standard-Modus sind die Filterausdrücke einfache Platzhalter-Schablonen,
wobei ein Asterisk (`*`) einen Platzhalter für eine beliebige Anzahl von Zeichen,
und ein Fragezeichen (`?`) einen Platzhalter für ein einzelnes Zeichen
darstellt.

Wenn Sie also nur PDF-Dateien im Userfiles Ordner anzeigen wollen, verwenden
Sie:

    {{{wdir('', '*.pdf')}}}

Wenn Sie alle Dateien im Userfiles Ordner anzeigen möchten, die mit
"Vertrag_" beginnen, schreiben Sie

    {{{wdir('', 'Vertrag_*')}}}

Eine sehr viel mächtigere Möglichkeit des Filterns kann durch Setzen der
Konfigurationsoption `Filter` → `Regexp` aktiviert werden. Dann wird das zweite
Argument für `wdir()` als PERL kompatibler regulärer Ausdruck interpretiert.
Dieser Modus sollte nur von fortgeschrittenen Webmastern verwendet werden, die
die Syntax im [PHP-Handbuch](https://www.php.net/manual/de/pcre.pattern.php)
nachschlagen können.

## Einschränkungen

Wdir_XH verfügt derzeit noch nicht über alle Möglichkeiten von Wdir 03beta,
und ein paar werden wahrscheinlich niemals implementiert werden (wie die Anzeige
des Datei-Besitzers und der -Berechtigungen).

## Fehlerbehebung

Melden Sie Programmfehler und stellen Sie Supportanfragen entweder auf
[Github](https://github.com/cmb69/wdir_xh/issues) oder im
[CMSimple_XH Forum](https://cmsimpleforum.com/).

## Lizenz

Wdir_XH ist freie Software. Sie können es unter den Bedingungen der
GNU General Public License, wie von der Free Software Foundation
veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß
Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung von Wdir_XH erfolgt in der Hoffnung, dass es
Ihnen von Nutzen sein wird, aber ohne irgendeine Garantie, sogar ohne
die implizite Garantie der Marktreife oder der Verwendbarkeit für einen
bestimmten Zweck. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit
Wdir_XH erhalten haben. Falls nicht, siehe <https://www.gnu.org/licenses/>.

Copyright © Christoph M. Becker

Slovakische Übersetzung © 2015 Dr. Martin Sereday<br>
Russische Übersetzung © 2015 Васильев Леонид Валерьевич

## Danksagung

Das Plugin-Icon wurde von [Alexander Moore](https://www.famfamfam.com/) gestaltet.
Vielen Dank für die Veröffentlichung des Icons unter GPL.

Die Datei-Icons wurden von [19eighty7](https://www.19eighty7.com/) gestaltet.
Vielen Dank für die Veröffentlichung unter einer liberalen Lizenz.

Die Sortier-Icons wurden [Wikimedia Commons](https://commons.wikimedia.org/wiki/Category:Table_sort_icons)
entnommen. Vielen Dank für die Veröffentlichung unter einer liberalen Lizenz.

Vielen Dank an die Gemeinschaft im [CMSimple_XH Forum](https://www.cmsimpleforum.com/)
für Tipps, Vorschläge und das Testen.

Und zu guter letzt vielen Dank an [Peter Harteg](https://www.harteg.dk/),
den „Vater“ von CMSimple, und allen Entwicklern von [CMSimple_XH](https://www.cmsimple-xh.org/de/)
ohne die es dieses phantastische CMS nicht gäbe.
