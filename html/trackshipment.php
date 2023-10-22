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
    <title>Track Shipment</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/css/demo.css" rel="stylesheet" />

    

    <script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            
            $('#trackShipment').submit(function(e) {
                e.preventDefault();                
                var error_res="";
                   
                var shipmentID=document.getElementById("shipmentID").value;
                if(shipmentID.length!=21){
                    error_res=error_res+"<li>Enter Shipment ID Properly</li>";
                }
                                
                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{

                    var send_data= 'shipmentID='+shipmentID;                    
                    $.ajax({
                        type: "POST",
                        url: './queries/trackShipment.php',
                        data: send_data,
                        success: function(response)
                        {
                            var jsonData = response[0];                            
                        }
                    });
                }
            });
        });

    </script>

</head>

<body>

 <!-- Mini Modal -->
 <div class="modal fade  modal-primary" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <div class="wrapper">

       <!-- SideBar -->
       <div class="sidebar" data-image="../assets/img/sidebar-5.jpg">
        <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

    Tip 2: you can also add an image using data-image tag
-->
        <div class="sidebar-wrapper">
            <div class="logo">
                <a href="index.html" class="simple-text">
                    Blue Express
                </a>
            </div>
            <ul class="nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.html">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>Dashboard</p>
                    </a>
                </li>             
            </ul>
        </div>
    </div>
    <!-- SideBar -->        
    <div class="main-panel">
    <?php

        include("./includes/navbar.php");

    ?>

            <!-- Main Content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Track Shipment</h4>
                                    <h6>To track your consignment please enter your Shipment ID provided during booking</h6>
                                </div>
                                <div class="card-body">
                                    <form id="trackShipment" method="post">
                                        <div class="row">                                                                                    
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Shipment ID</label>
                                                    <input type="text" id="shipmentID" name="shipmentID" class="form-control" placeholder="Enter your Shipment ID" required>
                                                </div>
                                            </div>                                            
                                        </div>                                        
                                        <button type="submit" class="btn btn-info btn-fill pull-right">Track</button>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>    
                        

                        <!-- Shipment Details card -->
                        <div class="col-md-12">
                            <div class="card table-plain-bg">                                
                                <div class="card-body">                                    
                                    <div class="row">
                                        <div class="col-md-12">                                            
                                            <h4 class="card-title">Shipment Details</h4>
                                        </div>
                                        <div class="col-md-3">
                                            Shipment ID: shipmentID
                                        </div>
                                        <div class="col-md-3">
                                            Status: status
                                        </div>
                                        <div class="col-md-3">
                                            Weight: weight
                                        </div>
                                        <div class="col-md-3">
                                            Content Type: content type
                                        </div>                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr/><h4 class="card-title">Sender Details</h4>
                                        </div>
                                        <div class="col-md-4">
                                            Full Name: Nihal Mukesh Bhovare
                                        </div>
                                        <div class="col-md-4">
                                            Address: Address
                                        </div>
                                        <div class="col-md-4">
                                            Mobile Number: Number
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                    
                                            <hr/><h4 class="card-title">Receiver Details</h4>
                                        </div>
                                        <div class="col-md-4">
                                            Full Name: Nihal Mukesh Bhovare
                                        </div>
                                        <div class="col-md-4">
                                            Address: Address
                                        </div>
                                        <div class="col-md-4">
                                            Mobile Number: Number
                                        </div>
                                    </div>                                    
                                </div>  
                            </div>
                        </div>
                        <!-- Shipment Details card Close -->      

                        <!-- Shipment Events Details card -->
                        <div class="col-md-12">
                            <div class="card table-plain-bg">
                                <div class="card-header ">
                                    <h4 class="card-title">Shipment Details</h4>
                                    <p class="card-category">Click on any entry to get more Details</p>
                                </div>
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>Date</th>
                                            <th>Activity</th>
                                            <th>Location</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Dakota Rice</td>
                                                <td>$36,738</td>
                                                <td>Niger</td>
                                                <td>Oud-Turnhout</td>
                                                <td>Sinaai-Waas</td>
                                            </tr>                                            
                                        </tbody>
                                    </table>
                                </div>  
                            </div>
                        </div>
                        <!-- Shipment Events Details card Close -->                      
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
<script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
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
