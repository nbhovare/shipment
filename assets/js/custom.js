//$().ready(function() {
    
function getUserPermission(userId){
    if(userId===null || userId==="" || userId===undefined){
        return "error";
    }
    else{
        var data_send={
            user_id:userId,
            type:"dataNPer"
        };
        $.ajax({
            type: "POST",
            url: './queries/getUserData.php',
            data:  {data:data_send},
            success: function(response)
            {
                if(response.error_msg){
                    $("#admin_user_id").empty();                                    
                    alert(response.error_msg.error_msg);                                    
                }
                else{                                                                                                  
                    $("#admin_user_id").empty();
                    $("#admin_user_id").append("\
                        <option value='Select Admin'>Select Admin</option>");

                    $.each(response.users_data, function(index, getData) {
                        
                        $("#admin_user_id").append("\
                            <option value="+getData.user_id+">"+getData.first_name+" "+getData.last_name+" : "+getData.email_id+"</option>");
                        });
                        
                }
            },
            error: function (xhr, status, error) {
                console.log("Ajax request failed with status: " + status + " and error: " + error);
                // You can provide a more user-friendly error message or handle errors as needed.
            }   
        });
    }
}