<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}

$orderId = $_SESSION['orderId'];
$serviceId = $_SESSION['serviceId'];
$packageId = $_SESSION['packageId'];
$customerId = $_SESSION['customerId'];
$prevDir = $_SESSION['previousDir'];

$query = "SELECT * FROM `messages` WHERE `orderId` = '$orderId'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$mssgSubj = $row['mssgSubj'];
$mssgContent = $row['mssgContent'];
$mssgFrom = $row['mssgFrom'];
$mssgFile = $row['mssgFile'];
$icon = null;

$ext = pathinfo($row['mssgFile'], PATHINFO_EXTENSION);

// Check the file extension and display the appropriate Bootstrap icon
if ($ext == "pdf") {
    $icon = "file-pdf";
} else if ($ext == "doc" || $ext == "docx") {
    $icon = "file-word";
} else if ($ext == "xls" || $ext == "xlsx") {
    $icon = "file-excel";
} else if ($ext == "ppt" || $ext == "pptx") {
    $icon = "file-powerpoint";
} else if ($ext == "jpg" || $ext == "png" || $ext == 'jpeg') {
    $icon = "card-image";
} else if ($ext == "zip" || $ext == "rar") {
    $icon = "file-earmark-zip";
} else {
    $icon = "file";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeTech Customer| Delivered Work</title>
    <?php include "../links.php" ?>
    <link rel="Stylesheet" href="../style.css">

</head>

<body class="bg-light position-relative">
    <?php include 'header.php' ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-4 mx-3">
            <li class="breadcrumb-item"><a href="homePage.php">Codetech</a></li>
            <li class="breadcrumb-item"><a href="<?=$prevDir?>">Manage Orders</a></li>
            <li class="breadcrumb-item active">Delivered Work</li>
        </ol>
    </nav>

    <main class="container-fluid p-4 pt-0">
        <div class="container-fluid">
            <div class="card shadow-none border">
                <div class="bg-white card-header d-flex align-items-center justify-content-between">
                    <div class="card-title m-0 p-0 d-flex align-items-center">
                        <a href="<?=$prevDir?>"
                            class="rounded-circle btn btn-light d-flex align-items-center justify-content-center me-2"
                            style="width: 40px; height: 40px;"><i class="fw-bold bi bi-arrow-left"></i></a>Deliverd Work
                    </div>
                </div>
                <div class="card-body overflow-auto px-3" style="height: 500px">
                    <div class="d-flex flex-column">
                        <h3 class="card-title mb-3 pb-0">
                            <?=$mssgSubj?>
                        </h3>
                        <small>
                            <?php
                              $query = "SELECT * FROM `admin` WHERE `Id` = '$mssgFrom'";
                              $result = mysqli_query($conn, $query);
                              $row = mysqli_fetch_assoc($result);
                              $imgUrl = $row['imgUrl'];
                              $email = $row['email'];

                              ?>
                            <b>From:</b>
                            <span class="d-inline-block ms-2 bg-secondary bg-opacity-25 px-2 py-1 rounded">
                                <img src="../uploads/admin_img/<?=$imgUrl?>"
                                    style="width: 30px; height: 30px; border-radius: 50%;" />
                                &nbsp;
                                <?=$email?>
                            </span>
                            <?php
                            ?>
                        </small>
                    </div>
                    <div class="my-3">
                        <small>
                            <?=$mssgContent?>
                        </small>
                    </div>
                    <div class="alert alert-secondary" role="alert">
                        <?php
                        if ($icon == "card-image") {
                            ?>
                        <img src='../uploads/FileMssg/<?= $mssgFile ?>' width='180' height=100' data-bs-toggle='modal'
                            data-bs-target='#exampleModal'>
                        <small>
                            <?= $mssgFile ?>
                        </small>;

                        <div class="modal fade bg-dark bg-opacity-50" id="exampleModal" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content p-0 m-0  position-relative">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 text-white"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                    <div class="modal-body p-0 m-0 ">
                                        <img src='../uploads/FileMssg/<?= $mssgFile ?>' width='100%' height='auto'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        } else {
                            ?>
                        <i class='fs-1 bi bi-<?= $icon ?>'></i>
                        <small>
                            <?= $mssgFile ?>
                        </small>
                        <?php
                        }
                        ?>
                    </div>
                    <a href="../uploads/FileMssg/<?= $mssgFile ?>" class="btn btn-primary px-3 py-2 text-white"
                        download>
                        <i class="bi bi-download"></i>&nbsp;Download File
                    </a>
                </div>
            </div>
        </div>
    </main>
    <!-- #region -->

    <?php include "../footer.php" ?>
</body>

</html>