<?php

    include("../includes/db_connect.php");
            
    if (isset($_POST['shipmentID'])) {

        $shipmentID = $_POST ['shipmentID'];        
        
        $query = "SELECT * FROM shipment_details WHERE shipment_id='".$shipmentID."'";        
        $execute_query = mysqli_query($connection, $query);            
        if ($execute_query) {                           
            if(mysqli_num_rows($execute_query)>0){                
                $jsonData_shpiment = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);            
                    
                    /* Getting Events Data */
                    //$query_events = "SELECT shipment_events.event_id as event_id,shipment_events.date as event_date,shipment_events.remarks as event_remarks,shipment_details.shipment_status as shipment_status FROM shipment_events 
                      //          INNER JOIN shipment_details on shipment_details.shipment_id=shipment_events.shipment_id where shipment_events.shipment_id='".$shipmentID."'";                    
                      
                      $query_events = "SELECT shipment_events.event_id as event_id,shipment_events.date as event_date,shipment_events.remarks as event_remarks,shipment_events.activity as events_activity,shipment_details.shipment_status as shipment_status, shipment_facility.facility_id,shipment_facility.facility_name 
                      , shipment_events.forward_to as forward_to, shipment_events.return_to as return_to
                      FROM shipment_events 
                      INNER JOIN shipment_details on shipment_details.shipment_id=shipment_events.shipment_id 
                      INNER JOIN shipment_facility on shipment_facility.facility_id=shipment_events.facility_id where shipment_events.shipment_id='".$shipmentID."' ORDER BY shipment_events.event_id DESC";

                    $execute_query_events = mysqli_query($connection, $query_events);                    
                    if ($execute_query_events) {               
                        if(mysqli_num_rows($execute_query_events)>0){  
                            $jsonData_events = mysqli_fetch_all($execute_query_events, MYSQLI_ASSOC);                            
                    

                                /* Getting Facility Details
                                
                                $query_facilty = "SELECT shipment_events.event_id as event_id,shipment_events.date as event_date,shipment_events.remarks as event_remarks,shipment_details.shipment_status as shipment_status FROM shipment_events 
                                    INNER JOIN shipment_details on shipment_details.shipment_id=shipment_events.shipment_id where shipment_events.shipment_id='".$shipmentID."'";                    
                                $execute_query_facilty = mysqli_query($connection, $query_facilty);
                                if ($execute_query_facilty) {               
                                if(mysqli_num_rows($execute_query_facilty)>0){  
                                    $jsonData_facility = mysqli_fetch_all($execute_query_events, MYSQLI_ASSOC);                            
                                }
                                else{             
                                    $res=array(array("error_msg"=>"Shipment ID does not exist"));
                                    $jsonData = json_encode($res);
                                    //echo "Shipment ID does not exist";
                                } 

                                /* Getting faicility Details */

                                // Close the database connection
                                mysqli_close($connection);

                                // Combine data from both queries into an array
                                $jsonData_send = array(
                                    'shipment_data' => $jsonData_shpiment,
                                    'events_data' => $jsonData_events
                                );
                                $jsonData = json_encode($jsonData_send);

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
                    /* Getting Events Data */
                
            }
            else{             
                //$res=array(array("error_msg"=>"Shipment ID does not exist"));
                $arr=array('error_msg' => 'Shipment ID does not exist');
                $jsonData = array(
                    'error_msg' => $arr
                );

                $jsonData = json_encode($jsonData);
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
