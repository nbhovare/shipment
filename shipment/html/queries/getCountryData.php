<?php

    include("../includes/db_connect.php");
            
    if (isset($_POST['data'])) {

        $getDataQ="";
        switch($_POST['data']['getData']){
            case "state":
                $country=$_POST['data']['basedOn'];                           
                $getDataQ.="SELECT DISTINCT state FROM country_data WHERE country LIKE '".$country."'";
            break;

            case "city":
                $state=$_POST['data']['basedOn'];
                $getDataQ="SELECT district FROM country_data WHERE state LIKE '".$state."'";
            break;
                        
        }
        
        
                $getDataQ_EQ=mysqli_query($connection,$getDataQ);
                if($getDataQ_EQ){
                    if(mysqli_num_rows($getDataQ_EQ)>0){

                        $jsonData_country_state_data=mysqli_fetch_all($getDataQ_EQ,MYSQLI_ASSOC);
                        mysqli_close($connection);

                        // Combine data from both queries into an array
                        $jsonData_send = array(
                            'data' => $jsonData_country_state_data                            
                        );
                        $jsonData = json_encode($jsonData_send);
                    }
                    else{                        
                        $jsonData = array(
                            'error_msg' => "No Data Found"
                        );

                        $jsonData = json_encode($jsonData);                                                
                    }
                }   
                else{                
                    $jsonData = array(
                        'error_msg' => "Error"
                    );

                    $jsonData = json_encode($jsonData);
                } 

    }
    else {                
        $jsonData = array(
            'error_msg' => "Enter Data Properly"
        );

        $jsonData = json_encode($jsonData);
    }
                
    // Set headers and send the JSON response
    header("Content-Type: application/json");
    echo $jsonData;

?>
