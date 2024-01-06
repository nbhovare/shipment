<?php
    session_start();

    if (isset($_SESSION['isSession'])) {
        // Unset the session data
        unset($_SESSION['isSession']);
        unset($_SESSION['user_id']);
        unset($_SESSION['type']);
        unset($_SESSION['status']);
        
        // Destroy the session
        session_destroy();

        header("location:../login.php");
    }

    // Redirect to a logout confirmation page or any other destination
    //header('Location: /logout_confirmation.html');
    exit();
?>
