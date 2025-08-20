<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed

// Database connection
$conn = new mysqli("localhost", "root", "", "rescue_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the upload directory
$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Process form data
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize user inputs
    $dog_name = htmlspecialchars(trim($_POST['dog_name']));
    $location = htmlspecialchars(trim($_POST['location']));
    $condition = htmlspecialchars(trim($_POST['condition_']));
    $status = htmlspecialchars(trim($_POST['status']));
    $phone_no = htmlspecialchars(trim($_POST['phone_no']));
    $photo = $_FILES['photo'];

    // Validate input fields
    if (empty($dog_name) || empty($location) || empty($condition) || empty($status) || empty($phone_no)) {
        $message = "All fields are required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone_no)) {
        $message = "Invalid phone number. It must be 10 digits.";
    } elseif ($photo['error'] !== UPLOAD_ERR_OK) {
        $message = "Photo upload failed. Please try again.";
    } else {
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($photo['type'], $allowed_types)) {
            $message = "Invalid photo format. Only JPEG and PNG are allowed.";
        } else {
            // Generate a unique name for the uploaded file
            $photo_name = uniqid() . "-" . basename($photo["name"]);
            $photo_path = $upload_dir . $photo_name;

            // Move the uploaded file
            if (move_uploaded_file($photo["tmp_name"], $photo_path)) {
                // Fetch the closest rescuer's email from the database
                $email = "";
                $rescuer_query = $conn->query("SELECT email FROM rescuers ORDER BY RAND() LIMIT 1");
                
                if ($rescuer_query->num_rows > 0) {
                    $rescuer = $rescuer_query->fetch_assoc();
                    $email = $rescuer['email'];
                } else {
                    $message = "No rescuers found in the database.";
                }

                // Insert data into the database
                $stmt = $conn->prepare("INSERT INTO rescue_request (dog_name, location, condition_, photo, phone_no, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $dog_name, $location, $condition, $photo_name, $phone_no, $status);

                if ($stmt->execute()) {
                    if (!empty($email)) {
                        // Send email notification
                        $mail = new PHPMailer(true);
                        try {
                            // SMTP Configuration
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'prebhme@gmail.com'; // Your Gmail
                            $mail->Password = 'hfnn wlvq klsh hyxx'; // Your App Password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            // Email content
                            $mail->setFrom('prebhme@gmail.com', 'Dog Rescue System');
                            $mail->addAddress($email, 'Rescuer');
                            $mail->Subject = "New Dog Rescue Report";
                            $mail->isHTML(true);
                            $mail->Body = "
                                <h2>New Dog Rescue Report</h2>
                                <p><strong>Dog Name:</strong> $dog_name</p>
                                <p><strong>Location:</strong> $location</p>
                                <p><strong>Condition:</strong> $condition</p>
                                <p><strong>Status:</strong> $status</p>
                                <p><strong>Contact:</strong> $phone_no</p>
                                <p>A photo of the dog is attached.</p>
                            ";

                            // Attach the uploaded photo
                            $mail->addAttachment($photo_path);

                            // Send email
                            if ($mail->send()) {
                                $message = "Report submitted successfully! An email has been sent to the rescuer.";
                            } else {
                                $message = "Report submitted, but email failed to send.";
                            }
                        } catch (Exception $e) {
                            $message = "Report submitted, but email sending failed. Error: {$mail->ErrorInfo}";
                        }
                    } else {
                        $message = "Report submitted, but no rescuer email found.";
                    }
                } else {
                    $message = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Failed to upload the photo.";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report for Rescue</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            /* background: linear-gradient(135deg, #6dd5ed, #2193b0); */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        input[type="file"] {
            background: #f9f9f9;
            padding: 10px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2193b0;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }

        button:hover {
            background: #17687d;
        }

        .message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <h2>Report an Emergency</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <label for="dog_name">Dog Name:</label>
        <input type="text" id="dog_name" name="dog_name" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="condition_">Condition:</label>
        <input type="text" id="condition_" name="condition_" required>

        <label for="photo">Condition Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*" required>

        <label for="phone_no">Phone No:</label>
        <input type="text" id="phone_no" name="phone_no" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Healthy">Healthy</option>
            <option value="Injured">Injured</option>
            <option value="Missing">Missing</option>
            <option value="Deceased">Deceased</option>
        </select>

        <button type="submit">Submit Report</button>
    </form>

    <script>
    document.getElementById('rescueForm').addEventListener('submit', function(event) {
        let isValid = true;

        // Get form fields
        const location = document.getElementById('location').value.trim();
        const condition = document.getElementById('dog_condition').value;
        const description = document.getElementById('description').value.trim();
        const image = document.getElementById('image').files[0];

        // Reset previous error messages
        document.getElementById('locationError').innerText = "";
        document.getElementById('conditionError').innerText = "";
        document.getElementById('descriptionError').innerText = "";
        document.getElementById('imageError').innerText = "";

        // Validate location
        if (location === "") {
            document.getElementById('locationError').innerText = "Location is required.";
            isValid = false;
        }

        // Validate dog condition
        if (condition === "") {
            document.getElementById('conditionError').innerText = "Please select a dog condition.";
            isValid = false;
        }

        // Validate description
        if (description.length < 10) {
            document.getElementById('descriptionError').innerText = "Description must be at least 10 characters long.";
            isValid = false;
        }

        // Validate image file
        if (!image) {
            document.getElementById('imageError').innerText = "Please upload an image.";
            isValid = false;
        } else {
            const allowedTypes = ["image/png", "image/jpeg"];
            if (!allowedTypes.includes(image.type)) {
                document.getElementById('imageError').innerText = "Only PNG and JPEG images are allowed.";
                isValid = false;
            }
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
</script>
</body>
</html>
