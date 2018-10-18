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

<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input">
                <select name="mi_group[#xxxx#][room]" id="room_#xxxx#">
					[[|room_id_options|]]
				</select>
            </span>
			<span class="multi-input" style="width:20px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_group','#xxxx#','');event.returnValue=false; " style="cursor:pointer;"/></span>   
		</div>
        <br clear="all" />
	</span>
</span>
<form method="post" name="Createform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.create_card_title.]]</td>
                        <td style="width: 260px; " align="right" >
                            <!--<input type="button" id="home" value="Home" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />-->
                        </td>
                    </tr>
                </table>
                   
                <table width="800px" style="border: 1px solid #087E85;" >
                    <tr>
                        <td width ="150px" height="430px" align="center" style="background-color: silver; " >
                            <div style=" width: 400px; border: 3px inset gray; background-color: white; padding-right: 20px; padding-left: 40px; padding-top: 20px; ">
                                
                                <?php
                                if([[=result=]]!='')
                                {
                                    if([[=result=]]=='Create card success')
                                    {
                                        $style = 'style=" font-size: 16px;color:blue; font-family: tahoma;"';
                                    }   
                                    else
                                        $style = 'style=" font-size: 16px;color:red; font-family: tahoma;"';
                                    ?>
                                    <div style="width: 100%; height: auto;">
                                        <p <?php echo $style; ?> ><?php echo [[=result=]]; ?></p>
                                    </div>
                                    <?php
                                } 
                                ?>
                                <table width="100%" >
                                    <tr>
                                        <td width ="150px" valign="top" style=" font-size: 16px;">[[.room.]]</td>
                                        <td style=" font-size: 16px;">
                                            <div >
                                				<span id="mi_group_all_elems">
                                				</span>
                                			</div>
                                			<div style="padding-top: 2px;" ><a href="javascript:void(0);" onclick="if(input_count <104){mi_add_new_row('mi_group');$('room_'+input_count).focus();}" class="button-medium-add">[[.Add.]]</a></div>
                                        </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.start_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_start" type="text" id="date_start" style="text-align: center;" size="10" />
                                            <input name="time_start" type="text" id="time_start" style="text-align: center;" size="5" />
                                        </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.expiry_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_expiry" type="text" id="date_expiry" style="text-align: center;" size="10" />
                                            <input name="time_expiry" type="text" id="time_expiry" style="text-align: center;" size="5" />
                                        </td>
                                    </tr>
                                    <tr height="20px">
                                        <td style=" font-size: 16px;">[[.number_card.]]</td>
                                        <td style=" font-size: 16px;">
                                            <select id="number_key" name="number_key" style="width: 50px;" >
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.type_card.]]</td>
                                        <td style=" font-size: 16px;">
                                            <select id="type" name="type" style="width: 100px;" >
                                                <option value="CN">New card</option>
                                                <option value="CC">Copy card</option>
                                            </select>    
                                        </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.serial_card.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input id="serial" name="serial" type="checkbox" checked="checked"/>    
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; font-size: 16px;">
                                            [[.read_key_reception.]]:
                                        </td>
                                        <td>
                                            <select id="reception_id" name="reception_id" style="width: 80px;" >
                                                [[|reception_id|]]
                                            </select>
                                        </td>
                                    </tr>
                                    <tr height="60px" >
                                        <td colspan="2" align="center" >
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
    <input type="hidden" value="" name="get_result" id="get_result" />
    <input type="hidden" value="" name="width" id="width" />
</form>
<script>
    <?php if(isset($_REQUEST['mi_group'])){echo 'var arr = '.String::array2js($_REQUEST['mi_group']).';';}else{echo 'var arr = [];';}?>
    mi_init_rows('mi_group',arr);
    jQuery(document).ready(function(){
        jQuery("#width").val(screen.width);
        jQuery("#home").click(function(e)
        {
            window.location.href = '?page=manager_key_salto&portal=' + '<?php echo PORTAL_ID; ?>';
        });
        jQuery("#create").click(function(e)
        {
            if(check_input())
            {
                jQuery("#get_result").val('get_result');
                Createform.submit();
            }
        });
        jQuery("#date_start").datepicker();
        jQuery("#date_expiry").datepicker();
        jQuery('#time_start').mask('99:99');
        jQuery('#time_expiry').mask('99:99');
    });
    function check_input()
    {
        if(!jQuery("#date_start").val())
        {
            alert('Input start date!');
            return false;
        }
        if(!jQuery("#date_expiry").val())
        {
            alert('Input expiry date!');
            return false;
        }
        return true;
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
            var reception = document.Createform.reception_id.options[i].value;
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