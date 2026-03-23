<?php
class DBController
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "secbookstore";
    private $conn;
    function __construct()
    {
        $this->conn = $this->connectDB();
    }
    function connectDB()
    {
        $con = mysqli_connect($this->host, $this->user, $this->pass, $this->dbname);
        mysqli_set_charset($con, "utf8");
        return $con;
    }
    function runQuery($query)
    {
        $result = mysqli_query($this->conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }
        if (!empty($resultset))
            return $resultset;
    }
    function numRows($query)
    {
        $result = mysqli_query($this->conn, $query);
        $rowcount = mysqli_num_rows($result);
        return $rowcount;
    }
}
?>