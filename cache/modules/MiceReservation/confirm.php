<style>
    .simple-layout-middle{width:100%;}
    .simple-layout-content {
        background: #EEEEEE;
    }
    div {
        margin: 0px;
        padding: 0px;
    }
    #MiceReservationBody {
        width: 960px;
        height: auto;
        padding: 10px;
        margin: 5px auto;
        background: #FFFFFF;
        box-shadow: 0px 1px 4px 0px rgba(0,0,0,0.2);
    }
    #MiceReservationHeader,#MiceReservationContainer,#MiceReservationFooter {
        width: 100%;
        height: auto;
        padding: 0px;
        margin: 1px auto;
        color: #656565;
        font-weight: normal;
    }
    #MiceReservationHeader {
        border-bottom: 1px solid #EEEEEE;
    }
    #MiceReservationContainer {
        border-bottom: 1px solid #EEEEEE;
        border-top: 1px solid #EEEEEE;
    }
    #MiceReservationFooter {
        border-top: 1px solid #EEEEEE;
    }
    #MiceReservationHeader table {
        width: 100%;
    }
    #MiceReservationHeader table tr td:first-child {
        text-align: left;
    }
    #MiceReservationHeader table tr td:last-child {
        text-align: right;
    }
    #MiceReservationInfoContact,#MiceReservationInfoService,#MiceReservationInfoPayment {
        width: 100%;
        height: auto;
        margin: 5px auto;
        clear: both;
    }
    #MiceReservationInfoContact table tr td,#MiceReservationInfoService table tr td,#MiceReservationInfoPayment table tr td {
        color: #656565;
    }
    #MiceReservationInfoContact table tr td input,#MiceReservationInfoPayment table tr td input {
        border: none;
        border-bottom: 1px dashed #CCCCCC;
        width: 150px;
        padding: 5px;
        height: 25px;
        color: #828282;
    }
    #MiceReservationInfoContact table tr td input:hover,#MiceReservationInfoPayment table tr td input:hover {
        border-bottom: 1px dashed #00B2F9;
    }
    .DivTitle {
        float: left;
        height: 50px;
        width: auto;
        padding: 5px;
    }
    .DivTitle h1 {
        line-height: 50px;
        padding: 0px;
        margin: 0px;
        text-transform: uppercase;
        margin-left: 5px;
        float: left;
        color: #ff5353;
    }
    .DivTitle img {
        width: 40px;
        height: 40px;
        padding: 0px;
        margin: 5px;
        float: left;
    }
    .DivButton {
        float: right;
        height: 30px;
        padding: 0px 10px 0px 0px;
        background: #FFFFFF;
        box-shadow: 0px 0px 3px #CCCCCC;
        margin-left: 10px;
        cursor: pointer;
        transition: all 0.3s ease-out;
        overflow: hidden;
    }
    .DivButton:hover {
        background: #ff9393;
    }
    .DivButton .DivButtonIcon {
        width: 30px;
        height: 30px;
        transition: all 0.3s ease-out;
        float: left;
        background: #F7F7F7;
        padding: 7.5px;
    }
    .DivButton:hover .DivButtonIcon {
        background: #FFFFFF;
    }
    .DivButton .DivButtonIcon img {
        width: 15px;
        height: 15px;
        margin: 0px;
    }
    .DivButton span {
        line-height: 30px;
        text-transform: uppercase;
        padding: 0px;
        margin: 0px;
        margin-left: 5px;
        float: left;
        transition: all 0.3s ease-out;
        color: #555555;
    }
    .DivButton:hover span {
        color: #ffffff;
    }
    .MiceReservationContainerTitle {
        color: #ff7b7b;
        line-height: 30px;
        height: 30px;
        margin: 0px;
        margin-top: 5px;
        padding: 0px;
        padding-left: 5px;
        width: 240px;
        text-transform: uppercase;
        font-weight: normal;
    }
    .Department {
        color: #656565;
        float: left;
        display: block;
        transition: all 0.2s ease-in-out;
    }
    .DepartmentHide {
        background: #FFFFFF;
        width: 230px;
        height: 60px;
        margin: 30px;
        padding: 10px;
        position: relative;
        box-shadow: 0px 1px 4px 0px rgba(0,0,0,0.7);
    }
    .DepartmentShow {
        background: #F7F7F7;
        width: 98%;
        margin: 10px auto;
        padding: 1%;
        border: 1px solid #EEEEEE;
        box-shadow: inset 0px 0px 3px #CCCCCC;
    }
    .DepartmentHide .DepartmentIcon, .DepartmentShow .DepartmentIcon {
        width: 30px;
        height: 30px;
        float: left;
        margin: 0px;
        padding: 0px;
        padding-right: 10px;
    }
    .DepartmentHide .DepartmentIcon img, .DepartmentShow .DepartmentIcon img {
        width: 30px;
        height: 30px;
        display: block;
        margin: 0px;
        padding: 0px;
    }
    .DepartmentHide .DepartmentName, .DepartmentShow .DepartmentName {
        width: 170px;
        height: 30px;
        line-height: 15px;
        color: #555555;
        float: left;
        margin: 0px;
        padding: 0px;
    }
    .DepartmentHide .DepartmentName span, .DepartmentShow .DepartmentName span {
        font-size: 10px;
        color: #828282;
    }
    .DepartmentHide .DepartmentControl, .DepartmentShow .DepartmentControl {
        width: 20px;
        height: 30px;
        line-height: 30px;
        font-size: 20px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 10px;
        margin: 0px;
        padding: 0px;
        text-align: center;
        cursor: pointer;
        background: #FFFFFF;
        transition: all 0.3s ease-out;
    }
    .DepartmentHide .DepartmentControl:hover, .DepartmentShow .DepartmentControl:hover {
        background: #EEEEEE;
    }
    .DepartmentHide .DepartmentMessenger, .DepartmentShow .DepartmentMessenger {
        width: auto;
        height: 20px;
        line-height: 20px;
        font-size: 15px;
        margin: 0px;
        padding: 0px 5px;
        text-align: center;
        background: #ff5353;
        color: #FFFFFF;
        border-radius: 10px;
        position: absolute;
        top: -10.5px;
        right: -10.5px;
        display: block;
    }
    .DepartmentHide .DepartmentItems {
        display: none;
    }
    .DepartmentShow .DepartmentItems {
        width: 100%;
        margin: 10px auto;
        float: left;
        clear: both;
    }
    .DepartmentItems .items {
        background: #FFFFFF;
        margin: 10px;
        padding: 0px;
        float: left;
        border: 1px solid #EEEEEE;
    }
    .BOOKED {
        border-left: 5px solid #EEEEEE;
        border-right: 1px solid #EEEEEE;
    }
    .CHECKIN {
        border-left: 5px solid #379dfc;
        border-right: 1px solid #EEEEEE;
        
    }
    .CHECKOUT {
        border-left: 5px solid #ff4848;
        border-right: 1px solid #EEEEEE;
    }
    .CANCEL {
        border-left: 5px solid #000000;
        border-right: 1px solid #EEEEEE;
    }
</style>
<form name="ConfirmMiceReservationForm" method="POST">
    <input id="act" name="act" type="text" value="" style="display: none;" />
    <input id="save" name="save" type="checkbox" value="save" style="display: none;" />
    <div id="MiceReservationBody">
        <div id="MiceReservationHeader">
            <table>
                <tr>
                    <td>
                        <div class="DivTitle">
                            <img src="packages/hotel/packages/mice/skins/img/service.png" />
                            <h1><?php echo Portal::language('detail');?> MICE</h1>
                        </div>
                    </td>
                    <td>
                        <?php if($this->map['check_un_confirm']==1){ ?>
                        <div class="DivButton" onclick="if(confirm('<?php echo Portal::language('are_you_delete_all_booking_for_mice');?> \n <?php echo Portal::language('are_you_un_confirm_mice');?>')){jQuery('#act').val('UN_CONFIRM'); ConfirmMiceReservationForm.submit();}else{return false;}">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/confirm.png" /></div>
                            <span><?php echo Portal::language('un_confirm');?></span>
                        </div>
                        <?php } ?>
                        <?php if($this->map['check_close_mice']==1 AND User::can_admin($this->get_module_id('CheckCloseOpenMice'),ANY_CATEGORY)){ ?>
                            <?php if($this->map['close']==0){ ?>
                            <div class="DivButton" onclick="if(confirm('<?php echo Portal::language('are_you_close_mice');?>')){jQuery('#act').val('CLOSE'); ConfirmMiceReservationForm.submit();}else{return false;}">
                                <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/confirm.png" /></div>
                                <span><?php echo Portal::language('close_mice');?></span>
                            </div>
                            <?php }else{ ?>
                            <div class="DivButton" onclick="if(confirm('<?php echo Portal::language('are_you_open_mice');?>')){jQuery('#act').val('OPEN'); ConfirmMiceReservationForm.submit();}else{return false;}">
                                <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/confirm.png" /></div>
                                <span><?php echo Portal::language('open_mice');?></span>
                            </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="DivButton" onclick="window.location.href='?page=mice_reservation'">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/back.png" /></div>
                            <span><?php echo Portal::language('back');?></span>
                        </div>
                        <div class="DivButton" onclick="window.location.href='?page=mice_reservation&cmd=beoform&id=<?php echo Url::get('id'); ?>'">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/report.png" /></div>
                            <span><?php echo Portal::language('view_beo');?></span>
                        </div>
                        <?php if($this->map['check_un_confirm']!=1 or User::can_add(false,ANY_CATEGORY)){ ?>
                        <div class="DivButton" onclick="openinvoice('<?php echo Url::get('id'); ?>');">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/invoice.png" /></div>
                            <span><?php echo Portal::language('payment');?></span>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
        <div id="MiceReservationContainer">
            <div id="MiceReservationInfoContact">
                <div class="MiceReservationContainerTitle"><?php echo Portal::language('infomation_contact');?></div>
                <table cellpadding="10" style="width: 100%;">
                    <tr>
                        <td style="width: 250px;"><?php echo Portal::language('contact_person');?>:</td>
                        <td style="border-right: 1px solid #EEEEEE;"><?php echo $this->map['contact_name'];?></td>
                        <td style="width: 250px;"><?php echo Portal::language('customer_name');?></td>
                        <td><?php echo $this->map['customer_name'];?></td>
                    </tr>
                    <tr>
                        <td style="width: 250px;"><?php echo Portal::language('contact_phone');?>:</td>
                        <td style="border-right: 1px solid #EEEEEE;"><?php echo $this->map['contact_phone'];?></td>
                        <td style="width: 250px;"><?php echo Portal::language('traveller_name');?></td>
                        <td><?php echo $this->map['traveller_name'];?></td>
                    </tr>
                    <tr>
                        <td style="width: 250px;"><?php echo Portal::language('contact_email');?>:</td>
                        <td style="border-right: 1px solid #EEEEEE;"><?php echo $this->map['contact_email'];?></td>
                        <td style="width: 250px;"><?php echo Portal::language('create_date');?></td>
                        <td><?php echo $this->map['code_mice'];?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #EEEEEE;">
                        <td style="width: 250px;"><?php echo Portal::language('start_date');?>:</td>
                        <td style="border-right: 1px solid #EEEEEE;"><?php echo $this->map['start_date'];?></td>
                        <td style="width: 250px;"><?php echo Portal::language('end_date');?></td>
                        <td><?php echo $this->map['end_date'];?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #EEEEEE;">
                        <td style="width: 250px;"><?php echo Portal::language('cut_of_date');?>:</td>
                        <td style="border-right: 1px solid #EEEEEE;"><?php echo $this->map['cut_of_date'];?></td>
                        <td style="width: 250px;"><?php echo Portal::language('code_mice');?></td>
                        <td>MICE+<?php echo Url::get('id'); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><?php echo Portal::language('note');?>:<br /><?php echo $this->map['note'];?></td>
                    </tr>
                </table>
            </div>
            <div id="MiceReservationInfoService">
                <div class="MiceReservationContainerTitle"><?php echo Portal::language('infomation_service');?></div>
                <table cellpadding="10" style="width: 100%;">
                    <tr>
                        <td>
                            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                                <div id="MiceDepartment_<?php echo $this->map['items']['current']['id'];?>" class="Department <?php if($this->map['items']['current']['count_item']>0){ ?>DepartmentShow<?php }else{ ?>DepartmentHide<?php } ?>" >
                                    <input id="SH_<?php echo $this->map['items']['current']['id'];?>" type="checkbox" style="display: none;" <?php if($this->map['items']['current']['count_item']>0){ ?>checked="checked"<?php } ?> />
                                    <div class="DepartmentIcon"><img src="<?php echo $this->map['items']['current']['icon'];?>" title="<?php echo $this->map['items']['current']['name'];?>" /></div>
                                    <div class="DepartmentName"><?php echo $this->map['items']['current']['name'];?><br /><span><?php echo $this->map['items']['current']['description'];?></span></div>
                                    <div class="DepartmentControl" onclick="if(jQuery('#SH_<?php echo $this->map['items']['current']['id'];?>').attr('checked')=='checked'){ jQuery('#SH_<?php echo $this->map['items']['current']['id'];?>').removeAttr('checked'); jQuery('#MiceDepartment_<?php echo $this->map['items']['current']['id'];?>').removeClass('DepartmentShow'); jQuery('#MiceDepartment_<?php echo $this->map['items']['current']['id'];?>').addClass('DepartmentHide'); jQuery(this).html('+'); }else{ jQuery('#SH_<?php echo $this->map['items']['current']['id'];?>').attr('checked','checked'); jQuery('#MiceDepartment_<?php echo $this->map['items']['current']['id'];?>').removeClass('DepartmentHide'); jQuery('#MiceDepartment_<?php echo $this->map['items']['current']['id'];?>').addClass('DepartmentShow'); jQuery(this).html('_'); }"><?php if($this->map['items']['current']['count_item']>0){ ?>_<?php }else{ ?>+<?php } ?></div>
                                    <div class="DepartmentMessenger"><?php echo $this->map['items']['current']['count_item'];?></div>
                                    <div class="DepartmentItems">
                                        <?php if(isset($this->map['items']['current']['item']) and is_array($this->map['items']['current']['item'])){ foreach($this->map['items']['current']['item'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['item']['current'] = &$item2;?>
                                            <div class="items">
                                                <table cellpadding="10">
                                                    <tr>
                                                        <td class="<?php echo $this->map['items']['current']['item']['current']['status'];?>">
                                                            <?php if($this->map['items']['current']['id'] == 'REC'){ ?>
                                                            <?php echo $this->map['items']['current']['item']['current']['id'];?> - <?php echo $this->map['items']['current']['item']['current']['room_name'];?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'BANQUET'){ ?>
                                                            <?php echo $this->map['items']['current']['item']['current']['id'];?> - <?php echo $this->map['items']['current']['item']['current']['party_name'];?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'VENDING'){ ?>
                                                            <?php echo $this->map['items']['current']['item']['current']['id'];?> - <?php echo $this->map['items']['current']['item']['current']['department_code'];?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'RES'){ ?>
                                                            <?php echo $this->map['items']['current']['item']['current']['id'];?> - <?php echo $this->map['items']['current']['item']['current']['table_name'];?> (<?php echo $this->map['items']['current']['item']['current']['bar_name'];?>)
                                                            <?php }elseif($this->map['items']['current']['id'] == 'EXS'){ ?>
                                                            <?php echo $this->map['items']['current']['item']['current']['id'];?> - <?php echo $this->map['items']['current']['item']['current']['service_name'];?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'TICKET'){ ?>
                                                            <?php echo $this->map['items']['current']['item']['current']['id'];?> - <?php echo $this->map['items']['current']['item']['current']['service_name'];?>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php if($this->map['items']['current']['id'] == 'REC'){ ?>
                                                                <?php echo date('H:i d/m/Y',$this->map['items']['current']['item']['current']['time_in']).' - '.date('H:i d/m/Y',$this->map['items']['current']['item']['current']['time_out']); ?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'BANQUET'){ ?>
                                                                <?php echo date('H:i',$this->map['items']['current']['item']['current']['checkin_time']).' - '.date('H:i d/m/Y',$this->map['items']['current']['item']['current']['checkout_time']); ?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'VENDING'){ ?>
                                                                <?php echo date('H:i d/m/Y',$this->map['items']['current']['item']['current']['time_in']); ?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'RES'){ ?>
                                                                <?php echo date('H:i',$this->map['items']['current']['item']['current']['arrival_time']).' - '.date('H:i d/m/Y',$this->map['items']['current']['item']['current']['departure_time']); ?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'EXS'){ ?>
                                                                <?php echo $this->map['items']['current']['item']['current']['date'];?>
                                                            <?php }elseif($this->map['items']['current']['id'] == 'TICKET'){ ?>
                                                                <?php echo $this->map['items']['current']['item']['current']['date'];?>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?php echo Portal::language('total_amount');?>: <?php echo System::display_number($this->map['items']['current']['item']['current']['total']); ?></td>
                                                        <td>
                                                            <?php if($this->map['items']['current']['id'] != 'EXS'){ ?>
                                                                <?php echo Portal::language('status');?>: 
                                                                <?php if($this->map['items']['current']['id'] != 'TICKET'){ ?>
                                                                    <?php echo $this->map['items']['current']['item']['current']['status'];?>
                                                                <?php }else{ ?> 
                                                                    <?php if($this->map['items']['current']['item']['current']['status']=='CHECKOUT'){ ?>
                                                                        <?php echo Portal::language('exporting_ticket');?>
                                                                    <?php }else{ ?>
                                                                        <?php echo Portal::language('waiting_export_ticket');?>
                                                                    <?php } ?> 
                                                                <?php } ?> 
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php if($this->map['items']['current']['id'] == 'EXS'){ ?>
                                                            <select id="extra_service_<?php echo $this->map['items']['current']['item']['current']['id'];?>" onchange="UpdateExtraService('<?php echo $this->map['items']['current']['item']['current']['id'];?>',this.value);" name="extra_service[][reservation_room_id]" style="padding: 3px;"><?php echo $this->map['list_room_option'];?></select>
                                                            <script>jQuery("#extra_service_<?php echo $this->map['items']['current']['item']['current']['id'];?>").val('<?php echo $this->map['items']['current']['item']['current']['reservation_room_id'];?>')</script>
                                                            <?php } ?>
                                                        </td>
                                                        <td><a target="_blank" href="<?php echo $this->map['items']['current']['item']['current']['link'];?>"><img src="packages/hotel/packages/mice/skins/img/edit.png" /></a></td>
                                                        
                                                    </tr>
                                                </table>
                                            </div>
                                        <?php }}unset($this->map['items']['current']['item']['current']);} ?>
                                    </div>
                                </div>
                            <?php }}unset($this->map['items']['current']);} ?>
                        </td>
                    </tr>
                </table>
            </div>
            <?php if($_REQUEST['cmd']!='add'){ ?>
            <div id="MiceReservationInfoPayment">
                <div class="MiceReservationContainerTitle"><?php echo Portal::language('infomation_payment');?></div>
                <table cellpadding="10">
                    <!--<tr>
                        <td><?php echo Portal::language('total_amount');?>:</td>
                        <td style="vertical-align: bottom;"><input  name="total_amount" id="total_amount" style="text-align: right;" readonly="readonly" / type ="text" value="<?php echo String::html_normalize(URL::get('total_amount'));?>"></td>
                        <td></td>
                    </tr>-->
                    <tr>
                        <td><?php echo Portal::language('deposit');?>:</td>
                        <td style="vertical-align: bottom;">
                            <input  name="deposit" id="deposit" style="text-align: right;" readonly="readonly" / type ="text" value="<?php echo String::html_normalize(URL::get('deposit'));?>"> 
                        </td>
                        <td>
                            <?php if(User::can_edit(false,ANY_CATEGORY)){ ?>
                            <div class="DivButton" onclick="DepositMiceReservation();">
                                <span><?php echo Portal::language('deposit');?></span>
                            </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <!--<tr>
                        <td><?php echo Portal::language('remain');?>:</td>
                        <td style="vertical-align: bottom;">
                            <input  name="remain" id="remain" style="text-align: right;" readonly="readonly" / type ="text" value="<?php echo String::html_normalize(URL::get('remain'));?>">
                        </td>
                        <td>
                            <?php //if(User::can_edit(false,ANY_CATEGORY)){ ?>
                            <div class="DivButton" onclick="PaymentMiceReservation();">
                                <span><?php echo Portal::language('payment');?></span>
                            </div>
                            <?php //} ?>
                        </td>
                    </tr>-->
                </table>
            </div>
            <?php } ?>
        </div>
        <div id="MiceReservationFooter">
            <p>&copy; Copy Right <?php echo date('Y'); ?> By Newway</p>
        </div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<div id="mice_loading" style="display: none; width: 100%; height: 100%; z-index: 99; background: rgba(17,64,59,0.7); position: fixed; top: 0px; left: 0px;">
    <div style="width: 50px; height: 50px; margin: 250px auto;"><i class="fa fa-5x fa-spin fa-cog" style="color: #ff9393;"></i></div>
</div>
<script>
    var windownskey = 0;
    jQuery(document).ready(function(){
        jQuery(window).scroll(function(){
            //if(jQuery(this).scrollTop()>110){ CloseMenu(); jQuery(this).scrollTop((110+jQuery(this).scrollTop())) }else{ OpenMenu() }
        });
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
        var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=428&cmd=payment&id=<?php echo Url::get('id'); ?>&type=MICE&total_amount='+to_numeric(jQuery("#total_amount").val());
        var Wintitle = '<?php echo Portal::language('payment');?> MICE';
        FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
    }
    function DepositMiceReservation()
    {
        var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=428&cmd=deposit&id=<?php echo Url::get('id'); ?>&type=MICE';
        var Wintitle = '<?php echo Portal::language('deposit');?> MICE';
        FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
    }
    function openinvoice(id)
    {
        var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>?page=mice_reservation&cmd=invoice&id='+id;
        var Wintitle = '<?php echo Portal::language('invoice');?> MICE';
        FunOpenWindowns(WinSrc,Wintitle,1000,500,50,200);
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
    function UpdateExtraService(id,rr_id)
    {
        jQuery("#mice_loading").css('display','');
        jQuery.ajax({
					url:"get_mice.php?",
					type:"POST",
					data:{cmd:'update_extra',id:id,r_r_id:rr_id},
					success:function(html)
                    {
                       setTimeout(function(){
                            jQuery("#mice_loading").css('display','none');
                        }, 1000);
					}
		});
    }
</script>