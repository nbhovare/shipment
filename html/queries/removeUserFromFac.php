<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");  

    session_start();        

    function checkIfUserBelongToCurFac($user_id,$facility_id,$connection){
        $checkQ="SELECT user_id FROM users WHERE user_id='".$user_id."' AND facility_id='".$facility_id."'";        
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

    function getUserData($type,$id,$connection,$specificField){

        
        $query = "select user_id, first_name, last_name, mobile_no, email_id, type, create_date, status, facility_id,created_by	 from users";

        if($type==="userID")
            $query.=" where user_id='".$id."'";

        /*if($type==="facID")
            $query.=" where facility_id='".$id."' order by facility_id";

        /*else if($type==="emailID")
            $query.=" where email_id='".$id."'";
        
        else if($type==="listAll")
            $query.="";*/
            
        $results=mysqli_query($connection,$query);
        if($specificField!="0"){
            if(mysqli_num_rows($results)>0){
                $res=mysqli_fetch_all($results,MYSQLI_ASSOC);
                return $res[0]['email_id'];
            }
            else{
                return "Not Exist";
            }                  
        }
        else{
            return $results;        
        }                 
    }

    function removeUserPermission($user_id,$connection){
        $removeUserPermissionQ="DELETE FROM permissions where user_id='".$user_id."'";
        $removeUserPermissionQ_EQ=mysqli_query($connection,$removeUserPermissionQ);
        if($removeUserPermissionQ_EQ){
            return "1";        
        }
        else{
            return "0";
        }
    }


    function updateUserdata($user_id,$combined,$connection){
        $updateUserDetails="UPDATE users set ";
                            
            foreach($combined as $fields => $values){
                $updateUserDetails.=" ".$fields."=".$values."";
            }
            $updateUserDetails.=" WHERE user_id='".$user_id."'";                           
            $updateUserDetails_EQ=mysqli_query($connection,$updateUserDetails);
            
            if($updateUserDetails_EQ){
                return 1;
            }
            else{
                return 0;
            }        

    }

    function removeUserFromfac($user_id_arr,$facility_id,$connection){
        $return_res="<ul>";
        $count=0;
        foreach ($user_id_arr as $user_id) {            
            $usrDatares=getUserData("userID",$user_id,$connection,"email_id");            
            if($usrDatares==="Not Exist"){
                $count++;
            }   
            else{                                
                $checkRes=checkIfUserBelongToCurFac($user_id,$facility_id,$connection);                            
                if($checkRes===1){     
                    if($_SESSION['user_id']===$user_id){
                        $return_res.="<li>Error:  You cannot remove yourself from facility Contact Admin</li>";
                    }   
                    else{
                                                         
                        //Remove user from facility hence null/update 0 in fac_id iin users table for specific user entry           
                        $assocArray = []; // Creating an empty associative array
                                            
                            $assocArray['facility_id'] = "NULL";                                                                         
                        $updateUserFac=updateUserdata($user_id,$assocArray,$connection);                                        
                        if($updateUserFac===1){
                            $removeUserPer=removeUserPermission($user_id,$connection);
                            if($removeUserPer===1){
                                $return_res.="<li>Success: User removed from facility ".$usrDatares."</li>";
                            }   
                            else{
                                $return_res.="<li>Success: User removed from facility ".$usrDatares."</li>";
                            }
                        }                
                        else{
                            $return_res.="<li>Error: Cannot User removed from facility ".$usrDatares."</li>";
                        }   
                    }                 
                }
                else if($checkRes===0){                             
                    //Remove user from facility hence null/update 0 in fac_id iin users table for specific user entry
                    $return_res.="<li>Error: User not under your facility ".$usrDatares."</li>";
                }
                else if($checkRes===-1){
                    $return_res.="<li>Error: while removing user ".$usrDatares."</li>";
                }
            }
            
        }
        
        if($count>0){
            $return_res.="<li>Error: ".$count." User Ids not found</li>";
        }
        $return_res.="</ul>";        
        return $return_res;
    }

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"users_php_REMOVE_USER_FROM_FAC",$connection);        
        if($checkPer==="1"){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {    
                    if (isset($_POST['data']['user_id'])) {                        
                        $user_id_arr=$_POST['data']['user_id'];
                        $curUserFacilityId=$_SESSION['facility_id'];   
                                                
                        
                        $ret_msg=removeUserFromfac($user_id_arr,$curUserFacilityId,$connection);                        
                        
                        $return_data=array(
                            array(
                            "ret_msg" => $ret_msg
                        ));
                        echo json_encode($return_data);
                    }                                      
                    else{
                        $ret_msg="Invalid User ID";        
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
