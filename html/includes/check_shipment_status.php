<?php    

    function checkIfShipmentExists($shipment_id,$new_activity,$connection){        
        
        $query = "SELECT * FROM shipment_events WHERE shipment_id='".$shipmentID."' order by event_id desc LIMIT 1";                  
        $execute_query = mysqli_query($connection, $query);            
        if ($execute_query) {
            if(mysqli_num_rows($execute_query)>0){
                $row=mysqli_fetch_all($execute_query)
                $raw_data=$row[0];
                if($raw_data['facility_id']===$_SESSION['facility_id']){
                    
                    switch ($new_activity) {
                        case "ARRIVED":
                            echo "It's the beginning of the week.";
                            break;
                        
                        case "DEPARTED":
                        case "Wednesday":
                        case "Thursday":
                            echo "It's a weekday.";
                            break;
                    
                        case "Friday":
                            echo "It's Friday, almost the weekend.";
                            break;
                    
                        case "Saturday":
                        case "Sunday":
                            echo "It's the weekend!";
                            break;
                    
                        default:
                            echo "Invalid day.";
                    }
                }
                else{
                    echo "The shipment you are trying to update is not in your queue";
                }                
            }
            echo return "failed";
        }
        else{
            return "failed";
        }
    }
?>
