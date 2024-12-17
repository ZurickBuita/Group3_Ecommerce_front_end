<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}

$prevDir = $_SESSION['prevDir'];
$alertMssg = null;
$message = '';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['checkout'])) {
        $customerId = $_SESSION['ID'];
        $packageId = $_SESSION['packageId'];
        $serviceId = $_SESSION['serviceId'];
        $paymentMethod = test_input($_POST['paymentMethod']);
        $pinCode = test_input($_POST['pinCode']);
        if (!empty($_POST['message'])) {
            $message = test_input($_POST['message']);
        }

        $query = "INSERT INTO `orders`(`packageId`, `customerId`, `paymentMethod`, `pinCode`, `message`, `serviceId`) 
        VALUES ('$packageId','$customerId','$paymentMethod','$pinCode', '$message', '$serviceId')";

        if (mysqli_query($conn, $query)) {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show" role="alert">
              Checkout successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        } else {
            $alertMssg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Checkout Failed
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
    }
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
<html>

<head>
    <title>CodeTech | Checkout</title>
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

        .brand-logo>span {
            font-style: italic;
            color: #8000ff;
        }
    </style>
</head>

<body class="bg-light position-relative">
    <?php include "header.php" ?>
    <div class="main px-4 my-5 mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="homePage.php">Codetech</a></li>
                <li class="breadcrumb-item"><a href="<?= $prevDir ?>">
                        <?=$_SESSION['serviceTitle']?>
                    </a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header  bg-white">
                <div class="card-title">Checkout</div>
            </div>
            <div class="card-body">
                <?=$alertMssg?>
                <?php
                $customerId = $_SESSION['ID'];
                $serviceId = $_SESSION['serviceId'];
                $packageId = $_SESSION['packageId'];

                $query = "SELECT * FROM `package` WHERE `Id` = '$packageId'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $price = $row['price'];

                $query = "SELECT * FROM `service` WHERE `serviceId` = '$serviceId'";
                if ($result = mysqli_query($conn, $query)) {
                    if ($row = mysqli_fetch_assoc($result)) {
                        ?>
                <div class="border">
                    <div class="bg-light p-3 border-bottom">
                        <h5 class="card-title mb-0">Your Order</h5>
                    </div>
                    <div class="p-3 mb-3 d-flex align-items-center">
                        <img src="../uploads/services_img/<?= $row['imgUrl'] ?>" width="100" height="80">
                        <div class="p-2">
                            <h5 class="h5 mb-0">
                                <?= $row['serviceTitle'] ?>
                            </h5>
                            <small class="text-secondary"><b>Price: </b>$<?= $price ?>.00</small>
                        </div>
                    </div>
                    <?php
                    }
                }

                $query = "SELECT * FROM `customer` WHERE `Id` = '$customerId'";
                if ($result = mysqli_query($conn, $query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                    <div class="bg-light p-3 border-bottom border-top">
                        <h5 class="card-title mb-0">Place your Orders</h5>
                    </div>
                    <div class="p-3 mb-3">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter your Name"
                                        value="<?= $row['username'] ?>" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Number</label>
                                    <input type="number" class="form-control" name="number"
                                        placeholder="Enter your Number" value="<?= $row['phoneNumber'] ?>" disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter your Email"
                                        value="<?= $row['email'] ?>" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select class="form-select" name="paymentMethod" required>
                                        <option disabled value="0">Select a Payment Method</option>
                                        <option selected value="1">Credit Card</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Pin Code</label>
                                    <input type="password" class="form-control" name="pinCode" placeholder="e.g. 123456"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Message(optional)</label>
                                    <input type="text" class="form-control" name="message"
                                        placeholder="Enter your suggestion">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <input type="submit" name="checkout"
                                    class="btn btn-primary px-5 py-3 w-100 text-uppercase fw-bold" value="Place Order">
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    </div>

    <?php include "../footer.php" ?>
</body>

</html>