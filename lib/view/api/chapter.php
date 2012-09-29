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
 * @copyright © 2012 snakevil.in
 * @license   http://www.gnu.org/licenses/gpl.html
 */

namespace NrView\API;

use Exception;
use NrModel;
use NrView;

class Chapter extends NrView\Data
{
    /**
     * Stores the chapter model.
     *
     * @var NrModel\Chapter
     */
    protected $meta;

    /**
     * CONSTRUCT FUNCTION
     *
     * OVERRIDEN FROM {@link NrView\Data::__construct()}.
     *
     * @param string          $uri
     * @param NrModel\Chapter $chapter
     */
    public function __construct($uri, NrModel\Chapter $chapter)
    {
        parent::__construct($uri, $chapter);
    }

    /**
     * Serializes for JSON coding.
     *
     * IMPLEMENTED FROM {@link NrView\Data::jsonSerialize()}.
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
