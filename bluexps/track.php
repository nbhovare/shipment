<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Blue Express - Track Shipment</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<script>

$(document).ready(function() {    



    


    // Global AJAX event handlers
    $(document).ajaxStart(function() {
$('#spinner').addClass("show"); // Show the loader when any AJAX request starts
});

$(document).ajaxStop(function() {
$('#spinner').removeClass("show"); // Hide the loader when all AJAX requests complete
});




    $("#shipmentTrackingForm").submit(function(e){
        e.preventDefault();
        var formData = $('#shipmentTrackingForm').serializeArray();
                    // Serialize the form data using jQuery                    

                    // Convert the serialized form data to a JSON object
                    var formDataObject = {};
                    formDataObject["type"]="External";                    
                    $.each(formData, function(index, field) {
                        
                            formDataObject[field.name] = field.value;                        

                    });

                    // Convert the JSON object to a JSON string
                    var formDataJSON = JSON.stringify(formDataObject);
                                                        
                    $.ajax({
                        type: "POST",
                        url: './queries/trackShipment.php',
                        data:  {data: formDataJSON},
                        success: function(response)
                        {                                                        
                            //var responseData = JSON.parse(response);
                            if(response.error_msg){
                                alert(response.error_msg);
                            }
                            else{
                               
                                                                
                                $("#shipDetailsSections").empty();
                                const table=$("<table>").addClass("table-full-width table-responsive table table-hover");
                                const thead=$("<thead>").append("<th>").append("</th>");
                                const tbody=$("<tbody>");
                                table.append(thead).append(tbody);                                                                  
                                    var shipment=response.shipment_data[0]["Shipment Status"];                                    
                                    var shipStat="";
                                    if(
                                                shipment==="ARRIVED" || 
                                                shipment==="RELEASE_ON_HOLD" ||
                                                shipment==="FORWARD" ||
                                                shipment==="RETURN"){
                                                    shipStat='Shipment In Transit';
                                            }
                                            else if(shipment==="CREATED"){
                                                shipStat="BOOKED";
                                            }
                                            else if(shipment==="OUT_FOR_DELIVERY"){
                                                shipStat="Out For Delivery";
                                            }
                                            else{
                                                shipStat=shipment;        
                                            }

                                    for(var shipments in response.shipment_data[0]){
                                        const table_row=$("<tr>");
                                        const table_content=$("<td>").append(shipments);
                                        const table_content1=$("<td>");
                                        if(shipments==="Shipment Status"){
                                            table_content1.append(shipStat);
                                        }                                        
                                        else{
                                            table_content1.append(response.shipment_data[0][shipments]);
                                        }
                                        table_row.append(table_content);
                                        table_row.append(table_content1);
                                        table.append(table_row);
                                    }

                                    $("#shipDetailsSections").append(table);
                                $("#shipDetailsSection").show();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }   
                    });
    });
});
</script>

<body>

    


    <!-- Spinner Start -->
    <div id="spinner" class=" bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <?php
    
        include("./includes/navbar.php");

    ?>


    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5" style="margin-bottom: 6rem;">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Track Your Shipment</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="index.php">Home</a></li>                    
                    <li class="breadcrumb-item text-white active" aria-current="page">Track</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->



    <!-- Contact Start -->
    <div class="container-fluid overflow-hidden py-5 px-lg-0">
        <div class="container contact-page py-5 px-lg-0">
            <div class="row g-5 mx-lg-0">
                <div class="col-md-12 contact-form wow fadeIn" data-wow-delay="0.1s">
                    <h1 class="mb-4">Track Your Shipment</h1>
                    <h6 class="text-secondary text-uppercase">
                        TO TRACK YOUR CONSIGNMENT PLEASE ENTER YOUR SHIPMENT ID PROVIDED DURING BOOKING
                    </h6>                                   
                    <div class="bg-light p-4">
                        <form id="shipmentTrackingForm" method="post">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="shipment_id" name="shipment_id" placeholder="shipment_id" required>
                                        <label for="name">Shipment ID/Tracking ID</label>
                                    </div>
                                </div>
                                <!--<div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="name" class="form-control" id="full_name" name="full_name" placeholder="Your Full Name" required>
                                        <label for="email">Your Full Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Your Email" required>
                                        <label for="email">Your Email</label>
                                    </div>
                                </div>-->                                                            
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Track</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>   
                <div class="col-md-12" id="shipDetailsSection" style='display:none'>
                     <!-- Contact Start -->
                    
                <div class="contact-form wow fadeIn" data-wow-delay="0.1s">
                    <h1 class="mb-4">Shipment details</h1>                                                       
                    <div class="bg-light p-4" id="shipDetailsSections">                                                    
                    </div>
                </div>                        
    <!-- Contact End -->

                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
       

    <?php
        include("./includes/footer.php");
   ?>




    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-0 back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>