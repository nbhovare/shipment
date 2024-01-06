<?php
?>



        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light" color-on-scroll="500">            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation1" aria-controls="navigation1" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
                <a class="navbar-brand" href="./index.php"> Blue Express </a>
                <div class="collapse navbar-collapse justify-content-end" id="navigation1">
                    

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
                                <span class="no-icon">Branch</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="facility.php?act=searchFac">Search Branch</a>
                                <a class="dropdown-item" href="facility.php?act=createFac">Create Branch</a>                                                                
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
                                <a class="dropdown-item" href="./myProfile.php">My Profile</a>  
                                <a class="dropdown-item" href="./queries/deauth.php">Logout</a>                                
                            </div>
                        </li>                        
                    </ul>
                </div>
            
        </nav>
    <!-- End Navbar -->
    