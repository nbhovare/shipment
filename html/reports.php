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
    <title>Reports</title>
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

            $("#clearBtn").click(function(){
                $("#trackShipment").trigger("reset");
                $("#shipment_details_col").hide();
                $("#shipment_details_row").empty();
                $("#sender_details_row").empty();
                $("#receiver_details_row").empty();

                $("#shipment_event_card").hide();
                $("#shipment_event_table").empty();

                $("#clearBtn").prop("disabled", true);
                $("#modifyBtn").prop("disabled", true);
                $("#deleteBtn").prop("disabled", true);

            })

            $("#shipment_details_col").hide();
            $("#shipment_event_card").hide();            
            $('#trackShipment').submit(function(e) {
                $("#shipment_details_col").hide();
                $("#shipment_event_card").hide();
                e.preventDefault();                
                var error_res="";
                   
                var shipmentID=document.getElementById("shipmentID").value;
                if(shipmentID.length!=12){
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
                            if(response.error_msg){
                                alert(response.error_msg.error_msg);
                            }
                            else{

                                $("#clearBtn").prop("disabled", false);
                                $("#modifyBtn").prop("disabled", false);
                                $("#deleteBtn").prop("disabled", false);                                
                                
                                $("#shipment_details_col").show();
                                $("#shipment_details_row").empty();
                                $("#sender_details_row").empty();
                                $("#receiver_details_row").empty();
                                // Loop through shipment_data array
                                $.each(response.shipment_data, function(index, shipment) {                                                                    
                                    
                                    $("#shipment_details_row").append("\
                                        <div class='col-md-12'>\
                                            <h4 class='card-title'>Shipment Details</h4>\
                                        </div>\
                                        <div class='col-md-4'>\
                                            Shipment ID: "+shipment.shipment_id+"\
                                        </div>\
                                        <div class='col-md-3'>\
                                            Status: "+shipment.shipment_status+"\
                                        </div>\
                                        <div class='col-md-2'>\
                                            Weight: "+shipment.shipment_weight+"\
                                        </div>\
                                        <div class='col-md-3'>\
                                            Content Type: "+shipment.content_type+"\
                                        </div>");
                                        // Appeding Data
                                        
                                    $("#sender_details_row").append("\
                                        <div class='col-md-12'>\
                                            <hr/><h4 class='card-title'>Sender Details</h4>\
                                        </div>\
                                        <div class='col-md-4'>\
                                            Full Name: "+shipment.sender_name+"\
                                        </div>\
                                        <div class='col-md-4'>\
                                            Mobile Number: <a href=tel:'"+shipment.sender_phone+"'>"+shipment.sender_phone+"</a>\
                                        </div>\
                                        <div class='col-md-4'>\
                                            Address: "+shipment.sender_city+",   "+ shipment.sender_state + ", " + shipment.sender_country + ", " + shipment.sender_pincode +"\
                                        </div>");
                                    // Appeding Data

                                    $("#receiver_details_row").append("\
                                        <div class='col-md-12'>\
                                            <hr/><h4 class='card-title'>Receiver Details</h4>\
                                        </div>\
                                        <div class='col-md-4'>\
                                            Full Name: "+shipment.receiver_name+"\
                                        </div>\
                                        <div class='col-md-4'>\
                                            Mobile Number: <a href=tel:'"+shipment.receiver_phone+"'>"+shipment.receiver_phone+"</a>\
                                        </div>\
                                        <div class='col-md-4'>\
                                            Address: "+shipment.receiver_city+", "+ shipment.receiver_state + ", " + shipment.receiver_country + ", " + shipment.receiver_pincode +"\
                                        </div>");
                                    // Appeding Data
                                    
                                });

                                //Extracting Events Data
                                $("#shipment_event_table").empty();
                                $("#shipment_event_card").show();
                                $("#shipment_event_table").show();
                                $.each(response.events_data, function(index, event) {
                                    
                                        $("#shipment_event_table").append("abc\
                                        <tr>\
                                            <td>"+event.event_date+"</td>\
                                            <td>"+event.event_remarks+"</td>\
                                            <td>"+event.event_location+"</td>\
                                            <td>Niger</td>\
                                            <td>Oud-indexsut</td>\
                                            <td>"+event.shipment_status+"</td>\
                                        </tr>");
                                });
                                    
                            }
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


        <?php
            include("./includes/sidebar.php");
        ?>
        

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
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <button type="submit" class="btn btn-info btn-fill" style="margin:1px">Track</button>
                                                <button type="button" class="btn btn-info btn-fill" id="clearBtn" style="margin:1px"disabled>Clear</button>                                                
                                                <button class="btn btn-fill" id="modifyBtn" style="margin:1px" disabled>Modify</button>
                                                <button class="btn btn-warning btn-fill" id="deleteBtn" style="margin:1px" disabled>Delete</button>
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>    
                        

                        <!-- Shipment Details card -->                    
                        <div class="col-md-12" id="shipment_details_col">
                            <div class="card table-plain-bg">                                
                                <div class="card-body">                                    
                                    <div class="row" id="shipment_details_row">                                                                    
                                    </div>
                                    <div class="row" id="sender_details_row">                                    
                                    </div>
                                    <div class="row" id="receiver_details_row">                                        
                                    </div>                                    
                                </div>  
                            </div>
                        </div>
                        <!-- Shipment Details card Close -->      

                        <!-- Shipment Events Details card -->
                        <div class="col-md-12" id="shipment_event_card">
                            <div class="card table-plain-bg">
                                <div class="card-header ">
                                    <h4 class="card-title">Shipment Details</h4>
                                    <p class="card-category">Click on any entry to get more Details</p>
                                </div>
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover">
                                    <thead>
                                        <th>Date (Year/Month/Date)</th>
                                        <th>Activity</th>
                                        <th>Location</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody id="shipment_event_table">
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
            <!-- Footer -->        
            <!-- Footer -->
        </div>
    </div>
    
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
