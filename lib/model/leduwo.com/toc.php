<?php
/**
 * Represents as a novel TOC page in `leduwo.com'.
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

namespace CCNR\Model\Leduwo_com;

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
    const PATTERN = '~^http://(www\.)?leduwo\.com/book/\d+/\d+/(index\.html)?$~';

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
        $s_ret = $this->crop('@<div id="box"><h1>@', '@</h1></div>@', $content);
        if (false === $s_ret)
            throw new Model\NovelTitleNotFoundException;
        $this->title = $s_ret;
        $s_ret = $this->crop('@小说系列(&nbsp;\s*)+@', '@/著(&nbsp;\s*)+@', $content);
        if (false === $s_ret)
            throw new Model\AuthorNotFoundException;
        $this->author = $s_ret;
        $s_ret = $this->crop('@<table border="0" align="center" cellpadding="3" cellspacing="1" class="acss">@', '@<div id="box">@', $content);
        if (false === $s_ret)
            throw new Model\ChaptersListingNotFoundException;
        $content = 'class="vcss">正文</td></tr>' . $s_ret . '<td colspan="4"';
        $this->chapters = array();
        do
        {
            $s_vol = $this->crop('@class="vcss">@', '@</td>@', $content);
            if (false === $s_vol)
                break;
            $s_ret = $this->crop('@</tr>@', '@<td colspan="4"@', $content);
            if (false === $s_ret ||
                false === preg_match_all('@<a href="(\d+\.html)">(.*)</a>@U', $s_ret, $a_tmp)
            )
                throw new Model\ChaptersListingNotFoundException(array('volume' => $s_vol));
            if (empty($a_tmp[0]))
                continue;
            $a_chps = array();
            if (array_key_exists($s_vol, $this->chapters))
            {
                if (array_pop(array_keys($this->chapters)) == $s_vol)
                    $a_chps = $this->chapters[$s_vol];
                else
                {
                    while (array_key_exists($s_vol, $this->chapters))
                    {
                        list($s_vol, $ii) = explode('#', $s_vol);
                        if (!$ii)
                            $ii = 1;
                        $s_vol .= '#' . (1 + $ii);
                    }
                }
            }
            for ($ii = 0, $jj = count($a_tmp[1]); $ii < $jj; $ii++)
            {
                $a_chps[$s_prefix . $a_tmp[1][$ii]] = $this->clearChapterTitle($a_tmp[2][$ii]);
            }
            if (empty($a_chps))
                throw new Model\ChaptersListingNotFoundException(array('volume' => $s_vol));
            $this->chapters[$s_vol] = $a_chps;
        }
        while (true);
        if (empty($this->chapters))
            throw new Model\ChaptersListingNotFoundException;
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
