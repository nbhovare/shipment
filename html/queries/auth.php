<?php

    include("../includes/db_connect.php");
    $ret_msg="";
            
    if (isset($_POST['data'])) {

        $formdata = $_POST ['data'];        

        // Decode the JSON data into an array of objects
        $formData = json_decode($formdata, true);

        $user_data = array();        
        foreach ($formData as $key => $value) {
            // $key represents the form field name, and $value represents the form field value
                        
            array_push($user_data,$value);
        }
        
        $query = "SELECT email_id, password, user_id, type, status, facility_id FROM users WHERE email_id='".$user_data[0]."'";        
        $execute_query = mysqli_query($connection, $query);
        if ($execute_query) {            
            // get user Data from Database and verify password
            if(mysqli_num_rows($execute_query)>0){
                $dataFromDB=mysqli_fetch_assoc($execute_query);
                if($dataFromDB['password']===$user_data[1]){
                    session_start();
                    $_SESSION['isSession'] = "true";
                    $_SESSION['user_id'] = $dataFromDB['user_id'];
                    $_SESSION['type'] = $dataFromDB['type'];
                    $_SESSION['status'] = $dataFromDB['status'];

                    $ret_msg="1";

                }
                else{                    
                    $ret_msg="Invalid Password";                    
                }                  
            }
            else{                             
                $ret_msg="User Does not Exists or Invalid Credentials";                        
            }
            // get user Data from Database and verify password
        }
        else {
            $ret_msg="Error";
        }
    }
    else {
        $ret_msg="Error";
    }
                
    header("Content-Type: application/json");
    $return_data = array(
        array(
        "msg" => $ret_msg
    ));

    echo json_encode($return_data);

?>
