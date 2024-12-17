<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
  header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
  header("Location: ../customer/category.php");
}

$alertMssg = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['deleteBtn'])) {
    $image = "";
    $serviceId = $_POST['deleteBtn'];

    $query = "SELECT * FROM `service` WHERE `serviceId` = '$serviceId'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $image = $row['imgUrl'];
    unlink("../uploads/services_img/$image");

    $query = "DELETE FROM `service` WHERE serviceId = '$serviceId'";
    if (mysqli_query($conn, $query)) {

    $alertMssg = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    Record Deleted Successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    } else {
      $alertMssg = '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
      A record cannot be deleted.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
  }

  if (isset($_POST['editService'])) {
    $serviceId = $_POST['serviceId'];
    $_SESSION['serviceId'] = $serviceId;
    $_SESSION['prevDir'] = "allServices.php";

    header("Location: editServices.php");
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CodeTech Admin|All Services</title>

    <?php include 'links.php' ?>

    <style>
        .modal-backdrop {
            z-index: 1;
        }

        .ellipses {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden !important;
            text-overflow: ellipsis;
            -webkit-line-clamp: 2;
            /* Number of lines to show */
            line-clamp: 2;
        }
    </style>

</head>

<body class="position-relative ">

    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>All Services</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">All Services</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <?= $alertMssg?>
            <div class="row gutters-10">
                <?php
        $query = "SELECT * FROM `service`";

        if ($result = mysqli_query($conn, $query)) {
          if (mysqli_num_rows($result) > 0) {
            foreach ($result as $data) {
              ?>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card border overflow-hidden shadow" style="height: 410px;">
                        <img src="../uploads/services_img/<?= $data['imgUrl'] ?>" class="card-img-top" height="200">
                        <div class="card-body px-3">
                            <div class="card-title p-0">
                                <h5 class="m-0 p-0 ellipses">
                                    <?= $data['serviceTitle'] ?>
                                </h5>
                            </div>
                            <p class="card-text pt-0 ellipses">
                                <?= $data['serviceDescription'] ?>
                            </p>

                        </div>

                        <div class="card-footer my-0 py-2">
                            <div class="d-flex align-items-center justify-content-between">

                                <form class="m-0" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
                                    method="POST">
                                    <input type="hidden" name="serviceId" value="<?=$data['serviceId']?>">
                                    <button type="submit" name="editService" class="btn btn-primary">Edit</button>
                                </form>

                                <div class="bg-danger opacity-75 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <button class="deleteBtn btn border-0" type="button" data-bs-toggle="modal"
                                        data-bs-target="#delete1" value="<?= $data['serviceId'] ?>">
                                        <span class="material-symbols-rounded text-white">delete</span>
                                    </button>
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

                <!-- Modal -->
                <div class="modal fade" id="delete1" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-0">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Service</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form class="m-0 p-0" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                method="POST">
                                <div class="modal-body">
                                    <span>Are you sure to delete this?</span>
                                </div>
                                <div class="modal-footer ">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                    <button type="submit" class="btn btn-primary" id="deleteBtn"
                                        name="deleteBtn">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </section>

    </main><!-- End #main -->

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.deleteBtn').click(function () {
                $value = $(this).val();

                $('#deleteBtn').val($value);
                console.log($value);
            });
        });
    </script>
</body>

</html>