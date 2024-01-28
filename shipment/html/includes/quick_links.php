<?php


?> 
 
 <!-- Quick Links -->
                        
                        <div class="col-md-12">
                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title">Quick Links</h4>                                    
                                </div>
                                <div class="card-body ">
                                
                                <button class="btn btn-fill" onclick="window.location.href='index.php'">Home</button>     
                                <button class="btn btn-fill" onclick="window.location.href='booking.php'">Book</button>                                    
                                    <button class="btn btn-fill" onclick="window.location.href='trackshipment.php'">Track</button>                                    
                                    
                                    <?php

                                        if($_SESSION['type']!="CLIENT"){

                                    ?>
                                    <button class="btn btn-fill" onclick="window.location.href='bucket.php'">Bucket</button>                                    
                                    <button class="btn btn-fill" onclick="window.location.href='facility.php'">Branch</button>
                                    <button class="btn btn-fill" onclick="window.location.href='users.php'">Users</button>
                                    <button class="btn btn-fill" onclick="window.location.href='cust.php'">Clients/Customers</button>
                                    <button class="btn btn-fill" onclick="window.location.href='reports.php'">Reports</button>                                    
                                    <?php 
                                        }
                                    ?>
                                    <button class="btn btn-fill" onclick="window.location.href='faq.php'">FAQ</button>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
