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
    private string $filter;
    /** @var array<string,string> */
    private array $conf;

    /** @param array<string,string> $conf */
    public function __construct(string $path, string $filter, array $conf)
    {
        $this->path = $path;
        $this->filter = $filter;
        $this->conf = $conf;
    }

    /** @return list<File> */
    public function getFiles(): array
    {
        $files = [];
        $paths = $this->getFilePaths();
        foreach ($paths as $path) {
            $files[] = new File($path);
        }
        return $files;
    }

    /** @return list<string> */
    private function getFilePaths(): array
    {
        $files = [];
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

    private function isAllowedFile(string $filename): bool
    {
        return (!$this->filter || $this->matchesFilter(basename($filename)))
            && is_file($filename);
    }

    private function matchesFilter(string $basename): bool
    {
        if ($this->conf["filter_regexp"]) {
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
    private function matchesSimpleFilter(string $filter, string $string): bool
    {
        $pattern = strtr(
            preg_quote($filter, '/'),
            [
                '\\*' => '.*',
                '\\?' => '.'
            ]
        );
        return (bool) preg_match('/^' . $pattern . '$/', $string);
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
