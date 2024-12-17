<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}

$alertMssg = "";
$isDelivered = "";
$prevDir = null;
$customerId = null;
$mssgTo = null;
$fileUrl = null;

if (!empty($_SESSION['serviceId']) && !empty($_SESSION['previousDir'])) {
    $serviceId = $_SESSION['serviceId'];
    $prevDir = $_SESSION['previousDir'];
    $customerId =  $_SESSION['customerId']; 

    $query = "SELECT * FROM `customer` WHERE `Id` = '$customerId'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $mssgTo = $row['email'];
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['createNewMssg'])) {
        $mssgSubj = $_POST['mssgSubj'];
        $mssgTo = $_POST['mssgTo'];
        $mssgFrom = $_SESSION['ID'];
        $mssgContent = $_POST['mssgContent'];
        $serviceId = $_POST['serviceId'];
        $packageId = $_SESSION['packageId'];

        if (isset($_FILES['mssgFile'])) {
            $file_name = $_FILES['mssgFile']['name'];
            $file_size = $_FILES['mssgFile']['size'];
            $tmp_name = $_FILES['mssgFile']['tmp_name'];
            $file_err = $_FILES['mssgFile']['error'];

            if ($file_err === 0) {
                $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
                $file_ex_lc = strtolower($file_ex);

                $allowed_exs = array("jpg", "jpeg", "png", "pdf", "zip", "rar", "mp4");

                if (in_array($file_ex_lc, $allowed_exs)) {
                    $fileUrl = uniqid("FILE-", true) . '.' . $file_ex_lc;
                    $file_upload_path = '../uploads/FileMssg/' . $fileUrl;
                    move_uploaded_file($tmp_name, $file_upload_path);
                } else {
                    echo "<script>alert('You can't upload files of this type')</script>";
                }
            }
        }

        if (empty($fileUrl)) {
            $_SESSION['deliveredWork'] = false;
        } else {
            $_SESSION['deliveredWork'] = true;
        }

        if ($_SESSION['deliveredWork'] == true) {
            $orderId = $_SESSION['orderId'];
            $query = "UPDATE `orders` SET `status`='2' WHERE `orderId` = '$orderId'";

            if (mysqli_query($conn, $query)) {
                $isDelivered = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Service submitted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            } else {
                $isDelivered = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Service cannot be submitted!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
        }

        $adminId = $_SESSION['ID'];
        $orderId = $_SESSION['orderId'];
      
        $query = "INSERT INTO `messages`(`mssgSubj`, `mssgTo`, `mssgFrom`, `mssgContent`, `mssgFile`, `isAdmin`, `serviceId`, `packageId`, `orderId`) 
        VALUES ('$mssgSubj', '$mssgTo', '$adminId', '$mssgContent', '$fileUrl', '1', '$serviceId', '$packageId', '$orderId')";
        
        if (mysqli_query($conn, $query)) {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Message sent successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        } else {
            $alertMssg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            The message failed to be sent.
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
    <title>CodeTech Admin| Service Message</title>

    <?php include 'links.php' ?>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        *:focus {
            outline: none;
        }
    </style>
</head>

<body class="position-relative">
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main pb-5 mb-5">

        <div class="container-fluid">
            <div class="card shadow-none border">
                <div class="card-header">
                    <div class="d-flex align-items-center card-title m-0 p-0">
                        <a href="<?=$prevDir?>"
                            class="rounded-circle btn btn-light d-flex align-items-center justify-content-center me-2"
                            style="width: 40px; height: 40px;"><i class="fw-bold bi bi-arrow-left"></i></a>Project
                        Message
                    </div>
                </div>
                <div class="card-body px-3">
                    <?= $alertMssg ?>
                    <?= $isDelivered ?>

                    <form class="ps-2 pe-0" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST"
                        enctype="multipart/form-data">
                        <div class="mb-3 position-relative">
                            <input type="hidden" name="serviceId" value="<?= $serviceId ?>">
                            <input type="text" name="mssgTo" id="mssgTo" class="form-control" placeholder="To:"
                                value="<?= $mssgTo ?>" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="mssgSubj" id="mssgSubj" class="form-control"
                                placeholder="Subject: ">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="mssgContent" id="mssgContent" cols="30" rows="10"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload a File (Optional)</label>
                            <input type="file" class="form-control" name="mssgFile">
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="createNewMssg" class="btn btn-primary"><i
                                    class="bi bi-send"></i>&nbsp;Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>

</body>

</html>