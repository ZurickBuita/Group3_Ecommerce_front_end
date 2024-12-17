<?php

$id = $_SESSION['ID'];
$query = "SELECT * FROM `admin` WHERE Id = '$id'";
$query_run = mysqli_query($conn, $query);
$imgUrl = "";

if (mysqli_num_rows($query_run) > 0) {
  foreach ($query_run as $data) {
    $imgUrl = $data['imgUrl'];
  }
}


$notificationId = "";
$query = "SELECT COUNT(*) AS 'notifId' FROM `notification` WHERE notificationType = 'customer' AND isRead = '0';";
if ($result = mysqli_query($conn, $query)) {
  if (mysqli_num_rows($result) > 0) {
    foreach ($result as $data) {
      $notificationId = $data['notifId'];
    }
  }
}
?>

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="dashboard.php" class="d-flex align-items-center">
            <img src="../img/CodeTech_logo.png" width="150" height="60">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <div class="search-bar d-flex w-100 pt-3">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Search" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li>

            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number">
                        <?= $notificationId ?>
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        You have
                        <?= $notificationId ?> new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <?php
          $query = "SELECT * FROM 
          ((`notification` 
           INNER JOIN project_proposal ON notification.proposalId = project_proposal.proposalId)
           INNER JOIN customer ON project_proposal.customerId = customer.Id) WHERE notificationType = 'customer'";

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
                    <a
                        href="projectProposal.php?notificationId=<?= $data['notificationId'] ?>&isRead=<?= $data['isRead'] ?>">
                        <li class="notification-item bg-light">
                            <img class="rounded-circle me-3" src="../uploads/customer_img/<?= $data['imgUrl'] ?>"
                                width="50" height="50" />
                            <div>
                                <h4>
                                    <?= $data['notificationText'] ?>
                                </h4>
                                <p>
                                    <?= $data['projectTitle'] ?>
                                </p>
                                <p>
                                    <?= $posted_ago ?>
                                </p>
                            </div>
                        </li>
                    </a>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <?php
                } else if ($data['isRead'] == 1) {
                  ?>
                    <a href="projectProposal.php">
                        <li class="notification-item">
                            <img class="rounded-circle me-3" src="../uploads/customer_img/<?= $data['imgUrl'] ?>"
                                width="50" height="50" />
                            <div>
                                <h4>
                                    <?= $data['notificationText'] ?>
                                </h4>
                                <p>
                                    <?= $data['projectTitle'] ?>
                                </p>
                                <p>
                                    <?= $posted_ago ?>
                                </p>
                            </div>
                        </li>
                    </a>
                    <li>
                        <hr class="dropdown-divider">
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

                    <li class="dropdown-footer">
                        <a href="#">Show all notifications</a>
                    </li>

                </ul>

            </li>

            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-chat-left-text"></i>
                    <span class="badge bg-success badge-number">3</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                    <li class="dropdown-header">
                        You have 3 new messages
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="message-item">
                        <a href="#">
                            <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                            <div>
                                <h4>David Muldon</h4>
                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                <p>8 hrs. ago</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="dropdown-footer">
                        <a href="#">Show all messages</a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="../uploads/admin_img/<?php echo $imgUrl ?>" class="rounded-circle"
                        style="width: 35px; height: 35px;">
                    <span class="d-none d-md-block dropdown-toggle ps-2">Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>Admin</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="updateProfile.php">
                            <i class="bi bi-person"></i>
                            <span>Update Profile</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                            <i class="bi bi-gear"></i>
                            <span>Account Settings</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                            <i class="bi bi-question-circle"></i>
                            <span>Need Help?</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="../logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>

                </ul>
            </li>
        </ul>
    </nav>

</header>