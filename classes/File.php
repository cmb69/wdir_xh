<?php

/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Wdir_XH.
 *
 * Wdir_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Wdir_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Wdir_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Wdir;

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
