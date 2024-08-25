<?php

include_once 'database.php';
session_start();

if (isset($_SESSION["sid"])) {
    header("location:index.php");
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["login"])) {
        if (empty($_POST["sid"]) || empty($_POST["password"])) {
            $message = '<label>All fields are required</label>';
        } else {
            $sid= $_POST["sid"];
            $password = $_POST["password"];

            $query = "SELECT FLD_STAFF_PASS FROM tbl_staff_a196330 WHERE FLD_STAFF_ID = :sid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && password_verify($password, $result['FLD_STAFF_PASS'])) {
                $_SESSION["sid"] = $sid;
                header("location:login_success.php");
            } else {
                $message = '<label>Wrong Password</label>';
            }
        }
    }
} catch (PDOException $error) {
    $message = $error->getMessage();
}

$conn = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <style>
        body {
            background-image: url("lawa.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .container img.logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 100%;
            height: auto;
        }

        .container label {
            font-size: 18px;
            font-weight: bold;
        }

        .container input[type="text"],
        .container input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .container input[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background-color: #D24545;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container input[type="submit"]:hover {
            background-color: #A94438;
        }

        .container .text-danger {
            color: #ff0000;
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Navigation bar styles */
        .navbar {
            font-family: 'Georgia', serif;
            background-color: #8B0000; /* Dark red */
            border: none;
            border-radius: 0;
            margin-bottom: 0;
        }

        .navbar a {
            color: #fff !important;
            font-size: 18px;
        }

        .navbar a:hover, .navbar a:focus {
            background-color: #A94438; /* Slightly lighter red */
            color: white !important;
        }

        .navbar .dropdown-menu {
            background-color: #8B0000; /* Dark red */
            font-family: 'Georgia', serif;
            border-radius: 0;
        }

        .navbar .dropdown-menu a {
            color: #fff !important;
        }

        .navbar .dropdown-menu a:hover {
            background-color: #A94438; /* Slightly lighter red */
        }
    </style>
    <title>Nerf Pawn Shop : Login</title>
</head>
<body>
    <?php include_once 'nav_bar_login.php'; ?>

    <div class="container">
        <p><center>LOGIN TO ENTER</center></p>

        <?php
        if (isset($message)) {
            echo '<label class="text-danger">' . $message . '</label>';
        }
        ?>

        <form method="post">
            <label>ID:</label>
            <input type="text" name="sid" class="form-control">
            <label>Password:</label>
            <input type="password" name="password" class="form-control">
            <input type="submit" name="login" class="btn btn-info" value="Log in">
        </form>
    </div>
</body>
</html>
