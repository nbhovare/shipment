




<style>
        
        /* CSS to make the loader image fixed and appear above other elements */
#loading-bar {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.7); /* Optional: semi-transparent overlay */
    z-index: 9999; /* Set a high z-index to ensure it appears above other elements */
}

#loader-image {
    /*
    display: block;
    margin: auto;
    max-width: 10%; /* Adjust the width as needed 
    height: auto;*/

    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: block;
    max-width: 10%; /* Adjust the width as needed */
    height: auto;

}


    </style>

<script>
    


        // Global AJAX event handlers
        $(document).ajaxStart(function() {
    $('#loading-bar').show(); // Show the loader when any AJAX request starts
});

$(document).ajaxStop(function() {
    $('#loading-bar').hide(); // Hide the loader when all AJAX requests complete
});


</script>

<div id="loading-bar" >
    <!-- Loading bar content, such as a spinner or progress animation -->
    <img src="../assets/img/loaders.gif" alt="" id="loader-image">
</div>