<span style="display:none">
    <span id="email_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1" /></span>
			<span class="multi-input"><input  name="email[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px;background:#EFEFEF;" /></span>
            <span class="multi-input">
					<input name="email[#xxxx#][name]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="name_#xxxx#" />
			</span>
            
            <span class="multi-input">
                <input  name="email[#xxxx#][code]" class="multi-edit-text-input code" type="text" <?php 
				if((!User::can_edit(false,ANY_CATEGORY)))
				{?> disabled="disabled" 
				<?php
				}
				?> id="code_#xxxx#"  tabindex="-1" style="width:55px;color: red;"
                onclick="" />
            </span>
            
            <span class="multi-input">
                    <input name="choose_customer[#xxxx#][id]" class="multi-edit-text-input customer" type="button"  id="#xxxx#" style="height: 21px;" tabindex="-1" value="<?php echo Portal::language('customer');?>"
                        onclick="choose_customer(this);"
                      />
			</span>
			<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'email','#xxxx#',''); }" style="cursor:pointer;"/>
			</span>
			
				<?php
				}
				?>
		</div>
        <br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditEmailForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
    	<tr height="40">
    		<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-email w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('Group_email');?></td>
    		<td width="30%">
                <?php 
				if((User::can_edit(false,ANY_CATEGORY)))
				{?>
                <input type="submit" value="<?php echo Portal::language('Save');?>" class="w3-btn w3-blue" style="text-transform: uppercase; margin-right: 5px;" />
                
				<?php
				}
				?>
            
                <?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
                <a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('email');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?>
                </a>
                
				<?php
				}
				?>
            </td>
    	</tr>
    </table>
    <table cellspacing="0" width="100%">
    	<tr>
            <td style="padding-bottom:30px">
        		<table border="0">
        		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
            		<tr valign="top">
            			<td style="">
            			<div>
            				<span id="email_all_elems">
            					<span>
            						<span class="multi-input-header" style="width:13px;">
                                        <input type="checkbox" value="1" onclick="mi_select_all_row('email',this.checked);" />
            						</span>
            						<span class="multi-input-header" style="width:35px;"><?php echo Portal::language('ID');?></span>
            						<span class="multi-input-header" style="width:155px;"><?php echo Portal::language('event_name');?></span>
                                    <span class="multi-input-header code" style="width:55px; color:red"><?php echo Portal::language('code');?></span>
                                    <span class="multi-input-header customer" style="width:99px;"><?php echo Portal::language('customer');?></span>
            						<span class="multi-input-header" style="width:70px; text-align: center;"><?php echo Portal::language('Delete');?></span>
            					</span>
                                <br clear="all" />
            				</span>
            			</div>
            			 <div><a href="javascript:void(0);" onclick="mi_add_new_row('email');$('name_'+input_count).focus();jQuery('#'+i).attr('disabled','disabled').css('opacity','0.5');" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;"><?php echo Portal::language('Add');?></a></div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>" />
    <input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>" />
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<p>
       <span style="color: red;">(*)Ghi chú:</span> Sinh nhật có mã code là: <span style="color: red;">SN</span>, Ngày thành lập có mã code là: <span style="color: red;">NTL</span>, Gửi Sinh nhật cho khách nước ngoài có mã là: <span style="color: red;">BD</span>
</p>
<script>
    
    <?php 	if(isset($_REQUEST['email']))
    			echo 'var star = '.String::array2js($_REQUEST['email']).';';
    		else
    			echo 'var star = [];';
    ?>
    mi_init_rows('email',star);
    function Confirm(index)
    {
        var event_name = $('name_'+index).value;
        return confirm('<?php echo Portal::language('Are_you_sure_delete_bank');?> '+ event_name+' And Email Send?');
    }
    
    
    function ConfirmDelete()
    {
        return confirm('<?php echo Portal::language('Are_you_sure_delete_bank_selected');?>'+'And Email Send');
    }
    for(var i=100;i<=input_count;i++)
    {
        var code = jQuery('input#code_'+i).val();
        //console.log(code);
        if(code =='SN' || code=='NTL' || code=='BD')
        {
            jQuery('#code_'+i).attr('readonly','readonly').css('background-color','#cccccc');
            //jQuery('#code_'+i).css('background-color','#cccccc');
            
            jQuery('#'+i).attr('disabled','disabled').css('opacity','0.5');
        }  
    }
    function choose_customer(obj)
    {
        var x=obj.id;
        var id=jQuery("#id_"+x).val();
        var code = jQuery('input#code_'+x).val();
        if(code =='SN' || code=='NTL' || code=='BD')
        {
            alert('Không được chọn nhóm mặc định');
            jQuery('input#code_'+x).css('border','1px solid red');
            return false;
        }
        else
        {
            window.open("?page=email_group_event&cmd=list_customer&id="+id);
        }
        
    }
    
    
</script>