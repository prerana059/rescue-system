<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Load List Dynamically</title>
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
            position:fixed;
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
            text-decoration:none;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration:none;
        }
        a{
            text-decoration:none;
        }

        button:hover {
            background-color: #0056b3;
        }

        #addButton {
            position:fixed;
            display: none;
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
        ul.no-bullets {
            list-style-type:none;
            
            margin:0;
            padding 0;
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
            
            <div class="side-nav"  id="loadRescuers"><i class="fa-solid fa-user"></i><a  href="./manage-rescuers.php"> Manage Rescuers</a> </div>
            <div class="side-nav" id="loadUsers"><i class="fa-solid fa-user"></i><a  href="./manage-users.php"> Manage Users</a></div>

            


            
        </div>

        <div class="content">
            <div id="listContainer"></div>
            <button class="btn-primary" id="addButton"><a href="add-rescuers.php">Add rescuers</a>
           
        </button>

        </div>
    </div>

    <script>
        // Function to load content dynamically
        function loadContent(file) {
            fetch(file)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("listContainer").innerHTML = data;
                    document.getElementById("addButton").style.display  = file === "manage-rescuers.php" ? "block" : "none";
                });
        }

        // // Event listeners for buttons
        // document.getElementById("loadRescuers").addEventListener("click", () => loadContent("manage-rescuers.php"));
        // document.getElementById("loadUsers").addEventListener("click", () => loadContent("manage-users.php"));
    </script>
</body>
</html>
