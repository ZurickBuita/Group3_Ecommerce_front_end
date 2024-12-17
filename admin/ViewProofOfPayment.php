<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}


$proposalId = $_SESSION['proposalId'];
$customerId = $_SESSION['customerId'];
$gcashName = $gcashNumber = $proofImg = "";
$query = "SELECT * FROM `proofofpayment` WHERE `projectId` = '$proposalId'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$values = false;

if (mysqli_num_rows($result) == 1) {
    $values = true;
    $gcashName = $row['gcashName'];
    $gcashNumber = $row['gcashNumber'];
    $proofImg = $row['proofImg'];
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
    <title>CodeTech Admin|View Proof of Payment</title>

    <?php include 'links.php' ?>

</head>

<body class="position-relative">
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main pb-5 mb-5">

        <div class="pagetitle">
            <h1>Proof Of Payment</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= $_SESSION['prevDir'] ?>">Pending Payments</a></li>
                    <li class="breadcrumb-item active">Proof of Payment</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="card">
            <div class="card-header">
                <div class="card-title mb-0">Proof of Payment</div>
            </div>
            <div class="card-body">
                <div class="row p-2">

                    <?php

                    if ($values == false) {
                        ?>
                        <div class="col-12 text-center text-secondary">
                            <h2>Nothing Found <i class="bi bi-emoji-frown"></i></h2>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gcash Name</label>
                                <input type="text" class="form-control" placeholder="Gcash Name" value="<?= $gcashName ?>"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gcash Number</label>
                                <input type="password" class="form-control" placeholder="Gcash Number"
                                    value="<?= $gcashNumber ?>" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="alert alert-secondary d-flex align-items-center justify-content-center" role="alert">
                                <img src='../uploads/proofImg/<?= $proofImg ?>' width='250' height='500'
                                    data-bs-toggle='modal' data-bs-target='#exampleModal'>
                            </div>

                            <div class="col">
                                <a href="<?= $_SESSION['prevDir'] ?>" class="btn btn-primary">Go Back</a>
                            </div>
                        </div>

                        <?php
                    }
                    ?>

                </div>
        </section>
    </main>



    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>

</body>

</html>