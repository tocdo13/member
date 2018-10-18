<form method="POST" name="Checkoutform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.checkout_card_title.]]</td>
                        <td style="width: 260px; " align="right" >
                            <!--<input type="button" id="home" value="Home" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />-->
                            &nbsp;&nbsp;
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
                                            <span class="multi-input-header" style="width:80px; text-align: center; ">[[.stt.]]</span>
                                            <span class="multi-input-header" style="width:60px; text-align: center; ">[[.room.]]</span>
                                            <span class="multi-input-header" style="width:80px; text-align: center; ">[[.status.]]</span>
                                        </td>
                                    </tr>
                                    
                                    <?php
                                    $n = [[=row=]]['number_keys'];
                                    for($i=1;$i<=$n;$i++)
                                    {
                                        ?>
                                        <tr><td>
                                        <span class="multi-input"><input  type="text" name="guest_index" id="guest_index_<?php echo $i; ?>" value="<?php echo $i;?>"  style="background:#CCCCCC; width:80px ; text-align: center; "/></span>
                                        <span class="multi-input"><input  type="text" name="room" id="room_<?php echo $i; ?>" value="<?php echo [[=row=]]['room_name'];?>"  style="background:#CCCCCC; width:60px ; text-align: center; "/>
                                        <input  type="hidden" name="room_id" id="room_id_<?php echo $i; ?>" value="<?php echo [[=row=]]['room_id'];?>" />
                                        </span>
                                        
                                        <div class="multi-input" id="result_tmp_<?php echo $i;?>" style="display: inline; float:left;"><img id="result_<?php echo $i;?>" src="" style="width: 16px; height: 16px; margin-left: 10px;" />
                                        <input  type="hidden" name="status" id="status_<?php echo $i; ?>"  style="background:#F2F4CE; width:40px ; text-align: center; "/></div>
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
    
    var doorid=<?php echo [[=doorid=]]; ?>;
    
    function Send(i)
    {
        //truong hop cho nhieu phong: lay ra reservation_room_id,
        
        var reception = '<?php echo $_REQUEST['reception_id'];?>';
        var str_reception  = reception.split("_");
        
        var room_id = jQuery("#room_id_"+i).val();
        var str_room_str = '';
        
        
        for(var j in doorid)
        {
            if(doorid[j]['room_id']==room_id)
            {
                str_room_str += doorid[j]['door_id'];
                break;  
            }
        }
        
        while(str_room_str.length<4)
            str_room_str = '0' + str_room_str;
        
        //noi encoder_id
        //R010201_D201410300800_O102410301200:reception_id:reservation_room_id:first[late]

        var str =str_room_str + ':' + str_reception[0]+ ':'+room_id ;   
        
        //console.log(str);
        
        
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
                    var node = document.createTextNode("Waiting  for insert and checkout card!");
                    
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
        xmlhttp.open("GET","create_key_adel.php?str_room="+ str +"&input="+i + "&flag=2",true);
        xmlhttp.send();
    }
    
    jQuery(document).ready(function(){
        jQuery("#home").click(function(e)
        {
            window.location.href ='?page=manager_key_adel&portal='+ '<?php echo PORTAL_ID; ?>';
        });
        var root = document.getElementById('result_tmp_1');
        var p = document.createElement("span");
        p.setAttribute("id", "span_1");
        var node = document.createTextNode("Waiting for insert and checkout card!");
        
        p.appendChild(node);
        root.appendChild(p);
        document.getElementById("span_1").style.marginLeft='10px';
        document.getElementById("span_1").style.verticalAlign='top';
        document.getElementById("span_1").style.color ='blue';
        Send(1);
        document.getElementById('status_'+ 1).value = 2;
        setSrc();
        
    });
    
    
    function setSrc(){
        for(var i=1;i<=count;i++){
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