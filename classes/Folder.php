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

/**
 * The folders.
 *
 * @category CMSimple_XH
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class Wdir_Folder
{
    /**
     * The folder path.
     *
     * @var string
     */
    protected $path;

    /**
     * The filter expression.
     *
     * @var string
     */
    protected $filter;

    /**
     * Initializes a new instance.
     *
     * @param string $path   A folder path.
     * @param string $filter A filter expression.
     *
     * @return void
     */
    public function __construct($path, $filter)
    {
        $this->path = $path;
        $this->filter = $filter;
    }

    /**
     * Returns a list of files.
     *
     * @return array
     */
    public function getFiles()
    {
        $files = array();
        $paths = $this->getFilePaths();
        foreach ($paths as $path) {
            $files[] = new Wdir_File($path);
        }
        return $this->sortFiles($files);
    }

    /**
     * Returns a list of filepaths.
     *
     * @return array
     */
    protected function getFilePaths()
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

    /**
     * Returns whether a filename is allowed for the listing.
     *
     * @param string $filename A filename.
     *
     * @return bool
     */
    protected function isAllowedFile($filename)
    {
        return (!$this->filter || $this->matchesFilter(basename($filename)))
            && is_file($filename);
    }

    /**
     * Returns whether a basename matches the filter.
     *
     * @param string $basename A basename.
     *
     * @return bool
     *
     * @global array The configuration of the plugins.
     */
    protected function matchesFilter($basename)
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
     *
     * @param string $filter A simplyfied glob pattern.
     * @param string $string A string to be matched.
     *
     * @return bool
     */
    protected function matchesSimpleFilter($filter, $string)
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
    /**
     * Sorts and returns the files according to the configuration.
     *
     * @param array $files An array of files.
     *
     * @return array
     */
    protected function sortFiles($files)
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

    /**
     * Compares two files by size and returns the result.
     *
     * @param Wdir_File $a A file.
     * @param Wdir_File $b Another file.
     *
     * @return int
     */
    protected function compareFilesBySize(Wdir_File $a, Wdir_File $b)
    {
        return $a->getSize() - $b->getSize();
    }

    /**
     * Compares two files by modification time and returns the result.
     *
     * @param Wdir_File $a A file.
     * @param Wdir_File $b Another file.
     *
     * @return int
     */
    protected function compareFilesByTime(Wdir_File $a, Wdir_File $b)
    {
        return $a->getModificationTime() - $b->getModificationTime();
    }
}

?>
