<?php

    include("../includes/db_connect.php");
    session_start();
    //include("./db_connect.php");
    

    function getUserData($type,$id,$connection){
        
        $query = "select user_id, first_name, last_name, mobile_no, email_id, type, status, facility_id, state, country, city	 from users";
        if($type==="facID")
            $query.=" where facility_id='".$id."' order by facility_id";

        else if($type==="emailID")
            $query.=" where email_id='".$id."'";
        

        else if($type==="userID")
            $query.=" where user_id='".$id."'";

        else if($type==="listAll"){
        

            // Check if current user is Super Admin if yes then only show list of all users else show list for current user facility
            if($_SESSION['type']==="SADMIN")
                $query.="";
            else if($_SESSION['type']==="FADMIN")
                $query.=" where facility_id='".$_SESSION['facility_id']."' order by facility_id";

        }
        

        
        $results=mysqli_query($connection,$query);
        if($results){            
            
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
                    'error_msg' => "No Users"
                );
                //$jsonData = json_encode($jsonData);            
            }

        }
        else{
            // return json            
            $jsonData = array(
                'error_msg' => "Error"
            );
        }
        return $jsonData;        
    }


    function checkAdminOrNot($user_id,$connection){
        $checkIfAdminOrNotQ="SELECT * FROM users WHERE user_id='".$user_id."' AND type NOT IN ('SADMIN','FADMIN')";
        $checkIfAdminOrNotQ_EQ=mysqli_query($connection,$checkIfAdminOrNotQ);
        if($checkIfAdminOrNotQ_EQ){            
            if(mysqli_num_rows($checkIfAdminOrNotQ_EQ)>0){
                // It means user is not under SADMIN or FADMIN, hece updating is allowed based on certain user types
                return true;
            }
            else{

                // It means user is SADMIN or FADMIN hence return false and do not allow further updates to this kind of profiles
                return false;
            }
        }
        else{
            return false;
        }
    }


    function updateUserProfile($formData,$user_id,$connection){
        // Update User Profile
        $updateUserProfileQ="UPDATE users SET ";

        $firstIteration=true;
        foreach($formData as $key=>$value){
            // Check if current key is last one if yes do not append comma, else append comma at end 
            if($firstIteration){
                $updateUserProfileQ.=" ".$key."='".$value."'";
                $firstIteration=false;
            }
            else{
                $updateUserProfileQ.=", ".$key."='".$value."'";
            }
        }

        $updateUserProfileQ.=" WHERE user_id='".$user_id."'";        
        
        $updateUserProfileQ_EQ=mysqli_query($connection,$updateUserProfileQ);
        if($updateUserProfileQ_EQ){
            return 1;
        }
        else{
            return 0;
        }
    }

    if (isset($_POST['data'])) {
        
        
        if(isset($_POST['data']['type'])){
            $type=$_POST['data']['type'];                  
        
            if($type==="userID"){
                $response=getUserData($type,$_SESSION['user_id'],$connection);
                $jsonData = json_encode($response);    
                echo $jsonData;
            }

            if($type==="sendUserIDs"){
                
                $getUserID=$_POST['data']['user_id'];
                $response=getUserData("userID",$getUserID,$connection);
                $jsonData = json_encode($response);    
                echo $jsonData;
            }
            
            if($type==="updatePro"){                
                if(isset($_POST['data']['formData'])){                                    
                    $jsonData=$_POST['data']['formData'];
                    $formData = json_decode($jsonData, true);
                    if($formData!=null){                                                  
                        if(isset($_POST['data']['user_id'])){
                            $user_id_from_data=$_POST['data']['user_id'];
                            // check if user id thats received in data 
                            // is it SADMIN || FADMIN do not allow to update

                            $checkIfAdminOrNot=checkAdminOrNot($user_id_from_data,$connection);
                            if($checkIfAdminOrNot){
                                $resUpdate=updateUserProfile($formData,$user_id_from_data,$connection);                            
                            }
                            else{
                                $ret_msg="You cannot update details for this User";
                                $return_data=array(
                                    "ret_msg" => $ret_msg
                                );
                                $jsonData = json_encode($return_data);    
                                echo $jsonData;                                
                            }
                            
                        }
                        else{
                            
                            $resUpdate=updateUserProfile($formData,$_SESSION['user_id'],$connection);
                        }
                        
                        
                        if($resUpdate===1){
                            $ret_msg="Details Updated Successfully";
                            $return_data=array(
                                "ret_msg" => $ret_msg
                            );
                            $jsonData = json_encode($return_data);    
                            echo $jsonData;
                        }
                        else{
                            $ret_msg="Error Updating Details";
                            $return_data=array(
                                "ret_msg" => $ret_msg
                            );
                            $jsonData = json_encode($return_data);    
                            echo $jsonData;
                        }
                    }
                    else{
                        $ret_msg="Error";
                        $return_data=array(
                            "error_msg" => $ret_msg
                        );
                        $jsonData = json_encode($return_data);    
                        echo $jsonData;
                    }
                }
            }

            if($type==="updatePass"){
                if(isset($_POST['data']['curPass']) && isset($_POST['data']['newPass'])){                                    
                    $user_id=$_SESSION['user_id'];
                        // Update User Password
                        // first check if received user current password is correct if yes then only allow to change the password
                        // update new password if first condition is satisfied
                        
                        $curPass=$_POST['data']['curPass'];                        
                        $newPass=$_POST['data']['newPass'];
                        $checkIfCurPassIsCorrectQ="SELECT password FROM users WHERE user_id='".$user_id."'";
                        $checkIfCurPassIsCorrectQ_EQ=mysqli_query($connection,$checkIfCurPassIsCorrectQ);
                        if($checkIfCurPassIsCorrectQ_EQ){                            
                            $passFromDB=mysqli_fetch_all($checkIfCurPassIsCorrectQ_EQ,MYSQLI_ASSOC);                                                        
                            if($passFromDB[0]['password']===$curPass){          
                                
                                $formDataArray=array(
                                    "password" => $newPass
                                );
                                
                                $resUpdatePass=updateUserProfile($formDataArray,$user_id,$connection);
                                if($resUpdatePass===1){
                                    // return success
                                    $ret_msg="Password Updated Successfully";
                                    $return_data=array(
                                        "ret_msg" => $ret_msg
                                    );
                                    $jsonData = json_encode($return_data);    
                                    echo $jsonData;
                                }
                                else{

                                    $ret_msg="Error Updating Password";
                                    $return_data=array(
                                        "ret_msg" => $ret_msg
                                    );
                                    $jsonData = json_encode($return_data);    
                                    echo $jsonData;
                                }
                               
                            }
                            else{
                                // Current password is not correct tell user to retry
                                $ret_msg="Current password is Invalid";
                                $return_data=array(
                                    "error_msg" => $ret_msg
                                );
                                $jsonData = json_encode($return_data);    
                                echo $jsonData;
                            }
                        }
                        else{
                            $ret_msg="Error";                    
                            $return_data=array(
                                "error_msg" => $ret_msg
                            );
                            $jsonData = json_encode($return_data);    
                            echo $jsonData;
                        }                    
                }
                else{
                    $ret_msg="Error";                    
                    $return_data=array(
                        "error_msg" => $ret_msg
                    );
                    $jsonData = json_encode($return_data);    
                    echo $jsonData;
                }


            }


        }

        


        
    }
    else {
        $ret_msg="Error";
        $return_data = array(
            "error_msg" => $ret_msg
        );
        $jsonData = json_encode($return_data);   
        echo $jsonData; 
    }
                
    

?>
