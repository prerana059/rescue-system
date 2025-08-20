<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'rescue_system');

// Check connection
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Declare a variable to track submission success
$form_submitted = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requester_name = $conn->real_escape_string($_POST['requester_name']);
    $requester_email = $conn->real_escape_string($_POST['requester_email']);
    $message = $conn->real_escape_string($_POST['message']);
    $id = intval($_POST['id']); // Dog ID from the hidden field

    // Validate dog ID
    if ($id <= 0) {
        die("Invalid dog ID provided.");
    }

    // Retrieve dog details
    $query = "SELECT * FROM dogs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dog = $result->fetch_assoc();

        // Fetch all rescuers' emails
        $rescuers_query = "SELECT email FROM rescuers";
        $rescuers_result = $conn->query($rescuers_query);

        if ($rescuers_result->num_rows > 0) {
            // Insert adoption request into the database
            $sql = "INSERT INTO adoption_requests (dog_id, requester_name, requester_email, message) 
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $id, $requester_name, $requester_email, $message);

            if ($stmt->execute()) {
                // Send email notification to all rescuers
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Change this if using a different SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'prebhme@gmail.com'; // Your SMTP email
                    $mail->Password = 'hfnn wlvq klsh hyxx'; // Your SMTP email password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Email setup
                    $mail->setFrom('prebhme@gmail.com', 'Rescue System');
                    $mail->isHTML(true);
                    $mail->Subject = 'New Adoption Request for ' . $dog['name'];

                    // Email Content
                    $email_body = "
                        <h2>New Adoption Request</h2>
                        <p><strong>Requester Name:</strong> $requester_name</p>
                        <p><strong>Requester Email:</strong> $requester_email</p>
                        <p><strong>Message:</strong> $message</p>
                        <hr>
                        <p><strong>Dog Name:</strong> {$dog['name']}</p>
                        <p><strong>Breed:</strong> {$dog['breed']}</p>
                        <p><strong>Age:</strong> {$dog['age']} years</p>
                    ";
                    $mail->Body = $email_body;

                    // Add all rescuers as recipients
                    while ($rescuer = $rescuers_result->fetch_assoc()) {
                        $mail->addAddress($rescuer['email']);
                    }

                    // Send the email
                    if ($mail->send()) {
                        $form_submitted = true;
                    } else {
                        echo "<p>Error sending email: " . $mail->ErrorInfo . "</p>";
                    }
                } catch (Exception $e) {
                    echo "<p>Email sending failed: {$mail->ErrorInfo}</p>";
                }
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>No rescuers found to send the email.</p>";
        }
    } else {
        echo "<p>Dog not found, unable to submit your request.</p>";
    }
}

// Get dog ID from query parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch dog details
$sql = "SELECT * FROM dogs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dog = $result->fetch_assoc();
} else {
    echo "<p>Dog not found.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopt a Dog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
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
        .dog-details h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .dog-details p {
            margin: 10px 0;
            color: #555;
        }
        .adopt-form {
            margin-top: 20px;
        }
        .adopt-form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
        .adopt-form input,
        .adopt-form textarea,
        .adopt-form button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .adopt-form textarea {
            resize: vertical;
        }
        .adopt-form button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .adopt-form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($form_submitted) { ?>
            <p>Thank you for your request! We will get in touch soon.</p>
        <?php } else { ?>
            <form action="" method="POST" class="adopt-form">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> 
                <label for="requester_name">Your Name</label>
                <input type="text" id="requester_name" name="requester_name" required>
                <label for="requester_email">Your Email</label>
                <input type="email" id="requester_email" name="requester_email" required>
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                <button type="submit">Request Adoption</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
