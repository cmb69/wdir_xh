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

use Collator;

class Folder
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /** @return list<File> */
    public function getFiles(): array
    {
        $files = [];
        if ($dir = opendir($this->path)) {
            while (($entry = readdir($dir)) !== false) {
                $files[] = new File($this->path . $entry);
            }
            closedir($dir);
        }
        return $files;
    }

    /**
     * @param list<File> $files
     * @return list<File>
     */
    public function filter(array $files, string $filter, bool $regex): array
    {
        if ($filter && !$regex) {
            $filter = $this->filterToPattern($filter);
        }
        $res = [];
        foreach ($files as $file) {
            if ($this->isAllowedFile($file->path(), $filter)) {
                $res[] = $file;
            }
        }
        return $res;
    }

    private function isAllowedFile(string $filename, string $filter): bool
    {
        return (!$filter || $this->matchesFilter(basename($filename), $filter))
            && is_file($filename);
    }

    private function matchesFilter(string $basename, string $filter): bool
    {
        return (bool) preg_match($filter, $basename);
    }

    private function filterToPattern(string $filter): string
    {
        return '/^' . strtr(
            preg_quote($filter, '/'),
            [
                '\\*' => '.*',
                '\\?' => '.'
            ]
        ) . '$/';
    }

    /**
     * @param list<File> $files
     * @return list<File>
     */
    public function sortFiles(array $files, string $field, string $locale, bool $ascending): array
    {
        switch ($field) {
            case "name":
                if (class_exists(Collator::class)) {
                    $collator = new Collator($locale);
                    $collator->setStrength(Collator::TERTIARY);
                    usort($files, fn (File $a, File $b) => (int) $collator->compare($a->name(), $b->name()));
                } else {
                    usort($files, fn (File $a, File $b) => strcmp($a->name(), $b->name()));
                }
                break;
            case "size":
                usort($files, fn (File $a, File $b) => $a->size() - $b->size());
                break;
            case "date":
                usort($files, fn (File $a, File $b) => $a->mtime() - $b->mtime());
                break;
        }
        if (!$ascending) {
            $files = array_reverse($files);
        }
        return $files;
    }
}
