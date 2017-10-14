<?php

namespace Tests\Hefekranz\Pagination;

use Hefekranz\Pagination\JsonPagination;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class JsonPaginationTest extends TestCase
{

    public function testItShouldSerializeToJson() {
        $request = Request::create("/collection","GET",["page" => 2,"limit" => 10]);

        $pagination = (new JsonPagination(100, $request))->build();

        $json = json_encode($pagination);
        $decoded = json_decode($json,true);
        $this->assertJson($json);
        $this->assertEquals(100,$decoded["total"]);

    }

}
