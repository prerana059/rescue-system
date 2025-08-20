<?php
   $servername = "localhost";
   $username = "root";
   $password = " ";
   $dbname = "rescue_system";

    //Databse Connection
    $conn = new mysqli('localhost', 'root', '','rescue_system');

    //Check connection
    if($conn->connect_error){
        die('Connection Failed: '.$conn->connect_error);

    }
// echo"Connected!!";
?>