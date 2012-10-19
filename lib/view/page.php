<?php
/**
 * Represents as abstract page view. THIS CLASS CANNOT BE INSTANTIATED.
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

namespace CCNR\View;

use CCNR\Model;
use CCNR\View;

abstract class Page extends View
{
    /**
     * Stores the novel page model instance.
     *
     * @var Model\Page
     */
    protected $page;

    /**
     * CONSTRUCT FUNCTION
     *
     * OVERRIDEN FROM {@link View::__construct()}.
     *
     * @param string       $uri
     * @param Model\Page $page
     */
    public function __construct($uri, Model\Page $page)
    {
        parent::__construct($uri);
        $this->page = $page;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
