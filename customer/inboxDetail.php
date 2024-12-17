<?php
session_start();
require '../db_conn.php';


if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeTech | View Details</title>
    <?php include "../links.php" ?>
    <link rel="Stylesheet" href="../style.css">
    <style>
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-light position-relative">
    <?php include 'header.php' ?>

    <main class="container-fluid p-4">
        <div class="card overflow-hidden">
            <div class="card-header bg-white">
                <h5 class="card-title">Messages</h5>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-4 col-sm-12 p-0 border-end">
                        <div class="rounded-0 list-group">
                            <a href="createNewMessage.php"
                                class="border-0 border-bottom list-group-item list-group-item-action">&nbsp;&nbsp;<i
                                    class="bi bi-envelope-plus"></i>&nbsp;Create New Message</a>
                            <a href="inbox.php"
                                class="border-0 border-bottom list-group-item list-group-item-action active text-white">&nbsp;&nbsp;<i
                                    class="bi bi-inbox"></i>&nbsp;Inbox</a>
                            <a href="sentMssg.php"
                                class="border-0 border-bottom list-group-item list-group-item-action">&nbsp;&nbsp;<i
                                    class="bi bi-send-check"></i>&nbsp;Sent</a>
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-12 ps-0">
                        <div class="card-header">
                            <div class="card-title mb-0 d-flex align-items-center">
                                <a href="inbox.php"
                                    class="rounded-circle btn btn-light d-flex align-items-center justify-content-center me-2"
                                    style="width: 40px; height: 40px;"><i class="fw-bold bi bi-arrow-left"></i></a>
                                View Details
                            </div>
                        </div>
                        <div class="card-body overflow-auto" style="height: 400px">
                            <?php
                            $query = "SELECT * FROM `admin` WHERE `Id` = '1'";
                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            $img = $row['imgUrl'];

                            $id = $_SESSION['ID'];
                            $query = "SELECT * FROM customer WHERE `Id` = '$id'";
                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            $email = $row['email'];

                            $mssgId = $_GET['id'];
                            $query = "SELECT * FROM
                            (`messages` INNER JOIN admin ON messages.mssgFrom = admin.Id ) WHERE `mssgId` = '$mssgId'";

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
                            <div class="d-flex flex-column">
                                <span class="card-title mb-0 pb-0">
                                    <?= $row['mssgSubj'] ?>
                                </span>
                                <small>
                                    <b>From:</b>
                                    <span class="d-inline-block ms-2 bg-secondary bg-opacity-25 px-2 py-1 rounded">
                                        <img src="../uploads/admin_img/<?= $img ?>"
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
                            <a href="replyMssg.php" class="btn btn-warning"><i
                                    class="bi bi-reply"></i>&nbsp;<small>Reply</small></a>
                        </div>
                        <?php
                                }
                            }
                            ?>
                    </div>

                </div>
            </div>
        </div>
        </div>
    </main>

    <?php include "../footer.php" ?>
</body>

</html>