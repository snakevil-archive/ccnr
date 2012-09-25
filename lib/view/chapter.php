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
use NrView;

class Chapter extends NrView
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
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>{$this->page->novelTitle} - {$this->page->title}</title>
<script language="Javascript">
function navpage(ev) {
    ev = ev || window.event;
    switch (ev.keyCode) {
        case 13:
            location.href = "{$this->page->tocLink}";
            break;
        case 37:
            if ("{$this->page->prevLink}".length)
                location.href = "{$this->page->prevLink}";
            break;
        case 39:
            if ("{$this->page->nextLink}".length)
                location.href = "{$this->page->nextLink}";
            break;
    }
}
</script>
</head>
<body onkeyup="navpage(arguments[0])">
<nav>
<a href="{$this->page->prevLink}">Prev</a>
<a href="{$this->page->tocLink}">{$this->page->title}</a>
<a href="{$this->page->url}" target="_blank">#</a>
<a href="{$this->page->nextLink}">Next</a>
</nav>
<article>
<p>{$s_paragraphs}</p>
</article>
</body>
</html>
HTML;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
