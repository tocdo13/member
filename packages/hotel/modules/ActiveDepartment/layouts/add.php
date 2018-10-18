<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_department'));?>
<div align="center">
<table cellspacing="0" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
					<td width="30px" align="right"><a class="w3-orange w3-text-white w3-btn" onClick="AddActiveDepartmentForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.save.]]</a>
                    <a class="w3-btn w3-green" onClick="window.location='<?php echo Url::build_current();?>'" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a></td>                    
                </tr>
            </table>
		</td>
	</tr>
	
    <tr valign="top">
    	<td>
        	<form name="AddActiveDepartmentForm" method="post" >
                <fieldset>
                    <div class="form_content">
                	<table cellspacing="0" width="100%">
                	<tr bgcolor="#EFEFEF" valign="top">
                		<td width="100%">
        					<div style="border:2px solid #FFFFFF;">
                                <a name="top_anchor"></a>
                                <input name="portal_id" type="hidden" id="portal_id"/>
                                <table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
                                    <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
                                		<th width="40px" title="[[.check_all.]]">
                                            <input type="checkbox" value="1" id="ManagePortal_all_checkbox" onclick="select_all_checkbox(this.form, 'ManagePortal',this.checked,'#FFFFEC','white');"/>
                                        </th>
                                        <th width="10px" align="left" >[[.id.]]</th>
                                		<th width="300px" align="left" >[[.name.]]</th>
                                        <th width="300px" align="left" >[[.warehouse_auto.]]</th>
                                        <th width="300px" align="left" >[[.warehouse_auto.]] 2</th>
                                        <th width="300px" align="left" >[[.warehouse_department.]]</th>
                                	</tr>
             
                                	<!--LIST:department-->
                                	<tr bgcolor="#FF9" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                                		<td>
                                            <input name="selected_ids[]" type="checkbox" id="check_box_[[|department.id|]]" value="[[|department.id|]]" onclick="check_child(this.value,this.checked);" />
                                        </td>
                                        <td nowrap align="left" class="category-group" <?php Draw::hover('#E2F1DF');?>>[[|department.id|]]</td>
                                        <td nowrap align="left">[[|department.name|]]</td>
                                        <td nowrap align="left">
                                            <select  name="warehouse_id_[[|department.id|]]" id="warehouse_id_[[|department.id|]]">[[|warehouse_id|]]</select>
                                        </td>
                                        <td nowrap align="left">
                                            <!--IF:cond([[=department.id=]]=="RES" or [[=department.id=]]=="KARAOKE")-->
                                            <select  name="warehouse_id_2_[[|department.id|]]" id="warehouse_id_2_[[|department.id|]]">[[|warehouse_id|]]</select>
                                            <script>
                                                jQuery('#warehouse_id_2_[[|department.id|]]').val(<?php echo '\''.Url::get('warehouse_id_2_'.[[=department.id=]]).'\'' ?>);
                                            </script>  
                                            <!--/IF:cond-->
                                        </td>
                                        <td nowrap align="left">
                                            <select  name="warehouse_pc_id_[[|department.id|]]" id="warehouse_pc_id_[[|department.id|]]">[[|warehouse_id|]]</select>
                                        </td>
                                        <script>
                                            jQuery('#warehouse_id_[[|department.id|]]').val(<?php echo '\''.Url::get('warehouse_id_'.[[=department.id=]]).'\'' ?>);
                                            jQuery('#warehouse_pc_id_[[|department.id|]]').val(<?php echo '\''.Url::get('warehouse_pc_id_'.[[=department.id=]]).'\'' ?>);
                                        </script>                                
                                	</tr>
                                    <!--LIST:department.child-->
                                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                                		<td>
                                            <input name="selected_ids[]" type="checkbox" id="check_box_[[|department.child.id|]]" value="[[|department.child.id|]]" onclick="check_parent(this,this.checked);" parent="[[|department.id|]]"/>
                                        </td>
                                        <td style="text-indent: 30px;" align="left">[[|department.child.id|]]</td>
                                        <td style="text-indent: 30px;" align="left">[[|department.child.name|]]</td>
                                        <td align="left">
                                            <select  name="warehouse_id_[[|department.child.id|]]" id="warehouse_id_[[|department.child.id|]]">[[|warehouse_id|]]</select>
                                            <script>
                                                jQuery('#warehouse_id_[[|department.child.id|]]').val(<?php echo '\''.Url::get('warehouse_id_'.[[=department.child.id=]]).'\'' ?>);
                                            </script>
                                        </td>
                                        <td align="left">
                                            <!--IF:cond([[=department.id=]]=="RES" or [[=department.id=]]=="KARAOKE")-->
                                            <select  name="warehouse_id_2_[[|department.child.id|]]" id="warehouse_id_2_[[|department.child.id|]]">[[|warehouse_id|]]</select>
                                            <script>
                                                jQuery('#warehouse_id_2_[[|department.child.id|]]').val(<?php echo '\''.Url::get('warehouse_id_2_'.[[=department.child.id=]]).'\'' ?>);
                                            </script>  
                                            <!--/IF:cond-->
                                        </td>
                                        <td nowrap align="left">
                                            <select  name="warehouse_pc_id_[[|department.child.id|]]" id="warehouse_pc_id_[[|department.child.id|]]">[[|warehouse_id|]]</select>
                                            <script>
                                              jQuery('#warehouse_pc_id_[[|department.child.id|]]').val(<?php echo '\''.Url::get('warehouse_pc_id_'.[[=department.child.id=]]).'\'' ?>);  
                                            </script>
                                        </td>
                                                                      
                                	</tr>
                                	<!--/LIST:department.child-->
                                    
                                	<!--/LIST:department-->
                                </table>
        				    </div>
                			
                            
                            <table width="100%">
                                <tr>
                        			<td width="100%">
                        				[[.select.]]:&nbsp;
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',false,'#FFFFEC','white');">[[.select_none.]]</a>
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
                        			</td>
                        			<td>
                        				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"/></a>
                        			</td>
                    			</tr>
                            </table>
                		</td>
                	</tr>
                	</table>	
                	</div>                                                                                                                                                                                                                                              
                </fieldset>
        	</form>
    	</td>
    </tr>
</table>
</div>

<script>
 jQuery(document).ready(function(){
    
    dpt_portal_js = [[|portal_department_js|]];
    for(var j in dpt_portal_js){
        if(jQuery('#check_box_'+dpt_portal_js[j]['department_code']))
        {
            jQuery('#check_box_'+dpt_portal_js[j]['department_code']).attr('checked','checked');
        }
    }    
 })
 //check cha thi check luon con
 function check_child(parent,status)
 {
    var check = status;
    jQuery('[parent="'+parent+'"]').each(function(){
    	this.checked = check;
    });
 }
//check con thi check luon cha
function check_parent(obj,status)
 {
    var parent = jQuery(obj).attr('parent');
    if(status==true)
        jQuery('#check_box_'+parent).attr('checked','checked');
 }

</script> 