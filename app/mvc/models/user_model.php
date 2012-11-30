<?php
class User_Model extends Model {
	public function __construct(){
		parent::__construct();
	}

	public function query()
	{
		return array('apple', 'orange');
	}
}
