<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php"); 


    function runQuery($runQuery,$connection){            
        $execute_query = mysqli_query($connection, $runQuery);                        
        if ($execute_query) {   
            return $execute_query;                                                           
        }
        else {
            return "error";            
        }
    }

    function getUserPermission($user_id,$connection){        
        $query = "select * from permissions where user_id='".$user_id."'";        

        $results=runQuery($query,$connection);
        if($results==="error"){
            // return json
            $arr=array('error_msg' => 'Error');
            $jsonData = array(
                'error_msg' => $arr
            );
            $jsonData = json_encode($jsonData);
        }
        else{
                if(mysqli_num_rows($results)>0){                
                $jsonData = mysqli_fetch_all($results, MYSQLI_ASSOC);            
                
                // Close the database connection
                mysqli_close($connection);

                $arr=array($jsonData);
                $jsonData = array(
                    'users_data' => $arr
                );                                               
            }
            else{                             
                $arr=array('ret_msg' => 'No Permission assigned to this user');
                $jsonData = array(
                    'ret_msg' => $arr
                );
                $jsonData = json_encode($jsonData);            
            }
        }
        return $jsonData;
        
    }

            
    session_start();        

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"GET_USER_DATA",$connection);        
        if($checkPer==="1"){
            
            if(isset($_POST['data'])){
                $searchBy=$_POST['data']['type'];                   
                
                switch($searchBy){

                    case "per":                           
                        $user_id=$_POST['data']['user_id'];                        
                        $resultFromQuery=getUserPermission($user_id,$connection);                           
                        $jsonData=json_encode($resultFromQuery);                                        
                    break;

                    default:
                        $ret_msg="Error";
                        $return_data=array(
                            array(
                            "error_msg" => $ret_msg
                        ));
                        $jsonData=json_encode($return_data);
                    break;

                }                
            }
            else{
                $ret_msg="Error";
                $return_data=array(
                    array(
                    "error_msg" => $ret_msg
                ));
                $jsonData=json_encode($return_data);
            }
            
        }
        else{
            $ret_msg="You Do not have permission to this action";
            $return_data=array(
                array(
                "error_msg" => $ret_msg
            ));
            $jsonData=json_encode($return_data);
        }
    }    
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
