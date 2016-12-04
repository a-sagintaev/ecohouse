<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04.12.2016
 * Time: 11:46
 */
class MySQL
{
    private $host;
    private $username;
    private $password;
    private $db_name;

    public function __construct()
    {
        $this->host     = DB_HOST;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->db_name  = DB_NAME;

        $this->msqli = $this->connect();
        $this->query("SET NAMES utf8");
    }

    public function connect()
    {
        $msqli = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($msqli->connect_errno) {
            die("Connection failed: " . $msqli->connect_error);
        }
        else
        {
            return $msqli;
        }

    }

    public function getArray($query)
    {
        $array = array();
        $object = $this->query($query);

        if($object){
            // Cycle through results
            while ($row = $object->fetch_assoc()){
                $array[] = $row;
            }
            // Free result set
            $object->close();
        }

        return $array;
    }

    public function query($query = false)
    {
        if(!$query)
            return false;

        $object = $this->msqli->query($query);

        if ($this->msqli->errno) {
            die("Connection failed: " . $this->msqli->error);
        }
        else
        {
            return $object;
        }
    }

    public function close()
    {
        $this->msqli->close();
    }
}