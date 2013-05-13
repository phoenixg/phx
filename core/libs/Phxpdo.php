<?php
class PhxPDO extends PDO {
    private $db_host = 'localhost';
    private $db_name = '';
    private $db_user = '';
    private $db_pass = '';

    public $error     = null;
    public $insert_id = null;
    public $num_rows  = 0;
    public $bind      = array();
    public $sql       = null;
    public $order_by  = null;
    public $group_by  = null;
    public $limit     = null;
    public $result    = null;

    public function __construct( $dsn='', $username='', $password='', $driver_options=array() ) {
        if ( ! defined( 'PDO::ATTR_DRIVER_NAME' ) ) {
            $this->error = "Phxpdo requires PDO extension.";
            exit( $this->error );
        }

        if( empty( $dsn ) ) $dsn="mysql:host=$this->db_host;dbname=$this->db_name";
        if( empty( $username ) ) $username=$this->db_user;
        if( empty( $password ) ) $password=$this->db_pass;

        if( empty( $driver_options ) ) {
            $driver_options=array(
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT=>true,
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES \'UTF8\'',
            );
        }

        try {
            parent::__construct( $dsn, $username, $password, $driver_options );
        } catch ( PDOException $e ) {
            exit( $e->getMessage() );
        }
    }

    public function run( $sql=null, $bind=array() ) {
        $this->flush();

        if( !empty( $sql ) ) $this->sql=$sql;
        if( !empty( $bind ) ) $this->bind=$bind;

        if( empty( $this->sql ) ){
            $this->error='No query to execute!';
            $this->result=false;
            return false;
        }

        try {
            $stmt=$this->prepare( $this->sql );
            if( false === $stmt->execute( $this->bind ) ) {
                $this->result=false;
            } else if( preg_match( "/^(insert|delete|update|replace|drop|create)\s+/i", $this->sql ) ) {
                if( preg_match( "/^(insert|replace)\s+/i", $this->sql ) ) {
                    $this->insert_id=@$this->lastInsertId();
                }
                $this->num_rows=@$stmt->rowCount();
                $this->result=$this->num_rows;
            } else {
                return $stmt;
            }
        } catch ( PDOException $e ) {
            $this->error=$e->getMessage();
            $this->result=false;
        }
        return $this->result;
    }

    public function insert( $table=null, $data=array() ) {
        if( empty( $table ) ) return false;
        if( empty( $data ) || ! is_array( $data ) ) return false;

        $bind=array();
        foreach( array_keys( $data ) as $dn=>$dk ) {
            $bind[':' . $dk]=$data[$dk];
        }

        $sql="INSERT INTO `$table` (`" . implode( '`,`', array_keys( $data ) ) .
            "`) VALUES (:" . implode( ', :', array_keys( $data ) ) . ");";

        return $this->run( $sql, $bind );
    }

    public function update( $table=null, $data=array(), $where=array() ) {
        if( empty( $table ) ) return false;
        if( empty( $data ) || ! is_array( $data ) ) return false;
        if( empty( $where ) || ! is_array( $where ) ) return false;

        $bind=array();
        $sql="UPDATE `$table` SET ";

        foreach( array_keys( $data ) as $sn=>$sf ) {
            $bind[':set_' . $sf]=$data[$sf];
            $sql.="`$sf`=:set_$sf";
            if( $sn<count( $data ) - 1 ) $sql.=', ';
        }

        $sql.=' WHERE ';

        foreach( array_keys( $where ) as $wn=>$wf ) {
            $bind[':where_' . $wf ]=$where[$wf];
            $sql.="`$wf`=:where_$wf";
            if( $wn<count( $where ) - 1 ) $sql.=' AND ';
        }

        $sql.=';';

        return $this->run( $sql, $bind );
    }

    public function delete( $table=null, $where=array() ) {
        if( empty( $table ) ) return false;
        if( empty( $where ) || ! is_array( $where ) ) return false;

        $bind=array();
        $sql="DELETE FROM `$table` WHERE ";

        foreach( array_keys( $where ) as $wn=>$wf ) {
            $bind[':' . $wf]=$where[$wf];
            $sql.="`$wf`=:$wf";
            if( $wn<count( $where ) - 1 ) $sql.=' AND ';
        }

        $sql.=';';

        return $this->run( $sql, $bind );
    }

    public function exists( $table=null, $where=array() ) {
        if( empty( $table ) ) return false;
        if( empty( $where ) || ! is_array( $where ) ) return false;

        $bind=array();
        $sql="SELECT COUNT(*) FROM `$table` WHERE ";

        foreach( array_keys( $where ) as $wn=>$wf ) {
            $bind[':' . $wf]=$where[$wf];
            $sql.="`$wf`=:$wf";
            if( $wn<count( $where ) - 1 ) $sql.=' AND ';
        }

        $sql.=';';
        $stmt=$this->run( $sql, $bind );

        if( ! is_object( $stmt ) ) {
            $this->result=false;
            return false;
        }

        $this->result=$stmt->fetchColumn();
        if( !empty( $this->result ) ) return true;
        return false;
    }

    public function get_all( $table=null, $where=array(), $fields='*' ) {
        return $this->get( $table, $where, $fields, 'all' );
    }

    public function get_row( $table=null, $where=array(), $fields='*' ) {
        return $this->get( $table, $where, $fields, 'row' );
    }

    public function get_col( $table=null, $where=array(), $fields='*' ) {
        return $this->get( $table, $where, $fields, 'col' );
    }

    public function get_var( $table=null, $where=array(), $field=null ) {
        return $this->get( $table, $where, $field, 'var' );
    }

    public function get( $table=null, $where=array(), $fields='*', $type='all' ) {
        if( empty( $table ) ) return false;
        if( empty( $where ) || ! is_array( $where ) ) $where='1';
        if( empty( $fields ) ) $fields = '*';

        $bind=array();

        if( is_array( $fields ) ) {
            $fields='`' . implode( '`,`', $fields ) . '`';
        }

        $sql="SELECT $fields FROM `$table` WHERE ";

        if( is_array( $where ) ) {
            foreach( array_keys( $where ) as $wn=>$wf ) {
                $bind[":" . $wf]=$where[$wf];
                $sql.="`$wf`=:$wf";
                if( $wn<count( $where ) - 1 ) $sql.=" AND ";
            }
        } else {
            $sql.=$where;
            $bind=null;
        }

        if( ! empty( $this->group_by ) ) $sql .= " GROUP BY $this->group_by";
        if( ! empty( $this->order_by ) ) $sql .= " ORDER BY $this->order_by";
        if( ! empty( $this->limit ) ) $sql .= " LIMIT $this->limit";

        $sql.=';';

        $stmt=$this->run( $sql, $bind );

        if( ! is_object( $stmt ) ) {
            $this->result=false;
            return false;
        }

        if( $type == 'var' ) {
            $this->result=$stmt->fetchColumn();
        } else if( $type == 'row' ) {
            $this->result=$stmt->fetch();
        } else if( $type == 'col' ) {
            $this->result=$stmt->fetchAll( PDO::FETCH_COLUMN, 0 );
        } else {
            $this->result=$stmt->fetchAll();
        }

        $this->num_rows=is_array( $this->result ) ? count( $this->result ) : 1;

        return $this->result;
    }

    public function get_count( $table=null, $where=array() ) {
        if( empty( $table ) ) return false;
        if( empty( $where ) || ! is_array( $where ) ) $where='1';

        $bind=array();

        $sql="SELECT COUNT(*) FROM `$table` WHERE ";

        if( is_array( $where ) ) {
            foreach( array_keys( $where ) as $wn=>$wf ) {
                $bind[":" . $wf]=$where[$wf];
                $sql.="`$wf`=:$wf";
                if( $wn<count( $where ) - 1 ) $sql.=" AND ";
            }
        } else {
            $sql.=$where;
            $bind=null;
        }

        $sql.=';';

        $stmt=$this->run( $sql, $bind );

        if( ! is_object( $stmt ) ) {
            $this->result=false;
            return false;
        }

        $this->result=$stmt->fetchColumn();
        return (int) $this->result;
    }

    public function debug() {

        echo '<pre>';
        if( !empty( $this->sql ) ) {
            echo "Last Query: \r\n$this->sql \r\n\r\n";
            if( !empty( $this->error ) ) echo "Error Catched: \r\n$this->error \r\n\r\n";
            if( !empty( $this->num_rows ) ) echo "Number of Rows: \r\n$this->num_rows \r\n\r\n";
            if( !empty( $this->insert_id ) ) echo "Last Insert ID: \r\n$this->insert_id \r\n\r\n";
            echo "Last Result: \r\n";
                var_dump( $this->result );
        } else {
            echo "No executed query to show!.\r\n";
        }
        echo '</pre>';
    }

    private function flush() {
        $this->order_by  = null;
        $this->group_by  = null;
        $this->limit     = null;
        $this->result    = null;
        $this->error     = null;
        $this->insert_id = null;
        $this->num_rows  = 0;
    }
}
