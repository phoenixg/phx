# Marg

## About
Marg is an attempt to create an easy-to-use and extensible request router for
PHP programmers.

## Tutorial
```php
<?php

include 'marg/marg.php';

$routes = array(
    '/' => 'home',
    '/example_1/([0-9])' => array(
        'controller' => 'Example1',
        'methods' => array('GET', 'POST'),
    ),
    '/example_2' => array('Example2', array('GET', 'POST')),
    '/example_3' => 'Example3',
);

function home() {
    global $request;

    if ($request->verb == 'GET') {
        echo '<h1>Hello World!</h1>';
        if ($request->is_ajax) {
            echo json_encode(array('message' => 'JSON works!'));
        }
    } else {
        raise('405');
    }
}

function Example1($num) {
    global $request;

    echo '<h1>Example 1.' . $num . '</h1>';
    if ($request->verb == 'POST') {
        echo 'A POST request.';
    } else {
        echo 'A GET request.';
    }
}

function Example2() {
    global $request;

    echo '<h1>Example 2!</h1>';
    echo 'Request Type: ' . $request->verb;
}

class Example3 {
    function setUp() {
        echo '<center>';
    }

    function tearDown() {
        echo '</center>';
    }

    function get() {
        echo '<h1>Example 3!</h1>';
    }

    function get_ajax() {
        echo '<h1>Example 3 - AJAX Request!</h1>';
    }
};

function raise_404() {
    echo '<h1>Sorry! What you are looking for does not exists. :(</h1>';
}

function raise_405() {
    echo '<h1>405: Method Not Allowed</h1>';
}

Marg::addSetUp(function () {  echo '<html><head><title>Marg Examples</title></head><body>'; });
Marg::addTearDown(function () { echo '</body></html>'; });

Marg::run($routes);

?>
```

## License
Marg was create by [Vaidik Kapoor](http://vaidikkapoor.info). It is released under MIT License.
