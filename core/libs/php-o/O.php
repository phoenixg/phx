<?php

$classPath = realpath(dirname(__FILE__)."/src/O");
if (!class_exists("O")) include($classPath."/O.php");

O::init();
