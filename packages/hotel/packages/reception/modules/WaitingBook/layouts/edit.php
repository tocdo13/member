<span style="display:none">
    <span id="booking_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1" /></span>
			<span class="multi-input"><input  name="booking[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:15px;background:#EFEFEF;" /></span>
            <span class="multi-input">
					<input name="booking[#xxxx#][from_date]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="from_date_#xxxx#" />
			</span>
            
            <span class="multi-input">
                <input  name="booking[#xxxx#][to_date]" class="multi-edit-text-input to_date" type="text" id="to_date_#xxxx#"  tabindex="-1" style="width:155px;color: red;" />
            </span>
            
            <span class="multi-input">
                <select id="room_type_#xxxx#" name="booking[#xxxx#][room_type]" style="width: 158px;">
                    [[|room_type_option|]]
                </select>
            </span>
            
            <span class="multi-input">
                <input name="booking[#xxxx#][number_room]" class="multi-edit-text-input" type="text" id="number_room_#xxxx#"  tabindex="-1" style="width:53px;color: red;" />
            </span>
            
            <span class="multi-input">
                <input name="booking[#xxxx#][price_room]" class="multi-edit-text-input" type="text" id="price_room_#xxxx#"  tabindex="-1" style="width:100px;color: red;" />
            </span>
            
            <span class="multi-input">
                <input name="booking[#xxxx#][note]" class="multi-edit-text-input" type="text" id="note_#xxxx#"  tabindex="-1" style="width:155px;color: red;" />
            </span>
            
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:50px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'booking','#xxxx#',''); }" style="cursor:pointer;"/>
			</span>
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>	
</span>
<form name="EditWaitingBook" method="post" onsubmit="return(validate_form());">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="70%" class="form-title">[[|title|]]</td>
            <td width="30%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.save_close.]]" class="button-medium-save"  /><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="top">
            	<td>
                	<fieldset>
                    <legend class="title">[[.general_info.]]</legend>
                	<table border="0" cellspacing="0" cellpadding="2">
                        
                        <tr>
                            <td class="label">[[.person_contact.]](*)</td>
                            <?php if($_REQUEST['cmd']=='edit'){ ?>
                                <td><input name="contact_name" type="text" id="contact_name" value="[[|contact_name|]]" /></td>
                            <?php  }else{ ?>
                                <td><input name="contact_name" type="text" id="contact_name" placeholder="[[.person_contact.]]" /></td>
                            <?php } ?> 
                            
                            <td class="label">[[.phone.]]</td>
                            <td><input name="telephone" type="telephone" id="telephone" <?php if($_REQUEST['cmd']=='edit'){ ?>value="[[|telephone|]]" <?php } ?>/></td>   
                            <td>[[.booking_no.]]</td>
                            <td><input name="code_booking" type="text" id="code_booking" <?php if($_REQUEST['cmd']=='edit'){ ?>value="[[|code_booking|]]" <?php } ?> /></td>
                        </tr>
                        
                       
                       
                        <tr>
                            <td class="label">[[.customer.]]:</td> 
                            <td><input name="customer_name" type="text" id="customer_name" readonly="readonly" style="background: #F3F781;"  onfocus="customerAutocomplete();"<?php if($_REQUEST['cmd']=="edit"){ ?> value="[[|customer_name|]]" <?php  }  ?>/>
                            <input name="customer_id" type="hidden" id="customer_id" <?php if($_REQUEST['cmd']=="edit"){ ?> value="[[|cid|]]" <?php  }  ?>  />
                            <a href="#" onclick="window.open('?page=customer&amp;action=select_customer&site=WB','customer_booking_cf')"> 
                                        <img src="skins/default/images/cmd_Tim.gif" />
                                    </a>
                                    <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:pointer;"/>  
                            </td>
                            
                            
                            <td class="label">[[.payment_method.]]</td>
                            <td><select name="payment_method" id="payment_method"></select></td>
                        </tr>
                        <tr>
                        
                          <td>[[.arrival_date.]](*):</td>
                          <?php if($_REQUEST['cmd']=="edit"){  ?>
                                <td style="padding-left: 4px;"><input style="width: 70px;" name="arrival_date" type="text" id="arrival_date" value="<?php echo Date_time::convert_orc_date_to_date([[=arrival_date=]],'/'); ?>"></td>
                          <?php }else{?>
                                <td  style="padding-left: 4px;"><input style="width: 70px;" name="arrival_date" type="text" id="arrival_date" /></td>
                          <?php } ?>
                             
                          <td>[[.departure_date.]](*):</td>
                          
                           <?php if($_REQUEST['cmd']=="edit"){  ?>
                                <td><input style="width: 70px;" name="departure_date" type="text" id="departure_date" value="<?php echo Date_time::convert_orc_date_to_date([[=departure_date=]],'/'); ?>" /></td>
                          <?php }else{?>
                                <td><input style="width: 70px;" name="departure_date" type="text" id="departure_date" /></td>
                           <?php } ?>
                      </tr>
                      <tr>
                          <td class="label">[[.date_confirm.]](*):</td>
                          <?php if($_REQUEST['cmd']=='edit'){ ?>
                            <td style="padding-left:4px;"><input style="width: 70px;" name="confirm_date"  type="text" id="confirm_date" value="<?php echo Date_time::convert_orc_date_to_date([[=confirm_date=]],'/') ?>" /></td>
                          <?php }else{ ?>
                            <td style="padding-left:4px;"><input style="width: 70px;" name="confirm_date"  type="text" id="confirm_date"/></td>
                          <?php } ?>
                          <td class="label">[[.deposit.]]</td>
                          <td><input name="deposit" type="deposit" id="deposit" <?php if($_REQUEST['cmd']=='edit'){ ?>value="[[|deposit|]]" <?php } ?> /></td>
                          <td class="label">[[.before_date.]]</td>
                          <td><input name="before_date" type="text" id="before_date" <?php if($_REQUEST['cmd']=='edit'){ ?>value="<?php echo  Date_time::convert_orc_date_to_date([[=before_date=]],'/') ?>" <?php } ?> /></td>
                      </tr>
                      
                      <tr>
                          <td class="label">[[.note.]]:</td>
                          <?php if($_REQUEST['cmd']=='edit'){ ?>
                            <td style="padding-left:4px;" colspan="5"><textarea name="note" id="note"  style="width: 644px; height: 50px;">[[|note|]]</textarea></td>
                          <?php }else{ ?>
                            <td style="padding-left:4px;" colspan="5"><textarea name="note" id="note" style="width: 644px; height: 50px;"></textarea></td>
                          <?php } ?>
                      </tr>	
                    </table>
                    <table>
                        <tr>
                            <td style="width: 100px;">[[.information.]]</td>
                            <td><span id="booking_all_elems">
            					<span>
            						<span class="multi-input-header" style="width:16px;">
                                        <input type="checkbox" value="1" onclick="mi_select_all_row('booking',this.checked);" />
            						</span>
            						<span class="multi-input-header" style="width:15px;">[[.ID.]]</span>
            						<span class="multi-input-header" style="width:155px;">[[.from_date.]]</span>
                                    <span class="multi-input-header" style="width:155px;">[[.to_date.]]</span>
                                    <span class="multi-input-header" style="width:155px;">[[.room_type.]]</span>
                                    <span class="multi-input-header" style="width:53px;">[[.number_room.]]</span>
                                    <span class="multi-input-header" style="width:100px;">[[.price_room.]]</span>
                                    <span class="multi-input-header" style="width:155px;">[[.note.]]</span>
            						<span class="multi-input-header" style="width:50px;">[[.Delete.]]</span>
            					</span>
                                <br clear="all" />
                                                                
            				</span>
                            </td>
                            
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td><a href="javascript:void(0);" onclick="mi_add_new_row('booking');show_datepicker(input_count);" class="button-medium-add">[[.Add.]]</a></td>
                        </tr>
                    </table>
                    <div>
            				
            		</div>
            			 <div></div>
                    </fieldset>
                </td>
            </tr>
        </table>
</form>


<script type="text/javascript">
    function Confirm(index)
    {
        //var event_name = $('name_'+index).value;
        return confirm('[[.Are_you_sure_delete_booking.]]');
    }
    
    
    function ConfirmDelete()
    {
        return confirm('[[.Are_you_sure_delete_booking_selected.]]');
    }
    
    mi_init_rows('booking',<?php echo isset($_REQUEST['booking'])?String::array2js($_REQUEST['booking']):'{}';?>);
	
    <?php if(isset($_REQUEST['booking'])){?>
	for(var i=101; i<=input_count; i++)
	{
		show_datepicker(i);
	}
	<?php }?>
    
	function show_datepicker(id)
	{
		jQuery('#from_date_'+id).datepicker();
		jQuery('#to_date_'+id).datepicker();		
	}

	jQuery("#arrival_date").datepicker();
    jQuery("#departure_date").datepicker();
    jQuery("#confirm_date").datepicker();
    jQuery("#before_date").datepicker();
    
    
    
    function customerAutocomplete()
    {
    	jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item) {
             //console.log(item);
             document.getElementById('customer_id').value = item.data[0];
             // console.log(document.getElementById('customer_id').value);
            }
        }) 
    }
  
    function validate_form()
    {
        var vdeparture_date = document.EditWaitingBook.departure_date.value.split('/');
        var varrival_date = document.EditWaitingBook.arrival_date.value.split('/');        
        var noti = 'Ngày đến phải lớn hơn ngày đi';
        var arrival_focus = document.EditWaitingBook.arrival_date;
        
        if(varrival_date[2] > vdeparture_date[2])
        {
            alert(noti);
            arrival_focus.focus();
            return false;
        }
        else
        {
            if(varrival_date[1] > vdeparture_date[1] && varrival_date[2] == vdeparture_date[2])
            {
                alert(noti);
                arrival_focus.focus();
                return false;
            }
            else
            {
               if(varrival_date[0] > vdeparture_date[0] && varrival_date[1]==vdeparture_date[1])
               {
                    alert(noti);
                    arrival_focus.focus();
                    return false;
               } 
            }
        }  
        if(document.EditWaitingBook.contact_name.value =='')
        {
          alert ('plese provide contact name');
          document.getElementById("contact_name").style.border ="1px solid red";
          document.EditWaitingBook.contact_name.focus();
          return false; 
        }
        if(document.EditWaitingBook.customer.value =='')
        {
          alert ('plese provide customer');
          document.getElementById("customer").style.border ="1px solid red";
          document.EditWaitingBook.customer.focus();
          return false; 
        }
        
        if(document.EditWaitingBook.departure_date.value =='')
        {
          alert ('plese provide departure date');
          document.getElementById("departure_date").style.border ="1px solid red";
          document.EditWaitingBook.departure_date.focus();
          return false;  
        }
        
        if(document.EditWaitingBook.arrival_date.value =='')
        {
          alert ('plese provide arrival date');
          document.getElementById("arrival_date").style.border ="1px solid red";
          document.EditWaitingBook.arrival_date.focus();
          return false;  
        }
        
        if(document.EditWaitingBook.number_room.value =='')
        {
          alert ('plese provide number room');
          document.getElementById("number_room").style.border ="1px solid red";
          document.EditWaitingBook.number_room.focus();
          return false;  
        }
        if(document.EditWaitingBook.confirm_date.value =='')
        {
          alert ('plese provide  confirm date');
          document.EditWaitingBook.confirm_date.focus();
          return false;  
        }
            
    }
    
</script>