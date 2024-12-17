<?php
session_start();
require '../db_conn.php';


if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}

$alertMssg = null;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
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
        $_SESSION['previousDir'] = "manageOrders.php";
        $_SESSION['deliveredWork'] = false;

        header("Location: orderMssg.php");
    }

    if (isset($_POST['completeOrder'])) {
        $orderId = $_POST['orderId'];

        $query = "UPDATE `orders` SET `status`='4' WHERE `orderId` = '$orderId'";

        if (mysqli_query($conn, $query)) {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Order Completed Successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        } else {
            $alertMssg = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            The order cannot be deleted!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
    }

    if (isset($_POST['deliveredWork'])) {
        $orderId = $_POST['orderId'];
        $_SESSION['orderId'] = $orderId;
        $_SESSION['serviceId'] = $_POST['serviceId'];
        $_SESSION['packageId'] = $_POST['packageId'];
        $_SESSION['customerId'] = $_POST['customerId'];
        $_SESSION['previousDir'] = "manageOrders.php";
        header("Location: deliveredWork.php");
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeTech | Manage Orders</title>
    <?php include "../links.php" ?>
    <link rel="Stylesheet" href="../style.css">
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
            width: 100%;
        }

        .page.active {
            display: block;
        }
    </style>
</head>

<body class="bg-light position-relative">
    <?php include 'header.php' ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-4 mx-3">
            <li class="breadcrumb-item"><a href="homePage.php">Codetech</a></li>
            <li class="breadcrumb-item active">Manage Orders</li>
        </ol>
    </nav>

    <main class="container-fluid p-4 pt-0">
        <?=$alertMssg?>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <div class="card-title">Manage Orders</div>
                    <a href="postARequest.php" class="btn btn-primary text-white">Post a Request</a>
                </div>
                <div class="card-body">
                    <div class="card-header p-0 overflow-auto">
                        <div class="navbar-items w-100 d-flex bg-white text-nowrap">
                            <span class="navbar-pills active" data-switcher data-tab="1">ALL</span>
                            <span class="navbar-pills" data-switcher data-tab="2">PENDING</span>
                            <span class="navbar-pills" data-switcher data-tab="3">ACTIVE</span>
                            <span class="navbar-pills" data-switcher data-tab="4">DELIVERED</span>
                            <span class="navbar-pills" data-switcher data-tab="5">CANCELLED</span>
                            <span class="navbar-pills" data-switcher data-tab="6">COMPLETED</span>
                        </div>
                    </div>

                    <div class="pages">

                        <section class="card page active w-100 my-3 bg-white overflow-auto" data-page="1"
                            style="-webkit-scrollbar:">
                            <table class="table table-hover text-nowrap ">
                                <thead class="card-header w-100">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Service Title</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Delivery Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customerId = $_SESSION["ID"];
                                    $query = "SELECT * FROM ((`orders` INNER JOIN package ON orders.packageId = package.Id) INNER JOIN service ON package.serviceId = service.serviceId) WHERE `customerId` = '$customerId'";
                                    if ($result = mysqli_query($conn, $query)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                    <tr>
                                        <th scope="row">
                                            <?= $row['orderId'] ?>
                                        </th>
                                        <td>
                                            <img src="../uploads/services_img/<?= $row['imgUrl'] ?>" width="120"
                                                height="80">
                                            <span>
                                                <?= $row['serviceTitle'] ?>
                                            </span>
                                        </td>
                                        <td>$
                                            <?= $row['price'] ?>.00
                                        </td>
                                        <td>
                                            <?= $row['createdAt'] ?>
                                        </td>
                                        <td>
                                            <?php if ($row['deliveryWithIn'] == 1) {
                                                            echo $row['deliveryWithIn']. " day";
                                                        }else {
                                                            echo $row['deliveryWithIn']. " days";
                                                        } 
                                                        ?>
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
                                                        }  else if ($row['status'] == 4) {
                                                            echo '<span class="badge bg-success">Completed</span>';
                                                        }
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                          if ($row['status'] == 0) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                                <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                                <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                                <input type="hidden" name="customerId"
                                                    value="<?= $row['customerId'] ?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Contact Admin">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>

                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?=$row['orderId']?>">
                                                <button class="btn btn-danger border-0" type="submit" name="deleteOrder"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    data-bs-title="Cancel service">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                            <?php
                                                          } else if ($row['status'] == 1) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                                <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                                <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                                <input type="hidden" name="customerId"
                                                    value="<?= $row['customerId'] ?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Contact Admin">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>
                                            <?php
                                                          } else if ($row['status'] == 2) {
                                                            ?>

                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="completeOrder" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Complete">
                                                    <span class="bi bi-check"></span>
                                                </button>
                                            </form>

                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                                <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                                <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                                <input type="hidden" name="customerId"
                                                    value="<?= $row['customerId'] ?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Contact Admin">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>

                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?=$row['orderId']?>">
                                                <button class="btn btn-success border-0" type="submit"
                                                    name="deliveredWork" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Delivered Word">
                                                    <i class="bi bi-send"></i>
                                                </button>
                                            </form>
                                            <?php
                                                          } else if ($row['status'] == 3) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                                <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                                <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                                <input type="hidden" name="customerId"
                                                    value="<?= $row['customerId'] ?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Contact Admin">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>
                                            <?php
                                                          }  else if ($row['status'] == 4) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                                <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                                <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                                <input type="hidden" name="customerId"
                                                    value="<?= $row['customerId'] ?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Contact Admin">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>

                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                                <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                                <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                                <input type="hidden" name="customerId"
                                                    value="<?= $row['customerId'] ?>">
                                                <button class="btn btn-success border-0" type="submit"
                                                    name="deliveredWork" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Delivered Word">
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
                                        } else {
                                            ?>
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            <h5>No Pending orders to show</h5>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>

                        <section class="card page w-100 my-3 bg-white overflow-auto" data-page="2">
                            <table class="table table-hover text-nowrap ">
                                <thead class="card-header">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Service Title</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Delivery Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customerId = $_SESSION["ID"];
                                    $query = "SELECT * FROM ((`orders` INNER JOIN package ON orders.packageId = package.Id) INNER JOIN service ON package.serviceId = service.serviceId) WHERE `customerId` = '$customerId' AND status = '0'";
                                    if ($result = mysqli_query($conn, $query)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                    <tr>
                                        <th scope="row">
                                            <?= $row['orderId'] ?>
                                        </th>
                                        <td>
                                            <img src="../uploads/services_img/<?= $row['imgUrl'] ?>" width="120"
                                                height="80">
                                            <span>
                                                <?= $row['serviceTitle'] ?>
                                            </span>
                                        </td>
                                        <td>$
                                            <?= $row['price'] ?>.00
                                        </td>
                                        <td>
                                            <?= $row['createdAt'] ?>
                                        </td>
                                        <td>
                                            <?php if ($row['deliveryWithIn'] == 1) {
                                                            echo $row['deliveryWithIn']. " day";
                                                        }else {
                                                            echo $row['deliveryWithIn']. " days";
                                                        } 
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                          if ($row['status'] == 0) {
                                                            echo '<span class="badge bg-warning">Pending</span>';
                                                        } else if ($row['status'] == 1) {
                                                            echo '<span class="badge bg-primary">Active</span>';
                                                        } else if ($row['status'] == 2) {
                                                            echo '<span class="badge bg-success">Completed</span>'; 
                                                        } else if ($row['status'] == 3) {
                                                            echo '<span class="badge bg-danger">Cancelled</span>';
                                                        } else if ($row['status'] == 4) {
                                                            echo '<span class="badge bg-success">Completed</span>';
                                                        }
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                          if ($row['status'] == 0) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="contactAdmin" value="<?=$row['orderId']?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Submit requirements">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>

                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="contactAdmin" value="<?=$row['orderId']?>">
                                                <button class="btn btn-danger border-0" type="submit" name="deleteOrder"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    data-bs-title="Cancel service">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                            <?php
                                                          } 
                                                        ?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        } else {
                                            ?>
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            <h5>No Pending orders to show</h5>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>

                        <section class="card page w-100 my-3 bg-white overflow-auto" data-page="3">
                            <table class="table table-hover text-nowrap ">
                                <thead class="card-header w-100">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Service Title</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Delivery Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customerId = $_SESSION["ID"];
                                    $query = "SELECT * FROM ((`orders` INNER JOIN package ON orders.packageId = package.Id) INNER JOIN service ON package.serviceId = service.serviceId) WHERE `customerId` = '$customerId' AND status = '1'";
                                    if ($result = mysqli_query($conn, $query)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                    <tr>
                                        <th scope="row">
                                            <?= $row['orderId'] ?>
                                        </th>
                                        <td>
                                            <img src="../uploads/services_img/<?= $row['imgUrl'] ?>" width="120"
                                                height="80">
                                            <span>
                                                <?= $row['serviceTitle'] ?>
                                            </span>
                                        </td>
                                        <td>$
                                            <?= $row['price'] ?>.00
                                        </td>
                                        <td>
                                            <?= $row['createdAt'] ?>
                                        </td>
                                        <td>
                                            <?php if ($row['deliveryWithIn'] == 1) {
                                                            echo $row['deliveryWithIn']. " day";
                                                        }else {
                                                            echo $row['deliveryWithIn']. " days";
                                                        } 
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                          if ($row['status'] == 0) {
                                                            echo '<span class="badge bg-warning">Pending</span>';
                                                        } else if ($row['status'] == 1) {
                                                            echo '<span class="badge bg-primary">Active</span>';
                                                        } else if ($row['status'] == 2) {
                                                            echo '<span class="badge bg-success">Completed</span>'; 
                                                        } else if ($row['status'] == 3) {
                                                            echo '<span class="badge bg-danger">Cancelled</span>';
                                                        } else if ($row['status'] == 4) {
                                                            echo '<span class="badge bg-success">Completed</span>';
                                                        }
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                         if ($row['status'] == 1) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="contactAdmin" value="<?=$row['orderId']?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Submit requirements">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>
                                            <?php
                                                          } 
                                                        ?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        } else {
                                            ?>
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            <h5>No Active orders to show</h5>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>

                        <section class="card page w-100 my-3 bg-white" data-page="4">
                            <table class="table table-hover text-nowrap ">
                                <thead class="card-header w-100">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Service Title</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Delivery Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customerId = $_SESSION["ID"];
                                    $query = "SELECT * FROM ((`orders` INNER JOIN package ON orders.packageId = package.Id) INNER JOIN service ON package.serviceId = service.serviceId) WHERE `customerId` = '$customerId' AND status = '2'";
                                    if ($result = mysqli_query($conn, $query)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                    <tr>
                                        <th scope="row">
                                            <?= $row['orderId'] ?>
                                        </th>
                                        <td>
                                            <img src="../uploads/services_img/<?= $row['imgUrl'] ?>" width="120"
                                                height="80">
                                            <span>
                                                <?= $row['serviceTitle'] ?>
                                            </span>
                                        </td>
                                        <td>$
                                            <?= $row['price'] ?>.00
                                        </td>
                                        <td>
                                            <?= $row['createdAt'] ?>
                                        </td>
                                        <td>
                                            <?php if ($row['deliveryWithIn'] == 1) {
                                                            echo $row['deliveryWithIn']. " day";
                                                        }else {
                                                            echo $row['deliveryWithIn']. " days";
                                                        } 
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                          if ($row['status'] == 0) {
                                                            echo '<span class="badge bg-warning">Pending</span>';
                                                        } else if ($row['status'] == 1) {
                                                            echo '<span class="badge bg-primary">Active</span>';
                                                        } else if ($row['status'] == 2) {
                                                            echo '<span class="badge bg-success">Completed</span>'; 
                                                        } else if ($row['status'] == 3) {
                                                            echo '<span class="badge bg-danger">Cancelled</span>';
                                                        } else if ($row['status'] == 4) {
                                                            echo '<span class="badge bg-success">Completed</span>';
                                                        }
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                         if ($row['status'] == 2) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="contactAdmin" value="<?=$row['orderId']?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Contact Admin">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>

                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="contactAdmin" value="<?=$row['orderId']?>">
                                                <button class="btn btn-success border-0" type="submit"
                                                    name="deliveredWork" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Delivered Word">
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
                                        } else {
                                            ?>
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            <h5>No Delivered orders to show</h5>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>

                        <section class="card page w-100 my-3 bg-white overflow-auto" data-page="5">
                            <table class="table table-hover text-nowrap ">
                                <thead class="card-header w-100">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Service Title</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Delivery Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customerId = $_SESSION["ID"];
                                    $query = "SELECT * FROM ((`orders` INNER JOIN package ON orders.packageId = package.Id) INNER JOIN service ON package.serviceId = service.serviceId) WHERE `customerId` = '$customerId' AND status = '3'";
                                    if ($result = mysqli_query($conn, $query)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                    <tr>
                                        <th scope="row">
                                            <?= $row['orderId'] ?>
                                        </th>
                                        <td>
                                            <img src="../uploads/services_img/<?= $row['imgUrl'] ?>" width="120"
                                                height="80">
                                            <span>
                                                <?= $row['serviceTitle'] ?>
                                            </span>
                                        </td>
                                        <td>$
                                            <?= $row['price'] ?>.00
                                        </td>
                                        <td>
                                            <?= $row['createdAt'] ?>
                                        </td>
                                        <td>
                                            <?php if ($row['deliveryWithIn'] == 1) {
                                                            echo $row['deliveryWithIn']. " day";
                                                        }else {
                                                            echo $row['deliveryWithIn']. " days";
                                                        } 
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                          if ($row['status'] == 0) {
                                                            echo '<span class="badge bg-warning">Pending</span>';
                                                        } else if ($row['status'] == 1) {
                                                            echo '<span class="badge bg-primary">Active</span>';
                                                        } else if ($row['status'] == 2) {
                                                            echo '<span class="badge bg-success">Completed</span>'; 
                                                        } else if ($row['status'] == 3) {
                                                            echo '<span class="badge bg-danger">Cancelled</span>';
                                                        } else if ($row['status'] == 4) {
                                                            echo '<span class="badge bg-success">Completed</span>';
                                                        }
                                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                         if ($row['status'] == 3) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="contactAdmin" value="<?=$row['orderId']?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Contact Admin">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>
                                            <?php
                                                          }
                                                        ?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        } else {
                                            ?>
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            <h5>No Cancelled orders to show</h5>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>

                        <section class="card page w-100 my-3 bg-white overflow-auto" data-page="6">
                            <table class="table table-hover text-nowrap ">
                                <thead class="card-header w-100">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Service Title</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Delivery Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customerId = $_SESSION["ID"];
                                    $query = "SELECT * FROM ((`orders` INNER JOIN package ON orders.packageId = package.Id) INNER JOIN service ON package.serviceId = service.serviceId) WHERE `customerId` = '$customerId' AND status = '4'";
                                    if ($result = mysqli_query($conn, $query)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                    <tr>
                                        <th scope="row">
                                            <?= $row['orderId'] ?>
                                        </th>
                                        <td>
                                            <img src="../uploads/services_img/<?= $row['imgUrl'] ?>" width="120"
                                                height="80">
                                            <span>
                                                <?= $row['serviceTitle'] ?>
                                            </span>
                                        </td>
                                        <td>$<?= $row['price'] ?>.00</td>
                                        <td>
                                            <?= $row['createdAt'] ?>
                                        </td>
                                        <td>
                                            <?php if ($row['deliveryWithIn'] == 1) {
                                                echo $row['deliveryWithIn']. " day";
                                            }else {
                                                echo $row['deliveryWithIn']. " days";
                                            } 
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($row['status'] == 0) {
                                                echo '<span class="badge bg-warning">Pending</span>';
                                            } else if ($row['status'] == 1) {
                                                echo '<span class="badge bg-primary">Active</span>';
                                            } else if ($row['status'] == 2) {
                                                echo '<span class="badge bg-success">Completed</span>'; 
                                            } else if ($row['status'] == 3) {
                                                echo '<span class="badge bg-danger">Cancelled</span>';
                                            } else if ($row['status'] == 4) {
                                                echo '<span class="badge bg-success">Completed</span>';
                                            } 
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                         if ($row['status'] == 4) {
                                                            ?>
                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?= $row['orderId'] ?>">
                                                <input type="hidden" name="serviceId" value="<?= $row['serviceId'] ?>">
                                                <input type="hidden" name="packageId" value="<?= $row['packageId'] ?>">
                                                <input type="hidden" name="customerId"
                                                    value="<?= $row['customerId'] ?>">
                                                <button class="btn btn-primary border-0" type="submit"
                                                    name="contactAdmin" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Contact Admin">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            </form>

                                            <form class="d-inline-block"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                                method="POST">
                                                <input type="hidden" name="orderId" value="<?=$row['orderId']?>">
                                                <input type="hidden" name="orderId" value="<?=$row['orderId']?>">
                                                <button class="btn btn-success border-0" type="submit"
                                                    name="deliveredWork" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="Delivered Word">
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
                                        } else {
                                            ?>
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            <h5>No Completed orders to show</h5>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>

                    </div>
                </div>
            </div>
        </div>


    </main>


    <?php include "../footer.php" ?>
</body>

<script src="../js/tabSwitcher.js"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>

</html>