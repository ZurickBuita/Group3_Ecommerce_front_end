<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}

function test_input($data)
{
    $data = trim($_POST['newMssg']);
    $data = stripcslashes($_POST['newMssg']);
    $data = htmlspecialchars($_POST['newMssg']);

    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CodeTech Admin| Sent Messages</title>

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
        <div class="pagetitle ms-2">
            <h1>Messages Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Message Details</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="container-fluid">
            <div class="card shadow-none border">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title m-0 p-0 d-flex align-items-center">
                        <a href="sentMssg.php"
                            class="rounded-circle btn btn-light d-flex align-items-center justify-content-center me-2"
                            style="width: 40px; height: 40px;"><i class="fw-bold bi bi-arrow-left"></i></a>Message
                        Details
                    </div>
                    <a href="createMssg.php" class="btn btn-primary">Create New Message</a>
                </div>

                <?php
                $userId = $_SESSION['ID'];
                $query = "SELECT * FROM `admin` WHERE `Id` = '$userId'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $img = $row['imgUrl'];

                $id = $_GET['id'];
                $query = "SELECT * FROM `messages` WHERE `mssgId` = '$id'";

                if ($result = mysqli_query($conn, $query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Get the file extension
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
                <div class="card-body overflow-auto px-3" style="height: 500px">
                    <div class="d-flex flex-column">
                        <span class="card-title mb-0 pb-0">
                            <?= $row['mssgSubj'] ?>
                        </span>
                        <small>
                            <b>To:</b>
                            <span class="d-inline-block ms-2 bg-secondary bg-opacity-25 px-2 py-1 rounded">
                                <img src="../uploads/admin_img/<?= $img ?>"
                                    style="width: 30px; height: 30px; border-radius: 50%;" />
                                &nbsp;
                                <?= $row['mssgTo'] ?>
                            </span>
                        </small>
                    </div>
                    <div class="my-3">
                        <small>
                            <?= $row['mssgContent'] ?>
                        </small>
                    </div>
                    <?php
                            if (!empty($row['mssgFile'])) {
                                ?>
                    <div class="alert alert-secondary" role="alert">
                        <?php
                                    if ($icon == "card-image") {
                                        ?>
                        <img src='../uploads/FileMssg/<?=$row[' mssgFile']?>' width='180' height=100'
                        data-bs-toggle='modal'
                        data-bs-target='#exampleModal'>
                        <small>
                            <?=$row['mssgFile']?>
                        </small>

                        <div class="modal fade bg-dark bg-opacity-50" id="exampleModal" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content p-0 m-0  position-relative">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 text-white"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                    <div class="modal-body p-0 m-0 ">
                                        <img src='../uploads/FileMssg/<?=$row[' mssgFile']?>' width='100%'
                                        height='auto'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                    } else {
                                        ?>
                        <i class='fs-1 bi bi-<?= $icon ?>'></i>
                        <small>
                            <?=$row['mssgFile'] ?>
                        </small>
                        <?php
                                    }
                                    ?>
                    </div>
                    <a href="../uploads/FileMssg/<?= $mssgFile ?>" class="btn btn-primary px-3 py-2 text-white"
                        download>
                        <i class="bi bi-download"></i>&nbsp;Download File
                    </a>
                    <?php
                            }
                            ?>

                </div>
                <?php
                    }
                }
                ?>

            </div>
        </div>
    </main>

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>
</body>

</html>