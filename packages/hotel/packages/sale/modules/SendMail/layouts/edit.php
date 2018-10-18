<?php //system::debug($this->map) ?>
<form name="send_mail_list" method="post" enctype="multipart/form-data"><!-- onsubmit="return(validate_form());" -->
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[.title.]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.save.]]" class="button-medium-save"  /><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
    
 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="top">
            <td>
                <fieldset>
                    <legend>[[.general_info.]]</legend>
                    
                    <table>
                        <tr>
                            <td class="label">[[.title.]]:</td>
                            <td><input name="title" type="text" id="title" value="<?php if(Url::get('cmd')=='edit') echo [[=items=]]['title']  ?>" /></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="label">[[.event.]]:</td>
                            <td><select name="group_event" id="group_event" style="max-width: 300px;"></select></td>
                            <td><span class="customer_edit"><input class="customer_edit" type="button" value="[[.edit_customer.]]" onclick="choose_customer();" /></span></td>
                            <td>&nbsp;</td>
                            
                        </tr>
                        
                        <tr>
                            <td style="text-align:left; min-width: 50px;"><span class="date_send">[[.date_send.]]:</span></td>
                            <td style="min-width: 50px;"><span class="date_send"><input name="date_send" type="text" id="date_send" value="<?php if(Url::get('cmd')=='edit') echo Date_time::convert_orc_date_to_date([[=items=]]['date_send'],'/') ?>" /></span></td> 
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="label" id="label_content">[[.content.]]:</td>
                            <td style="padding-left:4px;" colspan="4">
                                <textarea name="content" id="content" class="ckeditor" >
                                    <?php if(Url::get('cmd')=='edit')
                                          {
                                                $file =fopen([[=items=]]['content'],'r+');
                                                while(!feof($file))
                                                {
                                                    echo fgets($file);
                                                }
                                                fclose($file);
                                           }
                                     ?>
                                </textarea>
                            </td>
                        </tr>
                        
                        
                        <tr>
                            <td class="label">[[.upload_image.]]:</td>
                            <td><input name="file" type="file" id="file"  /></td>
                            <td><?php if(Url::get('cmd')=='edit'){ ?><img src="<?php echo [[=items=]]['images'] ?>" alt="Images" width="150px;" height="150px;" /><?php } ?></td>
                            <td></td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
   
</form>
    
<script type="text/javascript">   
    var check_edit_group ='<?php 
                                if(Url::get('cmd')=='edit')
                                    echo [[=items=]]['code'];
                                else 
                                    echo 'add';
                            ?>';                        
    if(check_edit_group=='SN' || check_edit_group=='NTL' || check_edit_group=='BD' || check_edit_group=='add')
    {
       jQuery(".date_send").css('display','none'); 
       jQuery(".customer_edit").css('display','none');
    }

    <?php
        if(Url::get('cmd')=='edit')
        {
    ?>
            jQuery('select option:not(:selected)').attr('disabled',true);    
    <?php
        }
    ?>
    
    function choose_customer()
    {
       var group_event = jQuery('#group_event').val();
       window.open("?page=send_mail&cmd=list_customer&group_event="+group_event+"&send_mail_id=<?php echo Url::get('id'); ?>","group_mail"); 
    }

    function validate_form()
    {
        if(document.send_mail_list.title.value =='')
        {
          alert ('[[.error_title.]]');
          document.getElementById("title").style.border ="1px solid red";
          document.send_mail_list.title.focus();
          return false; 
        }
        if(document.send_mail_list.date_send.value =='')
        {
          alert ('[[.error_date_send.]]');
          document.getElementById("date_send").style.border ="1px solid red";
          document.send_mail_list.date_send.focus();
          return false; 
        }
        //console.log(document.send_mail_list.content.value);return false;
        if(document.send_mail_list.content.value =='')
        {  
          alert ('[[.error_content.]]');
          document.getElementById("label_content").style.color ="red";
          document.send_mail_list.content.focus();
          return false; 
        }   
    }
    CKEDITOR.replace('content');  
    jQuery("#date_send").datepicker();
</script> 