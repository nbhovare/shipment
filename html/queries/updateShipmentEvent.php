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
        $checkPer=checkPermission($curUserID,"trackshipment_php_UPDATE_SHIP_STATUS",$connection);        
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
                                                
                                                if($checkIfShipAtCurrFacilityQ_EQ_DT[0]['shipment_status']!="DELIVERED" &&
                                                $checkIfShipAtCurrFacilityQ_EQ_DT[0]['shipment_status']!="CANCEL"){                                                    
                                                
                                                switch($formData['activity']){     
                                                                                                        
                                                    // Activity == Arrived at facility
                                                    case "ARRIVED":                                                        
                                                        if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){

                                                            // To update Arrived Status check if previous activity is Marked a "FORWARD" or "RETURN" else throw Error                                                                                                                                                                             
                                                            $curStatus=getStatus($formData['shipment_id'],$connection);                                                                                                                               
                                                            if($curStatus['activity']==="RELEASE_ON_HOLD"){
                                                                $ret_msg="Shipment Already 'arrived' Marked at facility";
                                                                $return_data=array(
                                                                    array(
                                                                    "error_msg" => $ret_msg
                                                                ));
                                                                echo json_encode($return_data); 

                                                            }
                                                            else if($curStatus['activity']!=0 && $curStatus['activity']!="FORWARD" && $curStatus['activity']!="RETURN"){                                                                    
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
                                                                if($curStatus['activity']!="ARRIVED" && $curStatus['activity']!="CREATED" && $curStatus['activity']!="RELEASE_ON_HOLD"){  
                                                                    
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
                                                                    if(($curStatus['activity']=="ARRIVED" || $curStatus['activity']=="CREATED" || $curStatus['activity']=="RELEASE_ON_HOLD") && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){                                                                            
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

                                                        case "DELIVERED":
                                                            if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){
                                                                // To update DELIVERED Status check if previous activity is Marked a "OUT_FOR_DELIVERY" and current dest_id is current facility from which shipment is being marked DELIVERED else throw Error
                                                                $curStatus=getStatus($formData['shipment_id'],$connection);      
                                                                
                                                                if($curStatus['activity']==="FORWARD" && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){
                                                                    $ret_msg="Shipment not Arrived at facility, If already Arrived then update status as (Arrived) and take further action";
                                                                        $return_data=array(
                                                                            array(
                                                                            "error_msg" => $ret_msg
                                                                        ));
                                                                    echo json_encode($return_data);
                                                                }

                                                                else if($curStatus['activity']==="OUT_FOR_DELIVERY"){
                                                                    
                                                                    if($curStatus['activity']==="OUT_FOR_DELIVERY" && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){
                                                                        
                                                                        if(updateStatusInDb($formData,$curUserFacilityID,$curUserID,$connection)==1){                                                                                
                                                                            $ret_msg="Status Updated, Shipment Marked as DELIVERED";
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
                                                                    // Throw Error as previous status is not marked out for delivery
                                                                    $ret_msg="Error Updating Status/ Shipment not out for delivery ";
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
                                                        break;

                                                        case "OUT_FOR_DELIVERY":                                                            
                                                            $curStatus=getStatus($formData['shipment_id'],$connection);                                                            
                                                            if($curStatus['activity']==="DELIVERED"){
                                                                $ret_msg="Shipment is Already Delivered, For further action contact your ADMIN";
                                                                    $return_data=array(
                                                                        array(
                                                                        "error_msg" => $ret_msg
                                                                    ));
                                                                echo json_encode($return_data);                                                                
                                                            }
                                                            else{
                                                                if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){
                                                                    // To update OUT_FOR_DELIVERY Status check if previous activity is Marked a "ARRIVED" and current dest_id is current facility from which shipment is being forwarded or returned else throw Error
                                                                    $curStatus=getStatus($formData['shipment_id'],$connection);                                                                   
                                                                    if($curStatus['activity']!="ARRIVED" && $curStatus['activity']!="RELEASE_ON_HOLD"){  
                                                                        
                                                                        if($curStatus['activity']==="FORWARD" && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){
                                                                            $ret_msg="Shipment not Arrived at facility, If already Arrived then update status as (Arrived) and take further action";
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
                                                                        if(($curStatus['activity']=="ARRIVED" || $curStatus['activity']==="RELEASE_ON_HOLD") && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){                                                                            
                                                                                if(updateStatusInDb($formData,$curUserFacilityID,$curUserID,$connection)==1){                                                                                
                                                                                    $ret_msg="Status Updated, Shipment marked as ".$formData['activity'];
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
                                                                    $ret_msg="Shipment is already marked as OUT_FOR_DELIVERY ";
                                                                    $return_data=array(
                                                                        array(
                                                                        "error_msg" => $ret_msg
                                                                    ));
                                                                    echo json_encode($return_data); 
                                                                }                                                                
                                                            } 
                                                        break;

                                                        case "CANCEL":                                                                                                                    
                                                                $curStatus=getStatus($formData['shipment_id'],$connection);                                                            
                                                                if($curStatus['activity']==="DELIVERED"){
                                                                    $ret_msg="Shipment is Already Delivered, For further action contact your ADMIN";
                                                                        $return_data=array(
                                                                            array(
                                                                            "error_msg" => $ret_msg
                                                                        ));
                                                                    echo json_encode($return_data);                                                                
                                                                }
                                                                else{
                                                                    if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){
                                                                        // To update CANCEL Status check if previous activity is Marked a "ARRIVED" and current dest_id is current facility,
                                                                        // to mark status as CANCEL it should be marked 
                                                                        // as (arrived or release_on_hold or created) at curent facility 
                                                                        // additionally current status should not be marked as Delivered, forward, return, onhold
                                                                        // once marked as 'cancel' shipment cannot be in transit
                                                                        // further updates can be made by creating a push request, as gathering shipment details and creating a new shipment entry in dbs
                                                                        $curStatus=getStatus($formData['shipment_id'],$connection);                          
                                                                        if($curStatus['activity']==="ARRIVED" || $curStatus['activity']==="CREATED" || $curStatus['activity']==="RELEASE_ON_HOLD" ||
                                                                        $curStatus['activity']==="OUT_FOR_DELIVERY" ){                
                                                                                                                                                                                                                                            
                                                                                    if(updateStatusInDb($formData,$curUserFacilityID,$curUserID,$connection)==1){                                                                                
                                                                                        $ret_msg="Status Updated, Shipment marked as ".$formData['activity'];
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
                                                                                //if($curStatus['activity']!="ARRIVED" && $curStatus['activity']!="RELEASE_ON_HOLD"){
                                                                                if($curStatus['activity']==="ONHOLD"){
                                                                                    $ret_msg="Update Not Allowed";
                                                                                    $return_data=array(
                                                                                        array(
                                                                                        "error_msg" => $ret_msg
                                                                                    ));
                                                                                }
                                                                                
                                                                                else if(($curStatus['activity']==="FORWARD" || $curStatus['activity']==="RETURN") && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){
                                                                                    $ret_msg="Shipment not Arrived at facility, If already Arrived then update status as (Arrived) and take further action";
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
                                                                                //if($curStatus['activity']!="ARRIVED" && $curStatus['activity']!="RELEASE_ON_HOLD"){
                                                                            }                                                                   
                                                                        }
                                                                        else{   
                                                                            if($curStatus['activity']==="RETURN" || $curStatus['activity']==="FORWARD" || $curStatus['activity']==="ONHOLD"){
                                                                                $ret_msg="Update Not Allowed";
                                                                                $return_data=array(
                                                                                    array(
                                                                                    "error_msg" => $ret_msg
                                                                                ));
                                                                                echo json_encode($return_data);
                                                                            }
                                                                            else{
                                                                            
                                                                                
                                                                        }
            
                                                                    }                                                                                                                                  
                                                                } 
                                                            break;

                                                            case "ONHOLD":                                                            
                                                                $curStatus=getStatus($formData['shipment_id'],$connection);                                                                                                                                                                                            
                                                                if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){
                                                                    // To update ONHOLD Status check if
                                                                    // Shipment is Marked a "ARRIVED" or "RELEASE_ON_HOLD" and current dest_id is current facility 
                                                                    // check from which shipment is being forwarded or returned else throw Error
                                                                    $curStatus=getStatus($formData['shipment_id'],$connection);          
                                                                                                                                        
                                                                    $onHoldConditionMatrix_present=array();                                                                    
                                                                    array_push($onHoldConditionMatrix_present,"ARRIVED","RELEASE_ON_HOLD","OUT_FOR_DELIVERY");

                                                                    $isPresent = in_array($curStatus['activity'], $onHoldConditionMatrix_present);

                                                                    if ($isPresent && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID) {
                                                                        if(updateStatusInDb($formData,$curUserFacilityID,$curUserID,$connection)==1){                                                                                
                                                                            $ret_msg="Status Updated, Shipment marked as ".$formData['activity'];
                                                                            $return_data=array(
                                                                                array(
                                                                                "error_msg" => $ret_msg
                                                                            ));
                                                                            echo json_encode($return_data);                                                                                
                                                                        }
                                                                        else{                                                                                
                                                                            $ret_msg="Error Updating Status 1";
                                                                            $return_data=array(
                                                                                array(
                                                                                "error_msg" => $ret_msg
                                                                            ));
                                                                        echo json_encode($return_data);
                                                                    }
                                                                    }
                                                                    else {
                                                                        $onHoldConditionMatrix_absent=array();
                                                                        array_push($onHoldConditionMatrix_absent,"FORWARD","RETURN","DELIVERED","CANCEL","ONHOLD");
                                                                        $checkInAbsent=in_array($curStatus['activity'], $onHoldConditionMatrix_absent);
                                                                        if($checkInAbsent){
                                                                            $ret_msg="Error Updating Status 3";
                                                                            $return_data=array(
                                                                                array(
                                                                                "error_msg" => $ret_msg
                                                                            ));
                                                                            echo json_encode($return_data); 
                                                                        }
                                                                        else{
                                                                            $ret_msg="Error";
                                                                            $return_data=array(
                                                                                array(
                                                                                "error_msg" => $ret_msg
                                                                            ));
                                                                            echo json_encode($return_data); 
                                                                        }
                                                                    }

                                                                    
/*
                                                                    if($curStatus['activity']!="ARRIVED" && $curStatus['activity']!="RELEASE_ON_HOLD"){  
                                                                        
                                                                        if($curStatus['activity']==="FORWARD" && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){
                                                                            $ret_msg="Shipment not Arrived at facility, If already Arrived then update status as (Arrived) and take further action";
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
                                                                        if(($curStatus['activity']=="ARRIVED" || $curStatus['activity']==="RELEASE_ON_HOLD") && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){                                                                            
                                                                                if(updateStatusInDb($formData,$curUserFacilityID,$curUserID,$connection)==1){                                                                                
                                                                                    $ret_msg="Status Updated, Shipment marked as ".$formData['activity'];
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
                                                                    }*/
        
                                                                }
                                                                else{
                                                                    $ret_msg="Shipment is already marked as ONHOLD ";
                                                                    $return_data=array(
                                                                        array(
                                                                        "error_msg" => $ret_msg
                                                                    ));
                                                                    echo json_encode($return_data); 
                                                                }                                                                                                                            
                                                            break;


                                                            case "RETURN":
                                                                if(checkIfAlreadyExists($formData['activity'],$formData['shipment_id'],$connection)==0){
                                                                    // To update RETURN Status check if previous activity is Marked a "ARRIVED" and current dest_id is current facility from which shipment is being forwarded or returned else throw Error                                                                                                                                                                             
                                                                    $curStatus=getStatus($formData['shipment_id'],$connection);                                                                   
                                                                    if($curStatus['activity']==="CREATED"){
                                                                        // cannot be returned it can be cancelled as currently is in booked status
                                                                        $ret_msg="Return is not allowed at this movement, Can only be 'forwarded, cancelled, or placed on hold'";
                                                                                $return_data=array(
                                                                                    array(
                                                                                    "error_msg" => $ret_msg
                                                                                ));
                                                                                echo json_encode($return_data); 
                                                                    }
                                                                    else{
                                                                        if($curStatus['activity']!="ARRIVED" && $curStatus['activity']!="RELEASE_ON_HOLD"){  
                                                                        
                                                                            if($curStatus['activity']==="RETURN" && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){
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
                                                                            if(($curStatus['activity']=="ARRIVED" || $curStatus['activity']=="RELEASE_ON_HOLD") && $checkIfShipAtCurrFacilityQ_EQ_DT[0]['dest_id']===$curUserFacilityID){                                                                            
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
                                                $ret_msg="Shipment is '".$checkIfShipAtCurrFacilityQ_EQ_DT[0]['shipment_status']."', Hence further actions is not allowed";
                                                $return_data=array(
                                                    array(
                                                    "error_msg" => $ret_msg
                                                ));
                                                echo json_encode($return_data);
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
