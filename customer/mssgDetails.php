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
    <title>CodeTech | Sent Messages</title>
    <?php include "../links.php" ?>
    <link rel="Stylesheet" href="../style.css">
</head>

<body class="bg-light position-relative">
    <?php include 'header.php' ?>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-4 mb-0 mx-3">
            <li class="breadcrumb-item"><a href="homePage.php">Codetech</a></li>
            <li class="breadcrumb-item active">Message Details</li>
        </ol>
    </nav>
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
                                class="border-0 border-bottom list-group-item list-group-item-action">&nbsp;&nbsp;<i
                                    class="bi bi-inbox"></i>&nbsp;Inbox</a>
                            <a href="sentMssg.php"
                                class="border-0 border-bottom list-group-item list-group-item-action active text-white">&nbsp;&nbsp;<i
                                    class="bi bi-send-check"></i>&nbsp;Sent</a>
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-12 ps-0">
                        <div class="card-header">
                            <div class="card-title mb-0 d-flex align-items-center">
                                <a href="sentMssg.php"
                                    class="rounded-circle btn btn-light d-flex align-items-center justify-content-center me-2"
                                    style="width: 40px; height: 40px;"><i class="fw-bold bi bi-arrow-left"></i></a>
                                Create new Message
                            </div>
                        </div>
                        <div class="card-body overflow-auto" style="height: 500px">
                            <?php
                            $userId = $_SESSION['ID'];
                            $query = "SELECT * FROM `customer` WHERE `Id` = '$userId'";
                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            $img = $row['imgUrl'];

                            $id = $_GET['id'];
                            $query = "SELECT * FROM `messages` WHERE `mssgId` = '$id'";
                            if ($result = mysqli_query($conn, $query)) {
                                if ($row = mysqli_fetch_assoc($result)) {
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
                                <div class="d-flex align-items-center justify-content-between fs-2 mb-0 pb-0">
                                    <div class="card-title">
                                        <?= $row['mssgSubj'] ?>
                                    </div>
                                    <small class="fs-6">
                                        <?= $row['mssgDate'] ?>
                                    </small>
                                </div>

                                <small><b>To:</b>
                                    <span class="d-inline-block ms-2 bg-secondary bg-opacity-25 px-2 py-1 rounded"><img
                                            src="../uploads/customer_img/<?= $img ?>"
                                            style="width: 30px; height: 30px; border-radius: 50%;" />&nbsp;
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
                            <div class="alert alert-light" role="alert">
                                <i class='fs-1 bi bi-<?= $icon ?>'></i>
                                <small>
                                    <?= $row['mssgFile'] ?>
                                </small>
                            </div>
                            <?php
                                    }
                                    ?>
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