<?php

namespace Source\Framework\Support;

/**
 * Class Paginator
 *
 * @package Paginator
 */
class Paginator
{
    /** @var int */
    private $page;

    /** @var int */
    private $pages;

    /** @var int */
    private $rows;

    /** @var int */
    private $limit;

    /** @var int */
    private $offset;

    /** @var int */
    private $range;

    /** @var string */
    private $link;

    /** @var string */
    private $title;

    /** @var string */
    private $class;

    /** @var string */
    private $hash;

    /** @var array */
    private $first;

    /** @var array */
    private $last;

    /** @var string */
    private $params;
	
    /**
     * Paginator constructor.
     * @param string|null $link
     * @param string|null $title
     * @param array|null $first
     * @param array|null $last
     */
    public function __construct($link = null, $title = null, $first = null, $last = null)
    {
        $this->link = (!empty($link) ? $link : "?page=");
        $this->title = (!empty($title) ? $title : "Página");
        $this->first = (!empty($first) ? $first : ["Primeira página", "<i class='fas fa-angle-double-left'></i>"]);
        $this->last = (!empty($last) ? $last : ["Última página", "<i class='fas fa-angle-double-right'></i>"]);
    }

    /**
     * @param int $rows
     * @param int $limit
     * @param int|null $page
     * @param int $range
     * @param string|null $hash
     * @param array $params
     */
    public function pager($rows, $limit = 10, $page = null, $range = 3, $hash = null, $params = [])
    {
        $this->rows = $this->toPositive($rows);
        $this->limit = $this->toPositive($limit);
        $this->range = $this->toPositive($range);
        $this->pages = (int)ceil($this->rows / $this->limit);
        $this->page = ($page <= $this->pages ? $this->toPositive($page) : $this->pages);

        $this->offset = (($this->page * $this->limit) - $this->limit >= 0 ? ($this->page * $this->limit) - $this->limit : 0);
        $this->hash = (!empty($hash) ? "#{$hash}" : null);
		
		$this->addGetParams($params);

        if ($this->rows && $this->offset >= $this->rows) {
            header("Location: {$this->link}" . ceil($this->rows / $this->limit));
            exit;
        }
    }

    /**
     * @return int
     */
    public function limit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function page()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function pages()
    {
        return $this->pages;
    }

    /**
     * @param string $cssClass
     * @param bool $fixedFirstAndLastPage
     * @return null|string
     */
    public function render($cssClass = null, $fixedFirstAndLastPage = true)
    {
        $this->class = (!empty($cssClass) ? $cssClass : "paginator");

        if ($this->rows > $this->limit):
            $paginator = "<nav class=\"{$this->class}\">";
            $paginator .= $this->firstPage($fixedFirstAndLastPage);
            $paginator .= $this->beforePages();
            $paginator .= "<span class=\"{$this->class}_item {$this->class}_active\">{$this->page}</span>";
            $paginator .= $this->afterPages();
            $paginator .= $this->lastPage($fixedFirstAndLastPage);
            $paginator .= "</nav>";

            return $paginator;
        endif;

        return null;
    }

    /**
     * @return null|string
     */
    private function beforePages()
    {
        $before = null;
        for ($iPag = $this->page - $this->range; $iPag <= $this->page - 1; $iPag++):
            if ($iPag >= 1):
                $before .= "<a class='{$this->class}_item' title=\"{$this->title} {$iPag}\" rel=\"{$iPag}\" href=\"{$this->link}{$iPag}{$this->hash}{$this->params}\" onclick='followToPage(this)'>{$iPag}</a>";
            endif;
        endfor;

        return $before;
    }

    /**
     * @return string|null
     */
    private function afterPages()
    {
        $after = null;
        for ($dPag = $this->page + 1; $dPag <= $this->page + $this->range; $dPag++):
            if ($dPag <= $this->pages):
                $after .= "<a class='{$this->class}_item' title=\"{$this->title} {$dPag}\" rel=\"{$dPag}\" href=\"{$this->link}{$dPag}{$this->hash}{$this->params}\" onclick='followToPage(this)'>{$dPag}</a>";
            endif;
        endfor;

        return $after;
    }

    /**
     * @param bool $fixedFirstAndLastPage
     * @return string|null
     */
    public function firstPage($fixedFirstAndLastPage = true)
    {
        if ($fixedFirstAndLastPage || $this->page != 1) {
            return "<a class='{$this->class}_item' title=\"{$this->first[0]}\" rel='1' href=\"{$this->link}1{$this->hash}{$this->params}\" onclick='followToPage(this)'>{$this->first[1]}</a>";
        }
        return null;
    }

    /**
     * @param bool $fixedFirstAndLastPage
     * @return string|null
     */
    public function lastPage($fixedFirstAndLastPage = true)
    {
        if ($fixedFirstAndLastPage || $this->page != $this->pages) {
            return "<a class='{$this->class}_item' title=\"{$this->last[0]}\" rel=\"{$this->pages}\" href=\"{$this->link}{$this->pages}{$this->hash}{$this->params}\" onclick='followToPage(this)'>{$this->last[1]}</a>";
        }
        return null;
    }

    /**
     * @param $number
     * @return int
     */
    private function toPositive($number)
    {
        return ($number >= 1 ? $number : 1);
    }
	
    /**
     * Add get parameters
     * @param array $params
     * @return null
     */
    private function addGetParams(array $params)
    {
        $this->params = '';
        
        if (count($params) > 0) {
			
            if (isset($params['page'])) {
                unset($params['page']);
            }
            
            $this->params  = '&';
            $this->params .= http_build_query($params);
        }
        
        return $this;
    }
}