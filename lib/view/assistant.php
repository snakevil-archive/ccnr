<?php
/**
 * Represents as the assistant view.
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

use CCNR\View;

class Assistant extends View
{
    /**
     * Stores the tip message to be shown.
     *
     * @var string
     */
    protected $tip;

    /**
     * CONSTRUCT FUNCTION
     *
     * OVERRIDEN FROM {@link View::__construct()}.
     *
     * @param string $uri
     * @param string $tip OPTIONAL.
     */
    public function __construct($uri, $tip = '')
    {
        parent::__construct($uri);
        settype($tip, 'string');
        $this->tip = $tip;
    }

    /**
     * Implements magic method.
     *
     * IMPLEMENTED FROM {@link View::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        $b_ssl = isset($_SERVER['HTTPS']) && 'on' == $_SERVER['HTTPS'];
        $s_url = ($b_ssl ? 'https' : 'http') .
            '://' . $_SERVER['HTTP_HOST'] .
            ($b_ssl ? (443 != $_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : '') :
                (80 != $_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : '')) .
            $this->uri;
        $s_tip = (strlen($this->tip) ? '[WARNING] ' : '') . $this->tip;
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>CCNR</title>
<link rel="stylesheet" media="screen" href="{$this->uri}share/screen.css" />
<link rel="icon" href="{$this->uri}share/ccnr.ico" type="image/x-icon" />
<link rel="shortcut icon" href="{$this->uri}share/ccnr.ico" type="image/x-icon" />
</head>
<body>
<ol>
<li><span>Bookmarklet</span><a href="javascript:void((function(x,y){document.close();x.href.indexOf(y)&&x.assign(y+x.href.substr(7))})(location,'{$s_url}'))">READ!</a><span>&#x2934;</span></li>
</ol>
<h3>Clean & Clear Novel Reader</h3>
<form action="?">
<input name="s" type="text" size="64" placeholder="Paste/Type URL here and Go to..." />
<input class="button" type="submit" value="READ!" />
</form>
<h4>{$s_tip}</h4>
</body>
</html>
HTML;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
