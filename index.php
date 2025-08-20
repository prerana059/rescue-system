<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws for Hope</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            background-color: #333;
        }
        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
        }
        nav a:hover {
            background-color: #ddd;
            color: black;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
            position: absolute;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to Paws for Hope</h1>
    <p>Rescue, Report, and Adopt Street Dogs</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="contact.php">Contact Us</a>
    <a href="login.php">Login</a>
    <a href="signup.php">Signup</a>
</nav>

<div class="container">
    <h2>Join Us in Making a Difference</h2>
    <p>Report abandoned or injured dogs, adopt a pet, or get involved in rescue operations.</p>

    <a href="report-dog.php" class="button">Report a Dog</a>
    <a href="adopt-dog.php" class="button">Adopt a Dog</a>
</div>

<footer>
    <p>&copy; 2024 Paws for Hope. All Rights Reserved.</p>
</footer>

</body>
</html>
