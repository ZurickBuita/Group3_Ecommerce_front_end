<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
  header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
  header("Location: ../customer/category.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CodeTech | Admin Dashboard</title>

    <?php include 'links.php' ?>

</head>

<body class="position-relative">
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <?php
        $query = "SELECT *, COUNT(*) AS `TotalProject`FROM `project_proposal`";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        ?>
                <!-- Total Project Card -->
                <a href="allProjects.php" class="col-md-3 col-sm-6">
                    <div class="card shadow-none border info-card sales-card" style="height: 160px">
                        <div class="card-body px-3">
                            <h5 class="card-title">Total Project</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-book-fill"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?= $row['TotalProject'] ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </a><!-- End Total Project Card -->
                <?php
        ?>

                <?php
        $query = "SELECT COUNT(*) AS `pendingPayment` FROM `project_proposal` WHERE `status` = '1'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        ?>
                <!-- Pending Payments Project Card -->
                <a href="pendingPayments.php" class="col-md-3 col-sm-6">
                    <div class="card shadow-none border info-card sales-card" style="height: 160px">
                        <div class="card-body px-3">
                            <h5 class="card-title py-3 pb-1 m-0">Pending Payment Project</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?= $row['pendingPayment'] ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </a><!-- End Pending Payments Project Card -->
                <?php
        ?>

                <?php
        $query = "SELECT COUNT(*) AS `pendingPayment` FROM `project_proposal` WHERE `status` = '2'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        ?>
                <!-- Running Projects Project Card -->
                <a href="runningProjects.php" class="col-md-3 col-sm-6">
                    <div class="card shadow-none border info-card sales-card" style="height: 160px">
                        <div class="card-body px-3">
                            <h5 class="card-title">Running Project</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-book-half"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?= $row['pendingPayment'] ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </a><!-- End Running Projects Project Card -->
                <?php
        ?>

                <?php
        $query = "SELECT COUNT(*) AS `pendingPayment` FROM `project_proposal` WHERE `status` = ''";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        ?>
                <!-- Running Projects Project Card -->
                <a href="runningProjects.php" class="col-md-3 col-sm-6">
                    <div class="card shadow-none border info-card sales-card" style="height: 160px">
                        <div class="card-body px-3">
                            <h5 class="card-title">Completed Project</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-book-fill"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?= $row['pendingPayment'] ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </a><!-- End Running Projects Project Card -->
                <?php
        ?>

                <?php
        $query = "SELECT COUNT(*) AS `allServices` FROM `service`";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        ?>
                <!-- Total Project Card -->
                <a href="allProjects.php" class="col-md-3 col-sm-6">
                    <div class="card shadow-none border info-card sales-card" style="height: 160px">
                        <div class="card-body px-3">
                            <h5 class="card-title">Total Services</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-book-fill"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?= $row['allServices'] ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </a><!-- End Total Project Card -->
                <?php
        ?>
            </div>
        </section>

    </main><!-- End #main -->


    <?php include 'footer.php' ?>

    <?php include 'script.php' ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>