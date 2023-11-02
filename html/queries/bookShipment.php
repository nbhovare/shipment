<?php

    include("../includes/db_connect.php");
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['data'])) {
            
            // Retrieve the JSON data from the POST request
            $jsonData = $_POST['data'];

            // Decode the JSON data into an array of objects
            $formData = json_decode($jsonData, true);

            if ($formData !== null) {

                $shipmentIDToInsert="BXP-".$formData["shipment_delivery_method"]."-MH-MP-".mt_rand(10000, 99999);
                
                $create_by_admin_id=0;
                $toFields="shipment_id,create_by_admin_id,tracking_id";
                $toValues="'".$shipmentIDToInsert."','".$create_by_admin_id."','".$shipmentIDToInsert."'";
                
                // Loop through the associative array
                foreach ($formData as $key => $value) {
                    // $key represents the form field name, and $value represents the form field value

                    $toFields=$toFields.",".$key;
                    $toValues=$toValues.",'".$value."'";
                }
                
                // Code to insert into database
                $insertIntoDatabase = "INSERT INTO shipment_details (".$toFields.") VALUES (".$toValues.")";
                
                $execute_query = mysqli_query($connection, $insertIntoDatabase);
                                
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
                
                // Code to insert into database
                    /*if ($conn->query($sql) === TRUE) {
                        echo "Data inserted successfully.";
                    }
                    else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }*/        
       

                    /*echo " INSERT INTO your_table_name (
                        additional_information,booking_date,carrier_id,content_type,create_by_admin_id,event_id,payment_type,receiver_add_info_landmark,
                        receiver_address,receiver_city,receiver_country,receiver_email,receiver_name,receiver_phone,receiver_pincode,
                        receiver_state,sender_address,sender_city,sender_country,sender_email,sender_name,sender_phone,sender_pincode,sender_state,shipment_cost) 
                        
                        VALUES (
                        '$additional_information','$booking_date','$carrier_id','$content_type',$create_by_admin_id,
                        $event_id,'$payment_type','$receiver_add_info_landmark','$receiver_address','$receiver_city','$receiver_country',
                        '$receiver_email','$receiver_name','$receiver_phone','$receiver_pincode','$receiver_state','$sender_address','$sender_city',
                        '$sender_country','$sender_email','$sender_name','$sender_phone','$sender_pincode','$sender_state',$shipment_cost
                    );";
                    // Code to insert into database      
                                        
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
