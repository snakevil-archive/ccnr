<?php
/**
 * Represents as a novel TOC page in `tianyibook.com'.
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

namespace NrModel\Tianyibook_com;

use Exception;
use NrModel;

class TOC extends NrModel\TOC
{
    /**
     * Defines the matched URL pattern.
     *
     * INHERITED from {@link NrModel\Page::PATTERN}.
     *
     * @var string
     */
    const PATTERN = '~^http://www\.tianyibook\.com/tianyibook/\d+/\d+/(index\.html)?$~';

    /**
     * Parses retrieved content into meta-data.
     *
     * OVERRIDEN FROM {@link NrModel\Page::parse()}.
     *
     * @param  string  $content
     * @return TOC
     */
    protected function parse($content)
    {
        settype($content, 'string');
        $content = iconv('gbk', 'utf-8//ignore', $content);
        $s_ret = $this->crop('@<div id="box"><h1>@', '@</h1></div>@', $content);
        if (false === $s_ret)
            return $this;
        $this->title = $s_ret;
        $s_ret = $this->crop('@小说系列(&nbsp;\s*)+@', '@/著(&nbsp;\s*)+@', $content);
        if (false === $s_ret)
            return $this;
        $this->author = $s_ret;
        $s_ret = $this->crop('@<td colspan="4" class="vcss">\s*章节目录\s*</td>@', '@<div id="box"><h2>Tags：<a href="@', $content);
        if (false === $s_ret ||
            false === preg_match_all('@<td class="ccss">\s*<a href="(\d+\.html)">(.*)</a>\s*</td>@U', $s_ret, $a_tmp)
        )
            return $this;
        $this->chapters = array();
        for ($ii = 0, $jj = count($a_tmp[1]); $ii < $jj; $ii++)
        {
            $this->chapters[$a_tmp[1][$ii]] = $a_tmp[2][$ii];
        }
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120: