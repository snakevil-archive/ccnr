<?php
/**
 * Represents as API data container view.
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

class API extends NrView
{
    /**
     * Stores the API data.
     *
     * @var Data
     */
    protected $data;

    /**
     * Stores the code.
     *
     * @var int
     */
    protected $code;

    /**
     * CONSTRUCT FUNCTION
     *
     * OVERRIDEN FROM {@link NrView::__construct()}.
     *
     * @param string $uri
     * @param int    $code OPTIONAL.
     * @param Data   $data OPTIONAL.
     */
    public function __construct($uri, $code = 200, Data $data = NULL)
    {
        parent::__construct($uri);
        settype($code, 'int');
        $this->code = $code;
        $this->data = $data;
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
        return json_encode(array('code' => $this->code,
                'referer' => $this->uri,
                'data' => $this->data instanceof Data ? $this->data->jsonSerialize() : NULL
            ));
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
