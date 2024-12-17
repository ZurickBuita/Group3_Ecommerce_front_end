<?php
    session_start();
    require '../db_conn.php';

    if (empty($_SESSION['user'])) {
        header("Location: ../index.php");
    } else if ($_SESSION['user'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    }

    $serviceId =  $_SESSION['serviceId'];
    $serviceTitle = "";
    $alertMssg = "";
    $query = "SELECT * FROM `service` WHERE serviceId = '$serviceId'";

    if ($result = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($result) === 1) {
            foreach ($result as $data) {
                $serviceTitle = $data['serviceTitle'];
                $_SESSION['serviceTitle'] = $serviceTitle;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['contactAdmin'])) {
            $_SESSION['packageId'] = $_POST['packageId'];
            $_SESSION['serviceId'] = $_POST['serviceId'];

            header("Location: createNewMessage.php");
        }

        if (isset($_POST['checkout'])) {
            $packageId = $_POST['packageId'];
            $customerId = $_SESSION['ID'];
            $_SESSION['packageId'] = $packageId;
            $_SESSION['serviceId'] = $_POST['serviceId'];
            $_SESSION['prevDir'] = 'service.php';

            header("Location: checkout.php");
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>CodeTech |
        <?=$serviceTitle?>
    </title>
    <!-- Bootstrap -->
    <?php include "../links.php" ?>
    <!-- StyleSheet -->
    <link rel="StyleSheet" href="../style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inconsolata&family=Open+Sans:wght@300&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Source Sans Pro', sans-serif;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        .brand-logo>span {
            font-style: italic;
            color: #8000ff;
        }

        .navbar-pills {
            width: 100%;
            padding: 15px 20px;
            text-align: center;
            cursor: pointer;
        }

        .navbar-pills.active {
            background: #001b4d;
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
            padding: 15px 20px;
            width: 100%;
        }

        .page.active {
            display: block;
        }
    </style>
</head>

<body class="bg-light position-relative">
    <?php include "header.php" ?>

    <div class="main px-4 pt-4 pb-1">
        <!-- BreadCrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="homePage.php">Codetech</a></li>
                <li class="breadcrumb-item active">
                    <?=$serviceTitle?>
                </li>
            </ol>
        </nav>
        <!-- BreadCrumb end -->
    </div>

    <div class="row mb-2 mx-2">
        <div class="col-md-7">
            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <?php
                $query = "SELECT * FROM (service INNER JOIN category ON service.categoryId = category.categoryId) WHERE serviceId = '$serviceId'";
                if ($result = mysqli_query($conn, $query)) {
                    if (mysqli_num_rows($result) > 0) {
                        foreach ($result as $data) {
                            ?>
                <div class="col bg-white p-4 d-flex flex-column">
                    <h3>
                        <?= $data['serviceTitle'] ?>
                    </h3>

                    <div class="w-100 mt-2">
                        <img class="w-100" src="../uploads/services_img/<?= $data['imgUrl'] ?>" height="auto">
                    </div>
                    <h4 class="mb-0 my-3">About This Service</h4>
                    <p class="card-text mb-auto my-2 text-secondary">
                        <?= $data['serviceDescription'] ?>
                    </p>
                </div>
                <?php
                        }
                    }
                }
                ?>
            </div>
        </div>

        <div class="col-md-5">
            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
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
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold" style="color: #001b4d;">BASIC Package - Popular</span>
                                <span class="h4">$<?= $data['price'] ?>.00</span>
                            </div>

                            <div class="text-secondary">
                                <p class="d-flex align-items-center">
                                    <span class="material-symbols-rounded">
                                        schedule
                                    </span>
                                    <small class="fw-bold">
                                        <?php 
                                            if($data['deliveryWithIn'] === '1') {
                                                echo $data['deliveryWithIn']. " Day";
                                            } else {
                                                echo $data['deliveryWithIn']. " Days";
                                            }
                                        ?>
                                    </small>
                                    &nbsp;&nbsp;
                                    <span class="material-symbols-rounded text-success">
                                        autorenew
                                    </span>
                                    <small class="fw-bold">
                                        <?= $data['revisionLimit'] ?>
                                    </small>
                                </p>

                                <p class="d-flex align-items-center">
                                    <span class="material-symbols-rounded text-success fw-bold">
                                        done
                                    </span>
                                    <small class="fw-bold">
                                        <?= $data['sectionIncluded'] ?>
                                    </small>
                                </p>

                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                                    <input type="hidden" name="packageId" value="<?=$data['Id']?>">
                                    <input type="hidden" name="serviceId" value="<?=$data['serviceId']?>">
                                    <button type="submit" name="checkout"
                                        class="btn btn-success w-100 my-1">Continue&nbsp;$<?=$data['price']?>.00
                                    </button>
                                </form>

                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                                    <input type="hidden" name="packageId" value="<?=$data['Id']?>">
                                    <input type="hidden" name="serviceId" value="<?=$data['serviceId']?>">
                                    <button type="submit" name="contactAdmin"
                                        class="btn btn-outline-secondary w-100 my-1 mb-4">Contact Sellers</button>
                                </form>
                            </div>
                        </section>
                        <?php
                                    } else if ($data['packageType'] === '2') {
                                        ?>
                        <section class="page" data-page="2">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold" style="color: #001b4d;">STANDARD Package - Recommended</span>
                                <span class="h4">$<?= $data['price'] ?>.00</span>
                            </div>

                            <div class="text-secondary">

                                <p class="d-flex align-items-center">
                                    <span class="material-symbols-rounded">
                                        schedule
                                    </span>
                                    <small class="fw-bold">
                                        <?php 
                                            if($data['deliveryWithIn'] === '1') {
                                                echo $data['deliveryWithIn']. " Day";
                                            } else {
                                                echo $data['deliveryWithIn']. " Days";
                                            }
                                        ?>
                                    </small>
                                    &nbsp;&nbsp;
                                    <span class="material-symbols-rounded text-success">
                                        autorenew
                                    </span>
                                    <small class="fw-bold">
                                        <?= $data['revisionLimit'] ?>
                                    </small>
                                </p>

                                <p class="d-flex align-items-center">
                                    <span class="material-symbols-rounded text-success fw-bold">
                                        done
                                    </span>
                                    <small class="fw-bold">
                                        <?=$data['sectionIncluded']?>
                                    </small>
                                </p>

                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                                    <input type="hidden" name="packageId" value="<?=$data['Id']?>">
                                    <input type="hidden" name="serviceId" value="<?=$data['serviceId']?>">
                                    <button type="submit" name="checkout"
                                        class="btn btn-success w-100 my-1">Continue&nbsp;$<?=$data['price']?>.00
                                    </button>
                                </form>

                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                                    <input type="hidden" name="packageId" value="<?=$data['Id']?>">
                                    <input type="hidden" name="serviceId" value="<?=$data['serviceId']?>">
                                    <button type="submit" name="contactAdmin"
                                        class="btn btn-outline-secondary w-100 my-1 mb-4">Contact Sellers</button>
                                </form>
                            </div>
                        </section>
                        <?php
                                    } else if ($data['packageType'] === '3') {
                                        ?>
                        <section class="page" data-page="3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold" style="color: #001b4d;">PREMIUM Package - Must for Pro</span>
                                <span class="h4">$<?=$data['price']?>.00</span>
                            </div>

                            <div class="text-secondary">

                                <p class="d-flex align-items-center">
                                    <span class="material-symbols-rounded">
                                        schedule
                                    </span>
                                    <small class="fw-bold">
                                        <?php 
                                            if($data['deliveryWithIn'] === '1') {
                                                echo $data['deliveryWithIn']. " Day";
                                            } else {
                                                echo $data['deliveryWithIn']. " Days";
                                            }
                                        ?>
                                    </small>
                                    &nbsp;&nbsp;
                                    <span class="material-symbols-rounded text-success">
                                        autorenew
                                    </span>
                                    <small class="fw-bold">
                                        <?=$data['revisionLimit']?>
                                    </small>
                                </p>

                                <p class="d-flex align-items-center">
                                    <span class="material-symbols-rounded text-success fw-bold">
                                        done
                                    </span>
                                    <small class="fw-bold">
                                        <?=$data['sectionIncluded']?>
                                    </small>
                                </p>


                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                                    <input type="hidden" name="packageId" value="<?=$data['Id']?>">
                                    <input type="hidden" name="serviceId" value="<?=$data['serviceId']?>">
                                    <button type="submit" name="checkout" class="btn btn-success w-100 my-1">Continue&nbsp;$<?=$data['price']?>.00</button>
                                </form>

                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                                    <input type="hidden" name="packageId" value="<?=$data['Id']?>">
                                    <input type="hidden" name="serviceId" value="<?=$data['serviceId']?>">
                                    <button type="submit" name="contactAdmin"
                                        class="btn btn-outline-secondary w-100 my-1 mb-4">Contact Sellers</button>
                                </form>

                            </div>
                        </section>
                        <?php
                                    }
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

    <!-- Script -->
    <script src="../js/tabSwitcher.js"></script>
    <?php include "../footer.php" ?>
</body>

</html>