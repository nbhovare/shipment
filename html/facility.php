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
                                        
                    const editBtn=$('<button>').attr('type','button').attr('id','actionEditBtn').addClass('btn btn-info').attr('onclick','actionEditBtnClick()').append('Edit/Remove Permissions');
                    const delBtn=$('<button>').attr('type','button').attr('id','actionRemoveBtn').addClass('btn btn-danger').append('Remove User');

                    const action_col=$('<div>').addClass('col-md-3').append(editBtn);
                    const action_col1=$('<div>').addClass('col-md-3').append(delBtn);                    
                    const action_row=$('<div>').addClass('row').append(action_col).append(action_col1);
                    
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
                if(state==="Select State"){
                    error_res="<li>Select State Properly </li>";
                }
                if($("#filter_country")==="Select Country"){
                    error_res="<li>Select Country Properly </li>";
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
    
        $(document).ready(function() {            

            $('.multi-select').select2({
                placeholder: 'Select multiple options',        
                width: '100%' 
            });

            $("#search_facility_form").submit(function(e){
                e.preventDefault();
                
                var data_send="";
                var error_res="";
                if ($('#searchFilters').is(':checked')){
                    if($('#filter_state').val()==="Select State" || $("#filter_country").val()==="Select Country"){
                        error_res="<li>Enter State & Country Properly</li>";
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
                                alert(response.error_msg.error_msg);
                            }
                            
                            else{
                                //alert("hello");
                                //openCity(event, 'users_collapse_body');
                                alert(response.ret_msg.ret_msg);
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
        <button type="button" class="btn btn-primary">Save changes</button>
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
                            <div class="card">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="filter_state">State</label>
                                                    <select name="filter_state" id="filter_state" class="form-control" onchange="fetch_fac_list()">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="filter_country">Country</label>
                                                    <select name="filter_country" id="filter_country" class="form-control">                                                        
                                                        <option value="India">India</option>
                                                        <option value="Select Country">Select Country</option>
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="filter_select_fac">Select Facility</label>
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

                            <!-- Search -->

                            </div>
                                <!-- Collapse Cards-->

                                <!-- Collapse Cards-->
                                <div class="tabcontent" id="create_collapse_body">
                                    <!-- Create Facility -->                                                 
                            <div class="card-body">
                                <div class="card table-plain-bg">
                                    <div class="card-header ">
                                        <h4 class="card-title">Create Facility</h4>
                                        <p class="card-category">Enter Facility Details (Fields marked as <span style="color:red;font-weight:bold">" * "</span> are required)</p>
                                    </div>
                                    <div class="card-body">
                                        <form id="createFacilityForm" method="post">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Facility Name <span style="color:red;font-weight:bold">*</span></label>
                                                        <input type="text" class="form-control" id="facility_name" name="facility_name" placeholder="Enter Facility Name" required>
                                                    </div>
                                                </div>                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Address <span style="color:red;font-weight:bold">*</span></label>
                                                        <textarea name="address" id="address" cols="30" rows="10" class="form-control" placeholder="Enter Address" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>City <span style="color:red;font-weight:bold">*</span></label>
                                                        <input type="text" id="city" name="city" class="form-control" placeholder="Enter City" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="state">State: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                        <select id="state" name="state" class="form-control" required>
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
                                        
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="country">Country: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                        <select id="country" name="country" class="form-control" required>
                                                            <option value="India">India</option>
                                                            <option value="Select Country">Select Country</option>
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Pincode <span style="color:red;font-weight:bold">*</span></label>
                                                        <input type="number" id="pincode" name="pincode" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>                                                
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="checkbox" class="" id="addAdmin" name="addAdmin"> Add Admin For Facility
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style='display:none' id="facility_admin_row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Select Facility Admin <span style="color:red;font-weight:bold">*</span></label>                                                           
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
                      
                         <!-- Create Facility -->                        
                         <div class="col-md-12 collapse" id="createFacility">
                            <div class="card-body">
                                <div class="card table-plain-bg">
                                    <div class="card-header ">
                                        <h4 class="card-title">Create Facility</h4>
                                        <p class="card-category">Enter Facility Details (Fields marked as <span style="color:red;font-weight:bold">" * "</span> are required)</p>
                                    </div>
                                    <div class="card-body">
                                        <form id="createFacilityForm" method="post">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Facility Name <span style="color:red;font-weight:bold">*</span></label>
                                                        <input type="text" class="form-control" id="facility_name" name="facility_name" placeholder="Enter Facility Name" required>
                                                    </div>
                                                </div>                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Address <span style="color:red;font-weight:bold">*</span></label>
                                                        <textarea name="address" id="address" cols="30" rows="10" class="form-control" placeholder="Enter Address" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>City <span style="color:red;font-weight:bold">*</span></label>
                                                        <input type="text" id="city" name="city" class="form-control" placeholder="Enter City" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="state">State: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                        <select id="state" name="state" class="form-control" required>
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
                                        
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="country">Country: <span style="color:red;font-weight:bold">*</span></label>                                                
                                                        <select id="country" name="country" class="form-control" required>
                                                            <option value="India">India</option>
                                                            <option value="Select Country">Select Country</option>
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Pincode <span style="color:red;font-weight:bold">*</span></label>
                                                        <input type="number" id="pincode" name="pincode" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>                                                
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="checkbox" class="" id="addAdmin" name="addAdmin"> Add Admin For Facility
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style='display:none' id="facility_admin_row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Select Facility Admin <span style="color:red;font-weight:bold">*</span></label>                                                           
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
                            </div>   
                        </div>

                        <!-- Create Facility -->

                    
        
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


<?php }?>