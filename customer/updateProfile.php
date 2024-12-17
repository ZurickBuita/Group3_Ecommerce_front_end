<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}

$username = $phoneNumber = $email = $password = $confirmPassword = $imgUrl = $alertMssg = "";
$username_err = $password_err = $confirmPassword_err = $passwordMismatch = "";

$username_err = $phoneNumberErr = $emailErr = $password_err = $confirmPassword_err = $passwordMismatch = "";
$values = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['updateProfile'])) {
        $id = $_SESSION['ID'];
        $username = test_input($_POST['username']);
        $password = test_input($_POST['password']);
        $phoneNumber = test_input($_POST['phoneNumber']);
        $email = test_input($_POST['email']);
        $confirmPassword = test_input($_POST['confirmPassword']);

        if (empty($username)) {
            $values = false;
            $username_err = "Username is required!";
        }
        if (empty($phoneNumber)) {
            $values = false;
            $phoneNumberErr = "PhoneNumber is required!";
        }
        if (empty($email)) {
            $values = false;
            $emailErr = "Email is required!";
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $values = false;
                $emailErr = "Invalid Email Address";
            }
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
                        $img_upload_path = '../uploads/customer_img/' . $imgUrl;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        echo "<script>alert('You can't upload files of this type')</script>";
                    }
                }
            }
        }

        if ($values === true) {
            if (empty($imgUrl)) {
                $query = "UPDATE `customer` SET `username`='$username',`phoneNumber`='$phoneNumber',`email`='$email',`password`='$password' WHERE `Id` = '$id'";
            } else {
                $query = "SELECT * FROM `customer` WHERE `Id` = '$id'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $prevImg = $row['imgUrl'];

                unlink("../uploads/customer_img/$prevImg");
                $query = "UPDATE `customer` SET `username`='$username',`phoneNumber`='$phoneNumber',`email`='$email',`password`='$password',`imgUrl`='$imgUrl' WHERE `Id` = '$id'";
            }
            $query_run = mysqli_query($conn, $query);
            $alertMssg =
                '<div class="z-3 toast align-items-center text-bg-success border-0 fade show position-fixed bottom-0 start-0 my-3 mx-1" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body">
               Profile Updated Successfully.
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
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
<html>

<head>
    <title>Update Profile</title>
    <!-- Bootstrap -->
    <?php include "../links.php" ?>
    <!-- StyleSheet -->
    <link rel="StyleSheet" href="../style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inconsolata&family=Open+Sans:wght@300&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Source Sans Pro', sans-serif;
        }

        .brand-logo>span {
            font-style: italic;
            color: #8000ff;
        }
    </style>
</head>

<body class="bg-light position-relative">
    <?php include "header.php" ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-4 mx-2">
            <li class="breadcrumb-item"><a href="homePage.php">Codetech</a></li>
            <li class="breadcrumb-item active">Update Profile</li>
        </ol>
    </nav>
    <div class="main px-4 py-2">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 col-sm-12">
                <div class="card shadow-none border bg-white shadow">
                    <div class="card-header bg-white ">
                        <h5 class="h5 py-3 mb-0">Update Profile</h5>
                    </div>
                    <div class="card-body border-0">
                        <?php echo $alertMssg ?>
                        <?php
                        $id = $_SESSION['ID'];
                        $query = "SELECT * FROM `customer` WHERE Id = '$id'";
                        $query_run = mysqli_query($conn, $query);

                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $data) {
                                ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST"
                            enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control"
                                    value="<?= $data['username'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Phone Number</label>
                                <input type="number" name="phoneNumber" class="form-control"
                                    value="<?= $data['phoneNumber'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    value="<?= $data['password'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Confirm Password</label>
                                <input type="password" name="confirmPassword" class="form-control"
                                    value="<?= $data['password'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Profile Picture</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="mb-3">
                                <div class="alert alert-secondary py-2 alert-dismissible fade show" role="alert">
                                    <img src='../uploads/customer_img/<?= $data['imgUrl'] ?>' width='50' height='50'
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
                                                class="btn-close position-absolute top-0 end-0 bg-white z-1 p-3"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                            <div class="modal-body p-0 m-0 ">
                                                <img src='../uploads/customer_img/<?=$data['imgUrl'] ?>' width='100%'
                                                height='auto'>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="submit" name="updateProfile" value="Update Profile"
                                    class="btn btn-primary">
                            </div>
                        </form>

                        <?php
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "../footer.php" ?>
</body>

</html>