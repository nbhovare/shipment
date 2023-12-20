<?php

    include("../includes/db_connect.php");    
    include("../includes/check_permission.php");

    session_start();

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }  
    else{
            
        $curUserID=$_SESSION['user_id'];        
        $curUserFacID=$_SESSION['facility_id'];
        $checkPer=checkPermission($curUserID,"TRACK_SHIP",$connection);        
        if($checkPer==="1"){
            
            if (isset($_POST['typeofReq'])) {

                $typeofReq = $_POST['typeofReq'];
                $query="";

                switch($typeofReq){

                    case "INCOMING";
                        $query="select * from shipment_details
                            where (shipment_status like 'FORWARD' or shipment_status like 'RETURN') and dest_id='".$curUserFacID."'";
                    break;

                    case "INQUEUE";
                        /*$query = "SELECT * FROM shipment_events se WHERE event_id = (
                                        SELECT MAX(event_id)
                                        FROM shipment_events
                                        WHERE shipment_id = se.shipment_id
                                    ) and activity='ARRIVED' ;";*/

                        $query="select * from shipment_details
                            where 
                            (
                                shipment_status like 'ARRIVED' or 
                                shipment_status like 'CREATED' or 
                                shipment_status like 'OUT_FOR_DELIVERY' or
                                shipment_status like 'ONHOLD' or
                                shipment_status like 'RELEASE_ON_HOLD'
                            ) and dest_id='".$curUserFacID."'";
                    break;

                    case "PROCESSED";
                        $query="select * from shipment_details
                            where (shipment_status like 'DELIVERED' or shipment_status like 'CANCEL') and dest_id='".$curUserFacID."'";                            
                    break;                                      

                }                
                                                     
                $execute_query = mysqli_query($connection, $query);            
                if ($execute_query) {                               
                    if(mysqli_num_rows($execute_query)>0){                
                        $jsonData_shpiment = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);            
                                                                                    
                            // Close the database connection
                            mysqli_close($connection);

                            // Combine data from both queries into an array
                            $jsonData_send = array(
                                'shipment_data' => $jsonData_shpiment
                            );
                            $jsonData = json_encode($jsonData_send);
                        
                    }
                    else{                                     
                        $res=array(array("error_msg"=>"No Shipment Found"));                        
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
                $res=array(array("error_msg"=>"Error Bad Request"));
                $jsonData = json_encode($res);
                //echo "Enter Shipment ID Properly";
            }
        }
        else{
            $res=array(array("error_msg"=>"You Do not have permission to this action"));            
            $jsonData = json_encode($res);
        }
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;
}

?>
