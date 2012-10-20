<?php
/**
 * Represents as a novel TOC page in `zongheng.com'.
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

namespace CCNR\Model\Zongheng_com;

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
    const PATTERN = '~^http://book\.zongheng\.com/showchapter/\d+.html$~';

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
        $s_ret = $this->crop('@<meta name="keywords" content="@', '@,最新章节,最新TXT下载,全文阅读@', $content);
        if (false === $s_ret)
            throw new Model\NovelTitleNotFoundException;
        list($this->title, $this->author) = explode(',', $s_ret);
        $s_ret = $this->crop('@<div class="chapter">@', '@<!-- 章节列表 结束 -->@', $content);
        if (false === $s_ret)
            throw new Model\ChaptersListingNotFoundException;
        $content = $s_ret;
        $this->chapters = array();
        do
        {
            $s_vol = $this->crop('@<h2>@', '@</h2>@', $content);
            if (false === $s_vol)
                break;
            $s_ret = $this->crop('@<div class="booklist">@', '@</div>@', $content);
            if (false === $s_ret ||
                false === preg_match_all('@(<td>|</em>\s*)<a href="http://book.zongheng.com(.*)" title="最后更新时间:.*">(.*)</a>@U', $s_ret, $a_tmp)
            )
                throw new Model\ChaptersListingNotFoundException(array('volume' => $s_vol));
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
            for ($ii = 0, $jj = count($a_tmp[1]); $ii < $jj; $ii++)
            {
                $a_chps[('/' == $a_tmp[1][$ii][1] ? '#' : '..') . $a_tmp[2][$ii]] = $a_tmp[3][$ii];
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
