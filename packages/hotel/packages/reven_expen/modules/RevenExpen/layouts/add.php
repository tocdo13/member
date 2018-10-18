<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#">
            <input name="mi_group[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			<span class="multi-input"><input  name="mi_group[#xxxx#][amount]" type="text" id="amount_#xxxx#"  tabindex="-1" style="width:100px;" /></span>
            <span class="multi-input">
                <select name="mi_group[#xxxx#][currency_id]" style="width:55px;" id="currency_id_#xxxx#">
					[[|currency_id_options|]]
				</select>
            </span>
            
            <span class="multi-input">
                <select  name="mi_group[#xxxx#][item_id]" style="width:205px;"  id="item_id_#xxxx#">
					[[|item_id_options|]]
				</select>
            </span>
            
            <span class="multi-input"><input  name="mi_group[#xxxx#][time]" type="text" id="time_#xxxx#" style="width:55px;" /></span>
            <span class="multi-input"><input  name="mi_group[#xxxx#][hms]" type="text" id="hms_#xxxx#" style="width:29px;" /></span>
            
			<span class="multi-input">
                <select  name="mi_group[#xxxx#][member_id]" style="width:158px;"  id="member_id_#xxxx#">
					[[|user_id_options|]]
				</select>
            </span>
			<span class="multi-input"><input  name="mi_group[#xxxx#][note]" type="text" id="note_#xxxx#"  tabindex="-1" style="width:330px;"/></span>
            <span class="multi-input">
                <select  name="mi_group[#xxxx#][type]" style="width:50px;"  id="type_#xxxx#">
					<option value="1" <?php if(Url::get('type') and Url::get('type')==1) echo "selected"; ?> >Thu</option>
                    <option value="-1" <?php if(Url::get('type') and Url::get('type')==-1) echo "selected"; ?> >Chi</option>
				</select>
            </span>
            <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:20px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_group','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/></span>   
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>

<form name="RevenExpenAddForm" id="RevenExpenAddForm" method="post" >
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr height="40">
    		<td width="98%" class="form-title"><?php 
            if(Url::get('type')==1) 
            {
                ?>
                [[.revenue_manager.]]
                <?php 
            }
            else
            {
                ?>
                [[.spend_manager.]]
                <?php 
            }?></td>
    		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" name="btnSave" id="btnSave" value="[[.Save.]]" class="button-medium-save"/></td><?php }?>
    		<td width="" align="right"><a class="button-medium-back" onclick="history.go(-1)">[[.back.]]</a></td>
        </tr>
        
    </table>
    
    
    <fieldset>
        <legend class="title">[[.type.]]</legend>
		<table border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td><strong>[[.item.]]</strong>: 
                    <select  name="item_all" id="item_all" style="width:180px;" >
        				[[|item_id_options|]]
                    </select>
                </td>
                
                <td><strong>[[.type.]]</strong>: 
                    <select  name="type_all" id="type_all" style="width:50px;">
                    <!--Luu Nguyen Giap edit Thu & Chu form-->
        				<option value="1" <?php if(Url::get('type')==1) echo ' selected="selected"';?>>Thu</option>
                        <option value="-1" <?php if(Url::get('type')==-1) echo ' selected="selected"';?>>Chi</option>
                    <!--Luu Nguyen Giap End-->
                    </select>
                </td>
			</tr>
		</table>
    </fieldset>
    
    <fieldset>
        <legend class="title">[[.goods.]]</legend>
		<table cellspacing="0">
        	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
        	<tr>
                <td style="padding-bottom:30px">
            		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
            		<table border="0">
                		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
                		<tr bgcolor="#EEEEEE" valign="top">
                			<td style="">
                    			<div style="background-color:#EFEFEF;">
                    				<span id="mi_group_all_elems">
                    					<span style="white-space:nowrap; width:auto;">
                    						<span class="multi-input-header" style="float:left;width:100px;">[[.amount.]]</span>
                                            <span class="multi-input-header" style="float:left;width:51px;">[[.currencies.]]</span>
                                            <span class="multi-input-header" style="float:left;width:201px;">[[.item.]]</span>
                                            <span class="multi-input-header" style="float:left;width:89px;">[[.time.]]</span>
                                            <span class="multi-input-header" style="float:left;width:154px;">[[.member.]]</span>
                                            <span class="multi-input-header" style="float:left;width:330px;">[[.note.]]</span>
                                            <span class="multi-input-header" style="float:left;width:46px;">[[.type.]]</span>
                                            <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
                    						<span class="multi-input-header" style="float:left;width:30px;text-align:center;">[[.Delete.]]?</span>
                    						<!--/IF:delete-->
                                            <br clear="all">
                    					</span>
                    				</span>
                    			</div>
                    			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_group');AddInput(input_count);jQuery('#add_charge_'+input_count).ForceNumericOnly();" class="button-medium-add">[[.Add.]]</a></div>
                            </td>
                       </tr>
                   </table>
                </td>
            </tr>
         </table>	
    </fieldset>
    
    
    
 </form>
<script>
    <?php if(isset($_REQUEST['mi_group'])){echo 'var bars = '.String::array2js($_REQUEST['mi_group']).';';}else{echo 'var bars = [];';}?>
    mi_init_rows('mi_group',bars);
    jQuery(document).ready(function(){
        for(var i=101;i<=input_count;i++){
        	if(jQuery("#time_"+i)){
        		jQuery("#time_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
        	}
            
            var index = i;
            if(jQuery("#amount_"+index)){
                jQuery("#amount_"+index).keyup(function()
                {
                    processStr(index);
                });
            }
            
            processStr(i);
        }
       
        jQuery("#item_all").change(function(){
            var selected = document.getElementById('item_all').selectedIndex;
             var inputs = document.getElementsByTagName('select');
             
             for (var i=0;i<inputs.length;i++)
             {
                if(inputs[i].id.search('item_id') >= 0 && inputs[i].id != 'item_id_#xxxx#')
                {
                    inputs[i].selectedIndex = selected;
                }
             }
             
             
        });  
       
        
        jQuery("#type_all").change(function(){
            var selected = document.getElementById('type_all').selectedIndex;
             var inputs = document.getElementsByTagName('select');
             //console.log(inputs);
             for (var i=0;i<inputs.length;i++)
             {
                if(inputs[i].id.search('type') >= 0 && inputs[i].id != 'type_#xxxx#')
                {
                    inputs[i].selectedIndex = selected;
                }
             }
             
             //Luu Nguyen Giap add 
            // getValue();
            document.getElementById('RevenExpenAddForm').submit();
            
            //window.location=<?php echo "Url::build_current(array('cmd'=>'add','type'=>Url::get('type_all')))"; ?>;
            
            
        });
    });
    function getValue()
    {
        var type_id = jQuery("#type_all").val().replace('#',"");
        //console.log(type_id);
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
                var objs = jQuery.parseJSON(text_reponse);
                var txt=document.getElementById("item_all").innerHTML;
                var new_content = '';
                //console.log(text_reponse);                        
                for(var obj in objs)
                {
                    new_content +='<option value="'+obj+'" >'+objs[obj]+'</option>';
                }
                document.getElementById("item_all").innerHTML = new_content;
            }
        }
        xmlhttp.open("GET","packages/hotel/packages/reven_expen/modules/RevenExpen/forms/db.php?data=get_value&type="+type_id,true);
        xmlhttp.send(); 
    }
    function AddInput(input_count){
        jQuery("#amount_"+input_count).keyup(function()
        {
             processStr(input_count);    
        });
        
        jQuery("#time_"+input_count).val(jQuery.datepicker.formatDate('dd/mm/yy', new Date()));
    	jQuery("#time_"+input_count).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
    }
    
    function processStr(index_item)
    {
        if(jQuery("#amount_"+index_item)){
            var strVal = jQuery("#amount_"+index_item).val();
            var strValn = strVal.replace(/,/g,"");
                                
            var len = strValn.length;
            var i;
            var newStr = '';
            for(i = len; i > 0; i-=3)
            {
                var strTmp;
                if(i-3 > 0)
                    strTmp = ','+strValn.substr(i-3,3);
                else
                    strTmp = strValn.substr(0,i);
                                                
                newStr = strTmp+newStr;
            }
            jQuery("#amount_"+index_item).val(newStr);
       	}
        
        if(jQuery('#hms_'+index_item))
        {
            jQuery('#hms_'+index_item).mask('99:99');
        
            /*
            jQuery('#hms_'+index_item).keyup(function(){
                var val = jQuery('#hms_'+index_item).val();
                if(val.indexOf(":") < 0 && val.length >= 2)
                {
                    var newVal = val.substr(0,2)+":"+val.substr(2,val.length-2);
                    jQuery('#hms_'+index_item).val(newVal);
                    //jQuery('#hms_'+index_item).setSelectionRange(1,1);
                    var cursorPosition = jQuery('#hms_'+index_item).prop("selectionStart");
                    document.getElementById('hms_'+index_item).setSelectionRange(2,2);
                }
                /*
                val = jQuery('#hms_'+index_item).val();
                var arr=val.split(":");
                
                val = '';
                for(var i = 0; i < arr.length; i++)
                {
                    if(i>0)
                        val += ":";
                    if(arr[i] < 10)
                        val += "_"+arr[i];
                    else
                        val += arr[i];
                }
                
                jQuery('#hms_'+index_item).val(val);
                //console.log(val);
                
            });
            */
        }
    }
    
    
    function Confirm(index){
    	//var mi_group_name = $('name_'+ index).value;
    	//return confirm('[[.Are_you_sure_delete_mi_group_name.]] '+ mi_group_name+'?');
        return confirm('[[.Are_you_sure_delete_mi_group_name.]] ?');
    }
</script>