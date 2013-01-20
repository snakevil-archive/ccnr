<?php
/**
 * Represents as a novel page. THIS CLASS CANNOT BE INSTANTIATED.
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

abstract class Page
{
    /**
     * Defines the matched URL pattern.
     *
     * @var string
     */
    const PATTERN = '~^$~';

    /**
     * Retrieves the title.
     *
     * @var string
     */
    protected $title;

    /**
     * Retrieves the modified time as an UNIX timestamp.
     *
     * @var int
     */
    protected $modifiedTime;

    /**
     * Stores the URL.
     *
     * @var string
     */
    protected $url;

    /**
     * Clears chapter title.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  string $title
     * @return string
     */
    final protected function clearChapterTitle($title)
    {
        settype($title, 'string');
        return trim(preg_replace(array('@[\\[\\(（【].*[\\]\\)）】]?$@u'
                ), '', $title));
    }

    /**
     * CONSTRUCT FUNCTION
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param string $url
     */
    final public function __construct($url)
    {
        settype($url, 'string');
        if (!static::validate($url))
            throw new DismatchedPageException;
        $this->url = $url;
        $this->parse($this->read($url));
    }

    /**
     * Crops the content with 2 RegExp patterns as delimiters.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  string $leftPattern
     * @param  string $rightPattern
     * @param  string $content IN-OUT.
     * @return string FALSE would be returned when error occured.
     */
    final protected function crop($leftPattern, $rightPattern, &$content)
    {
        settype($leftPattern, 'string');
        settype($rightPattern, 'string');
        settype($content, 'string');
        $a_ret1 = preg_split($leftPattern, $content, 2);
        if (1 == count($a_ret1))
            return false;
        $a_ret2 = preg_split($rightPattern, $a_ret1[1], 2);
        if (1 == count($a_ret2))
            return false;
        $content = $a_ret2[1];
        return trim($a_ret2[0]);
    }

    /**
     * Implements magic method.
     *
     * @param  string $prop
     * @return mixed
     * @ignore
     */
    public function __get($prop)
    {
        settype($prop, 'string');
        if ('title' == $prop)
            return $this->title;
        else if ('modifiedTime' == $prop)
            return $this->modifiedTime;
        else if ('url' == $prop)
            return $this->url;
        return;
    }

    /**
     * Parses retrieved content into meta-data.
     *
     * THIS METHOD MUST BE OVERRIDEN.
     *
     * @param  string $content
     * @return Page
     */
    abstract protected function parse($content);

    /**
     * Reads page content.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  string $url
     * @return string
     */
    final protected function read($url)
    {
        $r_page = curl_init($url);
        $b_ret = is_resource($r_page);
        if (!$b_ret)
            throw new RemoteHttpReaderException(array('reason' => 'Initialization failed.'));
        curl_setopt_array($r_page, array(CURLOPT_FILETIME => true,
                CURLOPT_FORBID_REUSE => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'gzip,deflate',
                CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.19 (KHTML, like Gecko) ' .
                    'Ubuntu/11.10 Chromium/18.0.1025.168 Chrome/18.0.1025.168 Safari/535.19'
            ));
        $s_page = curl_exec($r_page);
        if (false === $s_page)
            throw new RemoteHttpReaderException(array('reason' => ucfirst(curl_error($r_page)) . '.'));
        $this->modifiedTime = curl_getinfo($r_page, CURLINFO_FILETIME);
        curl_close($r_page);
        return $s_page;
   }

    /**
     * Validates whether the content of URL is a legal novel page.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  string $url
     * @return bool
     */
    final public static function validate($url)
    {
        settype($url, 'string');
        return (bool) preg_match(static::PATTERN, $url);
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
