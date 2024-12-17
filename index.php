<?php
session_start();
include 'verify_user.php';
require 'db_conn.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML & CSS Developers | CodeTech</title>
    <?php include "links.php" ?>
    <link rel="Stylesheet" href="style.css">
    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        .md-sm {
            display: none;
        }

        @media only screen and (max-width: 850px) {
            .md-sm {
                display: block;
            }
        }

        .img {
            background: url("img/banner.jpg");
            background-size: cover;
            background-position: center;
            border-radius: 15px;
        }

        #navbar {
            position: fixed;
            top: 0;
            width: 100%;
            display: block;
            transition: top 1.2s ease;
            z-index: 3;
        }
    </style>
</head>

<body class="bg-light position-relative">
    <!-- header start -->
    <div id="navbar">
        <?php include "header.php" ?>
    </div>

    <!-- header end -->

    <main class="container-fluid py-1 px-2  mt-5">
        <!-- Banner start -->

        <div class="img position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light text-white shadow">
            <div class="col-md-5 p-lg-5 mx-auto my-5">
                <h1 class="display-4 fw-normal">Welcome!</h1>
                <p class="lead fw-normal">Programming project deadline is near? We got you covered!</p>
                <a class="btn bg-secondary text-white" href="login.php">Get started</a>
            </div>
            <div class="product-device shadow-sm d-none d-md-block"></div>
            <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
        </div>
        <div class="mt-5 pt-3 container d-flex align-items-center justify-content-center">
            <video class="w-100" controls muted>
                <source src="uploads/videoAdvertising/Act9_Buita_Lucañas_Del_Rosario.mp4" type="video/mp4">
                <source src="uploads/videoAdvertising/Act9_Buita_Lucañas_Del_Rosario.mp4" type="video/ogg">
                Your browser does not support the video tag.
            </video>
        </div>
        <!-- Banner end -->

        <div class="h2 px-3 mt-3">Categories</div>
        <div class="row mx-2">
            <?php
            $query = "SELECT * FROM `category`";
            $query_run = mysqli_query($conn, $query);

            if (mysqli_num_rows($query_run) > 0) {
                foreach ($query_run as $data) {
                    ?>
            <div class="col-xl-3 col-lg-4 col-md-4 p-2">
                <a href="customer/category.php?id=<?=$data['categoryId']?>"
                    class="bg-white shadow rounded d-flex align-items-center p-3">
                    <img src="uploads/category_img/<?= $data['categoryImg'] ?>" width="35" height="35" />
                    <span class="fw-semibold ms-2">
                        <?= $data['categoryName'] ?>
                    </span>
                </a>
            </div>
            <?php
                }
            }
            ?>
        </div>
        <div class="h2 px-3 my-3">Popular Services</div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 gy-4 px-3">

            <?php
            $query = "SELECT * FROM (`package` INNER JOIN service ON package.serviceId = service.serviceId) WHERE packageType = '1'";

            if ($result = mysqli_query($conn, $query)) {
                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $data) {
                        ?>
            <div class="col-xl-3 col-md-4 d-flex justify-content-center position-relative">
                <div class="card w-100 shadow">
                    <a href="#">
                        <img src="uploads/services_img/<?= $data['imgUrl'] ?>" class="card-img-top" alt="thumbnail"
                            height="200px">
                    </a>
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
                                        <?=$data['price']?>.00
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
        <div class="accordion-container px-3 mt-5">
            <h2 class="h2 text-secondary">Programming & Tech FAQs</h2>

            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button bg-transparent collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                            aria-controls="collapseOne">
                            What is Web programming?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Web programming or development use code to focus on the website functionality and ensure it
                            works and is easy to use. It involves markup, writing, network security and coding which is
                            client and server side. The most popular web programming languages are HTML, XML,
                            JavaScript, PHP, ASP.Net and Python.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button bg-transparent collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                            aria-controls="collapseTwo">
                            How do I choose the right freelance programmer for my project?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            With so many programming services, it’s a challenge to choose the right programmer.
                            Formulate a
                            clear brief, decide on a budget, deadlines and scope. Select a programmer based not only on
                            their skills and experience but also on how well you might work and communicate.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button bg-transparent collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                            aria-controls="collapseThree">
                            Do I need to prepare something for my programmer?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Yes, good documentation and a clear brief are crucial for the success of getting the desired
                            result for your project. Formulate your initial high level idea and brainstorm it until you
                            have
                            a clear vision. Next, turn your idea into detailed functionality requirements for the
                            backend
                            programming and detail your technical requirements (platform, devices etc.) Also add
                            non-functional requirements e.g. performance, security, load and clearly specify the scope
                            of
                            the project.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button bg-transparent collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false"
                            aria-controls="collapseFour">
                            What type of services can I find in Programming & Tech?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Starting with web development for client-side (frontend) and server-side (backend), the
                            category
                            also offers specialists in Wordpress and e-commerce development, mobile or desktop apps,
                            support
                            & cybersecurity, as well as user testing and QA.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button bg-transparent collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false"
                            aria-controls="collapseFive">
                            How do I find good developers on Fiverr?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Fiverr offers a huge choice of developers, so refine your requirements to determine whether
                            you
                            need a full-stack developer - proficient at both backend (server-side) and frontend
                            (client-side) or a more narrow specialist. Get quotes and discuss your needs with at least 3
                            developers for an informed decision.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    </div>

    </div>

    <?php include "footer.php" ?>
    <!-- javascript -->
    <script defer src="js/windowOnScroll.js"></script>

</body>

</html>