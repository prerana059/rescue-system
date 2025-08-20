<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rescuers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />    <style>
        body {
            margin: 0;
            font-family: Arial, monospace;
           
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 200px;
            padding: 10px;
            background-color: #f4f4f4;
            /* position:fixed; */
            height:100%;
            margin-top:0;
        }

        .content {
            flex: 1;
            margin-left:220px;
            padding: 20px;
            background-color: #fafafa;
            margin-top:0;
            text-decoration:none;
        }

        button {
            margin: 10px 0;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration:none;
        }

        button:hover {
            background-color: #0056b3;
        }

        
        ul.no-bullets {
            list-style-type:none;
            
            margin:0;
            padding 0;
        }
        a{
            text-decoration:none;
        }
        .side-nav{
            margin-bottom:10px;
            background-color:#87CEEB;
            padding:10px;
            font-size:14px;
            display:block;
            text-decoration:none;
        }
        h1 {
            display: flex;
            text-align: center;
            
            text-align:center;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #007bff; /* Blue background */
            color: white; /* White text */
        }
    </style>
</head>
<body>
    <h1>Admin Panel</h1>
    <div class="container">
        <div class="sidebar">
            
            <div class="side-nav"  id="loadRescuers"><i class="fa-solid fa-user"></i><a href="./manage-rescuers.php"> Manage Rescuers</a> </div>
            <div class="side-nav" id="loadUsers"><i class="fa-solid fa-user"></i><a href="./manage-users.php"> Manage Users</a></div>

            


            
        </div>

        
    </div>

    
</body>
</html>


<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rescue_system";

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a rescuer is being removed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rescuer_id'])) {
    $rescuer_id = intval($_POST['rescuer_id']); // Sanitize input


    // return print_r($rescuer_id);
    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM rescuers WHERE rescuer_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $rescuer_id); // Bind the parameter
        if ($stmt->execute()) {
            echo "<script>alert('Rescuer removed successfully.');</script>";
        } else {
            echo "<script>alert('Error removing rescuer: " . htmlspecialchars($stmt->error) . "');</script>";
        }
        $stmt->close(); // Close the statement
    } else {
        echo "<script>alert('Error preparing the statement: " . htmlspecialchars($conn->error) . "');</script>";
    }
}

// SQL query to fetch the list of rescuers
$sql = "SELECT rescuer_id, name, location, email, phone FROM rescuers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rescuer List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .table-container{
            margin-right:220px;
            width:80%;
            padding:20px
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left:220px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #ff1a1a;
        }
        #addButton {
            position:fixed;
            
            top:100px;
            right:20px;
            padding: 10px;
            text-decoration:none;
            color: white;
            border: none;
            cursor: pointer;
        }

        #addButton:hover {
            background-color:rgb;
            
        }
        h2{
            margin-left:220px;
        }
    </style>
</head>
<body>
<div class="content">
            <div id="listContainer"></div>
            <button class="btn-primary" id="addButton"><a href="addrescuers.html">Add rescuers</a>
           
        </button>

        </div>

<h2>Rescuer List</h2>


<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>
            <th>Rescuer ID</th>
            <th>Name</th>
            <th>Location</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['rescuer_id']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['location']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>
                    <form method='POST' style='margin: 0;' onsubmit='return confirmDeletion()'>
                        <input type='hidden' name='rescuer_id' value='" . htmlspecialchars($row['rescuer_id']) . "'>
                        <button type='submit'>Delete</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No rescuers found.";
}
$conn->close();
?>

<script>
    // JavaScript function to show confirmation dialog
    function confirmDeletion() {
        return confirm("Are you sure you want to delete this rescuer?");
    }
</script>

</body>
</html>
