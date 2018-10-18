<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_department'));?>
<div align="center">
<table cellspacing="0" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
					<td width="30px" align="right"><a class="w3-orange w3-text-white w3-btn" onClick="AddActiveDepartmentForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('save');?></a>
                    <a class="w3-btn w3-green" onClick="window.location='<?php echo Url::build_current();?>'" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('back');?></a></td>                    
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
                                <input  name="portal_id" id="portal_id"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('portal_id'));?>">
                                <table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
                                    <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
                                		<th width="40px" title="<?php echo Portal::language('check_all');?>">
                                            <input type="checkbox" value="1" id="ManagePortal_all_checkbox" onclick="select_all_checkbox(this.form, 'ManagePortal',this.checked,'#FFFFEC','white');"/>
                                        </th>
                                        <th width="10px" align="left" ><?php echo Portal::language('id');?></th>
                                		<th width="300px" align="left" ><?php echo Portal::language('name');?></th>
                                        <th width="300px" align="left" ><?php echo Portal::language('warehouse_auto');?></th>
                                        <th width="300px" align="left" ><?php echo Portal::language('warehouse_auto');?> 2</th>
                                        <th width="300px" align="left" ><?php echo Portal::language('warehouse_department');?></th>
                                	</tr>
             
                                	<?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key1=>&$item1){if($key1!='current'){$this->map['department']['current'] = &$item1;?>
                                	<tr bgcolor="#FF9" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                                		<td>
                                            <input name="selected_ids[]" type="checkbox" id="check_box_<?php echo $this->map['department']['current']['id'];?>" value="<?php echo $this->map['department']['current']['id'];?>" onclick="check_child(this.value,this.checked);" />
                                        </td>
                                        <td nowrap align="left" class="category-group" <?php Draw::hover('#E2F1DF');?>><?php echo $this->map['department']['current']['id'];?></td>
                                        <td nowrap align="left"><?php echo $this->map['department']['current']['name'];?></td>
                                        <td nowrap align="left">
                                            <select  name="warehouse_id_<?php echo $this->map['department']['current']['id'];?>" id="warehouse_id_<?php echo $this->map['department']['current']['id'];?>"><?php echo $this->map['warehouse_id'];?></select>
                                        </td>
                                        <td nowrap align="left">
                                            <?php 
				if(($this->map['department']['current']['id']=="RES" or $this->map['department']['current']['id']=="KARAOKE"))
				{?>
                                            <select  name="warehouse_id_2_<?php echo $this->map['department']['current']['id'];?>" id="warehouse_id_2_<?php echo $this->map['department']['current']['id'];?>"><?php echo $this->map['warehouse_id'];?></select>
                                            <script>
                                                jQuery('#warehouse_id_2_<?php echo $this->map['department']['current']['id'];?>').val(<?php echo '\''.Url::get('warehouse_id_2_'.$this->map['department']['current']['id']).'\'' ?>);
                                            </script>  
                                            
				<?php
				}
				?>
                                        </td>
                                        <td nowrap align="left">
                                            <select  name="warehouse_pc_id_<?php echo $this->map['department']['current']['id'];?>" id="warehouse_pc_id_<?php echo $this->map['department']['current']['id'];?>"><?php echo $this->map['warehouse_id'];?></select>
                                        </td>
                                        <script>
                                            jQuery('#warehouse_id_<?php echo $this->map['department']['current']['id'];?>').val(<?php echo '\''.Url::get('warehouse_id_'.$this->map['department']['current']['id']).'\'' ?>);
                                            jQuery('#warehouse_pc_id_<?php echo $this->map['department']['current']['id'];?>').val(<?php echo '\''.Url::get('warehouse_pc_id_'.$this->map['department']['current']['id']).'\'' ?>);
                                        </script>                                
                                	</tr>
                                    <?php if(isset($this->map['department']['current']['child']) and is_array($this->map['department']['current']['child'])){ foreach($this->map['department']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['department']['current']['child']['current'] = &$item2;?>
                                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                                		<td>
                                            <input name="selected_ids[]" type="checkbox" id="check_box_<?php echo $this->map['department']['current']['child']['current']['id'];?>" value="<?php echo $this->map['department']['current']['child']['current']['id'];?>" onclick="check_parent(this,this.checked);" parent="<?php echo $this->map['department']['current']['id'];?>"/>
                                        </td>
                                        <td style="text-indent: 30px;" align="left"><?php echo $this->map['department']['current']['child']['current']['id'];?></td>
                                        <td style="text-indent: 30px;" align="left"><?php echo $this->map['department']['current']['child']['current']['name'];?></td>
                                        <td align="left">
                                            <select  name="warehouse_id_<?php echo $this->map['department']['current']['child']['current']['id'];?>" id="warehouse_id_<?php echo $this->map['department']['current']['child']['current']['id'];?>"><?php echo $this->map['warehouse_id'];?></select>
                                            <script>
                                                jQuery('#warehouse_id_<?php echo $this->map['department']['current']['child']['current']['id'];?>').val(<?php echo '\''.Url::get('warehouse_id_'.$this->map['department']['current']['child']['current']['id']).'\'' ?>);
                                            </script>
                                        </td>
                                        <td align="left">
                                            <?php 
				if(($this->map['department']['current']['id']=="RES" or $this->map['department']['current']['id']=="KARAOKE"))
				{?>
                                            <select  name="warehouse_id_2_<?php echo $this->map['department']['current']['child']['current']['id'];?>" id="warehouse_id_2_<?php echo $this->map['department']['current']['child']['current']['id'];?>"><?php echo $this->map['warehouse_id'];?></select>
                                            <script>
                                                jQuery('#warehouse_id_2_<?php echo $this->map['department']['current']['child']['current']['id'];?>').val(<?php echo '\''.Url::get('warehouse_id_2_'.$this->map['department']['current']['child']['current']['id']).'\'' ?>);
                                            </script>  
                                            
				<?php
				}
				?>
                                        </td>
                                        <td nowrap align="left">
                                            <select  name="warehouse_pc_id_<?php echo $this->map['department']['current']['child']['current']['id'];?>" id="warehouse_pc_id_<?php echo $this->map['department']['current']['child']['current']['id'];?>"><?php echo $this->map['warehouse_id'];?></select>
                                            <script>
                                              jQuery('#warehouse_pc_id_<?php echo $this->map['department']['current']['child']['current']['id'];?>').val(<?php echo '\''.Url::get('warehouse_pc_id_'.$this->map['department']['current']['child']['current']['id']).'\'' ?>);  
                                            </script>
                                        </td>
                                                                      
                                	</tr>
                                	<?php }}unset($this->map['department']['current']['child']['current']);} ?>
                                    
                                	<?php }}unset($this->map['department']['current']);} ?>
                                </table>
        				    </div>
                			
                            
                            <table width="100%">
                                <tr>
                        			<td width="100%">
                        				<?php echo Portal::language('select');?>:&nbsp;
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',true,'#FFFFEC','white');"><?php echo Portal::language('select_all');?></a>&nbsp;
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',false,'#FFFFEC','white');"><?php echo Portal::language('select_none');?></a>
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',-1,'#FFFFEC','white');"><?php echo Portal::language('select_invert');?></a>
                        			</td>
                        			<td>
                        				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="<?php echo Portal::language('top');?>" border="0" alt="<?php echo Portal::language('top');?>"/></a>
                        			</td>
                    			</tr>
                            </table>
                		</td>
                	</tr>
                	</table>	
                	</div>                                                                                                                                                                                                                                              
                </fieldset>
        	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
    	</td>
    </tr>
</table>
</div>

<script>
 jQuery(document).ready(function(){
    
    dpt_portal_js = <?php echo $this->map['portal_department_js'];?>;
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