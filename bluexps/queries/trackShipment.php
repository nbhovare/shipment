<?php

    include("../../shipment/html/includes/db_connect.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
        if (isset($_POST['data'])) {
            
            
            // Retrieve the JSON data from the POST request
            $jsonData = $_POST['data'];

            // Decode the JSON data into an array of objects
            $formData = json_decode($jsonData, true);

            if ($formData !== null) {
                            

                $shipmentID = $formData['shipment_id'];                        
                

                $query = "SELECT 
                sd.shipment_id as 'Shipment ID',
                sd.shipment_status as 'Shipment Status',
                sd.shipment_type as 'Shipment Type',
                sd.shipment_weight as 'Weight (In Kg)',
                sd.shipment_delivery_method as 'Delivery Method',
                sd.shipment_cost as 'Cost (INR)',
                sd.payment_type as 'Payment Type',
                concat (sd.sender_city,', ',sd.sender_state,', ',sender_country,', ',sender_pincode) as 'Sender Address',
                concat (sd.receiver_city,', ',sd.receiver_state,', ',receiver_country,', ',receiver_pincode) as 'Receiver Address',                
                sd.booking_date as 'Booking Date',                
                concat (sf1.city,', ',sf1.state) as 'Booking Facility ',
                concat (sf2.city,', ',sf2.state) as 'Current Facility'                
                
            FROM 
                shipment_details sd
            LEFT JOIN 
                shipment_facility sf1 ON sd.facility_id = sf1.facility_id
            LEFT JOIN 
                shipment_facility sf2 ON sd.dest_id = sf2.facility_id
            WHERE 
                sd.shipment_id = '".$shipmentID."'";           

                
                $execute_query = mysqli_query($connection, $query);            
                if ($execute_query) {                           
                    if(mysqli_num_rows($execute_query)>0){                
                        $jsonData_shpiment = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);   

                        /*$tracking_logQ="INSERT INTO tracking_log (full_name,email_id,shipment_id,enquiry_type) 
                        VALUES ('".$formData['full_name']."','".$formData['email_id']."','".$shipmentID."','".$formData['type']."')";

                        $tracking_logQ_EQ=mysqli_query($connection,$tracking_logQ);                        
                          */                                                                                

                                        // Close the database connection
                                        mysqli_close($connection);

                                        // Combine data from both queries into an array
                                        $jsonData_send = array(
                                            'shipment_data' => $jsonData_shpiment
                                            //'events_data' => $jsonData_events
                                        );
                                        $jsonData = json_encode($jsonData_send);

                            
                        
                    }
                    else{             
                        //$res=array(array("error_msg"=>"Shipment ID does not exist"));                        
                        $jsonData = array(
                            'error_msg' => "Shipment ID does not exist"
                        );

                        $jsonData = json_encode($jsonData);
                        //echo "Shipment ID does not exist";
                    }
                }
                else {
                    $res=array(
                        "error_msg"=>"Error"
                    );
                    $jsonData = json_encode($res);
                    //echo "Error";
                }
        }
        else{
            $res=array(
                "error_msg"=>"Invalid Data"
            );
            $jsonData = json_encode($res);                                
        }
    }
    else {
        $res=array(
            "error_msg"=>"Invalid Data"
        );
        $jsonData = json_encode($res);
        //echo "Enter Shipment ID Properly";
    }
}
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
