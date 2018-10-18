<style>
    .simple-layout-middle {width:100%;}
    body {
        background: #EEEEEE;
    }
    .simple-layout-content {
         background: #EEEEEE;
         padding: 0px; 
         min-height: 100%;
         margin: 0px;
         border: none;
    }
    .icon-button {
        height: 30px;
        line-height: 30px;
        margin: 0px;
        padding: 0px 5px;
        border: 1px solid #EEEEEE;
        text-align: center;
        cursor: pointer;
        background: #3498db;
        color: #EEEEEE;
        font-weight: bold;
    }
    .icon-button i {
        line-height: 30px;
    }
    .icon-button > ul {
        display: none;
    }
    .icon-button:hover > ul {
        display: block;
    }
    .loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #3498db;
      width: 120px;
      height: 120px;
      margin: 200px auto;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
    }
    
    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
</style>
<div style="width: 90%; min-height: 600px; margin: 10px auto 40px; padding: 5px; background: #FFFFFF; border: 1px solid #DDDDDD;">
    <form name="MasterFolioEditForm" method="POST">
        <table style="width: 100%;" cellpadding="5" border="1" bordercolor="#EEEEEEE">
            <tr>
                <td style="text-align: center;">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <h2 style="text-transform: uppercase; color: #171717; font-weight: normal; line-height: 20px;">[[.create_folio.]]</h2>
                            </td>
                            <td style="width: 170px;">
                                <div class="icon-button" style="float: right;" onclick=""><i class="fa fa-fw fa-long-arrow-left"></i>[[.back_list.]]</div>
                                <div class="icon-button" style="float: right;" onclick=""><i class="fa fa-fw fa-plus"></i>[[.add.]]</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <fieldset style="border: none;">
                        <legend style="text-transform: uppercase; font-weight: bold;">[[.search_billing_data.]]</legend>
                        <table style="width: 100%;" cellpadding="5" border="1" bordercolor="#EEEEEEE">
                            <tr>
                                <td colspan="12">
                                    <span style="float: left;">
                                        <label for="reservation_id">Recode:</label> <input name="reservation_id" type="text" id="reservation_id" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 80px;" />
                                        <label for="room_name">[[.room.]]:</label> <input name="room_name" type="text" id="room_name" onfocus="Autocomplete('ROOM_NAME');" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 100px;" />
                                        <i class="fa fa-fw fa-remove" style="cursor: pointer; color: #fdc5c5;" onclick="RemoveDataSearch('ROOM_NAME');"></i>
                                        <input name="reservation_room_id" type="hidden" id="reservation_room_id" />
                                        <label for="traveller_name">[[.traveller.]]:</label> <input name="traveller_name" type="text" id="traveller_name" onfocus="Autocomplete('TRAVELLER_NAME');" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 200px;" />
                                        <i class="fa fa-fw fa-remove" style="cursor: pointer; color: #fdc5c5;" onclick="RemoveDataSearch('TRAVELLER_NAME');"></i>
                                        <label for="passport">[[.passport.]]:</label> <input name="passport" type="text" id="passport" onfocus="Autocomplete('PASSPORT');" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 100px;" />
                                        <i class="fa fa-fw fa-remove" style="cursor: pointer; color: #fdc5c5;" onclick="RemoveDataSearch('PASSPORT');"></i>
                                        <input name="reservation_traveller_id" type="hidden" id="reservation_traveller_id" />
                                    </span>
                                </td>
                                <td rowspan="2">
                                    <div class="icon-button" style="float: right;" onclick="SearchService();"><i class="fa fa-fw fa-search"></i>[[.get_data_payment.]]</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="check_all_service" type="checkbox" id="check_all_service" onclick="CheckAllService();" checked="checked" /> <label for="check_all_service">[[.all.]]</label>
                                </td>
                                <td>
                                    <input name="check_room_charge" type="checkbox" id="check_room_charge" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_room_charge">[[.room_charge.]]</label>
                                </td>
                                <td>
                                    <input name="check_ei_lo" type="checkbox" id="check_ei_lo" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_ei_lo">EI,LO</label>
                                </td>
                                <td>
                                    <input name="check_extra_bed" type="checkbox" id="check_extra_bed" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_extra_bed">[[.extra_bed.]]</label>
                                </td>
                                <td>
                                    <input name="check_breakfast" type="checkbox" id="check_breakfast" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_breakfast">[[.breakfast.]]</label>
                                </td>
                                <td>
                                    <input name="check_bar" type="checkbox" id="check_bar" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_bar">[[.food_and_drink_service.]]</label>
                                </td>
                                <td>
                                    <input name="check_minibar" type="checkbox" id="check_minibar" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_minibar">[[.minibar.]]</label>
                                </td>
                                <td>
                                    <input name="check_laundry" type="checkbox" id="check_laundry" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_laundry">[[.laundry.]]</label>
                                </td>
                                <td> 
                                    <input name="check_equipment" type="checkbox" id="check_equipment" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_equipment">[[.equipment.]]</label>
                                </td>
                                <td>
                                    <input name="check_tranfer" type="checkbox" id="check_tranfer" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_tranfer">[[.tranfer.]]</label>
                                </td>
                                <td>
                                    <input name="check_deposit" type="checkbox" id="check_deposit" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_deposit">[[.deposit_payment.]]</label>
                                </td>
                                <td> 
                                    <input name="check_other_service" type="checkbox" id="check_other_service" class="checkbox_items" onclick="CheckItemsService();" checked="checked" /> <label for="check_other_service">[[.service_other.]]</label>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <fieldset style="border: none;">
                        <legend style="text-transform: uppercase; font-weight: bold;">[[.payment_infomation.]]</legend>
                        <table style="width: 100%;" cellpadding="5">
                            <tr>
                                <td style="vertical-align: top;">
                                    <label for="customer_id">[[.select_customer.]] [[.payment.]]</label>
                                    <select name="customer_id" id="customer_id" style="padding: 5px; border: 1px solid #DDDDDD;"></select>
                                    <label for="reservation_traveller_id">[[.select_traveller.]] [[.payment.]]</label>
                                    <select name="reservation_traveller_id" id="reservation_traveller_id" style="padding: 5px; border: 1px solid #DDDDDD;"></select>
                                </td>
                                <td style="vertical-align: top;">
                                    <div class="icon-button" style="float: right; position: relative;">
                                        <i class="fa fa-fw fa-save"></i> [[.save_and.]] ... <i class="fa fa-fw fa-hand-o-down"></i>
                                        <ul style="list-style: none; position: absolute; width: 150px; bottom: -180px; right: 0px; background: #FFFFFF; padding: 0px; margin: 0px; border-radius: 3px; box-shadow: 0px 0px 3px #555555;">
                                            <li onclick="SaveFolio('ADD_CONTINUE');" style="line-height: 35px; padding: 0px 5px; margin: 0px; text-align: left; border-bottom: 1px solid #DDDDDD; color: #171717;"><i class="fa fa-fw fa-plus"></i> [[.add_continue.]]</li>
                                            <li onclick="SaveFolio('STAY');" style="line-height: 35px; padding: 0px 5px; margin: 0px; text-align: left; border-bottom: 1px solid #DDDDDD; color: #171717;"><i class="fa fa-fw fa-repeat"></i> [[.stay.]]</li>
                                            <li onclick="SaveFolio('PAYMENT');" style="line-height: 35px; padding: 0px 5px; margin: 0px; text-align: left; border-bottom: 1px solid #DDDDDD; color: #171717;"><i class="fa fa-fw fa-usd"></i> [[.payment.]]</li>
                                            <li onclick="SaveFolio('VIEW_INVOICE');" style="line-height: 35px; padding: 0px 5px; margin: 0px; text-align: left; border-bottom: 1px solid #DDDDDD; color: #171717;"><i class="fa fa-fw fa-file-text"></i> [[.view_invoice.]]</li>
                                            <li onclick="SaveFolio('BACK_LIST');" style="line-height: 35px; padding: 0px 5px; margin: 0px; text-align: left; color: #171717;"><i class="fa fa-fw fa-long-arrow-left"></i> [[.back_list.]]</li>
                                        </ul>
                                    </div>
                                    <div class="icon-button" style="float: right;" onclick=""><i class="fa fa-fw fa-file-text"></i>[[.view_invoice.]]</div>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; text-align: right;" colspan="2">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right;">
                                    
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 999;">
    <div class="loader"></div>
</div>
<script>
    
    <?php echo 'var block_id = '.Module::block_id().';';?>
    function SearchService(){
        OpenLoading();
        var reservation_id = jQuery("#reservation_id").val();
        var reservation_room_id = jQuery("#reservation_room_id").val();
        var reservation_traveller_id = jQuery("#reservation_traveller_id").val();
        var $data = {
                    select_service:1
                    ,reservation_id:reservation_id
                    ,reservation_room_id:reservation_room_id
                    ,reservation_traveller_id:reservation_traveller_id
                    };
        jQuery(".checkbox_items").each(function(){
            if(document.getElementById(this.id).checked==true)
                $data[this.id] = 1;
        });
        
        jQuery.ajax({
					url:  "form.php?block_id="+block_id,
					type: "POST",
                    dataType: "json",
					data: $data,
					success:function(html)
                    {
                        console.log(html);
                        CloseLoading();
					}
    		      });
    }
    function Autocomplete($key)
    {
        var reservation_id = jQuery("#reservation_id").val();
        var reservation_room_id = jQuery("#reservation_room_id").val();
        var reservation_traveller_id = jQuery("#reservation_traveller_id").val();
        if($key=='TRAVELLER_NAME'){
            jQuery("#traveller_name").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key,
                 onItemSelect: function(item){
                    console.log(item);
                    jQuery("#reservation_traveller_id").val(item.data[0]);
                    jQuery(".acResults").remove();
                    FindDataInput($key);
                }
            }) ;
        }else if($key=='PASSPORT'){
            jQuery("#passport").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key,
                 onItemSelect: function(item){
                    console.log(item);
                    jQuery("#reservation_traveller_id").val(item.data[0]);
                    jQuery(".acResults").remove();
                    FindDataInput($key);
                }
            }) ;
        }else if($key=='ROOM_NAME'){
            jQuery("#room_name").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key,
                 onItemSelect: function(item){
                    console.log(item);
                    jQuery("#reservation_room_id").val(item.data[0]);
                    jQuery(".acResults").remove();
                    FindDataInput($key);
                }
            }) ;
        }
    }
    function FindDataInput($key){
        var reservation_id = jQuery("#reservation_id").val();
        var reservation_room_id = jQuery("#reservation_room_id").val();
        var reservation_traveller_id = jQuery("#reservation_traveller_id").val();
        if($key=='TRAVELLER_NAME' || $key=='PASSPORT'){
            jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
                    dataType: "json",
					data:{find_data:1,type:$key,reservation_traveller_id:reservation_traveller_id},
					success:function(html)
                    {
                        jQuery("#reservation_id").val(html['reservation_id']);
                        if(to_numeric(reservation_room_id)!=to_numeric(html['reservation_room_id'])){
                            jQuery("#reservation_room_id").val(html['reservation_room_id']);
                            jQuery("#room_name").val(html['room_name']);
                        }
                        if($key=='TRAVELLER_NAME')
                            jQuery("#passport").val(html['passport']);
                        else
                            jQuery("#traveller_name").val(html['traveller_name']);
					}
            });
        }else if($key=='ROOM_NAME'){
            jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
                    dataType: "json",
					data:{find_data:1,type:$key,reservation_room_id:reservation_room_id},
					success:function(html)
                    {
                        jQuery("#reservation_id").val(html['reservation_id']);
                        var check = false;
                        for(var i in html['list_traveller']){
                            if(to_numeric(html['list_traveller'][i]['id'])==to_numeric(reservation_traveller_id))
                                check = true;
                        }
                        if(!check)
                            RemoveDataSearch('TRAVELLER_NAME');
					}
            });
        }
    }
    function RemoveDataSearch($key)
    {
        if($key=='TRAVELLER_NAME' || $key=='PASSPORT'){
            jQuery("#reservation_traveller_id").val('');
            jQuery("#traveller_name").val('');
            jQuery("#passport").val('');
        }else if($key=='ROOM_NAME'){
            jQuery("#room_name").val('');
            jQuery("#reservation_room_id").val('');
        }
    }
    function OpenLoading(){
        jQuery("#LoadingCentral").css('display','');
    }
    function CloseLoading(){
        jQuery("#LoadingCentral").css('display','none');
    }
    var windownskey = 0;
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
        var Wintitle = '[[.payment.]] MICE';
        FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
    }
    function CheckAllService(){
        if(document.getElementById('check_all_service').checked==true)
            jQuery(".checkbox_items").attr('checked','checked');
        else
            jQuery(".checkbox_items").removeAttr('checked');
    }
    function CheckItemsService(){
        var $check = true;
        jQuery(".checkbox_items").each(function(){
            if(document.getElementById(this.id).checked==false)
                $check = false;
        });
        if($check)
            jQuery("#check_all_service").attr('checked','checked');
        else
            jQuery("#check_all_service").removeAttr('checked');
        
        return false;
    }
    function SaveFolio($type){
        
    }
</script>