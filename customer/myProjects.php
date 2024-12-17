<?php
session_start();
require '../db_conn.php';


if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}
$alertMssg = "";
$proofImg = null;
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['confirmBtn'])) {
        $proposalId = $_POST['proposalId'];

        $query = "UPDATE `project_proposal` SET `status`='4' WHERE `proposalId` = '$proposalId'";
        if ($result = mysqli_query($conn, $query)) {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            The project is received.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
    }

    if (isset($_POST['deleteBtn'])) {
        $proposalId = $_POST['proposalId'];

        $query = "UPDATE `project_proposal` SET `status`='5' WHERE `proposalId` = '$proposalId'";
        if ($result = mysqli_query($conn, $query)) {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            project successfully deleted.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
    }
    
    if (isset($_POST['contactBtn'])) {
        $_SESSION['proposalId'] = $_POST['proposalId'];
        header("Location: createNewMessage.php");
    }
    
    if (isset($_POST['paymentBtn'])) {
        $proposalId = $_POST['proposalId'];
        $gcashName = $_POST['gcashName'];
        $gcashNum = $_POST['gcashNum'];

        if (isset($_FILES['proofImg'])) {
            $img_name = $_FILES['proofImg']['name'];
            $img_size = $_FILES['proofImg']['size'];
            $tmp_name = $_FILES['proofImg']['tmp_name'];
            $img_err = $_FILES['proofImg']['error'];

            if ($img_err === 0) {
                if ($img_size > 2225000) {
                    $values = false;
                    echo "<script>alert('Sorry, your file is too large.')</script>";
                } else {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);

                    $allowed_exs = array("jpg", "jpeg", "png");

                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $proofImg = uniqid("IMG-", true) . '.' . $img_ex_lc;
                        $img_upload_path = '../uploads/proofIMg/' . $proofImg;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        echo "<script>alert('You can't upload files of this type')</script>";
                    }
                }
            }
        }

        $query = "INSERT INTO `proofofpayment`(`gcashName`, `gcashNumber`, `proofImg`, `projectId`) 
        VALUES ('$gcashName','$gcashNum','$proofImg','$proposalId')";
        if (mysqli_query($conn, $query)) {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            Payment sent successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        } else {
            $alertMssg = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            Payment Cannot Successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeTech | My Projects</title>
    <?php include "../links.php" ?>
    <link rel="Stylesheet" href="../style.css">
    <style>
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-light position-relative">
    <?php include 'header.php' ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-4 mx-3">
            <li class="breadcrumb-item"><a href="homePage.php">Codetech</a></li>
            <li class="breadcrumb-item active">My Projects</li>
        </ol>
    </nav>
    <main class="container-fluid p-4">

        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="card-title d-flex align-items-center justify-content-between pt-2">
                        <h3>My Projects</h3>
                        <a href="postARequest.php" class="btn btn-primary text-white">Create a Request</a>
                    </div>
                </div>
                <div class="card-body">
                    <?= $alertMssg ?>
                    <div class="table-responsive-md">
                        <table class="table table-hover">
                            <thead class="bg-light border text-nowrap">
                                <tr>
                                    <th scope="col">Project</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Budget</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $customerId = $_SESSION['ID'];
                                $query = "SELECT * FROM `project_proposal` WHERE `customerId` = '$customerId'";
                                if ($result = mysqli_query($conn, $query)) {
                                    if (mysqli_num_rows($result) == 0) {
                                        echo "<tr>";
                                        echo "<td colspan='6' class='text-center'><h3>No Result Found</h3></td>";
                                        echo "</tr>";
                                    }
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                <tr>
                                    <td>
                                        <?= $row['projectTitle'] ?>
                                    </td>
                                    <td>
                                        <?php
                                                if ($row['status'] === '0') {
                                                    echo "<span class='badge text-bg-warning'>Pendings</span>";
                                                } else if ($row['status'] === '1') {
                                                    echo "<span class='badge text-bg-warning'>Pending Payments</span>";
                                                } else if ($row['status'] === '2') {
                                                    echo "<span class='badge text-bg-warning'>Running</span>";
                                                } else if ($row['status'] === '3') {
                                                    echo "<span class='badge text-bg-success'>Submitted</span>";
                                                } else if ($row['status'] === '4') {
                                                    echo "<span class='badge text-bg-success'>Completed</span>";
                                                } else if ($row['status'] === '5') {
                                                    echo "<span class='badge text-bg-danger'>Cancelled</span>";
                                                }
                                                ?>
                                    </td>
                                    <td>$
                                        <?= $row['budgetOffer'] ?>.00
                                    </td>
                                    <td>
                                        <?= $row['postedAt'] ?>
                                    </td>
                                    <td>
                                        <?php
                                                if ($row['status'] === '0') {
                                                    ?>
                                        <div class="d-flex align-items-start">
                                            <a href="#deleteModal" type="button"
                                                class="deleteBtn text-white btn btn-danger" data-bs-toggle="modal"
                                                data-id="<?= $row['proposalId'] ?>">Cancel</a>
                                            <span>&nbsp;</span>
                                           
                                        </div>
                                        <?php
                                                } else if ($row['status'] === '1') {
                                                    ?>
                                        <div class="d-flex align-items-start">
                                           
                                            <span>&nbsp;</span>
                                            <form class="m-0"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                method="POST">
                                                <input type="hidden" name="proposalId"
                                                    value="<?= $row['proposalId'] ?>">
                                                <button type="submit" name="contactBtn"
                                                    class="btn btn-primary text-white">Contact</button>
                                            </form>
                                            <span>&nbsp;</span>
                                            <a href="#payModal" type="button"
                                                class="payBtn text-white btn btn-secondary" data-bs-toggle="modal"
                                                data-id="<?= $row['proposalId'] ?>">Send Payment</a>
                                        </div>
                                        <?php
                                                } else if ($row['status'] === '2') {
                                                    ?>
                                        <div class="d-flex align-items-center">
                                           
                                            <span>&nbsp;</span>
                                            <form class="m-0"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                method="POST">
                                                <input type="hidden" name="proposalId"
                                                    value="<?= $row['proposalId'] ?>">
                                                <button type="submit" name="contactBtn"
                                                    class="btn btn-primary text-white">Contact</button>
                                            </form>
                                        </div>
                                        <?php
                                                } else if ($row['status'] === '3') {
                                                    ?>
                                        <div class="d-flex align-items-start">
                                            <a href="#" class="btn btn-primary text-white" data-bs-toggle="modal"
                                                data-bs-target="#viewModal">View</a>
                                            <span>&nbsp;</span>
                                            <form class="m-0"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                method="POST">
                                                <input type="hidden" name="proposalId"
                                                    value="<?= $row['proposalId'] ?>">
                                                <button type="submit" name="contactBtn"
                                                    class="btn btn-primary text-white">Contact</button>
                                            </form>
                                            <span>&nbsp;</span>
                                            <a href="#recievedModal" type="button"
                                                class="recievedBtn text-white btn btn-success" data-bs-toggle="modal"
                                                data-id="<?= $row['proposalId'] ?>">Recieved</a>
                                        </div>
                                        <?php
                                                        $projectId = $row['proposalId'];
                                                        $query = "SELECT * FROM `proofofpayment` WHERE `projectId` = '$projectId'";
                                                        if ($result = mysqli_query($conn, $query)) {
                                                            while ($data = mysqli_fetch_assoc($result)) {
                                                                ?>
                                        <div class="modal fade" id="viewModal" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">View Proof
                                                            of Payment</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Gcash Name</label>
                                                            <input type="text" name="gcashName" class="form-control"
                                                                placeholder="Gcash Name" value="<?=$data['gcashName']?>"
                                                                readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Gcash Number</label>
                                                            <input type="text" name="gcashNumber" class="form-control"
                                                                placeholder="Gcash Number"
                                                                value="<?=$data['gcashNumber']?>" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <div class="alert alert-secondary py-2 alert-dismissible fade show"
                                                                role="alert">
                                                                <a href="../uploads/proofImg/<?= $data['proofImg'] ?>"
                                                                    target="black">
                                                                    <img src='../uploads/proofImg/<?= $data['proofImg']
                                                                        ?>'
                                                                    width='50' height='50' >
                                                                </a>
                                                                <span>
                                                                    <?= $data['proofImg'] ?>
                                                                </span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                                            }
                                                        }
                                                        ?>

                                        <?php
                                                } else if ($row['status'] === '4') {
                                                    ?>
                                        <div class="d-flex align-items-center">
                                           
                                            <span>&nbsp;</span>
                                            <form class="m-0"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                method="POST">
                                                <input type="hidden" name="proposalId"
                                                    value="<?= $row['proposalId'] ?>">
                                                <button type="submit" name="contactBtn"
                                                    class="btn btn-primary text-white">Contact</button>
                                            </form>
                                        </div>
                                        <?php
                                                } else if ($row['status'] === '5') {
                                                    ?>
                                        <div class="d-flex align-items-center">
                                           
                                            <span>&nbsp;</span>
                                            <form class="m-0"
                                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                method="POST">
                                                <input type="hidden" name="proposalId"
                                                    value="<?= $row['proposalId'] ?>">
                                                <button type="submit" name="contactBtn"
                                                    class="btn btn-primary text-white">Contact</button>
                                            </form>
                                        </div>
                                        <?php
                                                }
                                                ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                                <!-- Recieved Modal -->
                                <div class="modal fade" id="recievedModal" tabindex="-1" aria-labelledby="recievedModal"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Project Receive Confirmation</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="proposalId" class="proposalId" />
                                                    Are you sure you want to confirm that you have received this
                                                    project?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">No</button>
                                                    <button type="submit" name="confirmBtn"
                                                        class="btn btn-primary">Confirm</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModal"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Delete Project</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="proposalId" class="proposalId" />
                                                    Are you sure you want to delete this project?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">No</button>
                                                    <button type="submit" name="deleteBtn"
                                                        class="btn btn-primary">Yes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pay Modal -->
                                <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModal"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Send Payment</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div
                                                        class="d-flex flex-column justify-content-center align-items-center mb-4">
                                                        <img class="d-block"
                                                            src="https://play-lh.googleusercontent.com/QNP0Aj2hyumAmYiWVAsJtY2LLTQnzHxdW7-DpwFUFNkPJjgRxi-BXg7A4yI6tgYKMeU"
                                                            width="80" height="80">
                                                        <small>Upload your proof of payment using Gcash</small>
                                                    </div>
                                                    <input type="hidden" name="proposalId" class="proposalId" />
                                                    <div class="mb-3">
                                                        <label class="form-label">Gcash Name</label>
                                                        <input type="text" class="form-control" name="gcashName"
                                                            placeholder="Gcash Name" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Gcash Number</label>
                                                        <input type="number" name="gcashNum" class="form-control"
                                                            placeholder="Gcash Number" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="formFile" class="form-label">Upload proof of
                                                            payment</label>
                                                        <input class="form-control" type="file" id="formFile"
                                                            name="proofImg" required>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">No</button>
                                                    <button type="submit" name="paymentBtn"
                                                        class="btn btn-primary">Send</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "../footer.php" ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.recievedBtn').click(function () {
                $('#recievedModal').modal('show');
                var id = $(this).data('id');
                $('.proposalId').val(id);
            });

            $('.deleteBtn').click(function () {
                $('#deleteModal').modal('show');
                var id = $(this).data('id');

                $('.proposalId').val(id);
            });

            $('.payBtn').click(function () {
                $('#payModal').modal('show');
                var id = $(this).data('id');

                $('.proposalId').val(id);
            });
        });
    </script>
</body>

</html>