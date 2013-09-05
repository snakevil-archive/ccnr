<?php
/**
 * Represents as a novel chapter in `17k.com'.
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

namespace CCNR\Model\_17k_com;

use CCNR\Model;

class Chapter extends Model\Chapter
{
    /**
     * Defines the matched URL pattern.
     *
     * INHERITED from {@link Model\Page::PATTERN}.
     *
     * @var string
     */
    const PATTERN = '~^http://www\.17k\.com/chapter/\d+/\d+\.html$~';

    /**
     * Parses retrieved content into meta-data.
     *
     * OVERRIDEN FROM {@link Model\Page::parse()}.
     *
     * @param  string  $content
     * @return Chapter
     */
    protected function parse($content)
    {
        settype($content, 'string');
        $s_ret = $this->crop('@var bookName = "@', '@"@', $content);
        if (false === $s_ret)
            throw new Model\NovelTitleNotFoundException;
        $this->novelTitle = $s_ret;
        $s_ret = $this->crop('@<h1 itemprop="headline">@', '@</h1>@', $content);
        if (false === $s_ret)
            throw new Model\ChapterTitleNotFoundException;
        $this->title = $this->clearChapterTitle($s_ret);
        $s_ret = $this->crop('@<div class="p" id="chapterContent">@', '@\s*(<!-- 作者有话说 -->\s*)?<div class="author-say-border"@', $content);
        if (false === $s_ret)
            throw new Model\ParagraphsNotFoundException;
        $this->paragraphs = array();
        $a_tmp = preg_split('@(<br/?>)+@U', $s_ret);
        for ($ii = 0, $jj = count($a_tmp); $ii < $jj; $ii++)
        {
            $a_tmp[$ii] = preg_replace('@^[　]+@u', '', $a_tmp[$ii]);
            if (strlen($a_tmp[$ii]))
                $this->paragraphs[] = $a_tmp[$ii];
        }
        if (empty($this->paragraphs))
            throw new Model\ParagraphsNotFoundException;
        $s_ret = $this->crop('@<a href="/chapter/\d+/@', '@">上一章</a>@', $content);
        $this->prevLink = false === $s_ret ? '' : $s_ret;
        $s_ret = $this->crop('@<a href="/list/@', '@">返回目录</a>@', $content);
        if (false === $s_ret)
            throw new Model\TocLinkNotFoundException;
        $this->tocLink = '../../list/' . $s_ret;
        $s_ret = $this->crop('@a href="/chapter/\d+/@', '@">下一章</a>@', $content);
        $this->nextLink = false === $s_ret ? '' : $s_ret;
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
