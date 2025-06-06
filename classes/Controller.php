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

use Plib\Request;
use Plib\View;

class Controller
{
    private string $pluginFolder;
    private string $userfilesFolder;
    /** @var array<string,string> */
    private array $conf;
    private View $view;

    /** @param array<string,string> $conf */
    public function __construct(string $pluginFolder, string $userfilesFolder, array $conf, View $view)
    {
        $this->pluginFolder = $pluginFolder;
        $this->userfilesFolder = $userfilesFolder;
        $this->conf = $conf;
        $this->view = $view;
    }

    public function renderTable(Request $request, string $path, string $filter = ""): string
    {
        $path = $this->userfilesFolder . $path;
        if ($path[strlen($path) - 1] != '/') {
            $path .= '/';
        }
        return $this->render(new Folder($path, $filter, $request->language(), $this->conf));
    }

    private function render(Folder $folder): string
    {
        return $this->view->render("wdir", [
            "config" => $this->jsConf(),
            "script" => $this->pluginFolder . "wdir.js",
            "rows" => $this->rows($folder),
        ]);
    }

    /** @return array<string,mixed> */
    private function jsConf(): array
    {
        switch ($this->conf["sort_column"]) {
            default:
                $column = null;
                break;
            case "name":
                $column = "wdir_name";
                break;
            case "size":
                $column = "wdir_size";
                break;
            case "date":
                $column = "wdir_modified";
                break;
        }
        return [
            "column" => $column,
            "ascending" => $this->conf["sort_ascending"],
        ];
    }

    /** @return list<object{name:string,icon:string,path:string,size:int,rsize:string,mtime:int}> */
    private function rows(Folder $folder): array
    {
        $res = [];
        foreach ($folder->getFiles() as $file) {
            $res[] = $this->rowRecord($file);
        }
        return $res;
    }

    /** @return object{name:string,icon:string,path:string,size:int,rsize:string,mtime:int} */
    private function rowRecord(File $file)
    {
        return (object) [
            "name" => $file->name(),
            "icon" => $this->renderFileIcon($file),
            "path" => $file->path(),
            "size" => $file->size(),
            "rsize" => $this->renderFileSize($file),
            "mtime" => $file->mtime(),
        ];
    }

    private function renderFileSize(File $file): string
    {
        return ceil($file->size() / 1024) . ' KB';
    }

    /** @todo alt attribute! */
    private function renderFileIcon(File $file): string
    {
        $ext = $file->extension();
        $imageFolder = $this->pluginFolder . 'images/';
        $src = $imageFolder . 'file-' . $ext . '.png';
        if (file_exists($src)) {
            $alt = $this->view->text("format_type", strtoupper($ext));
        } else {
            $src = $imageFolder . 'file.png';
            $alt = $this->view->text("label_file");
        }
        return '<img src="' . $src . '" alt="' . $alt . '" title="' . $alt . '">';
    }
}
