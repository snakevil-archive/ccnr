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
 * @copyright © 2012 szen.in
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
     * @param string      $uri
     * @param NrModel\Chapter $chapter
     */
    public function __construct($uri, NrModel\Chapter $chapter)
    {
        parent::__construct($uri, $chapter);
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
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>{$this->page->novelTitle} - {$this->page->title} * CCNR</title>
<link rel="stylesheet" media="screen" href="{$this->uri}share/screen.css" />
<link rel="icon" href="{$this->uri}share/ccnr.ico" type="image/x-icon" />
<link rel="shortcut icon" href="{$this->uri}share/ccnr.ico" type="image/x-icon" />
<script>
_={\$:function(x){return document.getElementById(x)},a:"{$this->uri}api/prefetch?s=",x:"{$this->page->tocLink}",p:"{$this->page->prevLink}",n:"{$this->page->nextLink}",r:"{$this->page->url}"};
function navpage(ev){ev=ev||window.event;switch(ev.keyCode){
    case 13:location.href=_.x;break;
    case 37:if(_.$("prevLink"))_.$("prevLink").click();else if(_.p.length)location.href=_.p;break;
    case 39:if(_.$("nextLink"))_.$("nextLink").click();else if(_.n.length)location.href=_.n;break;
}}
</script>
</head>
<body onkeyup="navpage(arguments[0])">
<h2>
<a id="tocLink" href="{$this->page->tocLink}">{$this->page->title}</a>
</h2>
<blockquote cite="{$this->page->url}">
<p>{$s_paragraphs}</p>
</blockquote>
<ul>
<li><a id="prevLink" href="{$this->page->prevLink}">上一章</a></li>
<li class="nextLink"><a id="nextLink" href="{$this->page->nextLink}">下一章</a></li>
</ul>
<script type="text/javascript" src="{$this->uri}share/prefetch.js"></script>
<noscript><fieldset><img src="{$this->page->nextLink}" /></fieldset></noscript>
</body>
</html>
HTML;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
