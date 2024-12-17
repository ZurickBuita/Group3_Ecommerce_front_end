<?php
require 'db_conn.php';
include 'verify_user.php';

$username = $email = $phoneNumber = $password = $confirmPassword = "";
$username_err = $email_err = $phoneNumber_err = $password_err = $confirmPassword_err = $passwordMismatch_err = "";
$values = true;
$alertMssg = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = test_input($_POST['Username']);
    $email = test_input($_POST['email']);
    $phoneNumber = test_input($_POST['phoneNumber']);
    $password = test_input($_POST['password']);
    $confirmPassword = test_input($_POST['confirmPassword']);

    if (isset($_POST['SignUp'])) {
        if (empty($username)) {
            $values = false;
            $username_err = "Username is Empty!";
        }

        if (empty($email)) {
            $values = false;
            $email_err = "Email is Empty!";
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
                $values = false;
                $email_err = "Invalid Email Address";
            }
        }

        if (empty($phoneNumber)) {
            $values = false;
            $phoneNumber_err = "Phone Number is Empty!";
        }

        if (empty($password)) {
            $values = false;
            $password_err = "Password is Empty!";
        }

        if (empty($confirmPassword)) {
            $values = false;
            $confirmPassword_err = "Confirm Password is Empty!";
        }

        if ($password != $confirmPassword) {
            $values = false;
            $password_err = "";
            $confirmPassword_err = "";
            $passwordMismatch_err = "Password Mismatch";
        }

        if ($values === true) {
            $query = "INSERT INTO `customer`(`username`, `phoneNumber`, `email`, `password`) 
                      VALUES ('$username', '$phoneNumber', '$email', '$password' )";
            $query_run = mysqli_query($conn, $query);

            if ($query_run) {
                $alertMssg =
                    '<div class="toast align-items-center text-bg-success border-0 fade show position-fixed bottom-0 start-0 my-3 mx-1" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                        <div class="toast-body">
                        Congratulations! Your account has been successfully created.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                     </div>';
            } else {
                $alertMssg =
                    '<div class="toast align-items-center text-bg-success border-0 fade show position-fixed bottom-0 start-0 my-3 mx-1" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                        <div class="toast-body">
                         Failed to create an account
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                     </div>';
                echo "<script>console.log('Error creating database: " . mysqli_error($conn) . "'); </script>";
            }

            mysqli_close($conn);
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
    <title>CodeTech | Sign up</title>
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

    <?=$alertMssg?>

    <div class="container d-flex justify-content-center">
        <div class="card shadow mt-3 mb-5" style="max-width: 550px; width: 100%;">
            <div class="card-header text-center bg-white text-secondary">
                <h2 class="h3 mb-0">Join With Us</h2>
                <small style="font-size: 103x;">Fill out the form to get started.</small>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" placeholder="Full Name" name="Username">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $username_err ?>
                            </small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="Email" name="email">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $email_err ?>
                            </small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="number" class="form-control" placeholder="Phone Number" name="phoneNumber">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $phoneNumber_err ?>
                            </small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $password_err ?>
                            </small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm Password"
                            name="confirmPassword">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $confirmPassword_err ?>
                            </small>
                            <small class="text-danger fw-bold">
                                <?php echo $passwordMismatch_err ?>
                            </small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input class="btn btn-primary w-100" type="submit" name="SignUp" value="Join With Us">
                    </div>
                </form>

                <div class="mb-3 d-flex flex-column text-center">
                    <small>Already have an account?</small>
                    <small>
                        <a href="login.php">Login to your Account</a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php" ?>
</body>

</html>