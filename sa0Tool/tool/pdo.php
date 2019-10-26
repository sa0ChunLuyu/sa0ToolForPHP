<?php

/***
 * @author: sa0ChunLuyu
 * @time: 2019-10-26 09:42 上午
 */
class pdoController
{
    /**
     * @var PDO
     */
    public $pdo;

    final function __construct()
    {
        $dbConfig = APP_CONFIG['db'];
        try {
            $this->pdo = new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbName'] . ';port=' . $dbConfig['port'], $dbConfig['user'], $dbConfig['password']);
            $this->pdo->exec('set names utf8');
        } catch (PDOException $e) {
            echo '数据库连接失败 ' . $e->getMessage();
            exit();
        }
    }

    final function getWhere($where)
    {
        if (empty($where)) return null;
        $return = array(' where ' . $where[0] . ' ', $where[1]);
        return $return;
    }

    final function createRow($tableName, $data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }
        $sql = "insert into $tableName (";
        $fields = array();
        $placeHolder = array();
        $insertData = array();
        foreach ($data as $k => $v) {
            $fields[] = "$k";
            $placeHolder[] = "?";
            $insertData[] = $v;
        }
        $sql .= implode(', ', $fields) . ') values (' . implode(', ', $placeHolder) . ');';
        $pretreatment = $this->pdo->prepare($sql);
        $pretreatment->execute($insertData);
        return $this->pdo->lastInsertId();
    }

    final function deleteRow($tableName, $whereData)
    {
        if (empty($whereData)) exit();
        $where = $this->getWhere($whereData);
        $sql = "delete from $tableName {$where[0]};";
        $pretreatment = $this->pdo->prepare($sql);
        return $pretreatment->execute($where[1]);
    }

    final function updateRow($tableName, $whereData, $data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }
        $where = $this->getWhere($whereData);
        $sql = "update {$tableName} set ";
        $updateData = array();
        foreach ($data as $k => $v) {
            $sql .= "$k = ?, ";
            $updateData[] = $v;
        }
        $sql = substr($sql, 0, -2) . $where[0] . ';';
        $updateData = array_merge($updateData, $where[1]);
        $pretreatment = $this->pdo->prepare($sql);
        return $pretreatment->execute($updateData);
    }

    final function readRow($tableName, $whereData, $rows = false)
    {
        if (empty($whereData)) exit();
        $where = $this->getWhere($whereData);
        $sql = "select * from $tableName {$where[0]};";
        $pretreatment = $this->pdo->prepare($sql);
        $pretreatment->execute($where[1]);
        if ($rows) {
            return $pretreatment->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return $pretreatment->fetch(\PDO::FETCH_ASSOC);
        }
    }

    final function doSql($sql, $data)
    {
        $pretreatment = $this->pdo->prepare($sql);
        $pretreatment->execute($data);
        return $pretreatment->fetchAll(\PDO::FETCH_ASSOC);
    }

    final function pdoClose()
    {
        $this->pdo = null;
    }
}