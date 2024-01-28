<?php

    include("../includes/db_connect.php");
    session_start();
    //include("./db_connect.php");
    

    function getUserData($type,$id,$connection){
        
        $query = "select user_id, first_name, last_name, mobile_no, email_id, type, status, facility_id, state, country, city,address	 from users";
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


    function checkAdminOrNot($user_id,$facility_id,$connection){

    /*  
        this function checks wheather passed user_id is SADMIN OR FADMIN if yes return false stating cannot update such user
        details here, and additionally also check is user belongs to passed facility_id if not do not return false
    */

        $checkIfAdminOrNotQ="SELECT * FROM users WHERE user_id='".$user_id."' AND type NOT IN ('SADMIN','FADMIN') 
        AND facility_id='".$facility_id."'";

        
        
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
        echo $updateUserProfileQ;   
        
        $updateUserProfileQ_EQ=mysqli_query($connection,$updateUserProfileQ);
        if($updateUserProfileQ_EQ){
            return 1;
        }
        else{
            return 0;
        }
    }
    
    function checkAvailableCreditsInSystem($connection){
        // Get available credits and share back

        $checkAvailableCreditsQ="SELECT count(shipment_id) as countOfCredits FROM shipment_polls WHERE (user_id IS NULL AND alloted_by IS NULL AND status='0')";
        $checkAvailableCreditsQ_EQ=mysqli_query($connection,$checkAvailableCreditsQ);
        if($checkAvailableCreditsQ_EQ) {
            $res=mysqli_fetch_all($checkAvailableCreditsQ_EQ,MYSQLI_ASSOC);
            return $res[0]['countOfCredits'];
        }
        else{
            return -1;
        }

    }


    function getUserCurrentCredits($user_id,$connection){
        // Get User Current Available credits from DB and return
        
        
        $getCreditsFromDBQ="SELECT count(shipment_id) as countOfCredits FROM shipment_polls WHERE user_id='".$user_id."' AND status='0'";
        
        $getCreditsFromDBQ_EQ=mysqli_query($connection,$getCreditsFromDBQ);
        if($getCreditsFromDBQ_EQ){
            $res=mysqli_fetch_all($getCreditsFromDBQ_EQ,MYSQLI_ASSOC);
            return $res[0]['countOfCredits'];
        }
        else{
            return -1;
        }

    }

    function updateCredits($alloted_by,$user_id,$func,$creditsToUpdate,$connection){
        // Update Credits Based on $creditsToUpdate Variables
        // Credits = Shipment_id
        // Firstly Check if required credits are available in shipemnt_polls table
        // Secondly check if 'creditsToUpdate' is valid number andnnot less than 0
        // only update credits in shipment_polls table where for the specific client/customer where unUtilized
        // i.e. status = 0 & user_id equals to $user_id which is passed as paramater



        $ret_msg=null;

        switch($func){
            case "add":
                $res=checkAvailableCreditsInSystem($connection);
                if($res==="-1" || $res===-1){
                    $ret_msg=array(
                        "error_msg"=>"Error"
                    );
                }
                else if($res<$creditsToUpdate){                                            
                    $ret_msg=array(
                        "error_msg"=>"Not Enough Credits in system"
                    );
                }   
                else{   
                    $updateCreditsQ="UPDATE shipment_polls SET user_id='".$user_id."', alloted_by='".$alloted_by."' WHERE (status='0' AND user_id IS NULL AND alloted_by IS NULL) LIMIT ".$creditsToUpdate;                    
                    $updateCreditsQ_EQ=mysqli_query($connection,$updateCreditsQ);            
                    if($updateCreditsQ_EQ){
                        $ret_msg=array(
                            "ret_msg"=>"Credits Added Successfully"
                        );
                    }
                    else{
                        $ret_msg=array(
                            "error_msg"=>"Error"
                        );
                    }
                } 
            break;
                
            case "remove":                
                $getUserCountOfCredits=getUserCurrentCredits($user_id,$connection);                
                if($getUserCountOfCredits===-1){
                    $ret_msg=array(
                        "error_msg"=>"Error"
                    );
                }
                else if($creditsToUpdate<=$getUserCountOfCredits){
                    $removeCreditsQ="UPDATE shipment_polls SET user_id=NULL, alloted_by=NULL and status='0' WHERE user_id='".$user_id."' AND status='0' LIMIT ".$creditsToUpdate;
                    $removeCreditsQ_EQ=mysqli_query($connection,$removeCreditsQ);
                    if($removeCreditsQ_EQ){
                        $ret_msg=array(
                            "ret_msg"=>"Credits Removed Successfully"
                        );
                    }
                    else{
                        $ret_msg=array(
                            "error_msg"=>"Error"
                        );
                    }
                }
                else{
                    $ret_msg=array(
                        "error_msg"=>"!Invalid, credits to remove count greater that allocated credits"
                    );
                }                
            break;

            default:
                $ret_msg=array(
                    "error_msg"=>"Error"
                );
            break;
        }

        return $ret_msg;
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
            

            if($type==="getShipInfo"){
                // Get User Shipment Info
                if(isset($_POST['data']['user_id'])){                    

                    $shipData=array();

                    // get User ID from Data
                        $getUserID=$_POST['data']['user_id'];                        

                    // Get Shipment credits Information for User
                        $getShipCredits="select count(shipment_id) as credits from shipment_polls where user_id=? and status='0'";                        
                        $stmt = $connection->prepare($getShipCredits);
                        $stmt->bind_param("i", $getUserID); 
                        
                        if($stmt->execute()){                            
                            $stmt->bind_result($credits);
                            $stmt->fetch();
                            $shipData['credits']=$credits." Credits";
                            $stmt->close();

                            // Get Shipment Details after capturing credits details                                
                                $getShipDetailsQ="SELECT shipment_id FROM shipment_details WHERE create_by_admin_id='".$getUserID."'";
                                $getShipDetailsQ_EQ=mysqli_query($connection,$getShipDetailsQ);                                                                                                
                                if($getShipDetailsQ_EQ && mysqli_num_rows($getShipDetailsQ_EQ)>0){
                                    // get data and store in shipData variables to return to user
                                    $shipRes=mysqli_fetch_all($getShipDetailsQ_EQ,MYSQLI_ASSOC);
                                    $shipData['shipment_details']=$shipRes;
                                }
                                else{
                                    // Throw Error stating no Shipment details found or Error
                                    $shipData['shipment_details']="No Shipment Found";
                                }

                            // Sending Response to User
                                $jsonData = json_encode($shipData);    
                                echo $jsonData;                                             
                                $connection->close();       

                        }
                        else{
                            $ret_msg="Error Getting Informations";
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
                    exit;     
                }
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
                            
                            $checkIfAdminOrNot=checkAdminOrNot($user_id_from_data,$_SESSION['facility_id'],$connection);                            
                            if($checkIfAdminOrNot){                                
                                $resUpdate=updateUserProfile($formData,$user_id_from_data,$connection);                                                            
                            }
                            else{
                                $ret_msg="You cannot update details for this User";
                                $return_data=array(
                                    "error_msg" => $ret_msg
                                );
                                $jsonData = json_encode($return_data);    
                                echo $jsonData;        
                                exit;                        
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

            if($type==="updateCount"){
                // Add credits to Client/Customers Account

                if(isset($_POST['data']['creditsToUpdate'])){
                    $creditsToUpdate=$_POST['data']['creditsToUpdate'];
                    if($creditsToUpdate>0){
                        $user_id=$_POST['data']['user_id'];
                        $func=$_POST['data']['typeOfUpdate'];
                        $resFromUpdateQ=updateCredits($_SESSION['user_id'],$user_id,$func,$creditsToUpdate,$connection);                                                
                        $jsonData = json_encode($resFromUpdateQ);    
                        echo $jsonData;                                                
                    }
                    else{
                        // Throw Error As creditsToUpdate is less than 0 cannot be negative number
                            $ret_msg="!Invalid, Credits to add should be greater than 0";
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
                                        "error_msg" => $ret_msg
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
