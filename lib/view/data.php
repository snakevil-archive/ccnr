<?php
/**
 * Represents as asbtract API data view. THIS CLASS CANNOT BE INSTANTIATED.
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

abstract class Data extends NrView
{
    /**
     * Stores the meta-data.
     *
     * @var mixed
     */
    protected $meta;

    /**
     * CONSTRUCT FUNCTION
     *
     * OVERRIDEN FROM {@link NrView::__construct()}.
     *
     * @param string $uri
     * @param mixed  $meta
     */
    public function __construct($uri, $meta)
    {
        parent::__construct($uri);
        $this->meta = $meta;
    }

    /**
     * Serializes for JSON coding.
     *
     * THIS METHOD MUST BE OVERRIDEN.
     *
     * @return array
     */
    abstract function jsonSerialize();

    /**
     * Implements magic method.
     *
     * IMPLEMENTED FROM {@link NrView::__toString()}.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @return string
     */
    final public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
