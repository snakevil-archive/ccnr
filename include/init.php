<?php
/**
 * Initializes the RUNTIME ENVIRONMENT.
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

spl_autoload_register(function($class)
    {
        settype($class, 'string');
        $s_class = strtolower($class);
        if ('ccnr\\' != substr($s_class, 0, 5))
            return;
        $s_fpath = __DIR__ . '/../lib/' . str_replace(array('\\', '_'), array('/', '.'), substr($s_class, 5)) . '.php';
        if (is_file($s_fpath) && is_readable($s_fpath))
            require_once $s_fpath;
    });

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
