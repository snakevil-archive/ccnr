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
        $a_tmp = count_chars($this->page->url, 1);
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
_ = {
    v : false,
    l : location.href,
    t : "{$this->page->tocLink}",
    s : {
        t : [document.title, ""],
        b : "",
        p : "{$this->page->prevLink}",
        n : "{$this->page->nextLink}",
        r : "{$this->page->url}"
    }
};
function navpage(ev) {
    ev = ev || window.event;
    switch (ev.keyCode) {
        case 13:
            location.href = _.t;
            break;
        case 37:
            if (document.getElementById("prevLink"))
                document.getElementById("prevLink").click();
            else if (_.s.p.length) {
                console.log("prev");
                location.href = _.s.p;
            }
            break;
        case 39:
            if (document.getElementById("nextLink"))
                document.getElementById("nextLink").click();
            else if (_.s.n.length) {
                console.log("next");
                location.href = _.s.n;
            }
            break;
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
<script>
if (_.s.n.length) {
    if (history.pushState) {
        (function () {
            _.s.t[1] = document.getElementById("tocLink").textContent,
            _.s.b = document.getElementsByTagName("blockquote")[0].innerHTML,
            _.c = function (x, y) {
                var i = x.split("/"),
                    j = y.split("/");
                i.pop();
                for (var k = 0; k < j.length; k++)
                    if (".." == j[k] && 3 < i.length)
                        i.pop();
                    else
                        i.push(j[k]);
                return i.join("/");
            },
            _.d = function (x) {
                with (document) {
                    with (getElementsByTagName("blockquote")[0]) {
                        innerHTML = x.b;
                        setAttribute("cite", x.r);
                    }
                    title = x.t[0],
                    getElementById("tocLink").textContent = x.t[1],
                    getElementById("prevLink").href = x.p,
                    getElementById("nextLink").href = x.n;
                }
            },
            _.g = function (x, y) {
                var i = new XMLHttpRequest;
                i.onload = function () {
                    try {
                        var j = JSON.parse(i.responseText);
                        if (200 != j.code || x != j.referer)
                            return;
                        _.t = {
                            t : [
                                j.data.novelTitle + " - " + j.data.title + " * CCNR",
                                j.data.title
                            ],
                            b : "",
                            p : j.data.links.previous,
                            n : j.data.links.next,
                            r : j.referer
                        };
                        for (var k = 0; k < j.data.paragraphs.length; k++)
                            if ("![IMAGE](" == j.data.paragraphs[k].substr(0, 9))
                                _.t.b += "<p><img src=\"" + j.data.paragraphs[k].substr(9, -1) + "\"/></p>";
                            else
                                _.t.b += "<p>" + j.data.paragraphs[k] + "</p>";
                    } catch (ex) {
                    }
                };
                i.open("GET", "{$this->uri}api/prefetch?s="+x, true);
                i.send();
            },
            document.getElementById("nextLink").onclick = function (ev) {
                if (!_.t)
                    return;
                ev.preventDefault();
                history.pushState(_.t, _.t.t[0], _.t.n);
                _.d(_.t);
                _.g(_.c(_.t.r, _.t.n), _.c(_.l, _.t.n));
            },
            window.onpopstate = function (ev) {
                if (!_.v) {
                    _.v = true;
                    return;
                }
                _.d(null == ev.state ? _.s : ev.state);
            };
            _.g(_.c(_.s.r, _.s.n), _.c(_.l, _.s.n));
        })();
    } else {
        (function (x) {
            x.body.appendChild(x.createElement("fieldset")).appendChild(x.createElement("img")).src = _.n;
        })(document);
    }
}
</script>
<noscript><fieldset><img src="{$this->page->nextLink}" /></fieldset></noscript>
</body>
</html>
HTML;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
