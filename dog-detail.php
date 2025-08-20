<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'rescue_system';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Get the dog ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch dog details from the database
$sql = "SELECT * FROM dogs WHERE id = $id";
$result = $conn->query($sql);

// Check if the dog exists
if ($result->num_rows > 0) {
    $dogs = $result->fetch_assoc();
} else {
    die('Dog not found.');
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($dogs['name']); ?> - Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .dog-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .dog-details {
            margin-top: 20px;
        }
        .dog-details h1 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }
        .dog-details p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .adopt-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .adopt-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?php echo htmlspecialchars($dog['image_url']); ?>" alt="<?php echo htmlspecialchars($dogs['name']); ?>" class="dog-image">
        <div class="dog-details">
            <h1><?php echo htmlspecialchars($dogs['name']); ?></h1>
            <p><strong>Breed:</strong> <?php echo htmlspecialchars($dogs['breed']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($dogs['age']); ?> years</p>
            <p><?php echo htmlspecialchars($dogs['description']); ?></p>
            <a href="adopt.php?id=<?php echo $id; ?>" class="adopt-button">Request for Adoption</a>
        </div>
    </div>
</body>
</html>
