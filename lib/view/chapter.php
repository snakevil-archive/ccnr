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
    x : "{$this->page->tocLink}",
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
            location.href = _.x;
            break;
        case 37:
            if (document.getElementById("prevLink"))
                document.getElementById("prevLink").click();
            else if (_.s.p.length)
                location.href = _.s.p;
            break;
        case 39:
            if (document.getElementById("nextLink"))
                document.getElementById("nextLink").click();
            else if (_.s.n.length)
                location.href = _.s.n;
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
                var i = x, x = _.c(y, x), y = i, i = new XMLHttpRequest;
                i.onload = function () {
                    try {
                        var j = JSON.parse(i.responseText);
                        if (200 != j.code || x != j.referer) {
                            document.getElementById("nextLink").href = "#" + y;
                            return;
                        }
                        _.t = {
                            t : [
                                j.data.novelTitle + " - " + j.data.title + " * CCNR",
                                j.data.title
                            ],
                            b : "",
                            p : j.data.links.previous,
                            n : j.data.links.next,
                            r : x,
                            i : y
                        };
                        var k = [];
                        for (var l = 0; l < j.data.paragraphs.length; l++)
                            if ("![IMAGE](" == j.data.paragraphs[l].substr(0, 9)) {
                                k[k.length] = j.data.paragraphs[l].substring(9, j.data.paragraphs[l].length - 1);
                                _.t.b += "<p><img src=\"" + k[k.length - 1] + "\"/></p>";
                            } else
                                _.t.b += "<p>" + j.data.paragraphs[l] + "</p>";
                        document.body.appendChild(document.createElement("fieldset")).
                            appendChild(document.createElement("img")).
                            src = k[0];
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
                if (_.d.l)
                    return;
                _.d.l = _.t.i;
                var t = _.t;
                history.pushState(t, t.t[0], t.i);
                _.d(t);
                delete _.d.l;
                delete _.t;
                _.g(t.n, t.r);
            },
            window.onpopstate = function (ev) {
                if (!_.v) {
                    _.v = true;
                    return;
                }
                _.d(null == ev.state ? _.s : ev.state);
            };
            if (_.s.n.length && '#' != _.s.n[0])
                _.g(_.s.n, _.s.r);
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
