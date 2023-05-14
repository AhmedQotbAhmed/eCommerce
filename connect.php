<?php

    $dsn = 'mysql:host=localhost;dbname=shop';
    $user = 'root';
    $pass = '';
    $option = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ];

    try {
        $con = new PDO($dsn, $user, $pass, $option);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Failed To Connect'.$e->getMessage();
    }

// File: init.php

class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'shop';
    private $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    protected $con;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        try {
            $this->con = new PDO(
                'mysql:host='.$this->host.';dbname='.$this->dbname,
                $this->username,
                $this->password,
                $this->options
            );
            $this->$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database connection error: '.$e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->con;
    }
}
