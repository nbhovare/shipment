<?php
    $server = "localhost"; // Replace with your MySQL server name    
    $username = "u570162860_shipment"; // Replace with your MySQL username
    $password = "Shipment123@456"; // Replace with your MySQL password
    $database = "u570162860_shipment"; // Replace with your database name
        

    // Create a connection
    $connection = new mysqli($server, $username, $password, $database);

    // Check the connection
    if (!$connection) {        
        die("Connection failed: " . $connection->connect_error);
    }

?>
