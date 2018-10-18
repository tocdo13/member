<form name="EditMiceReservationForm" method="POST">
    <input id="act" name="act" type="text" value="" style="display: none;" />
    <div id="MiceReservationBody">
        <div id="MiceReservationHeader">
            <table cellpadding="10">
                <tr>
                    <td>
                        <div class="DivTitle">
                            <img src="packages/hotel/packages/mice/skins/img/service.png" />
                            <?php if(Url::get('cmd')=='edit'){ ?>
                                <h1 style="border: none;">[[.detail.]] MICE</h1>
                            <?php }else{ ?>
                                <h1 style="border: none;">[[.mice_reservation.]]</h1>
                            <?php } ?>
                        </div>
                    </td>
                    <td>
                        <!--
                        <div id="JsPdf" >
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/back.png" /></div>
                            <span>[[.PDF.]]</span>
                        </div>
                        <div id="editor"></div>
                        -->
                        <div class="DivButton" onclick="window.location.href='?page=mice_reservation'">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/back.png" /></div>
                            <span>[[.back.]]</span>
                        </div>
                        <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                        <div class="DivButton" onclick="CheckSubmit('SAVE');">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/save.png" /></div>
                            <span>[[.save.]]</span>
                        </div>
                        <?php } ?>
                        <?php if( (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                        <div class="DivButton" onclick="CheckSubmit('CONFIRM');">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/confirm.png" /></div>
                            <span>[[.confirm_for_guest.]]</span>
                        </div>
                        <?php } ?>
                        <?php if( (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                        
                        <?php }else{ ?>
                        
                        <?php } ?>
                        <?php if( (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY)) ){ ?>
                        <div class="DivButton" onclick="window.location.href='?page=mice_reservation&cmd=beoform&id=<?php echo Url::get('id'); ?>';">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/report.png" /></div>
                            <span>[[.view_beo.]]</span>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
        <div id="MiceReservationContainer">
            <div id="MiceReservationInfoContact">
                <div class="MiceReservationContainerTitle">[[.infomation_contact.]]</div>
                <table cellpadding="10" style="width: 100%;">
                    <tr>
                        <td>
                            <label><i class="fa fa-user fa-fw"></i> [[.contact_person.]]:</label><br />
                            <input name="contact_name" type="text" id="contact_name" />
                        </td>
                        <td>
                            <label><i class="fa fa-group fa-fw"></i> [[.customer_name.]]:</label><br />
                            <input name="customer_name" type="text" id="customer_name" onfocus="Autocomplete();" autocomplete="OFF" style="width: 210px;" />
                            <input name="customer_id" type="text" id="customer_id" style="display: none;" />
                            <div class="DivButton"  onclick="window.open('?page=customer&cmd=add&site=mice')">
                                <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                                <span></span>
                            </div>
                        </td>
                        <td rowspan="6" style="vertical-align: top;">
                            <label><i class="fa fa-sticky-note fa-fw"></i> [[.note.]]:</label><br />
                            <textarea name="note" id="note" style="width: 400px; height: 210px; border: 1px solid #217346;"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><i class="fa fa-phone fa-fw"></i> [[.contact_phone.]]:</label><br />
                            <input name="contact_phone" type="text" id="contact_phone" autocomplete="OFF" />
                        </td>
                        <td>
                            <label><i class="fa fa-user-secret fa-fw"></i> [[.traveller_name.]]:</label><br />
                            <input name="traveller_name" type="text" id="traveller_name" onfocus="get_traveler();" autocomplete="OFF" style="width: 210px;" />
                            <input name="traveller_id" type="text" id="traveller_id" style="display: none;" />
                            <div class="DivButton"  onclick="window.open('?page=traveller&cmd=add&site=mice')">
                                <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                                <span></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><i class="fa fa-envelope-o fa-fw"></i> [[.contact_email.]]:</label><br />
                            <input name="contact_email" type="text" id="contact_email" autocomplete="OFF" />
                        </td>
                        <td>
                            <label><i class="fa fa-calendar-times-o fa-fw"></i> [[.create_date.]]:</label><br />
                            <input name="code_mice" type="text" id="code_mice" autocomplete="OFF" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><i class="fa fa-calendar-times-o fa-fw"></i> [[.start_date.]]:</label><br />
                            <input name="start_date" type="text" id="start_date" autocomplete="OFF" />
                        </td>
                        <td>
                            <label><i class="fa fa-calendar-times-o fa-fw"></i> [[.end_date.]]:</label><br />
                            <input name="end_date" type="text" id="end_date" autocomplete="OFF" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><i class="fa fa-calendar-times-o fa-fw"></i> [[.cut_of_date.]]:</label><br />
                            <input name="cut_of_date" type="text" id="cut_of_date" autocomplete="OFF" />
                        </td>
                        <td>
                            <?php if(Url::get('cmd')=='edit'){ ?>
                                <label><i class="fa fa-code fa-fw"></i> [[.code_mice.]]:</label><br />
                                <?php echo 'MICE+'.Url::get('id'); ?>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="MiceReservationInfoService">
                <div class="MiceReservationContainerTitle">[[.infomation_service.]]</div>
                <table cellpadding="10" style="width: 100%;">
                    <tr>
                        <td>
                            <!--LIST:items-->
                                <div id="MiceDepartment_[[|items.id|]]" class="Department DepartmentHide" >
                                    <input id="SH_[[|items.id|]]" type="checkbox" style="display: none;" />
                                    <div class="DepartmentIcon"><img src="[[|items.icon|]]" title="[[|items.name|]]" /></div>
                                    <div class="DepartmentName">[[|items.name|]]<br /><span>[[|items.description|]]</span></div>
                                    <div class="DepartmentControl" onclick="if(jQuery('#SH_[[|items.id|]]').attr('checked')=='checked'){ jQuery('#SH_[[|items.id|]]').removeAttr('checked'); jQuery('#MiceDepartment_[[|items.id|]]').removeClass('DepartmentShow'); jQuery('#MiceDepartment_[[|items.id|]]').addClass('DepartmentHide'); jQuery(this).html('+'); }else{ jQuery('#SH_[[|items.id|]]').attr('checked','checked'); jQuery('#MiceDepartment_[[|items.id|]]').removeClass('DepartmentHide'); jQuery('#MiceDepartment_[[|items.id|]]').addClass('DepartmentShow'); jQuery(this).html('_'); }">+</div>
                                    <div class="DepartmentMessenger">[[|items.count_item|]]</div>
                                    <div class="DepartmentItems">
                                        <?php if([[=items.id=]]=='REC'){ ?>
                                            <table id="MutilsREC" cellpadding="4" style="width: 100%;">
                                                <tr style="border-bottom-style: double; background: #217346; height: 35px;">
                                                    <th><label></label></th>
                                                    <th><label style="color: #FFFFFF;">[[.room_level.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.quantity_def.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.child.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.adult.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.time_in.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.from_date.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.time_out.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.to_date.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.price.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.usd_price.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total_amount.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.net.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.service_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.tax_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.note.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.delete.]]</label></th>
                                                </tr>
                                            </table>
                                        <?php }elseif([[=items.id=]]=='EXS'){ ?>
                                            <table id="MutilsEXS" cellpadding="4" style="width: 100%;">
                                                <tr style="border-bottom-style: double; background: #217346; height: 35px;">
                                                    <th><label></label></th>
                                                    <th><label style="color: #FFFFFF;">[[.type.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.service.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.start_date.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.end_date.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.quantity.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.price.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total_before_tax.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.service_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.tax_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total_amount.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.net.]]</label></th>
                                                    <th style="display: none;"><label style="color: #FFFFFF;">[[.close.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.note.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.delete.]]</label></th>
                                                </tr>
                                            </table>
                                        <?php }elseif([[=items.id=]]=='RES'){ ?>
                                            <table id="MutilsRES" cellpadding="4" style="width: 100%;">
                                                <tr style="border-bottom-style: double; background: #217346; height: 35px;">
                                                    <th><label></label></th>
                                                    <th><label style="color: #FFFFFF;">[[.bar.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.bar_table.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.date.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.time_in.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.time_out.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.full_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.full_charge.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.service_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.tax_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF; display: none;">[[.order_type.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.num_people.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount_percent.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.delete.]]</label></th>
                                                </tr>
                                            </table>
                                        <?php }elseif([[=items.id=]]=='VENDING'){ ?>
                                            <table id="MutilsVENDING" cellpadding="4" style="width: 100%;">
                                                <tr style="border-bottom-style: double; background: #217346; height: 35px;">
                                                    <th><label></label></th>
                                                    <th><label style="color: #FFFFFF;">[[.area_vending.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.date.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.time_in.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.full_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.full_charge.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.service_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.tax_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount_percent.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.delete.]]</label></th>
                                                </tr>
                                            </table>
                                        <?php }elseif([[=items.id=]]=='BANQUET'){ ?>
                                            <table id="MutilsPARTY" cellpadding="4" style="width: 100%;">
                                                <tr style="border-bottom-style: double; background: #217346; height: 35px;">
                                                    <th><label></label></th>
                                                    <th><label style="color: #FFFFFF;">[[.party_type.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.date.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.time_in.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.time_out.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.service_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.tax_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.promotions.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total_before_tax.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.delete.]]</label></th>
                                                </tr>
                                            </table>
                                        <?php }elseif([[=items.id=]]=='SPA'){ ?>
                                            <table id="MutilsSPA" cellpadding="4" style="width: 100%;">
                                                <tr style="border-bottom-style: double; background: #217346; height: 35px;">
                                                    <th><label></label></th>
                                                    <th><label style="color: #FFFFFF;">[[.net.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount_before_tax.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount_percent.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount_amount.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.tax_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.service_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total_before_tax.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total_amount.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.delete.]]</label></th>
                                                </tr>
                                            </table>
                                        <?php }elseif([[=items.id=]]=='TICKET'){ ?>
                                            <table id="MutilsTICKET" cellpadding="4" style="width: 100%;">
                                                <tr style="border-bottom-style: double; background: #217346; height: 35px;">
                                                    <th><label></label></th>
                                                    <th><label style="color: #FFFFFF;">[[.ticket_area.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.ticket.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.date_used.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.quantity.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.price.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount_quantity.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.discount_rate.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total_before_tax.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.total_amount.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.note.]]</label></th>
                                                    <th><label style="color: #FFFFFF;">[[.delete.]]</label></th>
                                                </tr>
                                            </table>
                                        <?php } ?>
                                        <!--------------------------- ----------------------------->
                                        <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                                        <br />
                                        <div class="DivButton"  onclick="AddItems('[[|items.id|]]'); GetTotalAmount();" style="float: left; clear: both;">
                                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                                            <span>[[.add.]] <?php if([[=items.id=]]=='REC'){ ?>[[.booking.]]<?php }elseif([[=items.id=]]=='RES'){ ?>[[.bar_reservation.]]<?php }elseif([[=items.id=]]=='BANQUET'){ ?>[[.party.]]<?php }elseif([[=items.id=]]=='SPA'){ ?>[[.spa.]]<?php } ?></span>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <!--/LIST:items-->
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="MiceReservationFooter">
            <p>&copy; Copy Right <?php echo date('Y'); ?> By Newway</p>
        </div>
    </div>
    <!--
     ** --------------------- **
     SildeBarLeft
     ** --------------------- **
    -->
    <div id="SildeBarLeft">
        <table id="sammary" cellpadding="5" style="width: 100%;">
            <tr style="background: #217346; height: 50px;">
                <td colspan="2" style="text-align: right;">
                    <div class="DivButton" onclick="window.location.href='?page=mice_reservation'">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/back.png" /></div>
                    </div>
                    <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY)  AND [[=status=]]!=1)){ ?>
                    <div class="DivButton" onclick="CheckSubmit('SAVE');">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/save.png" /></div>
                    </div>
                    <?php } ?>
                    <?php if( (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1 ) ){ ?>
                    <div class="DivButton" onclick="CheckSubmit('CONFIRM');">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/confirm.png" /></div>
                    </div>
                    <?php } ?>
                    <?php if( (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY)) ){ ?>
                    <div class="DivButton" onclick="window.location.href='?page=mice_reservation&cmd=beoform&id=<?php echo Url::get('id'); ?>';">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/report.png" /></div>
                    </div>
                    <?php } ?>
                </td>
            </tr>
            <!--LIST:items-->
            <tr>
                <td colspan="2"><img src="[[|items.icon|]]" title="[[|items.name|]]" style="width: 15px; height: auto;" /> <label style="font-weight: bold;">[[|items.name|]]</label></td>
            </tr>
            <tr style="border-bottom: 1px solid #CCCCCC;">
                <td><label>[[.quantity_def.]]</label><br /><label id="summary_quantity_[[|items.id|]]">0</label></td>
                <td><label>[[.total_amount.]]</label><br /><label id="summary_total_[[|items.id|]]">0</label></td>
            </tr>
            <!--/LIST:items-->
            <tr style="background: #217346; height: 40px;">
                <td colspan="2"><label style="color: #FFFFFF;">[[.infomation_payment.]]</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <label>[[.total_amount.]]:</label><br />
                    <input name="total_amount" type="text" id="total_amount" style="text-align: right; border: none; border-bottom: 1px solid #00b2f9;" readonly="readonly" />
                </td>
            </tr>
            <?php if($_REQUEST['cmd']!='add'){ ?>
            <tr style="display: none;">
                <td colspan="2">
                    <label>[[.deposit.]]:</label><br />
                    <input name="deposit" type="text" id="deposit" style="text-align: right; border: none; border-bottom: 1px solid #00b2f9;" readonly="readonly" />
                </td>
            </tr>
            <tr style="display: none;">
                <td colspan="2">
                    <?php if(User::can_edit(false,ANY_CATEGORY) AND [[=status=]]==1){ ?>
                    <div class="DivButton" style="border: 1px solid #EEEEEE;" onclick="DepositMiceReservation();">
                        <span>[[.deposit.]]</span>
                    </div>
                    <?php } ?>
                </td>
            </tr>
            <tr style="display: none;">
                <td colspan="2">
                    <label>[[.payment.]]:</label><br />
                    <input name="payment" type="text" id="payment" style="text-align: right; border: none; border-bottom: 1px solid #00b2f9;" readonly="readonly" />
                </td>
            </tr>
            <tr style="display: none;">
                <td colspan="2">
                    <?php if(User::can_edit(false,ANY_CATEGORY) AND [[=status=]]==1){ ?>
                    <div class="DivButton" style="border: 1px solid #EEEEEE;" onclick="PaymentMiceReservation();">
                        <span>[[.payment.]]</span>
                    </div>
                    <?php } ?>
                </td>
            </tr>
            <tr style="display: none;">
                <td colspan="2">
                    <label>[[.remain.]]:</label><br />
                    <input name="remain" type="text" id="remain" style="text-align: right; border: none; border-bottom: 1px solid #00b2f9;" readonly="readonly" />
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</form>

<!-- 
 ++ *********************************************
 ++ template spa, party, bar, restarant, vending
 ++ *********************************************
-->

<div style="display: none;"> 
    <!--- template Spa --->
    <table id="TemplateSpa">
        <tr class="TemplateSpa_X######X">
            <td style="text-align: center;"><input   name="spa[X######X][id]" type="text" id="spa_id_X######X" style="display: none;" /><label><i class="fa fa-leaf fa-fw"></i></label></td>
            <td style="text-align: center;"><input   name="spa[X######X][net_price]" type="checkbox" id="spa_net_price_X######X" <?php if(NET_PRICE_SPA==1){ ?>checked="checked"<?php } ?> onclick="GetTotalAmount();" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][discount_before_tax]" type="checkbox" id="spa_discount_before_tax_X######X" <?php if(DISCOUNT_BEFORE_TAX==1){ ?>checked="checked"<?php } ?> onclick="GetTotalAmount();" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][discount_percent]"  onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" type="text" id="spa_discount_percent_X######X" class="input_number" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][discount_amount]" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" type="text" id="spa_discount_amount_X######X" class="input_number" style="width: 70px; text-align: right;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][tax_rate]"  onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" type="text" id="spa_tax_rate_X######X" class="input_number" value="<?php echo SPA_TAX_RATE; ?>" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][service_rate]"  onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" type="text" id="spa_service_rate_X######X" class="input_number" value="<?php echo SPA_SERVICE_RATE; ?>" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][total_before_tax]" type="text" id="spa_total_before_tax_X######X" readonly="" style="width: 150px; text-align: right; background: #EEEEEE;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][total_amount]" type="text" id="spa_total_amount_X######X" readonly="" style="width: 150px; text-align: right; background: #EEEEEE;" /></td>
            <td style="text-align: center;"><label onclick="jQuery('.TemplateSpa_X######X').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label></td>
        </tr>
        <tr class="TemplateSpa_X######X">
            <td colspan="9" style="text-align: right;">
                <fieldset style="float: right;">
                    <legend  style="text-align: right;"><label><i class="fa fa-cog fa-fw"></i></label>[[.detail.]] [[.service.]]</legend>
                    <table id="MutilsSPAService_X######X" cellpadding="5" style="float: right;">
                        <tr style="background: #EEEEEE;">
                            <th><label></label></th>
                            <th><label>[[.product_code.]]</label></th>
                            <th><label>[[.product_name.]]</label></th>
                            <th><label>[[.room.]]</label></th>
                            <th><label>[[.staff.]]</label></th>
                            <th><label>[[.date.]]</label></th>
                            <th><label>[[.time_in.]]</label></th>
                            <th><label>[[.time_out.]]</label></th>
                            <th><label>[[.quantity_def.]]</label></th>
                            <th><label>[[.price.]]</label></th>
                            <th><label>[[.delete.]]</label></th>
                        </tr>
                    </table>
                    <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                    <br />
                    <div class="DivButton"  onclick="AddServiceSpa('X######X'); GetTotalAmount();" style="float: right; clear: both;">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                        <span>[[.add.]] [[.service.]]</span>
                    </div>
                    <?php } ?>
                </fieldset>
            </td>
        </tr>
        <tr class="TemplateSpa_X######X">
            <td colspan="9" style="text-align: right; border-bottom: 1px solid #DDDDDD;">
                <fieldset style="float: right;">
                    <legend style="text-align: right;"><label><i class="fa fa-dot-circle-o fa-fw"></i> [[.detail.]] [[.product.]]</label></legend>
                    <table id="MutilsSPAProduct_X######X" cellpadding="5" style="float: right;">
                        <tr style="background: #EEEEEE;">
                            <th><label></label></th>
                            <th><label>[[.product_code.]]</label></th>
                            <th><label>[[.product_name.]]</label></th>
                            <th><label>[[.date.]]</label></th>
                            <th><label>[[.time_in.]]</label></th>
                            <th><label>[[.time_out.]]</label></th>
                            <th><label>[[.quantity_def.]]</label></th>
                            <th><label>[[.price.]]</label></th>
                            <th><label>[[.amount.]]</label></th>
                            <th><label>[[.delete.]]</label></th>
                        </tr>
                    </table>
                    <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                    <br />
                    <div class="DivButton"  onclick="AddProductSpa('X######X'); GetTotalAmount();" style="float: right; clear: both;">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                        <span>[[.add.]] [[.product.]]</span>
                    </div>
                    <?php } ?>
                </fieldset>
            </td>
        </tr>
    </table>
    <table id="TemplateSpaService">
        <tr id="TemplateSpaService_X######X_Y######Y">
            <td style="text-align: center;"><input   name="spa[X######X][child_service][Y######Y][id]" type="text" id="spa_X######X_child_service_id_Y######Y" style="display: none;" /><label><i class="fa fa-cog fa-fw"></i></label></td>
            <td style="text-align: center;">
                <input   name="spa[X######X][child_service][Y######Y][product_id]" type="text" id="spa_X######X_child_service_product_id_Y######Y" onfocus="productAutoComplete(X######X,Y######Y);" onchange="getProductFromCode(X######X,Y######Y);" autocomplete="OFF" style="width: 70px; text-align: center;" />
                <input   name="spa[X######X][child_service][Y######Y][price_id]" type="text" id="spa_X######X_child_service_price_id_Y######Y" style="width: 70px; text-align: center; display: none;" />
            </td>
            <td style="text-align: center;">
                <input   name="spa[X######X][child_service][Y######Y][product_name]" type="text" id="spa_X######X_child_service_product_name_Y######Y" readonly="" style="width: 170px; text-align: center; background: #EEEEEE;" />
            </td>
            <td style="text-align: center;">
                <input   name="spa[X######X][child_service][Y######Y][spa_room_name]" type="text" id="spa_X######X_child_service_spa_room_name_Y######Y" readonly="" style="width: 70px; text-align: center; background: #EEEEEE;" />
                <input   name="spa[X######X][child_service][Y######Y][spa_room_id]" type="text" id="spa_X######X_child_service_spa_room_id_Y######Y" style="display: none;" />
                <img src="skins/default/images/cmd_Tim.gif" id="spa_X######X_child_service_select_room_Y######Y" style="cursor:pointer; width: 17px;" onclick="Check_Spa_Room_Availblity(X######X,Y######Y);"/>
            </td>
            <td style="text-align: center;">
                <input   type="button" onclick="Check_Staff_Availblity(X######X,Y######Y);" value="[[.select_staff.]]" style="width: 100px; text-align: center;" />
                <input   name="spa[X######X][child_service][Y######Y][staff_ids]" type="text" id="spa_X######X_child_service_staff_ids_Y######Y" style="display: none;" />
            </td>
            <td style="text-align: center;"><input   name="spa[X######X][child_service][Y######Y][in_date]" type="text" id="spa_X######X_child_service_in_date_Y######Y" value="<?php echo date('d/m/Y'); ?>" readonly="" class="layer_date" style="width: 70px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_service][Y######Y][time_in]" type="text" id="spa_X######X_child_service_time_in_Y######Y" value="<?php echo date('H:i',time()); ?>" class="layer_time" style="width: 35px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_service][Y######Y][time_out]" type="text" id="spa_X######X_child_service_time_out_Y######Y" value="<?php echo date('H:i',time()+3600); ?>" class="layer_time" style="width: 35px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_service][Y######Y][quantity]" type="text" id="spa_X######X_child_service_quantity_Y######Y" value="1"  onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_service][Y######Y][price]" type="text" id="spa_X######X_child_service_price_Y######Y"  onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /></td>
            
            <td style="text-align: center;"><label onclick="jQuery('#TemplateSpaService_X######X_Y######Y').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label></td>
        </tr>
    </table>
    <table id="TemplateSpaProduct">
        <tr id="TemplateSpaProduct_X######X_Y######Y">
            <td style="text-align: center;"><input   name="spa[X######X][child_product][Y######Y][id]" type="text" id="spa_X######X_child_product_id_Y######Y" style="display: none;" /><label><i class="fa fa-dot-circle-o fa-fw"></i></label></td>
            <td style="text-align: center;">
                <input   name="spa[X######X][child_product][Y######Y][product_id]" type="text" id="spa_X######X_child_product_product_id_Y######Y" onfocus="p_productAutoComplete(X######X,Y######Y);" onchange="p_getProductFromCode(X######X,Y######Y);" autocomplete="OFF" style="width: 70px; text-align: center;" />
                <input   name="spa[X######X][child_product][Y######Y][price_id]" type="text" id="spa_X######X_child_product_price_id_Y######Y" style="width: 70px; text-align: center; display: none;" />
            </td>
            <td style="text-align: center;">
                <input   name="spa[X######X][child_product][Y######Y][product_name]" type="text" id="spa_X######X_child_product_product_name_Y######Y" readonly="" style="width: 170px; text-align: center; background: #EEEEEE;" />
            </td>
            <td style="text-align: center;"><input   name="spa[X######X][child_product][Y######Y][in_date]" type="text" id="spa_X######X_child_product_in_date_Y######Y" value="<?php echo date('d/m/Y'); ?>" readonly="" class="layer_date" style="width: 70px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_product][Y######Y][time_in]" type="text" id="spa_X######X_child_product_time_in_Y######Y" value="<?php echo date('H:i',time()); ?>" class="layer_time" style="width: 35px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_product][Y######Y][time_out]" type="text" id="spa_X######X_child_product_time_out_Y######Y" value="<?php echo date('H:i',time()+3600); ?>" class="layer_time" style="width: 35px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_product][Y######Y][quantity]" type="text" id="spa_X######X_child_product_quantity_Y######Y" value="1"  onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_product][Y######Y][price]" type="text" id="spa_X######X_child_product_price_Y######Y"  onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /></td>
            <td style="text-align: center;"><input   name="spa[X######X][child_product][Y######Y][amount]" type="text" id="spa_X######X_child_product_amount_Y######Y" readonly="" style="width: 70px; text-align: right; background: #EEEEEE;" /></td>
            <td style="text-align: center;"><label onclick="jQuery('#TemplateSpaProduct_X######X_Y######Y').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label></td>
        </tr>
    </table>
    <!-- end tem spa -->
    
    <!-- Template Party -->
    <table id="TemplateParty">
        <tr class="TemplateParty_X######X">
            <td style="text-align: center; position: relative;"><input   name="party[X######X][id]" type="text" id="party_id_X######X" style="display: none;" /><input   name="party[X######X][party_reservation_id]" type="text" id="party_party_reservation_id_X######X" style="display: none;" /><label><i class="fa fa-beer fa-fw"></i></label><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="party[X######X][party_type]" id="party_party_type_X######X" style="width: 120px; text-align: center;">[[|all_party|]]</select><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="party[X######X][in_date]" type="text" id="party_in_date_X######X" value="<?php echo date('d/m/Y'); ?>" readonly="" class="layer_date" style="width: 70px; text-align: center;" /><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="party[X######X][time_in]" type="text" id="party_time_in_X######X"  value="<?php echo date('H:i',time()); ?>" class="layer_time" style="width: 40px; text-align: center;" /><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="party[X######X][time_out]" type="text" id="party_time_out_X######X"  value="<?php echo date('H:i',time()+2*3600); ?>" class="layer_time" style="width: 40px; text-align: center;" /><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="party[X######X][service_rate]" type="text" id="party_service_rate_X######X" value="5" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="party[X######X][tax_rate]" type="text" id="party_tax_rate_X######X" value="10" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   type="button" onclick="Select_Promotions(X######X);" value="[[.promotions.]]" style="width: 100px; text-align: center;" /><input   name="party[X######X][promotions]" type="text" id="party_promotions_X######X" style="width: 120px; text-align: center; display: none;" /><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="party[X######X][total_before_tax]" type="text" id="party_total_before_tax_X######X" readonly="" style="width: 150px; text-align: right; background: #EEEEEE;" /><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="party[X######X][total]" type="text" id="party_total_X######X" readonly="" style="width: 150px; text-align: right; background: #EEEEEE;" /><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><label onclick="jQuery('.TemplateParty_X######X').remove();GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label><div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
        </tr>
        <tr class="TemplateParty_X######X">
            <td colspan="11" style="text-align: right; position: relative;">
                <fieldset style="float: right;">
                    <legend style="text-align: right;"><label><i class="fa fa-dot-circle-o fa-fw"></i> [[.detail.]] [[.product.]]</label></legend>
                    <table id="MutilsPARTYProduct_X######X" cellpadding="5" style="float: right;">
                        <tr style="background: #EEEEEE;">
                            <th></th>
                            <th><label>[[.product_code.]]</label></th>
                            <th><label>[[.product_name.]]</label></th>
                            <th><label>[[.unit.]]</label></th>
                            <th><label>[[.quantity_def.]]</label></th>
                            <th><label>[[.price.]]</label></th>
                            <th><label>[[.type.]]</label></th>
                            <th><label>[[.delete.]]</label></th>
                        </tr>
                    </table>
                    <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                    <div class="DivButton"  onclick="AddProductParty('X######X');GetTotalAmount();" style="float: right; clear: both;">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                        <span>[[.add.]] [[.product.]]</span>
                    </div>
                    <?php } ?>
                </fieldset>
                <div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div>
            </td>
        </tr>
        <tr class="TemplateParty_X######X">
            <td colspan="11" style="text-align: right; border-bottom: 1px solid #DDDDDD; position: relative;">
                <fieldset style="float: right;">
                    <legend style="text-align: right;"><label><i class="fa fa-check fa-fw"></i> [[.detail.]] [[.party_room.]]</label></legend>
                    <table id="MutilsPARTYRoom_X######X" cellpadding="5" style="float: right;">
                        <tr style="background: #EEEEEE;">
                            <th></th>
                            <th><label>[[.party_room.]]</label></th>
                            <th><label>[[.time_type.]]</label></th>
                            <th><label>[[.address.]]</label></th>
                            <th><label>[[.price.]]</label></th>
                            <th><label>[[.type.]]</label></th>
                            <th><label>[[.note.]]</label></th>
                            <th><label>[[.delete.]]</label></th>
                        </tr>
                    </table>
                    <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                    <div class="DivButton"  onclick="AddRoomParty('X######X');GetTotalAmount();" style="float: right; clear: both;">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                        <span>[[.add.]] [[.party_room.]]</span>
                    </div>
                    <?php } ?>
                </fieldset>
                <div class="cover_party_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div>
            </td>
        </tr>
    </table>
    <table id="TemplatePartyProduct">
        <tr id="TemplatePartyProduct_X######X_Y######Y">
            <td style="text-align: center;"><input   name="party[X######X][child_product][Y######Y][id]" type="text" id="party_X######X_child_product_id_Y######Y" style="display: none;" /><label><i class="fa fa-dot-circle-o fa-fw"></i></label></td>
            <td style="text-align: center;">
                <input   name="party[X######X][child_product][Y######Y][product_id]" type="text" id="party_X######X_child_product_product_id_Y######Y" onfocus="partyproductAutoComplete(X######X,Y######Y);" onchange="partygetProductFromCode(X######X,Y######Y);" autocomplete="OFF" style="width: 80px; text-align: center;" />
            </td>
            <td style="text-align: center;">
                <input   name="party[X######X][child_product][Y######Y][product_name]" type="text" id="party_X######X_child_product_product_name_Y######Y" readonly="" style="width: 170px; background: #EEEEEE;" />
            </td>
            <td style="text-align: center;">
                <input   name="party[X######X][child_product][Y######Y][unit_name]" type="text" id="party_X######X_child_product_unit_name_Y######Y" readonly="" style="width: 70px; text-align: center;" />
                <input   name="party[X######X][child_product][Y######Y][unit_id]" type="text" id="party_X######X_child_product_unit_id_Y######Y" style="display: none;" />
            </td>
            <td style="text-align: center;"><input   name="party[X######X][child_product][Y######Y][quantity]" type="text" id="party_X######X_child_product_quantity_Y######Y" value="1" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="party[X######X][child_product][Y######Y][price]" type="text" id="party_X######X_child_product_price_Y######Y" onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /></td>
            <td style="text-align: center;"><select  name="party[X######X][child_product][Y######Y][type]" id="party_X######X_child_product_type_Y######Y" style="width: 120px; text-align: center;" onchange="partygetProductFromCode(X######X,Y######Y);">[[|all_product_party_type|]]</select></td>
            <td style="text-align: center;"><label onclick="jQuery('#TemplatePartyProduct_X######X_Y######Y').remove();GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label></td>
        </tr>
    </table>
    <table id="TemplatePartyRoom">
        <tr id="TemplatePartyRoom_X######X_Y######Y">
            <td style="text-align: center;"><label><i class="fa fa-check fa-fw"></i><input   name="party[X######X][child_room][Y######Y][id]" type="text" id="party_X######X_child_room_id_Y######Y" style="display: none;" /></td>
            <td style="text-align: center;">
                <select   name="party[X######X][child_room][Y######Y][party_room_id]" id="party_X######X_child_room_party_room_id_Y######Y" onchange="selectpartyrooms(X######X,Y######Y);" style="text-align: center; width: 150px;">[[|banquet_room_options|]]</select>
            </td>
            <td style="text-align: center;">
                <select   name="party[X######X][child_room][Y######Y][time_type]" id="party_X######X_child_room_time_type_Y######Y" onchange="selectpartyrooms(X######X,Y######Y);" style="text-align: center; width: 150px;"><option value="DAY">[[.full_day.]]</option><option value="MORNING">[[.morning.]]</option><option value="AFTERNOON">[[.afternoon.]]</option></select>
            </td>
            <td style="text-align: center;"><input   name="party[X######X][child_room][Y######Y][address]" type="text" id="party_X######X_child_room_address_Y######Y" style="width: 150px;" /></td>
            <td style="text-align: center;"><input   name="party[X######X][child_room][Y######Y][price]" type="text" id="party_X######X_child_room_price_Y######Y" onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /></td>
            <td style="text-align: center;"><select  name="party[X######X][child_room][Y######Y][type]" id="party_X######X_child_room_type_Y######Y" style="width: 120px; text-align: center;"><option value="1">[[.party_room.]]</option><option value="2">[[.meeting_room.]]</option></select></td>
            <td style="text-align: center;"><input   name="party[X######X][child_room][Y######Y][note]" type="text" id="party_X######X_child_room_note_Y######Y" style="width: 150px; text-align: center;" /></td>
            <td style="text-align: center;"><label onclick="jQuery('#TemplatePartyRoom_X######X_Y######Y').remove();GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label></td>
        </tr>
    </table>
    <!-- end tem party -->
    
    <!-- Template Res -->
    <table id="TemplateRes">
        <tr class="TemplateRes_X######X">
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][id]" type="text" id="bar_id_X######X" style="display: none;" /><input   name="bar[X######X][bar_reservation_id]" type="text" id="bar_bar_reservation_id_X######X" style="display: none;" /><label><i class="fa fa-cutlery fa-fw"></i></label><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="bar[X######X][bar_id]" id="bar_bar_id_X######X" onchange="parselayouttable(X######X);" style="width: 120px; text-align: center;">[[|bar_options|]]</select><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="bar[X######X][table_id]" id="bar_table_id_X######X" onchange="parselayoutproduct(X######X);" style="width: 120px; text-align: center; border: 1px solid #00b2f9;">[[|bar_table_options|]]</select><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][in_date]" type="text" id="bar_in_date_X######X" value="<?php echo date('d/m/Y'); ?>" readonly="" class="layer_date" style="width: 70px; text-align: center;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][time_in]" type="text" id="bar_time_in_X######X" value="<?php echo date('H:i',time()); ?>" class="layer_time" style="width: 40px; text-align: center;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][time_out]" type="text" id="bar_time_out_X######X" value="<?php echo date('H:i',time()+2*3600); ?>" class="layer_time" style="width: 40px; text-align: center;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][full_rate]" type="checkbox" id="bar_full_rate_X######X" onclick="if(document.getElementById('bar_full_rate_X######X').checked==true){ if(document.getElementById('bar_full_charge_X######X').checked==true){ document.getElementById('bar_full_charge_X######X').checked=false; } } GetTotalAmount();" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][full_charge]" type="checkbox" id="bar_full_charge_X######X" onclick="if(document.getElementById('bar_full_charge_X######X').checked==true){ if(document.getElementById('bar_full_rate_X######X').checked==true){ document.getElementById('bar_full_rate_X######X').checked=false; } } GetTotalAmount();" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][service_rate]" type="text" id="bar_service_rate_X######X" value="<?php echo RES_SERVICE_CHARGE; ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][tax_rate]" type="text" id="bar_tax_rate_X######X" value="<?php echo RES_TAX_RATE; ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][banquet_order_type]" type="text" id="bar_banquet_order_type_X######X" style="width: 80px; text-align: center;display: none;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][num_people]" type="text" id="bar_num_people_X######X" style="width: 30px; text-align: center;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][discount]" type="text" id="bar_discount_X######X" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: center;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="bar[X######X][discount_percent]" type="text" id="bar_discount_percent_X######X" onkeyup=" if(to_numeric(jQuery(this).val())>100 || to_numeric(jQuery(this).val())<0){ alert('khong duoc nhap nho hon 0 hoac lon hon 100'); jQuery(this).val(0); } jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><label onclick="jQuery('.TemplateRes_X######X').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label><div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
        </tr>
        <tr class="TemplateRes_X######X" style="border-bottom: 1px solid #DDDDDD;">
            <td style="text-align: center; position: relative;">
                <input type="number" id="coppy_bar_X######X" value="" style="width: 80px; text-align: center;" placeholder="[[.number_copy.]]" />
                <input type="button" onclick="CoppyBar(X######X);" value="[[.copy.]]" style="padding: 10px; margin-top: 5px;" />
                <div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div>
            </td>
            <td colspan="14" style="text-align: right; position: relative;">
                <fieldset style="float: right;">
                    <legend style="text-align: right;"><label><i class="fa fa-dot-circle-o fa-fw"></i>[[.detail.]] [[.product.]]</label></legend>
                    <table id="MutilsRESProduct_X######X" cellpadding="5" style="float: right;">
                        <tr style="background: #EEEEEE;">
                            <th><label></label></th>
                            <th><label>[[.product_code.]]</label></th>
                            <th><label>[[.product_name.]]</label></th>
                            <th><label>[[.unit.]]</label></th>
                            <th><label>[[.quantity_def.]]</label></th>
                            <th><label>[[.price.]]</label></th>
                            <th><label>[[.quantity_discount.]]</label></th>
                            <th><label>[[.discount_rate.]]</label></th>
                            <th><label>[[.note.]]</label></th>
                            <th><label>[[.delete.]]</label></th>
                        </tr>
                    </table>
                    <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                    <div class="DivButton"  onclick="AddProductBar('X######X'); GetTotalAmount();" style="float: right; clear: both;">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                        <span>[[.add.]] [[.product.]]</span>
                    </div>
                    <?php } ?>
                </fieldset>
                <div class="cover_res_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div>
            </td>
        </tr>
    </table>
    <table id="TemplateResProduct">
        <tr id="TemplateResProduct_X######X_Y######Y">
            <td style="text-align: center;"><input   name="bar[X######X][child][Y######Y][id]" type="text" id="bar_X######X_child_id_Y######Y" style="display: none;" /><label><i class="fa fa-dot-circle-o fa-fw"></i></label></td>
            <td style="text-align: center;">
                <input   name="bar[X######X][child][Y######Y][product_id]" type="text" id="bar_X######X_child_product_id_Y######Y" onfocus="barproductAutoComplete(X######X,Y######Y);" onchange="bargetProductFromCode(X######X,Y######Y);" autocomplete="OFF" style="width: 80px; text-align: center;" />
                <input   name="bar[X######X][child][Y######Y][price_id]" type="text" id="bar_X######X_child_price_id_Y######Y" style="display: none;" />
                
            </td>
            <td style="text-align: center;">
                <input   name="bar[X######X][child][Y######Y][product_name]" type="text" id="bar_X######X_child_product_name_Y######Y" readonly="" style="width: 120px; background: #EEEEEE;" />
                
            </td>
            <td style="text-align: center;">
                <input   name="bar[X######X][child][Y######Y][unit_name]" type="text" id="bar_X######X_child_unit_name_Y######Y" readonly="" style="width: 70px; text-align: center; background: #EEEEEE;" />
                <input   name="bar[X######X][child][Y######Y][unit_id]" type="text" id="bar_X######X_child_unit_id_Y######Y" style="display: none;" />
                
            </td>
            <td style="text-align: center;"><input   name="bar[X######X][child][Y######Y][quantity]" type="text" id="bar_X######X_child_quantity_Y######Y" value="1" onchange="if(to_numeric(jQuery(this).val())< to_numeric(jQuery('#bar_X######X_child_quantity_discount_Y######Y').val()) || to_numeric(jQuery(this).val())<0){ alert('khong duoc nhap nho hon 0 hoac lon hon so luong'); jQuery('#bar_X######X_child_quantity_discount_Y######Y').val(0); } jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="bar[X######X][child][Y######Y][price]" type="text" id="bar_X######X_child_price_Y######Y" <?php if(RES_EDIT_PRODUCT_PRICE==0){ ?>readonly=""<?php } ?> onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /></td>
            <td style="text-align: center;"><input   name="bar[X######X][child][Y######Y][quantity_discount]" type="text" id="bar_X######X_child_quantity_discount_Y######Y" onchange="if(to_numeric(jQuery(this).val())>to_numeric(jQuery('#bar_X######X_child_quantity_Y######Y').val()) || to_numeric(jQuery(this).val())<0){ alert('khong duoc nhap nho hon 0 hoac lon hon so luong'); jQuery(this).val(0); } jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="bar[X######X][child][Y######Y][discount_rate]" type="text" id="bar_X######X_child_discount_rate_Y######Y" onkeyup="if(to_numeric(jQuery(this).val())>100 || to_numeric(jQuery(this).val())<0){ alert('khong duoc nhap nho hon 0 hoac lon hon 100'); jQuery(this).val(0); }  jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="bar[X######X][child][Y######Y][note]" type="text" id="bar_X######X_child_note_Y######Y" style="width: 120px;" /></td>
            <td style="text-align: center;"><label onclick="jQuery('#TemplateResProduct_X######X_Y######Y').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label></td>
        </tr>
    </table>
    <!-- end tem res -->
    
    <!-- Template vending -->
    <table id="TemplateVending">
        <tr class="TemplateVending_X######X">
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][id]" type="text" id="vending_id_X######X" style="display: none;" /><input   name="vending[X######X][ve_reservation_id]" type="text" id="vending_ve_reservation_id_X######X" style="display: none;" /><label><i class="fa fa-cutlery fa-fw"></i></label><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="vending[X######X][department_id]" id="vending_department_id_X######X" onchange="parselayoutproductvending(X######X);" style="width: 120px; text-align: center;">[[|area_vending_options|]]</select><input   name="vending[X######X][department_code]" type="text" id="vending_department_code_X######X" style="display: none;" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][in_date]" type="text" id="vending_in_date_X######X" value="<?php echo date('d/m/Y'); ?>" readonly="" class="layer_date" style="width: 70px; text-align: center;" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][time_in]" type="text" id="vending_time_in_X######X" value="<?php echo date('H:i',time()); ?>" class="layer_time" style="width: 40px; text-align: center;" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][full_rate]" type="checkbox" id="vending_full_rate_X######X" <?php if(VENDING_FULL_RATE==1){ ?>checked="checked"<?php } ?> onclick="if(document.getElementById('vending_full_rate_X######X').checked==true){ if(document.getElementById('vending_full_charge_X######X').checked==true){ document.getElementById('vending_full_charge_X######X').checked=false; } } GetTotalAmount();" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][full_charge]" type="checkbox" id="vending_full_charge_X######X" <?php if(VENDING_FULL_CHARGE==1){ ?>checked="checked"<?php } ?> onclick="if(document.getElementById('vending_full_charge_X######X').checked==true){ if(document.getElementById('vending_full_rate_X######X').checked==true){ document.getElementById('vending_full_rate_X######X').checked=false; } } GetTotalAmount();" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][service_rate]" type="text" id="vending_service_rate_X######X" value="<?php echo VENDING_SERVICE_CHARGE; ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][tax_rate]" type="text" id="vending_tax_rate_X######X" value="<?php echo VENDING_TAX_RATE; ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][discount]" type="text" id="vending_discount_X######X" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: center;" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="vending[X######X][discount_percent]" type="text" id="vending_discount_percent_X######X" onkeyup="if(to_numeric(jQuery(this).val())>100 || to_numeric(jQuery(this).val())<0){ alert('khong duoc nhap nho hon 0 hoac lon hon 100'); jQuery(this).val(0); } jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><input   name="vending[X######X][exchange_rate]" type="text" id="vending_exchange_rate_X######X" value="[[|exchange_rate|]]" style="display: none;" /><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><label onclick="jQuery('.TemplateVending_X######X').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label><div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
        </tr>
        <tr class="TemplateVending_X######X" style="border-bottom: 1px solid #DDDDDD;">
            <td style="text-align: center; position: relative;">
                <input type="number" id="coppy_vending_X######X" value="" style="width: 80px; text-align: center;" placeholder="[[.number_copy.]]" />
                <input type="button" onclick="CoppyVending(X######X);" value="[[.copy.]]" style="padding: 10px; margin-top: 5px;" />
                <div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div>
            </td>
            <td colspan="10" style="text-align: right; position: relative;">
                <fieldset style="float: right;">
                    <legend style="text-align: right;"><label><i class="fa fa-dot-circle-o fa-fw"></i>[[.detail.]] [[.product.]]</label></legend>
                    <table id="MutilsVENDINGProduct_X######X" cellpadding="5" style="float: right;">
                        <tr style="background: #EEEEEE;">
                            <th><label></label></th>
                            <th><label>[[.product_code.]]</label></th>
                            <th><label>[[.product_name.]]</label></th>
                            <th><label>[[.unit.]]</label></th>
                            <th><label>[[.quantity_def.]]</label></th>
                            <th><label>[[.price.]]</label></th>
                            <th><label>[[.quantity_discount.]]</label></th>
                            <th><label>[[.discount_rate.]]</label></th>
                            <th><label>[[.note.]]</label></th>
                            <th><label>[[.delete.]]</label></th>
                        </tr>
                    </table>
                    <?php if( (Url::get('cmd')=='add' AND User::can_add(false,ANY_CATEGORY)) OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY) AND [[=status=]]!=1) ){ ?>
                    <div class="DivButton"  onclick="AddProductVending('X######X'); GetTotalAmount();" style="float: right; clear: both;">
                        <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                        <span>[[.add.]] [[.product.]]</span>
                    </div>
                    <?php } ?>
                </fieldset>
                <div class="cover_ve_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div>
            </td>
        </tr>
    </table>
    <table id="TemplateVendingProduct">
        <tr id="TemplateVendingProduct_X######X_Y######Y">
            <td style="text-align: center;"><input   name="vending[X######X][child][Y######Y][id]" type="text" id="vending_X######X_child_id_Y######Y" style="display: none;" /><label><i class="fa fa-dot-circle-o fa-fw"></i></label></td>
            <td style="text-align: center;">
                <input   name="vending[X######X][child][Y######Y][product_id]" type="text" id="vending_X######X_child_product_id_Y######Y" onfocus="vendingproductAutoComplete(X######X,Y######Y);" onchange="vendinggetProductFromCode(X######X,Y######Y);" autocomplete="OFF" style="width: 80px; text-align: center;" />
                <input   name="vending[X######X][child][Y######Y][price_id]" type="text" id="vending_X######X_child_price_id_Y######Y" style="display: none;" />
                
            </td>
            <td style="text-align: center;">
                <input   name="vending[X######X][child][Y######Y][product_name]" type="text" id="vending_X######X_child_product_name_Y######Y" readonly="" style="width: 120px; background: #EEEEEE;" />
                
            </td>
            <td style="text-align: center;">
                <input   name="vending[X######X][child][Y######Y][unit_name]" type="text" id="vending_X######X_child_unit_name_Y######Y" readonly="" style="width: 70px; text-align: center; background: #EEEEEE;" />
                <input   name="vending[X######X][child][Y######Y][unit_id]" type="text" id="vending_X######X_child_unit_id_Y######Y" style="display: none;" />
                
            </td>
            <td style="text-align: center;"><input   name="vending[X######X][child][Y######Y][quantity]" type="text" id="vending_X######X_child_quantity_Y######Y" value="1" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="vending[X######X][child][Y######Y][price]" type="text" id="vending_X######X_child_price_Y######Y" <?php if(RES_EDIT_PRODUCT_PRICE==0){ ?>readonly=""<?php } ?> onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /></td>
            <td style="text-align: center;"><input   name="vending[X######X][child][Y######Y][quantity_discount]" type="text" id="vending_X######X_child_quantity_discount_Y######Y" onkeyup="if(to_numeric(jQuery(this).val())>to_numeric(jQuery('#vending_X######X_child_quantity_Y######Y').val()) || to_numeric(jQuery(this).val())<0){ alert('khong duoc nhap nho hon 0 hoac lon hon so luong'); jQuery(this).val(0); } jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="vending[X######X][child][Y######Y][discount_rate]" type="text" id="vending_X######X_child_discount_rate_Y######Y" onkeyup="if(to_numeric(jQuery(this).val())>100 || to_numeric(jQuery(this).val())<0){ alert('khong duoc nhap nho hon 0 hoac lon hon 100'); jQuery(this).val(0); } jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /></td>
            <td style="text-align: center;"><input   name="vending[X######X][child][Y######Y][note]" type="text" id="vending_X######X_child_note_Y######Y" style="width: 120px;" /></td>
            <td style="text-align: center;"><label onclick="jQuery('#TemplateVendingProduct_X######X_Y######Y').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label></td>
        </tr>
    </table>
    <!-- end tem vending -->
    
    <!-- Template EXTRA -->
    <table id="TemplateExs">
        <tr class="TemplateExs_X######X" style="border-bottom: 1px solid #DDDDDD;">
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][id]" type="text" id="extra_id_X######X" style="display: none;" /> <input  name="extra[X######X][extra_id]" type="text" id="extra_extra_id_X######X" style="display: none;" /><label><i class="fa fa-cog fa-fw"></i></label><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="extra[X######X][type]" id="extra_type_X######X" onchange="selecttypeserviceextra(X######X);" style="width: 100px; text-align: center;"><option value="SERVICE">[[.extra_service_other.]]</option><option value="ROOM">[[.service_room.]]</option></select><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="extra[X######X][service_id]" id="extra_service_id_X######X" onchange="selectserviceextra(X######X);" style="width: 120px; text-align: center;">[[|extra_service_options|]]</select><input  name="extra[X######X][service_name]" type="text" id="extra_service_name_X######X" style="display: none;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][start_date]" type="text" id="extra_start_date_X######X" onchange="GetTotalAmount();" value="<?php echo date('d/m/Y'); ?>" style="width: 80px; text-align: center;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][end_date]" type="text" id="extra_end_date_X######X" onchange="GetTotalAmount();" value="<?php echo date('d/m/Y'); ?>" style="width: 80px; text-align: center;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][quantity]" type="text" id="extra_quantity_X######X" value="1" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); if(to_numeric(jQuery(this).val())<0){ jQuery(this).val(0); } GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][price]" type="text" id="extra_price_X######X" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); if(to_numeric(jQuery(this).val())<0){ jQuery(this).val(0); } GetTotalAmount();" style="width: 70px; text-align: right;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative; display: none;"><input   name="extra[X######X][percentage_discount]" type="text" id="extra_percentage_discount_X######X" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative; display: none;"><input   name="extra[X######X][amount_discount]" type="text" id="extra_amount_discount_X######X" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][total_before_tax]" type="text" id="extra_total_before_tax_X######X" readonly="" style="width: 70px; text-align: right;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][service_rate]" type="text" id="extra_service_rate_X######X" value="<?php echo EXTRA_SERVICE_SERVICE_CHARGE; ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][tax_rate]" type="text" id="extra_tax_rate_X######X" value="<?php echo EXTRA_SERVICE_TAX_RATE; ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][total_amount]" type="text" id="extra_total_amount_X######X" readonly="" style="width: 70px; text-align: right;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][net_price]" type="checkbox" id="extra_net_price_X######X" <?php if(NET_PRICE_SERVICE==1){ ?> checked="checked" <?php } ?> onclick="GetTotalAmount();" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative; display: none;"><input   name="extra[X######X][close]" type="checkbox" id="extra_close_X######X"/><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="extra[X######X][note]" type="text" id="extra_note_X######X" style="width: 70px;" /><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><label onclick="jQuery('.TemplateExs_X######X').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label><div class="cover_exs_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
        </tr>
    </table>
    <!-- end tem extra -->
    
    <!-- Template Rec -->
    <table id="TemplateRec">
        <tr class="TemplateRec_X######X" style="border-bottom: 1px solid #DDDDDD;">
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][id]" type="text" id="booking_id_X######X" style="display: none;" /><label><i class="fa fa-hotel fa-fw"></i></label> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="booking[X######X][room_level_id]" id="booking_room_level_id_X######X" onchange="selectroomlevel(X######X);" style="width: 120px; text-align: center;">[[|room_level_option|]]</select> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][quantity]" type="text" id="booking_quantity_X######X" value="1" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][child]" type="text" id="booking_child_X######X" value="0" style="width: 30px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][adult]" type="text" id="booking_adult_X######X" value="2" style="width: 30px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][time_in]" type="text" id="booking_time_in_X######X" value="<?php echo CHECK_IN_TIME; ?>" onchange="checkdatetimebooking(X######X); GetTotalAmount();" class="layer_time" style="width: 40px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][from_date]" type="text" id="booking_from_date_X######X" value="<?php echo date('d/m/Y'); ?>" onchange="checkdatetimebooking(X######X); GetTotalAmount();" readonly="" class="layer_date" style="width: 70px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][time_out]" type="text" id="booking_time_out_X######X" value="<?php echo CHECK_OUT_TIME; ?>" onchange="checkdatetimebooking(X######X); GetTotalAmount();" class="layer_time" style="width: 40px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][to_date]" type="text" id="booking_to_date_X######X" value="<?php echo date('d/m/Y',(time()+24*3600)); ?>" onchange="checkdatetimebooking(X######X); GetTotalAmount();" readonly="" class="layer_date" style="width: 70px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][price]" type="text" id="booking_price_X######X" onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); jQuery('#booking_usd_price_X######X').val(number_format((to_numeric(this.value)/jQuery('#booking_exchange_rate_X######X').val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][usd_price]" type="text" id="booking_usd_price_X######X" onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); jQuery('#booking_price_X######X').val(number_format((to_numeric(this.value)*jQuery('#booking_exchange_rate_X######X').val()))); GetTotalAmount();" style="width: 50px; text-align: right;" /><input   name="booking[X######X][exchange_rate]" type="text" id="booking_exchange_rate_X######X" value="[[|exchange_rate|]]" style="width: 70px; text-align: right; display: none;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][total_amount]" type="text" id="booking_total_amount_X######X" readonly="" style="width: 70px; text-align: right;" /><input   name="booking[X######X][recode]" type="text" id="booking_recode_X######X" style="width: 70px; text-align: right; display: none;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][net_price]" type="checkbox" id="booking_net_price_X######X" <?php if(NET_PRICE==1){ ?> checked="checked" <?php } ?> onclick="GetTotalAmount();" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][service_rate]" type="text" id="booking_service_rate_X######X" value="<?php echo RECEPTION_SERVICE_CHARGE; ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][tax_rate]" type="text" id="booking_tax_rate_X######X" value="<?php echo RECEPTION_TAX_RATE; ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="booking[X######X][note]" type="text" id="booking_note_X######X" style="width: 50px;" /> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><label onclick="jQuery('.TemplateRec_X######X').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label> <div class="cover_rec_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
        </tr>
    </table>
    <!-- end tem rec -->
    
    <!-- Template TICKET -->
    <table id="TemplateTicket">
        <tr class="TemplateTicket_X######X" style="border-bottom: 1px solid #DDDDDD;">
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][id]" type="text" id="ticket_id_X######X" style="display: none;" /><label><i class="fa fa-hotel fa-fw"></i></label> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="ticket[X######X][ticket_area_id]" id="ticket_ticket_area_id_X######X" onchange="selectticketarea(X######X);" style="width: 120px; text-align: center;">[[|ticket_area_options|]]</select> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><select  name="ticket[X######X][ticket_id]" id="ticket_ticket_id_X######X" onchange="selectticket(X######X);" style="width: 120px; text-align: center;">[[|ticket_options|]]</select> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][date_used]" type="text" id="ticket_date_used_X######X" value="<?php echo date('d/m/Y'); ?>" style="width: 80px; text-align: center;" /><div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][quantity]" type="text" id="ticket_quantity_X######X" value="1" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalAmount();" style="width: 30px; text-align: center;" /><div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][price]" type="text" id="ticket_price_X######X" onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); jQuery('#booking_usd_price_X######X').val(number_format((to_numeric(this.value)/jQuery('#booking_exchange_rate_X######X').val()))); GetTotalAmount();" style="width: 70px; text-align: right;" /> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][discount_quantity]" type="text" id="ticket_discount_quantity_X######X" onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); jQuery('#booking_usd_price_X######X').val(number_format((to_numeric(this.value)/jQuery('#booking_exchange_rate_X######X').val()))); GetTotalAmount();" style="width: 30px; text-align: right;" /> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][discount_rate]" type="text" id="ticket_discount_rate_X######X" onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); jQuery('#booking_usd_price_X######X').val(number_format((to_numeric(this.value)/jQuery('#booking_exchange_rate_X######X').val()))); GetTotalAmount();" style="width: 30px; text-align: right;" /> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][total_before_tax]" type="text" id="ticket_total_before_tax_X######X" readonly="" style="width: 70px; text-align: right;" /> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][total_amount]" type="text" id="ticket_total_amount_X######X" readonly="" style="width: 70px; text-align: right;" /><input   name="ticket[X######X][ticket_reservation_id]" type="text" id="ticket_ticket_reservation_id_X######X" style="width: 70px; text-align: right; display: none;" /> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><input   name="ticket[X######X][note]" type="text" id="ticket_note_X######X" style="width: 50px;" /> <div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
            <td style="text-align: center; position: relative;"><label onclick="jQuery('.TemplateTicket_X######X').remove(); GetTotalAmount();"><i class="fa fa-remove fa-fw"></i></label><div class="cover_ticket_X######X" style="width: 100%; height: 100%; background: rgba(0,0,0,0.3); position: absolute; top: 0px; left: 0px; display: none;"></div></td>
        </tr>
    </table>
    <!-- end tem TICKET -->
    
</div>


<!--
 ** --------------------- **
 SildeBarRight
 ** --------------------- **
-->
<div id="SildeBarRight">
    <table cellpadding="5">
        <!--LIST:items-->
        <tr>
            <td>
                <div>
                    <a href="#MiceDepartment_[[|items.id|]]" style="text-decoration: none;"><img src="[[|items.icon|]]" title="[[|items.name|]]" /></a>
                </div>
            </td>
        </tr>
        <!--/LIST:items-->
    </table>
</div>
<!--
 ** --------------------- **
 LightBox
 ** --------------------- **
-->
<div id="LightBox" style="display: none;">
    <div id="LightBoxContainer">
        <div id="LightBoxClose" onclick="CloseLightBox();">X</div>
        <div id="LightBoxHeader"></div>
        <div id="LightBoxContent">
        </div>
        <div id="LightBoxFooter"></div>
    </div>
</div>
<!--
 ** --------------------- **
 ErrorBox
 ** --------------------- **
-->
<div id="ErrorBox" style="width: 100%; height: auto; min-height: 0px; max-height: 100px; overflow: auto; position: fixed; bottom: 0px; left: 0px; background: #FFFFFF; box-shadow: 0px 0px 5px #171717;">
    <table style="width: 100%;">
        <tr>
            <td>
                <?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><br clear="all"/><?php }?>
            </td>
            <td style="vertical-align: top; width: 30px; text-align: right;">
                <?php if(Form::$current->is_error()){?><label onclick="jQuery('#ErrorBox').remove();"><i class="fa fa-remove fa-fw"></i></label><?php } ?>
            </td>
        </tr>
    </table>
</div>
<script>
    var windownskey = 0;
    var ALERT_TITLE = 'MICE MESSENGER';
    var ALERT_BUTTON_TEXT = 'OK MICE';
    var TIME_IN = '<?php echo CHECK_IN_TIME; ?>';
    var TIME_OUT = '<?php echo CHECK_OUT_TIME; ?>';
    var ARRIVAL_TIME = '<?php echo date('d/m/Y'); ?>';
    var DEPARTURE_TIME = '<?php echo date('d/m/Y',(time()+24*3600)); ?>';
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    var list_bars = <?php echo String::array2js([[=list_bars=]]);?>;
    var list_bar_tables = <?php echo String::array2js([[=list_bar_tables=]]);?>;
    var room_level = <?php echo String::array2js([[=room_level=]]);?>;
    var banquet_rooms = <?php echo String::array2js([[=banquet_rooms=]]);?>;
    var all_party_promotions = <?php echo String::array2js([[=all_party_promotions=]]);?>;
    var all_products = <?php echo String::array2js([[=all_products=]]);?>;
    var list_area_vending = <?php echo String::array2js([[=list_area_vending=]]);?>;
    var all_extra_service = <?php echo String::array2js([[=all_extra_service=]]);?>;
    var all_ticket_area = <?php echo String::array2js([[=all_ticket_area=]]);?>;
    var all_ticket = <?php echo String::array2js([[=all_ticket=]]);?>;
    /** parselayout **/
    var mi_row_booking = <?php echo isset($_REQUEST['booking'])?String::array2js($_REQUEST['booking']):String::array2js(array()); ?>;
    var mi_row_extra = <?php echo isset($_REQUEST['extra'])?String::array2js($_REQUEST['extra']):String::array2js(array()); ?>;
    var mi_row_bar = <?php echo isset($_REQUEST['bar'])?String::array2js($_REQUEST['bar']):String::array2js(array()); ?>;
    var mi_row_vending = <?php echo isset($_REQUEST['vending'])?String::array2js($_REQUEST['vending']):String::array2js(array()); ?>;
    var mi_row_party = <?php echo isset($_REQUEST['party'])?String::array2js($_REQUEST['party']):String::array2js(array()); ?>;
    var mi_row_spa = <?php echo isset($_REQUEST['spa'])?String::array2js($_REQUEST['spa']):String::array2js(array()); ?>;
    var mi_row_ticket = <?php echo isset($_REQUEST['ticket'])?String::array2js($_REQUEST['ticket']):String::array2js(array()); ?>;
    /** end **/
    //console.log(mi_row_party);
    //console.log(mi_row_extra);
    jQuery("#start_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
    jQuery("#end_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
    jQuery("#cut_of_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
    jQuery(document).ready(function(){
        /*
        var doc = new jsPDF();
        var specialElementHandlers = {
            '#editor': function (element, renderer) {
                return true;
            }
        };
        
        jQuery('#JsPdf').click(function () {
            doc.fromHTML(jQuery('#MiceReservationContainer').html(),15,15,{
                'width': 1000,
                    'elementHandlers': specialElementHandlers
            });
            doc.save('sample-file.pdf');
        });
        */
        jQuery(window).scroll(function(){
            if(jQuery(this).scrollTop()>110){ /*CloseMenu();*/ jQuery("#SildeBarLeft").css('top','10px'); jQuery("#SildeBarRight").css('top','10px'); }else{ /*OpenMenu();*/ jQuery("#SildeBarLeft").css('top','130px'); jQuery("#SildeBarRight").css('top','130px'); }
        });
        jQuery.mCustomScrollbar.defaults.scrollButtons.enable=true;
        jQuery.mCustomScrollbar.defaults.axis="y";
        jQuery("#LightBoxContent").mCustomScrollbar({theme:"dark"});
        /** notifications **/
        if(document.getElementById) 
        {
            window.alert = function(txt) 
            {
                createCustomAlert(txt);
            }
        }
        /** end notifications **/
        
        /** mutilitem **/
        for(var i in mi_row_booking)
        {
            AddItems('REC',mi_row_booking[i]);
            jQuery('#SH_REC').attr('checked','checked'); jQuery('#MiceDepartment_REC').removeClass('DepartmentHide'); jQuery('#MiceDepartment_REC').addClass('DepartmentShow'); jQuery('#MiceDepartment_REC .DepartmentControl').html('_');
        }
        for(var i in mi_row_extra)
        {
            AddItems('EXS',mi_row_extra[i]);
            jQuery('#SH_EXS').attr('checked','checked'); jQuery('#MiceDepartment_EXS').removeClass('DepartmentHide'); jQuery('#MiceDepartment_EXS').addClass('DepartmentShow'); jQuery('#MiceDepartment_EXS .DepartmentControl').html('_');
        }
        for(var i in mi_row_bar)
        {
            AddItems('RES',mi_row_bar[i]);
            var input_count = Bar_InputCount-1;
            for(var j in mi_row_bar[i]['child'])
            {
                AddProductBar(input_count,mi_row_bar[i]['child'][j]);
            }
            jQuery('#SH_RES').attr('checked','checked'); jQuery('#MiceDepartment_RES').removeClass('DepartmentHide'); jQuery('#MiceDepartment_RES').addClass('DepartmentShow'); jQuery('#MiceDepartment_RES .DepartmentControl').html('_');
        }
        for(var i in mi_row_vending)
        {
            AddItems('VENDING',mi_row_vending[i]);
            var input_count = Vending_InputCount-1;
            for(var j in mi_row_vending[i]['child'])
            {
                AddProductVending(input_count,mi_row_vending[i]['child'][j]);
            }
            jQuery('#SH_VENDING').attr('checked','checked'); jQuery('#MiceDepartment_VENDING').removeClass('DepartmentHide'); jQuery('#MiceDepartment_VENDING').addClass('DepartmentShow'); jQuery('#MiceDepartment_VENDING .DepartmentControl').html('_');
        }
        for(var i in mi_row_party)
        {
            AddItems('BANQUET',mi_row_party[i]);
            var input_count = Party_InputCount-1;
            for(var j in mi_row_party[i]['child_room'])
            {
                AddRoomParty(input_count,mi_row_party[i]['child_room'][j]);
            }
            for(var k in mi_row_party[i]['child_product'])
            {
                AddProductParty(input_count,mi_row_party[i]['child_product'][k]);
            }
            jQuery('#SH_BANQUET').attr('checked','checked'); jQuery('#MiceDepartment_BANQUET').removeClass('DepartmentHide'); jQuery('#MiceDepartment_BANQUET').addClass('DepartmentShow'); jQuery('#MiceDepartment_BANQUET .DepartmentControl').html('_');
        }
        for(var i in mi_row_spa)
        {
            AddItems('SPA',mi_row_spa[i]);
            var input_count = Spa_InputCount-1;
            for(var j in mi_row_spa[i]['child_service'])
            {
                AddServiceSpa(input_count,mi_row_spa[i]['child_service'][j]);
            }
            for(var k in mi_row_spa[i]['child_product'])
            {
                AddProductSpa(input_count,mi_row_spa[i]['child_product'][k]);
            }
            jQuery('#SH_SPA').attr('checked','checked'); jQuery('#MiceDepartment_SPA').removeClass('DepartmentHide'); jQuery('#MiceDepartment_SPA').addClass('DepartmentShow'); jQuery('#MiceDepartment_SPA .DepartmentControl').html('_');
        }
        for(var i in mi_row_ticket)
        {
            AddItems('TICKET',mi_row_ticket[i]);
            jQuery('#SH_TICKET').attr('checked','checked'); jQuery('#MiceDepartment_TICKET').removeClass('DepartmentHide'); jQuery('#MiceDepartment_TICKET').addClass('DepartmentShow'); jQuery('#MiceDepartment_TICKET .DepartmentControl').html('_');
        }
        GetTotalAmount();
        /** end mutilitem **/
    });
    function FunOpenWindowns(WinSrc,Wintitle,WinWidth,WinHeight,WinTop,WinLeft)
    {
        windownskey++;
        jQuery("body").append('<div id="container_window-'+windownskey+'" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(27,24,48,0.95);"></div>');
        jQuery.newWindow({
                        id:"window-"+windownskey,
                        posx:WinLeft,
                        posy:WinTop,
                        width:WinWidth,
                        height:WinHeight,
                        title:Wintitle,
                        type:"iframe"
                         });
        jQuery.updateWindowContent("window-"+windownskey,'<iframe src="'+WinSrc+'" width="'+WinWidth+'px" height="'+WinHeight+'px" />');
    }
    function PaymentMiceReservation()
    {
        var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=428&cmd=payment&id=<?php echo Url::get('id'); ?>&type=MICE&total_amount=[[|total_amount|]]';
        var Wintitle = '[[.payment.]] MICE';
        FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
    }
    function DepositMiceReservation()
    {
        var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=428&cmd=deposit&id=<?php echo Url::get('id'); ?>&type=MICE';
        var Wintitle = '[[.deposit.]] MICE';
        FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
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
    function OpenLightBox($header,$content,$footer)
    {
        document.getElementById('LightBoxHeader').innerHTML = $header;
        document.getElementById('mCSB_1_container').innerHTML = $content;
        document.getElementById('LightBoxFooter').innerHTML = $footer;
        jQuery("#LightBox").css('display','');
    }
    function CloseLightBox()
    {
        document.getElementById('LightBoxHeader').innerHTML = '';
        document.getElementById('mCSB_1_container').innerHTML = '';
        document.getElementById('LightBoxFooter').innerHTML = '';
        jQuery("#LightBox").css('display','none');
    }
    function createCustomAlert(txt) 
    {
        d = document;
    
        if(d.getElementById("modalContainer")) return;
    
        mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
        mObj.id = "modalContainer";
        mObj.style.height = d.documentElement.scrollHeight + "px";
    
        alertObj = mObj.appendChild(d.createElement("div"));
        alertObj.id = "alertBox";
        if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
        alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
        alertObj.style.visiblity="visible";
    
        h1 = alertObj.appendChild(d.createElement("h1"));
        h1.appendChild(d.createTextNode(ALERT_TITLE));
    
        msg = alertObj.appendChild(d.createElement("p"));
        msg.innerHTML = txt;
        btn = alertObj.appendChild(d.createElement("a"));
        btn.id = "closeBtn";
        btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
        btn.href = "#";
        btn.focus();
        btn.onclick = function() { removeCustomAlert();return false; }
        alertObj.style.display = "block";
    }
    function removeCustomAlert() 
    {
        document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
    }
    function count_date(start_day, end_day)
    {
    	var std =start_day.split("/");
    	var std_day=std[0];
    	var std_month=std[1];
    	var std_year=std[2];
    	var ed =end_day.split("/");
    	var ed_day=ed[0];
    	var ed_month=ed[1];
    	var ed_year=ed[2];
    	var startDAY=std_month+"/"+std_day+"/"+std_year;
    	var endDAY=ed_month+"/"+ed_day+"/"+ed_year;
    	var std_second=Date.parse(startDAY);
    	var ed_second=Date.parse(endDAY);
    	return (ed_second-std_second)/86400000;
    }
    function checkbarconflig()
    {
        $check = new Array();
        $check['value'] = true;
        $check['data'] = new Array();
        for(var i=100;i<=(Bar_InputCount-1);i++)
        {
            if(jQuery("#bar_id_"+i).val()!=undefined)
            {
                $BarId = jQuery("#bar_bar_id_"+i).val();
                $TableId = jQuery("#bar_table_id_"+i).val();
                $Indate = jQuery("#bar_in_date_"+i).val();
                $TimeIn = calc_time_js(jQuery("#bar_time_in_"+i).val());
                $TimeOut = calc_time_js(jQuery("#bar_time_out_"+i).val());
                
                for(var j=i+1;j<=Bar_InputCount;j++)
                {
                    if(jQuery("#bar_id_"+j).val()!=undefined)
                    {
                        $BarId_T = jQuery("#bar_bar_id_"+j).val();
                        $TableId_T = jQuery("#bar_table_id_"+j).val();
                        $Indate_T = jQuery("#bar_in_date_"+j).val();
                        $TimeIn_T = calc_time_js(jQuery("#bar_time_in_"+j).val());
                        $TimeOut_T = calc_time_js(jQuery("#bar_time_out_"+j).val());
                        if($BarId==$BarId_T && $TableId==$TableId_T && $Indate==$Indate_T)
                        {
                            if($TimeIn>$TimeOut_T || $TimeOut<$TimeIn_T)
                            {
                                // khong trung
                            }
                            else
                            {
                                $check['value'] = false;
                                if(list_bar_tables[$TableId_T])
                                    $check['data'][list_bar_tables[$TableId_T]['name']] = list_bar_tables[$TableId_T]['name'];
                            }
                        }
                    }
                }
            }
        }
        return $check;
        
    }
    function calc_time_js($string)
    {
        $arr = $string.split(':');
        return ($arr[0]*3600 + $arr[1]*60);
    }
    function CheckSubmit(act)
    {
        error = '';
        count = 0;
        /** check SPA **/
        error_spa = '';
        for(var i=100;i<=Spa_InputCount;i++)
        {
            count_service = 0;
            count_service_child = 0;
            if(jQuery("#spa_id_"+i).val()!=undefined)
            {
                count++;
                count_service ++;
                for(var j=100;j<=Spa_InputCount_Product;j++)
                {
                    if(jQuery("#spa_"+i+"_child_service_id_"+j).val()!=undefined)
                    {
                        count_service_child++;
                       if(jQuery("#spa_"+i+"_child_product_product_id_"+j).val()=='')
                       {
                            error_spa += '<br/><span><i class="fa fa-dot-circle-o fa-fw"></i>Chua chon san pham</span>';
                       }
                       if(jQuery("#spa_"+i+"_child_product_in_date_"+j).val()=='')
                       {
                            error_spa += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay su dung san pham</span>';
                       }
                       if(jQuery("#spa_"+i+"_child_product_time_in_"+j).val()=='')
                       {
                            error_spa += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon gio bat dau su dung san pam</span>';
                       }
                       if(jQuery("#spa_"+i+"_child_product_time_out_"+j).val()=='')
                       {
                            error_spa += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon gio ket thuc su dung san pham</span>';
                       }
                       if(to_numeric(jQuery("#spa_"+i+"_child_product_quantity_"+j).val())==0)
                       {
                            error_spa += '<br/><span><i class="fa fa-cubes fa-fw"></i>Chua nhap so luong san pham</span>';
                       }
                       //if(to_numeric(jQuery("#spa_"+i+"_child_product_price_"+j).val())==0)
                       //{
                            //error_spa += '<br/><span><i class="fa fa-usd fa-fw"></i>Chua nhap gia san pham</span>';
                       //}
                       
                       if(error_spa!='')
                            break;
                    }
                }
                for(var j=100;j<=Spa_InputCount_Service;j++)
                {
                    if(jQuery("#spa_"+i+"_child_service_product_id_"+j).val()!=undefined)
                    {
                        count_service_child++;
                        if(jQuery("#spa_"+i+"_child_service_product_id_"+j).val()=='')
                        {
                            error_spa += '<br/><span><i class="fa fa-dot-circle-o fa-fw"></i>Chua chon dich vu</span>';
                        }
                        if(jQuery("#spa_"+i+"_child_service_spa_room_id_"+j).val()=='')
                        {
                            error_spa += '<br/><span><i class="fa fa-bed fa-fw"></i>Chua chon phong</span>';
                        }
                        if(jQuery("#spa_"+i+"_child_service_staff_ids_"+j).val()=='')
                        {
                            error_spa += '<br/><span><i class="fa fa-user fa-fw"></i>Chua chon nhan vien</span>';
                        }
                        if(jQuery("#spa_"+i+"_child_service_in_date_"+j).val()=='')
                        {
                            error_spa += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay su dung dich vu</span>';
                        }
                        if(jQuery("#spa_"+i+"_child_service_time_in_"+j).val()=='')
                        {
                            error_spa += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon gio bat dau su dung dich vu</span>';
                        }
                        if(jQuery("#spa_"+i+"_child_service_time_out_"+j).val()=='')
                        {
                            error_spa += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon gio ket thuc su dung dich vu</span>';
                        }
                        if(to_numeric(jQuery("#spa_"+i+"_child_service_quantity_"+j).val())==0)
                        {
                            error_spa += '<br/><span><i class="fa fa-cubes fa-fw"></i>Chua nhap so luong dich vu</span>';
                        }
                        //if(to_numeric(jQuery("#spa_"+i+"_child_service_price_"+j).val())==0)
                        //{
                            //error_spa += '<br/><span><i class="fa fa-usd fa-fw"></i>Chua nhap gia dich vu</span>';
                        //}
                        if(error_spa!='')
                            break;
                    }
                }
            }
            if(count_service>0 && count_service_child==0)
                error_spa += '<br/><h4>Ban chua them san pham cho SPA</h4>';
            
        }
        if(error_spa!='')
            error += '<h3>SPA</h3>'+error_spa+'<br/>';
        /** END SPA **/
        
        /** PARTY **/
        error_party = '';
        for(var i=100;i<=Party_InputCount;i++)
        {
            count_service = 0;
            count_service_child = 0;
            if(jQuery("#party_id_"+i).val()!=undefined)
            {
                count++;
                count_service++;
                if(jQuery("#party_party_type_"+i).val()=='')
                {
                    error_party += '<br/><span><i class="fa fa-beer fa-fw"></i>Chua chon loai tiec</span>';
                }
                if(jQuery("#party_in_date_"+i).val()=='')
                {
                    error_party += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay su dung tiec</span>';
                }
                if(jQuery("#party_time_in_"+i).val()=='')
                {
                    error_party += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon gio bat dau su dung tiec</span>';
                }
                if(jQuery("#party_time_out_"+i).val()=='')
                {
                    error_party += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon gio ket thuc tiec</span>';
                }
                for(var j=100;j<=Party_InputCount_Product;j++)
                {
                    if(jQuery("#party_"+i+"_child_product_id_"+j).val()!=undefined)
                    {
                        count_service_child++;
                        if(jQuery("#party_"+i+"_child_product_product_id_"+j).val()=='')
                        {
                            error_party += '<br/><span><i class="fa fa-dot-circle-o fa-fw"></i>Chua chon san pham</span>';
                        }
                        if(to_numeric(jQuery("#party_"+i+"_child_product_quantity_"+j).val())==0)
                        {
                            error_party += '<br/><span><i class="fa fa-cubes fa-fw"></i>Chua nhap so luong san pham</span>';
                        }
                        //if(to_numeric(jQuery("#party_"+i+"_child_product_price_"+j).val())<0)
                        //{
                            //error_party += '<br/><span><i class="fa fa-usd fa-fw"></i>Nhap sai gia san pham</span>';
                        //}
                        if(error_party!='')
                            break;
                    }
                    
                }
                for(var j=100;j<=Party_InputCount_Room;j++)
                {
                    if(jQuery("#party_"+i+"_child_room_id_"+j).val()!=undefined)
                    {
                        count_service_child++;
                        if(jQuery("#party_"+i+"_child_room_party_room_id_"+j).val()=='')
                        {
                            error_party += '<br/><span><i class="fa fa-bed fa-fw"></i>Chua chon phong</span>';
                        }
                        //if(to_numeric(jQuery("#party_"+i+"_child_room_price_"+j).val())<0)
                        //{
                            //error_party += '<br/><span><i class="fa fa-usd fa-fw"></i>Nhap sai gia Phong</span>';
                        //}
                        if(error_party!='')
                            break;
                    }
                }
                
                if(error_party!='')
                    break;
            }
            //if(count_service>0 && count_service_child==0)
                //error_party += '<br/><h4>Ban chua them dich vu cho PARTY</h4>';
        }
        if(error_party!='')
            error += '<h3>PARTY</h3>'+error_party+'<br/>';
        /** END PARTY **/
        
        /** EXTRA **/
        error_extra = '';
        for(var i=100;i<=Extra_InputCount;i++)
        {
            
            if(jQuery("#extra_id_"+i).val()!=undefined)
            {
                count++;
                if(jQuery("#extra_service_id_"+i).val()=='')
                {
                    error_extra += '<br/><span><i class="fa fa-bed fa-fw"></i>Chua chon Dich vu</span>';
                }
                if(to_numeric(jQuery("#extra_quantity_"+i).val())==0)
                {
                    error_extra += '<br/><span><i class="fa fa-cubes fa-fw"></i>Chua nhap so luong dich vu</span>';
                }
                if(jQuery("#extra_start_date_"+i).val()=='')
                {
                    error_extra += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay</span>';
                }
                if(jQuery("#extra_end_date_"+i).val()=='')
                {
                    error_extra += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay</span>';
                }
                //if(to_numeric(jQuery("#extra_price_"+i).val())==0)
                //{
                    //error_extra += '<br/><span><i class="fa fa-usd fa-fw"></i>Chua nhap gia</span>';
                //}
                if(error_extra!='')
                    break;
            }
        }
        if(error_extra!='')
            error += '<h3>EXTRA SERVICE</h3>'+error_extra+'<br/>';
        /** END EXTRA **/
        
        
        /** BOOKING **/
        error_booking = '';
        for(var i=100;i<=Booking_InputCount;i++)
        {
            
            if(jQuery("#booking_id_"+i).val()!=undefined)
            {
                count++;
                if(jQuery("#booking_room_level_id_"+i).val()=='')
                {
                    error_booking += '<br/><span><i class="fa fa-bed fa-fw"></i>Chua chon hang phong</span>';
                }
                if(to_numeric(jQuery("#booking_quantity_"+i).val())==0)
                {
                    error_booking += '<br/><span><i class="fa fa-cubes fa-fw"></i>Chua nhap so luong phong</span>';
                }
                if(jQuery("#booking_time_in_"+i).val()=='')
                {
                    error_booking += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua nhap gio den</span>';
                }
                if(jQuery("#booking_from_date_"+i).val()=='')
                {
                    error_booking += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay den</span>';
                }
                if(jQuery("#booking_time_out_"+i).val()=='')
                {
                    error_booking += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua nhap gio di</span>';
                }
                if(jQuery("#booking_to_date_"+i).val()=='')
                {
                    error_booking += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay di</span>';
                }
                //if(to_numeric(jQuery("#booking_price_"+i).val())==0)
                //{
                    //error_booking += '<br/><span><i class="fa fa-usd fa-fw"></i>Chua nhap gia phong</span>';
                //}
                if(error_booking!='')
                    break;
            }
        }
        if(error_booking!='')
            error += '<h3>BOOKING</h3>'+error_booking+'<br/>';
        /** END BOOKING **/
        
        /** BAR **/
        error_bar = '';
        for(var i=100;i<=Bar_InputCount;i++)
        {
            count_service = 0;
            count_service_child = 0;
            if(jQuery("#bar_id_"+i).val()!=undefined)
            {
                count++;
                count_service++;
                if(jQuery("#bar_bar_id_"+i).val()=='')
                {
                    error_bar += '<br/><span><i class="fa fa-building fa-fw"></i>Chua chon nha hang</span>';
                }
                if(jQuery("#bar_table_id_"+i).val()=='')
                {
                    error_bar += '<br/><span><i class="fa fa-building fa-fw"></i>Chua chon ban</span>';
                }
                if(jQuery("#bar_in_date_"+i).val()=='')
                {
                    error_bar += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay dat ban</span>';
                }
                if(jQuery("#bar_time_in_"+i).val()=='')
                {
                    error_bar += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua nhap gio den</span>';
                }
                if(jQuery("#bar_time_out_"+i).val()=='')
                {
                    error_bar += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua nhap gio di</span>';
                }
                for(var j=100;j<=Bar_InputCount_Product;j++)
                {
                    if(jQuery("#bar_"+i+"_child_id_"+j).val()!=undefined)
                    {
                        count_service_child++;
                        if(jQuery("#bar_"+i+"_child_product_id_"+j).val()=='')
                        {
                            error_bar += '<br/><span><i class="fa fa-dot-circle-o fa-fw"></i>Chua chon san pham</span>';
                        }
                        if(to_numeric(jQuery("#bar_"+i+"_child_quantity_"+j).val())==0)
                        {
                            error_bar += '<br/><span><i class="fa fa-cubes fa-fw"></i>Chua nhap so luong san pham</span>';
                        }
                        //if(to_numeric(jQuery("#bar_"+i+"_child_price_"+j).val())==0)
                        //{
                            //error_bar += '<br/><span><i class="fa fa-usd fa-fw"></i>Chua nhap gia san pham</span>';
                        //}
                        if(error_bar!='')
                            break;
                    }
                }
                if(error_bar!='')
                    break;
            }
            if(count_service>0 && count_service_child==0)
                error_bar += '<br/><h4>Ban chua them san pham cho BAR</h4>';
        }
        if(error_bar!='')
            error += '<h3>BAR</h3>'+error_bar+'<br/>';
        /** END BAR **/
        
        /** VENDING **/
        error_vending = '';
        
        for(var i=100;i<=Vending_InputCount;i++)
        {
            count_service = 0;
            count_service_child = 0;
            if(jQuery("#vending_id_"+i).val()!=undefined)
            {
                count++;
                count_service++;
                if(jQuery("#vending_department_id_"+i).val()=='')
                {
                    error_vending += '<br/><span><i class="fa fa-building fa-fw"></i>Chua chon quay</span>';
                }
                if(jQuery("#vending_in_date_"+i).val()=='')
                {
                    error_vending += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay ban hang</span>';
                }
                if(jQuery("#vending_time_in_"+i).val()=='')
                {
                    error_vending += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua nhap gio bat dau ban hang</span>';
                }
                for(var j=100;j<=Vending_InputCount_Product;j++)
                {
                    if(jQuery("#vending_"+i+"_child_id_"+j).val()!=undefined)
                    {
                        count_service_child++;
                        if(jQuery("#vending_"+i+"_child_product_id_"+j).val()=='')
                        {
                            error_vending += '<br/><span><i class="fa fa-dot-circle-o fa-fw"></i>Chua chon san pham</span>';
                        }
                        if(to_numeric(jQuery("#vending_"+i+"_child_quantity_"+j).val())==0)
                        {
                            error_vending += '<br/><span><i class="fa fa-cubes fa-fw"></i>Chua nhap so luong san pham</span>';
                        }
                        //if(to_numeric(jQuery("#vending_"+i+"_child_price_"+j).val())==0)
                        //{
                            //error_vending += '<br/><span><i class="fa fa-usd fa-fw"></i>Chua nhap gia san pham</span>';
                        //}
                        if(error_vending!='')
                            break;
                    }
                }
                if(error_vending!='')
                    break;
            }
            if(count_service>0 && count_service_child==0)
                error_vending += '<br/><h4>Ban chua them san pham cho VENDING</h4>';
        }
        if(error_vending!='')
            error += '<h3>VENDING</h3>'+error_vending+'<br/>';
        /** END vending **/
        
        /** TICKET **/
        error_ticket = '';
        for(var i=100;i<=Extra_InputCount;i++)
        {
            
            if(jQuery("#ticket_id_"+i).val()!=undefined)
            {
                count++;
                if(jQuery("#ticket_ticket_area_id_"+i).val()=='')
                {
                    error_ticket += '<br/><span><i class="fa fa-building fa-fw"></i>Chua chon Khu ban ve</span>';
                }
                if(jQuery("#ticket_ticket_id_"+i).val()=='')
                {
                    error_ticket += '<br/><span><i class="fa fa-file fa-fw"></i>Chua chon ve</span>';
                }
                if(to_numeric(jQuery("#ticket_quantity_"+i).val())==0)
                {
                    error_ticket += '<br/><span><i class="fa fa-cubes fa-fw"></i>Chua nhap so luong dich vu</span>';
                }
                if(jQuery("#ticket_date_used_"+i).val()=='')
                {
                    error_ticket += '<br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Chua chon ngay</span>';
                }
                //if(to_numeric(jQuery("#ticket_price_"+i).val())==0)
                //{
                    //error_ticket += '<br/><span><i class="fa fa-usd fa-fw"></i>Chua nhap gia</span>';
                //}
                if(error_ticket!='')
                    break;
            }
        }
        if(error_ticket!='')
            error += '<h3>TICKET</h3>'+error_ticket+'<br/>';
        /** END TICKET **/
        
        if(error!='')
        {
            alert(error);
            return false;
        }
        else
        {
            if(count==0)
            {
                alert('<h3>MICE</h3><span>Vui long chon dich vu cho mice</span>');
                return false;
            }
            else
            {
                $checkBar = checkbarconflig();
                if($checkBar['value']==false)
                {
                    $listbarconflict = '';
                    for(var cf in $checkBar['data'])
                    {
                        $listbarconflict += '<br/>+<span>'+$checkBar['data'][cf]+'</span>';
                    }
                    alert('<h3>BAR</h3><span>Dat ban trung thoi gian (conflict time)</span>'+$listbarconflict);
                    return false;
                }
                else
                {
                    $listinfomation = '';
                    if(act=='CONFIRM')
                    {
                        if(jQuery("#customer_id").val()=='')
                        {
                            $listinfomation += '<br/>+<span>Chua chon nguon khach</span>'
                        }
                        if(jQuery("#customer_id").val()=='')
                        {
                            $listinfomation += '<br/>+<span>chua chon khach hang</span>'
                        }
                    }
                    if($listinfomation!='')
                    {
                        alert('<h3>THONG TIN CHUNG</h3>'+$listinfomation);
                        return false;
                    }
                    else
                    {
                        jQuery("#act").val(act);
                        EditMiceReservationForm.submit();
                    }
                    
                }
            }
        }
        
    }
</script>
