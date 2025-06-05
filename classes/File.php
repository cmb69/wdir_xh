<?php

/**
 * The files.
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
 * The files.
 *
 * @category CMSimple_XH
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class File
{
    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return basename($this->path);
    }

    public function getExtension(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function getSize(): int
    {
        return filesize($this->path);
    }

    public function getModificationTime(): int
    {
        return filemtime($this->path);
    }
}
