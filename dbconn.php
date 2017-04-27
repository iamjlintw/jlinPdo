<?php

/**
 * PDO  connect example class
 *
 *
 */
class dbconn
{
    public $db;
    public $flg; //type for environment

    /**
     * select your environment
     * if you have .dev file ,it will be dev server connect ,if you have not .dev file, will get live server
     * setting
     */
    public function __construct()
    {

        if (is_file(".dev")) {
            //if.env go dev
            $ary = parse_ini_file('.dev', TRUE);
            $this->flg = "dev";

        } else {
            #live
            $ary = parse_ini_file('.live', TRUE);
            $this->flg = "live";
        }

        $dbhost = $ary['db']['dbhost'];
        $dbuser = $ary['db']['dbuser'];
        $dbpwd = $ary ['db']['dbpass'];
        $deselect = $ary ['db']['dbselect'];
        $dbconnect = "mysql:host=" . $dbhost . ";dbname=" . $deselect;

        try {
            $this->pdo = new PDO($dbconnect, $dbuser, $dbpwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::MYSQL_ATTR_LOCAL_INFILE => TRUE));
        } catch (PDOException $e) {

            echo "Connection failed: " . $e->getMessage();
        }
    }

    //for query ,find something
    //default TYPE FETCH_ASSOC
    public function rawsqlByFetch($sql, $type = "assoc")
    {

        try {
            $query = $this->pdo->query($sql);
            if (!$query) {
                $err = $this->pdo->errorInfo();
                if (is_array($err)) {
                    die("Execute query error, because: " . $err[2]);
                } else {
                    die("Execute query error, because: " . $this->pdo->errorInfo());
                }

            } else {
                if ($type == "assoc") {
                    $datalist = $query->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $datalist = $query->fetchAll(PDO::FETCH_NUM);
                }

                return $datalist;
            }


        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    public function rawsqlSingle($sql)
    {

        try {
            $query = $this->pdo->query($sql);
            if (!$query) {
                $err = $this->pdo->errorInfo();
                if (is_array($err)) {
                    die("Execute query error, because: " . $err[2]);
                } else {
                    die("Execute query error, because: " . $this->pdo->errorInfo());
                }

            } else {
                $datalist = $query->fetch(PDO::FETCH_ASSOC);
                return $datalist;
            }


        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    public function rawsqlByrow($sql)
    {//PDO::FETCH_COLUMN, 0
        try {
            $query = $this->pdo->query($sql);
            if (!$query) {
                $err = $this->pdo->errorInfo();
                if (is_array($err)) {
                    die("Execute query error, because: " . $err[2]);
                } else {
                    die("Execute query error, because: " . $this->pdo->errorInfo());
                }

            } else {
                $datalist = $query->fetchAll(PDO::FETCH_COLUMN, 0);
                return $datalist;
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    //find single row by condition
    public function find($sql, $array)
    {

        $datalist = array();
        try {
            $sth = $this->pdo->prepare($sql);
            if (!$sth) {
                $err = $this->pdo->errorInfo();
                if (is_array($err)) {
                    die("Execute query error, because: " . $err[2]);
                } else {
                    die("Execute query error, because: " . $this->pdo->errorInfo());
                }

            } else {
                if ($sth->execute($array)) {

                    return $sth->fetch();
                } else {

                    echo "Unable to read record:" . print_r($sth->errorInfo(), true);
                    exit;
                }

            }

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    //find many rows by condition
    public function findAll($sql, $array)
    {
        try {
            $sth = $this->pdo->prepare($sql);

            if (!$sth) {
                $err = $this->pdo->errorInfo();
                if (is_array($err)) {
                    die("Execute query error, because: " . $err[2]);
                } else {
                    die("Execute query error, because: " . $this->pdo->errorInfo());
                }

            } else {
                if ($sth->execute($array)) {

                    return $sth->fetchAll();
                } else {

                    echo "Unable to read record:" . print_r($sth->errorInfo(), true);
                    exit;
                }
            }

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    //for exec sql ,without find something
    public function execsql($sql)
    {
        try {
            return $this->pdo->exec($sql);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    public function add($sql, $array)
    {
        try {

            $sth = $this->pdo->prepare($sql);

            if (!$sth) {
                $err = $this->pdo->errorInfo();
                if (is_array($err)) {
                    die("Execute query error, because: " . $err[2]);
                } else {
                    die("Execute query error, because: " . $this->pdo->errorInfo());
                }

            } else {
                $d = $sth->execute($array);
            }


        } catch (PDOException $e) {
            echo "xxx";
            echo "Connection failed: " . $e->getMessage();
        }
    }

}//end class


?>