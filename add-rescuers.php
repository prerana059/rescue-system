<?php

    $name = $_POST['name'];
    $location = $_POST['location'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];  
    


    // Database Connection
    $conn = new mysqli('localhost', 'root', '', 'rescue_system');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Corrected SQL Query
    $sql = "INSERT INTO rescuers (name, location, email, phone) 
            VALUES ('$name', '$location', '$email', '$phone')";

    // Execute the Query
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
?>




