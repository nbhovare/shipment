<?php

    session_start();
    if(!isset($_SESSION['isSession'])){
        header("location:./login.php");
    }        


    include("./includes/db_connect.php");
    include("./includes/check_permission.php");

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
    <title>Shipments</title>
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
    <script src="../assets/js/custom.js" type="text/javascript"></script>

    


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
                $('#forward_ret_div_city').show();

                var state=$("#forward_ret_state").val();
                var country=$("#forward_ret_country").val();
                var city=$("#forward_ret_city").val();

                var error_res="";

                if(state==="Select State"){                    
                    error_res="<li>Select State Properly</li>";                
                }
                if(country==="Select Country"){
                    error_res="<li>Select Country</li>";
                }
                if(city==="Select City"){
                    error_res="<li>Select City Properly</li>";
                }
                
                if(error_res.length!=0){
                    $("#facility_id").empty();
                    $('#facility_id').prop('disabled', true);                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                
                    // List Facilities in Select            

                    $.ajax({
                            type: "POST",
                            url: './queries/getFacilityList.php',
                            data:  {facilityCountry:country,facilityState:state,facilityCity:city},
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

            else if(actv==="DELIVERED"){
                var count
            }

            else{
                $('#facility_id_label').empty();                                
                $('#facility_id_label').hide();                               
                $('#forward_ret_div_country').hide();
                $('#forward_ret_div_state').hide();
                $('#forward_ret_div_city').hide();
                $("#facility_id").empty();
                $("#facility_id").hide();                
            }    
        }

        function getStandardStatus(getStatusOf){
            var reason="";
            switch(getStatusOf){
                case "ARRIVED":
                    reason="Arrived at Branch";
                break;
                
                case "FORWARD":
                    reason="Forwarding to another branch for further transit";
                break;

                case "RETURN":
                    reason="Returning to another branch for further transit";
                break;

                case "OUT_FOR_DELIVERY":
                    reason="Out for Delivery";
                break;

                case "DELIVERED":
                    reason="Delivered to Customer";
                break;

                case "ONHOLD":
                    reason="Placing ONHOLD basis: Mention your Business requirement/ Reason for Placing ONHOLD";
                break;

                case "RELEASE_ON_HOLD":
                    reason="Releasing ONHOLD basis: Mention your Business requirement/ Reason";
                break;

                case "CANCEL":
                    reason="Cancelling shipment basis: Mention your Business requirement/ Reason for cancellation";
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

        <?php 

            if($_SESSION['type']!="CLIENT"){            
        ?>

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
                            var typeFor=null;                                                     
                            if(type==="INQUEUE"){
                                typeFor="At Facility:";
                            }
                            else{
                                typeFor="";
                            }
                            $.each(response.shipment_data, function(index, event) {
                                
                                $("#queue_process_form_table").append("\
                                <tr>\
                                    <td> "+event.shipment_id+"</td>\
                                    <td>"+typeFor+" "+event.shipment_status+"</td>\
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

        <?php } ?>


        function handleEventRowClick(event) {
  // Get the ID of the clicked row
  const rowId = event.currentTarget.id;
  // Get Event data to show in Details
  
var data_send={
    eventId:rowId,
    shipmentId:$("#shipmentID").val()
};

  $.ajax({
                        type: "POST",
                        url: './queries/getShipmentEventDatas.php',
                        data:  {data: data_send},
                        success: function(response)
                        {                                                        
                            var responseData = JSON.parse(response);
                            if(responseData.error_msg){
                                alert(responseData.error_msg);
                            }
                            else{                                 
                                $("#actions_modal_title").empty();
                                $("#actions_modal_body").empty();  
                                $("#actions_modal_title").append("<h4>Shipment Activity Log at "+responseData.events_data[0].facility_name+"</h4>");
                                $("#actions_modal").modal("show"); 
                                
                                const fieldsToShow=["facility_name","event_date","events_activity","forward_fac_name","return_fac_name","event_remarks"]
                                const rowForEventData=$("<div>").addClass("row");
                                let countEventDatas=1;                                
                                $.each(responseData.events_data[0],function(index,eventDataFromId){
                                    
                                        if(fieldsToShow.includes(index)){
                                            const paraText=$("<p>").append(index+" : "+eventDataFromId);
                                            $("#actions_modal_body").append(paraText);  
                                        }
                                    

                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }   
                    });


}

function getConData(data_send,elementToAppendDataTo,basedOnValue){
    // Get Data and send back
    $.ajax({
        type: "POST",
        url: './queries/getCountryData.php',
        data:  {data: data_send},
        success: function(response)
        {                               
            if(response && response.error_msg){
                alert(response.error_msg);
                elementToAppendDataTo.empty();
            }
            else if (response && response.data) {                                                                                                    
                            
            elementToAppendDataTo.empty();
            elementToAppendDataTo.append("<option value='Select "+basedOnValue+"'>Select "+basedOnValue+"</option>");
                $.each(response.data, function(index, getData) {                    

                    $.each(getData, function(index, Values) {                                                                    
                        const OptionsData=$("<option>").attr("value",Values).text(Values);
                        elementToAppendDataTo.append(OptionsData);                                     
                    });
                        
                });                    
            }                                                                                                          
        },
        error: function (xhr, status, error) {
            //return "error";
            alert("error");
        }
    });     

}

        
        $(document).ready(function() {    


            const getConForSender=$("#forward_ret_country");   
            getCountry(getConForSender);

            
            $("#forward_ret_country").change(function(){
                var countryForUpe=$("#forward_ret_country").val();
                var error_res="";
                if(countryForUpe==="Select Country"){
                    error_res="<li>Select Country Properly</li>";
                }
                if(error_res.length!=0){
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                    var data_send={                                                
                        "getData":"state",
                        "basedOn":countryForUpe
                    };
                    getConData(data_send,$("#forward_ret_state"),"State");
                }
            });
            
            $("#forward_ret_state").change(function(){
                var stateForUpe=$("#forward_ret_state").val();
                var error_res="";
                if(stateForUpe==="Select State"){
                    error_res="<li>Select State Properly</li>";
                }
                if(error_res.length!=0){
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                    var data_send={                                                
                        "getData":"city",
                        "basedOn":stateForUpe
                    };
                    getConData(data_send,$("#forward_ret_city"),"City");
                }
            });


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
                    var city=$("#forward_ret_city").val();               
                    var facility=$("#facility_id").val();
                    
                    if(state=="Select State")
                        error_res="<li>Select State Properly</li>";

                        if(city==="Select City")
                            error_res="<li>Select City Properly</li>";                        
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

            <?php 

            if($_SESSION['type']!="CLIENT"){

                ?>

            $("#filterChecks").change(function(){
                if($(this).is(":checked")){
                    $("#ship_state_buttons").show();    
                    $("#shipmentID").prop("disabled",true);
                }
                else{
                    $("#ship_state_buttons").hide();
                    $("#shipmentID").prop("disabled",false);
                }
            });
            
        <?php 
            }
            ?>


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
                if(shipmentID.length===""){
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

                                var shipmentStatusArr=["Booked by Branch","On the way","Out for delivery","Delivered/Cancel"];                                
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

                                /*const mainColForShipDTable=$("<div>").addClass("row");*/
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
                                    $("#shipment_details_row").append(colForShipDTable);
                                }                                
                                /*$("#shipment_details_row").append(mainColForShipDTable);*/
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
                                        <tr id="+event.event_id+">\
                                            <td><a href='./facility.php?fac_id="+event.facility_id+"' target=_blank>\
                                            "+event.facility_name+"</a></td>\
                                            <td>"+event.event_date+"</td>\
                                            <td>"+event.events_activity+"</td>\
                                            <td>"+event.facility_name+"</td>\
                                            <td>"+nextFac+"</td>\
                                            <td>"+event.event_remarks+"</td>\
                                        </tr>");                                        
                                        
                                });

                                const table_event = document.getElementById('shipment_event_table_id');
                                const rows_event = table_event.getElementsByTagName('tr');

// Add click event listeners to each row
for (let i = 0; i < rows_event.length; i++) {
  rows_event[i].addEventListener('click', handleEventRowClick);
}
                                    
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
        <!--<button type="button" id="perSaveBtn" class="btn btn-primary" style='display:none'>Save changes</button>        -->
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
            <div class="content" style='margin-top:10px'>
                <div class="container-fluid">
                    <div class="row">

                    <?php

                            include("./includes/quick_links.php");

                      ?>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Shipments</h4>
                                    <h6>Use below filters to fetch shipments data</h6>
                                </div>
                                <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="checkbox" name="filterChecks" id="filterChecks">
                                        <label for="filterChecks">More Options</label>
                                    </div>                                    
                                    <div class="col-md-12" id="ship_state_buttons" style='display:none'>
                                        <?php 
                                    if($_SESSION['type']!="CLIENT"){

?>                                                    
<button type="button" class="btn btn-fill" id="shipmentsIncoming" onclick="QueueProcessed('INCOMING')">Shipments - Incoming</button>
<button type="button" class="btn btn-fill" id="shipmentsInQueue" onclick="QueueProcessed('INQUEUE')">Shipments - In Queue</button>
<button type="button" class="btn btn-fill" id="shipmentsProcessed" onclick="QueueProcessed('PROCESSED')">Shipments - Processed</button>
<?php } ?>
                                    </div>                                                                        
                                </div>

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
                                <div class="card-body  table-responsive">                                    
                                    <table class="table-hover">
                                        <thead>
                                            <th>Shipment ID</th>
                                            <th>Current Status</th>                                                                                    
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
                                    <div class="col-md-3" style='display:none' id="forward_ret_div_country">
                                            <div class="form-group">
                                                <label for="forward_ret_country">Country: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                <select id="forward_ret_country" name="forward_ret_country" class="form-control" >                                                    
                                                </select>                                                
                                            </div>
                                        </div>
                                        <div class="col-md-3" style='display:none' id="forward_ret_div_state">
                                            <div class="form-group">
                                                <label for="forward_ret_state" >State: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                <select id="forward_ret_state" name="forward_ret_state" class="form-control" >                                                                                                        
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-3' style='display:none' id="forward_ret_div_city">
                                            <label for='facility_id' id="forward_ret_city_label">City: <span style="color:red;font-weight:bold">*</span></label>
                                            <select class='form-control' id='forward_ret_city' name='forward_ret_city' onchange="checkUpdateStat()" >                                                
                                            </select>
                                        </div>                                        
                                        
                                        <div class='col-md-3'>
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
                                        <table class="" id="shipment_event_table_id">
                                            <thead>
                                                <tr>
                                                <th>Branch</th>
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