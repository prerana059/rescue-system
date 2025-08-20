<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rescue_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? '';

    if (!empty($email) && !empty($password) && !empty($user_type)) {
        // Determine the table based on user type
        $table = $user_type === 'rescuer' ? 'rescuers' : 'users';

        // Query to check login credentials
        $sql = "SELECT * FROM $table WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Fetch user details
            $user = $result->fetch_assoc();
            echo "Login successful! Welcome, " . $user['name'] . " (" . ucfirst($user_type) . ").";
        } else {
            echo "Invalid login credentials. Please try again.";
        }

        $stmt->close();
    } else {
        // echo "Please fill out all fields, including user type.";
    }
} else {
//     echo "Invalid request method.";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Street Dog Rescue System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    
</head>
<body>

<script>
// Function to validate login form
function validateLogin() {
    var email = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value.trim();
    var userType = document.getElementById("user_type").value;

    if (email === "" || password === "" || userType === "") {
        alert("All fields are required.");
        return false;
    }
    if (!validateEmail(email)) {
        alert("Please enter a valid email address.");
        return false;
    }
    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }
    return true;
}

// Function to validate signup form
function validateSignup() {
    var name = document.getElementById("name").value.trim();
    var email = document.getElementById("RegForm").elements["email"].value.trim();
    var phone = document.getElementById("RegForm").elements["phone"].value.trim();
    var password = document.getElementById("RegForm").elements["password"].value.trim();
    
    if (name === "" || email === "" || phone === "" || password === "") {
        alert("All fields are required.");
        return false;
    }
    if (!validateEmail(email)) {
        alert("Please enter a valid email address.");
        return false;
    }
    if (!validatePhone(phone)) {
        alert("Please enter a valid 10-digit phone number.");
        return false;
    }
    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }
    return true;
}

// Helper function to validate email format
function validateEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Helper function to validate phone number format
function validatePhone(phone) {
    var re = /^[0-9]{10}$/;
    return re.test(phone);
}

// Attach validation to forms
window.onload = function() {
    document.getElementById("LoginForm").onsubmit = function() {
        return validateLogin();
    };
    document.getElementById("RegForm").onsubmit = function() {
        return validateSignup();
    };
};
</script>

    <div class="container">
        <div class="navbar">
         <div class="logo">
            <img src="images/logo12.png" width="110px">
        </div>
        <nav>
            <ul id="MenuItems">
                <!-- <li><a href="index.html">Home</a></li> -->
                <!-- <li><a href="product.html">Product</a></li> -->
                <li><a href="Aboutus.html">About</a></li>
                
            </ul>
        </nav>
        <!-- <img src="images/cart.png" width="25px" height="25px">
        <img src="images/menu.png" class="menu-icon" onclick="menutoggle()"> -->
    </div>
  </div>
</div>

<!----------------------- account page ------------------->
<div class="account-page">
    <div class="container">
        <div class="row">
            <div class="colm-2">
                <img src="images/logo2.png" width="100%">
            </div>
            <div class="colm-2">
                <div class="form-container">
                    <div class="form-btn">
                        <span onclick="login()">Login</span>
                        <span onclick="register()">Register</span>
                        <hr id="Indicator">
                    </div>
<!---------------------------- login ------------------> 
<form action="homepage.php" method="post" id="LoginForm">
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            <label for="user_type">Select User Type:</label>
                            <select name="user_type" id="user_type" required>
                                <option value="">--Select User Type--</option>
                                <option value="user">User</option>
                                <option value="rescuer">Rescuer</option>
                            </select>
                            <button type="submit" class="btn">Login</button>
                            <a href="./forgot_password.php">Forgot Password?</a>
                        </form>
<!---------------------------- sign up  -------------> 
                    <form action="homepage.php" id="RegForm" method="post">
                        <input type="text" name="name" id="name" placeholder="Enter your full name">
                        <label for="gender">Select your gender</label>
                        <select name="gender" id="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="others">Others</option>
                        </select>
                        <input type="email" name="email" placeholder="Enter your email">
                        <input type="number" name="phone" placeholder="Enter your phone number">                               
                        <input type="password" name="password" placeholder="Create your Password">
                        <!-- <label for="user_type">Select user type</label> -->
                        <!-- <select name="user_type" id="user_type">
                            <option value="regular_user">Regular User</option>
                            <option value="rescuer">Rescuer</option>
                        </select><br><br> -->
                        <button type="submit" class="btn">Register</button>
                    </form>
                </div>
        </div>
    </div>
</div>

<!----------------------- footer ----------------------->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="footer-colm-1">
                <h3>Visit Our Website</h3>
                <p>Explore our website to learn more about our mission, adoptable dogs, and how you can help.</p>
                <div class="app-logo">
                    <img src="images/facebook.png" alt="Facebook">
                    <img src="images/twitter.png" alt="Twitter">
                </div>
            </div>
            <div class="footer-colm-2">
                <img src="images/logo12_white.png" alt="Paws & Rescue Logo">
                <p>Our mission is to rescue, rehabilitate, and rehome dogs in need, providing them with loving forever homes.</p>
            </div>
            <div class="footer-colm-3">
                <h3>Contact Us</h3>
                <ul>
                    <li>E-Mail: info@pawsandrescue.org</li>
                    <li>Phone: +9771234567890</li>
                    <li>Address: Baneshwor, Shantinagar,</li>
                    <li>Ward No-33</li>
                </ul>
            </div>
            <div class="footer-colm-4">
                <h3>Follow Us</h3>
                <ul>
                    <li>Facebook</li>
                    <li>Twitter</li>
                    <li>Instagram</li>
                    <li>YouTube</li>
                </ul>
            </div>
        </div>
        <hr>
        <p class="copyright">Paws & Rescue - 2024</p>
    </div>
</div>
   <!-- --------------js for toggle menu------------------ -->
   <script>
       varMenuItems = document.getElementById("MenuItems");

       MenuItems.style.maxHeight = "0px";

       function menutoggle(){
           if(MenuItems.style.maxHeight == "0px")
           {
               MenuItems.style.maxHeight = "200px"
           }
               else
               {
                MenuItems.style.maxHeight = "0px";
               }
           }
   </script>


<!-- js for product gallary -->

<script>
    var productImg = document.getElementById("productImg");
    var smallImg = document.getElementsByClassName("small-img");

    smallImg[0].onclick = function()
    {
        productImg.src = smallImg[0].src;
    }
    smallImg[1].onclick = function()
    {
        productImg.src = smallImg[1].src;
    }
    smallImg[2].onclick = function()
    {
        productImg.src = smallImg[2].src;
    }
    smallImg[3].onclick = function()
    {
        productImg.src = smallImg[3].src;
    }
</script>

<!------------------ js for toggle form  --------------------->

<script>

var LoginForm = document.getElementById("LoginForm");
var RegForm = document.getElementById("RegForm");
var Indicator = document.getElementById("Indicator");

    function register(){
        RegForm.style.transform = "translateX(0px)";
        LoginForm.style.transform = "translateX(0px)";
        Indicator.style.transform = "translateX(100px)";
    }
    function login(){
        RegForm.style.transform = "translateX(300px)";
        LoginForm.style.transform = "translateX(300px)";
        Indicator.style.transform = "translateX(0px)";
    }
</script>
</body>
</html>
