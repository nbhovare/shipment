<?php

    include("../includes/db_connect.php");
            
    session_start();        

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{
        
        if(
            isset($_POST['facilityState']) && 
            isset($_POST['facilityCountry']) && 
            isset($_POST['facilityCity'])
        ){
            $facilityState=$_POST['facilityState'];
            $facilityCity=$_POST['facilityCity'];
            $facilityCountry=$_POST['facilityCountry'];
            $query = "SELECT * FROM shipment_facility where state='".$facilityState."' AND country='".$facilityCountry."' AND city='".$facilityCity."' ORDER BY facility_id ASC";            
            $execute_query = mysqli_query($connection, $query);            
            if ($execute_query) {                           
                if(mysqli_num_rows($execute_query)>0){                
                    $jsonData_facility = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);            

                        // Close the database connection
                        mysqli_close($connection);

                        // Combine data from both queries into an arrayt
                        $jsonData_send = array(
                            'facility_data' => $jsonData_facility
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
        }
        else{
            $res=array(array("error_msg"=>"Select Country, State, City Properly"));
            $jsonData = json_encode($res);
        }
        
    }    
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
