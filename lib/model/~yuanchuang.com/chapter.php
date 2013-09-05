<?php
/**
 * Represents as a novel chapter in `yuanchuang.com'.
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

namespace CCNR\Model\Yuanchuang_com;

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
    const PATTERN = '~^http://www\.yuanchuang\.com/bookreader/\d+/\d+\.html$~';

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
        $s_ret = $this->crop('@<strong class="red">@', '@</strong>@', $content);
        if (false === $s_ret)
            throw new Model\NovelTitleNotFoundException;
        $this->novelTitle = $s_ret;
        $s_ret = $this->crop('@<h1>@', '@</h1>@', $content);
        if (false === $s_ret)
            throw new Model\ChapterTitleNotFoundException;
        $this->title = $s_ret;
        $s_ret = $this->crop('@<div id="readtext">\s+<p>@', '@</p>\s+<div class="text">@', $content);
        if (false === $s_ret)
            throw new Model\ParagraphsNotFoundException;
        $this->paragraphs = array();
        if (!strpos($s_ret, '<img src="'))
        {
            $a_tmp = preg_split('@</p><p>@', $s_ret);
            for ($ii = 0, $jj = count($a_tmp); $ii < $jj; $ii++)
            {
                $a_tmp[$ii] = preg_replace('@^[　]+@u', '', $a_tmp[$ii]);
                if (strlen($a_tmp[$ii]))
                    $this->paragraphs[] = $a_tmp[$ii];
            }
        }
        else if (preg_match_all('@<img src="([^\s"]+)" align="center" border=0>@', $s_ret, $a_tmp))
        {
            for ($ii = 0, $jj = count($a_tmp[0]); $ii < $jj; $ii++)
                $this->paragraphs[] = '![IMAGE](' . $a_tmp[1][$ii] . ')';
        }
        if (empty($this->paragraphs))
            throw new Model\ParagraphsNotFoundException;
        $s_ret = $this->crop('@onclick="window.location.href=\'http://www.yuanchuang.com/@', '@\'@', $content);
        if (false === $s_ret)
            throw new Model\PrevLinkNotFoundException;
        $a_tmp = explode('/', $s_ret);
        if ('bookcatalog' != $a_tmp[0])
        {
            $this->prevLink = $a_tmp[2];
            $s_ret = $this->crop('@onclick="window.location.href=\'http://www.yuanchuang.com/@', '@\'@', $content);
            if (false === $s_ret)
                throw new Model\TocLinkNotFoundException;
        }
        $this->tocLink = '../../' . $s_ret;
        $s_ret = $this->crop('@onclick="window.location.href=\'http://www.yuanchuang.com/@', '@\'@', $content);
        if (false === $s_ret)
            throw new Model\NextLinkNotFoundException;
        $a_tmp = explode('/', $s_ret);
        if ('bookreader' == $a_tmp[0])
        {
            $this->nextLink = $a_tmp[2];
        }
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
