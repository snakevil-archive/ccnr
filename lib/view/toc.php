<?php
/**
 * Represents as the novel TOC page view.
 *
 * This file is part of NOVEL.READER.
 *
 * NOVEL.READER is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * NOVEL.READER is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NOVEL.READER.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   novel.reader
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2012-2013 szen.in
 * @license   http://www.gnu.org/licenses/gpl.html
 */

namespace CCNR\View;

use CCNR\Model;

class TOC extends Page
{
    /**
     * CONSTRUCT FUNCTION
     *
     * OVERRIDEN FROM {@link CCNR\View::__construct()}.
     *
     * @param string      $uri
     * @param Model\TOC $toc
     */
    public function __construct($uri, Model\TOC $toc)
    {
        parent::__construct($uri, $toc);
    }

    /**
     * Implements magic method.
     *
     * IMPLEMENTED FROM {@link CCNR\View::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        $s_chapters = implode("\n", array_map(function($volume, $chapters)
                {
                    return "<dt>{$volume}</dt>\n" . implode("\n", array_map(function($url, $title)
                            {
                                return '<dd><a href="' . $url . '">' . $title . '</a></dd>';
                            }, array_keys($chapters), array_values($chapters)));
                }, array_keys($this->page->chapters), array_values($this->page->chapters)));
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>{$this->page->title} * CCNR</title>
<link rel="stylesheet" media="screen" href="{$this->uri}share/screen.css" />
<link rel="icon" href="{$this->uri}share/ccnr.ico" type="image/x-icon" />
<link rel="shortcut icon" href="{$this->uri}share/ccnr.ico" type="image/x-icon" />
</head>
<body>
<h1><span>{$this->page->title}</span><small>by {$this->page->author}</small></h1>
<dl>
{$s_chapters}
</dl>
</body>
</html>
HTML;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
