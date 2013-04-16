<?php

//phpinfo(); 

interface a{
	function setA();
	function setB();
}

class AA implements a {
	function setA(){

	}

	function setB(){

	}

	function setC(){
		
	}
}


function a($b,$c) {
echo $b;
echo $c;
}
call_user_func('a', "111","222"); echo '<br />';
call_user_func('a', "333","444");


d($var);
dd($var);
d($var1, $var2);