# Wdir_XH

Wdir_XH facilitates displaying directory listings on your website, so you can
easily make available a bunch of files for download.
Wdir_XH is meant to be a successor to the popular Wdir by Joachim Barthel,
which is out of development since a long time.
Unfortunately, the license of Wdir does not allow modifications,
so Wdir_XH was rewritten from scratch.

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Settings](#settings)
- [Usage](#usage)
  - [Filtering](#filtering)
- [Limitations](#limitations)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Wdir_XH is a plugin for [CMSimple_XH](https://cmsimple-xh.org/).
It requires CMSimple_XH ≥ 1.8 and PHP ≥ 7.4.0.
Wdir_XH also requires [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.10;
if that is not already installed (see `Settings` → `Info`),
get the [lastest release](https://github.com/cmb69/plib_xh/releases/latest),
and install it.

## Download

The [lastest release](https://github.com/cmb69/wdir_xh/releases/latest)
is available for download on Github.

## Installation

The installation is done as with many other CMSimple_XH plugins.

1. Backup the data on your server.
1. Unzip the distribution on your computer.
1. Upload the whole folder `wdir/` to your server into the `plugins/` folder of
   CMSimple_XH.
1. Set write permissions for the subfolder `css/`, `config/` and
   `languages/`.
<!--
1. Check under `Plugins` → `Wdir` in the back-end of the website
   that all requirements are fulfilled.
-->

## Settings

The configuration of the plugin is done as with many other CMSimple_XH plugins in
the back-end of the website. Go to `Plugins` → `Wdir`.

You can change the default settings of Wdir_XH under `Config`.  Hints for the
options will be displayed when hovering over the help icon with your mouse.

Localization is done under `Language`.  You can translate the character
strings to your own language (if there is no appropriate language file
available), or customize them according to your needs.

The look of Wdir_XH can be customized under `Stylesheet`.

## Usage

Wdir_XH allows to display files in the userfiles folder (`userfiles/` by
default).

To display a directory listing on a page, use:

    {{{wdir('PATH')}}}

where `PATH` is a subfolder of the userfiles folder.  If you want to display the
contents of `userfiles/downloads/`, for instance, you write:

    {{{wdir('downloads')}}}

To display the contents of the `userfiles/` folder, use:

    {{{wdir('')}}}

The filenames in the list are linked to the files, so visitors are able to
browse to the files; depending on the server and the browser settings some file
types can be viewed in the browser, while others are offered to download.

Wdir_XH does not display any subfolders of the folder given as argument to
the function, so visitors cannot traverse the directories.  If you want visitors
to be able to access some of the subfolders, you have to place multiple calls to
`wdir()` on a page (or different pages).

### Filtering

You can pass a second argument to `wdir()` to filter the files; i.e. only
filenames matching the filter will be displayed.

In the default mode the filter expressions are simple wildcard patterns,
where an asterisk (`*`) is a wildcard for any number of characters and a question
mark (`?`) is a wildcard for a single character.

So if you want to display only PDF files in the userfiles folder, use:

    {{{wdir('', '*.pdf')}}}

If you want to display all files in the userfiles folder which start with
"Contract_", write:

    {{{wdir('', 'Contract_*')}}}

A much more powerful way of filtering can be enabled by activating the
configuration option `Filter` → `Regexp`.  Then the second argument to `wdir()` is
treated as a PERL compatible regular expression.  This mode should only be used
by advanced webmasters, who can look up the syntax in the
[PHP manual](https://www.php.net/manual/en/pcre.pattern.php).

## Limitations

Wdir_XH does not yet have all the options offered by Wdir 03beta, and a few
will probably never be implemented (such as the display of the file owner and
permissions).

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/wdir_xh/issues)
or in the [CMSimple_XH Forum](https://cmsimpleforum.com/).

## License

Wdir_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Wdir_XH is distributed in the hope that it will be useful,
but *without any warranty*; without even the implied warranty of
*merchantibility* or *fitness for a particular purpose*. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Wdir_XH.  If not, see <https://www.gnu.org/licenses/>.

Copyright © Christoph M. Becker

Slovak translation © 2015 Dr. Martin Sereday<br>
Russian translation © 2015 Васильев Леонид Валерьевич

## Credits

The plugin icon is designed by [Alexander Moore](https://www.famfamfam.com/).
Many thanks for publishing the icon under GPL.

The file icons are designed by [19eighty7](https://www.19eighty7.com/).
Many thanks for releasing them under a liberal license.

The sort icons are taken from
[Wikimedia Commons](https://commons.wikimedia.org/wiki/Category:Table_sort_icons).
Many thanks for publishing these icon under a liberal license.

Many thanks to the community at the [CMSimple_XH forum](https://www.cmsimpleforum.com/)
for tips, suggestions and testing.

And last but not least many thanks to [Peter Harteg](https://www.harteg.dk),
the “father” of CMSimple,
and all developers of [CMSimple_XH](https://www.cmsimple-xh.org)
without whom this amazing CMS would not exist.
