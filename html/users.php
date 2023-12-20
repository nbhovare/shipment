<?php

    session_start();
    if(!isset($_SESSION['isSession'])){
        header("location:./login.php");
    }


    require("./includes/db_connect.php");
    require("./includes/check_permission.php");

    $resForPagePer=checkPermission($_SESSION['user_id'],"users_php",$connection);
    if($resForPagePer==="0"){
        echo "You do not have permission to this page, Please contact your administrator for any query<br/>";       
        echo "<a href='./index.php'>Click here to goto home page</a>";
    }
    else{

        $retPerData=getUserPermissionForPage($_SESSION['user_id'],"users_php",$connection);
        if($retPerData!="-1" && $retPerData!="0"){
        

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
    <title>Users</title>
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


    function checkboxChecked(checkbox) {
                // Use the checkbox variable here       '
                checkboxChecked.id = checkboxChecked.id || [];      
                if (checkbox.checked) {                    
                    if (!checkboxChecked.id.includes(checkbox.id)) {
            checkboxChecked.id.push(checkbox.id); // Add ID to the array if it's not already present
        }
                    checkboxChecked.counter = (checkboxChecked.counter || 0) + 1;                
                } else {
                    const index = checkboxChecked.id.indexOf(checkbox.id);
        if (index !== -1) {
            checkboxChecked.id.splice(index, 1); // Remove ID from the array
        }
                    checkboxChecked.counter = (checkboxChecked.counter || 0) - 1;
                }            
            }

            function getAllCheckedCheckboxes() {
  const checkboxes = document.querySelectorAll('#userDataTable input[type="checkbox"]:checked');
  return Array.from(checkboxes);
}

            
        <?php 
        
            if(in_array("users_php_VIEW_USERS",$retPerData)){

        ?>

        function  remPerToUser(){
            // Removing Permission to users 
            var curPermissions=$("#curPer").val();  
            if(curPermissions.length>0){
                for(var i=0;i<curPermissions.length;i++){
                    $('#curPer option[value="'+  curPermissions[i]+'"]').remove();
                    const optionToAdd=$("<option>").attr("value",curPermissions[i]).text(curPermissions[i]);
                    $("#selectPer").append(optionToAdd);
                }
            }
            else{
                alert("Please Select Permissions to remove");
            }
            

        }

        function addPerToUser(){
            // Adding Permission to users

            var curPermissions=$("#selectPer").val();
            if(curPermissions.length>0){
                for(var i=0;i<curPermissions.length;i++){
                    $('#selectPer option[value="'+  curPermissions[i]+'"]').remove();
                    const optionToAdd=$("<option>").attr("value",curPermissions[i]).text(curPermissions[i]);
                    $("#curPer").append(optionToAdd);
                }
            }
            else{
                alert("Please Select Permissions to add");
            }
        }

   


function getCurrentPer(){
                // get All permission for user for selected page
                const perForPage=$("#selectInputForPerType").val();
                var error_res="";
                if(perForPage==="Select Permission For"){
                    error_res="<li>Select Properly</li>";
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                       const checkedCheckboxes = getAllCheckedCheckboxes();
        const checkedIds = checkedCheckboxes.map(checkbox => checkbox.id)[0];

                    var data_send={
                        "user_id":checkedIds,
                        'type':"per",
                        "perFor":perForPage
                    };

                    $.ajax({
                    type: "POST",
                    url: './queries/getUserData.php',
                    data:  {data:data_send},
                    success: function(response)
                    {
                        if(response.error_msg){
                            $("#users_details_body").empty();                                    
                            $("#users_details_body").append("No users associated with facility");
                        }
                        else{                                                                                                                                                                      
                                                        
                            
                            if($("#showCurPerRow")){
                                $("#showCurPerRow").remove();
                            }

                            const label=$("<label>").text('Current Permissions');
                            const row=$('<div>').addClass('row').attr("id","showCurPerRow");
                            const col=$('<div>').addClass('col-md-6');         
                                                                                
                            //const select=$('<select>').addClass('multi-select form-control').attr('id','curPer');
                            const select=$('<select>').addClass('select_size form-control').attr({
                                'id': 'curPer',
                                'multiple':'multiple',
                                'style': 'height:200px'
                                });
                            //.attr('id','curPer').attr('','multiple').attr('size','5');                                                     
                            $.each(response.users_data, function(index, getUserDataArray) {   
                                
                                    $.each(getUserDataArray, function(innerIndex, getUserData) {
                                        // Iterate over each object inside the inner arrays
                                        const option=$('<option>').attr('value',getUserData.permission_type).text(getUserData.permission_type);
                                            select.append(option); 
                                    });
                                                                                               
                            });

                            const centerTag=$("<center>");
                            const btnAddPer=$("<button>").text("Add Selected").attr({
                                "type":"button",
                                "onclick":"addPerToUser()"
                            });
                            const btnRemPer=$("<button>").text("Remove Selected").attr({
                                "type":"button",
                                "onclick":"remPerToUser()"
                            });                            

                            col.append(label)
                            col.append(select);
                            centerTag.append(btnAddPer).append(btnRemPer);
                            col.append(centerTag);                                
                            row.append(col);   
                            const col1=$('<div>').addClass('col-md-6');                            
                            const label1=$("<label>").text('Assign Permission/Choose from below');                            
                            const selectPer=$("<select>").text('Assign Permission').addClass('select_size form-control').attr({
                                'id': 'selectPer',
                                'multiple':'multiple',
                                'style': 'height:200px'
                                
                                });


                                let trackshipment_phpArr=[
  "trackshipment_php_TRACK_SHIPMENT",
  "trackshipment_php_UPDATE_SHIP_STATUS",
  "trackshipment_php_MODIFY_SHIP_DETAILS"
];
        let booking_phpArr=[
  "booking_php_BOOK_SHIP"
];
        let users_phpArr=[
  "users_php_CREATE_USER",
  "users_php_MANAGE_PERMISSIONS",
  "users_php_VIEW_USERS",
  "users_php_MANAGE_FACILITY",
  "users_php_REMOVE_USER_FROM_FAC"
];
        let facility_phpArr=[
  "facility_php_CREATE_FACILITY",
  "facility_php_VIEW_FACILITY",
  "facility_php_MODIFY_FAC_DETAILS",
  "facility_php_MANAGE_USERS",
  "facility_php_MANAGE_PERMISSION"
];

let reports_phpArr=[
  "reports_php_CREATE_REPORT",
  "reports_php_GET_USER_DATA"
];

                            let opts = null;

                            switch(perForPage){
                                case "trackshipment_php":
                                    opts=trackshipment_phpArr;
                                break;

                                case "booking_php":
                                    opts=booking_phpArr;
                                break;
                                case "facility_php":
                                    opts=facility_phpArr;
                                break;
                                case "users_php":
                                    opts=users_phpArr;
                                break;
                                case "reports_php":
                                    opts=reports_phpArr;
                                break;

                            }

                        
                            // Check if response.users_data is not empty
if (response.users_data && response.users_data.length > 0) {
  let userPermissions = response.users_data.flatMap(user => user.map(permission => permission.permission_type));
  let filteredOpts = opts.filter(opt => !userPermissions.includes(opt));

  filteredOpts.forEach(function(optk) {                                                                
                                const option=$('<option>').attr('value',optk).text(optk);
                                selectPer.append(option);
                            });   
  
  // Use filteredOpts as needed
  // ...
} else {
  // Handle the case where response.users_data is empty
  // ...

  opts.forEach(optk=> {                                                                
                                const option=$('<option>').attr('value',optk).text(optk);
                                selectPer.append(option);
                            });   
}
                            
                                                                                                                                                           

                            col1.append(label1);
                            col1.append(selectPer);
                            row.append(col1);
                            

                            //$("#actions_modal_title, #actions_modal_body").empty();                
                            //$("#actions_modal_title").append('Actions Tab - Edit/Remove Permission'); 
                            //$("#actions_modal_body").append(linkToFaqDocx);               
                            //$("#actions_modal_title").append('<hr>');
                            $("#actions_modal_body").append(row);
                            //$("#actions_modal").modal('show');
                            $("#userRemoveBtn").hide();
                            $("#perSaveBtn").show();
                            
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Ajax request failed with status: " + status + " and error: " + error);
                        // You can provide a more user-friendly error message or handle errors as needed.
                    }   
                });
                }
            }

        
        function selectSearchChange(){
            var error_res="";
            var state=$("#search_fac_state").val();
            if(state==="Select State"){
                error_res="<li>Select State Properly </li>";
            }

            if(error_res.length!=0){                    
                $("#modal_message").empty();
                $("#modal_message").append("<ul>"+error_res+"</ul>");
                $('#error_modal').modal('show');
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
                            $("#search_fac_state_facility_id").empty();
                            $('#search_fac_state_facility_id').prop('disabled', true);
                            alert(response.error_msg.error_msg);                                    
                        }
                        else{    
                            $('#search_fac_state_facility_id').prop('disabled', false);                        
                            $("#search_fac_state_facility_id").empty();
                            $("#search_fac_state_facility_id").append("\
                                <option value='Select Branch'>Select Branch</option>");

                            $.each(response.facility_data, function(index, getData) {
                                
                                $("#search_fac_state_facility_id").append("\
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
        
        


        function actionRemoveBtnClick(){
            var error_msg="";
            if(checkboxChecked.counter===0 || checkboxChecked.counter===undefined){   
                error_msg="No Users Selected!!";
            }
            /*else if(checkboxChecked.counter>1){
                error_msg="Only One Users at a time should be Selected for this operation!!";
            }*/
            
            if(error_msg!=0){
                $("#actions_modal_title, #actions_modal_body").empty();                
                $("#actions_modal").modal('show');
                $("#actions_modal_title").append('Actions Tab -Remove Users from facility');                
                $("#actions_modal_title").append('<hr>');                
                $("#actions_modal_body").append(error_msg);                            
            }
            else{

                const checkedCheckboxes = getAllCheckedCheckboxes();
        const checkedIds = checkedCheckboxes.map(checkbox => checkbox.id)[0];

                    var data_send={
                        user_id:checkedIds,
                        type:"per"
                    };


                    $("#actions_modal_title, #actions_modal_body").empty();                
                            $("#actions_modal_title").append('Actions Tab -Remove Users from facility');                
                            $("#actions_modal_title").append('<hr>');
                            const data_append=$("<p>").append(
                                "Are you sure you want to remove selected users from facility"+
                                "<br/>Total users selected = "+checkboxChecked.counter+
                                "<br/>Also the permissions assigned to these users will be removed"
                            );
                            $("#actions_modal_body").append(data_append);
                            $("#actions_modal").modal('show');                            
                    $("#perSaveBtn").hide();
                    $("#userRemoveBtn").show();                    


            }                        
        }

        function actionEditBtnClick(){
            var error_msg="";
            if(checkboxChecked.counter===0 || checkboxChecked.counter===undefined){   
                error_msg="No Users Selected!!";
            }
            else if(checkboxChecked.counter>1){
                error_msg="Only One Users at a time should be Selected for this operation!!";
            }
            
            if(error_msg!=0){
                $("#actions_modal_title, #actions_modal_body").empty();                
                $("#actions_modal").modal('show');
                $("#actions_modal_title").append('Actions Tab - Edit/Remove Permission');                
                $("#actions_modal_title").append('<hr>');                
                $("#actions_modal_body").append(error_msg);                            
            }
            else{
                                                   
                const linkToFaqDocx=$("<a>").attr({
                                "href":"./faq.php?topic_type=permission",
                                "target":"_blank"
                            }).text("Know more about permissions");                            

                const label=$("<label>").text('Permission For');
                const row=$('<div>').addClass('row');
                const col=$('<div>').addClass('col-md-12');  
                const selectInputForPerType=$("<select>").addClass("form-control").attr({
                    "id":"selectInputForPerType",
                    "onchange":"getCurrentPer()"                    
                });

                selectInputForPerType.empty();
                const arrForSelPerType=["Select Permission For","Book Shipment Page","Track Shipment Page","Users Page","Facility Page","Reports Page"];
                const arrForSelPerTypeID=["Select Permission For","booking_php","trackshipment_php","users_php","facility_php","reports_php"];                
                for(let incPer=0;incPer<arrForSelPerType.length;incPer++){
                    const optionForPerType=$("<option>").attr("value",arrForSelPerTypeID[incPer]).text(arrForSelPerType[incPer]);
                    selectInputForPerType.append(optionForPerType);                   
                }                

                col.append(label);
                col.append(selectInputForPerType);
                row.append(col);                
                
                $("#actions_modal_title, #actions_modal_body").empty();                
                    $("#actions_modal_title").append('Actions Tab - Edit/Remove Permission'); 
                    $("#actions_modal_body").append(linkToFaqDocx);               
                    $("#actions_modal_title").append('<hr>');
                    $("#actions_modal_body").append(row);
                    $("#actions_modal").modal('show');
                    $("#userRemoveBtn").hide();
                    $("#perSaveBtn").show();
             
            }

        }


        <?php } ?>


        $(document).ready(function() {
            

            <?php 
                if(in_array("users_php_CREATE_USER",$retPerData)){

                    if($_SESSION['type']==="SADMIN"){
            ?>

            $("#assignFacility").change(function(){
                if ($(this).is(':checked')) {
                    $("#assignUserFacRow").show();
                    
                    // Adding other options to state & country
                    const states = [
                                            "Select State", "Andhra Pradesh", "Andaman and Nicobar Islands", "Arunachal Pradesh", "Assam", "Bihar",
    "Chandigarh", "Chhattisgarh", "Dadar and Nagar Haveli", "Daman and Diu", "Delhi", "Lakshadweep",
    "Puducherry", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jammu and Kashmir", "Jharkhand",
    "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland",
    "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh",
    "Uttarakhand", "West Bengal"
  ];

  const selectApp = $('#assignUserFacState');  
  
  $.each(states, function(index, state) {    
    const option = $('<option>').attr("value",state).text(state);
    selectApp.append(option);
  });
                } else {
                    $("#assignUserFacRow").hide();      
                    $('#assignUserFacState').empty();
                }
            });


            $("#assignUserFacState").change(function(){                
                var error_res="";
                var state=$("#assignUserFacState").val();
                if(state==="Select State"){
                    error_res="<li>Select State Properly </li>";
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
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
                                $("#createUserForm #facility_id").empty();
                                $("#createUserForm #facility_id").prop("disabled",true);
                                alert(response.error_msg.error_msg);                                    
                            }
                            else{    
                                $("#createUserForm #facility_id").empty();
                                $("#createUserForm #facility_id").prop("disabled",false);
                                $("#createUserForm #facility_id").append("\
                                    <option value='Select Branch'>Select Branch</option>");

                                $.each(response.facility_data, function(index, getData) {
                                    
                                    $("#createUserForm #facility_id").append("\
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
            });


            <?php 
                }

            ?>

            $('#createUserForm').submit(function(e) {
                e.preventDefault();
                var formData = $('#createUserForm').serializeArray();
                
                var error_res="";                

                if(formData.find(field => field.name === "mobile_no").value.length!=10){
                    error_res=error_res+"<li>Enter Mobile Number Properly</li>";
                }
                
                if(formData.find(field => field.name === "type").value==="Select User Type"){
                    error_res=error_res+"<li>Incorrect Value for User Type</li>";
                }
                                
                if ($("#assignFacility").is(':checked')) {
                    if(formData.find(field => field.name === "assignUserFacState").value==="Select State"){
                        error_res=error_res+"<li>Select State Properly</li>";
                    }
                    else{
                        if(formData.find(field => field.name === "facility_id").value==="Select Branch"){
                            error_res=error_res+"<li>Select Branch Properly</li>";
                        }
                    }
                }
                
                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                
                
                    // Serialize the form data using jQuery                    

                    var formDataObject = {};
                    let hashedPass="";

                    $.each(formData, function(index, field) {
                        if(field!="password"){
                            formDataObject[field.name] = field.value;
                        }
                    });
                    
                        async function hashPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hash = await crypto.subtle.digest('SHA-256', data);    
    return hash;
}


async function storeHashedPassword() {
    try {
        const hashedPassword = await hashPassword($("#createUserForm #password"));
        // Convert the hashed password to a hexadecimal string
        const hashedPasswordHex = Array.from(new Uint8Array(hashedPassword))
            .map(byte => byte.toString(16).padStart(2, '0'))
            .join('');
        //hashedPass = hashedPasswordHex;        
        formDataObject["password"] = hashedPasswordHex;
    } catch (error) {
        console.error('Error hashing password:', error);
    }
}

// Call the function to store the hashed password
storeHashedPassword();

/*
hashPassword($("#createUserForm #password"))
    .then(hashedPassword => {
        // Convert the hashed password to a hexadecimal string
        const hashedPasswordHex = Array.from(new Uint8Array(hashedPassword))
            .map(byte => byte.toString(16).padStart(2, '0'))
            .join('');                                
    })
    .catch(error => {
        console.error('Error hashing password:', error);    
    });                    
                */ 

    //console.log(hashedPass);
      //              formDataObject["password"] = hashedPass;
                    // Convert the JSON object to a JSON string
                    var formDataJSON = JSON.stringify(formDataObject);                    
                                                     
                    $.ajax({
                        type: "POST",
                        url: './queries/createUser.php',
                        data:  {data: formDataJSON},
                        success: function(response)
                        {                                                        
                            var responseData = JSON.parse(response);
                            if(responseData[0].error_msg){                                
                                alert(responseData[0].error_msg);
                            }
                            else{
                                document.getElementById('createUserForm').reset();
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

            <?php } ?>

            <?php 

if(in_array("users_php_VIEW_USERS",$retPerData)){

?>   
            
            
            $("#perSaveBtn").click(function(){
                // per
                var perTypePage=$("#selectInputForPerType").val();
                var curPermissionsLength=$("#curPer option").length;
                
                var error_res="";
                var data_send="";   
                
                if(perTypePage==="Select Permission For"){
                    error_res="<li>Select Properly</li>";
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                

                const optionValues = $('#curPer').find('option').map(function() {
        return $(this).val();
    }).get();


                data_send={
                    "type":"update",
                    "user_id":checkboxChecked.id[0],
                    "permissions":optionValues,
                    "perType":perTypePage
                }
                    
                
                    $.ajax({
                        type: "POST",
                        url: './queries/managePermissions.php',
                        data:  {data: data_send},
                        success: function(response)
                        {                                                        
                            var responseData = JSON.parse(response);
                            if(responseData[0].error_msg){                                
                                alert(responseData[0].error_msg);
                            }
                            else{
                                /*actionEditBtnClick();*/
                                alert(responseData[0].ret_msg);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }
                    });                
                }


            })

            $("#userRemoveBtn").click(function(){
                var error_res="";

               

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                    const checkedCheckboxes = getAllCheckedCheckboxes();
        const checkedIds = checkedCheckboxes.map(checkbox => checkbox.id);

                    var data_send={
                        user_id:checkedIds                        
                    };

                    $.ajax({
                        type: "POST",
                        url: './queries/removeUserFromFac.php',
                        data:  {data: data_send},
                        success: function(response)
                        {                                                        
                            var responseData = JSON.parse(response);
                            if(responseData[0].error_msg){                                
                                alert(responseData[0].error_msg);
                            }
                            else{           
                                $("#modal_message").empty();
                            $("#modal_message").append(responseData[0].ret_msg);
                            $('#error_modal').modal('show');                                                                    

                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }
                    });
                }
                          
            });

            
            $("#searchBy").change(function(){          
                var searchBy=$("#searchBy").val();                          
                $("#searchUserFilterRow").empty();
                $("#searchUserBtn").prop("disabled",true);
                switch(searchBy){

                    case "search_by":
                        $("#modal_message").empty();
                        $("#modal_message").append("<ul>Properly specify how you want to search user</ul>");
                        $('#error_modal').modal('show');                        
                        $("#searchUserBtn").prop("disabled",true);
                    break;

                    case "email_id":
                        const colForEmail=$("<div>").addClass("col-md-4");
                        const inputDiv=$("<div>").addClass("form-group");
                        const inputEmailSearchLabel=$("<label>").text("User-ID/Email-ID (Required *)");
                        const inputEmailSearch=$("<input>").attr({
                            "type":"email",
                            "id":"search_email_id",
                            "placeholder":"Enter Email ID"
                        }).prop("required",true).addClass("form-control");
                        inputDiv.append(inputEmailSearchLabel);
                        inputDiv.append(inputEmailSearch);
                        colForEmail.append(inputDiv);
                        $("#searchUserFilterRow").append(colForEmail);
                        $("#searchUserBtn").prop("disabled",false);
                    break;

                    case "facility":
                        
                        // Create Search Form
                                                
                        const selectSearchLabel=$("<label>").text("Facility State (Required *)");
                        const selectSearch=$("<select>").attr({                            
                            "id":"search_fac_state",
                            "onchange":"selectSearchChange()"
                        }).addClass("form-control");
                        
                        const selectSearchLabelFac=$("<label>").text("Facility (Required *)");
                        const selectSearchFac=$("<select>").attr({                            
                            "id":"search_fac_state_facility_id",                            
                        }).addClass("form-control").prop("disabled",true);

                        const headersLabel = [selectSearchLabel, selectSearchLabelFac];
                        const headers = [selectSearch, selectSearchFac];

                        for(let i=0;i<2;i++){
                            var colForSearch=$("<div>").addClass("col-md-4");
                            var colForSearchDiv=$("<div>").addClass("form-group");
                            colForSearchDiv.append(headersLabel[i]).append(headers[i]);                            
                            colForSearch.append(colForSearchDiv);
                            $("#searchUserFilterRow").append(colForSearch);
                        }                                                                                            

                        const states = [
                                            "Select State", "Andhra Pradesh", "Andaman and Nicobar Islands", "Arunachal Pradesh", "Assam", "Bihar",
    "Chandigarh", "Chhattisgarh", "Dadar and Nagar Haveli", "Daman and Diu", "Delhi", "Lakshadweep",
    "Puducherry", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jammu and Kashmir", "Jharkhand",
    "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland",
    "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh",
    "Uttarakhand", "West Bengal"
  ];

  const selectSearchOptions = $('#search_fac_state');
  
  $.each(states, function(index, state) {    
    const option = $('<option>').attr("value",state).text(state);
    selectSearchOptions.append(option);
  });

                        $("#searchUserBtn").prop("disabled",false);

                        // Create Search Form                    

                    break;

                    case "list_all":                            
                        const colForListAll=$("<div>").addClass("col-md-12").append("<B>Click on below search button to list all users</B>");                            
                        $("#searchUserFilterRow").append(colForListAll);
                        $("#searchUserBtn").prop("disabled",false);
                    break;
                }                
            });
            

         


            $("#searchUserForm").submit(function(e){
                e.preventDefault();
                
                checkboxChecked.counter=0;

                var error_res="";
                var data_send="";

                var searchBy=$("#searchBy").val();
                switch(searchBy){
                    case "email_id":
                        if($("#searchUserForm #search_email_id").val().length===0){
                            error_res=error_res+"<li>Enter User-ID/Email-ID Properly</li>";
                        }
                        else{
                            data_send={
                                "type":"emailID",
                                "email_id":$("#searchUserForm #search_email_id").val()
                            };                        
                        }
                    break;

                    case "facility":
                        if($("#searchUserForm #search_fac_state_facility_id").val()==="Select Branch"){
                            error_res=error_res+"<li>Select Branch Properly</li>";
                        }
                        else{
                            data_send={
                                "type":"facID",
                                "facility_id":$("#searchUserForm #search_fac_state_facility_id").val()
                            };                        
                        }
                    break;

                    case "list_all":
                        data_send={
                            "type":"listAll",
                            "list_all":"yes"
                        };                        
                    break;

                }
                               
                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                    $.ajax({
                        type: "POST",
                        url: './queries/getUserData.php',
                        data:  {data:data_send},
                        success: function(response)
                        {                            
                            if(response.error_msg){ 
                                alert(response.error_msg);

                                if ($('#user_card_col').length > 0) {
    // Element with ID 'elementId' exists
    $("#user_card_col").remove();
}
                                

                            }
                            else{  
                                                              
                                
                                if ($('#user_card_col').length > 0) {
    // Element with ID 'elementId' exists
    $("#user_card_col").remove();
}


                                const user_card_col=$("<div>").addClass("col-md-12").attr("id","user_card_col");
                                const user_card=$("<div>").addClass("card");
                                const user_card_header=$("<div>").addClass("card-header");
                                const user_card_body=$("<div>").addClass("card-body");
                                const user_card_header_h4=$("<h4>").addClass("card-title").append("User Details");
                                user_card_header.append(user_card_header_h4);
                                user_card.append(user_card_header);
                                user_card.append(user_card_body);
                                user_card_col.append(user_card);                                
                                $("#search_collapse_body").append(user_card_col);
                                                                                                                                            
                                // Create the elements                                       
                                const divCardBody = $('<div>').addClass('table-responsive').attr({
                                    "style":"margin-top:15px"
                                });
                                const table = $('<table>').addClass('').attr('id','userDataTable');
                                const thead = $('<thead>');
                                const tbody = $('<tbody>').attr('id', 'fac_users_table');
                                const headers = ['first_name', 'last_name', 'mobile_no', 'email_id', 'type', 'status'];
                                
                                // Create the header row
                                const headerRow = $('<tr>');
                                headerRow.append("<th>");
                                headers.forEach(headerText => {
                                    const th = $('<th>').text(headerText);
                                    headerRow.append(th);
                                });                        
                                thead.append(headerRow);                                
                                // Populate table with data from response.users_data
                                $.each(response.users_data, function(index, getUserData) {
                                    if (index < 10) { // Limiting to first 10 rows for overflow
                                        const row = $('<tr>');
                                        const checkbox=$('<input>').attr('type','checkbox').attr('id',getUserData.user_id).attr('onchange','checkboxChecked(this)');                            
                                        const cell0=$('<td>');
                                        cell0.append(checkbox);
                                        row.append(cell0);                            
                                        // Assuming getData is an object with properties corresponding to table headers
                                        headers.forEach(header => {
                                            const cell = $('<td>').text(getUserData[header.toLowerCase()].toLowerCase());
                                            row.append(cell);
                                        });                                                                                      
                                        
                                        tbody.append(row);
                                    }
                                });
                        
                                // Assemble the elements
                                table.append(thead);
                                table.append(tbody);
                                                    
                                const editBtn=$('<button>').attr('type','button').attr('id','actionEditBtn').addClass('btn btn-info').attr('onclick','actionEditBtnClick()').append('Edit/Remove Permissions');
                                const delBtn=$('<button>').attr('type','button').attr('id','actionRemoveBtn').addClass('btn btn-danger').attr('onclick',"actionRemoveBtnClick()").append('Remove User');

                                const action_col=$('<div>').addClass('col-md-3').append(editBtn);
                                const action_col1=$('<div>').addClass('col-md-3').append(delBtn);                    
                                const action_row=$('<div>').addClass('row').append(action_col).append(action_col1);
                                                  
                                divCardBody.append(table);         
                                user_card_body.append(action_row);                       
                                user_card_body.append(divCardBody); 
                                                      
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
            ?>
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
                    <?php

include("./includes/quick_links.php");

?>

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

                                    <?php 
                                    
                                        if($retPerData!="-1" && $retPerData!="0"){
                                            
                                            
                                            if(in_array("users_php_VIEW_USERS",$retPerData)){                                                                                        
                                            ?>
                                            
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
                                                                    <?php if($_SESSION['type']==="SADMIN") { ?>
                                                                    <option value="facility">Facility</option>
                                                                    <?php } ?>
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
                                    <?php
                                        }
                                        else{
                                            echo "You do not have permission to search users";
                                        }                                                                           
                                    ?>                                
                                    </div>
                                </div>
                                <div class="tabcontent" id="create_collapse_body">
                                    <div class="card-body">       
                                        <?php 

                                            if(in_array("users_php_CREATE_USER",$retPerData)){

                                            ?>
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
                                                                        <label>Type <span style="color:red;font-weight:bold">*</span><a href='./faq.php?topic_type=users&sub_topic=create_user' target='_blank'> (For More info on user type Click Here)</a></label>
                                                                        <select class="form-control" id="type" name="type">
                                                                            <option value="Select User Type">Select User Type</option>
                                                                            <option value="NUSER">Normal User</option>
                                                                            <?php
                                                                            if($_SESSION['type']==="SADMIN"){
                                                                            ?>
                                                                            <option value="FADMIN">Facility Admin</option>                                                                                                                                                        
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <option value="DUSER">Delivery Person</option>                                                                            
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
                                                            
                                                            <?php 
                                                                if($_SESSION['type']==="SADMIN"){

                                                            ?>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">                                                                        
                                                                        <input type="checkbox" id="assignFacility" name="assignFacility"> Assign Branch
                                                                    </div>
                                                                </div>                                                                
                                                            </div>    
                                                            <!-- Assign Branch Row -->
                                                            <div class="row" id="assignUserFacRow" style='display:none'>
                                                                <div class="col-md-12">
                                                                    <div class="card table-plain-bg">  
                                                                        <div class="card-header ">
                                                                            <h5 class="card-title">Assign Branch</h5>
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
                                                                                    <label for='facility_id'>Branch</label>
                                                                                    <select class='form-control' id='facility_id' name='facility_id' disabled>
                                                                                    </select>
                                                                                </div>                                        
                                                                            </div>
                                                                        </div>  
                                                                    </div>
                                                                </div>
                                                                <!-- Shipment Status Update card -->
                                                            </div>    
                                                            <!-- Assign Branch Row -->      
                                                            <?php 
                                                            
                                                                }

                                                            ?>                                
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
                                    <?php
                                }
                                            else{
                                                echo "You do not have permission to create users";
                                            }
                                        }

                                        ?>                                                                                                                                               
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


<?php } }?>