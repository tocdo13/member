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

<form method="POST" name="Readkeyform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px;"><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" ><?php echo Portal::language('read_key_title');?></td>
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
                                    if(isset($this->map['result']))
                                    {
                                        if($this->map['result']==-1)
                                        {
                                            $result="Read card unsuccessful!";
                                            ?>
                                            <div style="width: 100%; height: auto;">
                                                <p style=" font-size: 16px; color:red; font-family: tahoma;"><?php echo $result; ?></p>
                                            </div>
                                            <?php
                                        }
                                        else if($this->map['result']==1)
                                        {
                                            $result="Card has been checkout!";
                                            ?>
                                            <div style="width: 100%; height: auto;">
                                                <p style=" font-size: 16px; color:blue; font-family: tahoma;"><?php echo $result; ?></p>
                                            </div>
                                            <?php
                                        }
                                    } 
                                ?>
                                <table width="100%" style="margin-top: 10px;">
                                   <tr height="60px">
                                        <td align="center">
                                            <span style="font-weight: bold; font-size: 14px;"><?php echo Portal::language('read_key_reception');?></span>
                                            <select id="reception_id" name="reception_id" style="width: 80px;" >
                                                <?php echo $this->map['reception_id'];?>
                                            </select>
                                            &nbsp;&nbsp;&nbsp;
                                            <input id="read" name="read" type="submit" value="<?php echo Portal::language('read_key');?>" style="width: 100px; height: 40px; font-size: 16px; font-weight: bold; color: #1F6B7A; " />
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
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="text/javascript">
    jQuery("#width").val(screen.width);
    jQuery("#home").click(function(e)
    {
        window.location.href = '?page=manager_key&portal=default';
    });
    
    /*var lenghOption  =  document.Readkeyform.reception_id.length;
    window.onload = function () {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://www.telize.com/jsonip?callback=DisplayIP";
        document.getElementsByTagName("head")[0].appendChild(script);
    };
    var ip4 = '<?php //echo getip4_local();?>';
    function DisplayIP(response) {
        var j=0;
        for(var i = 0;i<lenghOption;i++)
        {
            var reception =  document.Readkeyform.reception_id.options[i].value;
            var str_reception = reception.split("_");
            if(str_reception[1]==response.ip || str_reception[1]==ip4)
            {
                j =i;
                break;
            }
        }
        var selected_op =  document.Readkeyform.reception_id.options[j].value;
        document.getElementById("reception_id").value = selected_op;
        
    }*/
</script>
