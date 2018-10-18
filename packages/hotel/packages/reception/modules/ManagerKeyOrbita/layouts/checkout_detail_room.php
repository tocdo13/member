<?php System::set_page_title("Cancel card");?>
<form method="POST" name="Checkoutform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >CHECK OUT</td>
                        <td style="width: 260px; " align="right" >
                            
                            <input type="button" id="back" value="Back" onclick=" window.history.back();" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />
                        </td>
                    </tr>
                </table>
                <table width="800px" style="border: 1px solid #087E85;" align="center">
                    <tr>
                        <td width ="450px" align="center" style="background-color: silver; height: auto;" >
                            <div style=" width:90%; border: 3px inset gray; background-color: white; margin-top:10px; margin-bottom: 10px;">
                                <table width="450px" style="margin-left: 10px; margin-top: 10px;vertical-align: central; margin-bottom: 10px; height: auto;" >
                                    <tr>
                                        <td>
                                            <span class="multi-input-header" style="width:76px; text-align: center; ">[[.stt.]]</span>
                                            <span class="multi-input-header" style="width:80px; text-align: center; ">[[.status.]]</span>
                                            <span class="multi-input-header" style="width:200px; text-align: center; ">[[.description.]]</span>
                                        </td>
                                    </tr>
                                    
                                    <?php
                                    $n = [[=row=]]['number_keys'];
                                    for($i=1;$i<=$n;$i++)
                                    {
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="multi-input"><input  type="text" name="guest_index" id="guest_index_<?php echo $i; ?>" value="<?php echo $i;?>"  style="background:#CCCCCC; width:80px ; text-align: center; "/></span>
                                                <div class="multi-input" id="result_tmp_<?php echo $i;?>" style="display: inline; float:left; width: 85px; text-align: center;">
                                                    <img id="result_<?php echo $i;?>" src="" style="width: 16px; height: 16px; " />
                                                </div>
                                                <span class="multi-input"><input type="text" style="color: red; border: 0; background: transparent; width: 203px; margin-left: 5px;" readonly="true" id="error_<?php echo $i;?>" /></span>
                                            </td>
                                        </tr>
                                    <?php 
                                    }
                                    ?>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    var count= <?php echo [[=row=]]['number_keys']; ?>;
    
    function Send(i)
    {
        var reception = '<?php echo $_REQUEST['reception_id'];?>';
        var str_reception  = reception.split("_");
        
        var str = "3_1"; 
        
        if (window.XMLHttpRequest)
        {
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                
                var arr_reponse = text_reponse.split("_");
                if(arr_reponse[1] == '0')
                   document.getElementById("result_"+arr_reponse[0]).src = "packages/core/skins/default/images/buttons/update_button.png";
                else
                {
                   document.getElementById("result_"+arr_reponse[0]).src = "packages/core/skins/default/images/buttons/delete2.png";
                   
                   switch (to_numeric(arr_reponse[1]))
                   {
                        case -1 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Interface error"; break;
                        }
                        case -2 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Connect encoder failed"; break;
                        }
                        case -3 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Register encoder failed"; break;
                        }
                        case -4 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Buzzer mute"; break;
                        }
                        case -5 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Not supported card type"; break;
                        }
                        case -6 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Wrong card password"; break;
                        }
                        case -7 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Wrong supplier password"; break;
                        }
                        case -8 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Wrong card type"; break;
                        }
                        case -9 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Wrong authorization code"; break;
                        }
                        case -10 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Find card request failed"; break;
                        }
                        case -11 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Find card failed"; break;
                        }
                        case -12 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Load card password failed"; break;
                        }
                        case -13 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Read device information failed"; break;
                        }
                        case -14 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Read card failed"; break;
                        }
                        case -15 : {
                            document.getElementById("error_"+arr_reponse[0]).value = "Write card failed"; break;
                        }
                        default : {
                            break;
                        }
                   }
                }
                
                var n =to_numeric(arr_reponse[0]);
                if(n<count)
                {
                    setTimeout(function(){
                        Send(n+1);
                    }, 2000);
                }
                else
                {
                    if(window.opener) 
                      window.opener.location.reload(false);
                }
            }
        }
        xmlhttp.open("GET","create_key_orbita.php?str_room="+ str +"&input="+i + "&reception=" + str_reception[0],true);
        xmlhttp.send();
        document.getElementById("result_"+i).src = "packages/core/skins/default/images/crud/indicator.gif";
    }
    
    jQuery(document).ready(function(){
        Send(1);
    });
</script>