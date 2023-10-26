<?php

    include("../includes/db_connect.php");
            
    if (isset($_POST['shipmentID'])) {

        $shipmentID = $_POST ['shipmentID'];
        
        $query = "SELECT shipment_events.event_id as event_id,shipment_events.date as event_date,shipment_events.remarks as event_remarks,shipment_events.location as event_location,shipment_details.shipment_status as shipment_status FROM shipment_events 
                    INNER JOIN shipment_details on shipment_details.shipment_id=shipment_events.shipment_id where shipment_events.shipment_id='".$shipmentID."'";
        $execute_query = mysqli_query($connection, $query);
        if ($execute_query) {               
            if(mysqli_num_rows($execute_query)>0){  
                $data = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);

                    // Close the database connection
                    mysqli_close($connection);

                    // Encode the data as JSON
                    $jsonData = json_encode($data);

            }
            else{             
                $res=array(array("error_msg"=>"Shipment ID does not exist"));
                $jsonData = json_encode($res);
                //echo "Shipment ID does not exist";
            }
        }
        else {
            $res=array(array("error_msg"=>"Error"));
            $jsonData = json_encode($res);
            //echo "Error";
        }
    }
    else {
        $res=array(array("error_msg"=>"Enter Shipment ID Properly"));
        $jsonData = json_encode($res);
        //echo "Enter Shipment ID Properly";
    }
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
