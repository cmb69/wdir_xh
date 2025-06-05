<?php

/**
 * The domain layer.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Wdir
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Wdir_XH
 */

namespace Wdir;

/**
 * The folders.
 *
 * @category CMSimple_XH
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class Folder
{
    protected string $path;

    protected string $filter;

    public function __construct(string $path, string $filter)
    {
        $this->path = $path;
        $this->filter = $filter;
    }

    /** @return list<File> */
    public function getFiles(): array
    {
        $files = array();
        $paths = $this->getFilePaths();
        foreach ($paths as $path) {
            $files[] = new File($path);
        }
        return $this->sortFiles($files);
    }

    /** @return list<string> */
    protected function getFilePaths(): array
    {
        $files = array();
        if ($dir = opendir($this->path)) {
            while (($entry = readdir($dir)) !== false) {
                $path = $this->path . $entry;
                if ($this->isAllowedFile($path)) {
                    $files[] = $path;
                }
            }
            closedir($dir);
        }
        return $files;
    }

    protected function isAllowedFile(string $filename): bool
    {
        return (!$this->filter || $this->matchesFilter(basename($filename)))
            && is_file($filename);
    }

    protected function matchesFilter(string $basename): bool
    {
        global $plugin_cf;

        if ($plugin_cf['wdir']['filter_regexp']) {
            return (bool) preg_match($this->filter, $basename);
        } else {
            return $this->matchesSimpleFilter($this->filter, $basename);
        }
    }

    /**
     * Matches a string against a pattern in a simplyfied glob style.
     *
     * This is primarily a workaround for fnmatch() which might not be
     * available on all platforms. To have the same behavior everywhere, we're
     * using it throughout, though.
     */
    protected function matchesSimpleFilter(string $filter, string $string): bool
    {
        $pattern = strtr(
            preg_quote($filter, '/'),
            array(
                '\\*' => '.*',
                '\\?' => '.'
            )
        );
        return (bool) preg_match('/^' . $pattern . '$/', $string);
    }

    protected function sortFiles(array $files): array
    {
        global $plugin_cf;

        switch ($plugin_cf['wdir']['sort_column']) {
            case 'name':
                sort($files);
                break;
            case 'name/i':
                usort($files, 'strcasecmp');
                break;
            case 'size':
                usort($files, array($this, 'compareFilesBySize'));
                break;
            case 'date':
                usort($files, array($this, 'compareFilesByTime'));
                break;
        }
        if (!$plugin_cf['wdir']['sort_ascending']) {
            $files = array_reverse($files);
        }
        return $files;
    }

    protected function compareFilesBySize(File $a, File $b): int
    {
        return $a->getSize() - $b->getSize();
    }

    protected function compareFilesByTime(File $a, File $b): int
    {
        return $a->getModificationTime() - $b->getModificationTime();
    }
}
