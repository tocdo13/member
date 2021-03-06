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
            if( User::can_admin(false,ANY_CATEGORY) ){
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
        					<td align="right" style="font-weight:bold">[[.start_date.]]</td>
        					<td>:</td>
        					<td>
        						<input name="from_date" type="text" id="from_date" onchange="changevalue()" style="width:70px"/>
        					</td>
        					<td align="right" style="font-weight:bold">[[.end_date.]]</td>
        					<td>:</td>
        					<td>
        						<input name="to_date" type="text" id="to_date" onchange="changefromday();" style="width:70px"/>
        					</td>
                            <td class="label">[[.customer.]]</td>
                            <td>
                                <select name="customer_id" id="customer_id"></select>
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
                		
                		<th width="150px" align="left">[[.customer_name.]]</th>
                        
                        <th width="150px" align="left">[[.total.]]</th>
                        
                        <th width="80px" align="left">[[.date.]]</th>
                        
                        <th width="150px" align="left">[[.note.]]</th>
                        
                        <th width="100px" align="left">[[.create.]]</th>
                        
                        <th width="100px" align="left">[[.modify.]]</th>
                        

                        
                		<th width="1%" align="center" >&nbsp;</th>
                		
                		<th width="1%" align="center" >&nbsp;</th>
                	</tr>
                    
                	<!--LIST:items-->
                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" >
                		<td>
                            <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'ManageNote',this,'#FFFFEC','white');" id="ManageNote_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                            <input name="id" type="hidden" id="id"/>
                        </td>
                		
                        <td  align="left">[[|items.stt|]]</td>
                       
                        <td  align="left">[[|items.customer_name|]]</td>
                        
                        <td  align="left">[[|items.total|]]</td>
                        
                        <td  align="left">[[|items.create_date|]]</td>
                        
                        <td  align="left">[[|items.note|]]</td>
                        
                        <td  align="left">
                            [[|items.user_id|]]
                            <br />
                            [[|items.time|]]
                        </td>
                        
                        <td  align="left">
                            [[|items.lastest_edited_user_id|]]
                            <br />
                            [[|items.lastest_edited_time|]]
                        </td>
                        
                        
                		
                        
                		<td align="left" >
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>">
                                <img src="packages/core/skins/default/images/buttons/edit.gif" alt="Sua"/>
                            </a>
                        <?php }?>
                        </td>
                        
                		<td align="left" >
                        <?php if(  User::can_admin(false,ANY_CATEGORY) or ( Session::get('user_id') == [[=items.user_id=]] )  ){?>
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
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
</script>
