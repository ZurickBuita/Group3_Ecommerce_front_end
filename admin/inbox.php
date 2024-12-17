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
    <title>CodeTech Admin| Inbox</title>

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
            <h1>Inbox</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Inbox</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="container-fluid">
            <div class="card shadow-none border">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title m-0 p-0">Inbox</div>
                    <a href="createMssg.php" class="btn btn-primary">Create New Message</a>
                </div>
                <div class="card-body pt-3 overflow-auto" style="height: 400px;">
                    <div class="list-group rounded-0">

                        <?php
                        $query = "SELECT * FROM 
                        (`messages` INNER JOIN customer ON messages.mssgFrom = customer.Id) WHERE `isAdmin`= '0'";
                        if ($result = mysqli_query($conn, $query)) {
                            if (mysqli_num_rows($result) == 0) {
                                ?>
                        <h3 class="text-secondary p-3"><i class="bi bi-exclamation-triangle-fill"></i>
                            No Result Found.</h3>
                        <?php
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                        <a href="inboxDetail.php?id=<?= $row['mssgId'] ?>"
                            class="d-flex align-items-center justify-content-between border-0 border-bottom list-group-item list-group-item-action">
                            <div>
                                <small>
                                    <img src="../uploads/customer_img/<?= $row['imgUrl'] ?>"
                                        style="width: 35px; height: 35px; border-radius: 50%" />
                                    <?= $row['email'] ?>
                                </small>
                            </div>
                            <div class="d-flex align-items-center flex-fill mx-3">
                                <small class="fw-bold me-2">
                                    <?= $row['mssgSubj'] ?>
                                </small>
                                <small>
                                    <?= $row['mssgContent'] ?>
                                </small>
                            </div>
                            <div>
                                <small>
                                    <?= $formatted_timestamp = date("M. d-Y", strtotime($row["mssgDate"])); ?>
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
    </main>

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>

</body>

</html>