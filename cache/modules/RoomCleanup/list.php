<?php System::set_page_title(HOTEL_NAME.' - '.$this->map['title']);?>

<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />


<div class="form_bound">
<form name="ListNoteForm" method="post">    
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="" width="60%" style="text-transform: uppercase; font-size: 20px;"> <i class="fa fa-recycle w3-text-orange" style="font-size: 28px;"></i> <?php echo $this->map['title'];?></td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <td style="padding-right: 50px; text-align: right;">
                <a class="w3-btn w3-cyan w3-text-white" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a>
            
            <?php }
            if( User::can_delete(false,ANY_CATEGORY) ){
            ?>
            
                <a class="w3-btn w3-red" href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListNoteForm.submit();" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a>
            </td>
            <?php }?>
        </tr>
    </table>
    
	
    <div class="form_content">
        <div class="search-box">
            <a name="top_anchor"></a>
            <fieldset>
            	<legend class="title"><?php echo Portal::language('search');?></legend>
                
                <table border="0" cellpadding="3" cellspacing="0">
        				<tr>
        					<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('start_date');?></td>
        					<td>:</td>
        					<td nowrap>
        						<input  name="from_date" id="from_date" style="width:70px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
        					</td>
        					<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('end_date');?></td>
        					<td>:</td>
        					<td nowrap>
        						<input  name="to_date" id="to_date" style="width:70px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
        					</td>
        					<td><input class="w3-btn w3-gray" name="search" type="submit" value="  <?php echo Portal::language('search');?>  " style="height: 24px; padding-top: 5px;"/></td>
        				</tr>
        		</table>
            </fieldset>
        </div>
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
        

        
			<div style="border:2px solid #FFFFFF;">
                <a name="top_anchor"></a>
                <table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
                	
                    <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px; text-transform: uppercase;">
                		<th width="1%" title="<?php echo Portal::language('check_all');?>">
                            <input type="checkbox" value="1" id="ManageProvince_all_checkbox" onclick="select_all_checkbox(this.form, 'ManageProvince',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/>
                        </th>
                		
                        <th width="30px" align="center" ><?php echo Portal::language('stt');?></th>
                        <th width="50px" align="center" ><?php echo Portal::language('id');?></th>
                        <th width="100px" align="center"><?php echo Portal::language('room');?></th>
                        <th width="120px" align="center"><?php echo Portal::language('status');?></th>
                        <th width="100px" align="center"><?php echo Portal::language('start_time');?></th>
                        <th width="100px" align="center"><?php echo Portal::language('end_time');?></th>
                        <th width="400px" align="center"><?php echo Portal::language('note');?></th>
                        <th width="130px" align="center"><?php echo Portal::language('user');?></th>
                        <th width="50px" align="center" nowrap>&nbsp;</th>
                		<th width="50px" align="center" nowrap>&nbsp;</th>
                	</tr>
                    
                	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" onclick="window.location='<?php echo Url::build_current( array( 'id'=>$this->map['items']['current']['id'],'cmd'=>'detail' ) );?>'" >
                		<td>
                            <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'ManageNote',this,'#FFFFEC','white');" id="ManageNote_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                            <input  name="id" id="id"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('id'));?>">
                        </td>
                        <td align="center"><?php echo $this->map['items']['current']['stt'];?></td>
                        <td align="center"><?php echo $this->map['items']['current']['id'];?></td>
                        <td align="center"><?php echo $this->map['items']['current']['room_name'];?></td>
                        <td align="center"><?php echo $this->map['items']['current']['status'];?></td>
                        <td align="center"><?php echo $this->map['items']['current']['start_time'];?></td>
                        <td align="center"><?php echo $this->map['items']['current']['end_time'];?></td>
                        <td align="left"><?php echo $this->map['items']['current']['note'];?></td>
                        <td align="center"><?php echo $this->map['items']['current']['user_id'];?></td>
                		<td align="center" nowrap>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>">
                                <i class="far fa-edit" style="color: green;"></i>
                            </a>
                        <?php }?>
                        </td>
                		<td align="center" nowrap>
                        <?php if(  User::can_delete(false,ANY_CATEGORY) ){?>
                            <a onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false}" href="<?php echo URL::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>">
                                <i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i>
                            </a>
                        <?php } ?>
                        </td>

                	</tr> 
                	<?php }}unset($this->map['items']['current']);} ?>
                </table>
		    </div>
            
			<div class="paging"><?php echo $this->map['paging'];?></div>
            
            <table width="100%">
                <tr>
        			<td width="100%">
        				<?php echo Portal::language('select');?>:&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',true,'#FFFFEC','white');"><i class="fa fa-angle-double-right"></i> <?php echo Portal::language('select_all');?></a>&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',false,'#FFFFEC','white');" style="padding-left: 20px;"> <i class="fa fa-angle-double-right"></i> <?php echo Portal::language('select_none');?></a>
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',-1,'#FFFFEC','white');" style="padding-left: 20px;"><i class="fa fa-angle-double-right"></i> <?php echo Portal::language('select_invert');?></a>
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
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>
