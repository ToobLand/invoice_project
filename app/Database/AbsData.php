<?php

namespace App\Database;

use App\Database\Mysql;

#[\AllowDynamicProperties]
abstract class AbsData
{
    // every class needs a 'fields' property. 
    // it is an array of all the columns of the table (object) with additional info how to validate/sanitize/encrypt the data.
    // this abstract class will handle all standard CRUD stuff with the database for all the classes.
    // and will automatically add related objects if there is a id_"relatedObject" present.

    public function __construct($id = 0)
    {
        unset($this->fields);
        unset($this->table);
        $this->load_obect($id);
    }
    protected function load_obect($id = 0)
    {
        if ($id > 0) {
            // id given, get values from database and fill object with valus
            $conn = new Mysql();
            $where = '';
            $values = [$id];
            if ($this->check_field('id_user', get_class($this)) && get_class($this) != 'App\Models\Keeplogin') {
                $where = " AND id_user=? ";
                $values[] = $_SESSION['user']->id;
            }
            $result = $conn->fetchData("SELECT * FROM `{$this->table}` WHERE id = ? {$where} LIMIT 1", $values);
            if ($this->exist_fields(get_class($this))) {
                $fields = $this->get_fields();
                foreach ($result[0] as $key => $val) {
                    if ($this->check_field($key, get_class($this))) {
                        $this->$key = $val;
                    }
                }
            } else {
                trigger_error("__construct: 'Fields' property doesn't exist in class", E_USER_ERROR);
                exit;
            }
        } else {
            // empty new object
            if ($this->exist_fields(get_class($this))) {
                $fields = $this->get_fields();
                foreach ($fields as $key => $val) {
                    $this->$key = $val['default'];
                }
            }
        }
        return true;
    }

    public function __set($name, $value)
    {

        if ($this->exist_fields(get_class($this))) {
            if ($this->check_field($name, get_class($this))) {
                // to do, sanitize values based on type
                $this->$name = $value;
            } else {
                // not in object but can be a related class
                if ($this->check_related($name)) {
                    $this->$name = $value;
                } else {
                    trigger_error("__set: Property '" . print_r($name, true) . "' doesn't exist ", E_USER_ERROR);
                    exit;
                }
            }
        } else {
            trigger_error("__set: No fields", E_USER_ERROR);
            exit;
        }

        return true;
    }

    public function __get($name)
    {
        $res = false;
        if ($name == 'table') {
            // $classname= 'App\Models\\' . ucfirst($this->table);
            $reflector = new \ReflectionClass(get_class($this));
            $properties = $reflector->getDefaultProperties();
            return $properties[$name];
        }
        if ($this->exist_fields(get_class($this))) {
            if ($this->check_field($name, get_class($this))) {
                if (isset($this->$name)) {
                    $res = $this->$name;
                } else {
                    $res = $this->$name = $this->default_field($name);
                }
            } else {
                if ($this->check_related($name)) {
                    $this->$name = $this->get_related($name);
                    $res = $this->$name;
                } else {
                    trigger_error("__get: Property '" . print_r($name, true) . "' doesn't exist", E_USER_ERROR);
                    exit;
                }
            }
        } else {
            trigger_error("__get: No fields", E_USER_ERROR);
            exit;
        }
        return $res;
    }

    function get_fields()
    {
        $name = get_class($this);
        $reflector = new \ReflectionClass($name);
        $properties = $reflector->getDefaultProperties();
        return $properties['fields'];
    }
    protected function exist_fields($obj)
    {
        $reflector = new \ReflectionClass($obj);
        $properties = $reflector->getDefaultProperties();
        return isset($properties['fields']);
    }
    protected function check_field($var, $obj)
    {
        $reflector = new \ReflectionClass($obj);
        $properties = $reflector->getDefaultProperties();
        if ($var === 'date_created' || $var === 'date_updated') {
            return true;
        }
        return isset($properties['fields'][$var]);
    }
    protected function default_field($var)
    {
        $reflector = new \ReflectionClass(get_class($this));
        $properties = $reflector->getDefaultProperties();
        return $properties['fields'][$var]['default'];
    }
    protected function check_related($name)
    {
        $classname = 'App\Models\\' . ucfirst($name);
        if (class_exists($classname)) {
            $related_class = new $classname();

            if ($this->exist_fields(get_class($related_class))) {
                if ($this->check_field("id_" . $this->table, get_class($related_class))) {
                    return true;
                } else if ($this->check_field("id_" . $related_class->table, get_class($this))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    protected function get_related($name)
    {
        $classname = 'App\Models\\' . ucfirst($name);
        if (class_exists($classname)) {
            $related_class = new $classname();
            if ($this->exist_fields(get_class($related_class))) {
                if ($this->check_field("id_" . $this->table, get_class($related_class))) {
                    $conn = new Mysql();
                    $result = $conn->fetchData("SELECT id FROM `{$related_class->table}` WHERE id_" . $this->table . "={$this->id} ");
                    $array = [];
                    if (count($result) > 0) {
                        foreach ($result as $res) {
                            $array[] = new $classname($res['id']);
                        }
                    }
                    return $array;
                } else if ($this->check_field("id_" . $related_class->table, get_class($this))) {
                    if ($this->{"id_" . $related_class->table} > 0) {
                        $related_class = new $classname($this->{"id_" . $related_class->table});
                    }
                    return $related_class;
                } else {
                    trigger_error("get_related: class doesn't exist", E_USER_ERROR);
                    exit;
                }
            } else {
                trigger_error("get_related: class doesn't exist", E_USER_ERROR);
                exit;
            }
        } else {
            trigger_error("get_related: class doesn't exist", E_USER_ERROR);
            exit;
        }
    }

    public function save()
    {
        if ($this->id > 0) {
            /////////////
            // update ///
            /////////////
            if ($this->exist_fields(get_class($this))) {
                $fields = $this->get_fields();
                $questionmarks = [];
                $values = [];
                $keys = [];
                $query = '';

                $query .= "UPDATE `{$this->table}` SET ";
                foreach ($fields as $key => $val) {
                  
                    if ($key == 'id') {
                        continue;
                    } // don't update id, use it for where
                    if ($key == 'password' && $this->$key == '') {
                        continue;
                    } // don't update password if nothing changed. (with init its emptied)
                    $this->$key = $this->validate($val, $this->$key, $key);
                    $this->$key = $this->sanitize($val, $this->$key);
                    $values[] = $this->$key;
                    $keys[] = $key . " = ? ";
                }
                $keys[] = "date_updated = ? ";
                $values[] = date('Y-m-d H:i:s', time());

                $values[] = $this->id;
                $query .= " " . implode(', ', $keys) . " WHERE id = ?";
                $conn = new Mysql();
                $conn->save_query($query, $values);
                return true;
            }
        } else {
            /////////////////
            // insert into //
            /////////////////
            if ($this->exist_fields(get_class($this))) {
                $fields = $this->get_fields();
                $questionmarks = [];
                $values = [];
                $keys = [];
                $query = '';
                $query .= "INSERT INTO `{$this->table}` ";
                foreach ($fields as $key => $val) {
                    if ($key == 'id') {
                        continue;
                    }
                    // sanitize, validate 

                    $this->$key = $this->validate($val, $this->$key, $key);
                    $this->$key = $this->sanitize($val, $this->$key);
                    $questionmarks[] = '?';
                    $values[] = $this->$key;
                    $keys[] = $key;
                }
                $keys[] = "date_created";
                $keys[] = "date_updated";
                $questionmarks[] = '?';
                $questionmarks[] = '?';
                $values[] = date('Y-m-d H:i:s', time());
                $values[] = date('Y-m-d H:i:s', time());
                $query .= " (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $questionmarks) . ")";
                $conn = new Mysql();
                $new_id = $conn->save_query($query, $values);
                $this->id = $new_id;
                return true;
            }
        }
    }
    public function validate($field, $value, $key)
    {
        if ($field['required'] == true && ($value === '' || $value === 0) && $key != 'id_user') {
            trigger_error("$key is a required property", E_USER_ERROR);
            exit;
        } elseif ($key == 'id_user' && ($value == '' || $value == 0)) {
            if ($_SESSION['loggedin']) {
                return $_SESSION['user']->id;
            }
        }
        return $value;
    }
    public function sanitize($field, $value)
    {
        switch ($field['type']) {
            case 'string':
            case 'text':
            case 'varchar':
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                break;
            case 'int':
            case 'number':
                if (is_numeric($value)) {
                    $value = (int)$value;
                } else {
                    trigger_error("{$value} needs to be a valid Integer.", E_USER_ERROR);
                    exit;
                }
                break;
            case 'float':
            case 'decimal':
                $value = str_replace(",", ".", $value);
                $value = preg_replace('/\.(?=.*\.)/', '', $value);
                if (is_numeric($value)) {
                    $value = number_format((float)$value, 2, '.', '');
                } else {
                    trigger_error("{$value} needs to be a valid Float (number).", E_USER_ERROR);
                    exit;
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    trigger_error("{$value} needs to be a valid email adress.", E_USER_ERROR);
                    exit;
                }
                break;
            case 'date':
            case 'datetime':
                if (is_numeric($value)) {
                    $value = date('Y-m-d H:i:s', $value);
                } else {
                    $timestamp = strtotime($value);
                    if ($timestamp && is_numeric($timestamp)) {
                        $value = date('Y-m-d H:i:s', $timestamp);
                    } else {
                        trigger_error("{$value} needs to be a valid TIMESTAMP.", E_USER_ERROR);
                        exit;
                    }
                }

                break;
            default:
                trigger_error('No sanitizing in place for this "type" , so abort action');
                exit;
                break;
        }
        switch ($field['encrypt']) {
            case '3':
                $value = password_hash($value, PASSWORD_DEFAULT);
                break;
            case '2':
            case '1':
                // to do//
                break;
        }
        return $value;
    }

    public function get_all($order_by = false)
    {
        $conn = new Mysql();
        // if id_user exists in object. Use session user id. to add the where condition. (prevent leaks between users)
        $where = '';
        if ($this->check_field('id_user', get_class($this))) {
            $where = " WHERE id_user={$_SESSION['user']->id} ";
        }
        $order = '';
        if ($order_by) {
            $order = " ORDER BY `$order_by` ASC";
        }
        $result = $conn->fetchData("SELECT * FROM `{$this->table}` {$where} {$order}");
        if ($this->exist_fields(get_class($this))) {
            $array = [];
            $classname = 'App\Models\\' . ucfirst($this->table);
            $fields = $this->get_fields();
            foreach ($result as $res) {
                // build object with query data instead of loading each object individually, lots of queries with a lot of rows
                $tmp_obj = new $classname();
                $fields['date_created'] = 'dummy content'; // not in fields array, always added automatically, but need those keys also in the loop.
                $fields['date_updated'] = 'dummy content';
                foreach ($fields as $key => $val) {
                    $tmp_obj->$key = $res[$key];
                }

                $array[] = $tmp_obj;
                unset($tmp_obj);
            }
            return $array;
        } else {
            trigger_error("__construct: 'Fields' property doesn't exist in class", E_USER_ERROR);
            exit;
        }
    }
    public function get_customer_offer($id_customer, $id_offer)
    {
        $conn = new Mysql();

        $result = $conn->fetchData("SELECT * FROM `{$this->table}` WHERE id_customer=? AND id_offer=? AND id_user=?", [$id_customer, $id_offer, $_SESSION['user']->id]);
        if ($this->exist_fields(get_class($this))) {
            $array = [];
            $classname = 'App\Models\\' . ucfirst($this->table);
            $fields = $this->get_fields();
            foreach ($result as $res) {
                $tmp_obj = new $classname();
                foreach ($fields as $key => $val) {
                    $tmp_obj->$key = $res[$key];
                }

                $array[] = $tmp_obj;
                unset($tmp_obj);
            }
            return $array;
        } else {
            trigger_error("__construct: 'Fields' property doesn't exist in class", E_USER_ERROR);
            exit;
        }
    }
    public function get_from_till($from = 0, $till = 0, $column = 'date_created', $order_by = false)
    {
        $from = date('Y-m-d H:i:s', $from);
        $till = date('Y-m-d 23:59:59', $till);
        $conn = new Mysql();
        $where = '';
        if ($this->check_field('id_user', get_class($this))) {
            $where = " AND id_user={$_SESSION['user']->id} ";
        }
        $order = '';
        if ($order_by) {
            $order = " ORDER BY `$order_by` ASC";
        }
        $result = $conn->fetchData("SELECT * FROM `{$this->table}` WHERE `{$column}` >= '{$from}' AND `{$column}` <= '{$till}' {$where} {$order}");
        if ($this->exist_fields(get_class($this))) {
            $array = [];
            $classname = 'App\Models\\' . ucfirst($this->table);
            $fields = $this->get_fields();
            foreach ($result as $res) {
                $tmp_obj = new $classname();
                foreach ($fields as $key => $val) {
                    $tmp_obj->$key = $res[$key];
                }

                $array[] = $tmp_obj;
                unset($tmp_obj);
            }
            return $array;
        } else {
            trigger_error("__construct: 'Fields' property doesn't exist in class", E_USER_ERROR);
            exit;
        }
    }
    public function delete($force = false)
    {
        if ($this->id > 0) {
            if ($this->exist_fields(get_class($this))) {
                $conn = new Mysql();

                // IF id_user exists in Object. Make sure user only is able to delete own rows. 
                $where = '';
                $values = [$this->id];
                if ($this->check_field('id_user', get_class($this))) {
                    $where = " AND id_user=? ";
                    $values[] = $_SESSION['user']->id;
                }
                $query = "DELETE FROM `{$this->table}` WHERE id=? {$where}";
                try { 
                    $rRes = $conn->exec_query($query, $values);
                    return $rRes;
                } catch (Exception $e) { 
                    trigger_error("mysql Error: " . $e->getMessage() . "\n" . $query, E_USER_ERROR);
                }
            }
        }
    }
}
