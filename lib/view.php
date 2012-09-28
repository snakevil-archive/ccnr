<?php
/**
 * Represents as abstract view. THIS CLASS CANNOT BE INSTANTIATED.
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

abstract class NrView
{
    /**
     * Stores the request URI.
     *
     * @var string
     */
    protected $uri;

    /**
     * CONSTRUCT FUNCTION
     *
     * @param  string $uri
     */
    public function __construct($uri)
    {
        settype($uri, 'string');
        $this->uri = array_shift(explode('?', $uri));
    }

    /**
     * Implements magic method.
     *
     * THIS METHOD MUST BE OVERRIDEN.
     *
     * @return string
     */
    abstract public function __toString();
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
