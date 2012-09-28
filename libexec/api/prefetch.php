<?php
/**
 * Provides filtered novel chapters data.
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

require_once __DIR__ . '/../../include/init.php';

$o_resp = NrResponse::singleton();

if (!isset($_GET['s']) || !is_string($_GET['s']) || !strlen($_GET['s']))
    $o_resp->halt(400, new NrView\API('', 400));

try
{
    $o_chapter = NrModel\Analyzer::parse($_GET['s']);

    if ($o_chapter instanceof NrModel\TOC)
        $o_resp->halt(400, new NrView\API($_GET['s'], 400));
    $o_data = new NrView\API\Chapter($_GET['s'], $o_chapter);
}
catch (Exception $ex)
{
    $o_resp->halt(504, new NrView\API($_GET['s'], 504));
}

$o_resp->write(new NrView\API($_GET['s'], 200, $o_data))->close();

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
