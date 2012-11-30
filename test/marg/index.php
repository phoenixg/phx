<?php

var_dump($_SERVER['REQUEST_METHOD']);

include 'marg/marg.php';

$routes = array(
    '/' => array('home', array('GET', 'POST')),
    '/classes' => 'ClassesExample',
);

function home() {
    echo '<h1>Hello World!</h1>';
}

class ClassesExample {
    public function get() {
        echo '<h1>This is an example of how to use classes with [Marg][MARG].</h1>';
        echo '<p>This is a GET request.</p>';
    }

    public function post() {
        echo '<h1>This is an example of how to use classes with [Marg][MARG].</h1>';
        echo '<p>This is a POST request.</p>';
    }
};

Marg::run($routes);


?>
<form method="post" action="test.php">
	<input type="submit" name="submit" value="Submit using POST" />
</form>