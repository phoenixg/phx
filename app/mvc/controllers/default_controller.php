<?php

class Default_Controller extends Controller {

    // 普通的是以action_开头
    public function action_index()
    {

        echo 'you are in default controller and default method';
    }

    public function action_pdo()
    {
        /*
        mysql> select * from my_table;
        +----+-------+------+
        | id | name  | age  |
        +----+-------+------+
        |  1 | Harry |   12 |
        |  2 | Marry |   15 |
        |  3 | Tom   |   20 |
        |  4 | Peter |   15 |
        |  5 | Ralph |   16 |
        +----+-------+------+
        */
        //require('smplPDO.php');
        $db_host = 'localhost';
        $db_name = 'test';
        $db_user = 'root';
        $db_pass = '123456';

        $db = new smplPDO( "mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass );

        // 操纵
        $db->insert( 'my_table', array( 'name'=>'John', 'age'=>28 ) );
        $db->update( 'my_table', array( 'age'=>29 ), array( 'name'=>'John' ) );
        $db->delete( 'my_table', array( 'name'=>'John' ) );

        // 查询
        $db->get_all( 'my_table', array( 'age'=>15 ));
        /*
        array
          0 =>
            array
              'id' => string '2' (length=1)
              'name' => string 'Marry' (length=5)
              'age' => string '15' (length=2)
          1 =>
            array
              'id' => string '4' (length=1)
              'name' => string 'Peter' (length=5)
              'age' => string '15' (length=2)
        */

        $db->get_row( 'my_table', array( 'name'=>'Marry' ), array( 'id', 'age', 'name' ) );
        /*
        array
          'id' => string '2' (length=1)
          'age' => string '15' (length=2)
          'name' => string 'Marry' (length=5)
        */

        $db->get_col( 'my_table', array( 'age'=>15 ), 'name' );
        /*
        array
          0 => string 'Marry' (length=5)
          1 => string 'Peter' (length=5)
        */

        $db->get_var( 'my_table', array( 'name'=>'Marry' ) ); // string '2' (length=1) 即id

        // 检查是否存在该值
        if( $db->exists( 'my_table', array( 'name'=>'John' ) ) ) echo 'Record exists!';

        // 获得匹配的结果记录数量
        $db->get_count( 'my_table', array( 'age'=>20 ) ); // int 1

        // debug
        $db->sql; // Holds the SQL query executed.
        $db->bind; // Holds the bind parameters of a Prepared Statement.
        $db->insert_id; // Holds the ID of last inserted row.
        $db->num_rows; // Holds the number of rows affected by last query.
        $db->result; // Holds the result of the last query executed.

        $db->debug(); // Print out all necessary properties.

    }

    public function action_hello(array $params)
    {
        var_dump($params);
        echo 'you are in hello';

        $this->test();
        /*
        $model = new User_Model();
        $myVar =  $model->query();
        var_dump($myVar);
        */

        $viewPath = dirname(__FILE__) . '/../views/default.php';
        if(!file_exists($viewPath))
            throw new Exception('不存在视图文件：'.$viewPath);

        include $viewPath;
    }

    // 只有put, delete, post方法才能使用到的方法，以rest_开头
    public function rest_post($request)
    {
        // 这里应该做一些安全验证和过滤
        // 存数据，获取返回id

        $id = 123;
        return $id;
    }

    public function rest_put($request)
    {
        print_r($request);
        return 'dog';
    }

    public function rest_delete($request)
    {
        print_r($request);
        return 'dog';
    }


}
