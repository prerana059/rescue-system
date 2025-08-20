<?php
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];  
    $password=$_POST['password'];

    $hashed_password = md5($password);


    // Database Connection
    $conn = new mysqli('localhost', 'root', '', 'rescue_system');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Corrected SQL Query
    $sql = "INSERT INTO users (name, gender, email, phone, password) 
            VALUES ('$name', '$gender', '$email', '$phone', '$password')";

    // Execute the Query
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
?>
