<?php

namespace App;


/**
 * Class Model
 * @package App
 */
class Model
{
    private $pdo;
    private $table = 'list';
    private $delimiter = ';';
    private $enclosed = "\"";
    private $linesTerminated = "\r\n";

    /**
     * Model constructor.
     *
     * @param Database $pdo
     */
    public function __construct(Database $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $table
     *
     * @return Model
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param string $delimiter
     *
     * @return Model
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @param string $enclosed
     *
     * @return Model
     */
    public function setEnclosed($enclosed)
    {
        $this->enclosed = $enclosed;
        return $this;
    }

    /**
     * @param string $linesTerminated
     *
     * @return Model
     */
    public function setLinesTerminated($linesTerminated)
    {
        $this->linesTerminated = $linesTerminated;
        return $this;
    }

    /**
     * @param $id
     * @param $status
     */
    public function update($id, $status)
    {
        try {
            $sqlf = "UPDATE {$this->table} SET status = ? WHERE id = ?";
            $STHF = $this->pdo->prepare($sqlf);
            $STHF->execute([$status, $id]);
            $STHF = null;

        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     */
    public function getRandomRow()
    {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY RAND() LIMIT 1";
            $STH = $this->pdo->prepare($sql);
            $STH->execute();
            return $STH->fetch();

        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * @param string $path
     * @param string $character
     */
    public function generateTable($path, $character = 'cp1251')
    {
        if ($this->tableExists()) {
            return;
        }
        $this->createTable();
        $this->loadCsv($path, $character);
    }

    /**
     * @param string $path
     * @param string $character
     */
    protected function loadCsv($path, $character)
    {
        try {
            $sql = "LOAD DATA LOCAL INFILE '$path' INTO TABLE {$this->table} CHARACTER SET $character FIELDS TERMINATED BY '{$this->delimiter}' ENCLOSED BY '{$this->enclosed}' LINES TERMINATED BY '{$this->linesTerminated}' IGNORE 1 LINES (name, status)";
            $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            throw $e;
        }
    }


    protected function createTable()
    {
        try {
            $sql = "CREATE TABLE {$this->table} (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, name VARCHAR(100) NOT NULL, status TINYINT NOT NULL, PRIMARY KEY (id)) COLLATE='utf8_general_ci' ENGINE=MyISAM;";
            $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return bool
     */
    protected function tableExists()
    {
        try {
            $result = $this->pdo->query("SELECT 1 FROM {$this->table} LIMIT 1");
        } catch (\PDOException $e) {
            return false;
        }

        return $result !== false;
    }
}