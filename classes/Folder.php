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
        $this->path = (string) $path;
        $this->filter = (string) $filter;
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
        return $files;
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
        sort($files);
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
            return fnmatch($this->filter, $basename);
        }
    }
}

?>
