<?php
class Database
{
    private $host = 'localhost';
    private $user = 'root';
    private $data_base = 'test1';
    private $pass = '';
    private $data_base_type = 'mysql';

    private $connection = null;

    function __construct()
    {
        $param_string = $this->data_base_type.':host='.$this->host.';dbname='.$this->data_base;
        $this->connection = new PDO($param_string, $this->user, $this->pass);
    }

    function query($q)
    {
        $this->connection->query($q);
    }

    function select($q)
    {
        return $this->connection->query($q)->fetchAll(PDO::FETCH_ASSOC);
    }
 }