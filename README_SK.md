# Wdir_XH

Wdir_XH umožňuje zobrazovanie obsahu adresárov na Vašej webstránke,
takže umožňuje aj nastavenie rôznych zoznamov na sžahopvanie súborov.
Wdir_XH možno považovať za nástupcu obľúbeného pluginu Wdir by Joachim Barthels,
ktorý sa už dávnejšie nevyvíja.
Škoda, že livencia, pod ktorou bol dostupný, neumožňuje modifikácie.
Preto bol Wdir_XH od zájkladu prepísaný.

- [Požiadavky](#požiadavky)
- [Download](#download)
- [Inštalácia](#inštalácia)
- [Nastavenia](#nastavenia)
- [Použitie](#použitie)
  - [Filtrovanie](#filtrovanie)
- [Obmedzenia](#obmedzenia)
- [Troubleshooting](#troubleshooting)
- [Licencia](#licencia)
- [Záruky](#záruky)

## Požiadavky

Wdir_XH je plugin pre [CMSimple_XH](https://cmsimple-xh.org).
Vyžaduje CMSimple_XH ≥ 1.8 a PHP ≥ 7.4.0.
Wdir_XH also requires [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.10;
if that is not already installed (see `Settings` → `Info`),
get the [lastest release](https://github.com/cmb69/plib_xh/releases/latest),
and install it.

## Download

The [lastest release](https://github.com/cmb69/wdir_xh/releases/latest)
is available for download on Github.

## Inštalácia

Inštalácia prebieha rovnako ako pri väčšine pluginov pre CMSimple_XH.

1. Urogte si zálohu Vašich údajov na serveri.
1. Rozbaľtw inštalačný balíček vo Vašom počítači.
1 .Skopírujte celý adresár `wdir/` do adresára `plugins/` na Vašom serveri.
1. Nastavte potrebné oprávnenia pre adresáre `css/`, `config/` a `languages/`.
<!--
1. Check under `Plugins` → `Wdir` in the back-end of the website
   that all requirements are fulfilled.
-->

## Nastavenia

Konfigurácia pluginu sa vykonáva - rovnako ako pri väčšine pluginov pre CMSimple_XH
- v správcovskom prostredí.
Prihláste sa ako správca stránky a zvoľte `Wdir` v zozname `Plugins`.

You can change the default settings of Wdir_XH under `Config`.  Hints for the
options will be displayed when hovering over the help icon with your mouse.

Jazykový súbor (ak nie je k dispozícii v inštalačnom balíku) vytvoríte aktualizáciou údajov v `Language`.
Tu môžete jednoducho preložiť textové reťazce do Vášho jazyka alebo ich upraviť podľa vašich potrieb.

The look of Wdir_XH can be customized under `Stylesheet`.

## Použitie

Východiskovým adresárom pre Wdir_XH je adresár `/userfiles`.
Pre zobrazenie obsahu adresára na stránke použite:

    {{{wdir('PATH')}}}

kde PATH je názov podadresára v adresári `userfiles/`.
TEda ak chcete zobraziť obsah adresára `userfiles/downloads/`, použijete:

    {{{wdir('downloads')}}}

Ak chcete zobraziť celý obsah adresára `userfiles/`, použite:

    {{{wdir('')}}}

Názvy súborov v zoznamoch sú formátované ako odkazy na tieto súbory,
takže užívatelia k nim majú prástup v závislosti od nastavenia ich serverov
(niektoré sa dajú priamo zobraziť v prehliadači, iné prostredníctvom ich lokálnych programov,
alebo sa dajú stiahnuť a uložiť.

Wdir_XH nezobrazuje žiadne podadresáre adresárov udaných ako argument funkcie Wdir,
takže návštevníci k podadresárom nemajú prístup.
Ak chcete návštevníkom umožniť prístup aj k podadresárom, musíte na stránke použiť
pre každý z nich samostatný príkaz `wdir()`.

### Filtrovanie

Vo funkcii `wdir()` môžete použiť premenné na filtrovanie súborov vo vytvorených zoznamoch.
Do zoznamov sa tak zaradia iba súbory, ktoré vyhovujú nastavenému filtru.

Štandardne sa filtre nastavujú ako jednoduché masky, kde hviezdička (`*`)
nahradzuje akýkoľvek textový reťazec a otáznik (`?)` jedno písmeno/znak.

Ak chcete zobraziť napr. iba PDF súbory obsuahnuté v `userfiles/`, použite:

    {{{wdir('', '*.pdf')}}}

Ak chcete zobraziť všetky súbory v `userfiles/`, ktoré začínajú s "Zmluva_", použite:

    {{{wdir('', 'Zmluva_*')}}}

Precíznejšie filtrovanie môžete docieliť, ak povolíte použitie `Filter` → `Regexp` v nastavení pluginu.
V takom prípade bude druhá premenná vo `wdir()` považovaná za štandardný výraz PERL.
Tento mód je však určený iba pre skúsených správcov stránok, ktorí ovládajú potrebnú syntax.
Viac v [PHP manual](https://www.php.net/manual/en/pcre.pattern.php).

## Obmedzenia

Wdir_XH zatiaľ neposkytruje všetky funkcie, ktoré poskytuje Wdir 03beta.
Niektoré z nich možno nebudú uvedené ani v budúcnosti
(napr. zobrazovanie vlastníka súboru, oprávnení a i.).

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/wdir_xh/issues)
or in the [CMSimple_XH Forum](https://cmsimpleforum.com/).

## Licencia

Wdir_XH je slobodný softvér: môžete ho šíriť a upravovať podľa ustanovení Všeobecnej
verejnej licencie GNU (GNU General Public Licence),
vydávanej nadáciou Free Software Foundation a to buď podľa 3.
verzie tejto Licencie, alebo (podľa vášho uváženia) ktorejkoľvek neskoršej verzie.

Wdir_XH je rozširovaný v nádeji, že bude užitočný, avšak *bez akejkoľvek záruky*.
Neposkytujú sa ani odvodené záruky *predajnosti* alebo *vhodnosti pre určitý účel*.
Ďalšie podrobnosti hľadajte vo Všeobecnej verejne licencii GNU.

Kópiu Všeobecnej verejnej licencie GNU ste mali dostať spolu s Wdir_XH.
Ak sa tak nestalo, nájdete ju tu: <http://www.gnu.org/licenses/>.

Copyright © Christoph M. Becker

Slovak translation © 2015 Dr. Martin Sereday<br>
Russian translation © 2015 Васильев Леонид Валерьевич

## Záruky

Ikona pluginu je od [Alexander Moore](https://www.famfamfam.com/).
Ďakujem za jej poskytnutie pod GPL.

Ikony súborov sú od [19eighty7](https://www.19eighty7.com/).
Ďakujem za ich poskytnutie pod liberálnou licenciou.

The sort icons are taken from
[Wikimedia Commons](https://commons.wikimedia.org/wiki/Category:Table_sort_icons).
Many thanks for publishing these icon under a liberal license.

Many thanks to the community at the [CMSimple_XH forum](https://www.cmsimpleforum.com/)
for tips, suggestions and testing.

Nakoniec, nemenej, veľka patrí [Petrovi Hartegovi](https://harteg.dk/),
otcovi CMSimple a všetkým vývojárom [CMSimple_XH](https://www.cmsimple-xh.org/),
bez pomoci ktorých by tento skvelý CMSimple_XH nikdy neexistoval.
