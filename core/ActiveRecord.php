<?php

namespace core;

use application\components\Db;

abstract class ActiveRecord
{
    /** @var int */
    protected $id;

    protected static $parents = [];
    protected static $childrens = [];

    private $fields = [];
    private $loaded = false;
    private $modified = false;


    public function getFields()
    {
        return $this->fields;
    }

    public static function getClass()
    {
        return get_called_class();
    }

    abstract protected static function getTableName(): string;

    public function __get($name)
    {
//        if ($this->modified) {
//            throw new InvalidOperationException;
//        }
        $getMethod = 'get' . ucfirst($name);
        if (method_exists($this, $getMethod)) {
            return $this->$getMethod();
        }

        $this->load();

        return $this->getValue($name);
    }

    public function __set($name, $value)
    {
        if (!empty($value)) {
            $value = str_replace("'", "''", $value);
        }
        $this->setValue($name, $value);
    }

    public function getValue($name)
    {
        $this->load();

        if ($name == 'id') {
            return $this->id;
        }
        if (!empty($this->fields[$name]))
            return $this->fields[$name];
        return null;
    }

    public function setValue($name, $value)
    {
        $this->fields[$name] = $value;
        $this->modified = true;
    }

    public function save()
    {
        if ($this->modified || !$this->id) {
            if (isset($this->id)) {
                $this->update();
            } else {
                $this->insert();
            }
        }

        $this->modified = false;
    }

    public function delete()
    {
        if ($this->id == NULL) {
            throw new InvalidOperationException;
        }
        $query = sprintf(self::$deleteQuery, static::getTableName());
        Db::execute($query, array($this->id));
    }

    public static function findOne($where = [])
    {

        $table = static::getTableName();
        $queryArgs = [];
        $query = Db::buildSelectQuery($table, '*', $where, $queryArgs);
        $params = Db::execute($query, $queryArgs, 'single');

        if (!$params) {
            return false;
        }
        return self::buildObject($params);
    }

    public static function count($where = [], $select = '*'): int
    {
        $countQuery = self::findQuery($where, $select, [], 'single');
        if (!empty(current($countQuery))) {
            return current($countQuery);
        }
        return 0;
    }

    public static function findQuery($where = [], $select = '*', $options = [], $type = 'list')
    {
        $table = static::getTableName();
        $queryArgs = [];

        $query = Db::buildSelectQuery($table, $select, $where, $queryArgs);
        if (!empty($options['sortedField'])) {
            $sortType = 'ASC';
            if (strpos($options['sortedField'], '-') !== false) {
                $sortType = 'DESC';
                $options['sortedField'] = str_replace('-', '', $options['sortedField']);
            }
            if (!in_array($options['sortedField'], $options['allowedSortedColumns'], true)) {
                throw new InvalidArgumentException("Invalid order by value");
            }
            $query .= sprintf(" ORDER BY %s %s ", $options['sortedField'], $sortType);
        }
        if (!empty($options['paginate'])) {
            $query .= ' LIMIT ?,?';
            foreach ($options['paginate'] as $key => $value) {
                $queryArgs[] = $value;
            }
        }
        return Db::execute($query, $queryArgs, $type);
    }

    public static function find($where = [], $select = '*')
    {
        $params = self::findQuery($where, $select);
        return self::buildObjectList($params);
    }

    private static function buildObject($params)
    {
        $class = get_called_class();

        $object = new $class;

        $object->id = $params['id'];
        $object->fields = $params;
        $object->loaded = true;

        return $object;
    }

    private static function buildObjectList($params)
    {
        $objList = [];

        foreach ($params as $paramsPack) {
            $objList[] = self::buildObject($paramsPack);
        }

        return $objList;
    }

    private function load(): bool
    {
        if ($this->id == NULL || $this->loaded) {
            return false;
        }

        $queryArgs = [];
        $query = Db::buildSelectQuery(static::getTableName(), '*', ['id' => $this->id], $queryArgs);
        $params = Db::execute($query, $queryArgs, 'single');

        foreach ($params as $column => $value) {
            $this->fields[$column] = $value;
        }

        $this->modified = false;
        $this->loaded = true;

        return true;
    }

    private function update()
    {
        $modifiedFieldsStr = '';

        foreach ($this->fields as $field => $value) {
            $modifiedFieldsStr .= $field . "='" . $value . "',";
        }
        $modifiedFieldsStr = rtrim($modifiedFieldsStr, ",");

        $query = sprintf(Db::$updateQuery, static::getTableName(), $modifiedFieldsStr);
        DB::execute($query, array($this->id));
    }

    private function insert()
    {
        $fieldsStr = '';
        $valuesStr = '';

        foreach ($this->fields as $field => $value) {
            $fieldsStr .= $field . ",";
            $valuesStr .= "'" . $value . "',";
        }
        $fieldsStr = rtrim($fieldsStr, ",");
        $valuesStr = rtrim($valuesStr, ",");

        $query = sprintf(
            Db::$insertQuery,
            static::getTableName(),
            $fieldsStr,
            $valuesStr
        );
        Db::execute($query);
        $this->id = Db::execute(Db::$lastIdQuery, [], 'single');
        $this->loaded = true;
    }

}