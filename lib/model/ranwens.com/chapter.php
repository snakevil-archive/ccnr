<?php
/**
 * Represents as a novel chapter in `ranwens.com'.
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

class Chapter extends NrModel\Chapter
{
    /**
     * Defines the matched URL pattern.
     *
     * INHERITED from {@link NrModel\Page::PATTERN}.
     *
     * @var string
     */
    const PATTERN = '~^http://www\.ranwens\.com/files/article/html/\d+/\d+/\d+\.html$~';

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
        $s_ret = $this->crop('@var articlename=\'@', '@\';@', $content);
        if (false === $s_ret)
            return $this;
        $this->novelTitle = $s_ret;
        $s_ret = $this->crop('@var chaptername=\'@', '@\';@', $content);
        if (false === $s_ret)
            return $this;
        $this->title = $s_ret;
        $s_ret = $this->crop('@var author=\'@', '@\';@', $content);
        if (false === $s_ret)
            return $this;
        $this->author = $s_ret;
        $this->tocLink = 'index.html';
        $s_ret = $this->crop('@var preview_page = "@', '@";@', $content);
        if (false === $s_ret)
            return $this;
        $this->prevLink = $s_ret;
        if ('index.html' == $this->prevLink)
            $this->prevLink = '';
        $s_ret = $this->crop('@var next_page = "@', '@";@', $content);
        if (false === $s_ret)
            return $this;
        $this->nextLink = $s_ret;
        if ('index.html' == $this->nextLink)
            $this->nextLink = '';
        $s_ret = $this->crop('@<DIV id=content name="content">\s*<script src=\'@', '@\'</script>@', $content);
        if (false === $s_ret)
            return $this;
        $content = $this->read($s_ret);
        $content = iconv('gbk', 'utf-8//ignore', $content);
        $s_ret = $this->crop('@document\.writeln\("(&nbsp;)*@', '@<br /><br />(&nbsp;)*</div>@', $content);
        if (false === $s_ret)
            return $this;
        $this->paragraphs = array();
        $a_tmp = preg_split('@(<br />)+(&nbsp;)*@', $s_ret);
        for ($ii = 0, $jj = count($a_tmp); $ii < $jj; $ii++)
        {
            $a_tmp[$ii] = preg_replace('@^[　]+@u', '', $a_tmp[$ii]);
            if (strlen($a_tmp[$ii]))
                $this->paragraphs[] = $a_tmp[$ii];
        }
        if (0 === strpos($this->paragraphs[0], '收藏【燃文小说网】'))
            array_shift($this->paragraphs);
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
