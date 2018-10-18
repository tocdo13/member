<?php System::set_page_title(HOTEL_NAME.' - '.[[=title=]]);?>

<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />


<div class="form_bound">
<form name="ListNoteForm" method="post">    
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="form-title" width="100%">[[|title|]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <td>
                <a class="button-medium-add" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>">[[.Add.]]</a>
            </td>
            <?php }
            if( User::can_admin(MODULE_MANAGENOTE,ANY_CATEGORY) ){
            ?>
            <td>
                <a class="button-medium-delete" href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListNoteForm.submit();">[[.Delete.]]</a>
            </td>
            <?php }?>
        </tr>
    </table>
    
	
    <div class="form_content">
        <div class="search-box">
            <a name="top_anchor"></a>
            <fieldset>
            	<legend class="title">[[.search.]]</legend>
                
                <table border="0" cellpadding="3" cellspacing="0">
        				<tr>
        					<td align="right" nowrap style="font-weight:bold">[[.start_date.]]</td>
        					<td>:</td>
        					<td nowrap>
        						<input name="from_date" type="text" id="from_date" style="width:70px"/>
        					</td>
        					<td align="right" nowrap style="font-weight:bold">[[.end_date.]]</td>
        					<td>:</td>
        					<td nowrap>
        						<input name="to_date" type="text" id="to_date" style="width:70px"/>
        					</td>
        					<td><input name="search" type="submit" value="  [[.search.]]  "/></td>
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
                	
                    <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
                		<th width="1%" title="[[.check_all.]]">
                            <input type="checkbox" value="1" id="ManageProvince_all_checkbox" onclick="select_all_checkbox(this.form, 'ManageProvince',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/>
                        </th>
                		
                        <th width="10px" align="left" >[[.stt.]]</th>
                		
                		<th width="300px" align="left">[[.content.]]</th>
                        
                        <th width="100px" align="left">[[.create.]]</th>
                        
                        <th width="100px" align="left">[[.modify.]]</th>
                       
                        
                        <!--<th width="50px" align="left">[[.confirm_user.]]</th>-->
                        
                		<th width="1%" align="center" nowrap>&nbsp;</th>
                		
                		<th width="1%" align="center" nowrap>&nbsp;</th>
                	</tr>
                    
                	<!--LIST:items-->
                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" >
                		<td>
                            <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'ManageNote',this,'#FFFFEC','white');" id="ManageNote_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                            <input name="id" type="hidden" id="id"/>
                        </td>
                		
                        <td nowrap align="left">[[|items.stt|]]</td>
                       
                        <td nowrap align="left">[[|items.note|]]</td>
                        
                        <td nowrap align="left">
                            [[.create_user.]]: <strong>[[|items.user_id|]]</strong>
                            <br />
                            [[.create_time.]]: <strong>[[|items.create_time|]]</strong>
                        </td>
                        
                        <td nowrap align="left">
                            [[.last_modify_user.]]: <strong>[[|items.last_modify_user_id|]]</strong>
                            <br />
                            [[.last_modify_time.]]: <strong>[[|items.last_modify_time|]]</strong>
                        </td>
                      
                		<td align="left" nowrap>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>">
                                <img src="packages/core/skins/default/images/buttons/edit.gif" alt="Sua"/>
                            </a>
                        <?php }?>
                        </td>
                        
                		<td align="left" nowrap>
                        <?php if(  User::can_admin(MODULE_MANAGENOTE,ANY_CATEGORY) or ( Session::get('user_id') == [[=items.user_id=]] )  ){?>
                            <a onclick="if(!confirm('[[.are_you_sure.]]')){return false}" href="<?php echo URL::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>">
                                <img src="packages/core/skins/default/images/buttons/delete.gif" alt="Xoa"/>
                            </a>
                        <?php } ?>
                        </td>

                	</tr> 
                	<!--/LIST:items-->
                </table>
		    </div>
            
			<div class="paging">[[|paging|]]</div>
            
            <table width="100%">
                <tr>
        			<td width="100%">
        				[[.select.]]:&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',false,'#FFFFEC','white');">[[.select_none.]]</a>
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
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
</form>
</div>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>
