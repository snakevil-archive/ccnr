<?php
/**
 * Serves filtered novel chapters pages.
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

require_once __DIR__ . '/../include/init.php';

$o_resp = CCNR\Response::singleton();

if (!isset($_SERVER['QUERY_STRING']) || !strlen($_SERVER['QUERY_STRING']))
    $o_resp->halt(200, new CCNR\View\Assistant($_SERVER['REQUEST_URI']));

if (strpos($_SERVER['QUERY_STRING'], ':/'))
{
    $_SERVER['QUERY_STRING'] = str_replace(':/', '://', $_SERVER['QUERY_STRING']);
    $_SERVER['REQUEST_URI'] = str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
}
else
{
    $_SERVER['REQUEST_URI'] = str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
    $_SERVER['QUERY_STRING'] = 'http://' . $_SERVER['QUERY_STRING'];
}

try
{
    $o_chapter = CCNR\Model\Analyzer::parse($_SERVER['QUERY_STRING']);

    $o_page = $o_chapter instanceof CCNR\Model\TOC ?
        new CCNR\View\TOC($_SERVER['REQUEST_URI'], $o_chapter) :
        new CCNR\View\Chapter($_SERVER['REQUEST_URI'], $o_chapter);
}
catch (Exception $ex)
{
    $o_resp->halt(504, new CCNR\View\Assistant($_SERVER['REQUEST_URI'], $ex->getMessage()));
}

$o_resp->write($o_page)->close();

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
