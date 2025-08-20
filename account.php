<?php
session_start(); // Start session at the top of the file

// Debugging - Check if session exists
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>User ID not found in session. Please <a href='login.php'>login</a>.</p>";
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "rescue_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch user details
$sql = "SELECT name, email, phone_no FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission for updating details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone_no = htmlspecialchars(trim($_POST['phone_no']));
    
    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone_no)) {
        $message = "Phone number must be 10 digits.";
    } else {
        $update_sql = "UPDATE users SET name=?, email=?, phone_no=? WHERE id=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $name, $email, $phone_no, $user_id);
        
        if ($update_stmt->execute()) {
            $message = "Profile updated successfully!";
            // Refresh user details
            $user['name'] = $name;
            $user['email'] = $email;
            $user['phone_no'] = $phone_no;
        } else {
            $message = "Error updating profile.";
        }
        $update_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 15px; background: #007BFF; color: white; border: none; padding: 10px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { margin-top: 10px; color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Profile</h2>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <label for="phone_no">Phone No:</label>
            <input type="text" id="phone_no" name="phone_no" value="<?php echo htmlspecialchars($user['phone_no']); ?>" required>
            
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
