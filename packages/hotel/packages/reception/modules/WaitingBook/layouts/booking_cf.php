<form name="BookingConfirmWaitingBook" method="POST">
    <table width="100%">
        <tr>
            <td width="85%">&nbsp;</td>
            <td align="right" style="vertical-align: bottom;" >
                <input name="save" type="submit" value="Save" class="button-medium-save" onClick="BookingConfirmWaitingBook.submit();" />
                <a onClick="print();" title="In">
                    <img src="packages/core/skins/default/images/printer.png" height="40" />
                </a>
            </td>
        </tr>
    </table>
    <div id="print" class="booking_cf" style="margin: 0 auto; border: 1px solid #00b2f9; padding: 5px; width:800px;">
    <div class="logo" style="margin: 0 auto; text-align: center;"><img src="<?php echo HOTEL_LOGO; ?>" width="150" /></div><br />
        <div style ="font-size : 26px; text-align: center;">BOOKING CONFIRMATION</div>
    <table style="margin: 0 auto;  padding: 5px;">
       
        <tr>
        	<td>
            	<table>
                	<tr>
                    
                        <td width="75" style = "font-size : 16px;" >
                            Company:
                        </td>
                        <td width="420"><input  name="customer_name"  type="text"  id="customer_name" style="font-family:'Times New Roman', Times, serif; font-size:16px; width:100%; border:none" value="<?php if(isset([[=customer=]])) echo [[=customer_name=]] ?>"/>
                        </td>
                        <td width="95" align="left" style = "font-size : 16px;" >
                            Date: 
							</td>
							<td width="150"><?php echo date("d/m/y"); ?>
                        </td>
                     </tr>
                     <tr style="height: 70px;">
                     	<td width="75"  style = "font-size : 16px; vertical-align: center;">
                    Address:
                    </td>
                    <td ><textarea name="customer_address" id="customer_address" style="font-family:'Times New Roman', Times, serif; font-size:16px; width:100%; margin-top: 20px; "><?php if(isset([[=address=]])) echo [[=address=]] ?></textarea>
                    
                        </td>
                        <td width="95"  style = "font-size : 16px; vertical-align: center;" >
                            Group code:
                            </td>
                            <td width="150" style = "font-size : 16px; vertical-align: center;"> <input name="booking_no" type="text" id="booking_no" style="font-family:'Times New Roman', Times, serif; font-size:16px;" value="<?php if(isset([[=code_booking=]])) echo [[=code_booking=]] ?>"/>
                        </td>
                     </tr>
                     <tr>
                     	<td width="75"  style = "font-size : 16px;" >
                    Tel :
                    </td>
                    <td> <input  name="customer_phone" type="text" id="customer_phone" style="font-family:'Times New Roman', Times, serif; font-size:16px; width:100%" value="<?php if(isset([[=cphone=]])) echo [[=cphone=]] ?>"/>
                   
                        </td>
                        <td width="95"  style = "font-size : 16px;" >
                           
                                Fax:
                                </td>
                                <td width="150"> <input name="customer_fax" type="text" id="customer_fax" style="font-family:'Times New Roman', Times, serif; font-size:16px;" value="<?php if(isset([[=cfax=]])) echo [[=cfax=]] ?>"/>
                               
                        </td>
                     </tr>  
                     
                     <tr>
                	<td width="75" style = "font-size : 16px;" >
                        Dear:
                    </td>
                    <td> 
                        <input  name="contact_name" type="text" id="contact_name" style="font-family:'Times New Roman', Times, serif; font-size:16px; width:100%" value="<?php if(isset([[=contact_name=]])) echo [[=contact_name=]] ?>" />
            		</td>
                    <td width="95"  style = "font-size : 16px;" >
                    
                        Telephone:</td>
                    <td width="150"> <input  name="telephone_contact" type="text" id="telephone_contact" style="font-family:'Times New Roman', Times, serif; font-size:16px;" value="<?php if(isset([[=telephone=]])) echo [[=telephone=]] ?>" /></td>
                    
            	</tr> 
            	</table>
            </td>
    </tr>
    <tr>
        <td width="750px" style = "font-size : 16px;" >
            <i>Thank you very much for choosing <?php echo HOTEL_NAME; ?>. We would like to confirm the following.</i> 
        </td>
    </tr>
    
    <tr>
        <td>
            <table width="750" >
                <tr>
                    <td align="center"  style = "font-size : 16px; font-family:'Times New Roman', Times, serif;" >
                        <input type="radio" name="confirm_feedback" id="bcf_status_1" value="RESERVATION" <?php if([[=confirm_feedback=]]=='RESERVATION') echo 'checked="checked"'; ?>  /> Reservation &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="confirm_feedback" id="bcf_status_2" value="AMENDMENT" <?php if([[=confirm_feedback=]]=='AMENDMENT') echo 'checked="checked"'; ?> /> Amendment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="confirm_feedback" id="bcf_status_3" value="CANCELL" <?php if([[=confirm_feedback=]]=='CANCELL') echo 'checked="checked"'; ?> /> Cancellation
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <tr>
        <td>
             <br />
            <strong>Booking Content:</strong>
            <table cellpadding="0" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound booking_content">
                <tr>
                    <td>[[.stt.]]</td>
                    <td>[[.from_date.]]</td>
                    <td>[[.to_date.]]</td>
                    <td>[[.number_room.]]</td>
                    <td>[[.room_name.]]</td>
                    <td>[[.price_room.]]</td>
                    <td>[[.note.]]</td>
                </tr>
            <?php $i=1 ?>    
            <!--LIST:waiting_information-->
                <tr>
                    <td><?php echo $i++ ?></td>
                    <td><?php echo Date_time::convert_orc_date_to_date([[=waiting_information.from_date=]],'/') ?> </td>
                    <td><?php echo Date_time::convert_orc_date_to_date([[=waiting_information.to_date=]],'/') ?> </td>
                    <td>[[|waiting_information.number_room|]]</td>
                    <td>[[|waiting_information.room_name|]]</td>
                    <td><?php echo System::display_number([[=waiting_information.price_room=]]) ?></td>
                    <td>[[|waiting_information.note|]]</td>
                </tr>
            <!--/LIST:waiting_information-->
            </table>
            
            
        </td>
    </tr>
    
    <tr>
          	<td>- The above rates include tax, services charge and breakfast.<br />
          	- In case of group of Guests, please attach the rooming list.
            </td>
          </tr>
      <tr>
      	<td>
        	<table>
                  <tr>
                    <td width="200px" style="padding: 0; font-size:16px;" ><strong>METHOD OF PAYMENT:&nbsp; </strong></td>
                     <td><select name="payment_method" id="payment_method"></select></td>
                     <td width="400px">&nbsp;
                     </td>
                  </tr>
                  <tr>
                   	<td width="650" colspan="3">Bank account: Vietinbank Coseco Dalat - 102010001578959 - Vietinbank Lam Dong
                    </td>
                  </tr>
                  
             </table>
           </td>
         </tr>      
      <tr>
            <td>
                <table width="750" >
                	<tr>
            			<td width="80" valign="top" style="padding: 0; font-family:'Times New Roman', Times, serif; font-size:16px;" ><strong>Deposit:</strong></td>
                        <td width="200" align="left">
                            <input type="text" name="need_deposit" id="need_deposit" style="font-family:'Times New Roman', Times, serif; font-size:16px; width:80%;" value ="<?php if(isset([[=deposit=]])) echo [[=deposit=]] ?>"/>VND
                        </td>
                        <td width="100px">&nbsp;</td><td width="400" style="font-family:'Times New Roman', Times, serif; font-size:16px;">before:&nbsp;
                            <input size="10" type="text" name="before_date" id="before_date" style="font-family:'Times New Roman', Times, serif; font-size: 16px;" value ="<?php if(isset([[=before_date=]])) echo Date_time::convert_orc_date_to_date([[=before_date=]],'/') ?>" />
                        </td>
                    </tr>
                 </table> 
            </td>
      </tr>
      
      <tr>
            <td>
                <br />
                <strong>Note:</strong>
                <br />
                <textarea name="note" id="note" style="width:787px;height:84px; font-family:'Times New Roman', Times, serif; font-size:16px;"><?php if(isset([[=note=]])) echo [[=note=]] ?></textarea>
            </td>
            
       </tr>
       
       <tr>
          	<td>
            	<table width="750px">
                      <tr>
                        <td width="200" valign="top" align="center"><br /><br />Best regards<br />
                          <br /><br /><br /><br />                       
                          </td>
                        <td width="200" valign="top" align="center"><br /><br />Confirmed by<br />
                          <br /><br /><br /><br />
                          </td>
                      </tr>
                      <tr>
                        <td width="200" valign="top" align="center">
                            <?php echo [[=pname=]]  ?>
                            <br />
                            <?php if(isset([[=pphone=]])) echo [[=pphone=]]  ?>
                        </td>
                        <td width="200" valign="top" align="center">&nbsp;
                          </td>
                      </tr>
                 </table>
             </td>
           </tr>
           <tr>
           		<td width="750px" style="font-family:'Times New Roman', Times, serif; font-size:16px;"><br /><p align="center"><?php echo HOTEL_ADDRESS;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tel:&nbsp;<strong><?php echo HOTEL_PHONE;?></strong>&nbsp;&nbsp;&nbsp;&nbsp; Fax:&nbsp;<strong><?php echo HOTEL_FAX;?></strong>
        <br />
        Email: <?php echo HOTEL_EMAIL;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Website:&nbsp;<strong><?php echo HOTEL_WEBSITE;?></strong></p>
                </td>
           </tr>
    </table>
    </div>
    
</form>
<style type="text/css">
td
{
    font-size : 14px;
    padding: 4px;
}

</style>
<script>
   
   
    function print()
    {
        var inputs = jQuery('table input:radio:checked,table input:checkbox:checked');
        
        for (var i=0;i<inputs.length;i++)
        { 
            var typ=document.createAttribute("checked");
            typ.nodeValue="true";
            inputs[i].attributes.setNamedItem(typ);
        }
        var inputs = jQuery('table input:text');
        inputs.css('border','none');
        for (var i=0;i<inputs.length;i++)
        { 
            var typ=document.createAttribute("value");
            if(inputs[i].attributes.id)
            {
                typ.value=jQuery('#'+inputs[i].attributes.id.value).val();
                inputs[i].attributes.setNamedItem(typ);
            }
        } 
        jQuery('#payment_method').css('border','0 none');
        jQuery('#content_booking').css('border','0 none');
        jQuery('#customer_address').css('border','0 none');
        jQuery('#note').css('border','0 none');
        
        
        
        var select_box = jQuery('select');
        
       
        for (var i=0;i<select_box.length;i++)
        {
            var typ=document.createAttribute("value");
            if(select_box[i].attributes.id)
            {
                typ.value=jQuery('#'+select_box[i].attributes.id.value).val();
                
                jQuery( select_box[i]+ "option:selected" ).each(function() {
                  str = jQuery( this ).text() + " ";
                });
                var text = "<span>"+str+"</span>";
                
               jQuery(select_box[i]).after(text);
               
            }  
        } 
        select_box.css('display','none');
        BookingConfirmWaitingBook.submit();
        printWebPart('print');
        
        
    }
    jQuery("#before_date").datepicker();
</script>