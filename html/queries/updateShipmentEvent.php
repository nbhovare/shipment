<?php

    include("../includes/db_connect.php");
    include("../includes/check_shipment.php");
    include("../includes/check_permission.php");
    
    session_start();        
    
    function getStatus($shipment_id,$connection){
        
        // function to get & return current shipment status for provided shipment id 
        $getCurStatQ="SELECT * FROM `shipment_events` WHERE shipment_id='".$shipment_id."' ORDER BY event_id DESC LIMIT 1";        
        $getCurStatQ_EQ=mysqli_query($connection,$getCurStatQ);        
        if($getCurStatQ_EQ || mysqli_num_rows($getCurStatQ_EQ)>0){
            $res=mysqli_fetch_all($getCurStatQ_EQ, MYSQLI_ASSOC);
            return $res[0];
        }
        else
            return 0;        
    }

    function updateStatusInDb($formDt,$curUserFacilityID,$curUserID,$connection){
        // Begin a transaction
        mysqli_begin_transaction($connection);        

        $releaseonHoldStatusQ="UPDATE shipment_details SET shipment_status='".$formDt['activity']."'";    
        
        // Check if shipment is requesting to be forwarded or returned if yes check for next_facility_id and update as dest_id else continue with no change
        if($formDt['activity']==="FORWARD" || $formDt['activity']==="RETURN"){
            if($formDt['facility_id']!=-1 && $formDt['facility_id']!=null)
                $releaseonHoldStatusQ=$releaseonHoldStatusQ.", dest_id='".$formDt['facility_id']."'";            
        }  
        
        $releaseonHoldStatusQ=$releaseonHoldStatusQ." where shipment_id='".$formDt['shipment_id']."'";
        
        $releaseonHoldStatusQ_EQ=mysqli_query($connection,$releaseonHoldStatusQ);
        
        if($releaseonHoldStatusQ_EQ){

            $fields="date,time,remarks,facility_id,event_by_user_id,activity,shipment_id";
            $values="'".$formDt['date']."','".$formDt['date']." ".(date("h:i:s"))."','".$formDt['remarks']."','".$curUserFacilityID."','".$curUserID."','".$formDt['activity']."','".$formDt['shipment_id']."'";
            
            // Check if shipment is requesting to be forwarded or returned if yes check for next_facility_id else continue with no change
            if($formDt['activity']==="FORWARD" || $formDt['activity']==="RETURN"){
                if($formDt['facility_id']!=-1 && $formDt['facility_id']!=null){
                    $fields=$fields.(($formDt['activity']==="FORWARD")?",forward_to":",return_to");
                    $values=$values.",'".$formDt['facility_id']."'";
                }
            }            

            $insertIntoEventForReleaseQ="INSERT INTO shipment_events (".$fields.") values(".$values.")";            

            $insertIntoEventForReleaseQ_EQ=mysqli_query($connection,$insertIntoEventForReleaseQ);                                                
            if($insertIntoEventForReleaseQ_EQ){                
                mysqli_commit($connection);                                
                return 1;
            }
            else{
                mysqli_rollback($connection);
                return 0;
            }                                                                                                
        }
        else{
            mysqli_rollback($connection);
            return 0;
        }
    }

    function checkIfAlreadyExists($statusToUpdate,$shipment_id,$connection){
        $checkShipQ="SELECT shipment_status FROM shipment_details WHERE shipment_id='".$shipment_id."' AND shipment_status='".$statusToUpdate."'";
        $checkIfShipQ_EQ=mysqli_query($connection,$checkShipQ);
        if($checkIfShipQ_EQ && (mysqli_num_rows($checkIfShipQ_EQ)>0))
            return 1;
        else{
            return 0;
        }
    }

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        

        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"MODIFY_SHIP_STATUS",$connection);        
        if($checkPer==="1"){
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {
                    
                    // Retrieve the JSON data from the POST request
                    $jsonData = $_POST['data'];

                    // Decode the JSON data into an array of objects
                    $formData = json_decode($jsonData, true);

                    if ($formData !== null) {
                                                
                        $checkShipment=checkIfShipmentExists($formData["shipment_id"],$connection);                        
                        
                        if($checkShipment!="failed"){                            
                            if(!mysqli_num_rows($checkShipment)>0){                                
                                $ret_msg="Shipment ID Does not exist";
                                $return_data=array(
                                    array(
                                    "error_msg" => $ret_msg
                                ));
                                echo json_encode($return_data);
                            }
                            else{

                                // check if shipment is under current facility queue if not throw error not allowed
                                $curUserFacilityID=$_SESSION['facility_id'];
                                $checkIfShipAtCurrFacilityQ="SELECT shipment_status,dest_id FROM shipment_details WHERE shipment_id='".$formData['shipment_id']."' AND dest_id='".$curUserFacilityID."'";
                                $checkIfShipAtCurrFacilityQ_EQ=mysqli_query($connection,$checkIfShipAtCurrFacilityQ);
                                if($checkIfShipAtCurrFacilityQ_EQ){
                                    if(mysqli_num_rows($checkIfShipAtCurrFacilityQ_EQ)>0){                                        

                                        if($formData['activity']==="RELEASE_ON_HOLD"){                                            
                                            if(getStatus($formData['shipment_id'],$connection)['activity']=="ONHOLD"){
                                                // check if current status equals RELEASE_ON_HOLD if not allow to update the status 
                                                if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){                                                    
                                                    $res=updateStatusInDb($formData,$curUserFacilityID,$curUserID,$connection);                                                    
                                                    if($res==1){
                                                        $ret_msg="Status Updated";
                                                        $return_data=array(
                                                            array(
                                                            "error_msg" => $ret_msg
                                                        ));
                                                        echo json_encode($return_data);
                                                    }
                                                    else{
                                                        $ret_msg="Error Updating Status";
                                                        $return_data=array(
                                                            array(
                                                            "error_msg" => $ret_msg
                                                        ));
                                                        echo json_encode($return_data);
                                                    }
                                                }
                                                else{
                                                    $ret_msg="Status is already updated";
                                                    $return_data=array(
                                                        array(
                                                        "error_msg" => $ret_msg
                                                    ));
                                                    echo json_encode($return_data); 
                                                }
                                            }
                                            else{
                                                $ret_msg="Invalid, Shipment is not ONHOLD";
                                                $return_data=array(
                                                    array(
                                                    "error_msg" => $ret_msg
                                                ));
                                                echo json_encode($return_data);  
                                            }                                            

                                        }
                                        else{                                           

                                            $checkIfShipAtCurrFacilityQ_EQ_DT=mysqli_fetch_all($checkIfShipAtCurrFacilityQ_EQ, MYSQLI_ASSOC);
                                            
                                            if($checkIfShipAtCurrFacilityQ_EQ_DT[0]['shipment_status']!="ONHOLD"){                                                
                                                switch($formData['activity']){                                                    
                                                    // Activity == Arrived at facility
                                                    case "ARRIVED":                                                        
                                                        if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){

                                                            // To update Arrived Status check if previous activity is Marked a "FORWARD" or "RETURN" else throw Error                                                                                                                                                                             
                                                            $curStatus=getStatus($formData['shipment_id'],$connection);                                                                   
                                                            if($curStatus['activity']!=0 && $curStatus['activity']!="FORWARD" && $curStatus['activity']!="RETURN"){                                                                    
                                                                // Throw Error                                                                                                                             
                                                                $ret_msg="Error Updating Status";
                                                                $return_data=array(
                                                                    array(
                                                                    "error_msg" => $ret_msg
                                                                ));
                                                                echo json_encode($return_data); 
                                                            }
                                                            else{                                                                                                                                                                                                                   
                                                                if(($curStatus['activity']=="FORWARD" || $curStatus['activity']=="RETURN")
                                                                && ($curStatus['forward_to']===$curUserFacilityID || $curStatus['return_to']===$curUserFacilityID)){                                                                        
                                                                        if(updateStatusInDb($formData,$curUserFacilityID,$curUserID,$connection)==1){                                                                                
                                                                            $ret_msg="Status Updated";
                                                                            $return_data=array(
                                                                                array(
                                                                                "error_msg" => $ret_msg
                                                                            ));
                                                                            echo json_encode($return_data);                                                                                
                                                                        }
                                                                        else{                                                                                
                                                                            $ret_msg="Error Updating Status";
                                                                            $return_data=array(
                                                                                array(
                                                                                "error_msg" => $ret_msg
                                                                            ));
                                                                        echo json_encode($return_data);
                                                                    }
                                                                }
                                                                else{
                                                                    $ret_msg="Error Updating Status";
                                                                        $return_data=array(
                                                                            array(
                                                                            "error_msg" => $ret_msg
                                                                        ));
                                                                    echo json_encode($return_data);
                                                                }
                                                            }

                                                        }
                                                        else{
                                                            $ret_msg="Status is already updated";
                                                            $return_data=array(
                                                                array(
                                                                "error_msg" => $ret_msg
                                                            ));
                                                            echo json_encode($return_data); 
                                                        }
                                                        break;
                                                        // Activity == Arrived at facility
                                                        
                                                        // Activity == Forward to another facility
                                                        case "FORWARD":
                                                            if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){
                                                                // To update FORWARD or RETURN Status check if previous activity is Marked a "ARRIVED" and current dest_id is current facility from which shipment is being forwarded or returned else throw Error                                                                                                                                                                             
                                                                $curStatus=getStatus($formData['shipment_id'],$connection);                                                                   
                                                                if($curStatus['activity']!="ARRIVED" && $curStatus['activity']!="CREATED"){  
                                                                    
                                                                    if($curStatus['activity']==="FORWARD" && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){
                                                                        $ret_msg="Current Status is Arriving, first mark as Arrived and then try updating '".$formData['activity']."' status";
                                                                            $return_data=array(
                                                                                array(
                                                                                "error_msg" => $ret_msg
                                                                            ));
                                                                        echo json_encode($return_data);
                                                                    }
                                                                    else{                                                                    
                                                                        // Throw Error                                                                                                                                                                                                 
                                                                        $ret_msg="Error Updating Status";
                                                                        $return_data=array(
                                                                            array(
                                                                            "error_msg" => $ret_msg
                                                                        ));
                                                                        echo json_encode($return_data); 
                                                                    }
                                                                }
                                                                else{   
                                                                    // Check if current status in shipment_events table is "ARRIVED" & dest_id equals current_facility then only allow update status else throw error                                                                                                                                                                                                                 
                                                                    if(($curStatus['activity']=="ARRIVED" || $curStatus['activity']=="CREATED") && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){                                                                            
                                                                            if(updateStatusInDb($formData,$curUserFacilityID,$curUserID,$connection)==1){                                                                                
                                                                                $ret_msg="Status Updated, Shipment is ".$formData['activity']." to selected facility";
                                                                                $return_data=array(
                                                                                    array(
                                                                                    "error_msg" => $ret_msg
                                                                                ));
                                                                                echo json_encode($return_data);                                                                                
                                                                            }
                                                                            else{                                                                                
                                                                                $ret_msg="Error Updating Status";
                                                                                $return_data=array(
                                                                                    array(
                                                                                    "error_msg" => $ret_msg
                                                                                ));
                                                                            echo json_encode($return_data);
                                                                        }
                                                                    }                                                                    
                                                                    else{
                                                                        $ret_msg="Error Updating Status";
                                                                            $return_data=array(
                                                                                array(
                                                                                "error_msg" => $ret_msg
                                                                            ));
                                                                        echo json_encode($return_data);
                                                                    }
                                                                }
    
                                                            }
                                                            else{
                                                                $ret_msg="Status is already updated";
                                                                $return_data=array(
                                                                    array(
                                                                    "error_msg" => $ret_msg
                                                                ));
                                                                echo json_encode($return_data); 
                                                            }                                                        
                                                        break;
                                                        // Activity == Forward to another facility

                                                        default:
                                                            $ret_msg="Sorry";
                                                                $return_data=array(
                                                                    array(
                                                                    "error_msg" => $ret_msg
                                                                ));
                                                                echo json_encode($return_data); 
                                                        break;
                                                    
                                                }
                                                
                                            }
                                            else{
                                                $ret_msg="Current Shipment Status = ON-HOLD, First Release ON-HOLD status, then try updating status";
                                                $return_data=array(
                                                    array(
                                                    "error_msg" => $ret_msg
                                                ));
                                                echo json_encode($return_data);
                                            }                                                                                
                                        }                                        
                                    }
                                    else{
                                        $ret_msg="Shipment not under your Queue/Not allowed to update details";
                                        $return_data=array(
                                            array(
                                            "error_msg" => $ret_msg
                                        ));
                                        echo json_encode($return_data);
                                    }
                                }
                                else{
                                    $ret_msg="Failed";
                                    $return_data=array(
                                        array(
                                        "error_msg" => $ret_msg
                                    ));
                                    echo json_encode($return_data);
                                }                                                             
                            }
                        }                        
                        else{                            
                            $ret_msg="Failed";
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
