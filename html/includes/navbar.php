<?php
?>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg " color-on-scroll="500">
            <div class="container-fluid">
                <!--<a class="navbar-brand" href="index.html"> Blue Express </a>-->
                <div class="collapse navbar-collapse justify-content-end" id="navigation">
                    
                    <ul class="navbar-nav ml-auto">                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="index.php" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="no-icon">Shipment</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="booking.php">Book Shipment</a>
                                <a class="dropdown-item" href="trackshipment.php">Track/Modify Shipment</a>
                                <a class="dropdown-item" href="reports.php">Reports</a>
                                <div class="divider"></div>
                                <a class="dropdown-item" href="#">More Options</a>
                            </div>
                        </li>   
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="facility.php" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="no-icon">Facility</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="facility.php?act=searchFac">Search Facility</a>
                                <a class="dropdown-item" href="facility.php?act=createFac">Create Facility</a>                                                                
                            </div>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link" href="users.php">
                                <span class="no-icon">Users</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="myaccount.php" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="no-icon">Your Account</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="./queries/deauth.php">Logout</a>                                
                            </div>
                        </li>                        
                    </ul>
                </div>
            </div>
        </nav>
    <!-- End Navbar -->
    