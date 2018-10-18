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
    .history_items {
        transition: all 0.3s ease-out;
        opacity: 0.7;
    }
    .history_items:hover {
        opacity: 1;
    }
    .DataItems {
        background: #FFFFFF;
    }
    .DataItems:hover {
        background: #abffd5;
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
<div class="w3-container" style="width: 99%; padding: 5px; background: #FFFFFF; border: 1px solid #DDDDDD; margin-bottom:100px;">
    <form name="MasterReservationForm" method="POST">
        <table style="width: 100%;" cellpadding="5" border="0" bordercolor="#EEEEEEE">
            <tr>
                <td style="text-align: center;">
                    <b style="text-transform: uppercase; color: #171717; font-size: 30px; font-weight: normal; line-height: 20px;">RESERVATION</b><a href="#" class="icon-button w3-text-white" style="float: right; text-transform: uppercase; text-decoration: none;" onclick="FunSearch();"><i class="fa fa-fw fa-search"></i><?php echo Portal::language('search');?></a>
                </td>
            </tr>
            <tr style="border: 1px solid lightgray;">
                <td>
                    <div style="text-align: center;">
                        <label for="from_date"><?php echo Portal::language('from_date');?>:</label> 
                        <input  name="from_date" id="from_date" onchange="CheckDate('from_date');" onkeypress="if(event.keyCode==13){FunSearch();}" readonly="readonly" style="padding: 5px; border: 1px solid #DDDDDD; width: 72px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                        <i class="fa fa-times-circle w3-text-red" style="cursor: pointer; font-size: 16px; padding-right: 15px;" onclick="jQuery('#from_date').val('');"></i>
                        <label for="to_date"><?php echo Portal::language('to_date');?>:</label> 
                        <input  name="to_date" id="to_date" onchange="CheckDate('to_date');" onkeypress="if(event.keyCode==13){FunSearch();}" readonly="readonly" style="padding: 5px; border: 1px solid #DDDDDD; width: 72px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                        <i class="fa fa-times-circle w3-text-red" style="cursor: pointer; font-size: 16px; padding-right: 15px;" onclick="jQuery('#to_date').val('');"></i>
                        <label for="reservation_id">Recode:</label> 
                        <input  name="reservation_id" id="reservation_id" autocomplete="OFF" onfocus="Autocomplete('RESERVATION_ID');" onkeypress="if(event.keyCode==13){FunSearch();}" style="padding: 5px; border: 1px solid #DDDDDD; width: 60px;" / type ="text" value="<?php echo String::html_normalize(URL::get('reservation_id'));?>">
                        <i class="fa fa-times-circle w3-text-red" style="cursor: pointer; font-size: 16px; padding-right: 15px;" onclick="jQuery('#reservation_id').val('');"></i>
                        <label for="room_name"><?php echo Portal::language('room');?>:</label> 
                        <input  name="room_name" id="room_name" onfocus="Autocomplete('ROOM_NAME');" onkeypress="if(event.keyCode==13){FunSearch();}" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 50px;" / type ="text" value="<?php echo String::html_normalize(URL::get('room_name'));?>">
                        <i class="fa fa-times-circle w3-text-red" style="cursor: pointer; font-size: 16px; padding-right: 15px;" onclick="RemoveDataSearch('ROOM_NAME');"></i>
                        <input  name="reservation_room_id" id="reservation_room_id" / type ="hidden" value="<?php echo String::html_normalize(URL::get('reservation_room_id'));?>">
                        <label for="status"><?php echo Portal::language('status');?>:</label> 
                        <select id="status" name="status" style="padding: 5px; border: 1px solid #DDDDDD; width: 80px;"><option value=""><?php echo Portal::language('select');?></option><option value="BOOKED">BOOKED</option><option value="CHECKIN">CHECKIN</option><option value="CHECKOUT">CHECKOUT</option></select>
                        <label for="traveller_name"><?php echo Portal::language('traveller');?>:</label> 
                        <input  name="traveller_name" id="traveller_name" onfocus="Autocomplete('TRAVELLER_NAME');" onkeypress="if(event.keyCode==13){FunSearch();}" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 10%;" / type ="text" value="<?php echo String::html_normalize(URL::get('traveller_name'));?>">
                        <i class="fa fa-times-circle w3-text-red" style="cursor: pointer; font-size: 16px; padding-right: 15px;" onclick="RemoveDataSearch('TRAVELLER_NAME');"></i>
                        <label for="passport"><?php echo Portal::language('passport');?>:</label> 
                        <input  name="passport" id="passport" onfocus="Autocomplete('PASSPORT');" onkeypress="if(event.keyCode==13){FunSearch();}" autocomplete="OFF" style="padding: 5px; border: 1px solid #DDDDDD; width: 7%;" / type ="text" value="<?php echo String::html_normalize(URL::get('passport'));?>">
                        <i class="fa fa-times-circle w3-text-red" style="cursor: pointer; font-size: 16px; padding-right: 15px;" onclick="RemoveDataSearch('PASSPORT');"></i>
                        <input  name="reservation_traveller_id" id="reservation_traveller_id" / type ="hidden" value="<?php echo String::html_normalize(URL::get('reservation_traveller_id'));?>">
                        
                    </div>
                    
                </td>
            </tr>
        </table>
        <div id="data_search" style="width: 100%; margin: 10px auto; padding: 0px;">
            <div id="data_search_new" style="width: 100%; margin: 10px auto; padding: 0px;">             
            </div>
            <div id="data_search_old" style="width: 100%; margin: 200px auto 20px; padding: 0px; display: none;">
                
            </div>
        </div>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 999;">
    <div class="loader"></div>
</div>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    <?php echo 'var block_id = '.Module::block_id().';';?>
    jQuery(window).keypress(function(e) {
        if(event.keyCode==13){FunSearch();}
    });
    jQuery(document).ready(function(){
        <?php if(Url::get('autoload')){ ?>
        FunSearch();
        console.log(1);
        <?php } ?>
    });
    function Autocomplete($key)
    {
        var reservation_id = jQuery("#reservation_id").val();
        var reservation_room_id = jQuery("#reservation_room_id").val();
        var reservation_traveller_id = jQuery("#reservation_traveller_id").val();
        var from_date = jQuery("#from_date").val();
        var to_date = jQuery("#to_date").val();
        if($key=='TRAVELLER_NAME'){
            jQuery("#traveller_name").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key+"&from_date="+from_date+"&to_date="+to_date,
                 onItemSelect: function(item){
                    jQuery("#reservation_traveller_id").val(item.data[0]);
                    jQuery(".acResults").remove();
                    FindDataInput($key);
                }
            }) ;
        }else if($key=='PASSPORT'){
            jQuery("#passport").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key+"&from_date="+from_date+"&to_date="+to_date,
                 onItemSelect: function(item){
                    jQuery("#reservation_traveller_id").val(item.data[0]);
                    jQuery(".acResults").remove();
                    FindDataInput($key);
                }
            }) ;
        }else if($key=='ROOM_NAME'){
            jQuery("#room_name").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key+"&from_date="+from_date+"&to_date="+to_date,
                 onItemSelect: function(item){
                    jQuery("#reservation_room_id").val(item.data[0]);
                    jQuery(".acResults").remove();
                    FindDataInput($key);
                }
            }) ;
        }else if($key=='RESERVATION_ID'){
            jQuery(".acResults").remove();
            FindDataInput($key);
            /**
            jQuery("#reservation_id").autocomplete({
                 url: "form.php?block_id="+block_id+"&get_autocomplete=1&type="+$key,
                 onItemSelect: function(item){
                    jQuery(".acResults").remove();
                    FindDataInput($key);
                }
            }) ;
            **/
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
        }else if($key=='RESERVATION_ID'){
            RemoveDataSearch('TRAVELLER_NAME');
            RemoveDataSearch('ROOM_NAME');
            /*
            jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
                    dataType: "json",
					data:{find_data:1,type:$key,reservation_id:reservation_id},
					success:function(html)
                    {
                        var check = false;
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
            */
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
        //form.php?block_id=429&cmd=create_folio&rr_id='+$('id_101').value+'&r_id='+156
        var Wintitle = '<?php echo Portal::language('payment');?> MICE';
        FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
    }
    function CheckDate($id){
        if(jQuery("#from_date").val()!='' && jQuery("#to_date").val()!='' && count_date(jQuery("#from_date").val(),jQuery("#to_date").val())<0){
            if($id){
                alert('<?php echo Portal::language('start_date_must_be_less_than_end_date');?> !');
                if($id=='from_date')
                    jQuery("#from_date").val(jQuery("#to_date").val());
                else
                    jQuery("#to_date").val(jQuery("#from_date").val());
            }else{
                alert('<?php echo Portal::language('start_date_must_be_less_than_end_date');?> !');
                return false;
            }
        }else{
            return true;
        }
    }
    function count_date(start_day, end_day)
    {
    	var std =start_day.split("/");
    	var ed =end_day.split("/");
    	return (Date.parse(ed[1]+"/"+ed[0]+"/"+ed[2])-Date.parse(std[1]+"/"+std[0]+"/"+std[2]))/86400000;
    }
    var last_time_search = new Array();//[] length=0
    var count = 10000;
    var data_search_history = new Array();
    
    function FunSearch($reload){
        if(CheckDate(false) || $reload){
            var reservation_id = $reload?data_search_history['reservation_id']:jQuery("#reservation_id").val();
            var reservation_room_id = $reload?data_search_history['reservation_room_id']:jQuery("#reservation_room_id").val();
            var reservation_traveller_id = $reload?data_search_history['reservation_traveller_id']:jQuery("#reservation_traveller_id").val();
            var from_date = $reload?data_search_history['from_date']:jQuery("#from_date").val();
            var to_date = $reload?data_search_history['to_date']:jQuery("#to_date").val();
            var status = $reload?data_search_history['status']:jQuery("#status").val();
            data_search_history['reservation_id'] = reservation_id;
            data_search_history['reservation_room_id'] = reservation_room_id;
            data_search_history['reservation_traveller_id'] = reservation_traveller_id;
            data_search_history['from_date'] = from_date;
            data_search_history['to_date'] = to_date;
            data_search_history['status'] = status;
            if(reservation_id!='' || reservation_room_id!='' || reservation_traveller_id!='' || from_date!='' || to_date!='' || status!=''){
                OpenLoading();
                jQuery.ajax({
    					url:"form.php?block_id="+block_id,
    					type:"POST",
                        dataType: "json",
    					data:{search_data:1,reservation_id:reservation_id,reservation_room_id:reservation_room_id,reservation_traveller_id:reservation_traveller_id,from_date:from_date,to_date:to_date,status:status},
    					success:function(html)
                        {
                            if(html['data'].length==0){
                                alert('<?php echo Portal::language('no_records');?>');
                            }else{
                                var content = '';
                                content += '<div id="TIME_'+html['time']+'_'+count+'">';
                                    content += '<table cellpadding="3" border="1" bordercolor="#EEEEEEE" style="width: 100% !important;">';
                                        content += '<tr class="w3-light-gray" style="text-align: center; height: 30px; text-transform: uppercase;">';
                                            content += '<th class="w3-border"></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('recode');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('customer_name');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('room');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('room_level');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('time_in');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('time_out');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('foc');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('foc_all');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('description');?></th>';                                         
                                            content += '<th class="w3-border"><?php echo Portal::language('status');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('create_folio');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('add_service');?></th>';
                                            content += '<th class="w3-border"><?php echo Portal::language('edit');?></th>';
                                        content += '</tr>';
                                        for(var i in html['data']){
                                            content += '<tr class="DataItems">';
                                                content += '<td style="text-align: center;"><i class="fa fa-fw fa-plus" style="color: #847f7f;"></i></td>';
                                                content += '<td style="text-align: center; font-weight: bold;"><a target="_blank" href="?page=reservation&cmd=edit&id='+html['data'][i]['reservation_id']+'&r_r_id='+html['data'][i]['id']+'">'+html['data'][i]['reservation_id']+'</a></td>';
                                                content += '<td style="font-weight: bold;">'+html['data'][i]['customer_name']+'</td>';
                                                content += '<td style="text-align: center; font-weight: bold;">'+html['data'][i]['room_name']+'</td>';
                                                content += '<td style="text-align: center;">'+html['data'][i]['room_level_name']+'</td>';                                                
                                                content += '<td style="text-align: center;">'+html['data'][i]['time_in']+'</td>';
                                                content += '<td style="text-align: center;">'+html['data'][i]['time_out']+'</td>';
                                                content += '<td style="text-align: center;">'+html['data'][i]['foc']+'</td>';
                                                content += '<td style="text-align: center;">'+html['data'][i]['foc_all']+'</td>';
                                                content += '<td>'+html['data'][i]['description']+'</td>';                                                
                                                content += '<td style="text-align: right; color: red;">'+html['data'][i]['status']+'<select id="fast_status_'+html['data'][i]['id']+'_'+html['time']+'_'+count+'" onchange="if(this.value!=\'\' && confirm(\'<?php echo Portal::language('are_you_sure');?> \')){FastOperation('+html['data'][i]['id']+','+html['time']+','+count+','+html['data'][i]['reservation_id']+','+html['data'][i]['customer_id']+',\'STATUS\');}else{this.value=\'\';}" style="padding: 3px; border: 1px solid #DDDDDD; margin-left: 5px; width: 75px; fload: right;"><option value=""><?php echo Portal::language('select');?></option>'+html['data'][i]['fast_status_option']+'</select></td>';
                                                content += '<td style="text-align: center;"><select id="fast_operation_'+html['data'][i]['id']+'_'+html['time']+'_'+count+'" onchange="FastOperation('+html['data'][i]['id']+','+html['time']+','+count+','+html['data'][i]['reservation_id']+','+html['data'][i]['customer_id']+',\'FOLIO\','+(html['data'][i]['mice']==null?'\'\'':html['data'][i]['mice'])+');" style="padding: 3px; width: 110px; border: 1px solid #DDDDDD;"><option value=""><?php echo Portal::language('select');?></option>'+html['data'][i]['fast_operation_option']+'</select></td>';
                                                content += '<td style="text-align: center;"><select id="add_service_'+html['data'][i]['id']+'_'+html['time']+'_'+count+'" onchange="AddService('+html['data'][i]['id']+','+html['time']+','+count+','+html['data'][i]['reservation_id']+','+html['data'][i]['customer_id']+');" style="padding: 3px; width: 110px; border: 1px solid #DDDDDD;"><option value=""><?php echo Portal::language('select');?></option>'+html['data'][i]['add_service_option']+'</select></td>';
                                                content += '<td style="text-align: center;"><a target="_blank" href="?page=reservation&cmd=edit&id='+html['data'][i]['reservation_id']+'&r_r_id='+html['data'][i]['id']+'"><i class="fa fa-fw fa-pencil w3-text-green" style="font-size:18px;"></i></a></td>';
                                            content += '</tr>';
                                            if(to_numeric(html['data'][i]['count_child'])>0){
                                                content += '<tr class="w3-light-gray w3-text-orange">';
                                                    content += '<th colspan="4" style="text-align: center;"><?php echo Portal::language('traveller');?></th>';
                                                    content += '<th><?php echo Portal::language('nationality');?></th>';
                                                    content += '<th><?php echo Portal::language('time_in');?></th>';
                                                    content += '<th><?php echo Portal::language('time_out');?></th>';
                                                    content += '<th colspan="2"><?php echo Portal::language('passport');?></th>';
                                                    content += '<th colspan="4"><?php echo Portal::language('address');?></th>';                                                                                                        
                                                    content += '<th><?php echo Portal::language('history_of_stay');?></th>';
                                                    
                                                content += '</tr>';
                                                data_child = html['data'][i]['child'];
                                                for(var j in data_child){
                                                    content += '<tr class="w3-light-gray w3-text-blue">';
                                                        content += '<td colspan="2" style="text-align: right;"><i class="fa fa-user w3-text-blue" ></i></td>';
                                                        content += '<td class="w3-text-blue" colspan="2" >'+data_child[j]['traveller_name']+'</td>';
                                                        content += '<td class="w3-text-blue" style="text-align: center;">'+data_child[j]['country']+'</td>';
                                                        content += '<td class="w3-text-blue" style="text-align: center;">'+data_child[j]['time_in']+'</td>';
                                                        content += '<td class="w3-text-blue" style="text-align: center;">'+data_child[j]['time_out']+'</td>';                                                        
                                                        content += '<td class="w3-text-blue" colspan="2" style="text-align: center;">'+data_child[j]['passport']+'</td>';                                                        
                                                        content += '<td class="w3-text-blue" colspan="4">'+data_child[j]['address']+'</td>';
                                                        content += '<td class="w3-text-blue" style="text-align: center;"><a target="_blank" href="?page=traveller&id='+data_child[j]['traveller_id']+'"><i class="fa fa-fw fa-history w3-text-blue w3-hover-text-red" style="font-size: 16px;"></i></a></td>';
                                                        
                                                    content += '</tr>';
                                                }
                                            }
                                            
                                        }
                                    content += '</table>';
                                content += '</div>';
                                
                                if(!$reload){
                                    if(last_time_search.length>0){
                                        var history = '';
                                        history += '<fieldset style="border: none; border-top: 1px solid #EEEEEE;">';
                                            history += '<legend style="color: #555555;"><?php echo Portal::language('history_search');?> <i class="fa fa-fw fa-remove" style="cursor: pointer; color: #fdc5c5;" onclick="jQuery(\'.history_items\').remove(); jQuery(\'#data_search_old\').css(\'display\',\'none\');"></i></legend>';
                                            for(var k in last_time_search){
                                                //console.log(jQuery('#TIME_'+last_time_search[k]['time']+'_'+last_time_search[k]['count']));
                                                if(jQuery('#TIME_'+last_time_search[k]['time']+'_'+last_time_search[k]['count']).html()!=null)
                                                {
                                                    history += '<div id="TIME_'+last_time_search[k]['time']+'_'+last_time_search[k]['count']+'"  class="history_items" style="border-bottom: 1px dashed #EEEEEE; padding: 15px 0px;">';
                                                        history += jQuery('#TIME_'+last_time_search[k]['time']+'_'+last_time_search[k]['count']).html();
                                                    history += '</div>';
                                                }
                                            }
                                        history += '</fieldset>';
                                        //console.log(history);
                                        jQuery("#data_search_old").css('display','');
                                        document.getElementById('data_search_old').innerHTML = history;
                                    }
                                }
                                    last_time_search[count] = new Array();
                                    last_time_search[count]['time'] = html['time'];
                                    last_time_search[count]['date'] = html['date'];
                                    last_time_search[count]['count'] = count;
                                    //console.log(last_time_search);
                                    count--;
                                document.getElementById('data_search_new').innerHTML = content;
                            }
                            
                            CloseLoading();
    					}
                });
            }else{
                alert('Bạn chưa nhập dữ liệu tìm kiếm!');
            }
        }
    }
    
    function AddService($reservation_room_id,$time,$key,$reservation_id,$customer_id)
    {
        $service = jQuery('#add_service_'+$reservation_room_id+'_'+$time+'_'+$key).val();
        if($service!='')
        {
            // EXTRA_ROOM
            // EXTRA_SERVICE
            // MINIBAR
            // LAUNDRY
            // EQUIPMENT
            jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
                    dataType: "json",
					data:{add_service:1,service:$service,reservation_room_id:$reservation_room_id},
					success:function(html)
                    {
                        if(to_numeric(html['status'])==1){
                            if($service=='EXTRA_ROOM')
                                var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=343&type=ROOM&cmd=add&reservation_room_id='+$reservation_room_id+'&fast=1';
                            else if($service=='EXTRA_SERVICE')
                                var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=343&type=SERVICE&cmd=add&reservation_room_id='+$reservation_room_id+'&fast=1';
                            else if($service=='MINIBAR')
                                var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=59&cmd=add&reservation_room_id='+$reservation_room_id+'&fast=1';
                            else if($service=='LAUNDRY')
                                var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=55&cmd=add&reservation_room_id='+$reservation_room_id+'&fast=1';
                            else if($service=='EQUIPMENT')
                                var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=317&cmd=add&reservation_room_id='+$reservation_room_id+'&fast=1';
                            var Wintitle = '<?php echo Portal::language('add');?> '+$service;
                            FunOpenWindowns(WinSrc,Wintitle,(window.screen.width-100),500,50,50);
                        }else{
                            alert(html['messenge']);
                            jQuery('#add_service_'+$reservation_room_id+'_'+$time+'_'+$key).val('');
                        }
					}
            });
        }
    }
    
    function FastOperation($reservation_room_id,$time,$key,$reservation_id,$customer_id,$type,$mice_id){
        
        if($type=='FOLIO'){
            $operation = jQuery('#fast_operation_'+$reservation_room_id+'_'+$time+'_'+$key).val();
            $option_id = 'fast_operation_'+$reservation_room_id+'_'+$time+'_'+$key;
        }else{
            $operation = jQuery('#fast_status_'+$reservation_room_id+'_'+$time+'_'+$key).val();
            $option_id = 'fast_status_'+$reservation_room_id+'_'+$time+'_'+$key;
        }
        if($operation!=''){
            // ASIGN_ROOM
            // CHECKIN
            // FOLIO_ROOM
            // FOLIO_GROUP
            // CHECKOUT
            jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
					data:{fast_operation:1,operation:$operation,reservation_room_id:$reservation_room_id},
					success:function(html)
                    {
                        if($operation=='ASIGN_ROOM')
                        {
                            var objs = jQuery.parseJSON(html);
                            if(to_numeric(objs['status'])==0){
                                alert(objs['messenge']);
                                jQuery('#'+$option_id).val('');
                            }else{
                                //window.open('?page=reservation&cmd=asign_room&id='+objs['data']);
                                var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=724&cmd=asign_room&id='+$reservation_id+'&fast=1';
                                var Wintitle = '<?php echo Portal::language('asign_room');?> ';
                                FunOpenWindowns(WinSrc,Wintitle,(window.screen.width-100),500,50,50);
                            }
                        }
                        else if($operation=='CHECKIN')
                        {
                            var otbjs = jQuery.parseJSON(html);
                            notify = '';
                            for(var otbj in otbjs)
                            {
                                notify += otbjs[otbj]['error']+"\n";
                            }
                            if(notify!='')
                            {
                                alert(notify);
                                jQuery('#'+$option_id).val('');
                            }
                            else
                            {
                                alert('<?php echo Portal::language('checkin_success');?>');
                                FunSearch(1);
                            }
                        }
                        else if($operation=='CHECKOUT') 
                        {
                            if(html)
                            {
                                var otbjs = jQuery.parseJSON(html);
                                
                                notify = 'Chênh lệch số tiền trên Folio hoặc chưa chọn hình thức thanh toán. Anh/chị vui lòng kiểm tra lại !!!!!\n'
                                console.log(otbjs);
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
                                    if(otbjs[otbj]['status'])
                                    {
                                        notify = otbjs[otbj]['error'];
                                    }
                                }
                                alert(notify);
                                jQuery('#'+$option_id).val('');
                            }
                            else
                            {
                                alert('<?php echo Portal::language('checkout_success');?>');
                                FunSearch(1);           
                            }
                        }
                        else if($operation=='FOLIO_ROOM')
                        {
                            var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=429&cmd=create_folio&rr_id='+$reservation_room_id+'&r_id='+$reservation_id+'&fast=1';
                            var Wintitle = '<?php echo Portal::language('folio');?> <?php echo Portal::language('room');?>';
                            FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
                        }
                        else if($operation=='FOLIO_GROUP')
                        {
                            var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=429&cmd=group_folio&id='+$reservation_id+'&customer_id='+$customer_id+'&fast=1';
                            var Wintitle = '<?php echo Portal::language('folio');?> <?php echo Portal::language('group');?>';
                            FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
                        }
                        else if($operation=='PAYMENT_MICE')
                        {
                            var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>?page=mice_reservation&cmd=invoice&id='+$mice_id+'&department_code=REC&fast=1';
                            var Wintitle = '<?php echo Portal::language('payment');?> MICE';
                            FunOpenWindowns(WinSrc,Wintitle,960,500,50,200);
                        }
					}
            });
        }
    }
</script>