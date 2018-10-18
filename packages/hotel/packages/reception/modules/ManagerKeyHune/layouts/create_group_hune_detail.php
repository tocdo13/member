<form method="POST" name="CreateListform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >CREATE KEY</td>
                        <td style="width: 260px; " align="right" >
                            <!--input type="button" id="home" value="Home" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />-->
                            &nbsp;&nbsp;
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
                                            <span class="multi-input-header" style="float:left;width:30px; text-align: center; ">Index</span>
                                            <span class="multi-input-header" style="float:left;width:40px; text-align: center; ">Room</span>
                                            <span class="multi-input-header" style="float:left;width:74px; text-align: center; ">Start date</span>
                                            <span class="multi-input-header" style="float:left;width:65px; text-align: center; ">Time start</span>
                                            <span class="multi-input-header" style="float:left;width:74px; text-align: center; ">Expiry date</span>
                                            <span class="multi-input-header" style="float:left;width:65px; text-align: center; ">Time expiry</span>
                                            <span class="multi-input-header" style="float:left;width:65px; text-align: center; ">Guest index</span>                                            
                                            <span class="multi-input-header" style="float:left;width:40px; text-align: center; ">Status</span>
                                            
                                        </td>
                                    </tr>
                                    <?php
                                        $k=100;
                                       foreach([[=items=]] as $row)
                                       {
                                            $n = $row['number_key'];
                                            for($i=0;$i<$n;$i++)
                                            {
                                                $k++;
                                            ?>
                                            <tr><td>
                                            <span class="multi-input"><input  type="text" name="index" id="index_<?php echo $k; ?>" value="<?php echo ($k-100);?>"  style="background:#CCCCCC; width:30px ; text-align: center; "/>
                                            <input  type="hidden" name="reservation_room_id" id="reservation_room_id_<?php echo $k; ?>" value="<?php echo $row['id'];?>" />
                                            </span>
                                            <span class="multi-input"><input  type="text" name="room" id="room_<?php echo $k; ?>" value="<?php echo $row['room_name'];?>"  style="background:#CCCCCC; width:40px ; text-align: center; "/>
                                            <input  type="hidden" name="room_id" id="room_id_<?php echo $k; ?>" value="<?php echo $row['room_id'];?>" />
                                            </span>
                                            <span class="multi-input"><input  type="text" name="date_start" id="date_start_<?php echo $k; ?>" value="<?php echo $row['date_start'];?>"  style="background:#CCCCCC; width:74px ; text-align: center; "/></span>
                                            <span class="multi-input"><input  type="text" name="time_start" id="time_start_<?php echo $k; ?>" value="<?php echo $row['time_start']?>"  style="background:#CCCCCC; width:65px ; text-align: center; "/></span>
                                            <span class="multi-input"><input  type="text" name="date_expiry" id="date_expiry_<?php echo $k; ?>" value="<?php echo $row['date_expiry'] ?>" style="background:#CCCCCC; width:74px ; text-align: center; "/></span>
                                            <span class="multi-input"><input  type="text" name="time_expiry" id="time_expiry_<?php echo $k; ?>"  value="<?php echo $row['time_expiry']; ?>" style="background:#CCCCCC; width:65px ; text-align: center; "/></span>
                                            <span class="multi-input"><input  type="text" name="guest_index" id="guest_index_<?php echo $k; ?>"  value="<?php echo ($i+1); ?>" style="background:#CCCCCC; width:65px ; text-align: center; "/></span>
                                            <div class="multi-input" id="result_tmp_<?php echo $k;?>" style="display: inline; float:left;" ><img id="result_<?php echo $k;?>" src="" style="width: 16px; height: 16px; margin-left: 10px;" />
                                            <input  type="hidden" name="status" id="status_<?php echo $k; ?>"  style="background:#F2F4CE; width:40px ; text-align: center; "/></div>
                                            
                                            </td>
                                            </tr>
                                            <?php 
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
    var count=100 + <?php echo [[=num_keys=]]?>;
    var doorid=<?php echo [[=doorid=]]; ?>;
    
    var cardNo=<?php echo $_REQUEST['card_no'];?>;
    
    function Send(i)
    {
        
        var reception = '<?php echo $_REQUEST['reception_id'];?>';
        var str_reception  = reception.split("_");
        
        var room_id = jQuery("#room_id_"+i).val();
        var room_name = jQuery("#room_"+i).val();
        var address = '';
        
        for(var j in doorid)
        {
            if(doorid[j]['room_id']==room_id)
            {
                address = doorid[j]['address'];
                break;  
            }
        }
        
        //tao beginTime
        var date_start = jQuery("#date_start_"+i).val();
        var str_date_start = date_start.split('/');
        var time_start = jQuery("#time_start_"+i).val();
        var str_time_start = time_start.split(':');
        
        var begin_date= str_date_start[2].substr(2,2) + '-' + str_date_start[1]+ '-' + str_date_start[0];
        var begin_time = str_time_start[0]+ ':' + str_time_start[1] + ':00'; 
        
        //tao endtime
        var date_expiry = jQuery("#date_expiry_"+i).val();
        var str_date_expiry =date_expiry.split('/');
        var time_expiry = jQuery("#time_expiry_"+i).val();
        var str_time_expiry = time_expiry.split(':');
        
        var end_date = str_date_expiry[2].substr(2,2) +'-' + str_date_expiry[1] + '-' + str_date_expiry[0];
        var end_time = str_time_expiry[0]+ ':' + str_time_expiry[1] + ':00';
        
        var level_pass =3;
        var pass_mode = 1;
        var address_qty = 1;
        var terminateOld;
        
        //truong hop cho nhieu phong: lay ra reservation_room_id,
        var reservation_room_id = jQuery("#reservation_room_id_"+i).val();
        var guest_index = jQuery("#guest_index_"+i).val();
        terminateOld = guest_index==1?1:0;
        
                
        //noi encoder_id
        //R010201_D201410300800_O102410301200:reception_id:reservation_room_id:first[late]
        var str = cardNo +'_00000000_' + address + '_' + begin_date +'_' +begin_time + '_' + end_date +'_' +end_time+ '_' + level_pass + '_' + pass_mode + '_' + address_qty +'_' + terminateOld;// '_3_1_1_1' ;
        str +='|' + room_name +'|' + reservation_room_id +'|' + terminateOld;
        console.log(str);
        cardNo = cardNo + 1;
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
                console.log(text_reponse);
                var arr_reponse = text_reponse.split("_");
                if(arr_reponse[1] == '1')
                    jQuery("#status_"+arr_reponse[0]).val(1);
                else
                {
                   jQuery("#status_"+arr_reponse[0]).val(-1);
                }
                setSrc();
                var n =to_numeric(arr_reponse[0]);
                //remove option n 
                 document.getElementById("span_" + n).remove();
                if(n<count)
                {
                    n +=1;
                    var root = document.getElementById('result_tmp_' +n);
                    var p = document.createElement("span");
                    p.setAttribute("id", "span_" + n);
                    var node = document.createTextNode("Waiting  for insert and create card!");
                    
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
       
        xmlhttp.open("GET","create_key_hune.php?str_room="+ str +"&input="+i +"&reception=" + str_reception[0],true);
        xmlhttp.send();
    
    }
    
    jQuery(document).ready(function(){
        
        var root = document.getElementById('result_tmp_101');
        var p = document.createElement("span");
        p.setAttribute("id", "span_101");
        var node = document.createTextNode("Waiting  for insert and create card!");
        
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