<?php
/**
 * Represents as a novel TOC page in `luoqiu.com'.
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

namespace CCNR\Model\Luoqiu_com;

use CCNR\Model;

class TOC extends Model\TOC
{
    /**
     * Defines the matched URL pattern.
     *
     * INHERITED from {@link Model\Page::PATTERN}.
     *
     * @var string
     */
    const PATTERN = '~^http://www\.luoqiu\.com/html/\d+/\d+/(index\.html)?$~';

    /**
     * Parses retrieved content into meta-data.
     *
     * OVERRIDEN FROM {@link Model\Page::parse()}.
     *
     * @param  string  $content
     * @return TOC
     */
    protected function parse($content)
    {
        settype($content, 'string');
        $content = iconv('gbk', 'utf-8//ignore', $content);
        $s_ret = $this->crop('@<h1>@', '@最新章节</h1>@', $content);
        if (false === $s_ret)
            throw new NovelTitleNotFoundException;
        $this->title = $s_ret;
        $s_ret = $this->crop('@<span class="author">作者：@', '@</span>@', $content);
        if (false === $s_ret)
            throw new AuthorNotFoundException;
        $this->author = $s_ret;
        $s_ret = $this->crop('@<div class="booklist clearfix">@', '@</div><!--booklist-->@', $content);
        if (false === $s_ret ||
            false === preg_match_all('@<span><a href="(\d+\.html)">(.*)</a></span>@U', $s_ret, $a_tmp)
        )
            throw new ChaptersListingNotFoundException;
        $this->chapters = array();
        for ($ii = 0, $jj = count($a_tmp[1]); $ii < $jj; $ii++)
        {
            $this->chapters[$a_tmp[1][$ii]] = $a_tmp[2][$ii];
        }
        if (empty($this->chapters))
            throw new ChaptersListingNotFoundException;
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
