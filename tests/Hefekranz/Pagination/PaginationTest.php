<?php

namespace Tests\Hefekranz\Pagination;

use Hefekranz\Pagination\Pagination;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{

    public function testItShouldBuildDefaultPaginationFromSymfonyRequestWithoutParameters() {

        $request = Request::create("/collection");
        $totals = 100;
        $pagination = (new Pagination(100, $request))->build();

        $this->assertEquals(1,$pagination->getFirst());
        $this->assertEquals(1,$pagination->getPrevious());
        $this->assertEquals(1,$pagination->getCurrent());
        $this->assertEquals(2,$pagination->getNext());
        $this->assertEquals(5,$pagination->getLast());
        $this->assertEquals("/collection?page=1&limit=20",$pagination->getFirstPage());
        $this->assertEquals("/collection?page=1&limit=20",$pagination->getPreviousPage());
        $this->assertEquals("/collection?page=1&limit=20",$pagination->getCurrentPage());
        $this->assertEquals("/collection?page=2&limit=20",$pagination->getNextPage());
        $this->assertEquals("/collection?page=5&limit=20",$pagination->getLastPage());
    }

    public function testItShouldBuildFromRequestParameters() {

        $total = 100;
        $request = Request::create("/collection","GET",["page" => 2,"limit" => 10]);

        $pagination = (new Pagination($total, $request))->build();

        $this->assertEquals(1,$pagination->getFirst());
        $this->assertEquals(1,$pagination->getPrevious());
        $this->assertEquals(2,$pagination->getCurrent());
        $this->assertEquals(3,$pagination->getNext());
        $this->assertEquals(10,$pagination->getLast());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getFirstPage());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getPreviousPage());
        $this->assertEquals("/collection?page=2&limit=10",$pagination->getCurrentPage());
        $this->assertEquals("/collection?page=3&limit=10",$pagination->getNextPage());
        $this->assertEquals("/collection?page=10&limit=10",$pagination->getLastPage());

    }

    public function testItShouldNormaliseToArray() {

        $total = 100;
        $request = Request::create("/collection","GET",["page" => 2,"limit" => 10]);

        $pagination = (new Pagination($total, $request))->build()->__toArray();

        $this->assertEquals(1,$pagination["pages"]["first"]);
        $this->assertEquals(1,$pagination["pages"]["first"]);
    }

    public function testItShouldGetOffset() {

        $total = 100;
        $request = Request::create("/collection","GET",["page" => 2,"limit" => 10]);

        $pagination = (new Pagination($total, $request))->build();

        $this->assertEquals(10,$pagination->getOffset());
    }

    public function testItShouldBuildWithCount0() {

        $total = 100;
        $request = Request::create("/collection","GET",["page" => 2,"limit" => 10]);

        $pagination = (new Pagination(0, $request))->build();

        $this->assertEquals(1,$pagination->getFirst());
        $this->assertEquals(1,$pagination->getPrevious());
        $this->assertEquals(1,$pagination->getCurrent());
        $this->assertEquals(1,$pagination->getNext());
        $this->assertEquals(1,$pagination->getLast());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getFirstPage());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getPreviousPage());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getCurrentPage());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getNextPage());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getLastPage());
    }

    public function testGettersAndSetters() {

        $total = 100;
        $request = Request::create("/collection","GET",["page" => 2,"limit" => 10]);

        $pagination = new Pagination($total);
        $pagination->setTotalCount(100);
        $pagination->setLimit(10);
        $pagination->setCurrent(2);
        $pagination->setRequest($request);

        $pagination->build();

        $this->assertEquals(1,$pagination->getFirst());
        $this->assertEquals(1,$pagination->getPrevious());
        $this->assertEquals(2,$pagination->getCurrent());
        $this->assertEquals(3,$pagination->getNext());
        $this->assertEquals(10,$pagination->getLast());
        $this->assertEquals(100,$pagination->getTotalCount());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getFirstPage());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getPreviousPage());
        $this->assertEquals("/collection?page=2&limit=10",$pagination->getCurrentPage());
        $this->assertEquals("/collection?page=3&limit=10",$pagination->getNextPage());
        $this->assertEquals("/collection?page=10&limit=10",$pagination->getLastPage());
    }

    public function testLimitBiggerTotal() {
        $request = Request::create("/collection","GET",["page" => 2,"limit" => 100]);
        $pagination = new Pagination(10,$request);
        $pagination->build();

        $this->assertEquals(1,$pagination->getFirst());
        $this->assertEquals(1,$pagination->getPrevious());
        $this->assertEquals(1,$pagination->getCurrent());
        $this->assertEquals(1,$pagination->getNext());
        $this->assertEquals(1,$pagination->getLast());
    }

    public function testLastPage() {
        $request = Request::create("/collection","GET",["page" => 10,"limit" => 10]);
        $pagination = new Pagination(100,$request);
        $pagination->build();

        $this->assertEquals(1,$pagination->getFirst());
        $this->assertEquals(9,$pagination->getPrevious());
        $this->assertEquals(10,$pagination->getCurrent());
        $this->assertEquals(10,$pagination->getNext());
        $this->assertEquals(10,$pagination->getLast());
        $this->assertEquals("/collection?page=1&limit=10",$pagination->getFirstPage());
        $this->assertEquals("/collection?page=9&limit=10",$pagination->getPreviousPage());
        $this->assertEquals("/collection?page=10&limit=10",$pagination->getCurrentPage());
        $this->assertEquals("/collection?page=10&limit=10",$pagination->getNextPage());
        $this->assertEquals("/collection?page=10&limit=10",$pagination->getLastPage());
    }

    public function testWithoutRequest() {
        $pagination = new Pagination(100);
        $pagination->build();

        $this->assertEquals(1,$pagination->getFirst());
        $this->assertEquals(1,$pagination->getPrevious());
        $this->assertEquals(1,$pagination->getCurrent());
        $this->assertEquals(2,$pagination->getNext());
        $this->assertEquals(5,$pagination->getLast());
        $this->assertNull($pagination->getFirstPage());
        $this->assertNull($pagination->getPreviousPage());
        $this->assertNull($pagination->getCurrentPage());
        $this->assertNull($pagination->getNextPage());
        $this->assertNull($pagination->getLastPage());
    }



}
