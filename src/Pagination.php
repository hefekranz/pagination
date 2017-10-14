<?php
/**
 * @author Levin Mauritz <l.mauritz@posteo.de>
 *
 */

namespace Hefekranz\Pagination;

use Symfony\Component\HttpFoundation\Request;

class Pagination
{
    /** @var int  */
    private $limit;

    /** @var  int */
    private $totalCount;

    /** @var int  */
    private $first;

    /** @var int  */
    private $previous;

    /** @var int  */
    private $current;

    /** @var  int */
    private $next;

    /** @var  int */
    private $last;

    /** @var  string */
    private $firstPage;

    /** @var  string */
    private $previousPage;

    /** @var  string */
    private $currentPage;

    /** @var  string */
    private $nextPage;

    /** @var  string */
    private $lastPage;

    /** @var Request  */
    private $request;


    public function __construct($total, Request $request = null, $pageLimit = 20)
    {
        $this->request = $request;

        $this->totalCount = $total;

        $this->limit = $pageLimit;
        $this->current = 1;
        $this->first = 1;
        $this->previous = 1;

        return $this;
    }

    /**
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount(int $totalCount)
    {
        $this->totalCount = $totalCount;
        return $this;
    }

    /**
     * @param int $current
     * @return $this
     */
    public function setCurrent(int $current)
    {
        $this->current = $current;
        return $this;
    }

    public function setLimit(int $limit) {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;

    }

    /**
     * @return $this
     */
    public function build()
    {

        if($this->totalCount == 0 || $this->limit > $this->totalCount) {
            $this->first = 1;
            $this->previous = 1;
            $this->current = 1;
            $this->next = 1;
            $this->last = 1;
            $this->calculateLimit();
            $this->generateLinks();
            return $this;
        }
        $this->generatePagination();
        $this->generateLinks();
        return $this;
    }

    private function generatePagination()
    {
        $this->calculateLimit();
        $this->calculateLast();
        $this->calculateCurrent();
        $this->calculatePrevious();
        $this->calculateNext();
    }

    private function generateLinks()
    {
        $this->firstPage = $this->buildUrl(
            array(
                'page' => $this->first,
                'limit' => $this->getLimit()
            ));
        $this->previousPage = $this->buildUrl(
            array(
                'page' => $this->previous,
                'limit' => $this->getLimit()
            ));
        $this->currentPage = $this->buildUrl(array(
            'page' => $this->current,
            'limit' => $this->getLimit()
        ));
        $this->nextPage = $this->buildUrl(array(
            'page' => $this->next,
            'limit' => $this->getLimit()
        ));
        $this->lastPage = $this->buildUrl(array(
            'page' => $this->last,
            'limit' => $this->getLimit()
        ));

    }

    private function calculateLimit()
    {
        if($this->request) {
                $this->limit = (int) $this->request->query->get('limit',$this->limit);
            }
    }

    private function calculateLast()
    {
        $this->last = (int) ceil($this->totalCount / $this->limit);
    }

    private function calculateCurrent()
    {
        if($this->request) {
            $this->current = (int) $this->request->query->get('page',1);
        }
    }

    private function calculateNext()
    {
        if($this->current == $this->last)
        {
            $this->next = $this->last;
            return;
        }

        $this->next = $this->current + 1;
    }

    private function calculatePrevious()
    {
        if($this->current == 1) {
            $this->previous = 1;
            return;
        }
        $this->previous = $this->current - 1;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return (int) $this->limit;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return (int) $this->totalCount;
    }

    /**
     * @return int
     */
    public function getFirst()
    {
        return (int) $this->first;
    }

    /**
     * @return int
     */
    public function getCurrent()
    {
        return (int) $this->current;
    }

    /**
     * @return int
     */
    public function getPrevious()
    {
        return (int) $this->previous;
    }

    /**
     * @return int
     */
    public function getNext()
    {
        return (int) $this->next;
    }

    /**
     * @return int
     */
    public function getLast()
    {
        return (int) $this->last;
    }

    /**
     * @return string | null
     */
    public function getFirstPage()
    {
        return $this->firstPage;
    }

    /**
     * @return string | null
     */
    public function getPreviousPage()
    {
        return $this->previousPage;
    }

    /**
     * @return string | null
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return string | null
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }


    /**
     * @return string | null
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    private function buildUrl($parameters)
    {
        if(!$this->request) {
            return null;
        }

        $currentPath = $this->request->getPathInfo();

        $currentQueryParams = $this->request->query->all();
        foreach ($parameters as $key => $value) {
            $currentQueryParams[$key] = $value;
        }
        $query = http_build_query($currentQueryParams);

        return $currentPath.'?'.$query;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->limit * ($this->current - 1);
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        $links = array(
            'first' => $this->firstPage,
            'previous' => $this->previousPage,
            'current' => $this->currentPage,
            'next' => $this->nextPage,
            'last' => $this->lastPage
        );
        $pages = array(
            'first' => $this->first,
            'previous' => $this->previous,
            'current' => $this->current,
            'next' => $this->next,
            'last' => $this->last
        );

        return $data = array(
            'pages' => $pages,
            'links' => $links
        );

    }

}