<?php

    include("../includes/db_connect.php");

    $data = json_decode(file_get_contents('php://input'));
    if (isset($_POST['formData'])) {
                
        //$data=$_POST['formData'];
        
        foreach ($data as $item) {                                                
            
            echo $item->sender_pincode;
            
        }                

        /*$data="Data found";
        echo json_encode($data);*/      
          
        echo $data;
        /*
        if ($data === null) {            
            echo "Failed to decode JSON data.";
        } else {
        
            foreach ($data as $item) {                                            $data = json_decode(file_get_contents('php://input'));    
                //$dt=$item->sender_pincode;
                echo "<script>alert(".$item->sender_pincode.")</script>";
                //echo json_encode($dt);
            }
        }
        /*$formData = $_POST['formData'];     // The data you want to insert
        $sql = "INSERT INTO your_table (column1, column2) VALUES ('$dataToInsert', '$dataToInsert')";
        if ($conn->query($sql) === TRUE) {
            echo "Data inserted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }*/

            /*echo " INSERT INTO your_table_name (
            additional_information,booking_date,carrier_id,content_type,create_by_admin_id,event_id,payment_type,receiver_add_info_landmark,
            receiver_address,receiver_city,receiver_country,receiver_email,receiver_name,receiver_phone,receiver_pincode,
            receiver_state,sender_address,sender_city,sender_country,sender_email,sender_name,sender_phone,sender_pincode,sender_state,shipment_cost) 
            
            VALUES (
            '$additional_information','$booking_date','$carrier_id','$content_type',$create_by_admin_id,
            $event_id,'$payment_type','$receiver_add_info_landmark','$receiver_address','$receiver_city','$receiver_country',
            '$receiver_email','$receiver_name','$receiver_phone','$receiver_pincode','$receiver_state','$sender_address','$sender_city',
            '$sender_country','$sender_email','$sender_name','$sender_phone','$sender_pincode','$sender_state',$shipment_cost
        );";*/
    
}
    else {
        
        $data="Invalid Data";
        echo json_encode($data);

    }    

?>
