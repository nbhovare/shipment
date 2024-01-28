<?php

    include("../includes/db_connect.php");
            

    function getTopicDetails($data_send,$connection){
        // Get topic details accroding to Topic_type Passed variabled


        $topicType=array("permission","shipment","users","branch");
        $return_msg=null;

        if(in_array($data_send['topic'],$topicType)){


            $getTopicDataFromDBQ="SELECT * FROM faq";
            $firstIteration=true;

            $appendData="";

            foreach($data_send as $key=>$value){
                if($firstIteration){
                    $appendData.=" ".$key."='".$value."'";
                    $firstIteration=false;
                }
                else{
                    $appendData.=", ".$key."='".$value."'";           
                }
            }

            if($appendData!="" && $appendData!=null){
                $getTopicDataFromDBQ.=" WHERE ".$appendData;

                $getTopicDataFromDBQ_EQ=mysqli_query($connection,$getTopicDataFromDBQ);
                if($getTopicDataFromDBQ_EQ){
                    // Fetch Data From DB

                    if(mysqli_num_rows($getTopicDataFromDBQ_EQ)>0){
                        
                        $getDataRes=mysqli_fetch_all($getTopicDataFromDBQ_EQ,MYSQLI_ASSOC);
                        $jsonData_send=array(
                            "data"=>$getDataRes
                        );
                        $return_msg=$jsonData_send;                
                    }
                    else{
                        $return_msg=array("error_msg"=>"No Data Found");                   
                    }                

                }
                else{
                    $return_msg=array("error_msg"=>"error");
                }

            }
            else{
                $return_msg=array("error_msg"=>"error");
            }
        }
        else{
            $return_msg=array("error_msg"=>"Invalid Topic");
        }

        return $return_msg;
    }

    if (isset($_POST['data'])) {

        if(isset($_POST['data']['topic'])){            
            // Get type of topic from data


            $data=$_POST['data'];
            $res=getTopicDetails($data,$connection);
            
            $jsonData = json_encode($res);                     
                
        }
        else{
            $res=array("error_msg"=>"Invalid Inputs");
            $jsonData = json_encode($res);            
        }

    }
    else {
        $res=array("error_msg"=>"Invalid Topic");
        $jsonData = json_encode($res);        
    }
                
    
    echo $jsonData;

?>
