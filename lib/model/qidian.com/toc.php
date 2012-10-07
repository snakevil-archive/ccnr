<?php
/**
 * Represents as a novel TOC page in `qidian.com'.
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

namespace NrModel\Qidian_com;

use Exception;
use NrModel;

class TOC extends NrModel\TOC
{
    /**
     * Defines the matched URL pattern.
     *
     * INHERITED from {@link NrModel\Page::PATTERN}.
     *
     * @var string
     */
    const PATTERN = '~^http://www\.qidian\.com/BookReader/\d+\.aspx$~';

    /**
     * Parses retrieved content into meta-data.
     *
     * OVERRIDEN FROM {@link NrModel\Page::parse()}.
     *
     * @param  string  $content
     * @return TOC
     */
    protected function parse($content)
    {
        settype($content, 'string');
        $s_ret = $this->crop('@<meta id="meta_share" name="meta_share" content="@', '@" title="@', $content);
        if (false === $s_ret)
            return $this;
        list($this->title, $this->author) = explode('-', $s_ret);
        $s_ret = $this->crop('@<div id="content">@', '@</ul></div></div>\s*</div>@', $content);
        $s_ex = '@<li style=\'width:25%;\'>' .
            '<a(?:| rel="nofollow") href="(.*\d+,\d+\.aspx)" title=\'[^\']*\'(?:| target=\'_blank\')>' .
            '(.*)' .
            '</a></li>@U';
        if (false === $s_ret ||
            false === preg_match_all($s_ex, $s_ret, $a_tmp)
        )
            return $this;
        $this->chapters = array();
        for ($ii = 0, $jj = count($a_tmp[1]); $ii < $jj; $ii++)
        {
            $a_tmp[1][$ii] = array_pop(explode('/BookReader/', $a_tmp[1][$ii]));
            if (0 === strpos($a_tmp[1][$ii], 'v'))
                $a_tmp[1][$ii] = '#' . array_shift(explode('.', array_pop(explode(',', $a_tmp[1][$ii]))));
            $this->chapters[$a_tmp[1][$ii]] = $a_tmp[2][$ii];
        }
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
