<?php
session_start();
require '../db_conn.php';


if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}

$categoryId = $_GET['id'];
$categoryName = "";
$query = "SELECT * FROM `category` WHERE categoryId = '$categoryId'";

if ($result = mysqli_query($conn, $query)) {
    if (mysqli_num_rows($result) === 1) {
        foreach ($result as $data) {
            $categoryName = $data['categoryName'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['viewService'])) {
        $_SESSION['serviceId'] = $_POST['viewService'];
        
        header("Location: service.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?=$categoryName?> | CodeTech
    </title>
    <?php include "../links.php" ?>
    <link rel="Stylesheet" href="../style.css">
</head>

<body class="bg-light position-relative">
    <!-- header start -->
    <?php include "header.php" ?>
    <!-- header end -->

    <main class="container-fluid p-4">

        <?php
      $query = "SELECT * FROM `category` WHERE categoryId = '$categoryId'";

      if ($result = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($result) === 1) {
            foreach($result as $data) {
                ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="homePage.php">CodeTech</a> </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?=$data['categoryName']?></a>
                </li>
            </ol>
        </nav>
        <h2 class="h2">
            <?=$data['categoryName']?>
        </h2>
        <?php
            }
        }
      }
    ?>

        <div class="container-fluid text-center py-3 px-0">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 gy-4">

                <?php
            $query = "SELECT * FROM (`package` INNER JOIN service ON package.serviceId = service.serviceId) WHERE packageType = '1' AND categoryId = '$categoryId'";

            if ($result = mysqli_query($conn, $query)) {
                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $data) {
                        ?>
                <div class="col-xl-3 col-md-4 d-flex justify-content-center position-relative">
                    <div class="card w-100 shadow overflow-hidden">
                        <form class="m-0 p-0" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                            method="POST">
                            <button class="border-0 m-0 p-0 card-img-top" type="submit" name="viewService"
                                value="<?=$data['serviceId']?>">
                                <img src="../uploads/services_img/<?= $data['imgUrl'] ?>" class="w-100" alt="thumbnail"
                                    height="200px">
                            </button>
                        </form>

                        <div class="card-body text-start">
                            <a hre="#" class="h6 card-title px-2">
                                <span class="ellipses">
                                    <?=$data['serviceDescription']?>
                                </span>
                            </a>

                            <div class="card-footer mt-3 px-1 bg-transparent pb-3">
                                <div class="row position-absolute px-3 py-2 bottom-0 start-0 end-0 text-nowrap">

                                    <div class="col px-1 text-end">
                                        <small class="text-uppercase text-secondary text-nowrap">Starting
                                            At</small>&nbsp;<span class="fs-5">$
                                            <?=$data['price']?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
            } 
            ?>
            </div>
        </div>

        <?php include "../footer.php" ?>
</body>

</html>