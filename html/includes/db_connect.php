<?php
    $server = "localhost"; // Replace with your MySQL server name
    $username = "root"; // Replace with your MySQL username
    $password = ""; // Replace with your MySQL password
    $database = "shipment"; // Replace with your database name

    // Create a connection
    $connection = new mysqli($server, $username, $password, $database);

    // Check the connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    echo "Connected successfully";

    // Close the connection when done
    $connection->close();
?>
