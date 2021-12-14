<?php
/**
 * class created to handle all database transactions
 */

class DB {
    private static $_instance = null;
    private $_pdo, // pdo instance
            $_query,
            $_error = false,
            $_results,
            $_count = 0;

    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    // get instance of DB class
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    // prepare and execute queries
    // $sql = sql query
    // params = query parameters
    // assigns results and row count to respective instance variables
    // return true if errors occurred else false
    public function query($sql, $params = array()) {
        $this->_error = false;

        if($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }

        return $this;
    }

    // assemble the action part of queries including where e.g WHERE id >= 10
    // $where = array of the action details
    // returns false if $where is not complete / has <3 elements Or the query encountered an error else:
    // returns value of query()
    public function action($action, $table, $where = array()) {
        if(count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }

        }

        return false;
    }

    // assembles insert queries
    // $table - the table where data is to be inserted
    // $fields - fields to bve altered
    // returns true if query->error is true else returns false meaning the insert failed
    public function insert($table, $fields = array()) {
        $keys = array_keys($fields);
        $values = null;
        $x = 1;

        foreach($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";//?

        if(!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    // assembles update queries
    // $table - table to be updated,
    // $id - id of the fields to be updated
    // $fields - fields to be updated
    // returns true if query->error is true else returns false meaning the update failed
    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count ($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if(!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    // starts the delete query 
    // returns values of action()
    public function delete($table, $where) {
        return $this->action('DELETE ', $table, $where);
    }

    // starts the delete query 
    // returns values of action()
    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);// select all statement
    }

    // returns results
    public function results() {
        return $this->_results;
    }

    // returns first result
    public function first() {
        $data = $this->results();
        return $data[0];
    }

    // returns row count
    public function count() {
        return $this->_count;
    }

    // returns errors
    public function error() {
        return $this->_error;
    }
}