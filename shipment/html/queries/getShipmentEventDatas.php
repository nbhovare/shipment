<?php

    include("../includes/db_connect.php");
            
    if (isset($_POST['data'])) {

        $eventID = $_POST ['data']['eventId'];        
        $shipmentID=$_POST['data']['shipmentId'];
        
        $query = "SELECT * FROM shipment_details WHERE shipment_id='".$shipmentID."'"; 
        
        $execute_query = mysqli_query($connection, $query);            
        if ($execute_query) {                           
            if(mysqli_num_rows($execute_query)>0){                
                $jsonData_shpiment = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);                                                    
                      
                      $query_events = "SELECT shipment_events.event_id as event_id,shipment_events.date as event_date,
                      shipment_events.remarks as event_remarks,shipment_events.activity as events_activity,
                      shipment_details.shipment_status as shipment_status, fac0.facility_id,
                      fac0.facility_name , shipment_events.forward_to as forward_to, fac2.facility_name as forward_fac_name, 
                      shipment_events.return_to as return_to, fac1.facility_name as return_fac_name 
                      FROM shipment_events 
                      LEFT JOIN shipment_details on shipment_details.shipment_id=shipment_events.shipment_id 
                      LEFT JOIN shipment_facility AS fac0 on fac0.facility_id=shipment_events.facility_id 
                      LEFT JOIN shipment_facility AS fac1 on fac1.facility_id=shipment_events.return_to
                      LEFT JOIN shipment_facility AS fac2 on fac2.facility_id=shipment_events.forward_to 
                      where shipment_events.event_id='".$eventID."' ORDER BY shipment_events.event_id DESC";

                    $execute_query_events = mysqli_query($connection, $query_events);                    
                    if ($execute_query_events) {               
                        if(mysqli_num_rows($execute_query_events)>0){  
                            $jsonData_events = mysqli_fetch_all($execute_query_events, MYSQLI_ASSOC);                            
                    
                               
                                // Close the database connection
                                mysqli_close($connection);

                                // Combine data from both queries into an array
                                $jsonData_send = array(                                    
                                    'events_data' => $jsonData_events
                                );
                                $jsonData = json_encode($jsonData_send);

                        }
                        else{             
                            $res=array("error_msg"=>"Shipment ID does not exist");
                            $jsonData = json_encode($res);
                            //echo "Shipment ID does not exist";
                        }                      
                    }
                    else {
                        $res=array("error_msg"=>"Error");
                        $jsonData = json_encode($res);
                        //echo "Error";
                    }
                    /* Getting Events Data */
                
            }
            else{                             
                $jsonData = array(
                    'error_msg' => "Shipment ID does not exist"
                );

                $jsonData = json_encode($jsonData);                
            }
        }
        else {
            $res=array("error_msg"=>"Error");
            $jsonData = json_encode($res);
            //echo "Error";
        }
    }
    else {
        $res=array("error_msg"=>"Invalid Event");
        $jsonData = json_encode($res);
        //echo "Enter Shipment ID Properly";
    }
                
    // Set headers and send the JSON response    
    echo $jsonData;

?>
