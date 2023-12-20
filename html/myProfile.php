<?php

    session_start();
    if(!isset($_SESSION['isSession'])){
        header("location:./login.php");
    }

?>



<!-- 
=========================================================
 Light Bootstrap Dashboard - v2.0.1
=========================================================

 Product Page: https://www.creative-tim.com/product/light-bootstrap-dashboard
 Copyright 2019 Creative Tim (https://www.creative-tim.com)
 Licensed under MIT (https://github.com/creativetimofficial/light-bootstrap-dashboard/blob/master/LICENSE)

 Coded by Creative Tim

=========================================================

 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.  -->
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>My Profile</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <link href="../assets/css/light-bootstrap.css" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/css/demo.css" rel="stylesheet" />
    <script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
    <script src="../assets/js/menutab.js" type="text/javascript"></script>



    <script type="text/javascript">



     
        $(document).ready(function() {
            
        
    });

    </script>

    
</head>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
 <!-- Mini Modal -->
 <div class="modal fade  modal-primary" id="error_modal" style='z-index:9999' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--<div class="modal-header justify-content-center">
                <div class="modal-profile">
                    <i class="nc-icon nc-bulb-63"></i>
                </div>
            </div>-->
            <div class="modal-body text-center">
                <p>Resolve below error before submitting the form</p>
                <p id="modal_message"></p>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-link btn-simple" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--  End Modal -->


<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="actions_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="actions_modal_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="actions_modal_body">
        ...
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="perSaveBtn" class="btn btn-primary" style='display:none'>Save changes</button>
        <button type="button" id="userRemoveBtn" class="btn btn-primary" style='display:none'>Remove Selected Users</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->


<?php 

        include("./includes/loaders.php");

?>


    <div class="wrapper">

      
            

        <?php

            include("./includes/navbar.php");

        ?>


            <!-- Main Content -->
            <div class="content" style="margin-top:10px">
                <div class="container-fluid">
                    <div class="row">


                      <!-- Main Menu -->
                      <div class="col-md-12" id="fac_details" >
                            <div class="card">
                                <div class="card-header">                                    
                                <div class="tab">
                                    <button class="tablinks" id="fac_collapse_body_btn" onclick="openCity(event, 'search_collapse_body')">Search User</button>
                                    <button class="tablinks" id="users_collapse_body_btn" onclick="openCity(event, 'create_collapse_body')">Create User</button>                                    
                                </div>      
                                    <hr/>                                  
                                </div>
                                <!-- Collapse cards -->
                                <div class="tabcontent" id="search_collapse_body">
                                    <div class="card-body">
                                        <!-- Search User -->   
                                            <div class="card table-plain-bg">
                                                <div class="card-header ">
                                                    <h4 class="card-title">Search User</h4>                                                    
                                                </div>
                                                <div class="card-body">
                                                    <form id="searchUserForm" method="post"> 
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Search By</label>
                                                                <select name="searchBy" id="searchBy">
                                                                    <option value="search_by">Search User By</option>
                                                                    <option value="email_id">User-ID/Email-ID</option>
                                                                    <option value="facility">Facility</option>
                                                                    <option value="list_all">List All Users</option>
                                                                </select>
                                                            </div>                                                            
                                                        </div>    
                                                        <hr/>                                                                                               
                                                        <div class="row" id="searchUserFilterRow">
                                                        </div>                                                                                                                                                                                                                             
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <button type="submit" id="searchUserBtn" class="form-control btn btn-warning" disabled>Search</button>
                                                            </div>
                                                        </div>                                    
                                                        <div class="clearfix"></div>   
                                                    </form>
                                                </div>
                                            </div>
                                    <!-- Search User -->                                            
                                    </div>
                                </div>
                                <div class="tabcontent" id="create_collapse_body">
                                    <div class="card-body">       
                                        <!-- Create user -->   
                                                <div class="card table-plain-bg">
                                                    <div class="card-header ">
                                                        <h4 class="card-title">Create User</h4>
                                                        <p class="card-category">Enter Below Details Properly(Fields marked as <span style="color:red;font-weight:bold">" * "</span> are required)</p>
                                                    </div>
                                                    <div class="card-body">
                                                        <form id="createUserForm" method="post">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>First Name <span style="color:red;font-weight:bold">*</span></label>
                                                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required>
                                                                    </div>
                                                                </div>                                                
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Last Name <span style="color:red;font-weight:bold">*</span></label>
                                                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Mobile Number <span style="color:red;font-weight:bold">*</span></label>
                                                                        <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Mobile Number" required>
                                                                    </div>
                                                                </div>
                                                            </div>                                            
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Email Address <span style="color:red;font-weight:bold">*</span></label>
                                                                        <input type="email" id="email_id" name="email_id" class="form-control" placeholder="Email Address" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Type <span style="color:red;font-weight:bold">*</span></label>
                                                                        <select class="form-control" id="type" name="type">
                                                                            <option value="Select User Type">Select User Type</option>
                                                                            <option value="USER">Normal User</option>
                                                                            <option value="FADMIN">Facility Admin</option>                                                                                                                                                        
                                                                            <option value="SADMIN">Delivery Person</option>
                                                                            <option value="SADMIN">Super User/Super Admin</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Password <span style="color:red;font-weight:bold">*</span></label>
                                                                        <input type="text" id="password" name="password" class="form-control" placeholder="Password" required>
                                                                    </div>
                                                                </div>
                                                            </div>              
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">                                                                        
                                                                        <input type="checkbox" id="assignFacility" name="assignFacility"> Assign Facility
                                                                    </div>
                                                                </div>                                                                
                                                            </div>    
                                                            <div class="row" id="assignUserFacRow" style='display:none'>
                                                                <div class="col-md-12">
                                                                    <div class="card table-plain-bg">  
                                                                        <div class="card-header ">
                                                                            <h5 class="card-title">Assign Facility</h5>
                                                                        </div>                              
                                                                        <div class="card-body">                                                                                                                                                                                                         
                                                                            <div class="row form-group">                                                                            
                                                                                <div class="col-md-4" id="forward_ret_div_state">
                                                                                    <div class="form-group">
                                                                                        <label for="assignUserFacState" >State: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                                                        <select id="assignUserFacState" name="assignUserFacState" class="form-control">                                                                                            
                                                                                        </select>
                                                                                    </div>
                                                                                </div>                                                                                                                                                               
                                                                                <div class='col-md-4'>
                                                                                    <label for='facility_id'>Facility</label>
                                                                                    <select class='form-control' id='facility_id' name='facility_id' disabled>
                                                                                    </select>
                                                                                </div>                                        
                                                                            </div>
                                                                        </div>  
                                                                    </div>
                                                                </div>
                                                                <!-- Shipment Status Update card -->
                                                            </div>                                          
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <button type="submit" class="form-control btn btn-success">Create</button>
                                                                </div>
                                                            </div>                                    
                                                            <div class="clearfix"></div>   
                                                        </form>                                 
                                                    </div>
                                                </div>   
                                            

                                    <!-- Create User -->                                                                                                                                                     
                                    </div>
                                </div>
                                <!-- Collapse cards -->
                            </div>
                        </div>                                                                                  
                        <!-- Main Menu -->                                                                    
                    
                    </div>   
                                                       
                                                                  
                    </div>
                </div>
            </div>
            <!-- Main Content Close -->
           <!-- Footer 
            <footer class="footer">
                <div class="container-fluid">
                    <nav>
                        <ul class="footer-menu">
                            <li>
                                <a href="#">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Company
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Portfolio
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Blog
                                </a>
                            </li>
                        </ul>
                        <p class="copyright text-center">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            <a href="http://www.creative-tim.com">Creative Tim</a>, made with love for a better web
                        </p>
                    </nav>
                </div>
            </footer>-->

            <!-- Footer -->
        </div>
    </div>
    <!--   -->
    <!-- <div class="fixed-plugin">
    <div class="dropdown show-dropdown">
        <a href="#" data-toggle="dropdown">
            <i class="fa fa-cog fa-2x"> </i>
        </a>

        <ul class="dropdown-menu">
			<li class="header-title"> Sidebar Style</li>
            <li class="adjustments-line">
                <a href="javascript:void(0)" class="switch-trigger">
                    <p>Background Image</p>
                    <label class="switch">
                        <input type="checkbox" data-toggle="switch" checked="" data-on-color="primary" data-off-color="primary"><span class="toggle"></span>
                    </label>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="adjustments-line">
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <p>Filters</p>
                    <div class="pull-right">
                        <span class="badge filter badge-black" data-color="black"></span>
                        <span class="badge filter badge-azure" data-color="azure"></span>
                        <span class="badge filter badge-green" data-color="green"></span>
                        <span class="badge filter badge-orange" data-color="orange"></span>
                        <span class="badge filter badge-red" data-color="red"></span>
                        <span class="badge filter badge-purple active" data-color="purple"></span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="header-title">Sidebar Images</li>

            <li class="active">
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="../assets/img/sidebar-1.jpg" alt="" />
                </a>
            </li>
            <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="../assets/img/sidebar-3.jpg" alt="" />
                </a>
            </li>
            <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="..//assets/img/sidebar-4.jpg" alt="" />
                </a>
            </li>
            <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="../assets/img/sidebar-5.jpg" alt="" />
                </a>
            </li>

            <li class="button-container">
                <div class="">
                    <a href="http://www.creative-tim.com/product/light-bootstrap-dashboard" target="_blank" class="btn btn-info btn-block btn-fill">Download, it's free!</a>
                </div>
            </li>

            <li class="header-title pro-title text-center">Want more components?</li>

            <li class="button-container">
                <div class="">
                    <a href="http://www.creative-tim.com/product/light-bootstrap-dashboard-pro" target="_blank" class="btn btn-warning btn-block btn-fill">Get The PRO Version!</a>
                </div>
            </li>

            <li class="header-title" id="sharrreTitle">Thank you for sharing!</li>

            <li class="button-container">
				<button id="twitter" class="btn btn-social btn-outline btn-twitter btn-round sharrre"><i class="fa fa-twitter"></i> · 256</button>
                <button id="facebook" class="btn btn-social btn-outline btn-facebook btn-round sharrre"><i class="fa fa-facebook-square"></i> · 426</button>
            </li>
        </ul>
    </div>
</div>
 -->
</body>
<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="../assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="../assets/js/plugins/bootstrap-switch.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!--  Chartist Plugin  -->
<script src="../assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="../assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="../assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="../assets/js/demo.js"></script>


</html>
