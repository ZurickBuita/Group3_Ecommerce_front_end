<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}

$categoryName = $categoryImg = $alertMssg = "";
$categoryNameErr = $categoryImg_err = "";
$values = true;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addCategory'])) {
        $categoryName = test_input($_POST['categoryName']);
        if (empty($categoryName)) {
            $values = false;
            $categoryNameErr = "Category Name is empty!";
        }

        if (isset($_FILES['categoryImg'])) {
            $img_name = $_FILES['categoryImg']['name'];
            $img_size = $_FILES['categoryImg']['size'];
            $tmp_name = $_FILES['categoryImg']['tmp_name'];
            $img_err = $_FILES['categoryImg']['error'];

            if ($img_err === 0) {
                if ($img_size > 2225000) {
                    $values = false;
                    echo "<script>alert('Sorry, your file is too large.')</script>";
                } else {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);

                    $allowed_exs = array("jpg", "jpeg", "png");

                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $categoryImg = uniqid("IMG-", true) . '.' . $img_ex_lc;
                        $img_upload_path = '../uploads/category_img/' . $categoryImg;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        echo "<script>alert('You can't upload files of this type')</script>";
                    }
                }
            } else {
                $values = false;
                echo "<script>alert('unknown error occurred!')</script>";
            }
        } else {
            $values = false;
            $categoryImg_err = "Category Image is empty!";
        }


        if ($values === true) {
            $query = "INSERT INTO `category`(`categoryName`, `categoryImg`) VALUES ('$categoryName','$categoryImg')";
            $query_run = mysqli_query($conn, $query);
            $alertMssg =
                '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                 Category created successfully.
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
        } else {
            $alertMssg =
            '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            Category cannot be created.
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
        }
    }

    if (isset($_POST['editCategory'])) {
        $categoryName = test_input($_POST['categoryName']);
        if (empty($categoryName)) {
            $values = false;
            $categoryNameErr = "Category Name is empty!";
        }

        if (isset($_FILES['categoryImg'])) {
            $img_name = $_FILES['categoryImg']['name'];
            $img_size = $_FILES['categoryImg']['size'];
            $tmp_name = $_FILES['categoryImg']['tmp_name'];
            $img_err = $_FILES['categoryImg']['error'];

            if ($img_err === 0) {
                if ($img_size > 2225000) {
                    $values = false;
                    echo "<script>alert('Sorry, your file is too large.')</script>";
                } else {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);

                    $allowed_exs = array("jpg", "jpeg", "png");

                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $categoryImg = uniqid("IMG-", true) . '.' . $img_ex_lc;
                        $img_upload_path = '../uploads/category_img/' . $categoryImg;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        echo "<script>alert('You can't upload files of this type')</script>";
                    }
                }
            }
        }

        if ($values === true) {
            $categoryId = test_input($_POST['categoryId']);
            if (empty($categoryImg)) {
                $query = "UPDATE `category` SET `categoryName`='$categoryName' WHERE `categoryId` = '$categoryId'";
            } else {
                $query = "SELECT * FROM `category` WHERE `categoryId` = '$categoryId'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $prevImg = $row['categoryImg'];

                unlink("../uploads/category_img/$prevImg");

                $query = "UPDATE `category` SET `categoryName`='$categoryName',`categoryImg`='$categoryImg' WHERE `categoryId` = '$categoryId'";
            }


            $query_run = mysqli_query($conn, $query);
            $alertMssg =
                '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                 Category Updated Successfully!
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
        }
    }

    if (isset($_POST['deleteCategory'])) {
        $categoryId = $_POST['deleteCategoryId'];
        $query = "SELECT * FROM `category` WHERE `categoryId` = '$categoryId'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $prevImg = $row['categoryImg'];

        unlink("../uploads/category_img/$prevImg");

        $query = "DELETE FROM `category` WHERE categoryId = '$categoryId'";
        $query_run = mysqli_query($conn, $query);
        $alertMssg =
            '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
         Category deleted Successfully!
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>';
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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CodeTech Admin|Category</title>

    <?php include 'links.php' ?>

    <style>
        .overflow-scroll::-webkit-scrollbar {
            display: none;
        }
    </style>

</head>

<body class="position-relative">
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main pb-5 mb-5">

        <div class="pagetitle">
            <h1>Categories</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <?php echo $alertMssg ?>
        <section class="card shadow-none border">

            <div class="card-header d-flex justify-content-end">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategory">
                    Add Category
                </button>

                <!-- Modal for Adding -->
                <div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Category</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST"
                                enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Category Name"
                                            name="categoryName">
                                        <div>
                                            <span class="text-danger fw-bold">
                                                <?php echo $categoryNameErr ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Upload Image</label>
                                        <input type="file" class="form-control" name="categoryImg">
                                        <div>
                                            <span class="text-danger fw-bold">
                                                <?php echo $categoryImg_err ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="addCategory">Save
                                        changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body overflow-scroll pt-3" style="-webkit-scrollbar:">
                <table class="table table-hover text-nowrap ">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category Img</th>
                            <th scope="col">Category Title</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM `category`";

                        if ($result = mysqli_query($conn, $query)) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <th>
                                        <?= $row['categoryId'] ?>
                                    </th>
                                    <td>
                                        <!-- dfadsfadfas -->
                                        <img src="../uploads/category_img/<?= $row['categoryImg'] ?>" width="80" height="80" />
                                    </td>
                                    <td>
                                        <?= $row['categoryName'] ?>
                                    </td>
                                    <td>

                                        <a href="#editModal" class="btn btn-primary editBtn" data-toggle="modal"
                                            data-id="<?= $row['categoryId'] ?>" data-img="<?= $row['categoryImg'] ?>"
                                            data-name="<?= $row['categoryName'] ?>">Edit</a>

                                        <a href="#deleteModal" class="btn btn-danger deleteBtn" data-toggle="modal"
                                            data-id="<?= $row['categoryId'] ?>">Delete</a>

                                        <!-- Modal for editing -->
                                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Category</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form id="editForm"
                                                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                        method="POST" enctype="multipart/form-data">

                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <input type="hidden" name="categoryId" id="categoryId">
                                                                <label class="form-label">Category Name</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter Category Name" name="categoryName"
                                                                    id="categoryName">
                                                                <div>
                                                                    <span class="text-danger fw-bold">
                                                                        <?php echo $categoryNameErr ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Upload Image</label>
                                                                <input type="file" class="form-control" name="categoryImg">
                                                                <div>
                                                                    <span class="text-danger fw-bold">
                                                                        <?php echo $categoryImg_err ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <div class="alert alert-secondary py-2 alert-dismissible fade show"
                                                                    role="alert">
                                                                    <img id="categoryImg" src="" alt="Previous Image" width="50"
                                                                        height="50">
                                                                    <span id="categoryImgUrl"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button id="editCategory" type="submit" name="editCategory"
                                                                class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal for Delete -->
                                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModal"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Category</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete this?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">No</button>
                                                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                                            method="POST">
                                                            <input type="hidden" name="deleteCategoryId" id="deleteCategoryId">
                                                            <button type="submit" name="deleteCategory"
                                                                class="btn btn-primary">Yes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="9" class="bg-light h5 text-center">No Records Found!</td>
                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {

            $('.editBtn').click(function () {
                var id = $(this).data('id');
                var img = $(this).data('img');
                var name = $(this).data('name');

                $('#editModal').modal('show');

                $('#categoryId').val(id);
                $('#categoryName').val(name);
                $('#categoryImgUrl').text(img);

                var currentImgSrc = '../uploads/category_img/' + img;
                $('#categoryImg').attr('src', currentImgSrc);
            });

            $('.deleteBtn').click(function () {
                var id = $(this).data('id');
                $('#deleteCategoryId').val(id);

                $('#deleteModal').modal('show');
            });
        });
    </script>
</body>

</html>