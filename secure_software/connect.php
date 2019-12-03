<?php
//session_start();
$servername = "localhost";
$username = "admin";
$password = "MEKwI2QowF30BHDt";
#MEKwI2QowF30BHDt

try {
    $conn = new PDO("mysql:host=$servername;dbname=bug_tracking", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>