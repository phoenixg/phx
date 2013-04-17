<?php
class Default_Controller extends Controller {

	public function __construct(){
		parent::__construct();
		d($this->CFG);
	}

	public function action_index()
	{
		d($CFG::get('application.timezone'));
		echo 'you are in default controller and default method';
	}

	public function action_hello(array $params)
	{
		var_dump($params);
		echo 'you are in hello';

		$this->test();

		$model = new User_Model();
		$myVar =  $model->query();
		var_dump($myVar);

		$viewPath = dirname(__FILE__) . '/../views/default.php';
		if(!file_exists($viewPath))
			throw new Exception('不存在视图文件：'.$viewPath);

		include $viewPath;
	}



}
