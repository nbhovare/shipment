<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");  
    include("../includes/check_shipment.php");

    session_start();        

    function updateCreditsDetails($user_id,$credit_used,$connection){
        // Update credts that has been utilized by current user
        $updateUserCreditQ="UPDATE shipment_polls SET status='1' WHERE user_id='".$user_id."' AND shipment_id='".$credit_used."'";
        $updateUserCreditQ_EQ=mysqli_query($connection,$updateUserCreditQ);
        if($updateUserCreditQ_EQ){
            return 1;
        }
        else{
            return 0;
        }
    }


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

                        $shipIDInserteds=null;
                        if($_SESSION['type']==="CLIENT"){
                            // If current user type is client then utilize client shipment credits from shipment_polls table
                            // but first check if current user has credits left in the poll

                            $clientUser_id=$_SESSION['user_id'];
                            $checkIfPollIsEmptyOrNotQ="SELECT shipment_id FROM shipment_polls WHERE user_id='".$clientUser_id."' AND status='0' LIMIT 1";
                            $checkIfPollIsEmptyOrNotQ_EQ=mysqli_query($connection,$checkIfPollIsEmptyOrNotQ);
                            if($checkIfPollIsEmptyOrNotQ_EQ && mysqli_num_rows($checkIfPollIsEmptyOrNotQ_EQ)>0){
                                // Means user has at least one credit left to book current shipmet as system designed to book 1 shipment at a time
                                $resId=mysqli_fetch_all($checkIfPollIsEmptyOrNotQ_EQ,MYSQLI_ASSOC);
                                $shipIDInserteds=$resId[0]['shipment_id'];    
                            }
                            else{
                                // Throw User and Exit from current script do not proceed further
                                $return_data=array(
                                    "error_msg" => "No Enough Credits left to book this shipment"
                                );
                                echo json_encode($return_data);
                                exit;                                
                            }


                        }
                        else{
                            $shipIDInserteds=$formData['shipment_id'];    
                        }
                        



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

                    

                        if($_SESSION['type']==="CLIENT"){                            
                            $toFields=$toFields.",shipment_id";
                            $toValues=$toValues.",'".$shipIDInserteds."'";
                        }

                        foreach ($formData as $key => $value) {
                            // $key represents the form field name, and $value represents the form field value                            
                            $toFields=$toFields.",".$key;
                            $toValues=$toValues.",'".$value."'";
                        }                        


                        // Check if current user type is CLIENT if yes then fetch city, state, country details from db and fill into shipment_details table
                        // if not then fill info received from user


                        if($_SESSION['type']==="CLIENT"){
                            $getDetailsFromDbQ="SELECT first_name as name,address,mobile_no as phone,city,state,country,pincode FROM users WHERE user_id='".$curUserID."'";
                            $getDetailsFromDbQ_EQ=mysqli_query($connection,$getDetailsFromDbQ);
                            if($getDetailsFromDbQ_EQ && mysqli_num_rows($getDetailsFromDbQ_EQ)>0){
                                    // Code to insert into database

                                    $getDetailsFromDbQ_EQ_fetchData=mysqli_fetch_all($getDetailsFromDbQ_EQ,MYSQLI_ASSOC);
                                    foreach($getDetailsFromDbQ_EQ_fetchData[0] as $key=>$value){
                                        $toFields=$toFields.",sender_".$key;
                                        $toValues=$toValues.",'".$value."'";
                                    }

                                $insertIntoDatabase = "INSERT INTO shipment_details (".$toFields.") VALUES (".$toValues.")";    

                                                                
                                                            
                                                
                                $execute_query = mysqli_query($connection, $insertIntoDatabase);                
                                                    
                                if ($execute_query) {    

                                    updateCreditsDetails($_SESSION['user_id'],$shipIDInserteds,$connection);

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
                            else{
                                $return_data=array(
                                    "error_msg" => "Failed Try Again"
                                );
                                echo json_encode($return_data);
                            }
                        }
                        else{
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
