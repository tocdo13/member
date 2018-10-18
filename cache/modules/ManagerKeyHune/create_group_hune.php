<script type="text/javascript">
var doorid=<?php echo $this->map['doorid']; ?>;


</script>
<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#">
            <input name="mi_group[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#F2F4CE"/>
			<span class="multi-input"><input name="mi_group[#xxxx#][index]" type="text" readonly="true" id="index_#xxxx#"  tabindex="1" style="background:#CCC; width:30px ; text-align: center; "/></span>
            <span class="multi-input">
                <select  name="mi_group[#xxxx#][room]" style="width:60px; background-color: #F2F4CE;" tabindex="-1"  id="room_#xxxx#" >
					<?php echo $this->map['room_id_options'];?>
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
            <!--<span class="multi-input" ><img id="result_#xxxx#" src="" style="width: 16px; height: 16px;" />
            <input  name="fail_#xxxx#"  id="fail_#xxxx#" style="width: 10px; vertical-align: top; height: 15px;margin-left: 2px;display: none;background-color: transparent;border: 0px solid; font-size: 14px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('fail_#xxxx#'));?>"></span> 
            <input name="mi_group[#xxxx#][status]" type="hidden" value="0" readonly="" id="status_#xxxx#"  tabindex="-1" style="width:30px; background:#F2F4CE"/>-->

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
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >MANAGE GROUP KEY</td>
                        <td style="width: 260px; " align="right" >
                            <input type="button" id="exit" value="Exit" onclick="window.close();" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />
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
                                            <span class="multi-input-header" style="float:left;width:30px; text-align: center; ">Index</span>
                                            <span class="multi-input-header" style="float:left;width:60px; text-align: center; ">Room</span>
                                            <span class="multi-input-header" style="float:left;width:74px; text-align: center; ">Start date</span>
                                            <span class="multi-input-header" style="float:left;width:65px; text-align: center; ">Time start</span>
                                            <span class="multi-input-header" style="float:left;width:74px; text-align: center; ">Expiry date</span>
                                            <span class="multi-input-header" style="float:left;width:65px; text-align: center; ">Time expiry</span>
                                            <span class="multi-input-header" style="float:left;width:84px; text-align: center; ">Number keys</span>
                                            <span class="multi-input-header" style="float:left;width:40px; text-align: center; ">Delete</span>
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" >
                                            <div>
                                				<span id="mi_group_all_elems">
                                				</span>
                                			</div>
                                			<!--<div style="padding-top: 2px;" ><a href="javascript:void(0);" onclick="mi_add_new_row('mi_group');AddInput(input_count);" class="button-medium-add"><?php echo Portal::language('Add');?></a></div>-->
                                        </td>
                                    </tr>
                                    
                                    <tr height="60px" >
                                        
                                        <td align="center" >
                                            <span style="font-weight: bold; font-size: 14px;">Reception:</span>
                                            <select id="reception_id" name="reception_id" style="width: 80px;" >
                                                <?php echo $this->map['reception_id'];?>
                                            </select>
                                            &nbsp;&nbsp;&nbsp;
                                            <input name="create" id="create" type="submit" value="Create key" onclick="return check_input();" style="width: 100px; height: 40px; font-size: 16px; font-weight: bold; color: #1F6B7A; " />
                                            
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
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

<script type="text/javascript">
    <?php if(isset($_REQUEST['mi_group'])){echo 'var bars = '.String::array2js($_REQUEST['mi_group']).';';}else{echo 'var bars = [];';}?>
    mi_init_rows('mi_group',bars);
    
    
    
    jQuery(document).ready(function(){
        /*jQuery("#home").click(function(e)
        {
            window.location.href ='?page=manager_key_adel&portal='+ '<?php echo PORTAL_ID; ?>';
        });*/
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
  

</script>