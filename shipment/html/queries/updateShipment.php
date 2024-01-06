<?php

    include("../includes/db_connect.php");
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['data'])) {
            
            // Retrieve the JSON data from the POST request
            $jsonData = $_POST['data'];

            // Decode the JSON data into an array of objects
            $formData = json_decode($jsonData, true);

            if ($formData !== null) {
                                                                                                
                $create_by_admin_id=0;
                $toupdate="";                
                $shipment_id="";

                $lastKey = end(array_keys($formData)); // Get the last key in the array

                // Loop through the associative array
                foreach ($formData as $key => $value) {
                    // $key represents the form field name, and $value represents the form field value
                    if($key==="shipment_id"){
                        $shipment_id=$value;
                    }
                    else{
                        $toupdate=$toupdate.$key."='".$value."'";
                        $toupdate=$toupdate.(($key === $lastKey)?"":",");
                        // Check if the current key is the last key if not append , (Comma) to add more values which needs to be updated
                    } 
                }                
                
                // Code to insert into database
                $insertIntoDatabase = "update shipment_details set ".$toupdate." where shipment_id='".$shipment_id."'";                
                                
                /*$execute_query = mysqli_query($connection, $insertIntoDatabase);                
                                
                if ($execute_query) {                    
                    $return_data=array(
                        array(
                        "shipment_id" => $shipmentIDToInsert
                    ));
                    echo json_encode($return_data);
                }
                else{
                    $ret_msg="Failed Try Again";
                    $return_data=array(
                        array(
                        "error_msg" => $ret_msg
                    ));
                    echo json_encode($return_data);
                }
                
                   
                                        
                    /*if ($key !== array_key_last($formData)) {
                        
                    } else {
                        $toFields=$toFields.",".$key;
                        $toValues=$toValues.",".$value;
                    } */                   
        
            }
            else {
                $ret_msg="Enter Data Properly.";
                $return_data=array(
                    array(
                    "error_msg" => $ret_msg
                ));
                echo json_encode($return_data);
            }                                    
            
    }
    else {
        
        $ret_msg="Invalid Data";        
        $return_data=array(
            array(
            "error_msg" => $ret_msg
        ));
        echo json_encode($return_data);

    }
}

?>
