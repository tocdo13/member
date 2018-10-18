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
<script type="text/javascript">
    var doorid =[[|doorid|]];
    
</script>
<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#" >
            <input name="mi_group[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#F2F4CE"/>
			<span class="multi-input"><input name="mi_group[#xxxx#][index]" type="text" readonly="true" id="index_#xxxx#"  tabindex="1" style="background:#CCC; width:30px ; text-align: center; "/></span>
            <span class="multi-input">
                <select  name="mi_group[#xxxx#][room]" style="width:60px; background-color: #F2F4CE;" tabindex="-1"  id="room_#xxxx#" >
					[[|room_id_options|]]
				</select>
            </span>
            <span class="multi-input"><input name="mi_group[#xxxx#][date_start]" type="text" id="date_start_#xxxx#"  tabindex="1" style="background:#F2F4CE; width:74px ; text-align: center; "/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][time_start]" type="text" id="time_start_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:65px ; text-align: center; " /></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][date_expiry]" type="text" id="date_expiry_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:74px ; text-align: center; "/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][time_expiry]" type="text" id="time_expiry_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:65px ; text-align: center; "/></span>
            <span class="multi-input">
                <select  name="mi_group[#xxxx#][number_key]" style="width:85px; background-color: #F2F4CE;"  id="number_key_#xxxx#">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
				</select>
            </span>
			<span class="multi-input" style="width:40px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_group','#xxxx#',''); input_count--;" style="cursor:pointer;"/></span>  
            <span class="multi-input" ><img id="result_#xxxx#" src="" style="width: 16px; height: 16px;" />
            <input name="fail_#xxxx#" type="text"  id="fail_#xxxx#" style="width: 10px; vertical-align: top; height: 15px;margin-left: 2px;display: none;background-color: transparent;border: 0px solid; font-size: 14px;" readonly="readonly"/></span> 
            <input name="mi_group[#xxxx#][status]" type="hidden" value="0" readonly="" id="status_#xxxx#"  tabindex="-1" style="width:30px; background:#F2F4CE"/>

		</div>
        <br clear="all" />
	</span>
</span>
<form method="POST" name="Createform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.create_group_room.]]</td>
                        <td style="width: 260px; " align="right" >
                            <!--<input type="button" id="home" value="Home" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />-->
                        </td>
                    </tr>
                </table>
                <table width="800px" style="border: 1px solid #087E85;" >
                    <tr>
                        <td align="center" style="background-color: silver; height: auto;  " >
                            <div style=" width: 700px; border: 3px inset gray; background-color: white; padding-right: 10px; padding-left: 26px; padding-top: 20px;margin-top: 10px; margin-bottom: 10px; ">
                                <table width="100%" >
                                    <tr>
                                        <td>
                                            <span class="multi-input-header" style="float:left;width:30px; text-align: center; ">[[.stt.]]</span>
                                            <span class="multi-input-header" style="float:left;width:56px; text-align: center; ">[[.room.]]</span>
                                            <span class="multi-input-header" style="float:left;width:145px; text-align: center; ">[[.start_date.]]</span>
                                            <!--<span class="multi-input-header" style="float:left;width:65px; text-align: center; ">Time start</span>-->
                                            <span class="multi-input-header" style="float:left;width:145px; text-align: center; ">[[.expiry_date.]]</span>
                                            <!--<span class="multi-input-header" style="float:left;width:65px; text-align: center; ">Time expiry</span>-->
                                            <span class="multi-input-header" style="float:left;width:80px; text-align: center; ">[[.number_key.]]</span>
                                            <span class="multi-input-header" style="float:left;width:40px; text-align: center; ">[[.delete.]]</span>
                                            <span class="multi-input-header" style="float:left;width:80px; text-align: center; ">[[.status.]]</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" >
                                            <div >
                                				<span id="mi_group_all_elems">
                                				</span>
                                			</div>
                                			<div style="padding-top: 2px;" ><a href="javascript:void(0);" onclick="mi_add_new_row('mi_group');AddInput(input_count);" class="button-medium-add">[[.Add.]]</a></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" >
                                            
                                        </td>
                                    </tr>
                                    <tr height="60px" >
                                        
                                        <td align="center" >
                                            <span style="font-weight: bold; font-size: 14px;">Reception:</span>
                                            <select id="reception_id" name="reception_id" style="width: 80px;" >
                                                [[|reception_id|]]
                                            </select>
                                            &nbsp;&nbsp;&nbsp;
                                            <input id="create" type="button" value="[[.create_card.]]" style="width: 100px; height: 40px; font-size: 16px; font-weight: bold; color: #1F6B7A; " />
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
</form>

<script>
    <?php if(isset($_REQUEST['mi_group'])){echo 'var bars = '.String::array2js($_REQUEST['mi_group']).';';}else{echo 'var bars = [];';}?>
    mi_init_rows('mi_group',bars);
    //console.log(doorid);
    
    function Send(i)
    {
        //tao chuoi cho phong va gui tung phong i
        var str ='1-';
        var room = jQuery("#room_"+i).val();
        var str_room = room.split('_');
        
        //console.log(str_room[0]);
        for(var j in doorid)
        {
            var room_id_door = parseFloat(doorid[j]['room_id']);
            var room_id = parseFloat(str_room[0]);
            if(room_id_door==room_id)
            {
                str +=doorid[j]['door_id'];
                break;  
            }
        }
        
        //tao beginTime
      
        var date_start = jQuery("#date_start_"+i).val();
        
        var str_date_start = date_start.split('/');
        var time_start = jQuery("#time_start_"+i).val();
        var str_time_start = time_start.split(':');
        str +='-' + str_date_start[2].substr(2,2);
        str += str_date_start[1];
        str += str_date_start[0];
        str += str_time_start[0]; 
        str += str_time_start[1];//2014
       // console.log(str);
        //tao endtime
        var date_expiry = jQuery("#date_expiry_"+i).val();
        var str_date_expiry =date_expiry.split('/');
        var time_expiry = jQuery("#time_expiry_"+i).val();
        var str_time_expiry = time_expiry.split(':');
        str +='-' + str_date_expiry[2].substr(2,2);
        str += str_date_expiry[1];
        str += str_date_expiry[0];
        str += str_time_expiry[0]; 
        str += str_time_expiry[1];//2014
        
        var number_key = jQuery("#number_key_"+i).val();
        str +='-' + number_key;
        str +='-0';
        
        //noi chuoi reservation_room_id
        str +=':' + jQuery("#id_"+i).val();
        
        //noi encoder_id
        var reception = jQuery("#reception_id").val();
        var str_reception  = reception.split("_");
        
        str +=':' + str_reception[0];
        
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
                //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                var text_reponse = xmlhttp.responseText;
                //console.log(text_reponse);
                var arr_reponse = text_reponse.split("_");
                if(arr_reponse[1] == '1')
                    jQuery("#status_"+arr_reponse[0]).val(1);
                else if(arr_reponse[1] == '2')
                {
                   jQuery("#status_"+arr_reponse[0]).val(1); 
                   jQuery("#fail_"+arr_reponse[0]).css('display','');
                   jQuery("#fail_"+arr_reponse[0]).val(arr_reponse[2]!=0?arr_reponse[2]:'');
                }
                else
                {
                    jQuery("#status_"+arr_reponse[0]).val(-1);
                }
                setSrc();
                if(to_numeric(arr_reponse[0])<input_count)
                {
                    console.log(arr_reponse[0]);
                    Send(to_numeric(arr_reponse[0])+1);
                    jQuery("#status_"+(to_numeric(arr_reponse[0])+1)).val(2);
                    setSrc();
                }
                else
                {
                    if(window.opener) 
                       window.opener.location.reload(false);
                }
            }
        }
        xmlhttp.open("GET","create_key.php?str_room="+ str +"&input="+i,true);
        xmlhttp.send();
    
    }
    jQuery(document).ready(function(){
        jQuery("#home").click(function(e)
        {
            window.location.href = '?page=manager_key&portal=default';
        });
        jQuery("#create").click(function(e)
        {
            //console.log(input_count);
            if(check_input())
            {
               Send(101);
               jQuery("#status_"+101).val(2);
               setSrc();
            }
        });
        
        setSrc();
        
        for(var i=101;i<=input_count;i++){
            jQuery("#index_"+i).val(i-100);
        	if(jQuery("#date_start_"+i)){
        		jQuery("#date_start_"+i).datepicker();//{ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' }
        	}
            if(jQuery("#date_expiry_"+i)){
        		jQuery("#date_expiry_"+i).datepicker();//{ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' }
        	}
            if(jQuery("#time_start_"+i)){
        		jQuery("#time_start_"+i).mask('99:99');
        	}
            if(jQuery("#time_expiry_"+i)){
        		jQuery("#time_expiry_"+i).mask('99:99');
        	}
        }
    });
    
    function AddInput(input_count){
        jQuery("#index_"+input_count).val(input_count-100);
        
        if(jQuery("#date_start_"+input_count)){
        		jQuery("#date_start_"+input_count).datepicker();//{ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' }
        	}
            if(jQuery("#date_expiry_"+input_count)){
        		jQuery("#date_expiry_"+input_count).datepicker();//{ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' }
        	}
            if(jQuery("#time_start_"+input_count)){
        		jQuery("#time_start_"+input_count).mask('99:99');
        	}
            if(jQuery("#time_expiry_"+input_count)){
        		jQuery("#time_expiry_"+input_count).mask('99:99');
        	}
    }
    
    function check_input()
    {
        for(var i=101;i<=input_count;i++){
        	if(!jQuery("#date_start_"+i).val())
            {
                alert('Nhập thời gian tạo thẻ!');
                return false;
            }
            if(!jQuery("#date_expiry_"+i).val())
            {
                alert('Nhập thời gian hết hạn thẻ!');
                return false;
            }
        }
        
        
        return true;
    }
    
    function setSrc(){
        for(var i=101;i<=input_count;i++){
        	if(jQuery('#status_'+i).val())
            {  
                switch (jQuery("#status_"+i).val())
                {
                    case "1": 
                    {
                        document.getElementById("result_"+i).src = "packages/core/skins/default/images/buttons/update_button.png";break;
                    }
                    case "-1": 
                    {
                        document.getElementById("result_"+i).src = "packages/core/skins/default/images/buttons/delete2.png";break;
                    }
                    case "2": 
                    {
                        document.getElementById("result_"+i).src = "packages/core/skins/default/images/crud/indicator.gif";break;
                    }
                    default :
                    {
                        document.getElementById("result_"+i).src = "";
                    }    
                }
            }
        }
    }
    
   /* var lenghOption  =  document.Createform.reception_id.length;
    
    
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
            var reception =  document.Createform.reception_id.options[i].value;
            var str_reception = reception.split("_");
            if(str_reception[1]==response.ip || str_reception[1]==ip4)
            {
                j =i;
                break;
            }
        }
        var selected_op =  document.Createform.reception_id.options[j].value;
        document.getElementById("reception_id").value = selected_op;
        
    }*/
</script>