<?php
/**
 * Represents as a novel chapter in `yzuu.com'.
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

namespace NrModel\Yzuu_com;

use Exception;
use NrModel;

class Chapter extends NrModel\Chapter
{
    /**
     * Defines the matched URL pattern.
     *
     * INHERITED from {@link NrModel\Page::PATTERN}.
     *
     * @var string
     */
    const PATTERN = '~^http://www\.yzuu\.com/look/\d+/\d+/?$~';

    /**
     * Parses retrieved content into meta-data.
     *
     * OVERRIDEN FROM {@link NrModel\Page::parse()}.
     *
     * @param  string  $content
     * @return Chapter
     */
    protected function parse($content)
    {
        settype($content, 'string');
        $content = iconv('gbk', 'utf-8//ignore', $content);
        $s_ret = $this->crop('@<meta name="description" content="@', '@">@', $content);
        if (false === $s_ret)
            return $this;
        list($this->novelTitle, $this->title) = explode(' - ', $s_ret);
        $s_ret = $this->crop('@<div id="readtext"><p>(&nbsp;)*@', '@</p></div>@', $content);
        if (false === $s_ret)
            return $this;
        $this->paragraphs = array();
        if (!strpos($s_ret, '<img src="'))
        {
            $s_ret = preg_replace(array(
                    '@\s*([wｗＷ]\s*){3}[\.．。点][yｙＹ]\s*[zｚＺ]\s*([uｕＵ]\s*){2}[\.．。点]\s*[cｃＣ]\s*[oｏＯ]\s*[mｍＭ]\s*@iu',
                    '@看小说就到叶子悠悠~@',
                    '@\(看小说就到叶 子・悠~悠\)@',
                    '@【叶\*子】【悠\*悠】@',
                ), '', $s_ret);
            $a_tmp = preg_split('@(<br>)+(&nbsp;)*@', $s_ret);
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
        $s_prefix = '/' == substr($this->url, -1) ? '../' : '';
        $this->tocLink = strlen($s_prefix) ? $s_prefix : './';
        $s_ret = $this->crop('@\(快捷键：←\)\s*<a href="/look/\d+/@', '@">上一页</a>@', $content);
        if (false === $s_ret)
            return $this;
        $this->prevLink = 'Index.shtml' == $s_ret ? '' : $s_prefix . $s_ret . '/';
        $s_ret = $this->crop('@">返回目录</a>\s*<a href="/look/\d+/@', '@">下一页</a>@', $content);
        if (false === $s_ret)
            return $this;
        $this->nextLink = 'Index.shtml' == $s_ret ? '' : $s_prefix . $s_ret . '/';
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
