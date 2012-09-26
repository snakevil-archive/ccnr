<?php
/**
 * Represents as a novel TOC page. THIS CLASS CANNOT BE INSTANTIATED.
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

namespace NrModel;

use Exception;

abstract class TOC extends Page
{
    /**
     * Retrieves the author.
     *
     * @var string
     */
    protected $author;

    /**
     * Retrieves the link URLs of chapters in associated array.
     *
     * @var array
     */
    protected $chapters;

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
        if ('author' == $prop)
            return $this->author;
        else if ('chapters' == $prop)
            return $this->chapters;
        return parent::__get($prop);
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
