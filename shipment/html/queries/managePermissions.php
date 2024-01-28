<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php");  

    session_start();   
    
    function updateUserPermission($permissions,$user_id,$perType,$conn){
        
            // Permissions array containing multiple permission types
            //$permissions = ['permission_type_1', 'permission_type_2', 'permission_type_3'];
            
        try {

            $delCurPerQ="DELETE FROM permissions WHERE user_id='".$user_id."' and permission_type LIKE '".$perType."%'";            
            $delCurPerQ_EQ=mysqli_query($conn,$delCurPerQ);
            if($delCurPerQ_EQ){
                foreach ($permissions as $permission) {
                    $sql = "INSERT INTO permissions (user_id, permission_type) 
                            SELECT ?, ? 
                            WHERE NOT EXISTS (
                                SELECT 1
                                FROM permissions
                                WHERE user_id = ? AND permission_type = ?
                            )";
            
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssss', $user_id, $permission, $user_id, $permission);
                    $stmt->execute();

                    
                }            
                
                
                $ret_msg="Permissions inserted successfully!";
                $return_data=array(
                    array(
                    "ret_msg" => $ret_msg
                ));
                echo json_encode($return_data);                
            }
            else{
                $ret_msg="Permissions inserted successfully!";
                $return_data=array(
                    array(
                    "ret_msg" => $ret_msg
                ));
                echo json_encode($return_data);              
            }
                                  


} catch(Exception $e) {
    $ret_msg="Error";
    $return_data=array(
        array(
        "error_msg" => $ret_msg
    ));
    echo json_encode($return_data);
}

// Close the statement and connection
$stmt->close();
$conn->close();
    }

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

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"users_php_MANAGE_PERMISSIONS",$connection);        
        if($checkPer==="1"){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['data'])) {
                    
                    if(isset($_POST['data']['type'])){
                        $type=$_POST['data']['type'];                        

                        switch($type){
                            case "update":                            

                                $fac_id=$_SESSION['facility_id'];                                
                                $checkUserForFac=checkIfUserBelongToCurFac($_POST['data']['user_id'],$fac_id,$connection);
                                if($checkUserForFac===1 || $_SESSION['type']==="SADMIN"){

                                    if($_POST['data']['user_id']===$curUserID){
                                        $ret_msg="You cannot update Your own permissions, Contact Admin for further action";        
                                        $return_data=array(
                                            array(
                                            "error_msg" => $ret_msg
                                        ));
                                        echo json_encode($return_data);
                                    }
                                    else{
                                    

                                        $permissions=$_POST['data']['permissions'];
                                        $user_id=$_POST['data']['user_id'];         
                                        $perType=$_POST['data']['perType'];                       
                                        updateUserPermission($permissions,$user_id,$perType,$connection);                                
                                    }
                                }
                                else if($checkUserForFac===0){
                                    $ret_msg="You cannot update details for user outside your facility";        
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
                            break;

                            default:
                            break;
                        }
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
