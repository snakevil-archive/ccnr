<?php
/**
 * Represents as a novel chapter in `wobudu.com'.
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

namespace CCNR\Model\Wobudu_com;

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
    const PATTERN = '~^http://www\.wobudu\.com/\d+/\d+\.html$~';

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
        $s_ret = $this->crop('@<title>@', '@</title>@', $content);
        if (false === $s_ret)
            throw new NovelTitleNotFoundException;
        list($this->novelTitle, $this->title) = explode('： ', substr($s_ret, 10, -25));
        $s_ret = $this->crop('@<div id="wcr">.*<p>@sU', '@</p>\s*</div>\s*<div id="wc2">@', $content);
        if (false === $s_ret)
            throw new ParagraphsNotFoundException;
        $a_tmp = preg_split('@\s*</p>\s*<p>\s*@', $s_ret);
        $this->paragraphs = array();
        for ($ii = 0, $jj = count($a_tmp); $ii < $jj; $ii++)
        {
            $a_tmp[$ii] = preg_replace('@^[　]+@u', '', $a_tmp[$ii]);
            if (strlen($a_tmp[$ii]))
                $this->paragraphs[] = $a_tmp[$ii];
        }
        if (empty($this->paragraphs))
            throw new ParagraphsNotFoundException;
        $this->tocLink = './';
        $s_ret = $this->crop('@<div class="page">\s*<a href="@', '@">上一章</a>@', $content);
        if (false === $s_ret)
            throw new PrevLinkNotFoundException;
        $this->prevLink = $s_ret;
        if ('./' == $this->prevLink)
            $this->prevLink = '';
        $s_ret = $this->crop('@>返回目录</a>\s*<a href="@', '@">下一章</a>@', $content);
        if (false === $s_ret)
            throw new NextLinkNotFoundException;
        $this->nextLink = $s_ret;
        if ('./' == $this->nextLink)
            $this->nextLink = '';
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
