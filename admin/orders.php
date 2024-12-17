<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}

$alertMssg = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['acceptOrder'])) {
        $orderId = $_POST['orderId'];
        $query = "UPDATE `orders` SET `status`='1'WHERE `orderId` = '$orderId'";

        if (mysqli_query($conn, $query)) {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Order accepted Successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        } else {
            $alertMssg = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Order Failed!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
    }

    if (isset($_POST['deleteOrder'])) {
        $orderId = $_POST['orderId'];

        $query = "UPDATE `orders` SET `status`='3' WHERE `orderId` = '$orderId'";

        if (mysqli_query($conn, $query)) {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Order Cancelled Successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        } else {
            $alertMssg = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Order cannot be deleted!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
    }

    if (isset($_POST['contactAdmin'])) {
        $orderId = $_POST['orderId'];
        $_SESSION['orderId'] = $orderId;
        $_SESSION['serviceId'] = $_POST['serviceId'];
        $_SESSION['packageId'] = $_POST['packageId'];
        $_SESSION['customerId'] = $_POST['customerId'];
        $_SESSION['previousDir'] = "orders.php";
        $_SESSION['deliveredWork'] = false;

        header("Location: serviceMssg.php");
    }

    if (isset($_POST['deliveredWork'])) {
        $orderId = $_POST['orderId'];
        $_SESSION['orderId'] = $orderId;
        $_SESSION['serviceId'] = $_POST['serviceId'];
        $_SESSION['packageId'] = $_POST['packageId'];
        $_SESSION['customerId'] = $_POST['customerId'];
        $_SESSION['previousDir'] = "orders.php";
        $_SESSION['deliveredWork'] = false;

        header("Location: deliverdWork.php");
    }

    if (isset($_POST['deliverWork'])) {
        $orderId = $_POST['orderId'];
        $_SESSION['orderId'] = $orderId;
        $_SESSION['serviceId'] = $_POST['serviceId'];
        $_SESSION['packageId'] = $_POST['packageId'];
        $_SESSION['customerId'] = $_POST['customerId'];
        $_SESSION['previousDir'] = "orders.php";
        $_SESSION['deliveredWork'] = false;

        header("Location: serviceMssg.php");
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CodeTech Admin|Orders</title>

    <?php include 'links.php' ?>

    <style>
        ::-webkit-scrollbar {
            display: none;
        }
    </style>

</head>

<body class="position-relative">
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main pb-5 mb-5">
        <div class="pagetitle">
            <h1>Orders</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="card shadow-none border">
            <div class="card-header">
                <span class="card-title">Orders</span>
            </div>
            <div class="card-body py-3">
                <?= $alertMssg ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-secondary text-white text-nowrap">
                            <tr>
                                <th scope="col">Service</th>
                                <th scope="col">Status</th>
                                <th scope="col">Price</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Delivery Date</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM ((`orders` INNER JOIN package ON orders.packageId = package.Id) INNER JOIN service ON package.serviceId = service.serviceId)";
                            if ($result = mysqli_query($conn, $query)) {
                                if (mysqli_num_rows($result) == 0) {
                                    ?>
                            <tr>
                                <td colspan="6">
                                    <h3>No Records found!</h3>
                                </td>
                            </tr>
                            <?php
                                } else {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="../uploads/services_img/<?= $row['imgUrl'] ?>" width="120"
                                            height="80">
                                        <div class="ms-3">
                                            <span>
                                                <?= $row['serviceTitle'] ?>
                                            </span><br>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                                if ($row['status'] == 0) {
                                                    echo '<span class="badge bg-warning">Pending</span>';
                                                } else if ($row['status'] == 1) {
                                                    echo '<span class="badge bg-primary">Active</span>';
                                                } else if ($row['status'] == 2) {
                                                    echo '<span class="badge bg-success">Delivered</span>';
                                                } else if ($row['status'] == 3) {
                                                    echo '<span class="badge bg-danger">Cancelled</span>';
                                                } else if ($row['status'] == 4) {
                                                    echo '<span class="badge bg-success">Completed</span>';
                                                }
                                                ?>
                                </td>
                                <td>$<?= $row['price'] ?>.00</td>
                                <td>
                                    <?= $row['createdAt'] ?>
                                </td>
                                <td>
                                    <?php 
                                                    if ($row['deliveryWithIn'] == 1) {
                                                        echo $row['deliveryWithIn']. " day";
                                                    } else {
                                                        echo $row['deliveryWithIn']. " days";
                                                    }
                                            ?>
                                </td>
                                <td>
                                    <?php
                                                if ($row['status'] == 0) {
                                                    ?>
                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <button class="btn btn-primary border-0" type="submit" name="acceptOrder"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Accept">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>

                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                        <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                        <input type="hidden" name="customerId" value="<?= $row['customerId'] ?>">
                                        <button class="btn btn-primary border-0" type="submit" name="contactAdmin"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Contact Customer">
                                            <i class="bi bi-chat-left-text"></i>
                                        </button>
                                    </form>

                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <button class="btn btn-danger border-0" type="submit" name="deleteOrder"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Reject">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                    <?php
                                                } else if ($row['status'] == 1) {
                                                    ?>
                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                        <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                        <input type="hidden" name="customerId" value="<?= $row['customerId'] ?>">
                                        <button class="btn btn-primary border-0" type="submit" name="deliverWork"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Deliver Word">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </form>
                                    <?php
                                                } else if ($row['status'] == 2) {
                                                    ?>
                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                        <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                        <input type="hidden" name="customerId" value="<?= $row['customerId'] ?>">
                                        <button class="btn btn-primary border-0" type="submit" name="contactAdmin"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Contact Customer">
                                            <i class="bi bi-chat-left-text"></i>
                                        </button>
                                    </form>

                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                        <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                        <input type="hidden" name="customerId" value="<?= $row['customerId'] ?>">
                                        <button class="btn btn-success border-0" type="submit" name="deliveredWork"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Delivered Word">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </form>
                                    <?php
                                                } else if ($row['status'] == 3) {
                                                    ?>
                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                        <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                        <input type="hidden" name="customerId" value="<?= $row['customerId'] ?>">
                                        <button class="btn btn-primary border-0" type="submit" name="contactAdmin"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Contact Customer">
                                            <i class="bi bi-chat-left-text"></i>
                                        </button>
                                    </form>
                                    <?php
                                                } else if ($row['status'] == 4) {
                                                    ?>
                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                        <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                        <input type="hidden" name="customerId" value="<?= $row['customerId'] ?>">
                                        <button class="btn btn-primary border-0" type="submit" name="contactAdmin"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Contact Customer">
                                            <i class="bi bi-chat-left-text"></i>
                                        </button>
                                    </form>

                                    <form class="d-inline-block"
                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                        <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                        <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                        <input type="hidden" name="customerId" value="<?= $row['customerId'] ?>">
                                        <button class="btn btn-success border-0" type="submit" name="deliveredWork"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Delivered Word">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </form>
                                    <?php
                                                }
                                                ?>
                                </td>
                            </tr>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
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