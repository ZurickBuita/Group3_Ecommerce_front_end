<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'customer') {
    header("Location: ../customer/category.php");
}

$alertMssg = "";
$mssgToId = $_GET['id'];

$query = "SELECT * FROM `customer` WHERE `Id` = '$mssgToId'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$mssgTo = $row['email'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['createNewMssg'])) {
        $mssgSubj = test_input($_POST['mssgSubj']);
        $mssgTo = test_input($_POST['mssgTo']);
        $mssgFrom = $_SESSION['ID'];
        $mssgContent = test_input($_POST['mssgContent']);
        $fileUrl = '';

        if (isset($_FILES['mssgFile'])) {
            $file_name = $_FILES['mssgFile']['name'];
            $file_size = $_FILES['mssgFile']['size'];
            $tmp_name = $_FILES['mssgFile']['tmp_name'];
            $file_err = $_FILES['mssgFile']['error'];

            if ($file_err === 0) {
                $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
                $file_ex_lc = strtolower($file_ex);

                $allowed_exs = array("jpg", "jpeg", "png", "pdf", "zip", "rar", "mp4");

                if (in_array($file_ex_lc, $allowed_exs)) {
                    $fileUrl = uniqid("FILE-", true) . '.' . $file_ex_lc;
                    $file_upload_path = '../uploads/FileMssg/' . $fileUrl;
                    move_uploaded_file($tmp_name, $file_upload_path);
                } else {
                    echo "<script>alert('You can't upload files of this type')</script>";
                }
            }
        }


        $query = "INSERT INTO `messages`(`mssgSubj`, `mssgTo`, `mssgFrom`, `mssgContent`, `mssgFile`, `isAdmin`)
       VALUES ('$mssgSubj','$mssgTo','$mssgFrom','$mssgContent','$fileUrl', '1')";

        if (mysqli_query($conn, $query)) {
            $alertMssg =
                '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                  Message Sent Successfully.
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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CodeTech Admin| Reply</title>

    <?php include 'links.php' ?>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        *:focus {
            outline: none;
        }
    </style>
</head>

<body>
    <?php include "header.php" ?>
    <?php include "sidebar.php" ?>
    <main id="main" class="main pb-5 mb-5">

        <div class="container-fluid">
            <div class="card shadow-none">
                <div class="card-header">
                    <div class="d-flex align-items-center card-title m-0 p-0">
                        <a href="inboxDetail.php?id=<?=$mssgToId?>"
                            class="rounded-circle btn btn-light d-flex align-items-center justify-content-center me-2"
                            style="width: 40px; height: 40px;"><i class="fw-bold bi bi-arrow-left"></i></a>Reply
                    </div>
                </div>
                <div class="card-body px-3">
                    <?= $alertMssg ?>
                    <form class="ps-2 pe-0" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST"
                        enctype="multipart/form-data">
                        <div class="mb-3 position-relative">
                            <input type="text" name="mssgTo" id="mssgTo" class="form-control" placeholder="To:"
                                value="<?= $mssgTo ?>" required>
                            <div class="list-group d-flex flex-column bg-white shadow rounded position-absolute top-100 start-0"
                                id="email-list"></div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="mssgSubj" id="mssgSubj" class="form-control"
                                placeholder="Subject: ">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="mssgContent" id="mssgContent" cols="30" rows="10"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload a File (Optional)</label>
                            <input type="file" class="form-control" name="mssgFile">
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="createNewMssg" class="btn btn-primary"><i
                                    class="bi bi-send"></i>&nbsp;Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include "script.php" ?>

    <script>
        function showEmails(str) {
            if (str.length == 0) {
                document.getElementById("email-list").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("email-list").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "getemails.php?q=" + str, true);
                xmlhttp.send();
            }
        }
        function clearEmailList() {
            document.getElementById("email-list").innerHTML = "";
        }

    </script>
</body>

</html>