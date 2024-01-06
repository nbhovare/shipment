<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");  

    session_start();        

    function checkIfUserMobileAlreadyInUse($mobile_no,$connection){
        $checkQ="SELECT mobile_no FROM users WHERE mobile_no='".$mobile_no."'";
        $checkQ_EQ=mysqli_query($connection,$checkQ);
        if($checkQ_EQ){
            if(mysqli_num_rows($checkQ_EQ)>0){
                return 1;
            }            
            else{
                return 0;
            }    
        }
        else{
            return -1;
        }
    }

    function checkIfUserAlreadyExists($email_id,$connection){
        $checkQ="SELECT email_id FROM users WHERE email_id='".$email_id."'";
        $checkQ_EQ=mysqli_query($connection,$checkQ);
        if($checkQ_EQ){
            if(mysqli_num_rows($checkQ_EQ)>0){
                return 1;
            }            
            else{
                return 0;
            }    
        }
        else{
            return -1;
        }
    }

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"users_php_CREATE_USER",$connection);        
        if($checkPer==="1"){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {
                    
                    // Retrieve the JSON data from the POST request
                    $jsonData = $_POST['data'];

                    // Decode the JSON data into an array of objects
                    $formData = json_decode($jsonData, true);

                    if ($formData !== null) {
                           
                        
                        $checkUserExistsOrNot=checkIfUserAlreadyExists($formData['email_id'],$connection);
                        if($checkUserExistsOrNot===0){

                            $checkUserMobileAlreadyBeingUsed=checkIfUserMobileAlreadyInUse($formData['mobile_no'],$connection);
                            if($checkUserMobileAlreadyBeingUsed===0){
                                // Code
                                $create_by_admin_id=$_SESSION['user_id'];                        
                                $toFields="created_by";
                                $toValues="'".$create_by_admin_id."'";
                                
                                if(!isset($formData["facility_id"])){                                                                                                            
                                
                                    $formData['facility_id']=$_SESSION['facility_id'];
                                }
                                
                                
                                // Loop through the associative array
                                foreach ($formData as $key => $value) {
                                    // $key represents the form field name, and $value represents the form field value                                    
                                    if($key==="assignUserFacCountry" || $key==="assignUserFacCity" || $key==="assignUserFacState" || $key==="assignFacility")
                                        continue;
                                    
                                    $toFields=$toFields.",".$key;
                                    $toValues=$toValues.",'".$value."'";
                                }
                                
                                // Code to insert into database
                                $insertIntoDatabase = "INSERT INTO users (".$toFields.") VALUES (".$toValues.")";                                     
                                                
                                $execute_query = mysqli_query($connection, $insertIntoDatabase);                
                                                
                                if ($execute_query) {                    
                                    $return_data=array(
                                        array(
                                        "ret_msg" => "User Created Successfully"
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
                                // Code                                 
                            }
                            else if($checkUserMobileAlreadyBeingUsed===1){
                                $ret_msg="Mobile Number is already being used by other User.";
                                $return_data=array(
                                    array(
                                    "error_msg" => $ret_msg
                                ));
                                echo json_encode($return_data); 
                            }   
                            else{
                                $ret_msg="Error";
                                $return_data=array(
                                    array(
                                    "error_msg" => $ret_msg
                                ));
                                echo json_encode($return_data); 
                            }                         
                        }
                        else if($checkUserExistsOrNot===1){
                            $ret_msg="User/Email-ID Already Exists.";
                            $return_data=array(
                                array(
                                "error_msg" => $ret_msg
                            ));
                            echo json_encode($return_data);                            
                        }         
                        else{
                            $ret_msg="Error.";
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
