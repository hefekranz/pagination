# Pagination

[![Build Status](https://travis-ci.org/hefekranz/pagination.svg?branch=master)](https://travis-ci.org/hefekranz/pagination)

This is a very simple one. You take a request, you take the count of whatever items you want to display.  
You out them together, you get a pagination.


### Installation

```
compser require hefekranz\pagination
```

### Usage
Quick Example for GET /collection?page=2&amp;limit=10 on a collection of 100 items
```php
    public function getMeSomeCollectionAction(Request $request) {

        $data = $objectRepo->findAll();
        
        $pagination = (new Pagination($objectRepo->count(), $request))->build();
        
        return new JsonResponse([
            "pagination" => $pagination->__toArray(),
            "data"       => $data->_toArray()
        ]);
    }
```
Would produce

```json
{
    "pagination": {
        "pages": {
            "first": 1,
            "previous": 1,
            "current": 1,
            "next": 2,
            "last": 5
        },
        "links": {
            "first": "/collection?page=1&limit=20",
            "previous": "/collection?page=1&limit=20",
            "current": "/collection?page=1&limit=20",
            "next": "/collection?page=2&limit=20",
            "last": "/collection?page=5&limit=20"
        }
    },
    "data": [
        {},
        {},
        {}
    ]
}
```

The class expects a Symfony\HttpFoundation\Request object. If you dont have one, build it.   
From the global request, or your custom implementation.

```php
        $request = Request::create("/collection","GET",["page" => 2,"limit" => 10]); 
        /** or Request::createFromGlobals() */
        $pagination = (new Pagination($objectRepo->count(), $request))->build();

```
