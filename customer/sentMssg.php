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
            <li class="breadcrumb-item active">Sent Messages</li>
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
                            <div class="card-title mb-0">Create new Message</div>
                        </div>
                        <div class="card-body overflow-auto" style="height: 500px">
                            <div class="list-group rounded-0">
                                <?php
                                $id = $_SESSION['ID'];
                                $query = "SELECT * FROM `messages` WHERE `mssgFrom` = '$id' AND `isAdmin` = '0' ORDER BY mssgDate DESC";
                                if ($result = mysqli_query($conn, $query)) {
                                    if (mysqli_num_rows($result) == 0) {
                                        ?>
                                <h1 class="text-secondary"><i class="bi bi-exclamation-triangle-fill"></i>
                                    No Result Found.</h1>
                                <?php
                                    } else {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                <a href="mssgDetails.php?id=<?= $row['mssgId'] ?>"
                                    class="d-flex align-items-center justify-content-between border-0 border-bottom list-group-item list-group-item-action">
                                    <div>
                                        <small><b>To:</b>
                                            <?= $row['mssgTo'] ?>
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center flex-fill mx-3">
                                        <small class="fs-6 fw-bold me-2">
                                            <?= $row['mssgSubj'] ?>
                                        </small>
                                        <small>
                                            <?= $row['mssgContent'] ?>
                                        </small>
                                    </div>
                                    <div>
                                        <small>
                                            <?= $row['mssgDate'] ?>
                                        </small>
                                    </div>
                                </a>
                                <?php
                                        }
                                    }
                                }
                                ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "../footer.php" ?>

</body>

</html>