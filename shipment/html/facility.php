<?php

    session_start();
    if(!isset($_SESSION['isSession'])){
        header("location:./login.php");
    }

    require("./includes/db_connect.php");
    require("./includes/check_permission.php");

    $resForPagePer=checkPermission($_SESSION['user_id'],"facility_php",$connection);
    if($resForPagePer==="0"){
        echo "You do not have permission to this page, Please contact your administrator for any query<br/>";       
        echo "<a href='./index.php'>Click here to goto home page</a>";
    }
    else{

        $retPerData=getUserPermissionForPage($_SESSION['user_id'],"facility_php",$connection);
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
    <title>Facility</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />    
    <link href="../assets/css/light-bootstrap.css" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/css/demo.css" rel="stylesheet" />
    <script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
    <link href="../assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="../assets/js/custom.js" type="text/javascript"></script>

    <style>
            
    .select_size {
        height: 200px; /* Adjust the height as needed */
    }
    </style>




    <script type="text/javascript">        

window.onload = function() {    
            const params = new URLSearchParams(window.location.search);            
            const param_act = params.get('act');             
            if(param_act==="searchFac"){                
                $('#searchFacility').collapse('show'); 
                $('#createFacility').collapse('hide'); 
            }
            else if(param_act==="createFac"){
                $('#searchFacility').collapse('hide'); 
                $('#createFacility').collapse('show');                 
            }
        };

        function checkboxChecked(checkbox) {
            // Use the checkbox variable here            
            if (checkbox.checked) {
                checkboxChecked.counter = (checkboxChecked.counter || 0) + 1;                
            } else {
                checkboxChecked.counter = (checkboxChecked.counter || 0) - 1;
            }            
        }



        function modifyBtnClicked(){

            var error_res="";
            var state=$("#fac_details_show_form #state").val();
            var country=$("#fac_details_show_form #country").val();
            if(state==="Select State"){
                error_res="<li>Select State Properly </li>";
            }
            if(country==="Select Country"){
                error_res="<li>Select Country Properly </li>";
            }

            if(error_res.length!=0){                    
                $("#modal_message").empty();
                $("#modal_message").append("<ul>"+error_res+"</ul>");
                $('#error_modal').modal('show');
            }
            else{

                var formData = $('#fac_details_show_form').serializeArray();
                // Convert the serialized form data to a JSON object
                var formDataObject = {};
                formDataObject["facility_id"] = $("#fac_details_show_form #facility_id").val();
                    $.each(formData, function(index, field) {
                        formDataObject[field.name] = field.value;
                    });

                    // Convert the JSON object to a JSON string
                    var formDataJSON = JSON.stringify(formDataObject);       
                                             
                $.ajax({
                    type: "POST",
                    url: './queries/updateFac.php',
                    data:  {data:formDataJSON},
                    success: function(response)
                    {

                        var responseData = JSON.parse(response);
                        if(responseData[0].error_msg){
                            alert(responseData[0].error_msg);
                        }
                        else{
                            alert(responseData[0].ret_msg);
                        }

                        /*if(response.error_msg){                            
                            alert(response.error_msg.error_msg);                                    
                        }
                        else{    
                            alert(response.ret_msg.ret_msg);                               
                        }*/
                    },
                    error: function (xhr, status, error) {
                        console.log("Ajax request failed with status: " + status + " and error: " + error);
                        // You can provide a more user-friendly error message or handle errors as needed.
                    }   
                });         
            }
        }

        function getAllCheckedCheckboxes() {
  const checkboxes = document.querySelectorAll('#userDataTable input[type="checkbox"]:checked');
  return Array.from(checkboxes);
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

                window.location.href = './users.php?user_id='; 

               /* const checkedCheckboxes = getAllCheckedCheckboxes();
        const checkedIds = checkedCheckboxes.map(checkbox => checkbox.id)[0];

                    var data_send={
                        user_id:checkedIds,
                        type:"per"
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
                            
                            const label=$("<label>").text('Current Permissions');
                            const row=$('<div>').addClass('row');
                            const col=$('<div>').addClass('col-md-6');         
                                                                                
                            //const select=$('<select>').addClass('multi-select form-control').attr('id','curPer');
                            const select=$('<select>').addClass('select_size form-control').attr({
                                'id': 'curPer',
                                'multiple':'multiple',
                                'size': '3'
                                });
                            //.attr('id','curPer').attr('','multiple').attr('size','5');                                                     
                            $.each(response.users_data, function(index, getUserDataArray) {   
                                
                                    $.each(getUserDataArray, function(innerIndex, getUserData) {
                                        // Iterate over each object inside the inner arrays
                                        const option=$('<option>').attr('value',getUserData.permission_id).text(getUserData.permission_type);
                                            select.append(option); 
                                    });
                                                                                               
                            });
                            col.append(label)
                            col.append(select);
                            row.append(col);   
                            const col1=$('<div>').addClass('col-md-6');                            
                            const label1=$("<label>").text('_Assign Permission');                            
                            const selectPer=$("<select>").text('_Assign Permission');

                            let opts = [];
                            
                            opts.forEach(function(opt) {
                                opt
                            });

                            col1.append(label1);
                            row.append(col1);
                            

                            $("#actions_modal_title, #actions_modal_body").empty();                
                            $("#actions_modal_title").append('Actions Tab - Edit/Remove Permission');                
                            $("#actions_modal_title").append('<hr>');
                            $("#actions_modal_body").append(row);
                            $("#actions_modal").modal('show');
                            
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Ajax request failed with status: " + status + " and error: " + error);
                        // You can provide a more user-friendly error message or handle errors as needed.
                    }   
                });*/
            }

        }

        function addUsrToFacMethod(){
                // Add User To Current Facility
                var search_email_id=$("#addUsrSearchEmail").val();
                var type_of_usr=$("#addUsrTypeOfUsr").val();
                var error_res="";
                if(search_email_id===null || search_email_id===undefined || search_email_id===""){
                    error_res+="Enter Email-ID Properly, ";
                }

                if(type_of_usr===null || type_of_usr===undefined || type_of_usr==="Select User Type"){
                    error_res+="Select User Type Properly";
                }
                if(error_res.length!=0){                    
                    /*$("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');*/
                    alert(error_res);
                }
                else{
                    var data_send={
                        email_id:search_email_id,
                        type:type_of_usr
                    };

                                       
                    $.ajax({
                        type: "POST",
                        url: './queries/facility_add_usr.php',
                        data:  {data:data_send},
                        success: function(response)
                        {
                            var jsonObject = JSON.parse(response);
                            alert(jsonObject.error_msg);                            
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }   
                    });       
                }


            }
        
        function addUsrToFac(){

            const addUsrBodyRow=$("<div>").addClass("row");
            const addUsrBodyCol=$("<div>").addClass("col-md-6");
            const addUsrSearchInputLabel=$("<label>").append("Enter User-ID/Email-ID");
            const addUsrSearchInput=$("<input>").attr({
                "type":"email",
                "id":"addUsrSearchEmail",
                "placeholder":"Enter User-ID/Email-ID"
            }).prop("required","true").addClass("form-control");            
            const hyperLinkToCreateNewUser=$("<a>").attr({
                "href":"./users.php",
                "target":"_blank"
            }).text("Create a new User");
            const addUsrTypeOfUsrSelectLabel=$("<label>").append("Select User Type/Role");
            const addUsrTypeOfUsrSelect=$("<select>").attr({
                "id":"addUsrTypeOfUsr"
            }).addClass("form-control").append("\
                <option value='Select User Type'>Select User Type</option>\
                <option value='NUSER'>Normal User</option>\
                <?php 
                
                    if($_SESSION['type']==="SADMIN"){
                        echo "<option value='FADMIN'>Facility Admin</option>";
                    }
                ?>
                <option value='DUSER'>Delivery Person</option>\
            ");
            addUsrBodyCol.append(addUsrSearchInputLabel).append(addUsrSearchInput).append(hyperLinkToCreateNewUser);
            const addUsrBodyCol1=$("<div>").addClass("col-md-6");
            addUsrBodyCol1.append(addUsrTypeOfUsrSelectLabel).append(addUsrTypeOfUsrSelect);
            addUsrBodyRow.append(addUsrBodyCol);
            addUsrBodyRow.append(addUsrBodyCol1);
            $("#actions_modal_title, #actions_modal_body").empty();                
                $("#actions_modal").modal('show');
                $("#actions_modal_title").append('Actions Tab - Add User to Facility');                
                $("#actions_modal_title").append('<hr>');                
                $("#actions_modal_body").append(addUsrBodyRow);   
                
                if ($('#actionSaveBtn').prop('onclick')) {
    // If onclick exists, remove it and add the new click function
    $('#actionSaveBtn').off('click').on('click', addUsrToFacMethod);
} else {
    // If onclick doesn't exist, simply add the new click function
    $('#actionSaveBtn').on('click', addUsrToFacMethod);
}

                        
               /* const checkedCheckboxes = getAllCheckedCheckboxes();
        const checkedIds = checkedCheckboxes.map(checkbox => checkbox.id)[0];

                    var data_send={
                        user_id:checkedIds,
                        type:"per"
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
                            
                            const label=$("<label>").text('Current Permissions');
                            const row=$('<div>').addClass('row');
                            const col=$('<div>').addClass('col-md-6');         
                                                                                
                            //const select=$('<select>').addClass('multi-select form-control').attr('id','curPer');
                            const select=$('<select>').addClass('select_size form-control').attr({
                                'id': 'curPer',
                                'multiple':'multiple',
                                'size': '3'
                                });
                            //.attr('id','curPer').attr('','multiple').attr('size','5');                                                     
                            $.each(response.users_data, function(index, getUserDataArray) {   
                                
                                    $.each(getUserDataArray, function(innerIndex, getUserData) {
                                        // Iterate over each object inside the inner arrays
                                        const option=$('<option>').attr('value',getUserData.permission_id).text(getUserData.permission_type);
                                            select.append(option); 
                                    });
                                                                                               
                            });
                            col.append(label)
                            col.append(select);
                            row.append(col);   
                            const col1=$('<div>').addClass('col-md-6');                            
                            const label1=$("<label>").text('_Assign Permission');                            
                            const selectPer=$("<select>").text('_Assign Permission');

                            let opts = [];
                            
                            opts.forEach(function(opt) {
                                opt
                            });

                            col1.append(label1);
                            row.append(col1);
                            

                            $("#actions_modal_title, #actions_modal_body").empty();                
                            $("#actions_modal_title").append('Actions Tab - Edit/Remove Permission');                
                            $("#actions_modal_title").append('<hr>');
                            $("#actions_modal_body").append(row);
                            $("#actions_modal").modal('show');
                            
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Ajax request failed with status: " + status + " and error: " + error);
                        // You can provide a more user-friendly error message or handle errors as needed.
                    }   
                });*/
            }


         
        
        
            // Tab JS

            function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";}
        
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace("active", "");}

document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";}

    // Tab JS
  

    function facModifyCheckClicked(){
        $("#modifyBtn").prop("disabled",false);

        const isChecked = $(this).is(':checked');
        if($("#modifyCheck").is(':checked')){
            $('#fac_details_show_form input, #fac_details_show_form textarea, #fac_details_show_form button, #fac_details_show_form select').prop('disabled', false);                             
            $('#fac_details_show_form #facility_id').prop('disabled', true);                             

        }            
        else{
            $('#fac_details_show_form input, #fac_details_show_form textarea, #fac_details_show_form button ,#fac_details_show_form select').prop('disabled', true);                        
            $('#modifyCheck').prop('disabled', false);            
        }

    }


    function getUsersList(){

        var data_send={
            facility_id:$("#facility_id").val()
        };

        $.ajax({
            type: "POST",
            url: './queries/getUsersList.php',
            data:  {data:data_send},
            success: function(response)
            {
                if(response.error_msg){
                    $("#users_details_body").empty();                                    
                    $("#users_details_body").append("No users associated with facility");
                }
                else{                                                                                                  
                    $("#users_details_body").empty();                                                        
                    
                    // Create the elements
                    const divCardBody = $('<div>').addClass('card-body table-full-width table-responsive');
                    const table = $('<table>').addClass('table table-hover').attr('id','userDataTable');
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
                                        
                    const addUsrBtn=$("<button>").attr({
                        'type':'button',
                        'id':'actionAddUsrBtn',
                        "onclick":"addUsrToFac()",
                        "style":"margin:4px"
                    }).addClass('btn btn-success').append("Add User");
                    const editBtn=$('<button>').attr({
                        'type':'button',
                        'onclick':'actionEditBtnClick()',
                        "id":'actionEditBtn',
                        "style":"margin:4px"
                    }).addClass('btn btn-info').append('Edit/Remove Permissions');
                    const delBtn=$('<button>').attr({
                        'type':'button',
                        'id':'actionRemoveBtn',
                        "style":"margin:4px",
                        "onclick":"removeUsrFromFac()"
                    }).addClass('btn btn-danger').append('Remove User');
                                        
                    const action_col=$('<div>').addClass('col-md-12').append(addUsrBtn).append(editBtn).append(delBtn);                                     
                    const action_row=$('<div>').addClass('row').append(action_col);
                    
                    divCardBody.append(action_row);                    
                    divCardBody.append(table);

                    // Append to the #users_details_body container
                    $('#users_details_body').append(divCardBody);                        
                }
            },
            error: function (xhr, status, error) {
                console.log("Ajax request failed with status: " + status + " and error: " + error);
                // You can provide a more user-friendly error message or handle errors as needed.
            }   
        });
    }


            function fetch_fac_list(){
                var error_res="";
                var state=$("#filter_state").val();
                var country=$("#filter_country").val();
                var city=$("#filter_city").val();                
                if($("#filter_country")==="Select Country"){
                    error_res="<li>Select Country Properly </li>";
                }
                if(state==="Select State"){
                    error_res="<li>Select State Properly </li>";
                }
                if(city==="Select City"){
                    error_res="<li>Select City Properly </li>";
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
                        data:  {facilityCountry:country,facilityState:state,facilityCity:city},
                        success: function(response)
                        {
                            if(response.error_msg){
                                $("#filter_select_fac").empty();
                                $('#filter_select_fac').prop('disabled', true);
                                alert(response.error_msg.error_msg);                                    
                            }
                            else{    
                                $('#filter_select_fac').prop('disabled', false);                        
                                $("#filter_select_fac").empty();
                                $("#filter_select_fac").append("\
                                    <option value='Select Facility'>Select Facility</option>");

                                $.each(response.facility_data, function(index, getData) {
                                    
                                    $("#filter_select_fac").append("\
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

            const getConForCreateFac=$("#createFacilityForm #country");   
            getCountry(getConForCreateFac);

            const getConForSearchFac=$("#search_facility_form #filter_country");   
            getCountry(getConForSearchFac);


            $("#search_facility_form #filter_country").change(function(){
                //
                var error_res="";
                var basedOn=$("#search_facility_form #filter_country").val();

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
                    const createFacStateField=$("#search_facility_form #filter_state");
                    getConData(data_send,createFacStateField,"State");
                }    
            });


            $("#search_facility_form #filter_state").change(function(){
                var error_res="";
                var basedOn=$("#search_facility_form #filter_state").val();

                if(basedOn==="Select State"){
                    error_res="Select State Properly";
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
                    const searchFacCityField=$("#search_facility_form #filter_city");
                    getConData(data_send,searchFacCityField,"City");
                }    
            });


            $("#createFacilityForm #country").change(function(){
                var error_res="";
                var basedOn=$("#createFacilityForm #country").val();

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
                                $("#createFacilityForm #state").empty();
                            }
                            else if (response && response.data) {                                                                                                    
                                
                            const senderStateField=$("#createFacilityForm #state");                    
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


            $("#createFacilityForm #state").change(function(){                
                
                var error_res="";
                var basedOn=$("#createFacilityForm #state").val();

                if(basedOn==="Select State"){
                    error_res="Select State Properly";
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
                    const createFacCityField=$("#createFacilityForm #city");
                    getConData(data_send,createFacCityField,"State");
                }
            });

            $('.multi-select').select2({
                placeholder: 'Select multiple options',        
                width: '100%' 
            });

            $("#search_facility_form").submit(function(e){
                e.preventDefault();
                
                var data_send="";
                var error_res="";
                if ($('#searchFilters').is(':checked')){
                    if($('#filter_state').val()==="Select State" || $("#filter_country").val()==="Select Country" ||
                        $("#filter_city").val()==="Select City"
                    ){
                        error_res="<li>Enter City, State & Country Properly</li>";
                    }   
                    else if($("#filter_select_fac").val()==="Select Facility"){
                        error_res="<li>Select Facility Properly </li>";
                    }
                    else{                                                                       
                        var data_send = {
                            type: 'facId',
                            facility_id: $("#filter_select_fac").val()
                        };
                    }                                     
                }
                else{
                    if($("#filter_facility_name").val().length>0){
                        var data_send = {
                            type: 'facName',
                            facility_name: $("#filter_facility_name").val()
                        };
                    }
                    else{
                        error_res="<li>Enter Facility name Properly</li>";
                    }                    
                }

                if(error_res.length!=0){                    
                    $("#modal_message").empty();
                    $("#modal_message").append("<ul>"+error_res+"</ul>");
                    $('#error_modal').modal('show');
                }
                else{
                    $.ajax({
                        type: "POST",
                        url: './queries/getFacilityData.php',
                        data:  {data: data_send},
                        success: function(response)
                        {                  

                            if (Array.isArray(response) && response.length > 0 && 'error_msg' in response[0]) {
                                $("#fac_details").hide();
                                $("#fac_details_body").empty();
                                alert(response[0].error_msg);
                            } 
                            else if (typeof response === 'object' && response !== null && 'facility_data' in response) {
                                                                              
                                //$('#searchFacility').collapse('hide');             
                                $("#fac_details").show();
                                $("#fac_details_body").empty();
                                                    
                                // Dynamic Form
                                
                                const formatDataArray = response.format_data;                                                                
                                const inputArray = formatDataArray[0].input;
                                const selectArray = formatDataArray[1].select;     
                                                                                               

                                    var form = $("<form>").attr({
                                        "id":'fac_details_show_form',
                                        "method":'post'
                                    });
                                    // id='fac_details_show_form' method='post'></form>");                                    

                                    var row;
                                    var elementsInARow = 4; // Number of elements in each row
                                    var lastPos=0;

                                    //<button class="btn btn-fill" id="modifyBtn" style="margin:1px" disabled>Modify</button>
                                                                                                           
                                    inputArray.forEach(function(element, index) {
                                        // Create a new row for every 'elementsInARow' elements                                        

                                        if (index % elementsInARow === 0) {
                                            row = $("<div class='row'></div>");
                                            form.append(row);
                                        }
                                        
                                        if(element!="admin_user_id" && element!="entry_create_date"){
                                            var col = $("<div class='col-md-3'><div class='form-group'></div></div>");
                                            var label = "<label for='" + element + "'>" + element + "</label>";
                                            var input = $("<input type='text' class='form-control' id='" + element + "' name='" + element + "' required disabled>");
                                            col.find('.form-group').append(label);
                                            col.find('.form-group').append(input);
                                            row.append(col);
                                        }
                                        lastPos=index;
                                    });
                                    
                                    selectArray.forEach(function(element, index) {
                                        // Create a new row for every 'elementsInARow' elements
                                        if ((index+lastPos) % elementsInARow === 0) {
                                            row = $("<div class='row'></div>");
                                            form.append(row);
                                        }

                                        var col = $("<div class='col-md-3'><div class='form-group'></div></div>");
                                        var label = "<label for='" + element + "'>" + element + "</label>";
                                        //var input = $("<input type='text' class='form-control' id='" + element + "' name='" + element + "' required disabled>");
                                        //var input = $("<select class='form-control' id='" + element + "' name='" + element + "' required disabled>");
                                        var input = $("<select>").attr({                                                            
                                                            "id":element,
                                                            "name":element
                                                        })
                                                        .prop({
                                                            "disabled":true
                                                        })
                                                        .addClass("form-control");

                                        col.find('.form-group').append(label);
                                        col.find('.form-group').append(input);
                                        row.append(col);
                                    });
                                
                                    const modifyCheck=$("<input>").attr({
                                        "id":"modifyCheck",
                                        "type":"checkbox",
                                        "onclick":"facModifyCheckClicked()"                                
                                    });
                                    const checkLabel=$("<label>").text("_Modify Details");
                                    const colCheck=$("<div>").addClass("col-md-12");                                                                 
                                    colCheck.append(modifyCheck).append(checkLabel);                                                                        

                                    const rowBtn=$("<div>").addClass("row");                                    
                                    const rowCheck=$("<div>").addClass("row");                                    
                                    rowCheck.append(colCheck);

                                    const button=$("<button>").addClass("btn btn-success").attr({
                                        "id":"modifyBtn",
                                        "type":"button",
                                        "onclick":"modifyBtnClicked()"
                                    }).prop("disabled",true).text("Modify Details");
                                    const colBtn=$("<div>").addClass("col-md-12");                                    
                                    colBtn.append(button);                                    
                                    rowBtn.append(colBtn);                                    
                                    form.append(rowCheck);
                                    form.append(rowBtn); 

                                    // Append the form to an element in the HTML body or wherever needed
                                    $("#fac_details_body").append(form);

                                        // Adding other options to state & country
                                        const states = [
                                            "Select State", "Andhra Pradesh", "Andaman and Nicobar Islands", "Arunachal Pradesh", "Assam", "Bihar",
    "Chandigarh", "Chhattisgarh", "Dadar and Nagar Haveli", "Daman and Diu", "Delhi", "Lakshadweep",
    "Puducherry", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jammu and Kashmir", "Jharkhand",
    "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland",
    "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh",
    "Uttarakhand", "West Bengal"
  ];

  const selectApp = $('#fac_details_show_form #state');  
  
  $.each(states, function(index, state) {    
    const option = $('<option>').attr("value",state).text(state);
    selectApp.append(option);
  });



  const countries = [
  "INDIA","Select Country","Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia",
  "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium",
  "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria",
  "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic",
  "Chad", "Chile", "China", "Colombia", "Comoros", "Congo (Congo-Brazzaville)", "Costa Rica", "Croatia", "Cuba",
  "Cyprus", "Czechia (Czech Republic)", "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica",
  "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini (fmr. Swaziland)",
  "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala",
  "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Holy See", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran",
  "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan",
  "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi",
  "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova",
  "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar (formerly Burma)", "Namibia", "Nauru", "Nepal",
  "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman",
  "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland",
  "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines",
  "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore",
  "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan",
  "Suriname", "Sweden", "Switzerland", "Syria", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago",
  "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America",
  "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
];

const selectCountry = $('#fac_details_show_form #country'); // Assuming you have a select element with ID 'country'

countries.forEach(function(country) {
  const option = $('<option>').attr("value", country).text(country);
  selectCountry.append(option);
});


// Adding other options to state & country

                                                                        
                                    // Populating the form inputs with data from response.facility_data
                                    $.each(response.facility_data, function(index, fac_dt) {
                                        $.each(fac_dt, function(key, value) {
                                            $("#fac_details_show_form input[name='" + key + "']").val(value);
                                            $("#fac_details_show_form select[name='" + key + "']").val(value);
                                            if(key==="facility_id" || key==="ENTRY_CREATE_DATE" || key===""){

                                            }
                                        });                                        
                                    });    
                                                                        
                                    $('#searchFacility').collapse('hide');                                                                        
                                    getUsersList();
                                    //$("#fac_collapse_body_btn").click();
                                    // Dynamic Form     
                                                                   
                            }
                            
                        
                            else {
                                alert("Error");                                
                            }

                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }
                    });
                }
                
                
            });
                
            $("#searchFilters").click(function(){
                if ($('#searchFilters').is(':checked')){
                    $("#search_filters_row0").hide();
                    $("#filter_facility_name").prop("disabled",true);
                    $("#search_filters_row").show();
                    $("#filter_facility_name").val("");                    
                }
                
                else{
                    $("#search_filters_row").hide();                
                    $("#search_filters_row0").show();
                    $("#filter_facility_name").prop("disabled",false);
                }
            });

            $('#searchFacilityBtn').click(function(){
                $('#createFacility').collapse('hide');
            });

            $('#createFacilityBtn').click(function(){
                $('#searchFacility').collapse('hide');
            });

            $("#createFacilityForm").submit(function(e){
                e.preventDefault();   

                var formData = $('#createFacilityForm').serializeArray();                
                
                var error_res="";                
                                                
                if(formData.find(field => field.name === "state").value==="Select State"){
                    error_res=error_res+"<li>Select State Properly</li>";
                }
                 
                if(formData.find(field => field.name === "country").value==="Select Country"){
                    error_res=error_res+"<li>Select Country Properly</li>";
                }   
                
                if(formData.find(field => field.name === "pincode").value.length!=6){
                    error_res=error_res+"<li>Enter Pincode Properly</li>";
                }

                if(formData.find(field => field.name === "addAdmin")){                    
                    if(formData.find(field => field.name === "addAdmin").value==="on"){                        
                        if(formData.find(field => field.name === "admin_user_id").value==="Select Admin"){                            
                            error_res=error_res+"<li>Select Admin Properly</li>";
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

                    // Convert the serialized form data to a JSON object
                    var formDataObject = {};
                    $.each(formData, function(index, field) {
                        formDataObject[field.name] = field.value;
                    });

                    // Convert the JSON object to a JSON string
                    var formDataJSON = JSON.stringify(formDataObject);                    
                                                     
                    $.ajax({
                        type: "POST",
                        url: './queries/createFacility.php',
                        data:  {data: formDataJSON},
                        success: function(response)
                        {                                                                                                                                      
                            if(response.error_msg){                                
                                alert(response.error_msg);
                            }
                            
                            else{
                                //alert("hello");
                                //openCity(event, 'users_collapse_body');
                                $("#createFacilityForm").trigger("reset");
                                alert(response.ret_msg);    
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }
                    });
                }
                
            });                    

            $("#addAdmin").click(function(){                              
                
                // Check if the checkbox with id "addUsers" is checked
                if ($('#addAdmin').is(':checked')) {
                    $("#facility_admin_row").show();
                    
                    // List All Users IDs with name and Email 

                    $.ajax({
                            type: "POST",
                            url: './queries/getUsersList.php',
                            //data:  {facilityState:state},
                            success: function(response)
                            {
                                if(response.error_msg){
                                    $("#admin_user_id").empty();                                    
                                    alert(response.error_msg.error_msg);                                    
                                }
                                else{                                                                                                  
                                    $("#admin_user_id").empty();
                                    $("#admin_user_id").append("\
                                        <option value='Select Admin'>Select Admin</option>");

                                    $.each(response.users_data, function(index, getData) {
                                        
                                        $("#admin_user_id").append("\
                                            <option value="+getData.user_id+">"+getData.first_name+" "+getData.last_name+" : "+getData.email_id+"</option>");
                                        });
                                        
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log("Ajax request failed with status: " + status + " and error: " + error);
                                // You can provide a more user-friendly error message or handle errors as needed.
                            }   
                        });
                        
                    
                } else {
                    $("#facility_admin_row").hide();
                    $("#admin_user_id").empty();
                }

            });

                    
        });


    </script>   
   <style>
 /* Style for the custom select */
.custom-select {
  position: relative;
  display: inline-block;
}

/* Style for the selected option */
.select-selected {
  background-color: #f1f1f1;
  padding: 8px 16px;
  border: 1px solid #ccc;
  cursor: pointer;
}

/* Style for the options */
.select-items {
  display: none;
  position: absolute;
  background-color: #fff;
  min-width: 100%;
  max-height: 200px;
  overflow-y: auto;
  border: 1px solid #ccc;
  z-index: 1;
}

.select-items div {
  padding: 8px 16px;
  cursor: pointer;
}

.select-items div:hover {
  background-color: #f1f1f1;
}

   </style>
    
</head>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
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
        <button type="button" class="btn btn-primary" id="actionSaveBtn">Save changes</button>
      </div>
    </div>
  </div>
</div>




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
                                <div class="tab">
                                    <button class="tablinks" id="fac_collapse_body_btn" onclick="openCity(event, 'search_collapse_body')">Search Branch</button>
                                    <button class="tablinks" id="users_collapse_body_btn" onclick="openCity(event, 'create_collapse_body')">Create Branch</button>                                    
                                </div>      
                                <hr/>                                  
                            </div>
                            <!-- Collapse cards -->
                            <div class="tabcontent" id="search_collapse_body">
                            
                            
                            <!-- Search -->                                                        
                            <div class="card-body">
                                <?php

                                    if(in_array("facility_php_VIEW_FACILITY",$retPerData)){
                            
                                ?>
                                <div class="card table-plain-bg">
                                <div class="card-header">
                                    <h4 class="card-title">Search Branch</h4>
                                    <h6>To view branch details please enter branch name or use filters</h6>
                                </div>
                                <div class="card-body">
                                    <form id="search_facility_form" method="post">
                                        <div class="row" id="search_filters_row0">
                                            <div class="col-md-12"> 
                                                <div class="form-group">
                                                    <label>Branch Name</label>
                                                    <input type="text" id="filter_facility_name" name="filter_facility_name" class="form-control" placeholder="Enter Branch Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="checkbox" name="searchFilters" id="searchFilters"> OR Search Via Filters
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="search_filters_row" style='display:none'>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="filter_country">Country</label>
                                                    <select name="filter_country" id="filter_country" class="form-control">                                                                                                                
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="filter_state">State</label>
                                                    <select name="filter_state" id="filter_state" class="form-control" >                                                    
                                                    </select>
                                                </div>
                                            </div>   
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="filter_city">City</label>
                                                    <select name="filter_city" id="filter_city" class="form-control" onchange="fetch_fac_list()"></select>
                                                </div>
                                            </div>              
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="filter_select_fac">Select Branch</label>
                                                    <select name="filter_select_fac" id="filter_select_fac" class="form-control" disabled>                                                      
                                                    </select>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-info btn-fill" style="margin:1px">Search</button>
                                                    <button type="button" class="btn btn-info btn-fill" id="clearBtn" style="margin:1px"disabled>Clear</button>                                                                                                                                                        
                                                </div>
                                            </div>
                                            </form>
                                        </div>                                        
                                        <div class="clearfix"></div>                                    
                                </div>                                
                            </div>    
                            <?php
                                    }
                                    else{
                                        echo "You do not have permission to search facility";
                                    }
                                ?>                     
                                </div>
                            <!-- Search -->

                            </div>
                                <!-- Collapse Cards-->

                                <!-- Collapse Cards-->
                                <div class="tabcontent" id="create_collapse_body">

                                

                                    <!-- Create Facility -->                                                 
                            <div class="card-body">
                            <?php 

if(in_array("facility_php_CREATE_FACILITY",$retPerData)){

?>
                                <div class="card table-plain-bg">
                                    <div class="card-header ">
                                        <h4 class="card-title">Create Branch</h4>
                                        <p class="card-category">Enter Branch Details (Fields marked as <span style="color:red;font-weight:bold">" * "</span> are required)</p>
                                    </div>
                                    <div class="card-body">
                                        <form id="createFacilityForm" method="post">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Branch Name <span style="color:red;font-weight:bold">*</span></label>
                                                        <input type="text" class="form-control" id="facility_name" name="facility_name" placeholder="Enter Branch Name" required>
                                                    </div>
                                                </div>                                                
                                               
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Country <span style="color:red;font-weight:bold">*</span></label>
                                                        <select id="country" name="country" class="form-control" required></select>
                                                    </div>
                                                </div>            
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="state">State: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                        <select id="state" name="state" class="form-control" required>                                                            
                                                        </select>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>City <span style="color:red;font-weight:bold">*</span></label>
                                                        <select id="city" name="city" class="form-control" required></select>
                                                    </div>
                                                </div>
                                                                                        
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Pincode <span style="color:red;font-weight:bold">*</span></label>
                                                        <input type="number" id="pincode" name="pincode" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Address <span style="color:red;font-weight:bold">*</span></label>
                                                        <textarea name="address" id="address" cols="30" rows="10" class="form-control" placeholder="Enter Address" required></textarea>
                                                    </div>
                                                </div>
                                            </div>                                                
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="checkbox" class="" id="addAdmin" name="addAdmin"> Add Admin For Branch
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style='display:none' id="facility_admin_row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Select Branch Admin <span style="color:red;font-weight:bold">*</span></label>                                                           
                                                        <select class="multi-select form-control" id="admin_user_id" name="admin_user_id">                                                                                                                      
                                                        </select>                                                    
                                                        <p class="card-category">If you dont find specific user then                                                             
                                                            <a href="./users.php" target="_blank">Click here to create a new user account</a>                                                            
                                                        </p>
                                                    </div>
                                                </div>
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
                                <?php 

                                    }
                                    else{
                                        echo "You do not have permission to create facility";
                                    }
                            ?>
                            </div>                           

                        <!-- Create Facility -->                        
                                </div><!-- Collapse Cards-->
                        </div>
                           
                        </div>
                    </div>

                    <div class="row">                                                                                              
                    
                        

                        <!-- Show Facility Details -->
                        
                        <!-- List Facility -->
                        <div class="col-md-12" id="fac_details" style='display:none'>
                            <div class="card">
                                <div class="card-header">                                    
                                <div class="tab">
                                    <button class="tablinks" id="fac_collapse_body_btn" onclick="openCity(event, 'fac_collapse_body')">Branch    Details</button>
                                    <button class="tablinks" id="users_collapse_body_btn" onclick="openCity(event, 'users_collapse_body')">Users Details</button>                                    
                                </div>      
                                    <hr/>                                  
                                </div>
                                <!-- Collapse cards -->
                                <div class="tabcontent" id="fac_collapse_body">
                                    <div class="card-body" id="fac_details_body">                                                                                                                                                            
                                    </div>
                                </div>
                                <div class="tabcontent" id="users_collapse_body">
                                    <div class="card-body" id="users_details_body">                                                                                                                                                            
                                    </div>
                                </div>
                                <!-- Collapse cards -->
                            </div>
                        </div>                                                                                  
                        <!-- List Facility -->                                
                                         

                    
        
                    </div>   
                                                       
                                                                  
                    </div>
                </div>
            </div>
            <!-- Main Content Close -->

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


<?php } } ?>