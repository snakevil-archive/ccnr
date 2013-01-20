<?php
/**
 * Provides essential behaviors to all derived exceptions. THIS CLASS
 * CANNOT BE INSTANTIATED.
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

namespace CCNR;

use Exception as Ex;

abstract class Exception extends Ex
{
    /**
     * Defines the message template.
     *
     * @var string
     */
    const MESSAGE = 'Unknown Exception';

    /**
     * Stores the context meta-data.
     *
     * @var array
     */
    protected $meta;

    /**
     * CONSTRUCT FUNCTION
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  Array|Ex $meta OPTIONAL.
     * @param  Ex       $previous OPTIONAL.
     */
    final public function __construct($meta = array(), Ex $previous = NULL)
    {
        if (is_array($meta))
            $this->meta = $meta;
        else
        {
            if ($meta instanceof Ex && NULL === $previous)
                $previous = $meta;
            $this->meta = array();
        }
        $s_msg = static::MESSAGE;
        $i_cnt = count($this->meta);
        if ($i_cnt)
        {
            $a_src = array_keys($this->meta);
            $a_dst = range(0, $i_cnt);
            for ($ii = 0; $ii < $i_cnt; $ii++)
            {
                $a_src[$ii] = '%' . $a_src[$ii] . '$';
                $a_dst[$ii] = '%' . $a_dst[$ii] . '$';
            }
            $s_msg = @vsprintf(str_replace($a_src, $a_dst, $s_msg), $this->meta);
            if (false === $s_msg)
                $s_msg = static::MESSAGE;
        }
        else
            $s_msg = static::MESSAGE;
        parent::__construct($s_msg, 0, $previous);
    }

    /** Retrieves the context meta-data.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @return array
     */
    final public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Implements magic method.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @return string
     */
    final public function __toString()
    {
        return $this->getMessage();
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
