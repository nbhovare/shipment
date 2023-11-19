<?php

    include("../includes/db_connect.php");
            
    session_start();        

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        
        //if(isset($_POST['facilityState'])){            
            $query = "select user_id, first_name, last_name, mobile_no, email_id from users";            
            $execute_query = mysqli_query($connection, $query);            
            if ($execute_query) {                           
                if(mysqli_num_rows($execute_query)>0){                
                    $jsonData_users = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);            

                        // Close the database connection
                        mysqli_close($connection);
                        
                        $jsonData_send = array(
                            'users_data' => $jsonData_users
                        );
                        $jsonData = json_encode($jsonData_send);            

                }
                else{                                 
                    $arr=array('error_msg' => 'No Users Found');
                    $jsonData = array(
                        'error_msg' => $arr
                    );

                    $jsonData = json_encode($jsonData);                    
                }
            }
            else {
                $res=array(array("error_msg"=>"Error"));
                $jsonData = json_encode($res);                
            }
        /*}
        else{
            $res=array(array("error_msg"=>"Select State Properly"));
            $jsonData = json_encode($res);
        }*/
        
    }    
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
