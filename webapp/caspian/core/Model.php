<?php

namespace Caspian\Core\Database;

class Model {

    /**
     * this is an instance of DatabaseTools class that allow to us write own method for manipulating to database
     * @var \Caspian\Core\Database\DatabaseTools
     */
    public $db;

    /**
     * this variable is super global variable of PDO object that allows you after new instance of Model class access to 
     * PDO object for working with database by PDO object.
     * @var \PDO
     */
    static $sg_pdo;

    /**
     * local variable for carry database info. this info read from /config/database.php file
     * variables for connecting to database
     */
    private $user;
    private $password;
    private $host;
    private $database;

    /**
     * this task connect to database
     * 
     * @global 2D_array $db_data 
     */
    function __construct() {
        /**
         * this variable located in /config/database.php
         */
        global $db_data;

        /**
         * initialize requirement variables to connect to database
         */
        $this->database = $db_data['default']['database'];
        $this->host = "mysql:host={$db_data['default']['hostname']};dbname={$this->database};charset={$db_data['default']['char_set']}";
        $this->user = $db_data['default']['username'];
        $this->password = $db_data['default']['password'];

        /**
         * connect to database
         */
        try {
            $link = new \PDO($this->host, $this->user, $this->password);
            $link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            static::$sg_pdo = $link;
            
            
            
            
        } catch (\PDOException $e) {
            ob_clean();
            header('HTTP/1.1 500 Internal Server Error.', TRUE, 500);

            include ERROR_PAGES_PATH . '/db.php';
            \Caspian\Core\Request\Controller::get_instance()->load_log();
            \Caspian\Core\Request\Controller::get_instance()->log->write($e->getMessage() . ' ' . 'Database Error!', 1500);
            if (CAS_ENV === 'development') {
                echo '<pre>' . $e->getMessage() . '</pre>';
            }
            exit();
        }

        /**
         * an instance for access to methods that this methods can be work with database
         */
        $databse_handle = 'DatabaseTools';
        if (file_exists(THIRD_PARTY_PATH . '/' . $db_data['default']['handle'] . '.php') && isset($db_data['default']['handle'])) {
            $db_handle_name = $db_data['default']['handle'];
            include_once THIRD_PARTY_PATH . '/' . $db_handle_name . '.php';
            if (class_exists($db_handle_name)) {
                $databse_handle = $db_handle_name;
            }
        }
        $class_name = "Caspian\\Core\\Database\\" . $databse_handle;
        $this->db = new $class_name($link);
    }

}

final class DatabaseTools {

    /**
     * this is an instance of PDO class
     * @var \PDO $pdo
     */
    private $pdo;

    /**
     * an object from Model class. this object is a connection to database
     * @param object $link an instance of PDO
     */
    function __construct(&$link) {
        $this->pdo = $link;
    }

    /**
     * ***************************************************************************************************
     */
    // all method in defualt use AND operation if it is require!

    private $query_type = NULL;
    private $from = NULL;
    private $where = NULL;
    private $order_by = NULL;
    private $limit = NULL;
    private $offset = NULL;
    private $select = '*';
    private $like = NULL;
    private $in = NULL;
    private $not = NULL;
    private $perPage = 50;
    private $page = NULL;
    private $query_string = NULL;
    private $where_separator = 'AND';
    private $like_separator = 'AND';
    private $value = NULL;
    public $startTime = 0;
    public $endTime = 0;

    public function page($page, $perPage = '') {
        $this->page = $page;
        $this->perPage = !empty($perPage) ? $perPage : $this->perPage;
        return $this;
    }

    public function from($from) {
        $this->from = !empty($from) ? $from : $this->from;
        return $this;
    }

    public function order_by($column, $order = 'ASC') {
        if ($order != 'ASC' && $order != 'DESC') {
            $order = 'ASC';
        }
        if (is_array($column)) {
            $i = 1;
            foreach ($column as $item) {
                $str = ', ' . trim($item);
                if ($i == 1) {
                    $str = trim($item);
                }
                $this->$this->order_by .= $str;
                $i++;
            }
        } else {
            $this->order_by = !empty($this->order_by) ? ', ' . trim($column) . ' ' . $order : trim($column) . ' ' . $order;
        }

        return $this;
    }

    public function select($select) {

        if (is_array($select)) {
            $i = 1;
            $temp_select = '';
            if ($this->select != '*') {
                $i = 2;
                $temp_select = $this->select;
                $this->select = '';
            } else {
                $this->select = '';
            }
            foreach ($select as $item) {
                $str = ', ' . trim($item);
                if ($i == 1) {
                    $str = trim($item);
                }
                $this->select .= $str;
                $i++;
            }

            $this->select = $temp_select . $this->select;
        } else {

            if ($this->select == '*') {
                $this->select = $select;
            } else {
                $this->select = strlen($this->select) > 0 ? "$this->select, $select" : $this->select;
            }
        }

        return $this;
    }

    public function where_or($where, $value = '') {
        $this->where_separator = 'OR';
        $this->where_generator($where, $value);
        return $this;
    }

    public function where($where, $value = '') {
        $this->where_separator = 'AND';
        $this->where_generator($where, $value);
        return $this;
    }

    private function where_generator($where, $value = '') {
        if (is_array($where)) {
            $i = $this->where ? 2 : 1;
            foreach ($where as $item => $value) {
                $str = " $this->where_separator " . trim($item) . " = '" . trim($value) . "'";
                if ($i == 1) {
                    $str = trim($item) . " = '" . trim($value) . "'";
                    $i++;
                }
                $this->where .= $str;
            }
//            $this->where = !empty($temp_where) ? $temp_where . ' ' . $this->where : $this->where;
        } else {
            if ($this->where) {
                $where = trim($where);
                $value = trim($value);
                $str = !empty($value) ? " $this->where_separator $where = '$value'" : " $this->where_separator $where";
                $this->where = "$this->where" . $str;
            } else {
                $this->where = !empty($value) ? "$where = '$value'" : $where;
            }
        }
    }

    public function query($sql) {
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $this->reset_query_builder();
            return $statement;
        }
        $this->reset_query_builder();
        return NULL;
    }

    /**
     * 
     * @param type $table
     * @param type $limit
     * @param type $offset
     * @return \PDOStatement
     */
    public function get($table = '', $limit = '', $offset = '') {

        $this->query_type = 'GET';

        if ($table) {
            $this->from = trim($table);
            $this->limit = !empty($limit) ? $limit : $this->limit;
            $this->offset = !empty($offset) ? $this->limit($limit, $offset) : $this->offset;
        }

        $this->get_build();
        return $this->executeGet();
    }

    // rules of query builder and
    // generate query string for get method
    private function get_build() {
        $sql = "SELECT:select: FROM:from::where::like::order_by::limit:";

        $this->query_string = $sql;

        $this->replace_get_build();
    }

    /**
     * $this->get() method execute function
     */
    private function executeGet() {
        $this->startTime = time();
        $statement = $this->pdo->prepare($this->query_string);
        $statement->execute();
        if ($statement->rowCount() > 0) {

            $result = $statement;
            $this->endTime = time() - $this->startTime;
            $this->reset_query_builder();
            return $result;
        }
        $this->reset_query_builder();
        return NULL;
    }

    private function replace_get_build() {
        foreach ($this->getStatments as $statement => $holder) {
            $prefix = '';

            if ($holder == '_') {
                $this->check_unstatement($statement);
                continue;
            } else if (is_array($holder)) {

                $prefix = $this->getStatementDependency($statement);
                $replace = $prefix . $this->{$statement};
            } else if ($holder) {

                $replace = $this->{$statement} ? ' ' . $holder . ' ' . $this->{$statement} : '';
            } else {

                $replace = $this->{$statement} ? ' ' . $this->{$statement} : '';
            }

            $find = $statement;

            $this->query_string = str_replace(":$find:", $replace, $this->query_string);
        }
    }

    private $getStatments = [
        'select' => '',
        'from' => '',
        'where' => 'WHERE',
        'like' => ['WHERE'],
        'order_by' => 'ORDER BY',
        'page' => '_',
        'limit' => 'LIMIT',
    ];
    private $getDependency = [
        'like' => 'where',
    ];
    private $callbacks = [
        'page' => 'set_pagination'
    ];

    private function getStatementDependency($dependency) {

        foreach ($this->getDependency as $d => $to) {
            if ($d == $dependency) {
                // if where is set
                if ($this->{$to} && $this->{$d}) {
                    $where_s = $to . '_separator';
                    return ' ' . $this->{$where_s} . ' ';
                }
                // if just ${$d} is set
                if ($this->{$d}) {
                    return ' ' . $this->getStatments[$d][0] . ' ';
                }
            }
        }
        return FALSE;
    }

    private function check_unstatement($unstatement) {

        if ($this->{$unstatement}) {
            if (method_exists($this, $this->callbacks[$unstatement])) {
                $callbackName = $this->callbacks[$unstatement];
                $this->$callbackName();
                return TRUE;
            }
        }
        return FALSE;
    }

    private function set_pagination() {
        if (!$this->perPage) {
            $this->perPage = 10;
        }
        $limit = $this->page == 1 ? 0 : $this->page * $this->perPage - $this->perPage;
        $offset = $this->perPage;
        $this->limit($limit, $offset);
    }

    public function get_query_string() {
        return $this->query_string;
    }

    public function reset_query_builder() {
        $this->query_type = NULL;
        $this->from = NULL;
        $this->where = NULL;
        $this->order_by = NULL;
        $this->limit = NULL;
        $this->offset = NULL;
        $this->select = '*';
        $this->like = NULL;
        $this->in = NULL;
        $this->not = NULL;
        $this->perPage = 50;
        $this->page = NULL;
        $this->query_string = NULL;
        $this->where_separator = 'AND';
        $this->like_separator = 'AND';
        $this->value = NULL;
        $this->startTime = 0;
        $this->endTime = 0;
    }

    public function limit($limit, $offset = '') {

        if (is_array($limit)) {
            $this->limit = isset($limit[0]) ? $limit[0] : NULL;
            $this->limit = isset($limit[1]) ? $this->limit . ', ' . $limit[1] : $this->limit;
        } else {
            $this->limit = $limit;
            $this->limit = !empty($offset) ? $this->limit . ', ' . $offset : $this->limit;
        }

        return $this;
    }

    public function like($column, $match, $wild = 'both') {
        $this->like_separator = 'AND';
        $this->like_genarator($column, $match, $wild);
        return $this;
    }

    public function like_or($column, $match, $wild = 'both') {
        $this->like_separator = 'OR';
        $this->like_genarator($column, $match, $wild);
        return $this;
    }

    private function like_genarator($column, $match, $wild = 'both') {
        $str = empty($this->like) ? "$column LIKE ':wild:'" : " $this->like_separator $column LIKE ':wild:'";

        if ($wild == 'right') {
            $match = "$match%";
        } else if ($wild == 'left') {
            $match = "%$match";
        } else {
            $match = "%$match%";
        }

        $like_statement = str_replace(':wild:', $match, $str);
        $this->like = $this->like . $like_statement;
    }

    public function insert($table, $value) {
        $this->query_type = 'INSERT';
        // if all of parameters is empty
        // use class parameters

        if ($table && $value) {
            $this->from = trim($table);
            $this->value = '(';
        } else {
            return;
        }
        $i = 1;
        foreach ($value as $column => $v) {
            if ($i == 1) {
                $this->value .= trim($column);
                $i++;
            } else {
                $this->value .= ', ' . trim($column);
            }
        }
        $this->value .= ') VALUES (';

        $j = 1;
        foreach ($value as $column => $v) {
            if ($j == 1) {
                $this->value .= "'$v'";
                $j++;
            } else {
                $this->value .= ", '$v'";
            }
        }
        $this->value .= ')';
        $this->insertBuild();
        return $this->execute();
    }

    private function insertBuild() {
        $sql = "INSERT INTO :from: :value:";

        $this->query_string = $sql;

        $this->replaceInsertBuild();
    }

    private function replaceInsertBuild() {

        foreach (['from', 'value'] as $statement) {
            $this->query_string = str_replace(":$statement:", $this->{$statement}, $this->query_string);
        }
    }

    public function update($table = '', $value = '', $where = '') {
        $this->query_type = 'UPDATE';
        // if all of parameters is empty
        // use class parameters

        if ($table) {
            $this->from = trim($table);
            !empty($value) ? $this->value($value) : '';
            !empty($where) ? $this->where($where) : '';
        } else {
            if (!$this->from) {
                return NULL;
            }
        }

        if (!$this->where || !$this->value) {
            return NULL;
        }

        $this->updateBuild();
        return $this->execute();
    }

    private function updateBuild() {
        $sql = "UPDATE :from: SET :value: WHERE :where:";

        $this->query_string = $sql;

        $this->replaceUpdateBuild();
    }

    private function replaceUpdateBuild() {

        foreach (['from', 'value', 'where'] as $statement) {
            $this->query_string = str_replace(":$statement:", $this->{$statement}, $this->query_string);
        }
    }

    private function execute() {
        $result = $this->pdo->exec($this->query_string);
        $this->reset_query_builder();
        return $result;
    }

    public function value($value) {
        $this->value = '';
        $i = 1;
        foreach ($value as $column => $v) {
            if ($i == 1) {
                $this->value = "$column = '{$v}'";
                $i++;
            } else {
                $this->value .= ", $column = '{$v}'";
            }
        }
        return $this;
    }

    public function delete($table = '', $where = '') {
        if ($table) {
            $this->from = trim($table);
        }
        if ($where) {
            $this->where($where);
        }

        if (!$this->from || !$this->where) {
            return NULL;
        }

        $this->deleteBuild();
        return $this->execute();
    }

    private function deleteBuild() {
        $sql = "DELETE FROM :from: WHERE :where:";

        $this->query_string = $sql;

        $this->replaceDeleteBuild();
    }

    private function replaceDeleteBuild() {
        foreach (['from', 'where'] as $statement) {
            $this->quer_string = str_replace(":$statement:", $this->{$statement}, $this->query_string);
        }
    }

}
