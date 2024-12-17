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

        <div class="container-fluid">
            <div class="card shadow-none border">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title m-0 p-0 d-flex align-items-center">
                        <a href="inbox.php"
                            class="rounded-circle btn btn-light d-flex align-items-center justify-content-center me-2"
                            style="width: 40px; height: 40px;"><i class="fw-bold bi bi-arrow-left"></i></a>Message
                        Details
                    </div>
                    <a href="createMssg.php" class="btn btn-primary">Create New Message</a>
                </div>

                <?php
                $id = $_GET['id'];
                $query = "SELECT * FROM 
                (`messages` INNER JOIN customer ON messages.mssgFrom = customer.Id) WHERE `mssgId` = '$id'";

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
                <div class="card-body overflow-auto px-3" style="height: 400px">
                    <div class="d-flex flex-column">
                        <span class="card-title mb-0 pb-0">
                            <?= $row['mssgSubj'] ?>
                        </span>
                        <small>
                            <b>From:</b>
                            <span class="d-inline-block ms-2 bg-secondary bg-opacity-25 px-2 py-1 rounded">
                                <img src="../uploads/customer_img/<?= $row['imgUrl'] ?>"
                                    style="width: 30px; height: 30px; border-radius: 50%;" />
                                &nbsp;
                                <?= $row['email'] ?>
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
                        <i class='fs-1 bi bi-<?= $icon ?>'></i>
                        <small>
                            <?= $row['mssgFile'] ?>
                        </small>
                    </div>
                    <?php
                            }
                            ?>

                </div>
                <div class="card-footer">
                    <a href="replyMssg.php?id=<?= $row['mssgFrom'] ?>" class="btn btn-warning"><i
                            class="bi bi-reply"></i>&nbsp;<small>Reply</small></a>
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