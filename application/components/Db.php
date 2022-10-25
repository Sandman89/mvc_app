<?php

namespace application\components;

use PDO;

class Db
{

    protected $db;

    public static $deleteQuery = 'DELETE FROM `%s` WHERE id=?';
    public static $insertQuery = 'INSERT INTO `%s` (%s) VALUES (%s)';
    public static $selectQuery = 'SELECT %s FROM `%s`';
    public static $selectByIdQuery = 'SELECT * FROM `%s` WHERE id=?'; //merge with other query?
    public static $updateQuery = 'UPDATE `%s` SET %s WHERE id=?';
    public static $lastIdQuery = 'SELECT LAST_INSERT_ID()';
    public static $setNamesQuery = 'SET NAMES %s';


    protected static function setDatabase(): PDO
    {
        $config = require 'application/config/db.php';
        return new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['name'] . '', $config['user'], $config['password']);
    }

    public static function buildSelectQuery($from, $select = '*', $where = [], &$args = [])
    {
        $query = sprintf(self::$selectQuery, $select, $from);
        if (!empty($where)) {
            $query .= self::buildWherePartQuery($where, $args);
        }
        return $query;
    }

    public static function buildWherePartQuery($params, &$whereValues = [])
    {
        $where = ' WHERE';

        foreach ($params as $column => $value) {
            $where .= ' ' . $column . '=? AND';
            $whereValues[] = $value;
        }

        return rtrim($where, 'AND'); //fix
    }

    public static function execute($query, $args = [], $returningData = false)
    {
        $pdo = self::setDatabase();
        $query = $pdo->prepare($query);

        foreach ($args as $index => $value) {
            $type = (gettype($value) == 'integer') ? PDO::PARAM_INT : PDO::FETCH_UNIQUE             ;
            $query->bindValue($index + 1, $value, $type);
        }

        try {
            $query->execute();
            //debug($query->debugDumpParams());
        } catch (\PDOException $e) {
            echo $e->getMessage() . "\n";
        }

        if ($returningData == 'single') {
            return $query->fetch(PDO::FETCH_ASSOC);
        } else if ($returningData == 'list') {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }


}