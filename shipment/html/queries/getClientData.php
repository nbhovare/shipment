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

    function getUserData($type,$id,$connection){

        
        $query = "select user_id, first_name, last_name, mobile_no, email_id, type, create_date, status, facility_id,created_by	 from users";
        if($type==="facID")
            $query.=" where facility_id='".$id."'";

        else if($type==="emailID")
            $query.=" where email_id='".$id."'";
        
        else if($type==="listAll"){
        

            // Check if current user is Super Admin if yes then only show list of all users else show list for current user facility
            if($_SESSION['type']==="SADMIN")
                $query.="";
            else if($_SESSION['type']==="FADMIN")
                $query.=" where facility_id='".$_SESSION['facility_id']."'";

        }

        $query.=" AND type='CLIENT' order by facility_id";
            
        $results=runQuery($query,$connection);
        if($results==="error"){
            // return json            
            $jsonData = array(
                'error_msg' => "Error"
            );
            
        }
        else{
            if(mysqli_num_rows($results)>0){
                $jsonData = mysqli_fetch_all($results, MYSQLI_ASSOC);            
                
                // Close the database connection
                mysqli_close($connection);

                //$arr=array($jsonData);
                $jsonData = array(
                    'users_data' => $jsonData
                );                                               
            }
            else{                                             
                $jsonData = array(
                    'error_msg' => "No such Clients/Customers"
                );
                //$jsonData = json_encode($jsonData);            
            }
        }
        return $jsonData;        
    }

    function getUserPermission($user_id,$perForPage,$connection){        
        $query = "select * from permissions where user_id='".$user_id."' AND permission_type LIKE '".$perForPage."%'";        

        $results=runQuery($query,$connection);
        if($results==="error"){
            // return json
            
            $jsonData = array(
                'error_msg' => "Error"
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
                $jsonData = array(
                    'error_msg' => "No Permission assigned to this user"
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
        $checkPer=checkPermission($curUserID,"client_php_VIEW_CLIENT",$connection);        
        if($checkPer==="1"){
            
            if(isset($_POST['data'])){
                $searchBy=$_POST['data']['type'];                   
                
                switch($searchBy){

                    case "per":                           
                        $user_id=$_POST['data']['user_id'];          
                        $perFor=$_POST['data']['perFor'];                         
                        $resultFromQuery=getUserPermission($user_id,$perFor,$connection);                           
                        $jsonData=json_encode($resultFromQuery);                                        
                    break;

                    case "facID":
                        if(isset($_POST['data']['facility_id'])){
                            $facility_id=$_POST['data']['facility_id'];
                            $resultFromQuery=getUserData($searchBy,$facility_id,$connection);
                            $jsonData=json_encode($resultFromQuery);
                        }
                        else{

                            $return_data=array(
                                "error_msg" => "Error wrong request"
                            );
                            $jsonData=json_encode($return_data);
                        }
                    break;

                    case "emailID":
                        if(isset($_POST['data']['email_id'])){
                            $email_id=$_POST['data']['email_id'];
                            $resultFromQuery=getUserData($searchBy,$email_id,$connection);
                            $jsonData=json_encode($resultFromQuery);
                        }
                        else{                            
                            $return_data=
                                array(
                                "error_msg" => "Error wrong request"
                            );
                            $jsonData=json_encode($return_data);
                        }
                    break;

                    case "listAll":
                        if(isset($_POST['data']['list_all'])){                            
                            $resultFromQuery=getUserData($searchBy,0,$connection);
                            $jsonData=json_encode($resultFromQuery);
                        }
                        else{                            
                            $return_data=
                                array(
                                "error_msg" => "Error wrong request"
                            );
                            $jsonData=json_encode($return_data);
                        }
                    break;

                    default:                        
                        $return_data=
                            array(
                            "error_msg" => "Error"
                        );
                        $jsonData=json_encode($return_data);
                    break;

                }                
            }
            else{                
                $return_data=array(
                    "error_msg" => "Error"
                );
                $jsonData=json_encode($return_data);
            }
            
        }
        else{            
            $return_data=
                array(
                "error_msg" => "You Do not have permission to this action"
            );
            $jsonData=json_encode($return_data);
        }
    }    
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
