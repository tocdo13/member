<form method="POST" name="Createform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.create_key.]]</td>
                        <td style="width: 260px; " align="right" >
                            
                            <input type="button" id="back" value="Back" onclick="window.history.back();" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />
                        </td>
                    </tr>
                </table>
                <table width="800px" style="border: 1px solid #087E85;" >
                    <tr>
                        <td width ="150px" align="center" style="background-color: silver; height: auto;" >
                            <div style=" width:90%; border: 3px inset gray; background-color: white; margin-top:10px; margin-bottom: 10px;">
                                <table width="95%" style="margin-left: 10px; margin-top: 10px;vertical-align: central; margin-bottom: 10px; height: auto;" >
                                    <tr>
                                        <td>
                                            <span class="multi-input-header" style="float:left;width:30px; text-align: center; ">[[.stt.]]</span>
                                            <span class="multi-input-header" style="float:left;width:56px; text-align: center; ">[[.rooms.]]</span>
                                            <span class="multi-input-header" style="float:left;width:145px; text-align: center; ">[[.create_date.]]</span>
                                            
                                            <span class="multi-input-header" style="float:left;width:145px; text-align: center; ">[[.expiry_date.]]</span>
                                            
                                            <span class="multi-input-header" style="float:left;width:140px; text-align: center; ">[[.status.]]</span>
                                        </td>
                                    </tr>
                                    <?php
                                       for($i=0;$i<$_REQUEST['number_key'];$i++)
                                       {
                                            ?>
                                            <tr><td>
                                            <span class="multi-input"><input  type="text" name="index" id="index_<?php echo ($i + 101); ?>" value="<?php echo ($i+1);?>"  style="background:#CCCCCC; width:30px ; text-align: center; "/></span>
                                            <span class="multi-input"><input  type="text" name="room" id="room_<?php echo ($i+ 101); ?>" value="<?php echo $_REQUEST['room_name'];?>"  style="background:#CCCCCC; width:56px ; text-align: center; "/>
                                            <input  type="hidden" name="room_id" id="room_id_<?php echo ($i+ 101); ?>" value="<?php echo $_REQUEST['room_id'];?>" />
                                            </span>
                                            <span class="multi-input"><input  type="text" name="date_start" id="date_start_<?php echo ($i+ 101); ?>" value="<?php echo $_REQUEST['date_start'];?>"  style="background:#CCCCCC; width:74px ; text-align: center; "/></span>
                                            <span class="multi-input"><input  type="text" name="time_start" id="time_start_<?php echo ($i+ 101); ?>" value="<?php echo $_REQUEST['time_start']?>"  style="background:#CCCCCC; width:65px ; text-align: center; "/></span>
                                            <span class="multi-input"><input  type="text" name="date_expiry" id="date_expiry_<?php echo ($i+ 101); ?>" value="<?php echo $_REQUEST['date_expiry'] ?>" style="background:#CCCCCC; width:74px ; text-align: center; "/></span>
                                            <span class="multi-input"><input  type="text" name="time_expiry" id="time_expiry_<?php echo ($i+ 101); ?>"  value="<?php echo $_REQUEST['time_expiry']; ?>" style="background:#CCCCCC; width:65px ; text-align: center; "/></span>
                                            
                                            <div class="multi-input" id="result_tmp_<?php echo ($i+101);?>" style="display: inline; float:left;" ><img id="result_<?php echo ($i+101);?>" src="" style="width: 16px; height: 16px; margin-left: 10px;" />
                                            <input  type="hidden" name="status" id="status_<?php echo ($i+ 101); ?>"  style="background:#F2F4CE; width:40px ; text-align: center; "/></div>
                                           
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
    
    var count = 100 + <?php echo $_REQUEST['number_key']; ?>;
    var doorid=<?php echo [[=doorid=]];?>;
    
    function Send(i)
    {
        //truong hop cho nhieu phong: lay ra reservation_room_id,
        var s_id = <?php echo Url::get('resevation_room_id');?>;
        
        var reception = '<?php echo $_REQUEST['reception_id'];?>';
        var str_reception  = reception.split("_");
        
        var room_id = jQuery("#room_id_"+i).val();
       
        var str_room_str = '';
        var str_building_str = "";
        for(var j in doorid)
        {
            
            if(doorid[j]['room_id']==room_id)
            {
                
                str_room_str = doorid[j]['door_id'];
                str_building_str = doorid[j]['building'];
                break;  
            }
        }
        
        //tao beginTime
        var date_start = jQuery("#date_start_"+i).val();
        var str_date_start = date_start.split('/');
        var time_start = jQuery("#time_start_"+i).val();
        
        time_start +=':00';
        
        var begin_date = str_date_start[2]+ '-' + str_date_start[1]+ '-' + str_date_start[0];
        var begin_time = begin_date + ' ' + time_start;
        
        //tao endtime
        var date_expiry = jQuery("#date_expiry_"+i).val();
        var str_date_expiry =date_expiry.split('/');
        var time_expiry = jQuery("#time_expiry_"+i).val();
        
        time_expiry +=':00';
        
        var end_date = str_date_expiry[2] +'-' + str_date_expiry[1] + '-' + str_date_expiry[0];
        var end_time =end_date + ' ' + time_expiry;
       
        //1-roomNo-beginTime-endTime-flag(write)
        //msg = "1_201_2015-07-06 08:00:00_2015-07-07 12:00:00_1";
        var str ="1_"+str_building_str+"_"+str_room_str + "__" +  begin_time +"_" + end_time+"_0";
        
        console.log("str:" + str);
        
        if (window.XMLHttpRequest)
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {
            // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                console.log("receive:" + text_reponse);
                var arr_reponse = text_reponse.split("_");
                if(arr_reponse[1] == '0')
                    jQuery("#status_"+arr_reponse[0]).val(1);
                else
                {
                   jQuery("#status_"+arr_reponse[0]).val(-1);
                }
                setSrc();
                var n = to_numeric(arr_reponse[0]);
                //remove option n 
                
                document.getElementById("span_" + n).remove();
                if(n<count)
                {
                    n +=1;
                    var root = document.getElementById('result_tmp_' +n);
                    var p = document.createElement("span");
                    p.setAttribute("id", "span_" + n);
                    var node = document.createTextNode("[[.please_push_the_card_for_create.]]");
                    
                    p.appendChild(node);
                    root.appendChild(p);
                    document.getElementById("span_" + n).style.marginLeft='10px';
                    document.getElementById("span_" + n).style.verticalAlign='top';
                    document.getElementById("span_" + n).style.color ='blue';
                    
                    Send(n);
                    jQuery("#status_"+n).val(2);
                    setSrc();
                }
                else
                {
                    if(window.opener) 
                      window.opener.location.reload(false);
                }
            }
        }
        xmlhttp.open("GET","create_key_orbita.php?str_room="+ str +"&input="+i +"&reception=" + str_reception[0] + "&reservation_room_id=" + s_id,true);
        xmlhttp.send();
    }
    
    jQuery(document).ready(function(){
        jQuery("#home").click(function(e)
        {
            window.location.href ='?page=manager_key_fox&portal='+ '<?php echo PORTAL_ID; ?>';
        });
        
        var root = document.getElementById('result_tmp_101');
        var p = document.createElement("span");
        p.setAttribute("id", "span_101");
        var node = document.createTextNode("[[.please_push_the_card_for_create.]]");
        
        p.appendChild(node);
        root.appendChild(p);
        document.getElementById("span_101").style.marginLeft='10px';
        document.getElementById("span_101").style.verticalAlign='top';
        document.getElementById("span_101").style.color ='blue';
        Send(101);
        document.getElementById('status_'+ 101).value = 2;
        setSrc();
    });
    
    
    function setSrc(){
        for(var i=101;i<=count;i++){
            var status = document.getElementById('status_'+ i).value;
        	if(status)
            {  
                switch (status)
                {
                    case "1": 
                    {
                        document.getElementById("result_"+i).src = "packages/core/skins/default/images/buttons/update_button.png";
                        break;
                    }
                    case "-1": 
                    {
                        document.getElementById("result_"+i).src = "packages/core/skins/default/images/buttons/delete2.png";
                        break;
                    }
                    case "2": 
                    {
                        document.getElementById("result_"+i).src = "packages/core/skins/default/images/crud/indicator.gif";
                        break;
                    }
                    default :
                    {
                        document.getElementById("result_"+i).src = "";
                        break;
                    }    
                }
            }
        }
    }
</script>