<?php

    session_start();
    if(!isset($_SESSION['isSession'])){
        header("location:./login.php");
    }        


    require("./includes/db_connect.php");
    require("./includes/check_permission.php");

    $resForPagePer=checkPermission($_SESSION['user_id'],"trackshipment_php",$connection);
    if($resForPagePer==="0"){
        echo "You do not have permission to this page, Please contact your administrator for any query<br/>";       
        echo "<a href='./index.php'>Click here to goto home page</a>";
    }
    else{

        $retPerData=getUserPermissionForPage($_SESSION['user_id'],"trackshipment_php",$connection);

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
    <title>Track Shipment</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/light-bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />    
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/css/demo.css" rel="stylesheet" />

    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" />    

    <script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>



    <!-- Adding CSS for shipment progress bar -->
    <style>


        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');
/*body{background-color: #eeeeee;font-family: 'Open Sans',serif}*/
body{background-color: #eeeeee;font-family: 'Open Sans',serif; }
.container{margin-top:50px;margin-bottom: 50px}
.card{position: relative;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-orient: vertical;-webkit-box-direction: normal;-ms-flex-direction: column;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0, 0, 0, 0.1);border-radius: 0.10rem}.card-header:first-child{border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0}.card-header{padding: 0.75rem 1.25rem;margin-bottom: 0;background-color: #fff;border-bottom: 1px solid rgba(0, 0, 0, 0.1)}.track{position: relative;background-color: #ddd;height: 7px;display: -webkit-box;display: -ms-flexbox;display: flex;margin-bottom: 60px;margin-top: 50px}.track .step{-webkit-box-flex: 1;-ms-flex-positive: 1;flex-grow: 1;width: 25%;margin-top: -18px;text-align: center;position: relative}.track .step.active:before{background: #FF5722}.track .step::before{height: 7px;position: absolute;content: "";width: 100%;left: 0;top: 18px}.track .step.active .icon{background: #ee5435;color: #fff}.track .icon{display: inline-block;width: 40px;height: 40px;line-height: 40px;position: relative;border-radius: 100%;background: #ddd}.track .step.active .text{font-weight: 400;color: #000}.track .text{display: block;margin-top: 7px}.itemside{position: relative;display: -webkit-box;display: -ms-flexbox;display: flex;width: 100%}.itemside .aside{position: relative;-ms-flex-negative: 0;flex-shrink: 0}.img-sm{width: 80px;height: 80px;padding: 7px}ul.row, ul.row-sm{list-style: none;padding: 0}.itemside .info{padding-left: 15px;padding-right: 7px}.itemside .title{display: block;margin-bottom: 5px;color: #212529}p{margin-top: 0;margin-bottom: 1rem}.btn-warning{color: #ffffff;background-color: #ee5435;border-color: #ee5435;border-radius: 1px}.btn-warning:hover{color: #ffffff;background-color: #ff2b00;border-color: #ff2b00;border-radius: 1px}
    </style>    

    <script type="text/javascript">

        window.onload = function() {    
            const params = new URLSearchParams(window.location.search);            
            const param_id = params.get('shipment_id'); 
            $('#shipmentID').val(param_id);                        
        };

        function checkUpdateStat(){
                        
            var actv=$("#activity").val();            

            $("#remarks").val(getStandardStatus(actv));
            if(actv==="FORWARD" || actv==="RETURN"){
                $('#facility_id_label').empty();                
                $('#facility_id').show();
                $('#facility_id_label').show();               
                $('#facility_id_label').append("Select Facility To "+ actv +" Shipment\
                    <span style='color:red;font-weight:bold'>*</span>\
                ");                
                
                $('#forward_ret_div_country').show();
                $('#forward_ret_div_state').show();

                var state=$("#forward_ret_state").val();
                if(state==="Select State"){                    
                    $("#facility_id").empty();
                    $('#facility_id').prop('disabled', true);
                    alert("Select State Properly ");
                }
                else{
                
                    // List Facilities in Select            

                    $.ajax({
                            type: "POST",
                            url: './queries/getFacilityList.php',
                            data:  {facilityState:state},
                            success: function(response)
                            {
                                if(response.error_msg){
                                    $("#facility_id").empty();
                                    $('#facility_id').prop('disabled', true);
                                    alert(response.error_msg.error_msg);                                    
                                }
                                else{    
                                    $('#facility_id').prop('disabled', false);                        
                                    $("#facility_id").empty();
                                    $("#facility_id").append("\
                                        <option value='Select Facility'>Select Facility</option>");

                                    $.each(response.facility_data, function(index, getData) {
                                        
                                        $("#facility_id").append("\
                                            <option value="+getData.facility_id+">"+getData.facility_name+"</option>");
                                        });
                                        
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log("Ajax request failed with status: " + status + " and error: " + error);
                                // You can provide a more user-friendly error message or handle errors as needed.
                            }   
                        });
                }
            }    
            else{
                $('#facility_id_label').empty();                                
                $('#facility_id_label').hide();                               
                $('#forward_ret_div_country').hide();
                $('#forward_ret_div_state').hide();
                $("#facility_id").empty();
                $("#facility_id").hide();                
            }    
        }

        function getStandardStatus(getStatusOf){
            var reason="";
            switch(getStatusOf){
                case "ARRIVED":
                    reason="Arrived at Facility";
                break;
                
                case "FORWARD":
                    reason="Forwarding to another facility for further transit";
                break;

                case "RETURN":
                    reason="Returning to another facility for further transit";
                break;

                case "OUT_FOR_DELIVERY":
                    reason="Out for Delivery";
                break;

                case "DELIVERED":
                    reason="Delivered to Customer";
                break;

                case "ONHOLD":
                    reason="Placing ONHOLD basis: 'Mention your Business requirement/ Reason for Placing ONHOLD'";
                break;

                case "RELEASE_ON_HOLD":
                    reason="Releasing ONHOLD basis: 'Mention your Business requirement/ Reason'";
                break;

                case "CANCEL":
                    reason="Cancelling shipment basis: 'Mention your Business requirement/ Reason for cancellation'";
                break;

                default:
                    reason="";
                break;
                
            }
            return reason;
        }

        function getCurrentTime(){
            // Create a new Date object for the current time
            const now = new Date();

            // Define the time zone (Asia/Kolkata for India)
            const timeZone = "Asia/Kolkata";

            // Format the current time with the specified time zone
            const options = { timeZone, hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const formatter = new Intl.DateTimeFormat('en-IN', options);
            const timeString = formatter.format(now);

            return timeString;
        }

        function QueueProcessed(type){
            
            // Extract And Append Data
            
            $("#queue_process_form_title").empty();
            $("#queue_process_form_table").empty();
            if(type==="INCOMING")
                $("#queue_process_form_title").append("Shipments - Incoming");
            else if(type==="INQUEUE")
                $("#queue_process_form_title").append("Shipments - In Queue");
            else if(type==="PROCESSED")
                $("#queue_process_form_title").append("Shipments - Processed");


            // Get Data
            $.ajax({
                    type: "POST",
                    url: './queries/queue_processed.php',
                    data:  {typeofReq:type},
                    success: function(response)
                    {                        
                        if(response.error_msg){                            
                            alert(response[0].error_msg);
                        }                        
                        else{
                            
                            $("#clearBtn").prop("disabled", false);                                                     
                           
                            $.each(response.shipment_data, function(index, event) {
                                
                                $("#queue_process_form_table").append("\
                                <tr>\
                                    <td><a href='./facility.php?fac_id="+event.shipment_id+"' target=_blank>"+event.shipment_id+"</a></td>\
                                    <td>"+event.date+"</a></td>\
                                    <td>"+event.remarks+"</td>\
                                    <td>"+event.activity+"</td>\
                                </tr>");
                            });
                                
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Ajax request failed with status: " + status + " and error: " + error);
                        // You can provide a more user-friendly error message or handle errors as needed.
                    }   
                });
            $("#queue_process_form").show();
                        
        }
        
        $(document).ready(function() {    

            $("#shipment_details_col").hide();
            $("#shipment_event_card").hide();
            $("#shipment_status_update_col").hide();    
            $("#queue_process_form").hide();
            
            <?php 

                if($retPerData!="-1" && $retPerData!="0"){
                    if(in_array("trackshipment_php_UPDATE_SHIP_STATUS",$retPerData)){                    

            ?>
            $("#updateStatusBtn").click(function(){                
                $("#shipment_status_update_col").show();                                                                
            });
          
            $("#updateshipmentStatusForm").submit(function(e){
                e.preventDefault();
                $("#modal_message").empty();
                var actv=$("#activity").val();
                var error_res="";

                if(actv==="Update Shipment Status"){
                    error_res="<li>Select Shipment Status Properly</li>";
                }
                if(actv==="FORWARD" || actv==="RETURN"){
                    // check if state and country and facility is selected
                    var state=$("#forward_ret_state").val();  
                    var country=$("#forward_ret_state").val();                
                    var facility=$("#facility_id").val();
                    
                    if(state=="Select State")
                        error_res="<li>Select State Properly</li>";

                    if(country=="Select Country")
                        error_res="<li>Select Country Properly</li>";

                    if(facility==="Select Facility" || facility===null)
                        error_res="<li>Select Facility Properly</li>";
                }
                
                if(error_res.length!=0){
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                
                    var formData = $('#updateshipmentStatusForm').serializeArray();
                    // Serialize the form data using jQuery                    

                    // Convert the serialized form data to a JSON object
                    var formDataObject = {};
                    formDataObject["shipment_id"]=$("#shipmentID").val();
                    //formDataObject["time"]=$("#date").val()+" "+getCurrentTime();
                    $.each(formData, function(index, field) {

                        if(field.name==="facility_id")
                            formDataObject[field.name] = field.value;                        
                        else
                            formDataObject[field.name] = field.value;
                    });

                    // Convert the JSON object to a JSON string
                    var formDataJSON = JSON.stringify(formDataObject);
                                                        
                    $.ajax({
                        type: "POST",
                        url: './queries/updateShipmentEvent.php',
                        data:  {data: formDataJSON},
                        success: function(response)
                        {                                                        
                            var responseData = JSON.parse(response);
                            if(responseData[0].error_msg){
                                alert(responseData[0].error_msg);
                            }
                            else{
                                alert(responseData[0].ret_msg);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }   
                    });
                }
            });

            <?php 
}

if(in_array("trackshipment_php_MODIFY_SHIP_DETAILS",$retPerData)){

            ?>


            $("#modifyBtn").click(function(){
                var shipmentID=$("#shipmentID").val();
                window.location.href="./update.php?shipID="+shipmentID;
            })

            <?php }} ?>

            $("#clearBtn").click(function(){
                $("#trackShipment").trigger("reset");
                $("#shipment_details_col").hide();
                $("#shipment_details_row").empty();
                $("#sender_details_row").empty();
                $("#receiver_details_row").empty();

                $("#shipment_event_card").hide();
                $("#shipment_event_table").empty();

                $("#shipment_status_update_row").empty();
                $("#shipment_status_update_col").hide();

                $("#clearBtn").prop("disabled", true);
                $("#modifyBtn").prop("disabled", true);
                $("#deleteBtn").prop("disabled", true);
                $("#updateStatusBtn").prop("disabled", true);
                $("#activity").prop("disabled", true);
                $("#updateShipmentStatusBtn").prop("disabled", true);
                $("#remarks").prop("disabled", true);    
                
                // Queue_processes_form
                
                $("#queue_process_form").hide();

            })
                                    
            $('#trackShipment').submit(function(e) {                
                e.preventDefault();                
                $("#modal_message").empty();
                $("#shipment_details_col").hide();
                $("#shipment_event_card").hide();
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
                                $("#updateStatusBtn").prop("disabled", false);
                                $("#activity").prop("disabled", false);                                
                                $("#updateShipmentStatusBtn").prop("disabled", false);
                                $("#remarks").prop("disabled", false);                                
                                
                                $("#shipment_details_col").show();
                                $("#shipment_details_row").empty();
                                $("#sender_details_row").empty();
                                $("#receiver_details_row").empty();
                                // Loop through shipment_data array
                                $.each(response.shipment_data, function(index, shipment) {                                                                    
                                    shipStat="";                                    
                                    if(
                                                shipment.shipment_status==="ARRIVED" || 
                                                shipment.shipment_status==="RELEASE_ON_HOLD" ||
                                                shipment.shipment_status==="FORWARD" ||
                                                shipment.shipment_status==="RETURN"){
                                                    shipStat='Shipment In Transit';
                                            }
                                            else if(shipment.shipment_status==="CREATED"){
                                                shipStat="BOOKED";
                                            }
                                            else if(shipment.shipment_status==="OUT_FOR_DELIVERY"){
                                                shipStat="Out For Delivery";
                                            }
                                            else{
                                                shipStat=shipment.shipment_status        
                                            }                                                                    

                                const colForProgress=$("<div>").addClass("col-md-12");                                                               
                                const cardForPro=$("<article>").addClass("card");
                                const cardHeaderForPro=$("<header>").addClass("card-header").append("Shipment Progress");
                                const cardBodyForPro=$("<div>").addClass("card-body");
                                const trackForPro=$("<div>").addClass("track");

                                var shipmentStatusArr=["Booked by Facility","On the way","Out for delivery","Delivered/Cancel"];                                
                                var arrIcon=["fa-check","fa-user","fa-truck","fa-box"];                                
                                var active="";
                                if(shipment.shipment_status==="CREATED"){
                                    active=0;
                                }
                                if(shipment.shipment_status==="ARRIVED" || shipment.shipment_status==="FORWARD" || 
                                shipment.shipment_status==="RETURN" || shipment.shipment_status==="ONHOLD"
                                 || shipment.shipment_status==="RELEASE_ON_HOLD"){
                                    active=1;
                                 }
                                 if(shipment.shipment_status==="OUT_FOR_DELIVERY"){
                                    active=2;
                                 }
                                if(shipment.shipment_status==="CANCEL"){
                                    active=3;
                                    shipmentStatusArr[3]="Cancelled";
                                }
                                if(shipment.shipment_status==="DELIVERED"){
                                    active=3;
                                    shipmentStatusArr[3]="Delivered";
                                }

                                // Ship Status
                                // Booked by Facility = Created
                                // On the way = Forward, Arrived, Return
                                // Out for delivery = Out for delivery
                                // Cancel/ Delivered = Cancel, Delivered

                                const activeComp="<div class='step active'> <span class='icon'> <i class='fa fa-check'></i> </span> <span class='text'>Order confirmed</span> </div>";                                


                                for(let i=0;i<4;i++){                                                                    
                                    const spanIcon=$("<span>").addClass("icon");
                                    const spanText=$("<span>").addClass("text");
                                    const icon=$("<i>").addClass("fa");
                                    const stepDiv=$("<div>").addClass("step");
                                    if(i<=active){                            
                                        stepDiv.addClass("active");                                     

                                    }                                    
                                    icon.addClass(arrIcon[i]);
                                        spanText.append(shipmentStatusArr[i]);
                                        spanIcon.append(icon);
                                        stepDiv.append(spanIcon).append(spanText); 
                                        trackForPro.append(stepDiv);
                                }                                
                                                                
                                cardBodyForPro.append(trackForPro); 
                                cardForPro.append(cardHeaderForPro).append(cardBodyForPro);                            
                                colForProgress.append(cardForPro);

                                $("#shipment_details_row").append(colForProgress);

                                const mainColForShipDTable=$("<div>").addClass("row");
                                let shipFieldsList=[
                                    ["shipment_id","shipment_status","shipment_type","shipment_weight","shipment_delivery_method","shipment_cost","payment_type","additional_information"],
                                    ["sender_name","sender_phone","sender_address","sender_city","sender_state"],
                                    ["receiver_name","receiver_phone","receiver_address","receiver_city","receiver_state"]
                                ];
                                let shipDetailsHeading=["Shipment Details","Sender Details","Receiver Details"];
                                             
                                for(let shipInc=0;shipInc<3;shipInc++){
                                            const colForShipDTable=$("<div>").addClass("col-md-4");
                                            const shipDetailsTable=$("<table>");
                                            const shipDetailsTableBody=$("<tbody>");
                                    for(let shipD in shipment){
                                        if(shipFieldsList[shipInc].includes(shipD)){
                                            const tableRow=$("<tr>");
                                            const tableRowBdy=$("<td>").append(shipD);
                                            const tableRowBdy1=$("<td>").append(shipment[shipD]);
                                            tableRow.append(tableRowBdy).append(tableRowBdy1);
                                            shipDetailsTableBody.append(tableRow);                                                                        
                                        }
                                    }
                                    shipDetailsTable.append(shipDetailsTableBody);
                                    colForShipDTable.append("<h4 class='card-title'><U>"+shipDetailsHeading[shipInc]+"</U></h4>");
                                    colForShipDTable.append(shipDetailsTable);                                    
                                    mainColForShipDTable.append(colForShipDTable);
                                }                                
                                $("#shipment_details_row").append(mainColForShipDTable);
                                /*                                    
                                    $("#shipment_details_row").append("\
                                        <div class='col-md-4'>\
                                            <h4 class='card-title'><U>Sender Details</U></h4><br/>\
                                            Full Name: "+shipment.sender_name+"<br/>\
                                            Mobile Number: <a href=tel:'"+shipment.sender_phone+"'>"+shipment.sender_phone+"</a><br/>\
                                            Address: "+shipment.sender_city+",   "+ shipment.sender_state + ", " + shipment.sender_country + ", " + shipment.sender_pincode +"<br/>\
                                        </div>");
                                    // Appeding Data

                                    $("#shipment_details_row").append("\
                                        <div class='col-md-4'>\
                                            <h4 class='card-title'><U>Receiver Details</U></h4><br/>\
                                            Full Name: "+shipment.receiver_name+"<br/>\
                                            Mobile Number: <a href=tel:'"+shipment.receiver_phone+"'>"+shipment.receiver_phone+"</a><br/>\
                                            Address: "+shipment.receiver_city+", "+ shipment.receiver_state + ", " + shipment.receiver_country + ", " + shipment.receiver_pincode +"<br/>\
                                        </div>");
                                    // Appeding Data*/
                                    
                                });

                                //Extracting Events Data
                                $("#shipment_event_table").empty();
                                $("#shipment_event_card").show();
                                $("#shipment_event_table").show();
                                
                                var eventField=["facility_id","facility_name","event_date","events"];
                                $.each(response.events_data, function(index, event) {
                                    
                                    nextFac="";
                                    if(event.forward_to===null && event.return_to===null){
                                        nextFac="-";
                                    }
                                    if(event.events_activity==="FORWARD"){
                                        nextFac=event.forward_to;
                                    }
                                    if(event.events_activity==="RETURN"){
                                        nextFac=event.return_to;
                                    }
                                    

                                        /*const eventTableRow=$("<tr>");
                                        for(let eventData in event){                                            
                                            const eventTableRowBdy=$("<td>");
                                            if(eventData==="facility_id"){
                                                const anchorForEvent_FacID=$("<a>").attr({
                                                    href:"./facility.php?fac_id="+event[eventData],
                                                    target:"_blank"                                                    
                                                }).text(event["facility_name"]);
                                                eventTableRowBdy.append(anchorForEvent_FacID);                                            
                                            }
                                            else{
                                                eventTableRowBdy.append(event[eventData]);
                                            }                                      
                                            
                                            
                                            eventTableRow.append(eventTableRowBdy);
                                        }*/
                                        
                                        //$("#shipment_event_table").append(eventTableRow);
                                        $("#shipment_event_table").append("\
                                        <tr>\
                                            <td><a href='./facility.php?fac_id="+event.facility_id+"' target=_blank>\
                                            "+event.facility_name+"</a></td>\
                                            <td>"+event.event_date+"</td>\
                                            <td>"+event.events_activity+"</td>\
                                            <td>"+event.facility_name+"</td>\
                                            <td>"+nextFac+"</td>\
                                            <td>"+event.event_remarks+"</td>\
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
                <button type="button" class="btn btn-link btn-fill" data-dismiss="modal">Close</button>                
            </div>
        </div>
    </div>
</div>
<!--  End Modal -->

<?php 

        include("./includes/loaders.php");

?>
    <div class="wrapper">


  
    
        <?php

            include("./includes/navbar.php");

        ?>

            <!-- Main Content -->
            <div class="content" style='margin-top:10px'>
                <div class="container-fluid">
                    <div class="row">

                    <?php

                            include("./includes/quick_links.php");

                      ?>

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
                                                    <button type="submit" id="searchShipBtn" class="btn btn-info btn-fill" style="margin:1px">Track</button>
                                                    <button type="button" class="btn btn-info btn-fill" id="clearBtn" style="margin:1px"disabled>Clear</button>      
                                                    
                                                    <?php 
                                                        // checking if user has access to modifu track shipmentdetails if yes then show button

                                                        if($retPerData!="-1" && $retPerData!="0"){                                                            
                                                            if(in_array("trackshipment_php_MODIFY_SHIP_DETAILS",$retPerData)){                                                                
                                                                //echo "<button type='button' class='btn btn-fill' id='modifyBtn' style='margin:1px' disabled>Modify</button>";
                                                            ?>
                                                    
                                                    <button type="button" class="btn btn-fill" id="modifyBtn" style="margin:1px" disabled>Modify</button>

                                                    <?php }
                                                    
                                                    if(in_array("trackshipment_php_UPDATE_SHIP_STATUS",$retPerData)){
                                                        ?>
<button type="button" class="btn btn-fill" id="updateStatusBtn" style="margin:1px" disabled>Update Status</button>
                                                    <?php }

                                                        }
                                                    ?>                                                    
                                                    <button type="button" class="btn btn-fill" id="shipmentsIncoming" onclick="QueueProcessed('INCOMING')">Shipments - Incoming</button>
                                                    <button type="button" class="btn btn-fill" id="shipmentsInQueue" onclick="QueueProcessed('INQUEUE')">Shipments - In Queue</button>
                                                    <button type="button" class="btn btn-fill" id="shipmentsProcessed" onclick="QueueProcessed('PROCESSED')">Shipments - Processed</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>                                        
                                        <div class="clearfix"></div>                                    
                                </div>
                            </div>
                        </div>    

                        <!-- Tracking Form -->
                        <div class="col-md-12" id="queue_process_form">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="queue_process_form_title"></h4>
                                    <h6>Click on shipment ID to track/View more Details</h6>
                                </div>
                                <div class="card-body table-full-width table-responsive">                                    
                                    <table class="table table-hover">
                                        <thead>
                                            <th>Shipment ID</th>
                                            <th>Date (Year/Month/Date)</th>
                                            <th>Remarks</th>                                      
                                            <th>Activity</th>                                                                                    
                                        </thead>
                                        <tbody id="queue_process_form_table">
                                        </tbody>
                                    </table>                                    
                                </div>
                            </div>
                        </div>    
                        <!-- Tracking Form -->
                        


                        <?php 
                            // checking if user has access to Update Shipemnt if yes then show below code

                            if($retPerData!="-1" && $retPerData!="0"){                                                            
                                if(in_array("trackshipment_php_UPDATE_SHIP_STATUS",$retPerData)){                                                                                                    
                                ?>

                         <!-- Shipment Status Update card -->
                         <div class="col-md-12" id="shipment_status_update_col">
                            <div class="card table-plain-bg">  
                                <div class="card-header ">
                                    <h4 class="card-title">Update Status</h4>
                                    <!--<p class="card-category">Click on any entry to get more Details</p>-->
                                </div>                              
                                <div class="card-body">
                                <form id="updateshipmentStatusForm" method=POST>                                    
                                    <div class="row form-group">
                                        <div class='col-md-4'>
                                            <label for='date'>Update Status : <span style="color:red;font-weight:bold">*</span></label>
                                            <select name='activity' id='activity' class='form-control' onchange="checkUpdateStat()">
                                                <option value='Update Shipment Status'>Update Shipment Status</option>
                                                <?php 
                                                    if($_SESSION['type']==="SADMIN" || $_SESSION['type']==="FADMIN"){

                                                
                                                ?>
                                                <option value='ARRIVED'>Mark Arrived</option>                                                
                                                <option value='FORWARD'>Forward</option>
                                                <option value='ONHOLD'>Place ON-HOLD</option>
                                                <option value='RELEASE_ON_HOLD'>Release ON-HOLD</option>
                                                <option value='RETURN'>Return</option>                                                
                                                <option value='OUT_FOR_DELIVERY'>Out For Delivery</option>
                                                <option value='CANCEL'>Cancel Shipment</option>

                                                <?php 
                                                    }
                                                ?>
                                                <option value='DELIVERED'>Delivered</option>
                                            </select>
                                        </div>
                                        <div class='col-md-4'>
                                            <label for='date'>Enter Date: <span style="color:red;font-weight:bold">*</span></label>
                                            <input type='date' class='form-control' id='date' name='date' required>                                            
                                        </div>
                                        <div class='col-md-4'>
                                            <label for='date'>Enter Reason/Remarks: <span style="color:red;font-weight:bold">*</span></label>
                                            <input type="text" class='form-control' id='remarks' name='remarks' placeholder='Enter Reason/Remarks'>
                                        </div>
                                    </div>                      
                                    <div class="row form-group">                                                                            
                                        <div class="col-md-4" style='display:none' id="forward_ret_div_state">
                                            <div class="form-group">
                                                <label for="forward_ret_state" >State: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                <select id="forward_ret_state" name="forward_ret_state" class="form-control" onchange="checkUpdateStat()">
                                                    <option value="Select State">Select State</option>
                                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                    <option value="Assam">Assam</option>
                                                    <option value="Bihar">Bihar</option>
                                                    <option value="Chandigarh">Chandigarh</option>
                                                    <option value="Chhattisgarh">Chhattisgarh</option>
                                                    <option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
                                                    <option value="Daman and Diu">Daman and Diu</option>
                                                    <option value="Delhi">Delhi</option>
                                                    <option value="Lakshadweep">Lakshadweep</option>
                                                    <option value="Puducherry">Puducherry</option>
                                                    <option value="Goa">Goa</option>
                                                    <option value="Gujarat">Gujarat</option>
                                                    <option value="Haryana">Haryana</option>
                                                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                    <option value="Jharkhand">Jharkhand</option>
                                                    <option value="Karnataka">Karnataka</option>
                                                    <option value="Kerala">Kerala</option>
                                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                    <option value="Maharashtra">Maharashtra</option>
                                                    <option value="Manipur">Manipur</option>
                                                    <option value="Meghalaya">Meghalaya</option>
                                                    <option value="Mizoram">Mizoram</option>
                                                    <option value="Nagaland">Nagaland</option>
                                                    <option value="Odisha">Odisha</option>
                                                    <option value="Punjab">Punjab</option>
                                                    <option value="Rajasthan">Rajasthan</option>
                                                    <option value="Sikkim">Sikkim</option>
                                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                                    <option value="Telangana">Telangana</option>
                                                    <option value="Tripura">Tripura</option>
                                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                    <option value="Uttarakhand">Uttarakhand</option>
                                                    <option value="West Bengal">West Bengal</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4" style='display:none' id="forward_ret_div_country">
                                            <div class="form-group">
                                                <label for="forward_ret_country">Receiver Country: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                <select id="forward_ret_country" name="forward_ret_country" class="form-control" disabled required>
                                                    <option value="India">India</option>
                                                    <option value="Afghanistan">Afghanistan</option>
                                                    <option value="Åland Islands">Åland Islands</option>
                                                    <option value="Albania">Albania</option>
                                                    <option value="Algeria">Algeria</option>
                                                    <option value="American Samoa">American Samoa</option>
                                                    <option value="Andorra">Andorra</option>
                                                    <option value="Angola">Angola</option>
                                                    <option value="Anguilla">Anguilla</option>
                                                    <option value="Antarctica">Antarctica</option>
                                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                    <option value="Argentina">Argentina</option>
                                                    <option value="Armenia">Armenia</option>
                                                    <option value="Aruba">Aruba</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="Austria">Austria</option>
                                                    <option value="Azerbaijan">Azerbaijan</option>
                                                    <option value="Bahamas">Bahamas</option>
                                                    <option value="Bahrain">Bahrain</option>
                                                    <option value="Bangladesh">Bangladesh</option>
                                                    <option value="Barbados">Barbados</option>
                                                    <option value="Belarus">Belarus</option>
                                                    <option value="Belgium">Belgium</option>
                                                    <option value="Belize">Belize</option>
                                                    <option value="Benin">Benin</option>
                                                    <option value="Bermuda">Bermuda</option>
                                                    <option value="Bhutan">Bhutan</option>
                                                    <option value="Bolivia">Bolivia</option>
                                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                    <option value="Botswana">Botswana</option>
                                                    <option value="Bouvet Island">Bouvet Island</option>
                                                    <option value="Brazil">Brazil</option>
                                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                    <option value="Bulgaria">Bulgaria</option>
                                                    <option value="Burkina Faso">Burkina Faso</option>
                                                    <option value="Burundi">Burundi</option>
                                                    <option value="Cambodia">Cambodia</option>
                                                    <option value="Cameroon">Cameroon</option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="Cape Verde">Cape Verde</option>
                                                    <option value="Cayman Islands">Cayman Islands</option>
                                                    <option value="Central African Republic">Central African Republic</option>
                                                    <option value="Chad">Chad</option>
                                                    <option value="Chile">Chile</option>
                                                    <option value="China">China</option>
                                                    <option value="Christmas Island">Christmas Island</option>
                                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                                    <option value="Colombia">Colombia</option>
                                                    <option value="Comoros">Comoros</option>
                                                    <option value="Congo">Congo</option>
                                                    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                                    <option value="Cook Islands">Cook Islands</option>
                                                    <option value="Costa Rica">Costa Rica</option>
                                                    <option value="Cote D'ivoire">Cote D'ivoire</option>
                                                    <option value="Croatia">Croatia</option>
                                                    <option value="Cuba">Cuba</option>
                                                    <option value="Cyprus">Cyprus</option>
                                                    <option value="Czech Republic">Czech Republic</option>
                                                    <option value="Denmark">Denmark</option>
                                                    <option value="Djibouti">Djibouti</option>
                                                    <option value="Dominica">Dominica</option>
                                                    <option value="Dominican Republic">Dominican Republic</option>
                                                    <option value="Ecuador">Ecuador</option>
                                                    <option value="Egypt">Egypt</option>
                                                    <option value="El Salvador">El Salvador</option>
                                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                    <option value="Eritrea">Eritrea</option>
                                                    <option value="Estonia">Estonia</option>
                                                    <option value="Ethiopia">Ethiopia</option>
                                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                                    <option value="Faroe Islands">Faroe Islands</option>
                                                    <option value="Fiji">Fiji</option>
                                                    <option value="Finland">Finland</option>
                                                    <option value="France">France</option>
                                                    <option value="French Guiana">French Guiana</option>
                                                    <option value="French Polynesia">French Polynesia</option>
                                                    <option value="French Southern Territories">French Southern Territories</option>
                                                    <option value="Gabon">Gabon</option>
                                                    <option value="Gambia">Gambia</option>
                                                    <option value="Georgia">Georgia</option>
                                                    <option value="Germany">Germany</option>
                                                    <option value="Ghana">Ghana</option>
                                                    <option value="Gibraltar">Gibraltar</option>
                                                    <option value="Greece">Greece</option>
                                                    <option value="Greenland">Greenland</option>
                                                    <option value="Grenada">Grenada</option>
                                                    <option value="Guadeloupe">Guadeloupe</option>
                                                    <option value="Guam">Guam</option>
                                                    <option value="Guatemala">Guatemala</option>
                                                    <option value="Guernsey">Guernsey</option>
                                                    <option value="Guinea">Guinea</option>
                                                    <option value="Guinea-bissau">Guinea-bissau</option>
                                                    <option value="Guyana">Guyana</option>
                                                    <option value="Haiti">Haiti</option>
                                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                                    <option value="Honduras">Honduras</option>
                                                    <option value="Hong Kong">Hong Kong</option>
                                                    <option value="Hungary">Hungary</option>
                                                    <option value="Iceland">Iceland</option>
                                                    <option value="India">India</option>
                                                    <option value="Indonesia">Indonesia</option>
                                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                                    <option value="Iraq">Iraq</option>
                                                    <option value="Ireland">Ireland</option>
                                                    <option value="Isle of Man">Isle of Man</option>
                                                    <option value="Israel">Israel</option>
                                                    <option value="Italy">Italy</option>
                                                    <option value="Jamaica">Jamaica</option>
                                                    <option value="Japan">Japan</option>
                                                    <option value="Jersey">Jersey</option>
                                                    <option value="Jordan">Jordan</option>
                                                    <option value="Kazakhstan">Kazakhstan</option>
                                                    <option value="Kenya">Kenya</option>
                                                    <option value="Kiribati">Kiribati</option>
                                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                                    <option value="Kuwait">Kuwait</option>
                                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                                    <option value="Latvia">Latvia</option>
                                                    <option value="Lebanon">Lebanon</option>
                                                    <option value="Lesotho">Lesotho</option>
                                                    <option value="Liberia">Liberia</option>
                                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                                    <option value="Liechtenstein">Liechtenstein</option>
                                                    <option value="Lithuania">Lithuania</option>
                                                    <option value="Luxembourg">Luxembourg</option>
                                                    <option value="Macao">Macao</option>
                                                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                                    <option value="Madagascar">Madagascar</option>
                                                    <option value="Malawi">Malawi</option>
                                                    <option value="Malaysia">Malaysia</option>
                                                    <option value="Maldives">Maldives</option>
                                                    <option value="Mali">Mali</option>
                                                    <option value="Malta">Malta</option>
                                                    <option value="Marshall Islands">Marshall Islands</option>
                                                    <option value="Martinique">Martinique</option>
                                                    <option value="Mauritania">Mauritania</option>
                                                    <option value="Mauritius">Mauritius</option>
                                                    <option value="Mayotte">Mayotte</option>
                                                    <option value="Mexico">Mexico</option>
                                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                                    <option value="Monaco">Monaco</option>
                                                    <option value="Mongolia">Mongolia</option>
                                                    <option value="Montenegro">Montenegro</option>
                                                    <option value="Montserrat">Montserrat</option>
                                                    <option value="Morocco">Morocco</option>
                                                    <option value="Mozambique">Mozambique</option>
                                                    <option value="Myanmar">Myanmar</option>
                                                    <option value="Namibia">Namibia</option>
                                                    <option value="Nauru">Nauru</option>
                                                    <option value="Nepal">Nepal</option>
                                                    <option value="Netherlands">Netherlands</option>
                                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                    <option value="New Caledonia">New Caledonia</option>
                                                    <option value="New Zealand">New Zealand</option>
                                                    <option value="Nicaragua">Nicaragua</option>
                                                    <option value="Niger">Niger</option>
                                                    <option value="Nigeria">Nigeria</option>
                                                    <option value="Niue">Niue</option>
                                                    <option value="Norfolk Island">Norfolk Island</option>
                                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                    <option value="Norway">Norway</option>
                                                    <option value="Oman">Oman</option>
                                                    <option value="Pakistan">Pakistan</option>
                                                    <option value="Palau">Palau</option>
                                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                                    <option value="Panama">Panama</option>
                                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                                    <option value="Paraguay">Paraguay</option>
                                                    <option value="Peru">Peru</option>
                                                    <option value="Philippines">Philippines</option>
                                                    <option value="Pitcairn">Pitcairn</option>
                                                    <option value="Poland">Poland</option>
                                                    <option value="Portugal">Portugal</option>
                                                    <option value="Puerto Rico">Puerto Rico</option>
                                                    <option value="Qatar">Qatar</option>
                                                    <option value="Reunion">Reunion</option>
                                                    <option value="Romania">Romania</option>
                                                    <option value="Russian Federation">Russian Federation</option>
                                                    <option value="Rwanda">Rwanda</option>
                                                    <option value="Saint Helena">Saint Helena</option>
                                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                    <option value="Saint Lucia">Saint Lucia</option>
                                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                                    <option value="Samoa">Samoa</option>
                                                    <option value="San Marino">San Marino</option>
                                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                                    <option value="Senegal">Senegal</option>
                                                    <option value="Serbia">Serbia</option>
                                                    <option value="Seychelles">Seychelles</option>
                                                    <option value="Sierra Leone">Sierra Leone</option>
                                                    <option value="Singapore">Singapore</option>
                                                    <option value="Slovakia">Slovakia</option>
                                                    <option value="Slovenia">Slovenia</option>
                                                    <option value="Solomon Islands">Solomon Islands</option>
                                                    <option value="Somalia">Somalia</option>
                                                    <option value="South Africa">South Africa</option>
                                                    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                                    <option value="Spain">Spain</option>
                                                    <option value="Sri Lanka">Sri Lanka</option>
                                                    <option value="Sudan">Sudan</option>
                                                    <option value="Suriname">Suriname</option>
                                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                    <option value="Swaziland">Swaziland</option>
                                                    <option value="Sweden">Sweden</option>
                                                    <option value="Switzerland">Switzerland</option>
                                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                                    <option value="Taiwan">Taiwan</option>
                                                    <option value="Tajikistan">Tajikistan</option>
                                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                                    <option value="Thailand">Thailand</option>
                                                    <option value="Timor-leste">Timor-leste</option>
                                                    <option value="Togo">Togo</option>
                                                    <option value="Tokelau">Tokelau</option>
                                                    <option value="Tonga">Tonga</option>
                                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                    <option value="Tunisia">Tunisia</option>
                                                    <option value="Turkey">Turkey</option>
                                                    <option value="Turkmenistan">Turkmenistan</option>
                                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                    <option value="Tuvalu">Tuvalu</option>
                                                    <option value="Uganda">Uganda</option>
                                                    <option value="Ukraine">Ukraine</option>
                                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                                    <option value="United Kingdom">United Kingdom</option>
                                                    <option value="United States">United States</option>
                                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                    <option value="Uruguay">Uruguay</option>
                                                    <option value="Uzbekistan">Uzbekistan</option>
                                                    <option value="Vanuatu">Vanuatu</option>
                                                    <option value="Venezuela">Venezuela</option>
                                                    <option value="Viet Nam">Viet Nam</option>
                                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                                    <option value="Western Sahara">Western Sahara</option>
                                                    <option value="Yemen">Yemen</option>
                                                    <option value="Zambia">Zambia</option>
                                                    <option value="Zimbabwe">Zimbabwe</option>
                                                </select>                                                
                                            </div>
                                        </div>
                                        <div class='col-md-4'>
                                            <label for='facility_id' id="facility_id_label" style='display: none;'></label>
                                            <select class='form-control' id='facility_id' name='facility_id' style="display: none;" disabled>                                                
                                            </select>
                                        </div>                                        
                                    </div>
                                        
                                    <div class="row form-group">
                                        <div class='col-md-12'>
                                            <button type='submit' class='btn btn-warning'>Update</button>
                                        </div>
                                    </div>
                                </form>                                
                                </div>  
                            </div>
                        </div>
                        <!-- Shipment Status Update card -->

                        <?php 
                        }
                    } ?>

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
                                    <h4 class="card-title">Shipment Activity</h4>
                                    <p class="card-category">Click on any entry to get more Details</p>
                                </div>
                                <div class="card-body" style='overflow-y: auto;'>
                                    <div class="table-responsive">
                                        <table class="">
                                            <thead>
                                                <tr>
                                                <th>Facility</th>
                                                <th>Date (Year/Month/Date)</th>
                                                <th>Activity</th>                                        
                                                <th>From</th>
                                                <th>To</th>                                        
                                                <th>Remarks</th>                                      
                                                </tr>
                                            </thead>
                                            <tbody id="shipment_event_table">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <!-- Shipment Events Details card Close -->                      
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


<?php }?>