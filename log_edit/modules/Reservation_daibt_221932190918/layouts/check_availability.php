<style type="text/css">
.simple-layout-middle{width:100%;}
</style>
<form name="CheckAvailabilityForm" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bound">
    <tr height="40">
	    <td width="90%" class="w3-text-blue" style="text-transform: uppercase; font-weight: bold; padding-left: 30px; font-size: 24px;"><i class="fa fa-pencil-square-o fa-2x w3-text-orange"></i>[[.room_availability.]]</td>
        <td width="10%" style="padding-right: 30px;"><input name="book" type="submit" value="[[.book_now.]]" onclick="if(!check_validate()){return false;}"  class="w3-btn w3-indigo" style="text-transform: uppercase; text-decoration: none;"/></td>
    </tr>
</table>
<div><br />
<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><br clear="all"/><?php }?>
<fieldset style="background-color:#EFEFEF; margin: 0px 20px 0px 20px;">
<!---<legend class="legend-title">[[.options.]]</legend>--->
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
	  <td>
    	[[.from.]]: 
        <input name="time_in_hour" type="text" id="time_in_hour" style="width:50px; display: none;"/><input style="width:40px; name="arrival_time_hour" type="text" id="arrival_time_hour" onchange="changevalue();"  />
        [[.hour.]]&nbsp;&nbsp;&nbsp;
        [[.day.]]: 
        <input name="time_in" type="text" id="time_in" class="date-input" style="width:50px; display: none;"/><input name="arrival_time" type="text" id="arrival_time" onchange="changevalue();" class="date-input" />
		&nbsp;&nbsp;&nbsp;[[.to.]]:
        <input name="time_out_hour" type="text" id="time_out_hour"  style="width:50px;  display: none;"/><input style="width:40px; name="departure_time_hour" type="text" id="departure_time_hour"  onchange="changefromday();" />
        [[.hour.]]&nbsp;&nbsp;&nbsp;
        [[.day.]] 
        <input name="time_out" type="text" id="time_out" class="date-input" style="width:50px;  display: none;"/><input name="departure_time" type="text" id="departure_time" class="date-input" onchange="changefromday();" />
    </td>
	<!--<td>[[.reservation_type.]]: <select name="reservation_type_id" id="reservation_type_id"></select></td>-->
	<td style="padding-left: 25px;">[[.confirm.]]: <input name="confirm" type="checkbox" id="confirm" /></td>
    <td style="padding-left: 35px;"><input name="search" type="submit" value="[[.check_availability.]]" class="w3-btn w3-orange w3-text-white" /></td>
    
  </tr>
</table>
</fieldset><br />
<fieldset style="background-color:#EFEFEF;display: none;">
<legend class="legend-title">[[.group_info.]]</legend>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
	  <td>[[.booking_code.]]: <input name="booking_code" type="text" id="booking_code" style="width:150px;" tabindex="7"></td>
        <!--<td>[[.tour.]]: <input name="tour_name" type="text" id="tour_name" style="width:215px;" readonly="" class="readonly">
                          <input name="tour_id" type="hidden" id="tour_id" value="0" />
                          <a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"></td>-->
        <td  colspan="3">[[.company.]]: <input name="customer_name" type="text" id="customer_name" style="width:215px;"  readonly="" class="readonly"/>
                          <input name="customer_id" type="text" id="customer_id" style="display:none;"/>
                          <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:hand;"/></td>
	</tr>
</table>
</fieldset>
</div><br />
<div>
<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="margin: 0px 20px 0px 20px;">
  <tr>
  	<td width="400px" valign="top">
     <div style="float:left;width:100%;">
     	<!--LIST:ebs-->
        <div class="check-availability-item" style="padding-left: 10px;">[[|ebs.name|]]</div>
     	<!--/LIST:ebs-->
     </div>
    </td>
    <td style="width: 60%;">
        <div style="float:left;width:40%;overflow:scroll;">
        <div style="width:2500px;float:left;">
        
        <?php
            $k =0; 
        ?>
		<!--LIST:ebs-->
            <div class="check-availability-item">
            <?php
                if($k==0)
                {
                    ?>
                    <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;"><strong>[[.total.]]</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;"></span>
                    <?php 
                }
                else
                {
                    if(isset([[=ebs.id=]]))
                    {
                        ?>
                     <span class="check-availability-day" style="background-color: #FFFFFF; color: black;">
            		[[|ebs.total_quantity|]]</span>
                    <span class="check-availability-day" style="background-color: #FFFFFF; color: black;">
            		&nbsp;</span>
                    
                    <?php
                    }
                     
                } 
                $k++;
            ?>
            
			<!--LIST:ebs.day_items-->
            [[|ebs.day_items.quantity|]]
            <!--/LIST:ebs.day_items-->
            </div>
		<!--/LIST:ebs-->
        </div>
        </div>
    </td>
  </tr>
  <tr>
  	<td colspan="3">&nbsp;<br /></td>
  </tr>
  <tr>
    <td width="400px" style="vertical-align: top;"><?php $i=1;$j=0;?>
    <div style="float:left;padding-top: 0px; margin-top: 0px; height: 465px; padding-left: 10px;">
		<!--LIST:room_levels-->
        	<div class="check-availability-item" style="float:left;white-space:nowrap">
			<?php if($i>1){?>
            <input  name="adult_[[|room_levels.id|]]" type="text" id="adult_[[|room_levels.id|]]" style="width:30px;height: 30px !important;" tabindex="<?php echo $j+9;$j++;?>">
            <input  name="child_[[|room_levels.id|]]" type="text" id="child_[[|room_levels.id|]]" style="width:30px; height: 30px !important;" tabindex="<?php echo $j+9;$j++;?>">
            <input  name="price_[[|room_levels.id|]]" type="text" id="price_[[|room_levels.id|]]" class="price" style="width:70px;position:relative; height: 30px !important;"  tabindex="<?php echo $j+9;$j++;?>" onfocus="//buildRateList('[[|room_levels.id|]]');" onclick="//buildRateList('[[|room_levels.id|]]');" onKeyUp="this.value=number_format(to_numeric(this.value));" oninput="jQuery('#usd_price_[[|room_levels.id|]]').val(number_format(to_numeric(jQuery('#price_[[|room_levels.id|]]').val())/to_numeric(jQuery('#exchange_rate').val())));">
            <input  name="usd_price_[[|room_levels.id|]]" type="text" id="usd_price_[[|room_levels.id|]]" class="price" style="width:60px;position:relative; height: 30px !important;"  tabindex="<?php echo $j+9;$j++;?>"   oninput="jQuery('#price_[[|room_levels.id|]]').val(number_format(to_numeric(jQuery('#usd_price_[[|room_levels.id|]]').val())*to_numeric(jQuery('#exchange_rate').val())));jQuery('#usd_price_[[|room_levels.id|]]').ForceNumericOnly().FormatNumber();">
            <input  
                    name="room_quantity_[[|room_levels.id|]]" 
                    type="text" id="room_quantity_[[|room_levels.id|]]" 
                    onkeyup="
                        <?php 
                            if (!OVER_BOOK)
                            { 
                        ?>
                        if(to_numeric(this.value)>to_numeric($('min_item_[[|room_levels.id|]]').value))
                        {
                            alert('[[.quantity.]] [[|room_levels.name|]] [[.must_smaller.]] '+$('min_item_[[|room_levels.id|]]').value);
                            this.focus();
                        }
                        <?php 
                            }
                        ?>
                        check_quantity();"
                    style="width:30px; height: 30px !important;background-color:#FF9;border:1px solid #F90;"  tabindex="<?php echo $j+9;$j++;?>" class="room-quantity-by-date QuantityRoom" lang="[[|room_levels.name|]]" title="[[.room_quantity.]]">
			<!--<input  name="note_[[|room_levels.id|]]" type="text" id="note_[[|room_levels.id|]]" style="width:55px;" tabindex="<?php echo $j+9;$j++;?>">-->
            <?php }else{?>
            <span class="room-quantity-by-date"><img src="packages/core/skins/default/images/buttons/adult.png" style="padding-left: 10px;" align="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="room-quantity-by-date"><img src="packages/core/skins/default/images/buttons/child.png" align="top">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="room-quantity-by-date">[[.vnd_price.]]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="room-quantity-by-date">[[.usd_price.]]&nbsp;&nbsp;&nbsp;</span>
            <span class="room-quantity-by-date">[[.r_q.]]&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<!--<span class="room-quantity-by-date">[[.note.]]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
            <?php }?>
            [[|room_levels.name|]]</div>
            <?php $i++;?>
        <!--/LIST:room_levels-->
        <span style="padding-left: 166px; "><b>[[.total.]]:</b>
        </span>
        <input name="sum_t" type="text" id="sum_t" style="width:30px;height: 30px !important;"/>&nbsp;&nbsp;<b>[[.room.]]</b>
        <span class="room-quantity-by-date">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </div>
    </td>
    
    <?php
        $h =0; 
    ?>
	<td style="width: 100%;">
    	<div style="float:left;width:40%;overflow-y:hidden;">
            <div style="width:2500px;float:left;height: 480px;">
        		<!--LIST:room_levels-->
        			<div class="check-availability-item">
                    <?php
                        if($h==0)
                        {
                            ?>
                            <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;position: absolute;"><strong>[[.total.]]</strong></span>
                            <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;"></span>
                            <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;position: absolute;"><strong>[[.real.]]</strong></span>
                            <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;"></span>
                            <?php 
                        } 
                        else
                        {
                            ?>
                            <!--IF:total_room([[=room_levels.id=]])-->
                    		 <span class="check-availability-day" style="background-color: #FFFFFF; color: black; position: absolute;">[[|room_levels.total_room_quantity|]]</span>
                             <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;"></span>                             
                             <input  name="min_item_[[|room_levels.id|]]" type="hidden" id="min_item_[[|room_levels.id|]]" value="[[|room_levels.min_room_quantity|]]" />
                            <span class="check-availability-day" style="background-color: #FFFFFF; color: black;position: absolute;"><?php echo [[=real_total_day=]][[[=room_levels.id=]]]; ?></span>
                            <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;"></span>                            
                            <!--/IF:total_room-->
                            <?php 
                        }
                        $h++;
                    ?>
                    <!--onclick="jQuery('#room_list').css({'display':'block','top':200,'left':500});"-->
        			<!--LIST:room_levels.day_items-->[[|room_levels.day_items.room_quantity|]]<!--/LIST:room_levels.day_items-->
        			</div>
        			
        		<!--/LIST:room_levels-->
        		<div style="float: left; margin-top: 3px; width: 60px;height:15px;border: 1px solid white; margin-left: 2px; text-align: center;">
                <table style="border: 1px solid white;">
                    <tr class="check-availability-item"><td style="width: 60px;height:32px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 0px; "><strong style="position: absolute;margin-top: 1px;">AV</strong></td></tr>
                    <!-- check inventory -->
                    <!--IF:cond_inventory(SITEMINDER_TWO_WAY or USE_HLS)-->
                    <tr class="check-availability-item"><td style="width: 60px;height:32px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 0px; "><strong style="position: absolute;margin-top: 1px;">ALM</strong></td></tr>
                    <!--/IF:cond_inventory-->
                    <!-- end check inventory -->
                    <tr class="check-availability-item"><td style="width: 60px;height:26px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 10px;"><strong style="position: absolute;margin-top: -10px;">OC</strong></td></tr>
                    <tr class="check-availability-item"><td style="width: 60px;height:26px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 10px; "> <em style="position: absolute;margin-top: -10px;">CF</em></td></tr>
                    <tr class="check-availability-item"><td style="width: 60px;height:26px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 10px; "><em style="position: absolute;margin-top: -10px;">TE</em></td></tr>
                    <tr class="check-availability-item"><td style="width: 60px;height:26px;margin-left: 2px; text-align: center;background: white;"><strong style="position: absolute;margin-top: -16px; background-color: white;width: 60px;margin-left: -19px;">RE</strong></td></tr>
                </table>
                </div>
                <!--LIST:total_by_day-->
                    <div style="float: left; margin-top: 3px; width: 36px;height:15px;border: 1px solid white; text-align: center;">
                    <table style="border: 1px solid white;">
                        <tr class="check-availability-item"><td style="width: 350px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;<?php if([[=total_by_day.total_avai_room=]]<0){echo "background:red;color:white;";} ?>"><strong>[[|total_by_day.total_avai_room|]]</strong></td></tr>
                        <!-- check inventory -->
                        <!--IF:cond_inventory(SITEMINDER_TWO_WAY)-->
                        <tr class="check-availability-item"><td style="width: 350px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;<?php if([[=total_by_day.total_inventory=]]<0){echo "background:red;color:white;";} ?>"><strong>[[|total_by_day.total_inventory|]]</strong></td></tr>
                        <!--/IF:cond_inventory-->
                        <!-- end check inventory -->
                        <tr class="check-availability-item"><td style="width: 30px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;"><strong><?php echo([[=total_by_day.total_occ_room=]]-[[=total_by_day.total_repair_room=]]);?></strong></td></tr>
                        <tr class="check-availability-item"><td style="width: 30px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;"><em>[[|total_by_day.total_confirm|]]</em></td></tr>
                        <tr class="check-availability-item"><td style="width: 30px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;"><em>[[|total_by_day.total_not_confirm|]]</em></td></tr>
                        <tr class="check-availability-item"><td style="width: 30px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;"><strong>[[|total_by_day.total_repair_room|]]</strong></td></tr>
                    </table>
                    </div>
        		<!--/LIST:total_by_day-->
                
            </div>
        </div>
	</td>
  </tr>
</table>
</div>
<div id="rate_list" class="room-rate-list" style="display:none;">
    <div>
        [[.rate_list.]]&nbsp;&nbsp;
        <a onclick="$('rate_list').style.display='none';"><img src="skins/default/images/close.JPG" title="[[.close.]]"></a>
    </div>
    <ul id="rate_list_result">
    </ul>
</div>
<!--start:KID them de chuyen doi gia-->
<input type="hidden" id="exchange_rate" value="[[|exchange_rate|]]" />
<!--end:KID them de chuyen doi gia-->

<div id="room_list" class="room-list" style="width:700px;height:500px;overflow:scroll;display:none;position: fixed; top: 100px; left: 300px;">
     <div class="w3-text-black" style="text-transform: uppercase; text-align: center; height; 20px;">
        [[.room_list_aval.]]:&nbsp;&nbsp;<span id="date_list"></span>
        <a onclick="$('room_list').style.display='none';"><img src="skins/default/images/close.JPG" title="[[.close.]]" style="float:right;"></a>
    </div>
    <div id="content_table"></div>
</div>
</form>
<script>
    jQuery(document).ready(function(){
        var roomlevelid = [[|room_levels_js|]];
        for(var j in roomlevelid)
        {
            jQuery("#adult_"+roomlevelid[j]['id']).val(roomlevelid[j]['num_people']);
            jQuery("#price_"+roomlevelid[j]['id']).val(number_format(roomlevelid[j]['price']));
            jQuery("#usd_price_"+roomlevelid[j]['id']).val(number_format((roomlevelid[j]['price'])/to_numeric(jQuery('#exchange_rate').val())));
        }
                   
    });
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    jQuery("#time_in").mask("99:99");
	jQuery("#time_out").mask("99:99");
    jQuery("#arrival_time_hour").mask("99:99");
    jQuery("#departure_time_hour").mask("99:99");
    jQuery("#arrival_time").datepicker();
    jQuery("#departure_time").datepicker();
	jQuery("#scroll_1").scroll(function(){
           scroll_1 = jQuery("#scroll_1").scrollLeft(); 
           jQuery("#scroll_3").scrollLeft(scroll_1); 
        });
    jQuery("#scroll_3").scroll(function(){
           scroll_3 = jQuery("#scroll_3").scrollLeft(); 
           jQuery("#scroll_1").scrollLeft(scroll_3); 
        });
    
    
	function selectAllLevel(levelId,minRoomQuantity){
		jQuery(".room-quantity-by-date").each(function(){
			idString = this.id;
			var re =  new RegExp("room_quantity_"+levelId,"g");
			if(idString.match(re)){
				if(jQuery(this).val()==''){
					jQuery(this).val(minRoomQuantity);
				}else{
					jQuery(this).val('');
				}
			}
		});
	}
	function buildRateList(roomLevelId){
		if(jQuery('#price_'+roomLevelId)){
			var customerId = jQuery('#customer_id').val();
			var adult = jQuery("#adult_"+roomLevelId).val();
			var child = jQuery("#child_"+roomLevelId).val();
			var startDate = jQuery('#arrival_time').val();
			var endDate = jQuery('#departure_time').val();
			getRateList(jQuery('#price_'+roomLevelId).attr('id'),roomLevelId,customerId,adult,child,startDate,endDate);
		}
	}
	function getRateList(id,roomLevelId,customerId,adult,child,startDate,endDate){
		if(adult<=0){
			alert('Chưa nhập số lượng người lớn / Miss adult quantity');
			jQuery('#adult_'+roomLevelId).focus();
			return false;
		}
		if(roomLevelId){
			obj = $(id);
			if($('rate_list').style.display=="none"){
				$('rate_list').style.display="";
				$('rate_list').style.top=obj.offsetTop-20+'px';
				$('rate_list').style.left=obj.offsetLeft+60+'px';
				jQuery('#rate_list_result').html('Loading...');
				ajax.get_text('r_get_rate_list.php?room_level_id='+roomLevelId+'&customer_id='+customerId+'&adult='+adult+'&child='+child+'&start_date='+startDate+'&end_date='+endDate, setRateList);
			}
		}else{
			//alert('You did not select room');
		}
	}
	function setRateList(text){
		jQuery('#rate_list_result').html(text);
	}
	function setRate(roomLevelId,rate){
		$('price_'+roomLevelId).value = rate;
		jQuery('#rate_list').hide();
	}
	function check_validate()
	{
		<!--LIST:room_levels--><!--IF:cond([[=room_levels.id=]] and !OVER_BOOK)-->
		if(to_numeric($('room_quantity_[[|room_levels.id|]]').value)>0 && to_numeric($('room_quantity_[[|room_levels.id|]]').value)>to_numeric($('min_item_[[|room_levels.id|]]').value))
		{
			alert('[[.quantity.]] [[|room_levels.name|]] [[.must_smaller.]] '+$('min_item_[[|room_levels.id|]]').value);
			return false;
		}
		<!--/IF:cond-->
		<!--/LIST:room_levels-->
        var myfromdate = $('arrival_time').value.split("/");
        //var now = new Date(getFullYear()+'-'+getMonth()+'-'+getDate());
        date_now = new Date(); 
       
        if(new Date(myfromdate[2],myfromdate[1]-1,myfromdate[0],23,59,59)< date_now)
        {
            alert('[[.no_booking_past.]]');
            return false;
        }
        
		return true;
	}
	function check_from_date(){
	  
        var from_date = $('arrival_time').value.split("/");
        from_date = from_date[1]+"/"+from_date[0]+"/"+from_date[2]; 
        var from_time = Date.parse(from_date.toString());
        //Cong 1 tuan le (ms nen * 1000)
        var to_time = to_numeric(from_time) + 2592000000;
        var to_date = new Date(to_time);
        to_date = to_date.getDate()+"/"+(to_date.getMonth()+1)+"/"+to_date.getFullYear();
        $('departure_time').value = to_date;
	}
    
    function changevalue()
    {
        var myfromdate = $('arrival_time').value.split("/");
        var mytodate = $('departure_time').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#departure_time").val(jQuery("#arrival_time").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('arrival_time').value.split("/");
        var mytodate = $('departure_time').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#arrival_time").val(jQuery("#departure_time").val());
        }
    }
    function check_quantity()
    {
        var sum = 0;
        jQuery(".QuantityRoom").each(function(){
            $sumi = to_numeric(jQuery("#"+this.id).val());
            sum += $sumi;            
          ;   
        jQuery('#sum_t').val(sum);               
        })
    }
    function parseDate(str) 
    {
        var mdy = str.split('/');
        return new Date(mdy[2], mdy[1], mdy[0]);
    }
    //phan tich chon bat ra cua so cac phong
    function list_avalible(room_levels_id,in_date,num)
    {
        if (window.XMLHttpRequest)
        {
            xmlhttp=new XMLHttpRequest();
        }
        else
        {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var objs = jQuery.parseJSON(text_reponse);
                obj = objs['floor'];
                document.getElementById("content_table").innerHTML = '';
                var text_html = '<table cellpadding="3" width="100%" id="table_room_type">';
                var number_room = 0;
                for( var i in obj)
                {
                    text_html += '<tr> <td width="50" style="border-bottom:1px solid white;">'+obj[i]['name']+'</td>';
                    text_html += '<td style="border-bottom:1px solid white;">';
            	    text_html += '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
                    for(var j in obj[i]['rooms'])
                    {
                         text_html += '<div class="room-bound" style="width:50px; height:50px; float:left;" onclick="var obj=jQuery(\'#room_select\').val(); jQuery(\'#room_level_name_\'+obj).val(\''+obj[i]['rooms'][j]['room_level_name']+'\');jQuery(\'#room_level_id_\'+obj).val('+obj[i]['rooms'][j]['room_level_id']+');jQuery(\'#room_id_\'+obj).val('+obj[i]['rooms'][j]['id']+');jQuery(\'#room_name_\'+obj).val(\''+obj[i]['rooms'][j]['room_name']+'\');$(\'room_list\').style.display=\'none\';checkRoom('+obj[i]['rooms'][j]['id']+',obj);">';
                               text_html += '<a href="#" style="width:45px;height:40px; text-decoration:none;" class="room AVAILABLE" lang="'+obj[i]['rooms'][j]['room_level_id']+'" id="'+obj[i]['rooms'][j]['id']+'" >';
                         text_html += '<span style="font-size:9px;font-weight:bold;color:#039" >'+obj[i]['rooms'][j]['room_name']+'<br />'+obj[i]['rooms'][j]['brief_name']+'</span><br />';
                   			   text_html += '</a>';
                         text_html += '</div>';
                         number_room++;
                    }
                    text_html += '</td></tr></table></td></tr>';
                }
                text_html += '</table>';
                document.getElementById("content_table").innerHTML = '<div style="width: 100%; text-align:center; height: 20px; padding-top: 2px; background: #00b2f9; color: #ffffff;">Có '+(number_room-num)+' Đặt phòng chưa gán phòng! </div>';
                document.getElementById("content_table").innerHTML += text_html;
                jQuery("#date_list").html(objs['in_date']);
                jQuery("#room_list").css('display','');
                //alert("có "+(number_room-num)+" Đặt phòng chưa gán phòng!");
            }
        }
        xmlhttp.open("GET","get_avalible_room.php?data=xxx&room_level_id="+room_levels_id+"&in_date="+in_date+"",true);
        xmlhttp.send();
    }
    //end---
</script>
