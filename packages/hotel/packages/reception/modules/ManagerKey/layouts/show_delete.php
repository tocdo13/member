<?php
    /*function getip4_local()
    {
        $ip='';
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        { 
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
         //to check ip is pass from proxy 
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        else 
        { 
            $ip=$_SERVER['REMOTE_ADDR']; 
        } 
        return $ip;
    }*/
?>

<form method="POST" name="Deletekeyform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.checkout_title.]]</td>
                        <td style="width: 260px; " align="right">
                            
                            <!--<input type="button" id="home" value="Home" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold;" />-->
                        </td>
                    </tr>
                </table>
                <table width="800px" style="border: 1px solid #087E85; " >
                    <tr>
                        <td width ="100%" height="auto" align="center" style="background-color: silver; " >
                            <div style=" width:600px; border: 3px inset gray; background-color: white; padding-right: 10px; margin: 20px 20px;">
                                <?php
                                if(isset([[=result=]]) && [[=result=]]!=0)
                                {
                                    $result = "";
                                    if([[=result=]]==1)
                                    {
                                        $result="Checkout card successful!";
                                    ?>
                                    <div style="width: 100%; height: auto;">
                                        <p style=" font-size: 16px;color:blue; font-family: tahoma;"><?php echo $result; ?></p>
                                    </div>
                                    <?php
                                    }
                                    else
                                    {
                                        $result="Checkout card unsuccessful!";
                                        ?>
                                        <div style="width: 100%; height: auto; ">
                                            <p style=" font-size: 16px; color:red; font-family: tahoma;"><?php echo $result; ?></p>
                                        </div>
                                        <?php
                                    }
                                } 
                                ?>
                               
                                <table width="100%" style="margin-top: 10px;">
                                   <!--<tr>
                                        <td align="center">
                                            <span style="font-weight: bold;">Note</span>
                                            <input name="txtNote" type="text" id="txtNote"  style="width: 300px;"/>  
                                        </td>
                                    </tr>--> 
                                   <tr >
                                        <td align="center">
                                            <span style="font-weight: bold; font-size: 14px;">[[.read_key_reception.]]</span>
                                            <select id="reception_id" name="reception_id" style="width: 80px;" >
                                                [[|reception_id|]]
                                            </select>
                                            &nbsp;&nbsp;&nbsp;
                                            <input id="delete" name="delete" type="submit" value="[[.checkout_card.]]" style="width: 100px; height: 40px; font-size: 16px; font-weight: bold; color: #1F6B7A; " />
                                        </td>
                                    </tr> 
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" value="" name="width" id="width" />
</form>
<script type="text/javascript">
    jQuery("#width").val(screen.width);
    jQuery("#home").click(function(e)
    {
        window.location.href = '?page=manager_key&portal=default';
    });
    var lenghOption  =  document.Deletekeyform.reception_id.length;
    window.onload = function () {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://www.telize.com/jsonip?callback=DisplayIP";
        document.getElementsByTagName("head")[0].appendChild(script);
    };
    var ip4 = '<?php echo getip4_local();?>';
    function DisplayIP(response) {
        var j=0;
        for(var i = 0;i<lenghOption;i++)
        {
            var reception =  document.Deletekeyform.reception_id.options[i].value;
            var str_reception = reception.split("_");
            if(str_reception[1]==response.ip || str_reception[1]==ip4)
            {
                j =i;
                break;
            }
        }
        var selected_op =  document.Deletekeyform.reception_id.options[j].value;
        document.getElementById("reception_id").value = selected_op;
        /*if(j>0)
        {
            document.getElementById("reception_id").disabled= true;
            document.getElementById("reception_id").style.backgroundColor ='#C0C0C0';
        }*/
        
    }
</script>
