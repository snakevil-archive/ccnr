<?php
/**
 * Represents as the novel chapter page view.
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
 * @copyright © 2012 snakevil.in
 * @license   http://www.gnu.org/licenses/gpl.html
 */

namespace NrView;

use Exception;
use NrModel;

class Chapter extends Page
{
    /**
     * CONSTRUCT FUNCTION
     *
     * OVERRIDEN FROM {@link NrView::__construct()}.
     *
     * @param NrModel\Chapter $chapter
     */
    public function __construct(NrModel\Chapter $chapter)
    {
        $this->page = $chapter;
    }

    /**
     * Implements magic method.
     *
     * IMPLEMENTED FROM {@link NrView::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        $s_paragraphs = implode("</p>\n<p>", $this->page->paragraphs);
        if (false !== strpos($s_paragraphs, '![IMAGE]('))
            $s_paragraphs = preg_replace('@!\[IMAGE\]\((\S+)\)@U', '<img src="$1" />', $s_paragraphs);
        $a_tmp = count_chars($this->page->url, 1);
        $s_pshare = (isset($a_tmp[47]) ? str_repeat('../', $a_tmp[47]) : '') . 'share/';
        $s_pshare = str_repeat('../', $a_tmp[47]) . 'share/';
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>{$this->page->novelTitle} - {$this->page->title} * CCNR</title>
<link rel="stylesheet" media="screen" href="{$s_pshare}screen.css" />
<link rel="icon" href="{$s_pshare}ccnr.ico" type="image/x-icon" />
<link rel="shortcut icon" href="{$s_pshare}ccnr.ico" type="image/x-icon" />
<script language="Javascript">
function navpage(ev) {
    ev = ev || window.event;
    switch (ev.keyCode) {
        case 13:location.href="{$this->page->tocLink}";break;
        case 37:if("{$this->page->prevLink}".length)location.href="{$this->page->prevLink}";break;
        case 39:if("{$this->page->nextLink}".length)location.href="{$this->page->nextLink}";break;
    }
}
</script>
</head>
<body onkeyup="navpage(arguments[0])">
<h2>
<a href="{$this->page->tocLink}">{$this->page->title}</a>
</h2>
<blockquote cite="{$this->page->url}">
<p>{$s_paragraphs}</p>
</blockquote>
<ul>
<li><a href="{$this->page->prevLink}">上一章</a></li>
<li class="nextLink"><a href="{$this->page->nextLink}">下一章</a></li>
</ul>
</body>
</html>
HTML;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
