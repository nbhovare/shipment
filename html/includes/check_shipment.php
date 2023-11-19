<?php    

    function checkIfShipmentExists($shipment_id,$connection){
        $shipmentID = $shipment_id;
        
        $query = "SELECT * FROM shipment_details WHERE shipment_id='".$shipmentID."'";        
        $execute_query = mysqli_query($connection, $query);            
        if ($execute_query) {
            return $execute_query;
        }
        else{
            return "failed";
        }
    }
?>
