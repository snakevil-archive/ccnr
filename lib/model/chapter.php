<?php
/**
 * Represents as a novel chapter. THIS CLASS CANNOT BE INSTANTIATED.
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

namespace CCNR\Model;

use PDO;

abstract class Chapter extends Page
{
    /**
     * Retrieves the novel title.
     *
     * @var string
     */
    protected $novelTitle;

    /**
     * Retrieves paragraphs in array.
     *
     * @var array
     */
    protected $paragraphs;

    /**
     * Retrieves the link URL of previous chapter.
     *
     * @var string
     */
    protected $prevLink;

    /**
     * Retrieves the link URL of novel TOC page.
     *
     * @var string
     */
    protected $tocLink;

    /**
     * Retrieves the link URL of next chapter.
     *
     * @var string
     */
    protected $nextLink;

    /**
     * Implements magic method.
     *
     * OVERRIDEN FROM {@link Page::__get()}. THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  string $prop
     * @return mixed
     * @ignore
     */
    final public function __get($prop)
    {
        settype($prop, 'string');
        if ('novelTitle' == $prop)
            return $this->novelTitle;
        else if ('paragraphs' == $prop)
            return $this->paragraphs;
        else if ('prevLink' == $prop)
            return $this->prevLink;
        else if ('tocLink' == $prop)
            return $this->tocLink;
        else if ('nextLink' == $prop)
            return $this->nextLink;
        return parent::__get($prop);
    }

    /**
     * Reads page informations from local database.
     *
     * THIS METHOD CANNOT BE OVERRIDEN.
     *
     * @param  string $url
     * @return array
     */
    final protected function read($url)
    {
        $s_sql = 'SELECT book novelTitle, title, purl prevLink, nurl nextLink, paragraphs paragraphs'
            . ' FROM chapters'
            . ' WHERE url = ?';
        $o_stmt = $this->db->prepare($s_sql);
        $a_ret = false;
        if ($o_stmt->execute(array($url))) {
            $a_ret = $o_stmt->fetchAll(PDO::FETCH_ASSOC);
            $o_stmt->closeCursor();
            if (count($a_ret)) {
                $a_ret = $a_ret[0];
                $a_ret['paragraphs'] = json_decode($a_ret['paragraphs']);
                $a_ret['tocLink'] = dirname($a_ret['prevLink']);
            } else {
                $a_ret = false;
            }
        }
        return $a_ret;
    }

    final protected function write()
    {
        if ($this->nextLink) {
            $s_sql = 'SELECT 1 FROM chapters WHERE url = ?';
            $o_stmt = $this->db->prepare($s_sql);
            if ($o_stmt->execute(array($this->url))) {
                if (!$o_stmt->rowCount()) {
                    $o_stmt->closeCursor();
                    $s_sql = 'INSERT INTO chapters (url, book, title, purl, nurl, paragraphs, ctime, cip)'
                        . ' VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                    $o_stmt = $this->db->prepare($s_sql);
                    $b_suc = $o_stmt->execute(
                        array(
                            $this->url,
                            $this->novelTitle,
                            $this->title,
                            $this->prevLink,
                            $this->nextLink,
                            json_encode($this->paragraphs, JSON_UNESCAPED_UNICODE),
                            $_SERVER['REQUEST_TIME'],
                            $_SERVER['REMOTE_ADDR']
                        )
                    );
                    if (!$b_suc) {
                        ob_end_clean();
                        var_dump($o_stmt->errorInfo());
                        die;
                    }
                } else {
                    $o_stmt->closeCursor();
                }
            }
        }
        return $this;
    }
}

# vim:se ft=php ff=unix fenc=utf-8 tw=120:
