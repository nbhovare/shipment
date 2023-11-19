<?php    

    function checkPermission($user_id,$per_type,$connection){

        $checkPermissionQuery = "select permission_type from permissions where user_id = ".$user_id." and permission_type = '".$per_type."'";        
        $execute_per_query = mysqli_query($connection, $checkPermissionQuery);        
        if ($execute_per_query) {
            if(mysqli_num_rows($execute_per_query)>0){
                return "1";
            }
            else{
                return "0";
            }
        }
        else{
            return "0";
        }
    }
?>
