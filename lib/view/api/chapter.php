<?php
/**
 * Represents as chapters data view.
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

namespace CCNR\View\API;

use Exception;
use CCNR\Model;
use CCNR\View;

class Chapter extends View\Data
{
    /**
     * Stores the chapter model.
     *
     * @var Model\Chapter
     */
    protected $meta;

    /**
     * CONSTRUCT FUNCTION
     *
     * OVERRIDEN FROM {@link View\Data::__construct()}.
     *
     * @param string          $uri
     * @param Model\Chapter $chapter
     */
    public function __construct($uri, Model\Chapter $chapter)
    {
        parent::__construct($uri, $chapter);
    }

    /**
     * Serializes for JSON coding.
     *
     * IMPLEMENTED FROM {@link View\Data::jsonSerialize()}.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array('title' => $this->meta->title,
                'novelTitle' => $this->meta->novelTitle,
                'paragraphs' => $this->meta->paragraphs,
                'links' => array('toc' => $this->meta->tocLink,
                    'previous' => $this->meta->prevLink,
                    'next' => $this->meta->nextLink
                )
            );
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
