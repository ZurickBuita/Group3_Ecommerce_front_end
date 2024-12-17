<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}

$projectTitle = $projectType = $projectBudget = $projectCategory = $projectSummary = $projectDetails = $projectImg = "";
$projectTitleErr = $projectTypeErr = $projectBudgetErr = $projectCategoryErr = $projectSummaryErr = $projectDetailsErr = $projectImgErr = "";
$values = true;
$alertMssg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['postRequest'])) {
        $projectTitle = test_input($_POST['projectTitle']);
        $projectType = $_POST['projectType'];
        $projectBudget = test_input($_POST['projectBudget']);
        $projectCategory = test_input($_POST['projectCategory']);
        $projectSummary = test_input($_POST['projectSummary']);
        $projectDetails = test_input($_POST['projectDetails']);
        $customerId = $_SESSION['ID'];


        if (empty($projectTitle)) {
            $values = false;
            $projectTitleErr = "Project Title is empty!";
        }

        if (empty($projectBudget)) {
            $values = false;
            $projectBudgetErr = "Project Budget is empty!";
        }

        if (empty($projectCategory)) {
            $values = false;
            $projectCategoryErr = "Project Category is empty!";
        }

        if (empty($projectSummary)) {
            $values = false;
            $projectSummaryErr = "Project Summary is empty!";
        }

        if (empty($projectDetails)) {
            $values = false;
            $projectDetailsErr = "Project Details is empty!";
        }

        // if (isset($_FILES['projectImg'])) {
        //     $img_name = $_FILES['projectImg']['name'];
        //     $img_size = $_FILES['projectImg']['size'];
        //     $tmp_name = $_FILES['projectImg']['tmp_name'];
        //     $img_err = $_FILES['projectImg']['error'];

        //     if ($img_err === 0) {
        //         if ($img_size > 2225000) {
        //             $values = false;
        //             echo "<script>alert('Sorry, your file is too large.')</script>";
        //         } else {
        //             $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        //             $img_ex_lc = strtolower($img_ex);

        //             $allowed_exs = array("jpg", "jpeg", "png");

        //             if (in_array($img_ex_lc, $allowed_exs)) {
        //                 $projectImg = uniqid("IMG-", true) . "." . $img_ex_lc;
        //                 $img_upload_path = "../uploads/postRequest/" . $projectImg;

        //                 move_uploaded_file($tmp_name, $img_upload_path);
        //             } else {
        //                 echo "<script>alert('You can't upload files of this type')</script>";
        //             }
        //         }
        //     } else {
        //         $values = false;
        //         echo "<script>alert('unknown error occurred!')</script>";
        //     }
        // }

        if ($values === true) {
            $proposalId = "";
            $notifType = $_SESSION['user'];
            $query = "INSERT INTO `project_proposal`( `projectTitle`, `projectType`, `budgetOffer`, `projectCategory`, `projectSummary`, `projectDetails`, `customerId`) 
            VALUES ('$projectTitle','$projectType','$projectBudget','$projectCategory','$projectSummary','$projectDetails','$customerId')";
            $query_run = mysqli_query($conn, $query);

            $query = "SELECT * FROM `project_proposal` WHERE projectTitle = '$projectTitle' AND projectImg = '$projectImg'";

            if ($result = mysqli_query($conn, $query)) {
                if (mysqli_num_rows($result) === 1) {
                    foreach ($result as $data) {
                        $proposalId = $data['proposalId'];
                    }
                }
            }

            $notifText = "New Post request";
            $query = "INSERT INTO `notification`(`userId`, `notificationText`, `notificationType`, `proposalId`) VALUES ('$customerId','$notifText','$notifType','$proposalId')";
            $query_run = mysqli_query($conn, $query);

            $alertMssg = '<div class="toast align-items-center text-bg-success border-0 fade show position-fixed bottom-0 start-0 my-3 mx-1" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body">
              Project Proposal sent Successfully!
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
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
    <title>CodeTech|Post a Request</title>
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

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-4 mx-2">
            <li class="breadcrumb-item"><a href="homePage.php">Codetech</a></li>
            <li class="breadcrumb-item active">Post a Request</li>
        </ol>
    </nav>
    <div class="main px-4 my-2">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-6 col-sm-12">
                <div class="card shadow-none border">
                    <div class="card-header bg-white ">
                        <h5 class="m-0 text-dark">Post a Request</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST"
                            enctype="multipart/form-data">
                            <div class="mb-3">
                                <?php echo $alertMssg ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Project Title</label>
                                <input type="text" name="projectTitle" class="form-control"
                                    placeholder="Enter project title">
                                <div>
                                    <small class="text-danger fw-bold">
                                        <?= $projectTitleErr ?>
                                    </small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Project Type</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="projectType" id="exampleRadios1"
                                        value="Fixed">
                                    <label class="form-check-label" for="exampleRadios1">
                                        Fixed
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="projectType" id="exampleRadios2"
                                        value="Long Term">
                                    <label class="form-check-label" for="exampleRadios2">
                                        Long Term
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Project budget offer($ sign)</label>
                                <input type="text" name="projectBudget" class="form-control"
                                    placeholder="Enter project budget">
                                <div>
                                    <small class="text-danger fw-bold">
                                        <?= $projectBudgetErr ?>
                                    </small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Project Category</label>
                                <select class="form-select" name="projectCategory">
                                    <option selected value="">Open this select menu</option>
                                    <?php
                                    $query = "SELECT * FROM `category`";
                                    $query_run = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach ($query_run as $data) {
                                            ?>
                                    <option value="<?= $data['categoryId'] ?>">
                                        <?= $data['categoryName'] ?>
                                    </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div>
                                    <small class="text-danger fw-bold">
                                        <?= $projectCategoryErr ?>
                                    </small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Project summary</label>
                                <textarea class="form-control" rows="3" name="projectSummary"></textarea>
                                <div>
                                    <small class="text-danger fw-bold">
                                        <?= $projectSummaryErr ?>
                                    </small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Project Details</label>
                                <textarea class="form-control" rows="3" name="projectDetails"></textarea>
                                <div>
                                    <small class="text-danger fw-bold">
                                        <?= $projectDetailsErr ?>
                                    </small>
                                </div>
                            </div>
                            <!-- <div class="mb-3">
                                <label class="form-label">File attachment</label>
                                <input class="form-control" type="file" name="projectImg">
                            </div> -->
                            <div class="mb-3">
                                <input class="form-control btn btn-primary" type="submit" name="postRequest"
                                    value="Post Service">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "../footer.php" ?>
</body>

</html>