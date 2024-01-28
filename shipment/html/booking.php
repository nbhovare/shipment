<?php

    session_start();
    if(!isset($_SESSION['isSession'])){
        header("location:./login.php");
    }

    require("./includes/db_connect.php");
    require("./includes/check_permission.php");

    $resForPagePer=checkPermission($_SESSION['user_id'],"booking_php",$connection);
    if($resForPagePer==="0"){
        echo "You do not have permission to this page, Please contact your administrator for any query<br/>";       
        echo "<a href='./index.php'>Click here to goto home page</a>";
    }
    else{

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
    <title>Book Shipment</title>
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
    <script src="../assets/js/custom.js" type="text/javascript"></script>


    

<!-- body {
  transform: scale(0.85); /* Scale down the element */
}-->

    
    <script type="text/javascript">



        $(document).ready(function() {

            <?php 
                if($_SESSION['type']==="CLIENT"){    
            ?>
            const param_id="<?php echo $_SESSION['user_id']; ?>";

            var data_send={
        "type":"getShipInfo",
        "user_id":param_id
    };

        $.ajax({
        type: "POST",
        url: './queries/usrProfile.php',
        data:  {data: data_send},
        success: function(response)
        {                   

            
            var jsonData=JSON.parse(response);
            if(jsonData && jsonData.error_msg){
                alert(jsonData.error_msg);                        
            }
            else{         
                               
                $("#CreditHeading").empty().append("Current Credits Balance = "+jsonData.credits);                            
            }                                                                                                          
        },
        error: function (xhr, status, error) {
            //return "error";
            alert("error");
        },
        complete: function(){                        
        }
    });
    <?php 
} ?>


            const getConForSender=$("#sender_country");   
            getCountry(getConForSender);

            const getConForReceiver=$("#receiver_country");   
            getCountry(getConForReceiver);

            $("#sender_country").change(function(){    
                var error_res="";
                var basedOn=$("#sender_country").val();

                if(basedOn==="Select Country"){
                    error_res="Select Country Properly";
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{                             
                    
                    
                    var data_send={
                        "getData":"state",
                        "basedOn":basedOn
                    };                
                    $.ajax({
                        type: "POST",
                        url: './queries/getCountryData.php',
                        data:  {data: data_send},
                        success: function(response)
                        {                   
                            if(response && response.error_msg){
                                alert(response.error_msg);
                                $("#sender_state").empty();
                            }
                            else if (response && response.data) {                                                                                                    
                                
                            const senderStateField=$("#sender_state");                    
                            senderStateField.empty();
                            senderStateField.append("<option value='Select State'>Select State</option>");
                                $.each(response.data, function(index, getData) {                    

                                    $.each(getData, function(index, stateValues) {                                                                    
                                        const optionForState=$("<option>").attr("value",stateValues).text(stateValues);
                                        senderStateField.append(optionForState);                                     
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
            });

            <?php 
                if($_SESSION['type']!="CLIENT"){

                ?>
            $("#autoFill").change(function(){
                
                if($(this).is(':checked')){
                    $("#senderInformation").hide();
                }
                else{
                    $("#senderInformation").show();
                }
            });
            <?php 

                } ?>



            $("#sender_state").change(function(){
                // Get City As per State
                
                var error_res="";
                var basedOn=$("#sender_state").val();

                if(basedOn==="Select State"){
                    error_res="Select Sender State Properly";
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                    var data_send={
                        "getData":"city",
                        "basedOn":basedOn
                    };                
                    $.ajax({
                        type: "POST",
                        url: './queries/getCountryData.php',
                        data:  {data: data_send},
                        success: function(response)
                        {                   
                            if(response && response.error_msg){
                                alert(response.error_msg);
                                $("#sender_city").empty();
                            }
                            else if (response && response.data) {                                                                                                    
                                
                            const senderCityField=$("#sender_city");                    
                            senderCityField.empty();
                            senderCityField.append("<option value='Select City'>Select City</option>");
                                $.each(response.data, function(index, getData) {                    

                                    $.each(getData, function(index, stateValues) {                                                                    
                                        const optionForCity=$("<option>").attr("value",stateValues).text(stateValues);
                                        senderCityField.append(optionForCity);                                     
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
                
            });

            $("#receiver_country").change(function(){    
                var error_res="";
                var basedOn=$("#receiver_country").val();

                if(basedOn==="Select Country"){
                    error_res="Select Country Properly";
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{                             
                    
                    
                    var data_send={
                        "getData":"state",
                        "basedOn":basedOn
                    };                
                    $.ajax({
                        type: "POST",
                        url: './queries/getCountryData.php',
                        data:  {data: data_send},
                        success: function(response)
                        {                   
                            if(response && response.error_msg){
                                alert(response.error_msg);
                                $("#receiver_state").empty();
                            }
                            else if (response && response.data) {                                                                                                    
                                
                            const receiverStateField=$("#receiver_state");                    
                            receiverStateField.empty();
                            receiverStateField.append("<option value='Select State'>Select State</option>");
                                $.each(response.data, function(index, getData) {                    

                                    $.each(getData, function(index, stateValues) {                                                                    
                                        const optionForState=$("<option>").attr("value",stateValues).text(stateValues);
                                        receiverStateField.append(optionForState);                                     
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
            });

            $("#receiver_state").change(function(){
                // Get City As per State
                
                var error_res="";
                var basedOn=$("#receiver_state").val();

                if(basedOn==="Select State"){
                    error_res="Select Sender State Properly";
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                    var data_send={
                        "getData":"city",
                        "basedOn":basedOn
                    };                
                    $.ajax({
                        type: "POST",
                        url: './queries/getCountryData.php',
                        data:  {data: data_send},
                        success: function(response)
                        {                   
                            if(response && response.error_msg){
                                alert(response.error_msg);
                                $("#Receiver_city").empty();
                            }
                            else if (response && response.data) {                                                                                                    
                                
                            const receiverCityField=$("#Receiver_city");                    
                            receiverCityField.empty();
                            receiverCityField.append("<option value='Select City'>Select City</option>");
                                $.each(response.data, function(index, getData) {                    

                                    $.each(getData, function(index, stateValues) {                                                                    
                                        const optionForCity=$("<option>").attr("value",stateValues).text(stateValues);
                                        receiverCityField.append(optionForCity);                                     
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
                
            });

            
            $('#bookShipment').submit(function(e) {
                e.preventDefault();
                var formData = $('#bookShipment').serializeArray();
                
                var error_res="";
                                     
                <?php 

                    if($_SESSION['type']!="CLIENT"){
                    ?>
                if(formData.find(field => field.name === "sender_phone").value.length!=10){
                    error_res=error_res+"<li>Enter Sender Mobile Number Properly</li>";
                }
                
                if(formData.find(field => field.name === "sender_city").value==="Select City" || 
                formData.find(field => field.name === "sender_city").value===""){
                    error_res=error_res+"<li>Select Sender City Properly</li>";
                }

                if(formData.find(field => field.name === "sender_state").value==="Select State" || 
                formData.find(field => field.name === "sender_state").value===""){
                    error_res=error_res+"<li>Select Sender State Properly</li>";
                }

                if(formData.find(field => field.name === "sender_country").value==="Select Country" || 
                formData.find(field => field.name === "sender_country").value===""){
                    error_res=error_res+"<li>Select Sender Country Properly</li>";
                }
                if(formData.find(field => field.name === "sender_pincode").value.length!=6){
                    error_res=error_res+"<li>Enter Sender Pincode Properly</li>";
                }


                if(formData.find(field => field.name === "shipment_id").value===""){
                    error_res=error_res+"<li>Enter Shipment Id Properly</li>";
                }

                <?php 
                    } ?>
                
                if(formData.find(field => field.name === "Receiver_phone").value.length!=10){
                    error_res=error_res+"<li>Enter receiver Mobile Number Properly</li>";
                }
                
                

                if(formData.find(field => field.name === "Receiver_pincode").value.length!=6){
                    error_res=error_res+"<li>Enter Receiver Pincode Properly</li>";
                }

               

                

                if(formData.find(field => field.name === "receiver_country").value==="Select Country" || 
                formData.find(field => field.name === "receiver_country").value===""){
                    error_res=error_res+"<li>Select Receiver Country Properly</li>";
                }

                if(formData.find(field => field.name === "receiver_state").value==="Select State" || 
                formData.find(field => field.name === "receiver_state").value===""){
                    error_res=error_res+"<li>Select Receiver State Properly</li>";
                }


                if(formData.find(field => field.name === "Receiver_city").value==="Select City" || 
                formData.find(field => field.name === "Receiver_city").value===""){
                    error_res=error_res+"<li>Select Receiver City Properly</li>";
                }

                
                if(formData.find(field => field.name === "shipment_delivery_method").value==="Select Delivery Method"){
                    error_res=error_res+"<li>Select Shipment Method Properly</li>";
                }

                if(formData.find(field => field.name === "shipment_type").value==="Select Shipment Type"){
                    error_res=error_res+"<li>Select Shipment Type Properly</li>";
                }
                
                if(formData.find(field => field.name === "content_type").value==="Select Shipment Content Type"){
                    error_res=error_res+"<li>Select Shipment Content Type Properly</li>";
                }

                if(formData.find(field => field.name === "payment_type").value==="Select Payment Method"){
                    error_res=error_res+"<li>Select Payment Method Properly</li>";
                }
               
                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{

                    // Serialize the form data using jQuery                    

                    // Convert the serialized form data to a JSON object
                    var formDataObject = {};
                    $.each(formData, function(index, field) {
                        formDataObject[field.name] = field.value;
                    });

                    // Convert the JSON object to a JSON string
                    var formDataJSON = JSON.stringify(formDataObject);                    
                                                     
                    $.ajax({
                        type: "POST",
                        url: './queries/bookShipment.php',
                        data:  {data: formDataJSON},
                        success: function(response)
                        {                                                        
                            var responseData = JSON.parse(response);
                            if(responseData.error_msg){
                                alert(responseData.error_msg);
                            }
                            else{
                                const shipIDBooked=responseData.shipment_id;
                                alert(shipIDBooked);
                                const form = document.getElementById('bookShipment');
                                form.reset();
                                window.location.href="./trackshipment.php?shipment_id="+shipIDBooked
                                //alert(responseData[0].shipment_id);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }
                    });
                }
            });
        });

    </script>
    
    
</head>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
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
                                <div class="table-plain-bg">
                                    <div class="card-header ">
                                        <h4 class="card-title">Book Shipment</h4>
                                        <p class="card-category">Enter Shipment Details (Fields marked as <span style="color:red;font-weight:bold">" * "</span> are required)</p>
                                    </div>
                                <div class="card-body">
                                    <h6 id="CreditHeading"></h6>
                                    <form id="bookShipment" method="post">
                                        <div class="row">
                                            <?php 

                                                if($_SESSION['type']!="CLIENT"){
                                                
                                            ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="shipment_id">Shipment ID</label>

                                                    <input type="text" name="shipment_id" id="shipment_id" class="form-control" placeholder="Enter Shipment ID"  required>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Shipment Type <span style="color:red;font-weight:bold">*</span></label>                                                                                                        
                                                    <select class="form-control" id="shipment_type" name="shipment_type" required>
                                                        <option value="Select Shipment Type">Select Shipment Type</option>
                                                        <option value="BASIC">Basic</option>
                                                        <option value="STANDARD">Standard</option>                                                        
                                                        <option value="PREMIUM">Premium</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Shipment Weight (In Kg)<span style="color:red;font-weight:bold">*</span></label>
                                                    <input type="number" id="shipment_weight" name="shipment_weight" class="form-control" placeholder="Enter Shipment Weight" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Shipment Delivery Method <span style="color:red;font-weight:bold">*</span></label>
                                                    <select class="form-control" id="shipment_delivery_method" name="shipment_delivery_method" required>
                                                        <option value="Select Delivery Method">Select Delivery Method</option>
                                                        <option value="GND">Ground</option>
                                                        <option value="AIR">Air</option>                                                        
                                                        <option value="SEA">Water</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Shipment Content Type <span style="color:red;font-weight:bold">*</span></label>
                                                    <select class="form-control" id="content_type" name="content_type" required>
                                                        <option value="Select Shipment Content Type">Select Content Type</option>
                                                        <option value="DOCUMENTS">Documents</option>
                                                        <option value="FOOD">Food Items</option>
                                                        <option value="FRAGILE">Fragile Items</option>
                                                        <!-- Add more options as needed -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Shipment Cost (In Rupees) <span style="color:red;font-weight:bold">*</span></label>
                                                    <input type="number" id="shipment_cost" name="shipment_cost" class="form-control" placeholder="Enter Shipment Cost" required>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Booking Date <span style="color:red;font-weight:bold">*</span></label>
                                                    <input type="date" id="booking_date" name="booking_date" class="form-control" required>
                                                    <!--<input type="datetime-local" id="booking_date" name="booking_date" class="form-control" required>-->
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Shipment Additional Information <span style="color:red;font-weight:bold">(Optional)</span></label>
                                                    <textarea class="form-control" rows="5" id="additional_information" name="additional_information" placeholder="Enter Additional Information"></textarea>
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="clearfix"></div>
                                    
                                </div>
                            </div>   
                        </div>   
                        </div>   
                        

                        <?php 
                            if($_SESSION['type']!="CLIENT"){
                            ?>

                        <div class="col-md-12">
                            <input type="checkbox" id="autoFill" checked>
                            <label for="">Sender Information auto fill from Saved Info</label>
                        </div>

                        <div class="col-md-12" id="senderInformation" style='display:none'>
                            <div class="card">
                                <div class=" table-plain-bg">
                                    <div class="card-header ">
                                        <h4 class="card-title">Sender Information</h4>
                                        <p class="card-category">Enter Sender Details (Fields marked as <span style="color:red;font-weight:bold">" * "</span> are required)</p>
                                    </div>
                                <div class="card-body">
                                    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sender_full_name">Sender Full Name: <span style="color:red;font-weight:bold">*</span></label>
                                                <input type="text" class="form-control"  id="sender_name" name="sender_name" placeholder="Sender's Full Name" required>
                                            </div>
                                        </div>                                        
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sender_email">Sender Email: <span style="color:red;font-weight:bold">*</span></label>
                                                <input type="email" class="form-control"  id="sender_email" name="sender_email" placeholder="Sender's Email" required>
                                            </div> 
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sender_phone">Sender Phone Number:</label>
                                                <input type="tel" class="form-control"  id="sender_phone" name="sender_phone" placeholder="Sender's Phone Number" maxlength="10" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sender_country">Sender Country: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                <select id="sender_country" name="sender_country" class="form-control" required>                                                    
                                                </select>
                                            </div>
                                        </div>                                                                            
                                    </div>
                                        
                                    <div class="row">
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sender_state">Sender State: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                <select id="sender_state" name="sender_state" class="form-control" required>                                                  
                                                </select>
                                            </div>
                                        </div>
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sender_city">Sender City: <span style="color:red;font-weight:bold">*</span></label>
                                                <select type="text" class="form-control"  id="sender_city" name="sender_city"></select>
                                            </div>
                                        </div>    
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sender_address">Sender Address: <span style="color:red;font-weight:bold">*</span></label>
                                                
                                                <textarea class="form-control" rows="5" id="sender_address" name="sender_address" placeholder="Sender's Full Address" required></textarea>
                                            </div>
                                        </div>                                                                                
                                        
                                        
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sender_pincode">Sender Pincode: <span style="color:red;font-weight:bold">*</span></label>
                                                <input type="number" class="form-control" id="sender_pincode" name="sender_pincode" placeholder="Sender's Pincode" minlength="6" maxlength="6" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>   
                        </div>   
                        </div>
                        <?php 
                    } ?>

                        <div class="col-md-12">
                            <div class="card">
                                <div class=" table-plain-bg">
                                    <div class="card-header ">
                                        <h4 class="card-title">Receiver Information</h4>
                                        <p class="card-category">Enter Receiver Details (Fields marked as <span style="color:red;font-weight:bold">" * "</span> are required)</p>
                                    </div>
                                <div class="card-body">
                                    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Receiver_full_name">Receiver Full Name: <span style="color:red;font-weight:bold">*</span></label>
                                                <input type="text" class="form-control"  id="Receiver_name" name="Receiver_name" placeholder="Receiver's Full Name" required>
                                            </div>
                                        </div>                                        
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Receiver_email">Receiver Email: <span style="color:red;font-weight:bold">*</span></label>
                                                <input type="email" class="form-control"  id="Receiver_email" name="Receiver_email" placeholder="Receiver's Email" required>
                                            </div> 
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Receiver_phone">Receiver Phone Number: <span style="color:red;font-weight:bold">*</span></label>
                                                <input type="tel" class="form-control"  id="Receiver_phone" name="Receiver_phone" placeholder="Receiver's Phone Number" maxlength="10" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Receiver_country">Receiver Country: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                <select id="receiver_country" name="receiver_country" class="form-control" required>                                                    
                                                </select>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                        
                                    <div class="row">                                        
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Receiver_state">Receiver State: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                <select id="receiver_state" name="receiver_state" class="form-control" required>                                                   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Receiver_city">Receiver City: <span style="color:red;font-weight:bold">*</span></label>
                                                <select type="text" class="form-control"  id="Receiver_city" name="Receiver_city"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Receiver_address">Receiver Address: <span style="color:red;font-weight:bold">*</span></label>
                                                <input type="text" class="form-control"  id="Receiver_address" name="Receiver_address" placeholder="Receiver's Address" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Receiver_pincode">Receiver Pincode: <span style="color:red;font-weight:bold">*</span></label>
                                                <input type="number" class="form-control" id="Receiver_pincode" name="Receiver_pincode" placeholder="Receiver's Pincode" minlength="6" maxlength="6" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>   
                        </div>   
                    </div>

                        <!-- Payment Type -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="table-plain-bg">
                                    <div class="card-header ">
                                        <h4 class="card-title">Payment Details</h4>
                                        <p class="card-category">Enter payment Details (Fields marked as <span style="color:red;font-weight:bold">" * "</span> are required)</p>
                                    </div>
                                <div class="card-body">
                                    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Payment Mode <span style="color:red;font-weight:bold">*</span></label>
                                                    <select class="form-control" name="payment_type" id="payment_type" required>
                                                        <option >Select Payment Method</option>
                                                        <option value="Online At Booking">Online - At Booking</option>
                                                        <option value="Offline - At Booking">Offline - At Booking</option>                                                        
                                                        <option value="Offline - Cash on Delivery">Offline - Cash on Delivery</option>
                                                    </select>
                                                </div>
                                            </div>  
                                            <div class="col-md-12">
                                            
                                            
                                            <button type="submit" class="btn btn-info btn-fill pull-right">Submit</button>
                                            </div>                                          
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>   
                        </div>   
                        </div>  
                        <!-- Payment Type Close -->
                    </div>   
                                                       
                                                                  
                    </div>
                </div>
            </div>
            <!-- Main Content Close -->
           

            <!-- Footer -->
        </div>
    </div>
    <!--   -->

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



<?php }?>