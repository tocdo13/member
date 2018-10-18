<style>
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
    .loader  {
      animation: rotate 1s infinite;  
      height: 50px;
      width: 50px;
      margin: 200px auto;
    }
    
    .loader:before,
    .loader:after {   
      border-radius: 50%;
      content: '';
      display: block;
      height: 20px;  
      width: 20px;
    }
    .loader:before {
      animation: ball1 1s infinite;  
      background-color: #cb2025;
      box-shadow: 30px 0 0 #f8b334;
      margin-bottom: 10px;
    }
    .loader:after {
      animation: ball2 1s infinite; 
      background-color: #00a096;
      box-shadow: 30px 0 0 #97bf0d;
    }
    
    @keyframes rotate {
      0% { 
        -webkit-transform: rotate(0deg) scale(0.8); 
        -moz-transform: rotate(0deg) scale(0.8);
      }
      50% { 
        -webkit-transform: rotate(360deg) scale(1.2); 
        -moz-transform: rotate(360deg) scale(1.2);
      }
      100% { 
        -webkit-transform: rotate(720deg) scale(0.8); 
        -moz-transform: rotate(720deg) scale(0.8);
      }
    }
    
    @keyframes ball1 {
      0% {
        box-shadow: 30px 0 0 #f8b334;
      }
      50% {
        box-shadow: 0 0 0 #f8b334;
        margin-bottom: 0;
        -webkit-transform: translate(15px,15px);
        -moz-transform: translate(15px, 15px);
      }
      100% {
        box-shadow: 30px 0 0 #f8b334;
        margin-bottom: 10px;
      }
    }
    
    @keyframes ball2 {
      0% {
        box-shadow: 30px 0 0 #97bf0d;
      }
      50% {
        box-shadow: 0 0 0 #97bf0d;
        margin-top: -20px;
        -webkit-transform: translate(15px,15px);
        -moz-transform: translate(15px, 15px);
      }
      100% {
        box-shadow: 30px 0 0 #97bf0d;
        margin-top: 0;
      }
    }
</style>
<div style="width: 100%; min-height: 600px; margin: 0 auto; background: #FFFFFF; border: 1px solid #DDDDDD;">
    <form name="CheckoutFastForm" method="POST">
        <table style="width: 100%; margin: 0 auto;" cellpadding="5" border="1" bordercolor="#EEEEEEE">
            <tr>
                <td style="text-align: center;">
                    <h2 style="text-transform: uppercase; color: #171717; font-weight: normal; line-height: 20px;">[[.checkout_fast.]]</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="float: left;">
                        <label for="reservation_id">Recode:</label> 
                        <input name="reservation_id" type="text" id="reservation_id" onfocus="Autocomplete('RESERVATION_ID');" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 80px;" />
                        <label for="room_name">[[.room.]]:</label> 
                        <input name="room_name" type="text" id="room_name" onfocus="Autocomplete('ROOM_NAME');" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 100px;" />
                        <i class="fa fa-fw fa-remove" style="cursor: pointer; color: #fdc5c5;" onclick="RemoveDataSearch('ROOM_NAME');"></i>
                        <input name="reservation_room_id" type="hidden" id="reservation_room_id" />
                        <label for="traveller_name">[[.traveller.]]:</label> 
                        <input name="traveller_name" type="text" id="traveller_name" onfocus="Autocomplete('TRAVELLER_NAME');" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 200px;" />
                        <i class="fa fa-fw fa-remove" style="cursor: pointer; color: #fdc5c5;" onclick="RemoveDataSearch('TRAVELLER_NAME');"></i>
                        <label for="passport">[[.passport.]]:</label> 
                        <input name="passport" type="text" id="passport" onfocus="Autocomplete('PASSPORT');" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 100px;" />
                        <input name="passport_code" type="hidden" id="passport_code" />
                        <i class="fa fa-fw fa-remove" style="cursor: pointer; color: #fdc5c5;" onclick="RemoveDataSearch('PASSPORT');"></i>
                        <input name="reservation_traveller_id" type="hidden" id="reservation_traveller_id" />
                    </div>
                    <div class="icon-button" style="float: right;" onclick="FunSearch();"><i class="fa fa-fw fa-search"></i>[[.search.]]</div>
                    <input name="check_submit" type="checkbox" id="check_submit" style="display: none;" value="check submit" />
                </td>
            </tr>
        </table>
        <div id="data_search" style="width: 100%; margin: 10px auto; padding: 0px;">
            
        </div>
        <div id="AI" style="width: 100%; margin: 0px auto; padding: 0px;">
            <div id="checkin_list" style="padding: 1%; margin: 0px; float: left;">
                <fieldset>
                    <legend>[[.checkin_list.]]</legend>
                    <table cellpadding="5" border="1" bordercolor="#EEEEEEE">
                        <tr style="text-align: center;">
                            <th>[[.recode.]]</th>
                            <th>[[.customer_name.]]</th>
                            <th>[[.room.]]</th>
                            <th>[[.room_level.]]</th>
                            <th>[[.time_in.]]</th>
                            <th>[[.time_out.]]</th>
                            <th>[[.description.]]</th>
                            <th>[[.detail.]]</th>
                            <th>[[.checkout_fast.]]</th>
                        </tr>
                        <!--LIST:checkin_list-->
                            <tr>
                                <td style="text-align: center;"><a target="_blank" href="?page=reservation&cmd=edit&id=[[|checkin_list.reservation_id|]]&r_r_id=[[|checkin_list.id|]]">[[|checkin_list.reservation_id|]]</a></td>
                                <td><a target="_blank" href="?page=customer&cmd=edit&id=[[|checkin_list.customer_id|]]">[[|checkin_list.customer_name|]]</a></td>
                                <td style="text-align: center;">[[|checkin_list.room_name|]]</td>
                                <td style="text-align: center;">[[|checkin_list.room_level_name|]]</td>
                                <td style="text-align: center;"><?php echo date('H:i d/m/Y',[[=checkin_list.time_in=]]); ?></td>
                                <td style="text-align: center;"><?php echo date('H:i d/m/Y',[[=checkin_list.time_out=]]); ?></td>
                                <td>
                                    <p><?php if([[=checkin_list.confirm=]]==1){ ?>Đã xác nhận<?php }else{ ?>Chưa xác nhận<?php } ?></p>
                                    <?php if([[=checkin_list.room_id=]]==''){ ?><p>Chưa gán phòng</p><?php } ?>
                                    <?php if([[=checkin_list.foc_all=]]==1){ ?><p>Miễn phí toàn bộ</p><?php }elseif([[=checkin_list.foc=]]!=''){ ?><p>Miễn phí tiền phòng</p><?php } ?>
                                </td>
                                <td style="text-align: center;"><a target="_blank" href="?page=reservation&cmd=edit&id=[[|checkin_list.reservation_id|]]&r_r_id=[[|checkin_list.id|]]"><i class="fa fa-fw fa-pencil"></i></a></th>
                                <td style="text-align: center; cursor: pointer;" onclick="CheckOutFast([[|checkin_list.id|]],0);"><i class="fa fa-fw fa-sign-in"></i></td>
                            </tr>
                        <!--/LIST:checkin_list-->
                    </table>
                </fieldset>
            </div>
        </div>
    </form>
</div>
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 999;">
    <div class="loader"></div>
</div>
<script>
    <?php echo 'var block_id = '.Module::block_id().';';?>
    function Autocomplete($key)
    {
        var reservation_id = jQuery("#reservation_id").val();
        var reservation_room_id = jQuery("#reservation_room_id").val();
        var reservation_traveller_id = jQuery("#reservation_traveller_id").val();
        if($key=='TRAVELLER_NAME'){
            jQuery("#traveller_name").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key,
                 onItemSelect: function(item){
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
                    jQuery("#reservation_room_id").val(item.data[0]);
                    jQuery(".acResults").remove();
                    FindDataInput($key);
                }
            }) ;
        }else if($key=='RESERVATION_ID'){
            jQuery("#reservation_id").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key,
                 onItemSelect: function(item){
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
                        jQuery("#passport_code").val(html['passport']);
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
        }else if($key=='RESERVATION_ID'){
            //console.log($key);
            jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
                    dataType: "json",
					data:{find_data:1,type:$key,reservation_id:reservation_id},
					success:function(html)
                    {
                        var check = false;
                        //console.log(html);
                        for(var i in html){
                            if(to_numeric(html[i]['id'])==to_numeric(reservation_room_id))
                                check = true;
                        }
                        if(!check){
                            RemoveDataSearch('TRAVELLER_NAME');
                            RemoveDataSearch('ROOM_NAME');
                        }
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
            jQuery("#passport_code").val('');
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
    function FunSearch()
    {
        CheckoutFastForm.submit();
    }
    
    function CheckOutFast(id_check,is_reservation)
    {
        if(!confirm('[[.are_you_sure.]]'))
        {
			return false;
		}
        <?php echo 'var block_id = '.Module::block_id().';';?>
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+block_id,
			type:"POST",
			data:{status:'CHECKOUT',id_check:id_check,is_reservation:is_reservation},
			success:function(html)
            {
                console.log(html);
                if(html)
                {
                    notify = 'Chênh lệch số tiền trên Folio hoặc chưa chọn hình thức thanh toán. Anh/chị vui lòng kiểm tra lại !!!!!\n'
                    var otbjs = jQuery.parseJSON(html);
                    for(var otbj in otbjs)
                    {
                        if(otbjs[otbj]['not_create_folio'])
                        {
                            notify +="   - Phòng "+otbjs[otbj]['room_name']+': '+(otbjs[otbj]['not_create_folio']?'Chưa tạo hết hóa đơn\n':"\n");                            
                        }
                        if(otbjs[otbj]['not_deposit_group'])
                        {
                            notify +="   - Phòng "+otbjs[otbj]['room_name']+': '+(otbjs[otbj]['not_deposit_group']?'Còn khoản đặt cọc nhóm chưa được tạo thanh toán\n':"\n");                            
                        }
                        if(otbjs[otbj]['folios_not_paid'])
                        {
                            for(var folio in otbjs[otbj]['folios_not_paid'])
                            {
                                if(otbjs[otbj]['folios_not_paid'][folio]['payment'])
                                {
                                    notify +='    + folio '+otbjs[otbj]['folios_not_paid'][folio]['id']+" chưa thanh toán hết\n";
                                }
                                else
                                {
                                    notify +='    + folio '+otbjs[otbj]['folios_not_paid'][folio]['id']+" chưa thanh toán\n";
                                }
                            }
                        }
                    }
                    alert(notify);
                }
                else
                {
                    alert('[[.checkout_success.]]');
                    location.reload();                 
                }
                CloseLoading();
            }                        
		});
    }
</script>