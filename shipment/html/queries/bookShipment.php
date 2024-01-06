<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");  
    include("../includes/check_shipment.php");

    session_start();        

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"booking_php_BOOK_SHIP",$connection);        
        if($checkPer==="1"){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {
                    
                    // Retrieve the JSON data from the POST request
                    $jsonData = $_POST['data'];

                    // Decode the JSON data into an array of objects
                    $formData = json_decode($jsonData, true);

                    if ($formData !== null) {

                        $shipIDInserteds=$formData['shipment_id'];    
                        $resForShipExec=checkIfShipmentExists($shipIDInserteds,$connection);
                        if($resForShipExec==="failed"){                   
                            $return_data=array(
                                "error_msg" => "Error"
                            );
                            echo json_encode($return_data);
                        }
                        else if(mysqli_num_rows($resForShipExec)>0){                            
                            $return_data=array(
                                "error_msg" => "Shipment ID already exists"
                            );
                            echo json_encode($return_data);
                        }
                        else{                                                    
                                        
                        //$shipmentIDToInsert="BXP-".$formData["shipment_delivery_method"]."-MH-MP-".mt_rand(10000, 99999);
                        //$shipmentIDToInsert="BXP".$formData["shipment_delivery_method"]."-".mt_rand(10000, 99999);
                        
                        $create_by_admin_id=$_SESSION['user_id'];
                        $current_user_id_facility_id=$_SESSION['facility_id'];
                        //$toFields="shipment_id,create_by_admin_id,tracking_id,facility_id,dest_id";
                        //$toValues="'".$shipmentIDToInsert."','".$create_by_admin_id."','".$shipmentIDToInsert."','".$current_user_id_facility_id."','".$current_user_id_facility_id."'";


                        $toFields="create_by_admin_id,facility_id,dest_id";
                        $toValues="'".$create_by_admin_id."','".$current_user_id_facility_id."','".$current_user_id_facility_id."'";
                        
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
                                "shipment_id" => $shipIDInserteds
                            );
                            echo json_encode($return_data);
                        }
                        else{                            
                            $return_data=array(
                                "error_msg" => "Failed Try Again"
                            );
                            echo json_encode($return_data);
                        }
                    }
                        
                        
                                                
                            /*if ($key !== array_key_last($formData)) {
                                
                            } else {
                                $toFields=$toFields.",".$key;
                                $toValues=$toValues.",".$value;
                            } */                   
                
                    }
                    else {                        
                        $return_data=array(
                            "error_msg" => "Enter Data Properly."
                        );
                        echo json_encode($return_data);
                    }                                    
                    
            }
            else {
                                 
                $return_data=array(
                    "error_msg" => "Invalid Data"
                );
                echo json_encode($return_data);

            }
        }
    }
    else{    
        $return_data=array(
            "error_msg" => "You Do not have permission to this action"
        );
        echo json_encode($return_data);
    }
}

?>
