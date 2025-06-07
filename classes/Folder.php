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

class Folder
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /** @return Collection<File> */
    public function getFiles(): Collection
    {
        $files = [];
        if ($dir = opendir($this->path)) {
            while (($entry = readdir($dir)) !== false) {
                $filename = $this->path . $entry;
                if (is_file($filename)) {
                    $files[] = new File($filename);
                }
            }
            closedir($dir);
        }
        /** @var Collection<File> */
        $collection = Collection::of($files);
        return $collection;
    }
}
