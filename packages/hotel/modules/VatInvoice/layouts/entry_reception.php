<?php System::set_page_title(HOTEL_NAME);?>
<link rel="stylesheet" href="packages/core/skins/default/css/jquery/datepicker.css" />
<script type="text/javascript" src="packages/core/includes/js/jquery/datepicker.js"></script>
<div class="customer_type-bound">
<form name="VatInvoiceEntryForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="80%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.print_invoice_vat.]]" class="button-medium-add" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'view','r_id','r_r_id'));?>'"/><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){ echo Form::$current->error_messages(); }?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="top">
            	<td>
                	<fieldset>
                    <legend class="title">[[.confirm_info.]]</legend>
                	<table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="label">[[.reservation_id.]]:</td>
                            <td><input name="reservation_id" type="text" id="reservation_id" readonly="readonly" class="const"/></td>
                            <td class="label">[[.reservation_room_id.]]:</td>
                            <td><input name="reservation_room_id" type="text" id="reservation_room_id" readonly="readonly" class="const"/></td>
                        </tr>
                        <tr>
                            <td class="label">[[.type.]]:</td>
                            <td><input name="type" type="text" id="type" readonly="readonly" class="const" /></td>
                            <td class="label">[[.num_people.]]:</td>
                            <td><input name="num_people" type="text" id="num_people" /></td>
                        </tr>
                        <tr>
                            <td class="label">[[.customer_name.]]:</td>
                            <td><input name="customer_name" type="text" id="customer_name"/></td>
                            <td class="label">[[.guest_name.]]:</td>
                            <td><input name="guest_name" type="text" id="guest_name"/></td>
                        </tr>
                        <tr>
                            <td class="label">[[.tax_code.]]:</td>
                            <td colspan="3"><input name="tax_code" type="text" id="tax_code"/></td>
                        </tr>
                        <tr>
                            <td class="label">[[.customer_address.]]:</td>
                            <td colspan="3">
                                <textarea name="customer_address" id="customer_address" style="width: 200px; height: 80px; overflow: auto; font-family:'Arial' "></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">[[.arrival_time.]]:</td>
                            <td><input name="arrival_time" type="text" id="arrival_time" readonly="readonly" class="const" /></td>
                            <td class="label">[[.departure_time.]]:</td>
                            <td><input name="departure_time" type="text" id="departure_time" readonly="readonly" class="const" /></td>
                        </tr>
                        <tr>
                            <td class="label">[[.room_name.]]:</td>
                            <td><input name="room_name" type="text" id="room_name" readonly="readonly" class="const" /></td>
                            <td class="label">[[.room_price.]]:</td>
                            <td><input name="room_price" type="text" id="room_price" readonly="readonly" class="const" />(VND)</td>
                        </tr>
                        <tr>
                            <td class="label">[[.service_rate.]]:</td>
                            <td><input name="service_rate" type="text" id="service_rate" readonly="readonly" class="const" />(%)</td>
                            <td class="label">[[.line_per_page.]]:</td>
                            <td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" maxlength="2" /></td>
                        </tr>
                        <tr>
                            <td class="label">[[.time_print.]]:</td>
                            <td><input name="time" type="text" id="time" value="[[|day|]]/[[|month|]]/[[|year|]]  [[|time|]]" readonly="readonly" class="const" /></td>
                            <td><input name="date_print" type="hidden" id="date_print" /></td>
                            <td><input name="time_print" type="hidden" id="time_print" /></td>
                        </tr>
                        <tr>
                            <td class="label">[[.note_print.]]:</td>
                            <td><input name="note_print" type="text" id="note_print" value="[[|note_print|]]"  readonly="readonly" class="const" style="color: red;" /></td>
                        </tr>
                    </table>
                    </fieldset>
                </td> 
            </tr>
        </table>
	</div>
</form>	
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>

<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#arrival_time').datepicker();
	jQuery('#departure_time').datepicker();
	jQuery("#arrival_time").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
	jQuery("#departure_time").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
});
</script>
<style>
.const{background-color: #CCC;}
input[type="text"]{width: 210px;}
textarea{width: 508px !important;}
</style>