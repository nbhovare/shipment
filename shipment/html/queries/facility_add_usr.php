<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php"); 
            
    session_start();        


    function checkIfUsrExists($usr_email_id,$connection){
        // Function to checkk if user exists if yes return user data (Current_Facility_ID) else return error
        $checkIfUsrExistsQ="SELECT email_id,facility_id FROM users WHERE email_id='".$usr_email_id."'";        
        $checkIfUsrExistsQ_EQ=mysqli_query($connection,$checkIfUsrExistsQ);
        if($checkIfUsrExistsQ_EQ && (mysqli_num_rows($checkIfUsrExistsQ_EQ)>0)){            
            $usrDataFromDB=mysqli_fetch_all($checkIfUsrExistsQ_EQ,MYSQLI_ASSOC);
            return $usrDataFromDB;
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
        $curUserFacID=$_SESSION['facility_id'];
        $checkPer=checkPermission($curUserID,"facility_php_MANAGE_USERS",$connection);        
        if($checkPer==="1"){
            
            if(isset($_POST['data'])){
                $email_id=$_POST['data']['email_id'];
                $type_of_usr=$_POST['data']['type'];                   
                $resFromCheckFun=checkIfUsrExists($email_id,$connection);                
                if($resFromCheckFun!="error"){    
                    if($_SESSION['type']!="SADMIN" && $type_of_usr==="FADMIN"){
                        $ret_msg="Error Not Allowed to add Facility Admin";
                        $return_data=array(
                            "error_msg" => $ret_msg
                        );
                        echo json_encode($return_data);                         
                    }               
                    else{
                        if($resFromCheckFun[0]['facility_id']===null){                                                
                            // Add Usr to Facility
                            $addUsrToFacQ="UPDATE users SET facility_id='".$curUserFacID."' , type='".$type_of_usr."' WHERE email_id='".$email_id."'";
                            
                            $addUsrToFacQ_EQ=mysqli_query($connection,$addUsrToFacQ);
                            if($addUsrToFacQ_EQ){
                                $ret_msg="Successfully added ".$email_id." to your facility";
                                $return_data=array(
                                    "error_msg" => $ret_msg
                                );
                                echo json_encode($return_data); 
                            }
                            else{
                                $return_data=array(
                                    "error_msg" => "Error"
                                );                         
                                echo json_encode($return_data);    
                            }
                        }
                        else if($resFromCheckFun[0]['facility_id']===$curUserFacID){
                            //
                            $return_data=array(
                                "error_msg" => "User Already added to your facility"
                            );
                            echo json_encode($return_data); 
                        }
                        else{
                            
                            $return_data=array(
                                "error_msg" => "User Belongs to other Facility"
                            );
                            echo json_encode($return_data); 
                        }
                    }
                }
                else{
                    $return_data=array(
                        "error_msg" => "User-ID/Email-ID Does not Exist"
                    );
                    echo json_encode($return_data);    
                }                
                            

                // Get Facility Data and return                
                //$execute_query = mysqli_query($connection, $query);                                        

                
                
            }
            else{                
                $return_data=array(
                    "error_msg" => "Error"
                );
                echo json_encode($return_data);
            }
            
        }
        else{            
            $return_data=array(
                "error_msg" => "You Do not have permission to this action"
            );
            echo json_encode($return_data);
        }
    }    
                
    // Set headers and send the JSON response    


?>
