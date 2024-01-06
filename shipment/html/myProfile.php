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



    <script src="../assets/js/custom.js" type="text/javascript"></script>



    <script type="text/javascript">



function getConData(data_send,elementToAppendDataTo,basedOnValue,setVal){
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
        },
        complete: function(){
            if(basedOnValue==="State"){                
                                           
                
                if(setVal!="" && setVal!=null){                    
                    $("#state").val(setVal);
                        var data_sen={
                            "getData": "city",
                            "basedOn": setVal
                        };
                    getConData(data_sen,$("#city"),"City",getConData.city);                     
                }
                else{
                    $("#state").val("Select State");
                }


                $("#city").empty();
                
                
            }
            


            if(basedOnValue==="City"){
                if(setVal!="" && setVal!=null){
                    $("#city").val(setVal);
                }
                else{
                    $("#city").val("Select City");
                }
            }
        }
    });     

}

function updatePassRequs(data_send){

$.ajax({
        type: "POST",
        url: './queries/profile.php',
        data:  {data: data_send},
        success: function(response)
        {                           
            var responseData=JSON.parse(response);   
            if(responseData.error_msg){
                alert(responseData.error_msg);
            }                             
            else{
                alert(responseData.ret_msg);
                $("#updatePassForm").trigger("reset");
            }                                                                  
        },
        error: function (xhr, status, error) {
            //return "error";
            alert("error");
        },
        complete: function(){
        }
    });  
}


function getCData(valueForSettings){    
    getCountry($("#country"));
    if(valueForSettings["country"]!=null || valueForSettings["country"]!=""){        
        $("#country").val(valueForSettings["country"]);
        var country=valueForSettings["country"];
        
        var data_send={
            "getData":"state",
            "basedOn": country
        };
        getConData.city=valueForSettings["city"];
        getConData(data_send,$("#state"),"State",valueForSettings["state"]);                        
    }
}

        window.onload = function() {    
            //Get User Data


           
            
        
               
                                    

        };

     
        $(document).ready(function() {



            var data_send={
                "type":"userID"
            }
            let setTheValue={};

                $.ajax({
                type: "POST",
                url: './queries/profile.php',
                data:  {data: data_send},
                success: function(response)
                {                   
                    if(response && response.error_msg){
                        alert(response.error_msg);                        
                    }
                    else{                                                                                                    
                        
                        var jsonData=JSON.parse(response);
                        $.each(jsonData.users_data[0], function(index, getData) {                    

                            
                            if(index==="country"){
                                //$("#"+index).val(getData);
                                
                                setTheValue["country"]=getData;
                                
                            }
                            else if(index==="state"){
                                setTheValue["state"]=getData;
                            }
                            else if(index==="city"){
                                setTheValue["city"]=getData;
                            }
                            else{                            

                                $("#editProfileForm input[id='"+index+"']").val(getData);                            
                            
                            }                            
                                
                        });                    
                    }                                                                                                          
                },
                error: function (xhr, status, error) {
                    //return "error";
                    alert("error");
                },
                complete: function(){
                    getCData(setTheValue);
                }
            });



            $("#showPass").change(function(){
                if($(this).is(':checked')){
                    $("#updatePassForm :input").attr("type","text");    
                    $("#showPass").attr("type","checkbox");
                }
                else{
                    $("#updatePassForm :input").attr("type","password");
                    $("#showPass").attr("type","checkbox");
                }

            });


            $("#editProfileCheck").change(function(){
            if ($(this).is(':checked')) {
                    $("#editProfileForm :input").prop("disabled", false);
                } else {
                    $("#editProfileForm :input").prop("disabled", true);
                    $("#editProfileCheck").prop("disabled", false);
                }
            });

            $("#updatePassForm").submit(function(e){
                

                e.preventDefault();
                var error_res="";
                const curPass=$("#currentPassword").val();
                const newPass=$("#newPassword").val();
                const confirmNewPass=$("#confirmNewPass").val();
                if(curPass==="" || newPass==="" || confirmNewPass===""){
                    error_res="All fields are mandatory";
                }
                if(newPass!=confirmNewPass){
                    error_res="New password & Confirm new password fields not matched";
                }

                if(error_res!=""){
                    alert(error_res);
                }
                else{
                    // ajax for updating new password
                    var data_send={
                        "type":"updatePass",                                        
                    };


                    async function hashPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hash = await crypto.subtle.digest('SHA-256', data);    
    return hash;
}


hashPassword($("#updatePassForm #newPassword").val())
        .then(hashedPassword => {
            // Convert the hashed password to a hexadecimal string
            const hashedPasswordHex = Array.from(new Uint8Array(hashedPassword))
                .map(byte => byte.toString(16).padStart(2, '0'))
                .join('');
            
                data_send["newPass"]=hashedPasswordHex;  
                
                return hashPassword($("#currentPassword").val());
                    })                    
        .then(hashedPassword2 => {
            // Convert the hashed password to a hexadecimal string
            const hashedPasswordHex2 = Array.from(new Uint8Array(hashedPassword2))
                .map(byte => byte.toString(16).padStart(2, '0'))
                .join('');
            
                console.log(hashedPasswordHex2);
                data_send["curPass"]=hashedPasswordHex2;
                updatePassRequs(data_send);
                    })
                    .catch(error => {
            console.error('Error hashing password:', error);
        });

/*
async function storeHashedPassword() {
    try {
        const hashedPassword = await hashPassword($("#updatePassForm #newPassword"));
        // Convert the hashed password to a hexadecimal string
        const hashedPasswordHex = Array.from(new Uint8Array(hashedPassword))
            .map(byte => byte.toString(16).padStart(2, '0'))
            .join('');
        //hashedPass = hashedPasswordHex;        
        //formDataObject["password"] = hashedPasswordHex;
        data_send["newPass"]=hashedPasswordHex;
        console.log(hashedPasswordHex);

        updatePassRequs(data_send);

    } catch (error) {
        console.error('Error hashing password:', error);
    }
}

// Call the function to store the hashed password
storeHashedPassword();*/



                }

                   


            });

            


            $("#country").change(function(){
                //$("#state").empty();                
                var country=$("#country").val();
                var error_res="";
                if(country==="Select Country"){
                    error_res+="<li>Select Country Properly</li>";
                }


                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                    var data_send={
                        "getData":"state",
                        "basedOn":country
                    };
                    getConData(data_send,$("#state"),"State","");
                }


            });

            $("#state").change(function(){
                var state=$("#state").val();
                var error_res="";
                if(state==="Select State"){
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
                        "basedOn":state
                    };
                    getConData(data_send,$("#city"),"City","");
                }                                        
            });



            $("#editProfileForm").submit(function(e){
                e.preventDefault();


                var error_res="";
                var state=$("#state").val();
                var country=$("#country").val();
                var city=$("#city").val();

                if(city==="Select City"){
                    error_res+="<li>Select City Properly</li>";
                }

                if(state==="Select State"){
                    error_res+="<li>Select State Properly </li>";
                }
                if(country==="Select Country"){
                    error_res+="<li>Select Country Properly </li>";
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{

                    var formData = $('#editProfileForm').serializeArray();
                    // Convert the serialized form data to a JSON object
                    var formDataObject = {};
                    //formDataObject["facility_id"] = $("#fac_details_show_form #facility_id").val();
                        $.each(formData, function(index, field) {
                            formDataObject[field.name] = field.value;
                        });

                        // Convert the JSON object to a JSON string
                        var formDataJSON = JSON.stringify(formDataObject);       

                        var data_send={
                            type:"updatePro",
                            formData:formDataJSON
                        };
                                                
                    $.ajax({
                        type: "POST",
                        url: './queries/profile.php',
                        data:  {data:data_send},
                        success: function(response)
                        {

                            var responseData = JSON.parse(response);
                            if(responseData.error_msg){
                                alert(responseData.error_msg);
                            }
                            else{
                                alert(responseData.ret_msg);
                                window.location.reload();
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
<div class="modal fade bd-example-modal-lg" id="actions_modal_pass" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updatePassForm" method="POST">
        <div class="row">            
            <div class="col-md-12">
                <input type="checkbox" name="showPass" id="showPass"> <label for=""> Show Password</label>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" name="currentPassword" id="currentPassword" placeholder="Current Password" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" name="newPassword" id="newPassword" placeholder="New Password" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="confirmNewPass">Confirm New Password</label>
                    <input type="password" name="confirmNewPass" id="confirmNewPass" class="form-control" placeholder="Confirm New Password" required>
                </div>
            </div>            
        </div>        
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update Password</button>        
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal -->





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
<style>
        
        /* CSS to make the loader image fixed and appear above other elements */
#loading-bar {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.7); /* Optional: semi-transparent overlay */
    z-index: 9999; /* Set a high z-index to ensure it appears above other elements */
}

#loader-image {
    /*
    display: block;
    margin: auto;
    max-width: 10%; /* Adjust the width as needed 
    height: auto;*/

    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: block;
    max-width: 10%; /* Adjust the width as needed */
    height: auto;

}


    </style>

<script>
    


        // Global AJAX event handlers
        $(document).ajaxStart(function() {
    $('#loading-bar').show(); // Show the loader when any AJAX request starts
});

$(document).ajaxStop(function() {
    $('#loading-bar').hide(); // Hide the loader when all AJAX requests complete
});


</script>

<div id="loading-bar" >
    <!-- Loading bar content, such as a spinner or progress animation -->
    <img src="../assets/img/loaders.gif" alt="" id="loader-image">
</div>  

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
                                    <h5>My Profile</h5>                                                                                                      
                                </div>                                
                                <div class="card-body">
                                    <hr/>              
                                    <form id="editProfileForm" method="POST">                                                                  
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="checkbox" id="editProfileCheck" > Edit Profile
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" required disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" required disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email_id">Email-ID</label>
                                                <input type="email" name="email_id" id="email_id" placeholder="Email ID" class="form-control" required disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mobile_no">Mobile Number</label>
                                                <input type="text" name="mobile_no" id="mobile_no" placeholder="Mobile Number" class="form-control" required disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="couuntry">Country</label>
                                                <select name="country" id="country" class="form-control" required disabled></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <select name="state" id="state" class="form-control" required disabled></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label for="city">City</label>
                                                <select name="city" id="city" class="form-control" required disabled></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">                                        
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success" disabled>Save</button>
                                            <button type="button" class="btn btn-warning" onclick="$('#updatePassForm').trigger('reset');$('#actions_modal_pass').modal('show')" disabled>Change Password</button>
                                        </div>
                                    </div>
                                </form>
                                </div>                                                                                          
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
