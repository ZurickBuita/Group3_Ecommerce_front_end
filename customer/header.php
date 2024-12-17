<?php

$id = $_SESSION['ID'];
$query = "SELECT * FROM `customer` WHERE Id = '$id'";
$query_run = mysqli_query($conn, $query);
$imgUrl = "";

if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $data) {
        $imgUrl = $data['imgUrl'];
    }
}

$notificationId = "";
$query = "SELECT COUNT(*) AS 'notifId' FROM `notification` WHERE notificationType = 'admin' AND isRead = '0';";
if ($result = mysqli_query($conn, $query)) {
    if (mysqli_num_rows($result) > 0) {
        foreach ($result as $data) {
            $notificationId = $data['notifId'];
        }
    }
}
?>

<div class="header bg-white shadow-sm">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a href="homePage.php" class="navbar-brand">
            <img src="img/CodeTech_logo.png" alt="logo" width="150" height="58" alt="">
        </a>

        <div class="flex-fill px-5">
            <form action="#" method="post" class="form m-0  mx-5 d-flex align-items-center ">
                <input class="flex-grow-1 px-2 py-1" type="search" name="search-input"
                    placeholder="Search for a service">
                <button class="py-1 border-0 d-flex align-items-center justify-content-center px-3" type="submit"
                    name="searchButton" style="background: #001b4d;">
                    <span class="material-symbols-rounded text-white">search</span>
                </button>
            </form>
        </div>
        <ul class="navbar-nav d-flex align-items-center flex-row ">

            <li class="nav-item dropdown">
                <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
                    <span class="material-symbols-rounded mx-3">notifications</span>
                    <span class="badge bg-primary position-absolute top-0 start-50 z-5">
                        <?= $notificationId ?>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications overflow-auto"
                    style="width: 350px; height: 400px;">
                    <li class="dropdown-header">
                        You have
                        <?= $notificationId ?> new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <?php
                    $query = "SELECT * FROM 
                            ((`notification` 
                            INNER JOIN project_proposal ON notification.proposalId = project_proposal.proposalId)
                            INNER JOIN customer ON project_proposal.customerId = customer.Id) WHERE notificationType = 'admin'";

                    if ($result = mysqli_query($conn, $query)) {
                        if (mysqli_num_rows($result) > 0) {
                            foreach ($result as $data) {
                                $post_date = $data['postedAt']; // the date and time the post was made, retrieved from the database
                                $current_date = date('Y-m-d H:i:s'); // the current date and time
                                $posted_ago = "";
                                // calculate the difference between the post date and the current date in seconds
                                $diff = strtotime($current_date) - strtotime($post_date);

                                // check if the post is less than a minute old
                                if ($diff < 60) {
                                    $posted_ago = 'Just now';
                                }
                                // check if the post is less than an hour old
                                else if ($diff < 3600) {
                                    $minutes = floor($diff / 60);
                                    $posted_ago = $minutes . ' minutes ago';
                                }
                                // check if the post is less than a day old
                                else if ($diff < 86400) {
                                    $hours = floor($diff / 3600);
                                    $posted_ago = $hours . ' hours ago';
                                }
                                // otherwise, the post is at least a day old
                                else {
                                    $days = floor($diff / 86400);
                                    $posted_ago = $days . ' days ago';
                                }
                                if ($data['isRead'] == 0) {
                                    ?>
                    <a href="">
                        <li class="notification-item bg-light d-flex align-item-center px-3 py-2">
                            <img class="rounded-circle me-3" src="../uploads/customer_img/<?= $data['imgUrl'] ?>"
                                width="45" height="40" />
                            <div>
                                <h6>
                                    <?= $data['notificationText'] ?>
                                </h6>
                                <p class="m-0">
                                    <?= $data['projectTitle'] ?>
                                </p>
                                <small>
                                    <?= $posted_ago ?>
                                </small>

                            </div>
                        </li>
                    </a>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <?php
                                } else if ($data['isRead'] == 1) {
                                    ?>
                    <a href="projectProposal.php">
                        <li class="notification-item d-flex">
                            <img class="rounded-circle me-3" src="../uploads/customer_img/<?= $data['imgUrl'] ?>"
                                width="45" height="40" />
                            <div>
                                <h4>
                                    <?= $data['notificationText'] ?>
                                </h4>
                                <p class="m-0">
                                    <?= $data['projectTitle'] ?>
                                </p>
                                <small>
                                    <?= $posted_ago ?>
                                </small>

                            </div>
                        </li>
                    </a>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <?php
                                }
                            }
                        } else {
                            ?>
                    <li class="notification-item d-flex justify-content-center">
                        <h4>No Notification</h4>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <?php
                        }
                    }
                    ?>

                    <li class="dropdown-footer text-center">
                        <a href="#">Show all notifications</a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
                    <span class="material-symbols-rounded mx-3">mail</span>
                    <span class="badge bg-success position-absolute top-0 start-50 z-5">
                        99+
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        You have 0 new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li class="dropdown-item p-2 d-flex justify-content-center">
                        <a href="#">No Notification</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li class="dropdown-footer text-center">
                        <a href="#">Show all notifications</a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown">
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        You have 0 new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li class="dropdown-item p-2 d-flex justify-content-center">
                        <a href="#">No Notification</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li class="dropdown-footer text-center">
                        <a href="#">Show all notifications</a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle ms-3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="rounded-circle" src="../uploads/customer_img/<?php echo $imgUrl ?>" width="35"
                        height="35" alt="user_avatar">
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="updateProfile.php"><i class="bi bi-person"></i>&nbsp;&nbsp;Update
                            Profile</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li><a class="dropdown-item" href="postARequest.php"><i class="bi bi-send"></i>&nbsp;&nbsp;Post a
                            Request</a></li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li><a class="dropdown-item" href="myProjects.php"><i class="bi bi-briefcase"></i>
                            &nbsp;&nbsp;My Projects</a></li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>

                    <li><a class="dropdown-item" href="manageOrders.php"><i class="bi bi-bag"></i>
                            &nbsp;&nbsp;Manage Orders</a></li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li><a class="dropdown-item" href="createNewMessage.php"><i class="bi bi-envelope"></i>
                            &nbsp;&nbsp;Message</a></li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li><a class="dropdown-item" href="../logout.php"><i
                                class="bi bi-box-arrow-in-right"></i>&nbsp;&nbsp;Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>