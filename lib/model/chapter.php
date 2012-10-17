<?php
/**
 * Represents as a novel chapter. THIS CLASS CANNOT BE INSTANTIATED.
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

namespace NrModel;

use Exception;

abstract class Chapter extends Page
{
    /**
     * Retrieves the novel title.
     *
     * @var string
     */
    protected $novelTitle;

    /**
     * Retrieves paragraphs in array.
     *
     * @var array
     */
    protected $paragraphs;

    /**
     * Retrieves the link URL of previous chapter.
     *
     * @var string
     */
    protected $prevLink;

    /**
     * Retrieves the link URL of novel TOC page.
     *
     * @var string
     */
    protected $tocLink;

    /**
     * Retrieves the link URL of next chapter.
     *
     * @var string
     */
    protected $nextLink;

    /**
     * Implements magic method.
     *
     * OVERRIDEN FROM {@link Page::__get()}. THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  string $prop
     * @return mixed
     * @ignore
     */
    final public function __get($prop)
    {
        settype($prop, 'string');
        if ('novelTitle' == $prop)
            return $this->novelTitle;
        else if ('paragraphs' == $prop)
            return $this->paragraphs;
        else if ('prevLink' == $prop)
            return $this->prevLink;
        else if ('tocLink' == $prop)
            return $this->tocLink;
        else if ('nextLink' == $prop)
            return $this->nextLink;
        return parent::__get($prop);
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
