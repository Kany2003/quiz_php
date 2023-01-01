<?php 
    //connectDB.inc.php
    
    //localhost or Alwaysdata : mysql-xxxxxxx.alwaysdata.net
    $host="localhost";
    $user="root";
    $passwd="";
    $bd="quiz";


    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
    //instance of mysqli class
    $mysqli = new mysqli($host, $user, $passwd, $bd);

    //set_charset("utf8"), method of mysqli class
    $mysqli->set_charset("utf8");

    } catch (Exception $e) { 
    echo "MySQLi Error Code: " . $e->getCode() . "<br />";
    echo "Exception Msg: " . $e->getMessage();
    exit();
    }

?>