<?php


namespace Hefekranz\Pagination;


class JsonPagination extends Pagination implements \JsonSerializable
{
    function jsonSerialize()
    {
        return $this->toArray();
    }

}