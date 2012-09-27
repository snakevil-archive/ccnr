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
 * @copyright © 2012 snakevil.in
 * @license   http://www.gnu.org/licenses/gpl.html
 */

namespace NrView;

use Exception;
use NrView;

class Assistant extends NrView
{
    /**
     * Stores the tip message to be shown.
     *
     * @var string
     */
    protected $tip;

    /**
     * Stores the related URL.
     *
     * @var string
     */
    protected $url;

    /**
     * CONSTRUCT FUNCTION
     *
     * @param string $url
     * @param string $tip OPTIONAL.
     */
    public function __construct($url, $tip = '')
    {
        settype($url, 'string');
        settype($tip, 'string');
        $this->url = $url;
        $this->tip = $tip;
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
        $a_tmp = count_chars($this->url, 1);
        $s_pshare = (isset($a_tmp[47]) ? str_repeat('../', $a_tmp[47]) : '') . 'share/';
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>Clean & Clear Novel Reader</title>
<link rel="stylesheet" media="screen" href="{$s_pshare}screen.css" />
<link rel="icon" href="{$s_pshare}ccnr.ico" type="image/x-icon" />
<link rel="shortcut icon" href="{$s_pshare}ccnr.ico" type="image/x-icon" />
</head>
<body>
<dl>
<dd>
<form action="?">
<input name="s" type="text" />
<input type="submit" value="Read" />
</form>
</dd>
<dt>{$this->tip}</dt>
</dl>
</body>
</html>
HTML;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
