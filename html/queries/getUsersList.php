<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");
            
    session_start();        

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"VIEW_USERS",$connection);        
        if($checkPer==="1"){                                      
                $query = "select user_id, first_name, last_name, mobile_no, email_id ";            

                if(isset($_POST['data']['facility_id'])){
                    $facility_id=$_POST['data']['facility_id'];
                    $query=$query.", type, status from users where facility_id='".$facility_id."'";
                }
                else{
                    $query=$query." from users";
                }

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
        }
        else{
            $res=array(array("error_msg"=>"You Do not have permission to this action"));
            $jsonData = json_encode($res);  
        }

    }    
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
