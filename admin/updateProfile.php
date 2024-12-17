<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}

$username = $password = $confirmPassword = $imgUrl = $alertMssg = "";
$username_err = $password_err = $confirmPassword_err = $passwordMismatch = "";
$values = true;
$id = $_SESSION['ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submitUpdateProfile'])) {
        $username = test_input($_POST['username']);
        $password = test_input($_POST['password']);
        $confirmPassword = test_input($_POST['confirmPassword']);
        $email = test_input($_POST['email']);

        if (empty($username)) {
            $values = false;
            $username_err = "Username is required!";
        }
        if (empty($password)) {
            $values = false;
            $password_err = "Password is required!";
        }
        if (empty($confirmPassword)) {
            $values = false;
            $confirmPassword_err = "Confirm Password is required!";
        }

        if (!empty($password) && !empty($confirmPassword)) {
            if ($password !== $confirmPassword) {
                $values = false;
                $password_err = "";
                $confirmPassword_err = "";
                $passwordMismatch = "Password Mismatch";
            }
        }

        if (isset($_FILES['image'])) {
            $img_name = $_FILES['image']['name'];
            $img_size = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $img_err = $_FILES['image']['error'];

            if ($img_err === 0) {
                if ($img_size > 2225000) {
                    $values = false;
                    echo "<script>alert('Sorry, your file is too large.')</script>";
                } else {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);

                    $allowed_exs = array("jpg", "jpeg", "png");

                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $imgUrl = uniqid("IMG-", true) . '.' . $img_ex_lc;
                        $img_upload_path = '../uploads/admin_img/' . $imgUrl;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        echo "<script>alert('You can't upload files of this type')</script>";
                    }
                }
            }
        }

        if ($values === true) {
            if (!empty($imgUrl)) {
                $query = "SELECT * FROM `admin` WHERE Id = '$id'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $prevImg = $row['imgUrl'];

                unlink("../uploads/admin_img/$prevImg");

                $query = "UPDATE `admin` SET `username`='$username',`password`='$password', `email`='$email', `imgUrl`='$imgUrl' WHERE Id = '$id'";
            } else {
                $query = "UPDATE `admin` SET `username`='$username',`password`='$password', `email`='$email' WHERE Id = '$id'";
            }

            $query_run = mysqli_query($conn, $query);
            $alertMssg =
                '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                 Profile updated successfully
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>';

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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CodeTech Admin|Add Services</title>

    <?php include 'links.php' ?>

</head>

<body class="position-relative">
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>

    <main id="main" class="main">

        <div class="card shadow-none border">
            <div class="card-header">
                <div class="card-title mb-0">Update Profile</div>
            </div>
            <div class="card-body px-3">
                <?php echo $alertMssg ?>
                <?php
                $query = "SELECT * FROM `admin` WHERE Id = '$id'";
                $query_run = mysqli_query($conn, $query);

                if (mysqli_num_rows($query_run) > 0) {
                    foreach ($query_run as $data) {
                        ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="form"
                    enctype="multipart/form-data">
                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-light">Username</label>
                        <input type="text" class="form-control" name="username" value="<?= $data['username'] ?>">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $username_err ?>
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-light">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= $data['email'] ?>">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $username_err ?>
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-light">Password</label>
                        <input type="password" class="form-control" name="password" value="<?= $data['password'] ?>">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $password_err ?>
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-light">Confirm Password</label>
                        <input type="password" class="form-control" name="confirmPassword"
                            value="<?= $data['password'] ?>">
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $confirmPassword_err ?>
                            </small>
                        </div>
                        <div>
                            <small class="text-danger fw-bold">
                                <?php echo $passwordMismatch ?>
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-light">Profile Picture</label>
                        <div class="input-group flex-nowrap">
                            <input type="file" class="form-control" name="image">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="alert alert-secondary py-2 alert-dismissible fade show" role="alert">
                            <img src='../uploads/admin_img/<?= $data[' imgUrl'] ?>' width='50' height='50'
                            data-bs-toggle='modal' data-bs-target='#exampleModal'>
                            <span>
                                <?= $data['imgUrl'] ?>
                            </span>
                        </div>
                        <div class="modal fade bg-dark bg-opacity-50" id="exampleModal" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content p-0 m-0  position-relative">
                                    <button type="button"
                                        class="btn-close position-absolute top-0 end-0 bg-white  text-white z-3 p-3"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                    <div class="modal-body p-0 m-0">
                                        <img class="z-n1" src='../uploads/admin_img/<?= $data[' imgUrl'] ?>'
                                        width='100%'
                                        height='auto'>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mb-3 text-secondary">
                            <input type="submit" class="btn btn-primary" name="submitUpdateProfile"
                                value="Update Profile">
                        </div>
                </form>
                <?php
                    }
                }
                ?>

            </div>
        </div>
    </main><!-- End #main -->

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <script src="../js/script.js"></script>
    <?php include "script.php" ?>
</body>

</html>