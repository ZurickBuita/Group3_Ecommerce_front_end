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
    <title>CodeTech Admin|Order Details</title>

    <?php include 'links.php' ?>

    <style>
        ::-webkit-scrollbar {
            display: none;
        }
    </style>

</head>

<body>
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main pb-5 mb-5">
        <section class="card shadow-none border">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="card-title">Order Details</span>
                <a href="orders.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i>&nbsp;Go Back</a>
            </div>
            <div class="card-body py-3 px-0 overflow-hidden">
                <div class="row bg-light d-flex align-items-center px-3 py-2">
                    <div class="col-md-4 col-sm-12 p-3">
                        <span class="fw-bold">Date Placed</span>
                    </div>
                    <div class="col-md-8 col-sm-12">April 26, 2023 05:39 AM</div>
                </div>
                <div class="row d-flex align-items-center px-3 py-2">
                    <div class="col-md-4 col-sm-12 p-3">
                        <span class="fw-bold">Status</span>
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <span class="badge bg-success">Delivered</span>
                    </div>
                </div>
                <div class="row bg-light d-flex align-items-center px-3 py-2">
                    <div class="col-md-4 col-sm-12 p-3">
                        <span class="fw-bold">Expected Delivery Date</span>
                    </div>
                    <div class="col-md-8 col-sm-12">April 26, 2023 05:39 AM</div>
                </div>
                <div class="row d-flex align-items-center px-3 py-2">
                    <div class="col-md-4 col-sm-12 p-3">
                        <span class="fw-bold">Total</span>
                    </div>
                    <div class="col-md-8 col-sm-12">$30.00</div>
                </div>
                <div class="row bg-light d-flex align-items-center px-3 py-2">
                    <div class="col-md-4 col-sm-12 p-3">
                        <span class="fw-bold">Service</span>
                    </div>
                    <div class="col-md-8 col-sm-12 d-flex align-items-center">
                        <img src="https://snop.me/public/storage/gigs/gallery/large/100106615E4D9F4C9DCF.jpg" width="50"
                            height="50">
                        <div class="ms-3">
                            <span>Do on page SEO with rank math pro and technical
                                optimization</span><br>
                            <small class="fw-bold text-secondary">Digital Marketing</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>
</body>

</html>