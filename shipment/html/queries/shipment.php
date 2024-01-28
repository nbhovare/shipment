<?php

    include("../includes/db_connect.php");
            
    session_start();        

    if(!isset($_SESSION['isSession'])){
        header("location:../login.php");
    }
    else{

        
        
        if(
            isset($_POST['data']) &&
            isset($_POST['data']['pageId']) &&
            isset($_POST['data']['filters'])
        ){        
            
            $curUserId=$_SESSION['user_id'];

            $query = "SELECT shipment_id, shipment_status, shipment_type, booking_date FROM shipment_details ";            
            if(isset($_POST['data']['filters']) && $_POST['data']['filters']==="1"){                      
                $ifPrev=false;
                if(isset($_POST['data']['state'])){
                    // state and city referred here is receiver state and city
                    $state=$_POST['data']['state'];
                    $query.=" WHERE receiver_state='".$state."'";
                    $ifPrev=true;                    
                }
                if(isset($_POST['data']['city'])){
                    $city=$_POST['data']['city'];                    
                    $query.=($ifPrev)?" AND ":" WHERE ";                    
                    $query.=" receiver_city='".$city."'";
                    $ifPrev=true;
                }
                if(isset($_POST['data']['date'])){
                    $date=$_POST['data']['date'];
                    $query.=($ifPrev)?" AND ":" WHERE ";
                    $query.=" booking_date='".$date."'";
                    $ifPrev=true;
                }                                         
            }

                        
            $query.=($ifPrev)?" AND ":" WHERE ";                        
            $query.=" create_by_admin_id='".$curUserId."' ORDER by booking_date desc";            

            $execute_query = mysqli_query($connection, $query);            
            if ($execute_query) {                           
                if(mysqli_num_rows($execute_query)>0){                
                    $jsonData_facility = mysqli_fetch_all($execute_query, MYSQLI_ASSOC);            

                        // Close the database connection
                        mysqli_close($connection);

                        // Combine data from both queries into an arrayt
                        $jsonData_send = array(
                            'shipment_data' => $jsonData_facility
                        );
                        $jsonData = json_encode($jsonData_send);   
                        echo $jsonData;         

                }
                else{                                                    
                    $jsonData = array(
                        'error_msg' => "No Shipments Found"
                    );

                    $jsonData = json_encode($jsonData);        
                    echo $jsonData;            
                }
            }
            else {
                $res=array("error_msg"=>"Error");
                $jsonData = json_encode($res);     
                echo $jsonData;           
            }            
        }
        else{
            $res=array("error_msg"=>"Invalid Data");
            $jsonData = json_encode($res);
            echo $jsonData;
        }
        
    }    
                
    // Set headers and send the JSON response
    //header("Content-Type: application/json");    

?>
