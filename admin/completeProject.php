<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}

$alertMssg = "";
$userId = test_input($_SESSION['ID']);
$userType = test_input($_SESSION['user']);

if (isset($_GET['notificationId']) && isset($_GET['isRead'])) {
    $notificationId = $_GET['notificationId'];
    $query = "UPDATE `notification` SET `isRead`='1' WHERE notificationId = '$notificationId'";
    $query_run = mysqli_query($conn, $query);
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
    <title>CodeTech Admin|Pending Projects</title>

    <?php include 'links.php' ?>

</head>

<body class="position-relative">
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main pb-5 mb-5">

        <div class="pagetitle">
            <h1>Completed Projects</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Completed Projects</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <?php echo $alertMssg ?>
        <?php
        $query = "SELECT *
        FROM ((project_proposal
        INNER JOIN customer ON project_proposal.customerId = customer.Id)
        INNER JOIN category ON project_proposal.projectCategory = category.categoryId) WHERE `status` = '4';";
        $query_run = mysqli_query($conn, $query);

        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $data) {
                $post_date = $data['postedAt']; // the date and time the post was made, retrieved from the database
                $current_date = date('Y-m-d H:i:s'); // the current date and time
                $posted_ago = "";
                // calculate the difference between the post date and the current date in seconds
                $diff = strtotime($current_date) - strtotime($post_date);

                // check if the post is less than a minute old
                if ($diff < 60) {
                    $posted_ago = 'Just now';
                }
                // check if the post is less than an hour old
                else if ($diff < 3600) {
                    $minutes = floor($diff / 60);
                    $posted_ago = $minutes . ' minutes ago';
                }
                // check if the post is less than a day old
                else if ($diff < 86400) {
                    $hours = floor($diff / 3600);
                    $posted_ago = $hours . ' hours ago';
                }
                // otherwise, the post is at least a day old
                else {
                    $days = floor($diff / 86400);
                    $posted_ago = $days . ' days ago';
                }

                ?>
        <div class="card shadow-none border">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>
                    <span class="badge text-bg-primary">$
                        <?= $data['budgetOffer'] ?>.00
                    </span>
                </h5>
                <h5>
                    <?php
                            if ($data['status'] === '0') {
                                echo "<span class='badge text-bg-warning'>Pendings</span>";
                            } else if ($data['status'] === '1') {
                                echo "<span class='badge text-bg-warning'>Pending Payments</span>";
                            } else if ($data['status'] === '2') {
                                echo "<span class='badge text-bg-warning'>Running</span>";
                            } else if ($data['status'] === '3') {
                                echo "<span class='badge text-bg-success'>Submitted</span>";
                            } else if ($data['status'] === '4') {
                                echo "<span class='badge text-bg-success'>Completed</span>";
                            } else if ($data['status'] === '5') {
                                echo "<span class='badge text-bg-danger'>Cancelled</span>";
                            }
                            ?>
                </h5>
            </div>
            <div class="card-body px-3">
                <h5 class="card-title m-0 pb-0">
                    <?= $data['projectTitle'] ?>
                </h5>
                <small class="fw-bold text-secondary d-flex align-items-center mb-3" style="font-size: 12px;">
                    <span class="me-1 material-symbols-rounded" style="font-size: 14px;">
                        schedule
                    </span>
                    <span class="me-3">
                        <?= $posted_ago ?>
                    </span>

                    <span class="me-1 material-symbols-rounded" style="font-size: 14px;">
                        category
                    </span>
                    <span class="me-3">
                        <?= $data['categoryName'] ?>
                    </span>
                    <span class="me-1 material-symbols-rounded" style="font-size: 14px;">
                        handshake
                    </span>
                    <span class="me-3">
                        <?= $data['projectType'] ?>
                    </span>
                </small>
                <small class="card-text">
                    <?= $data['projectDetails'] ?>
                </small>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row g-3 d-flex align-items-center justify-content-between">
                    <a href="#" class="col-md-6 col-sm-12 d-flex align-items-center">
                        <img class="rounded-circle me-2" src="../uploads/customer_img/<?= $data['imgUrl'] ?>" width="40"
                            height="40">
                        <div>
                            <small class="fw-bold text-secondary">
                                <?= $data['username'] ?>
                            </small>

                        </div>
                    </a>

                </div>
            </div>
        </div>
        </div>
        <?php
            }
        } else {
            echo "<div class='card shadow-none border' style='height: 280px;'>
            <div class='card-body d-flex align-items-center justify-content-center flex-column pt-3'>
            <span class='material-symbols-outlined' style='font-size: 65px'>
    sentiment_dissatisfied
    </span>
              <h1 class='card-title m-0'>Nothing found</h1>
            </div>
          </div>";
        }

        ?>
    </main><!-- End #main -->

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>

</body>

</html>