<?php System::set_page_title(HOTEL_NAME.' - '.[[=title=]]);?>

<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />


<div class="form_bound">
<form name="ListNoteForm" method="post">    
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="" width="60%" style="text-transform: uppercase; font-size: 20px;"> <i class="fa fa-recycle w3-text-orange" style="font-size: 28px;"></i> [[|title|]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <td style="padding-right: 50px; text-align: right;">
                <a class="w3-btn w3-cyan w3-text-white" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a>
            
            <?php }
            if( User::can_delete(false,ANY_CATEGORY) ){
            ?>
            
                <a class="w3-btn w3-red" href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListNoteForm.submit();" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a>
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
        						<input name="from_date" type="text" id="from_date" style="width:70px; height: 24px;"/>
        					</td>
        					<td align="right" nowrap style="font-weight:bold">[[.end_date.]]</td>
        					<td>:</td>
        					<td nowrap>
        						<input name="to_date" type="text" id="to_date" style="width:70px; height: 24px;"/>
        					</td>
        					<td><input class="w3-btn w3-gray" name="search" type="submit" value="  [[.search.]]  " style="height: 24px; padding-top: 5px;"/></td>
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
                		<th width="1%" title="[[.check_all.]]">
                            <input type="checkbox" value="1" id="ManageProvince_all_checkbox" onclick="select_all_checkbox(this.form, 'ManageProvince',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/>
                        </th>
                		
                        <th width="30px" align="center" >[[.stt.]]</th>
                        <th width="50px" align="center" >[[.id.]]</th>
                        <th width="100px" align="center">[[.room.]]</th>
                        <th width="120px" align="center">[[.status.]]</th>
                        <th width="100px" align="center">[[.start_time.]]</th>
                        <th width="100px" align="center">[[.end_time.]]</th>
                        <th width="400px" align="center">[[.note.]]</th>
                        <th width="130px" align="center">[[.user.]]</th>
                        <th width="50px" align="center" nowrap>&nbsp;</th>
                		<th width="50px" align="center" nowrap>&nbsp;</th>
                	</tr>
                    
                	<!--LIST:items-->
                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" onclick="window.location='<?php echo Url::build_current( array( 'id'=>[[=items.id=]],'cmd'=>'detail' ) );?>'" >
                		<td>
                            <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'ManageNote',this,'#FFFFEC','white');" id="ManageNote_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                            <input name="id" type="hidden" id="id"/>
                        </td>
                        <td align="center">[[|items.stt|]]</td>
                        <td align="center">[[|items.id|]]</td>
                        <td align="center">[[|items.room_name|]]</td>
                        <td align="center">[[|items.status|]]</td>
                        <td align="center">[[|items.start_time|]]</td>
                        <td align="center">[[|items.end_time|]]</td>
                        <td align="left">[[|items.note|]]</td>
                        <td align="center">[[|items.user_id|]]</td>
                		<td align="center" nowrap>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>">
                                <i class="far fa-edit" style="color: green;"></i>
                            </a>
                        <?php }?>
                        </td>
                		<td align="center" nowrap>
                        <?php if(  User::can_delete(false,ANY_CATEGORY) ){?>
                            <a onclick="if(!confirm('[[.are_you_sure.]]')){return false}" href="<?php echo URL::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>">
                                <i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i>
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
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',true,'#FFFFEC','white');"><i class="fa fa-angle-double-right"></i> [[.select_all.]]</a>&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',false,'#FFFFEC','white');" style="padding-left: 20px;"> <i class="fa fa-angle-double-right"></i> [[.select_none.]]</a>
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',-1,'#FFFFEC','white');" style="padding-left: 20px;"><i class="fa fa-angle-double-right"></i> [[.select_invert.]]</a>
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
