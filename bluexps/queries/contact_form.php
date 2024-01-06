<?php

    include("../../shipment/html/includes/db_connect.php");    


    function insertIntoContactForm($data,$connection){        
        $toFields="";
        $toValues="";
        //$lastElement=end($data);

        $lastElement=array_key_last($data);
        foreach($data as $key => $value){
            $toFields.=$key;
            $toValues.="'".$value."'";

            if($lastElement!=$key){
                $toFields.=",";
                $toValues.=",";
            }
        }
        $createIssueQ="INSERT INTO issues (".$toFields.") VALUES(".$toValues.")";        
        $createIssueQ_EQ=mysqli_query($connection,$createIssueQ);
        if($createIssueQ_EQ){
            return 1;
        }
        else{
            return 0;
        }
    }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {
                    
                    // Retrieve the JSON data from the POST request
                    $jsonData = $_POST['data'];

                    // Decode the JSON data into an array of objects
                    $formData = json_decode($jsonData, true);

                    if ($formData !== null) {
                        

                        if($formData['shipment_id']==="" || $formData['shipment_id']===null){                            
                            if(insertIntoContactForm($formData,$connection)===1){
                                $return_data=array(
                                    array(
                                    "ret_msg" => "Thanks for reaching out to us, Our team will get back to you, regarding your query"
                                ));
                                echo json_encode($return_data);
                            }
                            else{
                                $return_data=array(
                                    array(
                                    "error_msg" => "Error"
                                ));
                                echo json_encode($return_data);
                            }
                        }
                        else{
                            // Check if shzpment ID Exists or not                            
                            $checkIfShipmentExistsQ="SELECT shipment_id from shipment_details WHERE shipment_id='".$formData['shipment_id']."'";                            
                            $checkIfShipmentExistsQ_EQ=mysqli_query($connection,$checkIfShipmentExistsQ);                            
                            if($checkIfShipmentExistsQ_EQ){                                
                                if(mysqli_num_rows($checkIfShipmentExistsQ_EQ)){                                    
                                    // Insert into contact_form                                    
                                    if(insertIntoContactForm($formData,$connection)===1){
                                        $return_data=array(
                                            array(
                                            "ret_msg" => "Thanks for reaching out to us, Our team will get back to you"
                                        ));
                                        echo json_encode($return_data);
                                    }
                                    else{
                                        $return_data=array(
                                            array(
                                            "error_msg" => "Error"
                                        ));
                                        echo json_encode($return_data);
                                    }
                                }
                                else{
                                    $return_data=array(
                                        array(
                                        "error_msg" => "Invalid, Shipment ID"
                                    ));
                                    echo json_encode($return_data);
                                }
                            }
                            else{
                                $return_data=array(
                                    array(
                                    "error_msg" => "Error"
                                ));
                                echo json_encode($return_data);
                            }
                        }                                                           
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
