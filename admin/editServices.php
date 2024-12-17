<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}

$serviceTitle = $serviceImg = $serviceDescription = $serviceCategory = $imgUrl = "";
$serviceTitleErr = $serviceDescriptionErr = $serviceCategoryErr = "";
$values = true;
$alertMssg = null;

$basicPackage = $price1 = $deliveryWithin1 = $revisionLimit1 = $includedSection1 = "";
$standardPackage = $price2 = $deliveryWithin2 = $revisionLimit2 = $includedSection2 = "";
$premiumPackage = $price3 = $deliveryWithin3 = $revisionLimit3 = $includedSection3 = "";

$previousCategory = "";
$serviceId = "";
$prevDir = $_SESSION['prevDir'];

if (isset($_SESSION['serviceId'])) {
    $serviceId = $_SESSION['serviceId'];

    $query = "SELECT * FROM `service` WHERE serviceId = '$serviceId'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $previousCategory = $row['categoryId'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $serviceTitle = test_input($_POST['serviceTitle']);
    $serviceDescription = test_input($_POST['serviceDescription']);
    $serviceCategory = test_input($_POST['category']);

    $basicPackage = test_input($_POST['basicPackage']);
    $standardPackage = test_input($_POST['standardPackage']);
    $premiumPackage = test_input($_POST['premiumPackage']);

    $price1 = test_input($_POST['price1']);
    $price2 = test_input($_POST['price2']);
    $price3 = test_input($_POST['price3']);

    $deliveryWithin1 = test_input($_POST['deliveryWithin1']);
    $deliveryWithin2 = test_input($_POST['deliveryWithin2']);
    $deliveryWithin3 = test_input($_POST['deliveryWithin3']);

    $revisionLimit1 = test_input($_POST['revisionLimit1']);
    $revisionLimit2 = test_input($_POST['revisionLimit2']);
    $revisionLimit3 = test_input($_POST['revisionLimit3']);

    $includedSection1 = test_input($_POST['includedSection1']);
    $includedSection2 = test_input($_POST['includedSection2']);
    $includedSection3 = test_input($_POST['includedSection3']);


    if (isset($_POST['postService'])) {
        if (empty($serviceTitle)) {
            $values = false;
            $serviceTitleErr = "Service Title is required!";
        }
        if (empty($serviceDescription)) {
            $values = false;
            $serviceDescriptionErr = "Service Description is required!";
        }
        if (empty($serviceCategory)) {
            $values = false;
            $serviceCategoryErr = "Service Category is required!";
        }

        if (isset($_FILES['image'])) {
            $img_name = $_FILES['image']['name'];
            $img_size = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $img_err = $_FILES['image']['error'];

            if ($img_err === 0) {
                if ($img_size > 2225000) {
                    $values = false;
                    echo "<script>alert('Sorry, your file is too large.')</script>";
                } else {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);

                    $allowed_exs = array("jpg", "jpeg", "png");

                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $imgUrl = uniqid("IMG-", true) . '.' . $img_ex_lc;
                        $img_upload_path = "../uploads/services_img/" . $imgUrl;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        echo "<script>alert('You can't upload files of this type')</script>";
                    }
                }
            }
        }

        if ($values === true) {
            $id = test_input($_POST['serviceId']);
            $query = "SELECT * FROM `service` WHERE `serviceId` = '$id'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $serviceId = $row['serviceId'];

            if (empty($imgUrl)) {
                $query = "UPDATE `service` SET `serviceTitle`='$serviceTitle',`serviceDescription`='$serviceDescription', `categoryId`='$serviceCategory' WHERE `serviceId` = '$serviceId'";
            } else {
                $query = "SELECT * FROM `service` WHERE `serviceId` = '$serviceId'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $prevImg = $row['imgUrl'];

                unlink("../uploads/services_img/$prevImg");

                $query = "UPDATE `service` SET `serviceTitle`='$serviceTitle',`serviceDescription`='$serviceDescription',`imgUrl`='$imgUrl',`categoryId`='$serviceCategory' WHERE `serviceId` = '$serviceId'";
            }
            $query_run = mysqli_query($conn, $query);


            $query1 = "UPDATE `package` SET `packageType`='$basicPackage',`price`='$price1',`deliveryWithIn`='$deliveryWithin1',`revisionLimit`='$revisionLimit1',`sectionIncluded`='$includedSection1' WHERE packageType = '1' AND `serviceId` = '$serviceId'";
            $query2 = "UPDATE `package` SET `packageType`='$standardPackage',`price`='$price2',`deliveryWithIn`='$deliveryWithin2',`revisionLimit`='$revisionLimit2',`sectionIncluded`='$includedSection2' WHERE packageType = '2' AND `serviceId` = '$serviceId'";
            $query3 = "UPDATE `package` SET `packageType`='$premiumPackage',`price`='$price3',`deliveryWithIn`='$deliveryWithin3',`revisionLimit`='$revisionLimit3',`sectionIncluded`='$includedSection3' WHERE packageType = '3' AND `serviceId` = '$serviceId'";

            // combine the three queries into a single string
            $query = $query1 . ";" . $query2 . ";" . $query3;

            // Execute multi query
            if (mysqli_multi_query($conn, $query)) {
                do {
                    // Store first result set
                    if ($result = mysqli_store_result($conn)) {
                        while ($row = mysqli_fetch_row($result)) {
                            printf("%s\n", $row[0]);
                        }
                        mysqli_free_result($result);
                    }

                    //Prepare next result set
                } while (mysqli_next_result($conn));

                $alertMssg = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                A new service is created successfully.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                             </div>';
            } else {
                $alertMssg =
                    '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                       A new service cannot be created.
                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>';
            }
        }
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CodeTech Admin|Edit Service</title>

    <?php include 'links.php' ?>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        .navbar-pills {
            padding: 15px 20px;
            text-align: center;
            cursor: pointer;
        }

        .navbar-pills.active {
            background-color: #001b4d;
            color: #fff;
        }

        .navbar-pills:hover {
            background: #001b4d;
            color: #fff;
        }

        .pages {
            display: grid;
            place-items: center;
        }

        .page {
            display: none;
            padding: 15px 0;
            width: 100%;
        }

        .page.active {
            display: block;
        }
    </style>
</head>

<body class="position-relative">
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Edit Service</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?=$prevDir?>">All Services</a></li>
                    <li class="breadcrumb-item active">Edit Service</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="card shadow-none border">
            <div class="card-header fw-bold">
                <div class="card-title m-0"> Service Info</div>
            </div>
            <div class="card-body px-3">

                <?=$alertMssg ?>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="form"
                    enctype="multipart/form-data">
                    <?php

                    $query = "SELECT * FROM `service` WHERE serviceId = '$serviceId'";

                    if ($result = mysqli_query($conn, $query)) {
                        if (mysqli_num_rows($result) === 1) {
                            foreach ($result as $data) {
                                ?>
                    <input type="hidden" name="serviceId" value='<?= $serviceId ?>'>
                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-light">Title of Service</label>
                        <input type="text" class="form-control" name="serviceTitle"
                            placeholder="Enter your service title" value="<?= $data['serviceTitle'] ?>">
                    </div>

                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-light">Service Image</label>
                        <div class="input-group flex-nowrap">
                            <input type="file" class="form-control" name="image" placeholder="Browse">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="alert alert-secondary py-2 alert-dismissible fade show" role="alert">
                            <img src="../uploads/services_img/<?= $data['imgUrl'] ?>" alt="Previous Image" width="80"
                                height="50">
                            <span>
                                <?= $data['imgUrl'] ?>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-light">About Service</label>
                        <textarea class="form-control" rows="8"
                            name="serviceDescription"><?= $data['serviceDescription'] ?></textarea>
                    </div>
                    <?php
                            }
                        }
                    }
                    ?>

                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-light">Select Category</label>

                        <select class="form-select" name="category" aria-label="Default select example">
                            <option>Select</option>
                            <?php
                            $query = "SELECT * FROM `category`";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $data) {
                                    if ($data['categoryId'] == $previousCategory) {
                                        ?>
                            <option selected value="<?= $data['categoryId'] ?>">
                                <?= $data['categoryName'] ?>
                            </option>
                            <?php
                                    } else {
                                        ?>
                            <option value="<?= $data['categoryId'] ?>">
                                <?= $data['categoryName'] ?>
                            </option>
                            <?php
                                    }

                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3 mt-3 text-secondary">
                        <label class="form-label fw-normal">Packages</label>
                        <div class="col bg-white d-flex flex-column position-static">

                            <div class="navbar-items w-100 d-flex shadow-sm">
                                <span class="navbar-pills active" data-switcher data-tab="1">Basic</span>
                                <span class="navbar-pills" data-switcher data-tab="2">Standard</span>
                                <span class="navbar-pills" data-switcher data-tab="3">Premium</span>
                            </div>

                            <div class="pages">
                                <?php
                                $query = "SELECT * FROM `package` WHERE `serviceId` = '$serviceId'";

                                if ($result = mysqli_query($conn, $query)) {
                                    if (mysqli_num_rows($result) > 0) {
                                        foreach ($result as $data) {
                                            if ($data['packageType'] === '1') {
                                                ?>
                                <section class="page active" data-page="1">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold" style="color: #001b4d;">Basic Package</span>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <input type="hidden" name="basicPackage" value="1">
                                        <label class="form-label fw-normal">Price</label>
                                        <input type="text" class="form-control" placeholder="Enter Price" name="price1"
                                            value="<?= $data['price'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">Delivery within</label>
                                        <input type="text" class="form-control" placeholder="Enter Delivery Time"
                                            name="deliveryWithin1" value="<?= $data['deliveryWithIn'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">Revision Limit</label>
                                        <input type="text" class="form-control" placeholder="Enter Revision Limit"
                                            name="revisionLimit1" value="<?= $data['revisionLimit'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">What's included section</label>
                                        <input type="text" class="form-control" name="includedSection1"
                                            value="<?= $data['sectionIncluded'] ?>" required>
                                    </div>

                                </section>
                                <?php
                                            } else if ($data['packageType'] === '2') {
                                                ?>
                                <section class="page" data-page="2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-bold" style="color: #001b4d;">Standard Package</span>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <input type="hidden" name="standardPackage" value="2">
                                        <label class="form-label fw-normal">Price</label>
                                        <input type="text" class="form-control" placeholder="Enter Price" name="price2"
                                            value="<?= $data['price'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">Delivery within</label>
                                        <input type="text" class="form-control" placeholder="Enter Delivery Time"
                                            name="deliveryWithin2" value="<?= $data['deliveryWithIn'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">Revision Limit</label>
                                        <input type="text" class="form-control" placeholder="Enter Revision Limit"
                                            name="revisionLimit2" value="<?= $data['revisionLimit'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">What's included section</label>
                                        <input type="text" class="form-control" name="includedSection2"
                                            value="<?= $data['sectionIncluded'] ?>" required>
                                    </div>

                                </section>
                                <?php
                                            } else if ($data['packageType'] === '3') {
                                                ?>
                                <section class="page" data-page="3">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold" style="color: #001b4d;">Premium Package</span>
                                    </div>
                                    <div class="mb-3 mt-3 text-secondary">
                                        <input type="hidden" name="premiumPackage" value="3">
                                        <label class="form-label fw-normal">Price</label>
                                        <input type="text" class="form-control" placeholder="Enter Price" name="price3"
                                            value="<?= $data['price'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">Delivery within</label>
                                        <input type="text" class="form-control" placeholder="Enter Delivery Time"
                                            name="deliveryWithin3" value="<?= $data['deliveryWithIn'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">Revision Limit</label>
                                        <input type="text" class="form-control" placeholder="Enter Revision Limit"
                                            name="revisionLimit3" value="<?= $data['revisionLimit'] ?>" required>
                                    </div>

                                    <div class="mb-3 mt-3 text-secondary">
                                        <label class="form-label fw-normal">What's included section</label>
                                        <input type="text" class="form-control" name="includedSection3"
                                            value="<?= $data['sectionIncluded'] ?>" required>
                                    </div>
                                </section>
                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>

                                <div class="container p-0">
                                    <input type="submit" value="Post Service" class="btn btn-primary"
                                        name="postService">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main><!-- End #main -->

    <?php include 'footer.php' ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <script src="../js/tabSwitcher.js"></script>
    <?php include "script.php" ?>
</body>

</html>