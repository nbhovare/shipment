<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");  

    session_start();    
    
    function checkIfUserIsAlreadyFacAdmin($userID,$connection){        
        // Check if selected user_id is already not an admin facility if yes do not allow that seleted user to be admin for the facility
        $checkIfUserCanBeAdminQ="SELECT facility_name FROM `shipment_facility` where admin_user_id='".$userID."'";        
        $checkIfUserCanBeAdminQ_EQ=mysqli_query($connection,$checkIfUserCanBeAdminQ);
        if($checkIfUserCanBeAdminQ_EQ){
            if(mysqli_num_rows($checkIfUserCanBeAdminQ_EQ)>0){
                $row=mysqli_fetch_all($checkIfUserCanBeAdminQ_EQ,MYSQLI_ASSOC);                                
                return $row;
            }
            else{
                return 0;
            }
        }
        else{
            return "error";
        }
    }

    function checkifUserBelongsToAnotherFac($userID,$connection){        
        // Check if selected user_id is already associated to facility if yes do not allow that seleted user to be admin for the facility
        $checkifUserBelongsToAnotherFacQ="SELECT facility_id FROM `users` where user_id='".$userID."'";        
        $checkifUserBelongsToAnotherFacQ_EQ=mysqli_query($connection,$checkifUserBelongsToAnotherFacQ);
        if($checkifUserBelongsToAnotherFacQ_EQ){
            if(mysqli_num_rows($checkifUserBelongsToAnotherFacQ_EQ)>0){
                $row=mysqli_fetch_all($checkifUserBelongsToAnotherFacQ_EQ,MYSQLI_ASSOC);       
                $ansRow=$row[0]['facility_id'];
                if($ansRow==="Null" || $ansRow==="NULL" || $ansRow==="null" || $ansRow==="" || $ansRow===null){
                    return 1;
                }            
                else{
                    return 0;
                }                             
            }
            else{
                return "error";
            }
        }
        else{
            return "error";
        }
    }

    function createBucket($formData,$connection){
        // Create Bucket from received Data

        $firstIteration=true;
        $toFields="";
        $toValues="";
        foreach($formData as $key=>$value){
            
            $toFields.=($firstIteration)?$key:", ".$key;
            $toValues.=($firstIteration)?"'".$value."'":", '".$value."'";        
            $firstIteration=false;
        }

        $createBucketQ="INSERT INTO bucket (".$toFields.") VALUES (".$toValues.")"; 
        
        $createBucketQ_EQ=mysqli_query($connection,$createBucketQ);
        if($createBucketQ_EQ){
            return true;
        }
        else{
            return false;
        }
    }

    function checkIfAlreadyExists($bucket_id,$connection){
        // Check if bucket id already exists
        $checkIfBucketIdExistsOrNotQ="SELECT bucket_id FROM bucket WHERE bucket_id='".$bucket_id."'";
        $checkIfBucketIdExistsOrNotQ_EQ=mysqli_query($connection,$checkIfBucketIdExistsOrNotQ);
        if($checkIfBucketIdExistsOrNotQ_EQ){                        
            if(mysqli_num_rows($checkIfBucketIdExistsOrNotQ_EQ)>0){            
                return true;
            }                
            else{                
                return false;
            }
        }
        else{                        
            return -1;
        }
    }

    function searchBucket($searchBy,$bucket_id,$connection){
        $query="SELECT 

            bucket.bucket_id,
            bucket.bucket_name,                                    
            bucket.status,
            bucket.weight,
            bucket.additional_information,            
            bucket.create_date,
            bucket.dest_id,
            bucket.facility_id,
            users.first_name as created_by,
            sf0.facility_name as facility_name,            
            sf1.facility_name as dest_name            

            FROM bucket LEFT JOIN shipment_facility as sf0 ON sf0.facility_id=bucket.facility_id
            LEFT JOIN shipment_facility as sf1 ON sf1.facility_id=bucket.facility_id
            LEFT JOIN users ON users.user_id=bucket.created_by ";
            
        $isAdded=false;
        if($searchBy==="bucket_id"){
            $query.=" WHERE bucket.bucket_id='".$bucket_id."'";
            $isAdded=true;
        }
        

        if($_SESSION['type']==="FADMIN"){
            $query.=($isAdded)?" AND ":" WHERE ";
            $query.=" bucket.facility_id='".$_SESSION['facility_id']."'";
        }
            
        $searchBucketQ_EQ=mysqli_query($connection,$query);
        if($searchBucketQ_EQ){
            if(mysqli_num_rows($searchBucketQ_EQ)>0){
                $res=mysqli_fetch_all($searchBucketQ_EQ,MYSQLI_ASSOC);
              
                $return_data=array(
                    "bucket_data" => $res
                );
                echo json_encode($return_data);
            }
            else{
                $return_data=array(
                    "error_msg" => "No Data Found"
                );
                echo json_encode($return_data);
            }
        }
        else{
            $return_data=array(
                "error_msg" => "Error"
            );
            echo json_encode($return_data);
        }
        
    }


    function checkIfShipmentExistsOrNot($shipmentID,$getData,$connection){
        // Check if shipment ID exists or not

        $checkShipmentExistsOrNot="SELECT shipment_id,shipment_type,shipment_status,shipment_facility.facility_name,dest_id FROM shipment_details
        LEFT JOIN shipment_facility ON shipment_facility.facility_id=shipment_details.dest_id WHERE shipment_id='".$shipmentID."'";

        $checkShipmentExistsOrNot_EQ=mysqli_query($connection,$checkShipmentExistsOrNot);
        if($checkShipmentExistsOrNot_EQ){
            if(mysqli_num_rows($checkShipmentExistsOrNot_EQ)>0){
                if($getData)
                    return mysqli_fetch_all($checkShipmentExistsOrNot_EQ,MYSQLI_ASSOC);
                else
                    return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }


    }
    
    function addShipToBucket($bucketID,$shipmentIds,$connection){
        /* Add Shipments to Bucket
            # first check if shipment ID Exists or not
                ## if yes then check shipment should not be part of other bucket if so then throw error            
        */


        $updated_by=$_SESSION['user_id'];
        $facility_id=$_SESSION['facility_id'];

        $resForNotExists=array();
        $resForSucceed=array();
        $resForError=array();
        $resForPartOfOtherBucket=array();        
        
        foreach($shipmentIds as $shipmentID){
            // Check if shipment ID Exists or not
            if(checkIfShipmentExistsOrNot($shipmentID,false,$connection)){
                // Shipment Id Exists Now check if associated to other bucket or not
                if(checkifShipmentBelongsToAnotherBucket($shipmentID,$connection)){
                    // Not part of any bucket proceed to update 'ship_bucket table'

                    $updateBucketShipRelationship="INSERT INTO ship_bucket (bucket_id,shipment_id,updated_by,facility_id)
                        VALUES(?,?,?,?)";                    
                    $stmt = $connection->prepare($updateBucketShipRelationship);
                    $stmt->bind_param('ssii', $bucketID, $shipmentID, $updated_by, $facility_id);                    
                            if($stmt->execute()){
                            // Succeed
                            $resForSucceed[$shipmentID]="Successfully added to ".$bucketID." - bucket";                
                        }
                        else{
                            $resForError[$shipmentID]="Error while adding to bucket";
                        }                                                
                    }
                    else{
                        // Already Part of ther bucket cannot update
                        $resForPartOfOtherBucket[$shipmentID]="Shipment is part of other Bucket";
                    }
            }                
            else{
                // Return Error saying shipment ID does not exists
                $resForNotExists[$shipmentID]="Shipment ID does not exists";                                
            }
        }
        
        $finalResult=array(
            "resForSucceed"=>$resForSucceed,
            "resForPartOfOtherBucket"=>$resForPartOfOtherBucket,
            "resForNotExists"=>$resForNotExists,
            "resForError"=>$resForError
        );    
        return $finalResult;     
    }

    function checkifShipmentBelongsToAnotherBucket($shipmentID,$connection){
        // Check if belongs to other bucket if yes throw false else true

        $checkForInBucket="SELECT shipment_id FROM shipment_details WHERE shipment_id='".$shipmentID."' AND bucket_id IS NULL";        
        $checkForInBucket_EQ=mysqli_query($connection,$checkForInBucket);
        if($checkForInBucket_EQ){
            if(mysqli_num_rows($checkForInBucket_EQ)>0){
                //shipment does not belong to other bucket
                return true;
            }
            else{
                //shipment belong to other bucket
                return false;
            }
        }
        else{
            // Error while checking
            return -1;
        }

    }


    function checkIfShipUnderCurrentFacility($shipmentID,$fac_id,$connection){
        $checkFacQ="SELECT shipment_id FROM shipment_details WHERE shipment_id='".$shipmentID."' AND dest_id='".$fac_id."'";        
        $checkFacQ_EQ=mysqli_query($connection,$checkFacQ);        
        if($checkFacQ_EQ){
            if(mysqli_num_rows($checkFacQ_EQ)){
                // Shipment is under current facility
                return true;
            }
            else{
                // Not under current facility
                return false;
            }
        }
        else{
            return -1;
        }
    }

    function getShipCurrentStats($shipmentID,$connection){
        // Check for
        $checkForStatQ="SELECT shipment_status FROM shipment_details WHERE shipment_id='".$shipmentID."'";
        $checkForStatQ_EQ=mysqli_query($connection,$checkForStatQ);
        return $checkForStatQ_EQ;
    }


    

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"bucket_php",$connection);        
        if($checkPer==="1"){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {
                    
                    // Retrieve the JSON data from the POST request
                    $jsonData = $_POST['data'];

                    $type=$jsonData['type'];
                    switch($type){
                        case "createBucket":                            
                            $checkPer=checkPermission($curUserID,"bucket_php_CREATE_BUCKET",$connection);                                    
                            if($checkPer==="1"){                                
                                $formData = json_decode($jsonData['formData'], true);
                                if ($formData !== null) {                                     
                                    $formData['dest_id']=$_SESSION['facility_id'];
                                    $formData['created_by']=$_SESSION['user_id'];
                                    $formData['facility_id']=$_SESSION['facility_id'];
                                    $formData['created_by']=$_SESSION['user_id'];
                                    $formData['status']="CREATED";
                                                
                                    
                                    $resCheck=checkIfAlreadyExists($formData['bucket_id'],$connection);                                    
                                    if($resCheck==="-1"){
                                        $return_data=array(
                                            "error_msg" => "Error"
                                        );
                                        echo json_encode($return_data);
                                    }
                                    else if(!$resCheck){
                                        // Create Bucket Code
                                        $res=createBucket($formData,$connection);                                                                      
                                        if($res){
                                            // return succeed
                                            $return_data=array(
                                                "error_msg" => "Bucket Created Successfully"
                                            );
                                            echo json_encode($return_data);
                                        }
                                        else{
                                            // return  error
                                            $return_data=array(
                                                "error_msg" => "Error Creating Bucket"
                                            );
                                            echo json_encode($return_data);
                                        }
                                        // Create Bucket Code
                                    }                        
                                    else{
                                        $return_data=array(
                                            "error_msg" => "Bucket ID already Exists"
                                        );
                                        echo json_encode($return_data);
                                    }            
                                }
                                else{
                                    $return_data=array(
                                        "error_msg" => "Invalid Data"
                                    );
                                    echo json_encode($return_data);
                                }                            
                            }
                            else{
                                $return_data=array(
                                    "error_msg" => "You do not have permission to this action"
                                );
                                echo json_encode($return_data);
                            }

                        break;

                        case "searchBucket":
                            // Search bucket
                            $checkPer=checkPermission($curUserID,"bucket_php_VIEW_BUCKET",$connection);                                    
                            if($checkPer==="1"){                                
                                //$formData = json_decode($jsonData['formData'], true);                                                                                    
                                $searchBy=$jsonData['searchBy'];
                                               
                                if(isset($jsonData['searchBy'])){
                                    $searchBy=$jsonData['searchBy'];
                                    switch($searchBy){
                                        case "bucket_id":
                                            $bucket_id=$jsonData['bucket_id'];
                                            searchBucket("bucket_id",$bucket_id,$connection);
                                        break;

                                        case "list_all":
                                            searchBucket("list_all","",$connection);
                                        break;

                                        default:
                                            $return_data=array(
                                                "error_msg" => "Invalid Operation1"
                                            );
                                            echo json_encode($return_data);
                                        break;

                                    }
                                }
                                else{
                                    $return_data=array(
                                        "error_msg" => "Invalid Operation2"
                                    );
                                    echo json_encode($return_data);
                                }
                            }
                            else{
                                $return_data=array(
                                    "error_msg" => "You do not have permission to this action"
                                );
                                echo json_encode($return_data);
                            }

                        break;



                        case "addShipmentToBucket":
                            if($jsonData['shipToAdd'] && $jsonData['bucketID']){
                                // Fetch Shipment Ids from $jsonData['shipmentIds']
                                $shipmentIds=$jsonData['shipToAdd'];
                                $bucketID=$jsonData['bucketID'];
                                $res=addShipToBucket($bucketID,$shipmentIds,$connection);
                                echo json_encode($res);
                            }
                            else{
                                // Error need shipment ids for current operation
                                $return_data=array(
                                    "error_msg" => "Invalid Data"
                                );
                                echo json_encode($return_data);
                            }
                        break;

                        case "verifyShipmentID":

                            if($jsonData['shipmentID']){                                
                                $shipmentID=$jsonData['shipmentID'];                                
                                $res=checkIfShipmentExistsOrNot($shipmentID,true,$connection);                                
                                if(!$res){                                    
                                    // Throw Error Shipment ID Does Not exists
                                    $return_data=array(
                                        "error_msg" => "Shipment ID Does Not exists"
                                    );
                                    echo json_encode($return_data);
                                }
                                else{                                               
                                                                       
                                    // Not in bucket proceed which next steps
                                    // Now make sure shipment current status should not be from below list
                                    // "CANCEL", "FORWARD", "RETURN", "OUT_FOR_DELIVERY", "ONHOLD", "RELEASE_ON_HOLD", "DELIVERED"                                        
                                    // simply meaning shipment current status should be ARRIVED and should be in current facility queue


                                    $resFacCheck=checkIfShipUnderCurrentFacility($shipmentID,$_SESSION['facility_id'],$connection);                                         
                                    if($resFacCheck==="-1"){     
                                        echo "12";
                                        $return_data=array(
                                            "error_msg" => "Error"
                                        );
                                        echo json_encode($return_data);                                       
                                    }
                                    else if($resFacCheck){                                                           
                                        // Shipment is under current facility proceed to next steps                         
                                        $checkShipStatus=getShipCurrentStats($shipmentID,$connection);                                        
                                        if($checkShipStatus){                                                                                        
                                            if(mysqli_num_rows($checkShipStatus)>0){                                                
                                                $shipCurrStat=mysqli_fetch_all($checkShipStatus,MYSQLI_ASSOC);
                                                $curr_stat_from_db=$shipCurrStat[0]['shipment_status'];                                                
                                                $ret_msg_stat="";

                                                $cannotBeArr=array("CANCEL","DELIVERED","OUT_FOR_DELIVERY");
                                                $cannotBeButConArr=array("FORWARD","RETURN");
                                                $mustBeArr=array("CREATED","ARRIVED","RELEASE_ON_HOLD");

                                                if(in_array($curr_stat_from_db,$cannotBeArr)){
                                                    $ret_msg_stat="Shipment current status is ".$curr_stat_from_db.", Cannot proceed";                                                    
                                                }
                                                
                                                else if(in_array($curr_stat_from_db,$cannotBeButConArr)){
                                                    $ret_msg_stat="Shipment current status is ".$curr_stat_from_db.", First Mark as ARRIVED, then proceed to add in Bucket";                                                    
                                                }          
                                                
                                                else if($curr_stat_from_db==="ONHOLD"){
                                                    $ret_msg_stat="Shipment current status is ".$curr_stat_from_db.", First Release HOLD status then proceed to add in Bucket";                                                    
                                                }          

                                                else if(in_array($curr_stat_from_db,$mustBeArr)){                                                                                                            
                                                    
                                                    // Now check if shipment belongs to other bucket if yes throw errr cannot be added else continue
                                                    $checkForInBucket=checkifShipmentBelongsToAnotherBucket($shipmentID,$connection);
                                                    if($checkForInBucket==="-1"){
                                                        $return_data=array(
                                                            "error_msg" => "Error"
                                                        );
                                                        echo json_encode($return_data);
                                                    }                            
                                                    else if($checkForInBucket){                                                        
                                                        // Proceed for further actions
                                                        $return_data=array(
                                                            "ret_msg" => "add_to_bucket"
                                                        );
                                                        echo json_encode($return_data);    
                                                    }                                  
                                                    else{                                                                                                                                      
                                                        $return_data=array(
                                                            "error_msg" => "Shipment ID belongs to other Bucket"
                                                        );
                                                        echo json_encode($return_data);                                                        
                                                    }
                                                }    
                                                else{
                                                    $return_data=array(
                                                        "error_msg" => "Invalid"
                                                    );
                                                    echo json_encode($return_data);
                                                } 

                                                if($ret_msg_stat!=""){
                                                    $return_data=array(
                                                        "error_msg" => $ret_msg_stat
                                                    );
                                                    echo json_encode($return_data);
                                                }

                                                
                                            }      
                                            else{
                                                // Throw Error
                                                $return_data=array(
                                                    "error_msg" => "Shipment ID Does Not Exist"
                                                );
                                                echo json_encode($return_data);                                                    
                                            }                                                                                                                                                                                     
                                        }
                                        else{
                                            // Throw Error
                                            $return_data=array(
                                                "error_msg" => "Error"
                                            );
                                            echo json_encode($return_data);
                                        }
                                    }        
                                    else{
                                        // Throw Error shipment not under current facility
                                        $return_data=array(
                                            "error_msg" => "Shipment is not under your facility"
                                        );
                                        echo json_encode($return_data);
                                    }                                                                                                                                          
                                    
                                }                                
                            }
                            else{
                                // Invalid Shipment ID
                                $return_data=array(
                                    "error_msg" => "Invalid Data"
                                );
                                echo json_encode($return_data);
                            }

                        break;

                        case "getBucketCon":

                            // Get Bucket contents Details             

                            if($jsonData['bucketID']){
                                $bucket_id=$jsonData['bucketID'];
                                $getBucketContentsDetails="SELECT shipment_id,updated_by,ship_bucket.facility_id as facility_id,date,shipment_facility.facility_name as facility_name,users.first_name as first_name
                                    FROM ship_bucket 
                                    LEFT JOIN users ON users.user_id=ship_bucket.updated_by
                                    LEFT JOIN shipment_facility ON shipment_facility.facility_id=ship_bucket.facility_id
                                    WHERE bucket_id='".$bucket_id."' AND ship_bucket.status='1'";
                                    
                                $getBucketContentsDetails_EQ=mysqli_query($connection,$getBucketContentsDetails);
                                if($getBucketContentsDetails){
                                    if(mysqli_num_rows($getBucketContentsDetails_EQ)>0){
                                        $bucket_contents=mysqli_fetch_all($getBucketContentsDetails_EQ,MYSQLI_ASSOC);
                                        $return_data=array(
                                            "bucket_con" => $bucket_contents
                                        );
                                        echo json_encode($return_data);
                                    }
                                    else{
                                        $return_data=array(
                                            "error_msg" => "No Data Found"
                                        );
                                        echo json_encode($return_data);
                                    }
                                }
                                else{
                                    // Error
                                    $return_data=array(
                                        "error_msg" => "Error"
                                    );
                                    echo json_encode($return_data);
                                }
                            }
                            else{
                                // Error Invalid Data
                                $return_data=array(
                                    "error_msg" => "Error"
                                );
                                echo json_encode($return_data);
                            }

                        break;

                        default:
                            $return_data=array(
                                "error_msg" => "Invalid Function"
                            );
                            echo json_encode($return_data);
                        break;


                    }
                                                      
                    
                }
                else {
                                    
                    $return_data=array(
                        "error_msg" => "Invalid Data"
                    );
                    echo json_encode($return_data);

                }
            }
        }
        else{        
            $return_data=array(
                "error_msg" => "You Do not have permission to this action"
            );
            echo json_encode($return_data);
        }
    }

header("Content-Type: application/json");
?>
