<?php
/**
 * Represents as a abstract factory to parse novel pages. THIS CLASS CANNOT BE
 * INSTANTIATED.
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

namespace CCNR\Model;

abstract class Analyzer
{
    /**
     * CONSTRUCT FUNCTION
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * Made invisible to prevent instantiation.
     */
    final protected function __construct()
    {
    }

    /**
     * Parses the novel page from the URL.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  string $url
     * @return Page
     */
    final public static function parse($url)
    {
        settype($url, 'string');
        $s_host = parse_url($url, PHP_URL_HOST);
        if (!$s_host)
            throw new IllegalUrlException;
        $s_host = implode('_', array_slice(explode('.', $s_host), -2));
        if (is_numeric($s_host[0]))
            $s_host = '_' . $s_host;
        $s_ctoc = 'CCNR\\Model\\' . $s_host . '\\TOC';
        $s_cchp = 'CCNR\\Model\\' . $s_host . '\\Chapter';
        if (!class_exists($s_ctoc))
            throw new UnsupportedSourceException;
        if (call_user_func(array($s_ctoc, 'validate'), $url))
            return new $s_ctoc($url);
        else
            return new $s_cchp($url);
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
