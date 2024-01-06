<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");  

    session_start();        

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"facility_php_MODIFY_FACILITY",$connection);        
        if($checkPer==="1"){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {
                    
                    // Retrieve the JSON data from the POST request
                    $jsonData = $_POST['data'];

                    // Decode the JSON data into an array of objects
                    $formData = json_decode($jsonData, true);                    

                    if ($formData !== null) {
                                                                                                                                    
                        $toFields="";            
                        $firstIteration = true;                                                            

                        // Loop through the associative array
                        foreach ($formData as $key => $value) {
                            // $key represents the form field name, and $value represents the form field value                            
                            if($key!="facility_id" && $key!="entry_create_date" && $key!="admin_user_id"){
                                if ($firstIteration) {                                
                                    $toFields.=$key."='".$value."'";
                                    $firstIteration = false;
                                } 
                                else {                                
                                    $toFields.=",".$key."='".$value."'";                               
                                }
                            }
                        }
                        
                        // Code to insert into database
                        $updateFacQ = "UPDATE shipment_facility SET ".$toFields." WHERE facility_id='".$formData['facility_id']."'";
                             
                        $execute_query = mysqli_query($connection, $updateFacQ);                
                                        
                        if ($execute_query) {                    
                            $return_data=array(
                                array(
                                "ret_msg" => "Details Updated Successfully"
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
    }
    else{
        $ret_msg="You Do not have permission to this action";
        $return_data=array(
            array(
            "error_msg" => $ret_msg
        ));
        echo json_encode($return_data);
    }
}

?>
