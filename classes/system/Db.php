<?php
class Db
{
    private static $_instance = null;
    private $_pdo, $_query, $_error = false, $_results, $_count = 0, $_conn;

    public function __construct()
    {
        try {
            $this->_pdo = new PDO("mysql:host=" . Config::get("mysql/host") . ";dbname=" . Config::get("mysql/db"), Config::get("mysql/username"), Config::get("mysql/password"));
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    public function connect()
    {
        $this->_pdo->setAttribute(PDO::ERRMODE_EXCEPTION, PDO::ATTR_ERRMODE);
        return $this->_pdo;
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Db();
        }
        return self::$_instance;
    }

    public function query($sql, $params = array())
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->_query->execute()) {
                // $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                // $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    public function action($action, $table, $where = array())
    {
        if (count($where) === 3) {
            $operators = array("=", "<", ">", "<=", ">=", "!=");

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        if (!count($where)) {
            $sql = "{$action} FROM {$table}";
            if ($this->query($sql)) {
                return $this;
            }
        }
        return false;
    }

    public function get($table, $where)
    {
        $this->action("SELECT *", $table, $where);
        if ($this->_query->execute()) {
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
        } else {
            $this->_error = true;
        }
        return $this;
    }
    public function all($table, $fields = array())
    {
        $this->action("SELECT *", $table, $fields);
        if ($this->_query->execute()) {
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
        } else {
            $this->_error = true;
        }
        return $this;
    }
    public function delete($table, $where = array())
    {
        return $this->action("DELETE", $table, $where);
    }
    public function insert($table, $fields = array())
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = ' ';
            $x = 1;
            foreach ($fields as $field) {
                $values .= '?';
                if ($x < count($fields)) {
                    $values .= ',';
                }
                $x++;
            }
            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";
            if (!$this->query($sql, $fields, "Insert")->error()) {
                return true;
            }
        }
        return false;
    }

    public function update($table, $id, $fields)
    {
        $set = "";
        $x = 1;
        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function results()
    {
        return $this->_results;
    }
    public function first()
    {
        return $this->results()[0];
    }
    public function count()
    {
        return $this->_count;
    }
    public function error()
    {
        return $this->_error;
    }
}
