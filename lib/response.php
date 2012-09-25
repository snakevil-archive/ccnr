<?php
/**
 * Represents as HTTP response.
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

class NrResponse
{
    const STATUS_100 = '100 Continue';

    const STATUS_101 = '101 Switching Protocols';

    const STATUS_200 = '200 OK';

    const STATUS_201 = '201 Created';

    const STATUS_202 = '202 Accepted';

    const STATUS_203 = '203 Non-Authoritative Information';

    const STATUS_204 = '204 No Content';

    const STATUS_205 = '205 Reset Content';

    const STATUS_206 = '206 Partial Content';

    const STATUS_300 = '300 Multiple Choices';

    const STATUS_301 = '301 Moved Permanently';

    const STATUS_302 = '302 Found';

    const STATUS_303 = '303 See Other';

    const STATUS_304 = '304 Not Modified';

    const STATUS_305 = '305 Use Proxy';

    const STATUS_307 = '307 Temporary Redirect';

    const STATUS_400 = '400 Bad Request';

    const STATUS_401 = '401 Unauthorized';

    const STATUS_402 = '402 Payment Required';

    const STATUS_403 = '403 Forbidden';

    const STATUS_404 = '404 Not Found';

    const STATUS_405 = '405 Method Not Allowed';

    const STATUS_406 = '406 Not Acceptable';

    const STATUS_407 = '407 Proxy Authentication Required';

    const STATUS_408 = '408 Request Timeout';

    const STATUS_409 = '409 Conflict';

    const STATUS_410 = '410 Gone';

    const STATUS_411 = '411 Length Required';

    const STATUS_412 = '412 Precondition Failed';

    const STATUS_413 = '413 Request Entity Too Large';

    const STATUS_414 = '414 Request-URI Too Long';

    const STATUS_415 = '415 Unsupported Media Type';

    const STATUS_416 = '416 Requested Range Not Satisfiable';

    const STATUS_417 = '417 Expectation Failed';

    const STATUS_500 = '500 Internal Server Error';

    const STATUS_501 = '501 Not Implemented';

    const STATUS_502 = '502 Bad Gateway';

    const STATUS_503 = '503 Service Unavailable';

    const STATUS_504 = '504 Gateway Timeout';

    const STATUS_505 = '505 HTTP Version Not Supported';

    /**
     * Stores the buffered content blob to be outputed.
     *
     * @var string
     */
    protected $buffer;

    /**
     * Stores the only instance.
     *
     * @var NrResponse
     */
    protected static $instance;

    /**
     * Flushes all buffered content blob and exit.
     *
     * @return void
     */
    public function close()
    {
        echo $this->buffer;
        exit(0);
    }

    /**
     * CONSTRUCT FUNCTION
     */
    protected function __construct()
    {
        $this->buffer = '';
    }

    /**
     * Halts all response content with specified status code.
     *
     * @param  int  $status
     * @return void
     */
    public function halt($status)
    {
        settype($status, 'int');
        $s_const = __CLASS__ . '::STATUS_' . $status;
        if (200 != $status && defined($s_const))
        {
            header('Status: ' . constant($s_const), true, $status);
            echo '<h1>' . constant($s_const) . '</h1>';
            exit(0);
        }
        header('Status: ' . static::STATUS_500, true, 500);
        exit(0);
        echo '<h1>' . static::STATUS_500 . '</h1>';
    }

    /**
     * Retrieves the only instance.
     *
     * @return NrResponse
     */
    public static function singleton()
    {
        if (!static::$instance instanceof static)
            static::$instance = new static;
        return static::$instance;
    }

    /**
     * Writes content blob into the output buffer.
     *
     * @param  string
     * @return NrResponse
     */
    public function write($blob)
    {
        settype($blob, 'string');
        $this->buffer .= $blob;
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
