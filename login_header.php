<div class="container-fluid bg-light d-flex align-items-center justify-content-between py-1 px-4">
    <div class="col me-3">
        <a href="index.php"> <img src="img/CodeTech_logo.png" alt="logo" width="140" height="50"></a>
    </div>

    <div class="col col-lg bg-white flex-fill">
        <form action="" method="post" class="form w-100 d-flex align-items-center">
            <input class="flex-grow-1 px-2 py-1" type="search" name="search-input" placeholder="Search for a service">
            <button class="bg-secondary py-1 border-0 d-flex align-items-center justify-content-center px-3"
                type="submit" name="searchButton">
                <span class="material-symbols-rounded text-white">search</span>
            </button>
        </form>
    </div>

    <div class="col d-flex align-items-center justify-content-end">
        <div class="header-icons d-flex align-items-center">


            <div class="col-sm dropdown mx-3" type="button">
                <span class="material-symbols-rounded px-3" data-bs-toggle="dropdown">search</span>

                <div class="dropdown-menu vw-100 mt-3 p-2">
                    <form action="#" method="post" class="form d-flex align-items-center overflow-hidden">
                        <input class="flex-grow-1 px-2 py-1" type="search" name="search-input"
                            placeholder="Search for a service">
                        <button
                            class="bg-secondary border-0 d-flex align-items-center m-0 justify-content-center px-3 py-2"
                            type="submit" name="searchButton">
                            <span class="material-symbols-rounded text-white">search</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="dropdown">
                <span type="button" class="px-3 material-symbols-rounded" data-bs-toggle="dropdown">notifications</span>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <span type="button" class="px-3 material-symbols-rounded" data-bs-toggle="dropdown">mail</span>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>

            <div>
                <a href="#" type="button"
                    class="text-decoration-none text-dark px-3 material-symbols-rounded">favorite</a>
            </div>

            <div class="dropdown">
                <span type="button" class="text-decoration-underline" aria-expanded="false"
                    data-bs-toggle="dropdown">Orders</span>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>

        </div>

        <div class="dropdown ms-3">
            <button class="border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="rounded-circle" src="img/CodeTech.png" width="35" height="35" alt="user_avatar">
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</div>