<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'rescue_system');

// Check connection
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Validate and fetch form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$breed = isset($_POST['breed']) ? trim($_POST['breed']) : '';
$age = isset($_POST['age']) ? intval($_POST['age']) : 0;
$description = isset($_POST['description']) ? trim($_POST['description']) : '';

// Ensure required fields are provided
if (empty($name) || empty($breed) || empty($age) || empty($description)) {
    die('Error: All fields except image are required.');
}

// Handle file upload
$image_url = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    } else {
        echo "Warning: Image upload failed. Proceeding without image.";
    }
}

// Insert into database
$sql = "INSERT INTO dogs (name, breed, age, description, image_url) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error in SQL preparation: " . $conn->error);
}
$stmt->bind_param("ssiss", $name, $breed, $age, $description, $image_url);

if ($stmt->execute()) {
    echo "Dog information added successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
