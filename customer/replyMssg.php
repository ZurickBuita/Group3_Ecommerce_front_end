<?php
session_start();
require '../db_conn.php';

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
} else if ($_SESSION['user'] === 'admin') {
    header("Location: ../admin/dashboard.php");
}
$alertMssg = "";

$query = "SELECT * FROM `admin`";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$adminId = $row['Id'];
$adminEmail = $row['email'];

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


        $query = "INSERT INTO `messages`(`mssgSubj`, `mssgTo`, `mssgFrom`, `mssgContent`, `mssgFile`)
         VALUES ('$mssgSubj','$mssgTo','$mssgFrom','$mssgContent','$fileUrl')";

        if (mysqli_query($conn, $query)) {
            $alertMssg =
                '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
         Message Sent Successfully!
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeTech | Create New Message</title>
    <?php include "../links.php" ?>
    <link rel="Stylesheet" href="../style.css">
</head>

<body class="bg-light position-relative">
    <?php include 'header.php' ?>

    <main class="container-fluid p-4">
        <div class="card overflow-hidden">
            <div class="card-header bg-white">
                <h5 class="card-title">Messages</h5>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-4 col-sm-12 p-0 border-end">
                        <div class="rounded-0 list-group">
                            <a href="createNewMessage.php"
                                class="border-0 border-bottom list-group-item list-group-item-action active text-white">&nbsp;&nbsp;<i
                                    class="bi bi-envelope-plus"></i>&nbsp;Create New Message</a>
                            <a href="inbox.php"
                                class="border-0 border-bottom list-group-item list-group-item-action">&nbsp;&nbsp;<i
                                    class="bi bi-inbox"></i>&nbsp;Inbox</a>
                            <a href="sentMssg.php"
                                class="border-0 border-bottom list-group-item list-group-item-action">&nbsp;&nbsp;<i
                                    class="bi bi-send-check"></i>&nbsp;Sent</a>
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-12 ps-0">
                        <div class="card-header">
                            <div class="card-title mb-0">Create new Message</div>
                        </div>
                        <div class="card-body">
                            <?= $alertMssg ?>
                            <form class="ps-2 pe-0" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <input type="text" name="mssgTo" id="mssgTo" class="form-control" placeholder="To: "
                                        value="<?= $adminEmail ?>" require>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="mssgSubj" id="mssgSubj" class="form-control"
                                        placeholder="Subject: ">
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" name="mssgContent" id="mssgContent" cols="30"
                                        rows="10" required></textarea>
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
            </div>
        </div>
    </main>

    <?php include "../footer.php" ?>

</body>

</html>