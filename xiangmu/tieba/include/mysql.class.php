<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/21
 * Time: 15:38
 */
class mysql extends db{
    private static $ins = null;
    private $conn = null;
    private $conf = [];

    protected function __construct()
    {
        $this->conf = conf::getIns();
        $this->connect($this->conf->host,$this->conf->user,$this->conf->pwd);
        $this->select_db($this->conf->db);
        $this->setChar($this->conf->char);
    }
    public  function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
    public static function getIns() {
        if(self::$ins == false) {
            self::$ins = new self();
        }
        return self::$ins;
}
     public function connect($h, $u, $p)
     {
         // TODO: Implement connect() method.

         $this->conn = mysqli_connect($h,$p,$u);
         if(!$this->conn) {
             $err = new Exception('链接失败');
             throw $err;
         }
     }

     public function select_db($db) {
        $sql = 'use'.$db;
        $this->query($sql);
     }
     public function serChar($char) {
        $sql = 'set names '.$char;
         $this->query($sql);
     }
     public function query($sql) {
        if($this->conf->debug) {
            $this->log($sql);
        }
        $rs = mysqli_query($sql,$this->conn);
        if(!$rs) {
            $this->log($this->error());
        }
        return $rs;
     }
     public function autoExecute($arr,$table, $mode='insert',  $where = 'where 1 limit 1')
     {
         // TODO: Implement autoExecute() method.
         if(!is_array($arr)) {
             return false;
         }
         if($mode == 'update') {
             $sql = 'update '.$table.' set';
             foreach ($arr as $k=>$v) {
                 $sql.=$k."='".$v."'.";
             }
             $sql = rtrim($sql,',');
             $sql .= $where;
             return $this->query($sql);
         }
         $sql = 'insert into '.$table.'('.implode(',',array_keys($arr)).')';
         $sql .= 'values(\'';
         $sql .= implode("','",array_values($arr));
         $sql .='\')';
         return $this->query($sql);

     }
     public function getAll($sql) {
        $rs = $this->query($sql);
        $list = [];
        while($row = mysqli_fetch_assoc($rs)) {
            $list[] = $row;
        }
        return $list;
     }

     public function getRow($sql) {
        $rs = $this->query($sql);
        return mysqli_fetch_assoc($rs);
     }
    public function getOne($sql) {
        $rs = $this->query($sql);
        return mysqli_fetch_assoc($rs);
    }
    public function affected_rows() {
        return mysqli_stmt_fetch($this->conn);
    }
    public function insert_id() {
        return mysqli_insert_id($this->conn);
    }

}