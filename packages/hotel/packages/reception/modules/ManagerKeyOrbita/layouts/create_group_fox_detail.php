<?php System::set_page_title("Make card");?>
<form method="POST" name="CreateListform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.create_card.]]</td>
                        <td style="width: 260px; " align="right" >
                            <input type="button" id="back" value="Back" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " onclick="window.history.back();" />
                        </td>
                    </tr>
                </table>
                <table width="800px" style="border: 1px solid #087E85;">
                    <tr>
                        <td width ="150px" align="center" style="background-color: silver; height: auto;" >
                            <div style=" width:90%; border: 3px inset gray; background-color: white; margin-top:10px; margin-bottom: 10px;">
                                <table width="95%" style="margin-left: 10px; margin-top: 10px;vertical-align: central; margin-bottom: 10px; height: auto;" >
                                    <tr>
                                        <td>
                                            <span class="multi-input-header" style="float:left;width:30px; text-align: center; ">[[.stt.]]</span>
                                            <span class="multi-input-header" style="float:left;width:40px; text-align: center; ">[[.rooms.]]</span>
                                            <span class="multi-input-header" style="float:left;width:105px; text-align: center; ">[[.start_date.]]</span>
                                            <span class="multi-input-header" style="float:left;width:105px; text-align: center; ">[[.expiry_date.]]</span>
                                            <span class="multi-input-header" style="float:left;width:85px; text-align: center; ">[[.commdoor.]]</span>
                                            <!--<span class="multi-input-header" style="float:left;width:85px; text-align: center; ">[[.guest_index.]]</span> -->  
                                            <span class="multi-input-header" style="float:left;width:32px; text-align: center; ">L.key</span>                                         
                                            <span class="multi-input-header" style="float:left;width:50px; text-align: center; ">[[.result.]]</span>
                                            <span class="multi-input-header" style="width:180px; text-align: center; ">[[.description.]]</span>
                                            
                                        </td>
                                    </tr>
                                    <?php
                                        $index=0;
                                        foreach([[=items=]] as $row)
                                        {
                                            $n = $row['number_key'];
                                            $first = true;
                                            for($i=0;$i<$n;$i++)
                                            {
                                                $index++;
                                            ?>
                                            <tr>
                                                <td>
                                                    <span class="multi-input">
                                                        <input  type="text" name="index" id="index_<?php echo $index; ?>" value="<?php echo $index;?>"  style="background:#CCCCCC; width:30px ; text-align: center; " readonly="true"/>
                                                        <input  type="hidden" name="reservation_room_id" id="reservation_room_id_<?php echo $index; ?>" value="<?php echo $row['rr_id'];?>" />
                                                    </span>
                                                    <span class="multi-input">
                                                        <input  type="text" name="room" id="room_<?php echo $index; ?>" value="<?php echo $row['room_name'];?>"  style="background:#CCCCCC; width:40px ; text-align: center; " readonly="true"/>
                                                        <input  type="hidden" name="door_id" id="door_id_<?php echo $index; ?>" value="<?php echo $row['door_id'];?>" />
                                                        <input  type="hidden" name="building" id="building_<?php echo $index; ?>" value="<?php echo $row['building'];?>" />
                                                    </span>
                                                    <span class="multi-input"><input  type="text" name="date_start" id="date_start_<?php echo $index; ?>" value="<?php echo $row['date_start'];?>"  style="background:#CCCCCC; width:64px ; text-align: center; " readonly="true"/></span>
                                                    <span class="multi-input"><input  type="text" name="time_start" id="time_start_<?php echo $index; ?>" value="<?php echo $row['time_start'];?>"  style="background:#CCCCCC; width:36px ; text-align: center; " readonly="true"/></span>
                                                    <span class="multi-input"><input  type="text" name="date_expiry" id="date_expiry_<?php echo $index; ?>" value="<?php echo $row['date_expiry']; ?>" style="background:#CCCCCC; width:64px ; text-align: center; " readonly="true"/></span>
                                                    <span class="multi-input"><input  type="text" name="time_expiry" id="time_expiry_<?php echo $index; ?>"  value="<?php echo $row['time_expiry']; ?>" style="background:#CCCCCC; width:36px ; text-align: center; " readonly="true"/></span>
                                                    <span class="multi-input"><input  type="text" name="commdoor" id="commdoor_<?php echo $index; ?>"  value="<?php echo $row['commdoor']; ?>" style="background:#CCCCCC; width:86px ; text-align: center; " readonly="true"/></span>
                                                    <!--<span class="multi-input"><input  type="text" name="guest_index" id="guest_index_<?php //echo $index; ?>"  value="<?php //echo ($i+1); ?>" style="background:#CCCCCC; width:86px ; text-align: center; " readonly="true"/></span>-->
                                                    <span class="multi-input">
                                                        <input  type="checkbox" name="loss_key" id="loss_key_<?php echo $index; ?>" value="<?php if(isset($row['loss_key']) and $first) echo '1'; else echo "0"; ?>" <?php if(isset($row['loss_key']) and $first) echo 'checked="true"'; ?>  style="background:#CCCCCC; width: 28px;" disabled="true"/>
                                                    </span>
                                                    <div class="multi-input" id="result_tmp_<?php echo $index;?>" style="display: inline; float:left; width: 53px; text-align: center;">
                                                        <img id="result_<?php echo $index;?>" src="" style="width: 16px; height: 16px;" />
                                                    </div>
                                                    <span class="multi-input"><input type="text" style="color: red; border: 0; background: transparent; width: 183px; margin-left: 5px;" readonly="true" id="error_<?php echo $index;?>" /></span>
                                                </td>
                                            </tr>
                                    <?php 
                                            $first = false;
                                            }  
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
    var count=<?php echo $index?>;
    
    function Send(i)
    {
        var reception = '<?php echo $_REQUEST['reception_id'];?>';
        var str_reception  = reception.split("_");
        
        var door_id = jQuery("#door_id_"+i).val();
        var building = jQuery("#building_"+i).val();
        //tao beginTime
        var date_start = jQuery("#date_start_"+i).val();
        var str_date_start = date_start.split('/');
        var time_start = jQuery("#time_start_"+i).val();
        time_start +=':00';
        
        var begin_date= str_date_start[2] + '-' + str_date_start[1]+ '-' + str_date_start[0];
        var begin_time = begin_date + ' ' +  time_start;
        
        //tao endtime
        var date_expiry = jQuery("#date_expiry_"+i).val();
        var str_date_expiry =date_expiry.split('/');
        var time_expiry = jQuery("#time_expiry_"+i).val();
        time_expiry  +=':00';
        
        var end_date = str_date_expiry[2] +'-' + str_date_expiry[1] + '-' + str_date_expiry[0];
        var end_time = end_date + ' ' + time_expiry;
        
        var commdoor = jQuery("#commdoor_"+i).val();
        
        var loss_key = jQuery("#loss_key_"+i).val();
        
        var reservation_room_id = jQuery("#reservation_room_id_"+i).val();
        var str = "1_" + building + "_" + door_id + "_" + commdoor + "_" + begin_time + "_" + end_time + "_" + loss_key ;
        console.log(str);
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
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
                    }, 5000);
                }
                else
                {
                    if(window.opener) 
                      window.opener.location.reload(false);
                }
            }
        }
        //console.log("create_key_orbita.php?str_room="+ str +"&input="+i +"&reception=" + str_reception[0] + "&reservation_room_id=" + reservation_room_id);return;
        xmlhttp.open("GET","create_key_orbita.php?str_room="+ str +"&input="+i +"&reception=" + str_reception[0] + "&reservation_room_id=" + reservation_room_id,true);
        xmlhttp.send();
        document.getElementById("result_"+i).src = "packages/core/skins/default/images/crud/indicator.gif";
    }
    
    jQuery(document).ready(function(){
        Send(1);
    });
</script>