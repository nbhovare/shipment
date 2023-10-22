<?php

    include("../includes/db_connect.php");
            
    if (isset($_POST['shipmentID'])) {

        $shipmentID = $_POST ['shipmentID'];
        
        $query = "SELECT * FROM shipment_details WHERE shipment_id='".$shipmentID."'";
        $execute_query = mysqli_query($connection, $query);
        if ($execute_query) {               
            if(mysqli_num_rows($execute_query)>0){  
                $data = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);

                    // Close the database connection
                    mysqli_close($connection);

                    // Encode the data as JSON
                    $jsonData = json_encode($data);
                    
                    
                /*while($row = mysqli_fetch_assoc($execute_query)) {
                    

                    //echo "shipment_id :{$row['shipment_id']}  <br> ".
                    "tracking_id : {$row['tracking_id']} <br> ".
                    "shipment_status: {$row['shipment_status']} <br> ".
                    "shipment_type : {$row['shipment_type']} <br> ".
                    "--------------------------------<br>";
               }*/
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
