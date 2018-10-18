<?php System::set_page_title(HOTEL_NAME.' - '.$this->map['title']);?>

<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />


<div class="form_bound">
<form name="ListNoteForm" method="post">    
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <td style="width: 30%; text-align: right; padding-right: 30px;">
                <a class="w3-btn w3-teal w3-text-white" name ="add_auto" onclick="fun_get_amenities_used();" style="text-transform: uppercase; margin-right: 5px;"><?php echo Portal::language('Add_auto');?></a>
           
                <a class="w3-btn w3-cyan w3-text-white" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>" onclick="if(<?php echo $this->map['check'];?>>0){alert('<?php echo Portal::language('recode_exist');?>');return false;}" style="text-transform: uppercase; margin-right: 5px;"><?php echo Portal::language('Add');?></a>
     
            <?php }
            if( User::can_delete(false,ANY_CATEGORY) ){
            ?>
          
                <a class="w3-btn w3-red w3-text-white" href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListNoteForm.submit();" style="text-transform: uppercase; margin-right: 5px;"><?php echo Portal::language('Delete');?></a>
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
                            <!--Start Luu Nguyen Giap add portal -->
                            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                            <td nowrap="nowrap" style="font-weight: bold;"><?php echo Portal::language('hotel');?></td>
                            <td style="margin: 0;"><select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
                            <?php //}?>
                            <!--End Luu Nguyen Giap add portal -->
        					<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('start_date');?></td>
        					<td>:</td>
        					<td nowrap>
        						<input  name="from_date" id="from_date" style="width:70px" onchange="changevalue();"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                                
        					</td>
        					<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('end_date');?></td>
        					<td>:</td>
        					<td nowrap>
        						<input  name="to_date" id="to_date" style="width:70px" onchange="changefromday();"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
        					</td>
                            
        					<td><input name="search" type="submit" value="  <?php echo Portal::language('search');?>  "/></td>
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
                		<th width="1%" title="<?php echo Portal::language('check_all');?>">
                            <input type="checkbox" value="1" id="ManageProvince_all_checkbox" onclick="select_all_checkbox(this.form, 'ManageProvince',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/>
                        </th>
                		
                        <th width="10px" align="left" ><?php echo Portal::language('stt');?></th>
                        <th width="10px" align="left" ><?php echo Portal::language('id');?></th>
                        <th width="100px" align="left"><?php echo Portal::language('create');?></th>
                        <th width="100px" align="left"><?php echo Portal::language('modify');?></th>
                        <th width="300px" align="left"><?php echo Portal::language('note');?></th>
                		<th width="1%" align="center" nowrap>&nbsp;</th>
                        <th width="1%" align="center" nowrap>&nbsp;</th>
                		<th width="1%" align="center" nowrap>&nbsp;</th>
                	</tr>
                    
                	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;"  > <!--onclick="window.location='</?php echo Url::build_current( array( 'id'=>$this->map['items']['current']['id'],'cmd'=>'detail' ) );?>'"-->
                		<td>
                        <?php if(  User::can_delete(false,ANY_CATEGORY)){?>
                            <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'ManageNote',this,'#FFFFEC','white');" id="ManageNote_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                            <input  name="id" id="id"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('id'));?>">
                         <?php }?>   
                        </td>
                        <td nowrap align="left"><?php echo $this->map['items']['current']['stt'];?></td>
                        <td nowrap align="left"><?php echo $this->map['items']['current']['id'];?></td>
                        <td nowrap align="left">
                            <?php echo Portal::language('create_user');?>: <strong><?php echo $this->map['items']['current']['user_id'];?></strong>
                            <br />
                            <?php echo Portal::language('create_time');?>: <strong><?php echo $this->map['items']['current']['time'];?></strong>
                        </td>
                        <td nowrap align="left">
                            <?php echo Portal::language('last_modify_user');?>: <strong><?php echo $this->map['items']['current']['last_modify_user_id'];?></strong>
                            <br />
                            <?php echo Portal::language('last_modify_time');?>: <strong><?php echo $this->map['items']['current']['last_modify_time'];?></strong>
                        </td>
                        <td nowrap align="left"><?php echo $this->map['items']['current']['note'];?></td>
                		<td align="left" nowrap>
                        <?php if(User::can_view(false,ANY_CATEGORY)){?>
                            <a href="<?php echo URL::build_current(array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>">
                                <img src="packages/core/skins/default/images/buttons/select.jpg" alt="Chi tiet"/>
                            </a>
                        <?php }?>
                        </td>
                		<td align="left" nowrap>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>">
                                <img src="packages/core/skins/default/images/buttons/edit.gif" alt="Sua"/>
                            </a>
                        <?php }?>
                        </td>
                		<td align="left" nowrap>
                        <?php if(  User::can_delete(false,ANY_CATEGORY)){?>
                            <a onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false}" href="<?php echo URL::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>">
                                <img src="packages/core/skins/default/images/buttons/delete.gif" alt="Xoa"/>
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
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',true,'#FFFFEC','white');"><?php echo Portal::language('select_all');?></a>&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',false,'#FFFFEC','white');"><?php echo Portal::language('select_none');?></a>
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListNoteForm,'ManageNote',-1,'#FFFFEC','white');"><?php echo Portal::language('select_invert');?></a>
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
