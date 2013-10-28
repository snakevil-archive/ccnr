<?php
/**
 * Represents as a novel chapter in `piaotian.net'.
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

namespace CCNR\Model\Piaotian_net;

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
    const PATTERN = '~^http://www\.piaotian\.net/html/\d/\d+/\d+\.html$~';

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
        $content = iconv('gbk', 'utf-8//ignore', $content);
        $s_ret = $this->crop('@var preview_page = "@', '@";@', $content);
        if (false === $s_ret)
            throw new Model\PrevLinkNotFoundException;
        $this->prevLink = 'index.html' == $s_ret ? '' : $s_ret;
        $s_ret = $this->crop('@var next_page = "@', '@";@', $content);
        if (false === $s_ret)
            throw new Model\NextLinkNotFoundException;
        $this->nextLink = 'index.html' == $s_ret ? '' : $s_ret;
        $this->tocLink = './';
        $s_ret = $this->crop('@<H1><a href=".*">@', '@</H1>@', $content);
        if (false === $s_ret)
            throw new Model\NovelTitleNotFoundException;
        $a_tmp = explode('</a>', $s_ret);
        if (2 != count($a_tmp)) {
            throw new Model\ChapterTitleNotFoundException;
        }
        $this->novelTitle = $a_tmp[0];
        $this->title = $this->clearChapterTitle($a_tmp[1]);
        $s_ret = $this->crop('@</td></tr></table></td></tr></table>\s*<br>\s*(&nbsp;)*@', '@</div>\s*<!--@', $content);
        if (false === $s_ret)
            throw new Model\ParagraphsNotFoundException;
        $this->paragraphs = array();
        $a_tmp = preg_split('@<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;@U', $s_ret);
        for ($ii = 0, $jj = count($a_tmp); $ii < $jj; $ii++)
        {
            $a_tmp[$ii] = preg_replace('@^[　]+@u', '', $a_tmp[$ii]);
            if (strlen($a_tmp[$ii]))
                $this->paragraphs[] = $a_tmp[$ii];
        }
        if (empty($this->paragraphs))
            throw new Model\ParagraphsNotFoundException;
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
