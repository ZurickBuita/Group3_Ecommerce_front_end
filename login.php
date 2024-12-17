<?php
session_start();
require "db_conn.php";
include 'verify_user.php';

$_SESSION['error_mssg'] = "";
$username = $password = "";
$username_err = $password_err = $login_err = "";

$values = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    if (isset($_POST['submitLogin'])) {
        if (empty($username)) {
            $values = false;
            $username_err = "Username is required!";
        }

        if (empty($password)) {
            $values = false;
            $password_err = "Password is required!";
        }

        if (empty($_POST['user'])) {
            $values = false;
        }

        if ($values === true) {
            if ($_POST['user'] === 'admin') {
                $query = "SELECT * FROM `admin` WHERE `username` = '$username' AND `password` = '$password'";
            } else if ($_POST['user'] === 'customer') {
                $query = "SELECT * FROM `customer` WHERE `username` = '$username' AND `password` = '$password'";
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                if ($username === $row['username'] && $password === $row['password']) {
                    $_SESSION['ID'] = $row['Id'];
                    $_SESSION['user'] = test_input($_POST['user']);

                    if ($_SESSION['user'] == 'admin') {
                        header('Location: admin/dashboard.php');
                    } else {
                        header('Location: customer/homePage.php');
                    }
                    mysqli_close($conn);
                }
            } else {
                if (!empty($username) && !empty($password)) {
                    echo "<script>console.log('Incorrect Username or Password'); </script>";
                    $login_err = "<div class='alert alert-danger' role='alert'>Incorrect Username or Password</div>";
                }
            }
        }
    }
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeTech | Log In</title>
    <link rel="icon" href="img/CodeTech_logo.png" />
    <?php include 'links.php' ?>
    <link rel="stylesheet" href="style.css" />
    <style>
        .md-sm {
            display: none;
        }

        @media only screen and (max-width: 850px) {
            .md-sm {
                display: block;
            }
        }
    </style>

<body class="bg-light position-relative">
    <?php include 'header.php' ?>

    <div class="container d-flex justify-content-center">

        <div class="card shadow mt-3 mb-5" style="max-width: 550px; width: 100%;">
            <div class="card-header text-center bg-white text-secondary">
                <h2 class="h3 mb-0">Welcome back</h2>
                <small style="font-size: 103x;">Login to manage your account.</small>
            </div>
            <div class="card-body">

                <?php echo $login_err ?>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Username">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $username_err ?>
                            </small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $password_err ?>
                            </small>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="user" value="admin">
                            <label class="form-check-label">
                                Admin
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" name="user" value="customer">
                            <label class="form-check-label">
                                Customer
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input class="btn btn-primary w-100" type="submit" name="submitLogin"
                            value="Login to your Account">
                    </div>
                </form>

                <div class="mb-3 d-flex flex-column text-center">
                    <small>Don't have an account?</small>
                    <small>
                        <a href="signup.php">Create an Account</a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php" ?>
</body>

</html>