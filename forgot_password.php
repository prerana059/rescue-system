<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: monospace, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        h1 {
            position: absolute;
            top: 30px;
            width: 100%;
            text-align: center;
            font-size: 30px;
            font-family:monospace;
            color: #333;
            margin: 0;
        }


        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .login-form label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .login-form input {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-form input:focus {
            border-color:rgb(107, 134, 253);
            outline: none;
            box-shadow: 0 0 5px rgba(120, 167, 247, 0.5);
        }

        .login-form button {
            padding: 10px;
            font-size: 16px;
            background-color:rgb(119, 171, 255);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-form button:hover {
            background-color:rgb(103, 164, 244);
        }

        .login-form button:active {
            background-color:rgb(94, 145, 241);
        }

        @media (max-width: 600px) {
            .login-container {
                padding: 15px;
            }

            .login-form input, .login-form button {
                font-size: 14px;
            }
        }
        h2{
            margin-top:20px;
            position:fixed;
        }
    </style>
</head>
<body>
    <h2>Forgot Password</h2>
    <div>
    <form action="process_reset.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br>
        <button type="submit">Reset Password</button>
    </form>
    </div>
</body>
</html>

