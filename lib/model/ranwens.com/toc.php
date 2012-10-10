<?php
/**
 * Represents as a novel TOC page in `ranwens.com'.
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

namespace NrModel\Ranwens_com;

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
    const PATTERN = '~^http://www\.ranwens\.com/files/article/html/\d+/\d+/index\.html$~';

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
        $s_ret = $this->crop('@var articlename=\'@', '@\';@', $content);
        if (false === $s_ret)
            return $this;
        $this->title = $s_ret;
        $s_ret = $this->crop('@var author=\'@', '@\';@', $content);
        if (false === $s_ret)
            return $this;
        $this->author = $s_ret;
        $s_ret = $this->crop('@<div id="defaulthtml4">@', '@</table>@', $content);
        if (false === $s_ret ||
            false === preg_match_all('@<td><div class="dccss"><a href="(\d+\.html)" alt=".*">(.*)</a></div></td>@U', $s_ret, $a_tmp)
        )
            return $this;
        $this->chapters = array();
        for ($ii = 0, $jj = count($a_tmp[1]); $ii < $jj; $ii++)
        {
            $this->chapters[$a_tmp[1][$ii]] = str_replace('-ranwens.com', '', $a_tmp[2][$ii]);
        }
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
