<?php System::set_page_title(HOTEL_NAME.' - '.[[=title=]]);?>

<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />


<div class="form_bound">
<form name="ListNoteForm" method="post">    
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <td style="width: 30%; text-align: right; padding-right: 30px;">
                <a class="w3-btn w3-teal w3-text-white" name ="add_auto" onclick="fun_get_amenities_used();" style="text-transform: uppercase; margin-right: 5px;">[[.Add_auto.]]</a>
           
                <a class="w3-btn w3-cyan w3-text-white" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>" onclick="if([[|check|]]>0){alert('[[.recode_exist.]]');return false;}" style="text-transform: uppercase; margin-right: 5px;">[[.Add.]]</a>
     
            <?php }
            if( User::can_delete(false,ANY_CATEGORY) ){
            ?>
          
                <a class="w3-btn w3-red w3-text-white" href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListNoteForm.submit();" style="text-transform: uppercase; margin-right: 5px;">[[.Delete.]]</a>
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
                            <!--Start Luu Nguyen Giap add portal -->
                            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                            <td nowrap="nowrap" style="font-weight: bold;">[[.hotel.]]</td>
                            <td style="margin: 0;"><select name="portal_id" id="portal_id"></select></td>
                            <?php //}?>
                            <!--End Luu Nguyen Giap add portal -->
        					<td align="right" nowrap style="font-weight:bold">[[.start_date.]]</td>
        					<td>:</td>
        					<td nowrap>
        						<input name="from_date" type="text" id="from_date" style="width:70px" onchange="changevalue();"/>
                                
        					</td>
        					<td align="right" nowrap style="font-weight:bold">[[.end_date.]]</td>
        					<td>:</td>
        					<td nowrap>
        						<input name="to_date" type="text" id="to_date" style="width:70px" onchange="changefromday();"/>
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
                        <th width="10px" align="left" >[[.id.]]</th>
                        <th width="100px" align="left">[[.create.]]</th>
                        <th width="100px" align="left">[[.modify.]]</th>
                        <th width="300px" align="left">[[.note.]]</th>
                		<th width="1%" align="center" nowrap>&nbsp;</th>
                        <th width="1%" align="center" nowrap>&nbsp;</th>
                		<th width="1%" align="center" nowrap>&nbsp;</th>
                	</tr>
                    
                	<!--LIST:items-->
                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;"  > <!--onclick="window.location='</?php echo Url::build_current( array( 'id'=>[[=items.id=]],'cmd'=>'detail' ) );?>'"-->
                		<td>
                        <?php if(  User::can_delete(false,ANY_CATEGORY)){?>
                            <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'ManageNote',this,'#FFFFEC','white');" id="ManageNote_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                            <input name="id" type="hidden" id="id"/>
                         <?php }?>   
                        </td>
                        <td nowrap align="left">[[|items.stt|]]</td>
                        <td nowrap align="left">[[|items.id|]]</td>
                        <td nowrap align="left">
                            [[.create_user.]]: <strong>[[|items.user_id|]]</strong>
                            <br />
                            [[.create_time.]]: <strong>[[|items.time|]]</strong>
                        </td>
                        <td nowrap align="left">
                            [[.last_modify_user.]]: <strong>[[|items.last_modify_user_id|]]</strong>
                            <br />
                            [[.last_modify_time.]]: <strong>[[|items.last_modify_time|]]</strong>
                        </td>
                        <td nowrap align="left">[[|items.note|]]</td>
                		<td align="left" nowrap>
                        <?php if(User::can_view(false,ANY_CATEGORY)){?>
                            <a href="<?php echo URL::build_current(array('cmd'=>'detail','id'=>[[=items.id=]]));?>">
                                <img src="packages/core/skins/default/images/buttons/select.jpg" alt="Chi tiet"/>
                            </a>
                        <?php }?>
                        </td>
                		<td align="left" nowrap>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>">
                                <img src="packages/core/skins/default/images/buttons/edit.gif" alt="Sua"/>
                            </a>
                        <?php }?>
                        </td>
                		<td align="left" nowrap>
                        <?php if(  User::can_delete(false,ANY_CATEGORY)){?>
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
    function fun_get_amenities_used()
    {

            if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
            }
            else{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    var text_reponse = xmlhttp.responseText;
                    console.log(text_reponse);
                    if(text_reponse==1)
                    {
                        alert('Thêm thành công!');
                  
                        location.reload();
                    }
                    else
                    {
                        alert('Thêm không thành công.(có thể chưa có phòng nào được đặt.hoặc chưa khai báo amenities)');
                        location.reload();
                    }
                }
            }
            xmlhttp.open("GET","get_amenities_used.php?data=get_amenities_used&check_auto=1",true);
            xmlhttp.send();
    }
    
</script>
