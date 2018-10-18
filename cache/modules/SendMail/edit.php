<?php //system::debug($this->map) ?>
<form name="send_mail_list" method="post" enctype="multipart/form-data"><!-- onsubmit="return(validate_form());" -->
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title"><?php echo Portal::language('title');?></td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="<?php echo Portal::language('save');?>" class="button-medium-save"  /><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-back"><?php echo Portal::language('back');?></a><?php }?>
            </td>
        </tr>
    </table>
    
 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="top">
            <td>
                <fieldset>
                    <legend><?php echo Portal::language('general_info');?></legend>
                    
                    <table>
                        <tr>
                            <td class="label"><?php echo Portal::language('title');?>:</td>
                            <td><input name="title" type="text" id="title" value="<?php if(Url::get('cmd')=='edit') echo $this->map['items']['title']  ?>" /></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo Portal::language('event');?>:</td>
                            <td><select  name="group_event" id="group_event" style="max-width: 300px;"><?php
					if(isset($this->map['group_event_list']))
					{
						foreach($this->map['group_event_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('group_event',isset($this->map['group_event'])?$this->map['group_event']:''))
                    echo "<script>$('group_event').value = \"".addslashes(URL::get('group_event',isset($this->map['group_event'])?$this->map['group_event']:''))."\";</script>";
                    ?>
	</select></td>
                            <td><span class="customer_edit"><input class="customer_edit" type="button" value="<?php echo Portal::language('edit_customer');?>" onclick="choose_customer();" /></span></td>
                            <td>&nbsp;</td>
                            
                        </tr>
                        
                        <tr>
                            <td style="text-align:left; min-width: 50px;"><span class="date_send"><?php echo Portal::language('date_send');?>:</span></td>
                            <td style="min-width: 50px;"><span class="date_send"><input name="date_send" type="text" id="date_send" value="<?php if(Url::get('cmd')=='edit') echo Date_time::convert_orc_date_to_date($this->map['items']['date_send'],'/') ?>" /></span></td> 
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="label" id="label_content"><?php echo Portal::language('content');?>:</td>
                            <td style="padding-left:4px;" colspan="4">
                                <textarea name="content" id="content" class="ckeditor" >
                                    <?php if(Url::get('cmd')=='edit')
                                          {
                                                $file =fopen($this->map['items']['content'],'r+');
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
                            <td class="label"><?php echo Portal::language('upload_image');?>:</td>
                            <td><input  name="file" id="file"  / type ="file" value="<?php echo String::html_normalize(URL::get('file'));?>"></td>
                            <td><?php if(Url::get('cmd')=='edit'){ ?><img src="<?php echo $this->map['items']['images'] ?>" alt="Images" width="150px;" height="150px;" /><?php } ?></td>
                            <td></td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
   
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
    
<script type="text/javascript">   
    var check_edit_group ='<?php 
                                if(Url::get('cmd')=='edit')
                                    echo $this->map['items']['code'];
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
          alert ('<?php echo Portal::language('error_title');?>');
          document.getElementById("title").style.border ="1px solid red";
          document.send_mail_list.title.focus();
          return false; 
        }
        if(document.send_mail_list.date_send.value =='')
        {
          alert ('<?php echo Portal::language('error_date_send');?>');
          document.getElementById("date_send").style.border ="1px solid red";
          document.send_mail_list.date_send.focus();
          return false; 
        }
        //console.log(document.send_mail_list.content.value);return false;
        if(document.send_mail_list.content.value =='')
        {  
          alert ('<?php echo Portal::language('error_content');?>');
          document.getElementById("label_content").style.color ="red";
          document.send_mail_list.content.focus();
          return false; 
        }   
    }
    CKEDITOR.replace('content');  
    jQuery("#date_send").datepicker();
</script> 