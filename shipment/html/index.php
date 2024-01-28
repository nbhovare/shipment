<?php

    session_start();
    if(!isset($_SESSION['isSession'])){
        header("location:./login.php");
    }




include("./includes/db_connect.php");
include("./includes/check_permission.php");


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
    <title>Dashboard</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/css/demo.css" rel="stylesheet" />


    


    <script type="text/javascript">



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

            $("#refreshShipDetails").click(function(){
                


                var data_send={
                        "pageId":1,
                        "filters":0
                    };

                if($("#filtersCheck").is(":checked")){                    
                    
                    if($("#state").val()!="Select State"){
                        data_send['filters']="1";
                        data_send['state']=$("#state").val();
                        
                        if($("#city").val()!="Select City")
                            data_send['city']=$("#city").val();
                    }                                

                    if($("#date").val().trim()!=""){
                        data_send['filters']="1";
                        data_send['date']=$("#date").val().trim();
                    }
                }
                


                $.ajax({
                    type: "POST",
                    url: './queries/shipment.php',
                    data:  {data: data_send},
                    success: function(response)
                    {       
                        var jsonData=JSON.parse(response);            
                        if(jsonData && jsonData.error_msg){
                            alert(jsonData.error_msg);     
                            $("#shipDetailsCols").empty();                   
                        }
                        else{                                                                                                    
                                                        
                            const table=$("<table>").addClass("table table-responsive");
                            const theader=$("<thead>");
                            const theaderTr=$("<tr>");
                            const tableHeadersList=['shipment_id', 'shipment_status', 'shipment_type', 'booking_date(YYYY/MM/DD)' ];
                            for(heads of tableHeadersList){
                                const theaderTh=$("<th>").append(heads);
                                theaderTr.append(theaderTh);
                            }
                            theader.append(theaderTr);
                            table.append(theader);
                            const tbody=$("<tbody>");
                            $.each(jsonData.shipment_data, function(index, getData) {                         
                                const tbodyTr=$("<tr>");       
                                $.each(getData, function(index, getShipData) {    
                                    
                                    var toAppendDats=getShipData;
                                    if(index==="shipment_id"){
                                        toAppendDats="<a href='trackshipment.php?shipment_id="+getShipData+"' target='_blank'>"+getShipData+"</a>";
                                    }               

                                    if(index==="shipment_status"){
                                        if(getShipData==="CREATED" || getShipData==="ARRIVED" || getShipData==="FORWARD" || getShipData==="RETURN"
                                         || getShipData==="RELEASE_ON_HOLD"
                                        ){
                                            toAppendDats="In Transit";
                                        }
                                    }
                                    const tbodyTd=$("<td>").append(toAppendDats);
                                    
                                    tbodyTr.append(tbodyTd);
                                    
                                });
                                tbody.append(tbodyTr);
                            });
                            table.append(tbody);
                            $("#shipDetailsCols").empty().append(table);
                        }                                                                                                          
                    },
                    error: function (xhr, status, error) {
                        //return "error";
                        alert("error");
                    },
                    complete: function(){
                        //getCData(setTheValue);
                    }
                });

            });

            $("#state").change(function(){
                //
                const basedOn=$("#state").val();
                if(basedOn==="Select State"){
                    $("#city").empty();
                }
                else{
                    var data_send={
                        "getData":"city",
                        "basedOn":basedOn
                    };
                    getConData(data_send,$("#city"),"City");
                }
            });


            $("#filtersCheck").change(function(){
                if($(this).is(":checked")){
                    var data_send={
                        "getData":"state",
                        "basedOn":"INDIA"
                    };
                    getConData(data_send,$("#state"),"State");
                    $("#filtersCol").show();
                }
                else{
                    $("#filtersCol").hide();
                }
            });
            


        });
    </script>

</head>

<body>

<?php 

        include("./includes/loaders.php");

?>

    <div class="wrapper">
       
        
            
        <?php

            include("./includes/navbar.php");

        ?>

            <div class="content" style='margin-top:10px'>
                <div class="container-fluid">
                    <div class="row">

                      <?php

                            include("./includes/quick_links.php");

                      ?>

<div class="col-md-12">
    <div class="card ">
        <div class="card-header ">
            <h4 class="card-title">Welcome</h4>
            <p class="card-category">To your dashboard</p>
        </div>
        <div class="card-body ">
        </div>
    </div>
</div>

<?php 

    if($_SESSION['type']==="CLIENT"){

?>


<div class="col-md-12">
    <div class="card ">
        <div class="card-header ">
            <h4 class="card-title">My Bookings</h4>
            <p class="card-category">By default shipments are sorted based on booking date (Latest one on top))</p>
        </div>
        <div class="card-body ">
            <div class="row">
                <div class="col-md-12">
                    <input type="checkbox" name="filtersCheck" id="filtersCheck">
                    <label for="filtersCheck"> Apply Filters</label>
                </div>
                <div class="col-md-12" id="filtersCol" style='display:none'>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State (Optional)</label>
                                <select name="state" id="state" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">City (Optional)</label>
                                <select name="city" id="city" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">date (Optional)</label>
                                <input type="date" id="date" name="date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-success" id="refreshShipDetails">
                        Refresh Details
                    </button>
                </div>
                <div class="col-md-12" id="shipDetailsCols"></div>
            </div>
        </div>
    </div>
</div>

<?php } ?>




                       <!-- <div class="col-md-4">
                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title">Shipment Statistics</h4>
                                    <p class="card-category">Categorized based on transport type</p>
                                </div>
                                <div class="card-body ">
                                    <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div>
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> Air
                                        <i class="fa fa-circle text-danger"></i> Ground
                                        <i class="fa fa-circle text-warning"></i> Water
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-clock-o"></i> Updated 30 mins Ago
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title">Year on Year growth</h4>
                                    <p class="card-category">24 months performance</p>
                                </div>
                                <div class="card-body ">
                                    <div id="chartHours" class="ct-chart"></div>
                                </div>
                                <div class="card-footer ">
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> Air
                                        <i class="fa fa-circle text-danger"></i> Ground
                                        <i class="fa fa-circle text-warning"></i> Water
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-history"></i> Updated 30 minutes ago
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <!--<div class="row">
                        <div class="col-md-6">
                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title">2023 Sales</h4>
                                    <p class="card-category">All Shipments</p>
                                </div>
                                <div class="card-body ">
                                    <div id="chartActivity" class="ct-chart"></div>
                                </div>
                                <div class="card-footer ">
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> Air
                                        <i class="fa fa-circle text-danger"></i> Ground
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-check"></i> Data information certified
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card  card-tasks">
                                <div class="card-header ">
                                    <h4 class="card-title">Tasks</h4>
                                    <p class="card-category">Backend development</p>
                                </div>
                                <div class="card-body ">
                                    <div class="table-full-width">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Sign contract for "What are conference organizers afraid of?"</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="" checked>
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="" checked>
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
                                                    </td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" checked>
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Create 4 Invisible User Experiences you Never Knew About</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Read "Following makes Medium better"</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="" disabled>
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Unfollow 5 enemies from twitter</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </td>   
                                                </tr>   
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                        <i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>

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
<!--
<script type="text/javascript">
    $(document).ready(function() {
        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts();

        

    });
</script>-->
</html>
