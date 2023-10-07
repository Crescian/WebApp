<?php include 'header.php' ?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow fw-bold" aria-label="Offcanvas navbar large">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fa-solid fa-computer text-bg-primary rounded py-2 px-2"></i>
            <span class="text-primary">IT ASSET</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2"
            aria-controls="offcanvasNavbar2">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end text-bg-light" tabindex="-1" id="offcanvasNavbar2"
            aria-labelledby="offcanvasNavbar2Label">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbar2Label">IT ASSET</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- =========================================================================================== -->
                <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            File
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="File_Username_and_Access.php">Username and Access</a></li>
                            <li><a class="dropdown-item" href="File_Borrowed_Items.php">Borrowed Items</a></li>
                            <li><a class="dropdown-item" href="File_PC_Guideline.php">PC Guideline</a></li>
                            <li><a class="dropdown-item" href="File_Assign_CPU.php">Assign CPU</a></li>
                            <li><a class="dropdown-item" href="File_Switch_Assignment.php">Switch Assignment</a></li>
                            <li><a class="dropdown-item" href="File_PC_Count.php">PC Count</a></li>
                            <li><a class="dropdown-item" href="File_Defective_Items.php">Defective Items</a></li>
                            <li><a class="dropdown-item" href="File_PC_Issuance.php">PC Issuance</a></li>
                            <li><a class="dropdown-item" href="File_Database_List.php">Database List</a></li>
                            <li><a class="dropdown-item" href="File_USB_Registration.php">USB Registration</a></li>
                            <li><a class="dropdown-item" href="File_Program_Access.php">Program Access</a></li>
                            <li class="dropdown dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Request Form
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="File_SH_Request_Form.php">&bull; SH Request
                                            Form</a></li>
                                    <li><a class="dropdown-item" href="File_User_Access_Request_Form.php">&bull; User
                                            Access Request Form</a></li>
                                    <li><a class="dropdown-item" href="File_RSM_Form.php">&bull; RSM Form</a></li>
                                    <li class="dropdown dropend">
                                        <a class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-expanded="false" href="#">&bull; Backup Recovery Request Form</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="File_SH_Request_Form.php">&#9702; SH
                                                    Request Form</a></li>
                                            <li><a class="dropdown-item"
                                                    href="File_User_Access_Request_Form.php">&#9702; User Access Request
                                                    Form</a></li>
                                            <li><a class="dropdown-item" href="File_RSM_Form.php">&#9702; RSM Form</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="File_Backup_Recovery_Request_Form.php">&#9702; Backup Recovery
                                                    Request Form</a></li>
                                        </ul>

                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    IT Repair Maintenance
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="File_IT_RMRF.php">&bull; IT RMRF</a></li>
                                    <li><a class="dropdown-item" href="File_IT_Service_Report.php">&bull; IT Service
                                            Report</a></li>
                                </ul>
                            </li>
                            <li class="dropdown dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Back Up
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="File_Antivirus_and_Source_Code_Backup.php">&bull;
                                            File, Antivirus and Source Code Backup</a></li>
                                    <li><a class="dropdown-item" href="File_Database_Backup.php">&bull; Database
                                            Backup</a></li>
                                </ul>
                            </li>
                            <li class="dropdown dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Daily Activities
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="File_Daily_IT_Activity.php">&bull; Daily IT
                                            Activity</a></li>
                                    <li><a class="dropdown-item" href="File_Software_Hardware_Request.php">&bull;
                                            Software Hardware Request</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            Hardware
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Issuance
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="Hardware_Issuance.php">&bull; Employee</a></li>
                                    <li><a class="dropdown-item" href="Hardware_Machine.php">&bull; Machine</a></li>
                                </ul>
                            </li>
                            <li><a class="dropdown-item" href="Software_Issuance.php">Software</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Software</a>
                    </li>

                </ul>
                <form class="d-flex mt-2 mt-lg-0 my-search" role="search">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</nav>