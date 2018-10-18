<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_department'));?>

<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />


<div class="form_bound">
<form name="ListActiveDepartmentForm" method="post">    
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="form-title" width="100%"><?php echo Portal::language('list_department');?></td>
        </tr>
    </table>
    
    <div class="form_content">
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
    			<tr>
    				<td width="100%">
    					<div style="border:2px solid #FFFFFF;">
                            <a name="top_anchor"></a>
                            <table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
                            	
                                <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
                            		
                                    <th width="14%" align="left"><?php echo Portal::language('portal_id');?></th>
                            		
                            		<th width="13%" align="left"><?php echo Portal::language('portal_name');?></th>
                                    
                                    <th width="13%" align="left"><?php echo Portal::language('department');?></th>
                            		
                            		
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<th width="1%" align="center" nowrap><?php echo Portal::language('select_warehouse');?></th>
                            		<?php }?>
                            		
                            	</tr>
                                
                            	<?php if(isset($this->map['portal']) and is_array($this->map['portal'])){ foreach($this->map['portal'] as $key1=>&$item1){if($key1!='current'){$this->map['portal']['current'] = &$item1;?>
                            	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                            		
                                    <td align="left"><?php echo $this->map['portal']['current']['id'];?></td>
                                   
                                    <td align="left"><?php echo $this->map['portal']['current']['name'];?></td>
                                    
                                    
                                    <td nowrap align="left">
                                    <?php if(isset($this->map['portal']['current']['department']) and is_array($this->map['portal']['current']['department'])){ foreach($this->map['portal']['current']['department'] as $key2=>&$item2){if($key2!='current'){$this->map['portal']['current']['department']['current'] = &$item2;?>
                                    <?php echo $this->map['portal']['current']['department']['current']['name'];?>,&nbsp;
                                    <?php }}unset($this->map['portal']['current']['department']['current']);} ?>
                                    </td>
                            		
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a href="<?php echo URL::build_current(array('cmd'=>'edit','portal_id'=>$this->map['portal']['current']['id']));?>">
                                            <img src="packages/core/skins/default/images/buttons/edit.gif" alt="Sua"/>
                                        </a>
                                    </td>
                            		<?php }?>
                            		
                            	</tr>
                            	<?php }}unset($this->map['portal']['current']);} ?>
                            </table>
    				    </div>
    				</td>
    			</tr>
			</table>
			
            
            <table width="100%">
                <tr>
        			<td width="100%">
        				&nbsp;
        			</td>
        			<td>
        				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="<?php echo Portal::language('top');?>" border="0" alt="<?php echo Portal::language('top');?>"></a>
        			</td>
    			</tr>
            </table>
		</td>
	</tr>
	</table>	
	</div>
    
                                                                
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
