<?php

    include("../includes/db_connect.php");
    include("../includes/check_permission.php"); 
            
    session_start();        

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        
        $curUserID=$_SESSION['user_id'];        
        $checkPer=checkPermission($curUserID,"facility_php_VIEW_FACILITY",$connection);        
        if($checkPer==="1"){
            
            if(isset($_POST['data'])){
                $searchBy=$_POST['data']['type'];   
                $query="SELECT * FROM shipment_facility ";
                
                switch($searchBy){

                    case "facName":                           
                        $facilityName=$_POST['data']['facility_name'];
                        $query = $query."where shipment_facility.facility_name LIKE '%".$facilityName."%'";
                    break;

                    case "facId":                                                          
                        $facility_id=$_POST['data']['facility_id'];                        
                        $query = $query."where shipment_facility.facility_id='".$facility_id."'";
                    break;

                    default:
                        $ret_msg="Error";
                        $return_data=array(
                            array(
                            "error_msg" => $ret_msg
                        ));
                        echo json_encode($return_data);
                    break;

                }


                // Get Facility Data and return                
                $execute_query = mysqli_query($connection, $query);                        
                if ($execute_query) {                           
                    if(mysqli_num_rows($execute_query)>0){                
                        $jsonData_facility = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);            

                            // Close the database connection
                            mysqli_close($connection);

                            // Format String

                            $format_data = array(                                
                                    array(
                                        "input" => array(
                                            "facility_id",
                                            "facility_name",
                                            "address",
                                            "city",
                                            "pincode",
                                            "entry_create_date",
                                            "admin_user_id"
                                        )
                                    ),
                                    array(
                                        "select" => array(
                                            "state",
                                            "country"
                                        )
                                    )                            
                            );

                            // Format String


                            // Combine data from both queries into an arrayt
                            $jsonData_send = array(
                                'facility_data' => $jsonData_facility,
                                'format_data' => $format_data
                            );
                            $jsonData = json_encode($jsonData_send);            

                    }
                    else{             
                        //$res=array(array("error_msg"=>"Shipment ID does not exist"));
                        $arr=array('error_msg' => 'No Facility Found');
                        $jsonData = array(
                            'error_msg' => $arr
                        );

                        $jsonData = json_encode($jsonData);
                        //echo "Shipment ID does not exist";
                    }
                }
                else {
                    $res=array(array("error_msg"=>"Error"));
                    $jsonData = json_encode($res);
                    //echo "Error";
                }

                // Get Facility Data and return
                
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
        else{
            $ret_msg="You Do not have permission to this action";
            $return_data=array(
                array(
                "error_msg" => $ret_msg
            ));
            echo json_encode($return_data);
        }
    }    
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
