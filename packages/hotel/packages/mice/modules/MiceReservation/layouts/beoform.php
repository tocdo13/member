<style>
    .simple-layout-middle{width:100%;}
    .simple-layout-content {
        background: #171717;
         padding: 0px; 
         min-height: 100%;
         margin: 0px;
         border: none;
    }
</style>
<form name="BeoFormMiceReservationForm" method="POST">
    <input name="act" type="text" id="act" style="display: none;" />
    <div id="BEO" style="width: 800px; height: auto; background: #FFFFFF; margin: 50px auto 120px auto;">
        <table style="width: 100%;" cellpadding="10" cellspacing="0">
            <tr style="border-bottom: 1px solid #EEEEEE;">
                <td><img src="<?php echo HOTEL_LOGO; ?>" style="height: 60px; width: auto;" /></td>
                <td style="text-align: right;">
                    
                </td>
            </tr>
        </table>
        <table style="width: 100%;" cellpadding="10" cellspacing="0">
            <tr style="background: #1f6f80; height: 35px;">
                <td style="text-transform: uppercase; font-size: 17px; font-weight: bold;">[[.banquet_order_event.]]</td>
                <td style="width: 50px; font-weight: bold;">[[.event_number.]]: </td>
                <td style="width: 150px; font-weight: bold;"><input value="[[|code_mice|]]" name="code_mice" type="text" id="code_name" class="datainput" readonly="" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555; background: #1f6f80; color: #FFFFFF;" /><label id="lbl_code_mice" class="datalbl" style="display: none;">[[|code_mice|]]</label></td>
            </tr>
        </table>
        <table style="width: 100%;" cellpadding="10" cellspacing="0" border="1" bordercolor="#EEEEEE">
            <tr style=" height: 30px;">
                <td style="width: 150px; font-weight: bold;">[[.company.]]:</td>
                <td><input value="[[|customer_name|]]" name="customer_name" type="text" id="customer_name" class="datainput" readonly="" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555; width: 450px;" /><label id="lbl_customer_name" class="datalbl" style="display: none;">[[|customer_name|]]</label></td>
                <td style="width: 150px; font-weight: bold;">[[.contact.]]:</td>
                <td style="width: 150px; font-weight: bold;"><input value="[[|contact_name|]]" name="contact_name" type="text" id="contact_name" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555;" /><label id="lbl_contact_name" class="datalbl" style="display: none;">[[|contact_name|]]</label></td>
            </tr>
            <tr style=" height: 30px;">
                <td style="width: 150px; font-weight: bold;" rowspan="2">[[.sales.]]:</td>
                <td><input value="[[|sales|]]" name="sales" type="text" id="sales" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555; width: 450px;" /><label id="lbl_sales" class="datalbl" style="display: none;">[[|sales|]]</label></td>
                <td style="width: 150px; font-weight: bold;">[[.telephone_number.]]:</td>
                <td style="width: 150px; font-weight: bold;"><input value="[[|contact_phone|]]" name="contact_phone" type="number" id="contact_phone" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555;" /><label id="lbl_contact_phone" class="datalbl" style="display: none;">[[|contact_phone|]]</label></td>
            </tr>
            <tr style="height: 30px;">
                <td colspan="3"><input value="[[|user_full_name|]]" name="user_full_name" type="text" id="user_full_name" class="datainput" readonly="" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555; width: 450px;" /><label id="lbl_user_full_name" class="datalbl" style="display: none;">[[|user_full_name|]]</label></td>
            </tr>
            <tr style=" height: 30px;">
                <td style="width: 150px; font-weight: bold;">[[.event_name.]]:</td>
                <td colspan="3"><input value="[[|event_name|]]" name="event_name" type="text" id="event_name" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555; width: 450px;" /><label style="lbl_event_name" class="datalbl" id="display: none;">[[|event_name|]]</label></td>
            </tr>
            <tr style=" height: 30px;">
                <td style="width: 150px; font-weight: bold;">[[.confirm_number_of_guest.]]:</td>
                <td><input value="[[|confirmed_number_of_guest|]]" name="confirmed_number_of_guest" type="number" id="confirmed_number_of_guest" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555; width: 450px;" /><label id="lbl_confirmed_number_of_guest" class="datalbl" style="display: none;">[[|confirmed_number_of_guest|]]</label></td>
                <td style="width: 150px; font-weight: bold;">[[.expect_number_of_guest.]]:</td>
                <td style="width: 150px; font-weight: bold;"><input value="[[|expect_number_of_guest|]]" name="expect_number_of_guest" type="number" id="expect_number_of_guest" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555;" /><label id="lbl_expect_number_of_guest" class="datalbl" style="display: none;">[[|expect_number_of_guest|]]</label></td>
            </tr>
            <tr style=" height: 30px;">
                <td style="width: 150px; font-weight: bold;">[[.event_date.]]:</td>
                <td><input value="[[|event_date|]]" name="event_date" type="text" id="event_date" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555; width: 450px;" /><label id="lbl_event_date" class="datalbl" style="display: none;">[[|event_date|]]</label></td>
                <td style="width: 150px; font-weight: bold;">[[.time.]]:</td>
                <td style="width: 150px; font-weight: bold;"><input value="[[|time|]]" name="time" type="text" id="time" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555;" /><label id="lbl_time" class="datalbl" style="display: none;">[[|time|]]</label></td>
            </tr>
        </table>
        <table style="width: 100%;" cellpadding="10" cellspacing="0" border="1" bordercolor="#EEEEEE">
            <tr style="text-align: center;">
                <th rowspan="<?php echo sizeof([[=venues=]])+4 ?>" style="background: #ffe796; width: 150px;"></th>
                <th style="background: #1f6f80;">[[.venue.]]</th>
                <th style="background: #1f6f80;">[[.service.]]</th>
                <th style="background: #1f6f80;">[[.time.]]</th>
            </tr>
            <!--LIST:venues-->
            <tr style="text-align: center;">
                <td>[[|venues.name|]]<input name="venues[[[|venues.id|]]][id]" type="text" style="display: none;" value="[[|venues.id|]]" /><input name="venues[[[|venues.id|]]][name]" type="text" style="display: none;" value="[[|venues.name|]]" /></td>
                <td><input value="[[|venues.service|]]" name="venues[[[|venues.id|]]][service]" type="text" id="venues_service_[[|venues.id|]]" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border-bottom: 1px solid #555555; text-align: center;" /><label id="lbl_venues_service_[[|venues.id|]]" class="datalbl" style="display: none;">[[|venues.service|]]</label></td>
                <td>[[|venues.time|]]<input name="venues[[[|venues.id|]]][time]" type="text" style="display: none;" value="[[|venues.time|]]" /></td>
            </tr>
            <!--/LIST:venues-->
            <tr>
                <td style="width: 150px; font-weight: bold;">[[.deposit.]]:</td>
                <td colspan="2">
                    <textarea id="deposit_note" name="deposit_note" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value.replace(/\r?\n/g, '<br />'));" style="border: none; border: 1px solid #555555; width: 100%; min-height: 80px;" >[[|deposit_note|]]</textarea>
                    <label id="lbl_deposit_note" class="datalbl" style="display: none;">[[|deposit_note|]]</label>
                    <script>jQuery('#lbl_deposit_note').html(jQuery('#deposit_note').val().replace(/\r?\n/g, '<br />'));</script>
                </td>
            </tr>
            <tr>
                <td style="width: 150px; font-weight: bold;">[[.payment_method.]]:</td>
                <td colspan="2">
                    <textarea id="payment_method_note" name="payment_method_note" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value.replace(/\r?\n/g, '<br />'));" style="border: none; border: 1px solid #555555; width: 100%; min-height: 80px;" >[[|payment_method_note|]]</textarea>
                    <label id="lbl_payment_method_note" class="datalbl" style="display: none;">[[|payment_method_note|]]</label>
                    <script>jQuery('#lbl_payment_method_note').html(jQuery('#payment_method_note').val().replace(/\r?\n/g, '<br />'));</script>
                </td>
            </tr>
            <tr>
                <td style="width: 150px; font-weight: bold;">[[.note.]]:</td>
                <td colspan="2">
                    <textarea id="note" name="note" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value.replace(/\r?\n/g, '<br />'));" style="border: none; border: 1px solid #555555; width: 100%; min-height: 80px;" >[[|note|]]</textarea>
                    <label id="lbl_note" class="datalbl" style="display: none;">[[|note|]]</label>
                    <script>jQuery('#lbl_note').html(jQuery('#note').val().replace(/\r?\n/g, '<br />'));</script>
                </td>
            </tr>
        </table>
        <table style="width: 100%;" cellpadding="10" cellspacing="0" border="1" bordercolor="#EEEEEE">
            <tr style="background: #1f6f80;">
                <th colspan="8">[[.quotation.]]</th>
            </tr>
            <tr style="background: #ffca96;">
                <th>[[.stt.]]</th>
                <th>[[.items.]]</th>
                <th>[[.qtt.]]</th>
                <th>[[.unit.]]</th>
                <th style="text-align: right;">[[.unit_price.]] <br /> (VNDnett)</th>
                <th>[[.discount.]]</th>
                <th style="text-align: right;">[[.amount.]] <br /> (VNDnett)</th>
                <th style="text-align: right;">[[.sub_total.]] <br /> (VNDnett)</th>
            </tr>
            <!--LIST:items-->
            <tr>
                <td colspan="8" style="text-transform: uppercase;"><b>[[|items.name|]]</b></td>
            </tr>
                <?php if([[=items.id=]]!='RES'){ ?>
                <!--LIST:items.child-->
                <tr>
                    <td style="text-align: center;">[[|items.child.stt|]]</td>
                    <td>[[|items.child.name|]]</td>
                    <td style="text-align: center;">[[|items.child.quantity|]]</td>
                    <td style="text-align: center;">[[|items.child.unit|]]</td>
                    <td style="text-align: right;"><?php echo System::display_number(round([[=items.child.price=]],0)); ?></td>
                    <td style="text-align: center;">[[|items.child.discount|]]%</td>
                    <td style="text-align: right;"><?php echo System::display_number(round([[=items.child.amount=]],0)); ?></td>
                    <td style="text-align: right;"><?php echo System::display_number(round([[=items.child.total=]],0)); ?></td>
                </tr>
                <!--/LIST:items.child-->
                <?php }else{ ?>
                <!--LIST:items.child-->
                <tr>
                    <td colspan="8">[[|items.child.name|]]</td>
                </tr>
                    <!--LIST:items.child.child-->
                    <tr>
                        <td style="text-align: center;">[[|items.child.child.stt|]]</td>
                        <td>[[|items.child.child.name|]]</td>
                        <td style="text-align: center;">[[|items.child.child.quantity|]]</td>
                        <td style="text-align: center;">[[|items.child.child.unit|]]</td>
                        <td style="text-align: right;"><?php echo System::display_number(round([[=items.child.child.price=]],0)); ?></td>
                        <td style="text-align: center;">[[|items.child.child.discount|]]%</td>
                        <td style="text-align: right;"><?php echo System::display_number(round([[=items.child.child.amount=]],0)); ?></td>
                        <td style="text-align: right;"><?php echo System::display_number(round([[=items.child.child.total=]],0)); ?></td>
                    </tr>
                    <!--/LIST:items.child.child-->
                <!--/LIST:items.child-->
                <?php } ?>
            <!--/LIST:items-->
            <tr>
                <td style="text-align: center; font-weight: bold;" colspan="7">[[.total.]]</td>
                <td style="text-align: right; font-weight: bold;"><?php echo System::display_number(round([[=mice_total=]],0)); ?><input name="mice_total" type="text" value="<?php echo System::display_number(round([[=mice_total=]],0)); ?>" style="display: none;" /></td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;" colspan="7">[[.service_rate.]]</td>
                <td style="text-align: right; font-weight: bold;"><?php echo System::display_number(round([[=mice_service=]],0)); ?><input name="mice_service" type="text" value="<?php echo System::display_number(round([[=mice_service=]],0)); ?>" style="display: none;" /></td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;" colspan="7">[[.tax_rate.]]</td>
                <td style="text-align: right; font-weight: bold;"><?php echo System::display_number(round([[=mice_tax=]],0)); ?><input name="mice_tax" type="text" value="<?php echo System::display_number(round([[=mice_tax=]],0)); ?>" style="display: none;" /></td>
            </tr>
            <!--
            <tr style="background: #EEEEEE;">
                <td style="text-align: center;" colspan="7">[[.extra_vat.]]</td>
                <td style="text-align: right;"><?php //echo System::display_number([[=extra_vat=]]); ?><input name="mice_grand_total" type="text" value="[[|extra_vat|]]" style="display: none;" /></td>
            </tr>
            -->
            <tr style="background: #EEEEEE;">
                <td style="text-align: center; font-weight: bold;" colspan="7">[[.grand_total.]]</td>
                <td style="text-align: right; font-weight: bold;"><?php echo System::display_number(round([[=mice_grand_total=]],0)); ?><input name="mice_grand_total" type="text" value="<?php echo System::display_number(round([[=mice_grand_total=]],0)); ?>" style="display: none;" /></td>
            </tr>
        </table>
        <table id="form_setup" style="width: 100%;" cellpadding="10" cellspacing="0" border="1" bordercolor="#EEEEEE">
            <tr style="background: #1f6f80;">
                <th>[[.setup.]]</th>
            </tr>
            <?php $countsetup = 100; ?>
            <!--LIST:setup-->
            <?php $countsetup++; ?>
            <tr class="setup_beo_<?php echo $countsetup; ?>" style="background: #ffca96;">
                <th style="position: relative;">
                    <input name="setup[<?php echo $countsetup; ?>][id]" type="text" value="[[|setup.id|]]" style="display: none;"  />
                    <input name="setup[<?php echo $countsetup; ?>][input_count]" type="text" value="<?php echo $countsetup; ?>" style="display: none;"  />
                    [[.location_setup.]]: 
                    <select name="setup[<?php echo $countsetup; ?>][mice_location_id]" onchange="SelectLocationSetup('<?php echo $countsetup; ?>');" id="setup_mice_location_id_<?php echo $countsetup; ?>" class="datainput" style="padding: 5px; width: 150px;">[[|location_setup_option|]]</select>
                    <script>
                        jQuery("#setup_mice_location_id_<?php echo $countsetup; ?>").val('[[|setup.mice_location_id|]]');
                    </script>
                    <label id="lbl_setup_mice_location_id_<?php echo $countsetup; ?>" class="datalbl" style="display: none;">[[|setup.localtion_name|]]</label>
                    
                     | [[.department_setup.]]: 
                    <select name="setup[<?php echo $countsetup; ?>][mice_department_id]" onchange="SelectDepartmentSetup('<?php echo $countsetup; ?>');" id="setup_mice_department_id_<?php echo $countsetup; ?>" class="datainput" style="padding: 5px; width: 150px;">[[|department_setup_option|]]</select>
                    <script>
                        jQuery("#setup_mice_department_id_<?php echo $countsetup; ?>").val('[[|setup.mice_department_id|]]');
                    </script>
                    <label id="lbl_setup_mice_department_id_<?php echo $countsetup; ?>" class="datalbl" style="display: none;">[[|setup.department_name|]]</label>
                    
                     | [[.time.]]: 
                    <input value="[[|setup.hour|]]" name="setup[<?php echo $countsetup; ?>][hour]" type="text" id="setup_hour_<?php echo $countsetup; ?>" class="datainput datamarsk" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="padding: 5px; width: 50px;" />
                    <label id="lbl_setup_hour_<?php echo $countsetup; ?>" class="datalbl" style="display: none;">[[|setup.hour|]]</label>
                     - <input value="[[|setup.in_date|]]" name="setup[<?php echo $countsetup; ?>][in_date]" type="text" id="setup_in_date_<?php echo $countsetup; ?>" class="datainput datadate" onchange="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="padding: 5px; width: 80px;" />
                    <label id="lbl_setup_in_date_<?php echo $countsetup; ?>" class="datalbl" style="display: none;">[[|setup.in_date|]]</label>
                    
                    <div class="hide_print" onclick="jQuery('.setup_beo_<?php echo $countsetup; ?>').remove();" style="width: 20px; height: 20px; border: 2px solid #000000; color: #171717; text-transform: uppercase; line-height: 20px; text-align: center; position: absolute; top: 5px; right: -10px; cursor: pointer; background: red; color: #FFFFFF;">
                        X
                    </div>
                </th>
            </tr>
            <tr class="setup_beo_<?php echo $countsetup; ?>">
                <td>
                    <textarea id="setup_description_<?php echo $countsetup; ?>" name="setup[<?php echo $countsetup; ?>][description]" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value.replace(/\r?\n/g, '<br />'));" style="border: none; border: 1px solid #555555; width: 100%; min-height: 200px;" >[[|setup.description|]]</textarea>
                    <label id="lbl_setup_description_<?php echo $countsetup; ?>" class="datalbl" style="display: none;">[[|setup.description|]]</label>
                    <script>jQuery('#lbl_setup_description_<?php echo $countsetup; ?>').html(jQuery('#setup_description_<?php echo $countsetup; ?>').val().replace(/\r?\n/g, '<br />'));</script>
                </td>
            </tr>
            <!--/LIST:setup-->
            <tr id="add_setup" class="hide_print">
                <td>
                    <input onclick="addsetupbeo();" type="button" value="[[.add.]] SETUP" style="padding: 5px;" />
                </td>
            </tr>
        </table>
        <table style="width: 100%;" cellpadding="10" cellspacing="0" border="1" bordercolor="#EEEEEE">
            <tr>
                <th style="text-align: center;"><input name="user_view_full_name" type="text" value="[[|user_view_full_name|]]" id="user_view_full_name" class="datainput" onkeyup="var idinput = this.id; jQuery('#lbl_'+idinput).html(this.value);" style="border: none; border: 1px solid #555555; width: 100%; text-align: center;" /><label id="lbl_user_view_full_name" class="datalbl" style="display: none;">Prepared by: [[|user_view_full_name|]]</label></th>
            </tr>
        </table>
    </div>
</form>
<div id="navslidebar" style="width: 100%; height: 45px; background: rgba(15,51,75,0.7); position: fixed; top: 0px; left: 0px; transition: all 0.5s ease-in-out;">
    <table style="width: 100%; margin: 0px auto;" cellpadding="5">
        <tr>
            <td style="width: 45px; text-align: center;">
                <img src="packages/hotel/packages/mice/skins/img/beologo.png" style="width: 35px; height: auto;" />
            </td>
            <td style="color: #FFFFFF; font-weight: bold;">
                [[.banquet_event_order_mice.]] <span style="color: #ff8585;">[[|code_mice|]]</span> <?php if(Url::get('filekey')){ ?> / <span style="color: #00b2f9;"><?php echo 'BEO-'.Url::get('filekey') ?></span><?php } ?>
            </td>
            <td style="text-align: right;">
                <!--
                <?php if([[=status=]]!=1){ ?>
                    <?php if(Url::get('filekey')){ ?>
                        <label style="margin-right: 10px; color: #FFFFFF;" onclick="FunSubmit('apply');"><i class="fa fa-check fa-fw" style="color: #ff8585;"></i> [[.apply_mice.]]</label>
                        <label style="margin-right: 10px; color: #FFFFFF;" onclick="window.location.href='?page=mice_reservation&cmd=beoform&id=<?php echo Url::get('id'); ?>'"><i class="fa fa-plus fa-fw" style="color: #ff8585;"></i> [[.create_beo.]]</label>
                    <?php } ?>
                    <label style="margin-right: 10px; color: #FFFFFF;" onclick="FunSubmit('save');"><i class="fa fa-save fa-fw" style="color: #ff8585;"></i> [[.save.]]</label>
                    <label style="margin-right: 10px; color: #FFFFFF;" onclick="FunSubmit('print');"><i class="fa fa-print fa-fw" style="color: #ff8585;"></i> [[.print_and_save.]]</label>
                <?php }else{ ?>
                    <label style="margin-right: 10px; color: #FFFFFF;" onclick="FunSubmit('save');"><i class="fa fa-save fa-fw" style="color: #ff8585;"></i> [[.save.]]</label>
                    <label style="margin-right: 10px; color: #FFFFFF;" onclick="FunPrint();"><i class="fa fa-print fa-fw" style="color: #ff8585;"></i> [[.print.]]</label>
                <?php } ?>
                -->
                <label style="margin-right: 10px; color: #FFFFFF;" onclick="FunSubmit('save');"><i class="fa fa-save fa-fw" style="color: #ff8585;"></i> [[.save.]]</label>
                <label style="margin-right: 10px; color: #FFFFFF;" onclick="FunPrint();"><i class="fa fa-print fa-fw" style="color: #ff8585;"></i> [[.print.]]</label>
                <label style="margin-right: 10px; color: #FFFFFF;" onclick="window.location.href='?page=mice_reservation&cmd=edit&id=<?php echo Url::get('id'); ?>'"><i class="fa fa-arrow-left fa-fw" style="color: #ff8585;"></i> [[.back.]]</label>
            </td>
        </tr>
    </table>
</div>
<?php if(sizeof([[=list_beo=]])>0){ ?>
<div id="slidebar" style="width: 100%; height: 120px; background: rgba(15,51,75,0.7); position: fixed; bottom: 0px; left: 0px; transition: all 0.5s ease-in-out;">
    <table style="" cellpadding="9">
        <tr>
            <!--LIST:list_beo-->
            <td style="text-align: center; width: 140px; cursor: copy;" onclick="window.location.href='?page=mice_reservation&cmd=beoform&id=<?php echo Url::get('id'); ?>&filekey=[[|list_beo.key|]]'">
                <img src="packages/hotel/packages/mice/skins/img/beo-thumb.png" style="height: 60px; width: auto; border: 2px solid #FFFFFF;" />
                <br />
                <label style="color: #FFFFFF;">[[|list_beo.create_date|]]</label>
            </td>
            <!--/LIST:list_beo-->
        </tr>
    </table>
</div>
<?php } ?>
<script>
    jQuery(document).ready(function(){
        CloseMenu();
        var top=0;
        jQuery(window).scroll(function(){
            topscroll = jQuery(this).scrollTop();
            if(topscroll>top){ jQuery("#navslidebar").css('top','-50px'); }else{ jQuery("#navslidebar").css('top','0px'); }
            if(topscroll>top){ jQuery("#slidebar").css('bottom','0px'); }else{ jQuery("#slidebar").css('bottom','-120px'); }
            top = topscroll;
        });
    });
    jQuery(".datadate").datepicker();
    jQuery(".datamarsk").mask("99:99");
    var input_count_setup = 100 + <?php echo sizeof([[=setup=]]); ?>;
    console.log(input_count_setup);
    var location_setup_js = <?php echo String::array2js([[=location_setup=]]); ?>;
    var department_setup_js = <?php echo String::array2js([[=department_setup=]]); ?>;
    
    function SelectLocationSetup($index)
    {
        $location_mice_id = jQuery("#setup_mice_location_id_"+$index).val();
        for(var $i in location_setup_js)
        {
            if(to_numeric(location_setup_js[$i]['id'])==to_numeric($location_mice_id))
            {
                jQuery("#lbl_setup_mice_location_id_"+$index).html(location_setup_js[$i]['name']);
            }
        }
    }
    function SelectDepartmentSetup($index)
    {
        $department_mice_id = jQuery("#setup_mice_department_id_"+$index).val();
        for(var $i in department_setup_js)
        {
            if(department_setup_js[$i]['id']==$department_mice_id)
            {
                jQuery("#lbl_setup_mice_department_id_"+$index).html(department_setup_js[$i]['name']);
            }
        }
    }
    function CloseMenu()
    {
        jQuery('#testRibbon').css('display','none');
        jQuery("#sign-in").css('display','none');
        jQuery("#chang_language").css('display','none');
    }
    function OpenMenu()
    {
        jQuery('#testRibbon').css('display','');
        jQuery("#sign-in").css('display','');
        jQuery("#chang_language").css('display','');
    }
    function FunSubmit(key)
    {
        jQuery(".datainput").css('display','none');
        jQuery(".datalbl").css('display','');
        if(key=='print')
        {
            jQuery(".hide_print").css('display','none');
            var user ='<?php echo User::id(); ?>';
            printWebPart('BEO',user);
        }
        jQuery("#act").val(key);
        BeoFormMiceReservationForm.submit();
    }
    function FunPrint()
    {
        jQuery(".datainput").css('display','none');
        jQuery(".datalbl").css('display','');
        jQuery(".hide_print").css('display','none');
        var user ='<?php echo User::id(); ?>';
        printWebPart('BEO',user);
        jQuery(".hide_print").css('display','');
        jQuery(".datalbl").css('display','none');
        jQuery(".datainput").css('display','');
    }
    function addsetupbeo()
    {
        jQuery("#add_setup").remove();
        var $content = '';
        input_count_setup++;
        console.log(input_count_setup);
        $content += '<tr class="setup_beo_'+input_count_setup+'" style="background: #ffca96;">';
                $content += '<th style="position: relative;">';
                    $content += '<input name="setup['+input_count_setup+'][id]" type="text" value="" style="display: none;"  />';
                    $content += '<input name="setup['+input_count_setup+'][input_count]" type="text" value="'+input_count_setup+'" style="display: none;"  />';
                    $content += '[[.location_setup.]]: ';
                    $content += '<select onchange="SelectLocationSetup(\''+input_count_setup+'\');" name="setup['+input_count_setup+'][mice_location_id]" id="setup_mice_location_id_'+input_count_setup+'" class="datainput" style="padding: 5px; width: 150px;">[[|location_setup_option|]]</select>';
                    $content += '<label id="lbl_setup_mice_location_id_'+input_count_setup+'" class="datalbl" style="display: none;"></label>';
                    $content += ' | [[.department_setup.]]: ';
                    $content += '<select onchange="SelectDepartmentSetup(\''+input_count_setup+'\');" name="setup['+input_count_setup+'][mice_department_id]" id="setup_mice_department_id_'+input_count_setup+'" class="datainput" style="padding: 5px; width: 150px;">[[|department_setup_option|]]</select>';
                    $content += '<label id="lbl_setup_mice_department_id_'+input_count_setup+'" class="datalbl" style="display: none;"></label>';
                    $content += ' | [[.time.]]: ';
                    $content += '<input value="<?php echo date('H:i'); ?>" name="setup['+input_count_setup+'][hour]" type="text" id="setup_hour_'+input_count_setup+'" class="datainput" onkeyup="var idinput = this.id; jQuery(\'#lbl_\'+idinput).html(this.value);" style="padding: 5px; width: 50px;" />';
                    $content += '<label id="lbl_setup_hour_'+input_count_setup+'" class="datalbl" style="display: none;"><?php echo date('H:i'); ?></label>';
                    $content += ' - <input value="<?php echo date('d/m/Y'); ?>" name="setup['+input_count_setup+'][in_date]" type="text" id="setup_in_date_'+input_count_setup+'" class="datainput" onchange="var idinput = this.id; jQuery(\'#lbl_\'+idinput).html(this.value);" style="padding: 5px; width: 80px;" />';
                    $content += '<label id="lbl_setup_in_date_'+input_count_setup+'" class="datalbl" style="display: none;"><?php echo date('d/m/Y'); ?></label>';
                    $content += '<div class="hide_print" onclick="jQuery(\'.setup_beo_'+input_count_setup+'\').remove();" style="width: 20px; height: 20px; border: 2px solid #000000; color: #171717; text-transform: uppercase; line-height: 20px; text-align: center; position: absolute; top: 5px; right: -10px; cursor: pointer; background: red; color: #FFFFFF;">';
                        $content += 'X';
                    $content += '</div>';
                $content += '</th>';
            $content += '</tr>';
            $content += '<tr class="setup_beo_'+input_count_setup+'">';
                $content += '<td>';
                    $content += '<textarea id="setup_description_'+input_count_setup+'" name="setup['+input_count_setup+'][description]" class="datainput" onkeyup="var idinput = this.id; jQuery(\'#lbl_\'+idinput).html(this.value.replace(/\\r?\\n/g, \'<br />\'));" style="border: none; border: 1px solid #555555; width: 100%; min-height: 200px;" ></textarea>';
                    $content += '<label id="lbl_setup_description_'+input_count_setup+'" class="datalbl" style="display: none;"></label>';
                $content += '</td>';
            $content += '</tr>';
            $content += '<tr id="add_setup" class="hide_print">';
                $content += '<td>';
                    $content += '<input onclick="addsetupbeo();" type="button" value="[[.add.]] SETUP" style="padding: 5px;" />';
                $content += '</td>';
            $content += '</tr>';
        jQuery("#form_setup").append($content);
        jQuery('#setup_in_date_'+input_count_setup+'').datepicker();
        jQuery('#setup_hour_'+input_count_setup+'').mask("99:99");
        SelectLocationSetup(input_count_setup);
        SelectDepartmentSetup(input_count_setup);
    }
</script>