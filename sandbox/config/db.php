<?php

class DB
{
    private $host ="10.42.0.2";
    private $user = "sandbox";
    private $pass = "sandbox";
    private $dbName = "sandbox";

    public function connect(): PDO
    {
        $conn_str = "mysql:host=$this->host;dbname=$this->dbName";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
            ];
        return new PDO($conn_str,$this->user, $this->pass,$options);
    }
}