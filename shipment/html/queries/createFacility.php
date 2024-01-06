<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");  

    session_start();    
    
    function checkIfUserIsAlreadyFacAdmin($userID,$connection){        
        // Check if selected user_id is already not an admin facility if yes do not allow that seleted user to be admin for the facility
        $checkIfUserCanBeAdminQ="SELECT facility_name FROM `shipment_facility` where admin_user_id='".$userID."'";        
        $checkIfUserCanBeAdminQ_EQ=mysqli_query($connection,$checkIfUserCanBeAdminQ);
        if($checkIfUserCanBeAdminQ_EQ){
            if(mysqli_num_rows($checkIfUserCanBeAdminQ_EQ)>0){
                $row=mysqli_fetch_all($checkIfUserCanBeAdminQ_EQ,MYSQLI_ASSOC);                                
                return $row;
            }
            else{
                return 0;
            }
        }
        else{
            return "error";
        }
    }

    function checkifUserBelongsToAnotherFac($userID,$connection){        
        // Check if selected user_id is already associated to facility if yes do not allow that seleted user to be admin for the facility
        $checkifUserBelongsToAnotherFacQ="SELECT facility_id FROM `users` where user_id='".$userID."'";        
        $checkifUserBelongsToAnotherFacQ_EQ=mysqli_query($connection,$checkifUserBelongsToAnotherFacQ);
        if($checkifUserBelongsToAnotherFacQ_EQ){
            if(mysqli_num_rows($checkifUserBelongsToAnotherFacQ_EQ)>0){
                $row=mysqli_fetch_all($checkifUserBelongsToAnotherFacQ_EQ,MYSQLI_ASSOC);       
                $ansRow=$row[0]['facility_id'];
                if($ansRow==="Null" || $ansRow==="NULL" || $ansRow==="null" || $ansRow==="" || $ansRow===null){
                    return 1;
                }            
                else{
                    return 0;
                }                             
            }
            else{
                return "error";
            }
        }
        else{
            return "error";
        }
    }

    

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"facility_php_CREATE_FACILITY",$connection);        
        if($checkPer==="1"){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {
                    
                    // Retrieve the JSON data from the POST request
                    $jsonData = $_POST['data'];

                    // Decode the JSON data into an array of objects
                    $formData = json_decode($jsonData, true);

                    if ($formData !== null) { 

                        // check if user belongs to anther facility if yes then do not allow                     
                        $checkifUserBelongsToAnotherFacRes=checkifUserBelongsToAnotherFac($formData['admin_user_id'],$connection);                                                  
                        if($checkifUserBelongsToAnotherFacRes!="error"){
                            if($checkifUserBelongsToAnotherFacRes===0){                                                          
                                $ret_msg="Selected user is already associated to another Facility";
                                $return_data=array(
                                    "error_msg" => $ret_msg
                                );
                                echo json_encode($return_data);
                            }
                            else if($checkifUserBelongsToAnotherFacRes===1){
                                // Check if selected user_id is already not an admin facility if yes do not allow that seleted user to be admin for the facility                        
                                $checkIfUserCanBeAdmin_res=checkIfUserIsAlreadyFacAdmin($formData['admin_user_id'],$connection);                                                  
                                if($checkIfUserCanBeAdmin_res!="error"){
                                    if($checkIfUserCanBeAdmin_res!=0){                                                          
                                        $ret_msg="Selected user is already an Facility Admin, Facility = ".$checkIfUserCanBeAdmin_res[0]['facility_name'];
                                        $return_data=array(
                                            "error_msg" => $ret_msg
                                        );
                                        echo json_encode($return_data);
                                    }
                                    else{
                                        $create_by_admin_id=$_SESSION['user_id'];                        
                                        $toFields="create_by";
                                        $toValues="'".$create_by_admin_id."'";
                                        
                                        // Loop through the associative array
                                        foreach ($formData as $key => $value) {
                                            // $key represents the form field name, and $value represents the form field value
                                            if($key==="addAdmin")
                                                continue;
                                            $toFields=$toFields.",".$key;
                                            $toValues=$toValues.",'".$value."'";
                                        }
                                        
                                        // Code to insert into database
                                        $insertIntoDatabase = "INSERT INTO shipment_facility (".$toFields.") VALUES (".$toValues.")";                          
                                                        
                                        $execute_query = mysqli_query($connection, $insertIntoDatabase);                
                                                        
                                        if ($execute_query) {                                      
                                            $return_data=array(
                                                "ret_msg" => "Facility Created Successfully"
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
                                else{                            
                                    $return_data=array(
                                        "error_msg" => "Failed Try Again"
                                    );
                                    echo json_encode($return_data);
                                }     
                            }
                        }
                        else{
                            $return_data=array(
                                "error_msg" => "Failed Try Again"
                            );
                            echo json_encode($return_data);
                        }                                                                               
                
                    }
                    else {                        
                        $return_data=array(
                            "error_msg" => "Enter Data Properly"
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

header("Content-Type: application/json");
?>
