<?php

/**
 * The domain layer.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Wdir
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
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
    private $_path;

    /**
     * Initializes a new instance.
     *
     * @param string $path A folder path.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->_path = (string) $path;
    }

    /**
     * Returns a list of files.
     *
     * @return array
     */
    public function getFiles()
    {
        $files = array();
        $paths = $this->_getFilePaths();
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
    private function _getFilePaths()
    {
        $files = array();
        if ($dir = opendir($this->_path)) {
            while (($entry = readdir($dir)) !== false) {
                $path = $this->_path . '/' . $entry;
                if (is_file($path)) {
                    $files[] = $path;
                }
            }
            closedir($dir);
        }
        natcasesort($files);
        return $files;
    }
}

/**
 * The files.
 *
 * @category CMSimple_XH
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class Wdir_File
{
    /**
     * The file path.
     *
     * @var string
     */
    private $_path;

    /**
     * Initializes a new instance.
     *
     * @param string $path A file path.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->_path = (string) $path;
    }

    /**
     * Returns the file path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Returns the file basename.
     *
     * @return string
     */
    public function getName()
    {
        return basename($this->_path);
    }

    /**
     * Returns the file extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->_path, PATHINFO_EXTENSION);
    }

    /**
     * Returns the file size.
     *
     * @return int
     */
    public function getSize()
    {
        return filesize($this->_path);
    }

    /**
     * Returns the timestamp of the last modification.
     *
     * @return int
     */
    public function getModificationTime()
    {
        return filemtime($this->_path);
    }
}

?>
