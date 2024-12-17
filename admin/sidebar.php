<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="dashboard.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Services</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="addServices.php">
                        <i class="bi bi-circle"></i><span>Add New Service</span>
                    </a>
                </li>
                <li>
                    <a href="allServices.php">
                        <i class="bi bi-circle"></i><span>All Services</span>
                    </a>
                </li>
                <li>
                    <a href="orders.php">
                        <i class="bi bi-circle"></i><span>Orders</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Projects</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="projectProposal.php">
                        <i class="bi bi-circle"></i><span>Pendings</span>
                    </a>
                </li>
                <li>
                    <a href="pendingPayments.php">
                        <i class="bi bi-circle"></i><span>Pending Payments</span>
                    </a>
                </li>
                <li>
                    <a href="runningProjects.php">
                        <i class="bi bi-circle"></i><span>Running</span>
                    </a>
                </li>
                <li>
                    <a href="submittedProjects.php">
                        <i class="bi bi-circle"></i><span>Submitted</span>
                    </a>
                </li>
                <li>
                    <a href="completeProject.php">
                        <i class="bi bi-circle"></i><span>Completed</span>
                    </a>
                </li>
                <li>
                    <a href="cancelledProject.php">
                        <i class="bi bi-circle"></i><span>Cancelled</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Forms Nav -->

        <li class="nav-item">
            <a class="nav-link " href="category.php">
                <i class="bi bi-grid"></i>
                <span>Category</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#messages" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Messages</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="messages" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="createMssg.php">
                        <i class="bi bi-circle"></i><span>Create message</span>
                    </a>
                    <a href="inbox.php">
                        <i class="bi bi-circle"></i><span>Inbox</span>
                    </a>
                    <a href="sentMssg.php">
                        <i class="bi bi-circle"></i><span>Sent</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Messages Nav -->
    </ul>

</aside><!-- End Sidebar-->