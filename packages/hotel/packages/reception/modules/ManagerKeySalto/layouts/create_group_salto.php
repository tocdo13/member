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
            <span class="multi-input"><input name="mi_group[#xxxx#][date_start]" type="text" id="date_start_#xxxx#"  tabindex="1" style="background:#F2F4CE; width:75px ; text-align: center; "/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][time_start]" type="text" id="time_start_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:65px ; text-align: center; " /></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][date_expiry]" type="text" id="date_expiry_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:75px ; text-align: center; "/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][time_expiry]" type="text" id="time_expiry_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:65px ; text-align: center; "/></span>
            <span class="multi-input">
                <select  name="mi_group[#xxxx#][number_key]" style="width:105px; background-color: #F2F4CE;"  id="number_key_#xxxx#">
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
            <span class="multi-input" ><img id="result_#xxxx#" src="" style="width: 16px; height: 16px; margin-left: 15px;"  />
             
            <input name="mi_group[#xxxx#][status]" type="hidden" value="0" readonly="" id="status_#xxxx#"  tabindex="-1" style="width:80px; background:#F2F4CE"/>

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
                        <td width ="150px" align="center" style="background-color: silver; height: auto;" >
                            <div style=" width:90%; border: 3px inset gray; background-color: white; margin-top:10px; margin-bottom: 10px;">
                                <table width="95%" style="margin-left: 10px; margin-top: 10px;vertical-align: central;" >
                                    <tr>
                                        <td>
                                            <span class="multi-input-header" style="float:left;width:30px; text-align: center; ">[[.stt.]]</span>
                                            <span class="multi-input-header" style="float:left;width:56px; text-align: center; ">[[.room.]]</span>
                                            <span class="multi-input-header" style="float:left;width:146px; text-align: center; ">[[.start_date.]]</span>
                                       
                                            <span class="multi-input-header" style="float:left;width:146px; text-align: center; ">[[.expiry_date.]]</span>
                                            <!--<span class="multi-input-header" style="float:left;width:65px; text-align: center; ">Time expiry</span>-->
                                            <span class="multi-input-header" style="float:left;width:100px; text-align: center; ">[[.number_card.]]</span>
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
                                            <span style="font-weight: bold; font-size: 14px;">[[.read_key_reception.]]:</span>
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
    
    function Send(i)
    {
        //tao chuoi cho phong va gui tung phong i
        var str ='CN';
        var number_key = jQuery("#number_key_"+i).val();
        str = str + number_key;
        
        var reception = jQuery("#reception_id").val();
        var str_reception  = reception.split("_");
        str = str + '_' + str_reception[0] + '_E';
        
        
        var room = jQuery("#room_"+i).val();
        var str_room = room.split('_');
        
        var str_room_str ='';
        for(var j in doorid)
        {
            if(doorid[j]['room_id']==str_room[0])
            {
                str_room_str =doorid[j]['door_id'];
                break;  
            }
        }
        if(str_room_str=='')
            str_room_str = str_room[1];
        str +='_' + str_room_str;//room1
        str +='_ ';//room2
        str +='_ ';//room3
        str +='_ ';//room4
        str +='_ ';//user grant
        str +='_ ';//user denied
        //tao beginTime
        
        var date_start = jQuery("#date_start_"+i).val();
        
        var str_date_start = date_start.split('/');
        //var s1_start = str_date_start[0].split('/');
        var time_start = jQuery("#time_start_"+i).val();
        var str_time_start = time_start.split(':');
        
        var str_begin = str_time_start[0] + str_time_start[1];
        str_begin += str_date_start[0] + str_date_start[1] + str_date_start[2].substr(2,2);
        
        str +='_' + str_begin;
        
        //tao endtime
        var date_expiry = jQuery("#date_expiry_"+i).val();
        var str_date_expiry =date_expiry.split('/');
       // var s_expiry = str_date_expiry[0].split('/');
        var time_expiry = jQuery("#time_expiry_"+i).val();
        var str_time_expiry = time_expiry.split(':');
        
        
        var str_end = str_time_expiry[0] + str_time_expiry[1];
        str_end += str_date_expiry[0] + str_date_expiry[1] + str_date_expiry[2].substr(2,2);
        
        str +='_' + str_end;
        
        str +='_ ';//track 1
        str +='_ ';//track 2
        str +='_ ';//track 3
        str +='_ ';//track 4
        str +='_1';//feild 15
        
        console.log(str);
        var reservation_room_id = jQuery("#id_"+i).val();
      
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
                console.log(text_reponse);
                var arr_reponse = text_reponse.split("_");
                if(arr_reponse[1] == '1')
                    jQuery("#status_"+arr_reponse[0]).val(1);
                else
                {
                   jQuery("#status_"+arr_reponse[0]).val(-1);
                }
                setSrc();
                if(to_numeric(arr_reponse[0])<input_count)
                {
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
        xmlhttp.open("GET","create_key_salto.php?str_room="+ str +"&input="+i + "&rr_r_id=" + reservation_room_id + "&encoder="+str_reception[0],true);
        xmlhttp.send();
    
    }
    jQuery(document).ready(function(){
        jQuery("#home").click(function(e)
        {
            window.location.href = '?page=manager_key_salto&portal=' + '&portal=' + '<?php echo PORTAL_ID ; ?>';
        });
        jQuery("#create").click(function(e)
        {
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
    
    /*var lenghOption  =  document.Createform.reception_id.length;
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