<?php
/**
 * Represents as a novel chapter in `qidian.com'.
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

namespace CCNR\Model\Qidian_com;

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
    const PATTERN = '~^http://www\.qidian\.com/BookReader/\d+,\d+\.aspx$~';

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
        $s_ret = $this->crop('@<title>\s*小说:@', '@</title>@', $content);
        if (false === $s_ret)
            throw new Model\NovelTitleNotFoundException;
        list($this->novelTitle, $this->title) = explode('/', $s_ret);
        $s_ret = $this->crop('@<a id="HeadPrevLink" href="@', '@">@', $content);
        if (false === $s_ret)
            $this->prevLink = '';
        else
        {
            $this->prevLink = array_pop(explode('/BookReader/', $s_ret));
            if (0 === strpos($this->prevLink, 'v'))
                $this->prevLink = '#' . array_shift(explode('.', array_pop(explode(',', $this->prevLink))));
        }
        $s_ret = $this->crop('@上一章</a>\s*<a href="@', '@">回书目</a>@', $content);
        if (false === $s_ret)
            throw new Model\TocLinkNotFoundException;
        $this->tocLink = array_pop(explode('/BookReader/', $s_ret));
        $s_ret = $this->crop('@<a id="HeadNextLink" href="@', '@">下一章</a>@', $content);
        if (false === $s_ret)
            $this->nextLink = '';
        else
        {
            $this->nextLink = array_pop(explode('/BookReader/', $s_ret));
            switch ($this->nextLink[0])
            {
                case 'v':
                    $this->nextLink = '#' . array_shift(explode('.', array_pop(explode(',', $this->nextLink))));
                    break;
                case 'B':
                    $this->nextLink = '#' . array_pop(explode('chapterId=', $this->nextLink));
                    break;
            }
        }
        $s_ret = $this->crop('@<script src=\'@', '@\'  charset=\'GB2312\'></script>@', $content);
        if (false === $s_ret)
            throw new Model\ParagraphsNotFoundException;
        $content = $this->read($s_ret);
        $content = iconv('gbk', 'utf-8//ignore', $content);
        $s_ret = $this->crop('@document\.write\(\'@', '@(\'\);|<a href=)@', $content);
        if (false === $s_ret)
            throw new Model\ParagraphsNotFoundException;
        $a_tmp = preg_split('@\s*<p>\s*@', $s_ret);
        $this->paragraphs = array();
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
