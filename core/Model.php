<?php

namespace Nano;

/**
 * Base model
 */
abstract class Model {

    protected static $_pk;

    /**
     * Get all of the rows in the table
     *
     * @return array
     */
    public static function all() {
        $table = static::$_table;
        $instance = Database::getInstance();

        $statement = $instance->prepare("SELECT * FROM {$table}");
        $statement->execute();
        $data = $statement->fetchAll(\PDO::FETCH_CLASS, get_called_class());

        return $data;
    }

    /**
     * Get model from table by identifier
     *
     * @param mixed $id
     * @return array
     */
    public static function get($id) {
        $table = static::$_table; // Get table
        $pk = static::getPrimaryKey(); // Get primary key
        $instance = Database::getInstance();

        $statement = $instance->prepare("SELECT * FROM {$table} WHERE {$pk} = :{$pk}");
        $statement->execute(array($pk => $id));
        $data = $statement->fetchObject(get_called_class());

        return $data;
    }

    /**
     * Get model from table by id
     *
     * @param integer $id
     * @return array
     */
    public static function getById($id) {
        $table = static::$_table; // Get table
        $instance = Database::getInstance();

        $id = intval($id);
        $statement = $instance->prepare("SELECT * FROM {$table} WHERE id = :id");
        $statement->execute(array('id' => $id));
        $data = $statement->fetchObject(get_called_class());

        return $data;
    }

    /**
     * Save the model to the database
     *
     * @param array $data
     * @return void
     */
    public function save() {
        $pk = static::getPrimaryKey(); // Get primary key
        $data = self::objectToArray($this);

        if(!isset($data[$pk])) {
            $ret = call_user_func_array(array(get_called_class(), 'insert'), array($data));
        } else {
            $ret = call_user_func_array(array(get_called_class(), 'update'), array($data));
        }
        foreach($ret as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Save the model to the database
     *
     * @param array $data
     * @return void
     */
    public static function _save($data) {
        $pk = static::getPrimaryKey(); // Get primary key
        $data = self::objectToArray($this);

        if(!isset($data[$pk])) {
            $ret = self::insert($data);
        } else {
            $ret = self::update($data);
        }
        return $ret;
    }

    /**
     * Custom check for when using the Model statically. Allows us to use same
     * method name multiple times.
     *
     * @param string $name
     * @param array $args
     * @return void
     */
    public static function __callStatic($name, $args) {
        if(method_exists(get_called_class(), '_' . $name)) {
            $name = '_' . $name;
        }
        return call_user_func_array(array(get_called_class(), $name), $args);
    }

    /**
     * Insert model into database
     *
     * @param array $data
     * @return void
     */
    public static function insert($data) {
        $table = static::$_table; // Get the table
        $instance = Database::getInstance();

        $keys = array_keys($data);
        $prefixed_keys = preg_filter('/^/', ':', $keys);
        $columns = implode(', ', $keys);
        $values = implode(', ', $prefixed_keys);

        $statement = $instance->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$values})");
        if($statement->execute($data)) {
            $id = $instance->lastInsertId();
            return self::getById($id);
        }
        return false;
    }

    /**
     * Update model in database
     *
     * @param array $data
     * @return void
     */
    public static function update($data) {
        $table = static::$_table; // Get the table
        $pk = static::getPrimaryKey(); // Get primary key
        $instance = Database::getInstance();

        $set_data = array();
        foreach($data as $key => $value) {
            $set_data[] = $key . ' = :' . $key;
        }
        $set_str = implode(', ', $set_data);

        $statement = $instance->prepare("UPDATE {$table} SET $set_str WHERE {$pk} = :{$pk}");
        if($statement->execute($data)) {
            return self::get($data[$pk]);
        }
        return false;
    }

    /**
     * Gets the primary key of model's table
     *
     * @return void
     */
    public static function getPrimaryKey() {
        if(self::$_pk === null) {
            $table = static::$_table; // Get the table
            $instance = Database::getInstance();

            $statement = $instance->prepare("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'");
            if($statement->execute()) {
                $data = $statement->fetch();
                if(!isset($data->Column_name)) {
                    throw new \Exception("Unable to fetch primary key for '{$table}'");
                }
                self::$_pk = $data->Column_name;
            }
        }
        return self::$_pk;
    }

    /**
     * Turn the object into an array for easy usage
     *
     * @param object $data
     * @return void
     */
    protected function objectToArray($data) {
        if (is_object($data)) {
            return get_object_vars($data);
        }
        return $data;
    }

}