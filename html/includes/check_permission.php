<?php    

    function checkPermission($user_id,$per_type,$connection){

        $checkPermissionQuery = "select permission_type from permissions where user_id = ".$user_id." and permission_type LIKE '".$per_type."%'";        
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

    function getUserPermissionForPage($user_id,$perForPage,$connection){        
        $query = "select permission_type from permissions where user_id='".$user_id."' AND permission_type LIKE '".$perForPage."%'";                
        $execute_per_query = mysqli_query($connection, $query);        
        if($execute_per_query){
            
            
            if(mysqli_num_rows($execute_per_query)>0){                
                $jsonData = mysqli_fetch_all($execute_per_query,MYSQLI_ASSOC);            
                
                // Close the database connection
                mysqli_close($connection);

                // Merge all rows into a single array
$mergedData = array_reduce($jsonData, function ($carry, $row) {
    return array_merge($carry, array_values($row));
}, []);

// Extract all values from the associative arrays into a single array
// $mergedData = array_merge(...array_column($rows, null));


                return $mergedData;                
            }
            else{   
                return "0";
            }
        }
        else{
                return "-1";
        }        
        
    }

/*
    function checkPermissionForPage($user_id,$pageRef,$connection){
        // Check if requested user_id has access to current page
        // Refer below permission matrix as per each pages present in the portal
        // If any new page is introduced in the portal kindly please add a permission matrix below for checking user permissions

        $trackshipment_php=["TRACK_SHIPMENT","UPDATE_SHIP_STATUS","MODIFY_SHIP_DETAILS"];
        $booking_php=["BOOK_SHIP"];
        $users_php=["CREATE_USER","GET_USER_DATA","MANAGE_PERMISSIONS","MANAGE_USER"];
        $facility_php=["CREATE_FACILITY","VIEW_FACILITY","MODIFY_FAC_DETAILS","MANAGE_USERS","MANAGE_PERMISSION"];

        $checkPermissionForPageQuery = "select permission_type from permissions where user_id = ".$user_id." AND (";        

        switch($pageRef){
            case "trackshipment_php":                
                $conditions = array();
                foreach ($trackshipment_php as $perm) {
                    $conditions[] = "permission_type LIKE '" . $perm . "'";
                }
                $query .= implode(' OR ', $conditions);
            break;            
        }
        $checkPermissionForPageQuery.=")";

        echo $checkPermissionForPageQuery;
/*
        $execute_per_query = mysqli_query($connection, $checkPermissionForPageQuery);        
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
        
    }*/

?>
