<?php
    $server = "localhost"; // Replace with your MySQL server name
    $username = "newuser"; // Replace with your MySQL username
    $password = ""; // Replace with your MySQL password
    $database = "shipment"; // Replace with your database name

    // Create a connection
    $connection = new mysqli($server, $username, $password, $database);

    // Check the connection
    if (!$connection) {
        die("Connection failed: " . $connection->connect_error);        
    }

    // Close the connection when done
    $connection->close();


?>
