<?php
   // Database connection
   $conn = new mysqli("localhost", "root", "", "rescue_system");

   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   // Get user inputs
   $email = $_POST['email'];
   $new_password = $_POST['new_password'];

   // Check if the email exists in the database
   $sql = "SELECT * FROM users WHERE email='$email'";
   $result = $conn->query($sql);

   if ($result && $result->num_rows > 0) {
       // Update the password
       $update_sql = "UPDATE users SET password='$new_password' WHERE email='$email'";
       if ($conn->query($update_sql) === TRUE) {
           echo "Password has been reset successfully!";
       } else {
           echo "Error updating password: " . $conn->error;
       }
   } else {
       echo "No account found with this email.";
   }

   // Close the connection
   $conn->close();
?>
