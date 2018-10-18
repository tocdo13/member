<div style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.8);">
    <form method="post" name="HotelRoomAvailabilityForm">
        <div style="text-align: center;"><h3 style="font-size: 20px; line-height: 35px; text-transform: uppercase; color: #ffffff; text-shadow: 5px 5px 5px #000000;">[[.change_room_level_group.]]</h3></div>
        <div style="width: 600px; height: 400px; margin: 50px auto; border: 1px solid #555555; box-shadow: 5px 5px 15px #000000; background: #ffffff;">
            <fieldset style="width: 90%; margin: 10px auto; text-align: center;">
                <legend>[[.search.]]</legend>
                <table style="width: 100%;">
                    <tr style="text-align: center;">
                        <td>
                            [[.from_date.]]: 
                            <input  name="from_time" style=" display:none;width:30px; font-size:10px;" type="text" id="from_time" title="00:00" maxlength="5" /> 
                            <input name="from_date" type="text" id="from_date" class="date-input" onchange="check_value_date(true);" />
                        </td>
                        <td>
                            [[.to_date.]]: 
                            <input  name="to_time" style=" display:none;width:30px; font-size:10px;" type="text" id="to_time" title="00:00" maxlength="5" />  
                            <input name="to_date" type="text" id="to_date" class="date-input" onchange="check_value_date(false);" />
                        </td>
                        <td>
                            <input name="search_date" type="button" value="[[.search.]]" onclick="fun_check_submit();" style="border: 1px solid #171717; background: #fac73e; font-weight: bold; padding: 3px;" />
                        </td>
                    </tr>
                </table>
            </fieldset>
            <!--IF:cond(Url::get('cmd')=='select')-->
        		<fieldset style="width: 90%; margin: 20px auto; text-align: center;">
        			<legend>[[.select_room_level.]]</legend>
        			<!--LIST:room_levels-->
                        <!--IF:cond_room_level([[=room_levels.vacant_room=]]>0 or [[=over_book=]])-->
            			<div class="row-even">
                            <input  name="room_level_[[|room_levels.id|]]" type="text" id="room_leel_[[|room_levels.id|]]" style="width:20px; display:none;" title="[[.room_quantity.]]" value="1" readonly="readonly" />
                            <a href="#" style="color: #00b2f9;" onclick="selectRoomLevel(<?php echo Url::iget('object_id');?>,'[[|room_levels.name|]]',[[|room_levels.id|]],<?php echo Url::iget('input_count');?>,[[|room_levels.vacant_room|]])">
                                [[|room_levels.name|]] 
                                <b>([[|room_levels.vacant_room|]])</b>
                            </a>
                        </div>
                        <br />
                        <!--ELSE-->
                        <div class="row-even"><input  name="room_level_[[|room_levels.id|]]" type="text" id="room_leel_[[|room_levels.id|]]" style="width:20px; display:none;" title="[[.room_quantity.]]" value="1" readonly="readonly" />&nbsp;[[|room_levels.name|]] <b>(0)</b>|<span style="color:red;"> [Hết Phòng]</span></div><br />
                        <!--/IF:cond_room_level-->
        			<!--/LIST:room_levels-->
        		</fieldset>
    		<!--/IF:cond-->
        </div>
    </form>
</div>
<script>
room_levels = <?php echo String::array2js([[=room_levels=]]);?>;
var count = <?php echo Url::get('count'); ?>;
var arrival_date = '<?php echo $_GET['from_date']; ?>';
var departure_date = '<?php echo $_GET['to_date']; ?>';
var cmd = '<?php echo isset($_GET['get_cmd'])?$_GET['get_cmd']:''; ?>';
var over_book = <?php echo OVER_BOOK; ?>;
console.log(arrival_date);
check_submit = 0;
jQuery("#from_date").datepicker();
jQuery("#to_date").datepicker();
function selectRoomLevel(index,roomLevelName,roomLevelId,inputCount,number_room)
{
    if((to_numeric(number_room)>=to_numeric(count) && to_numeric(over_book)==0) || to_numeric(over_book)==1)
    {
        if(arrival_date==jQuery("#from_date").val() && departure_date==jQuery("#to_date").val())
        {
            for(var i=101;i<=inputCount;i++)
            {
                if( (opener.document.getElementById("check_box_"+i).checked==true || chec_box_tick(inputCount)==true) && (opener.document.getElementById("status_"+i).value=='BOOKED' || cmd=='add') )
                {
                    if(opener.document.getElementById('room_level_id_'+i).value!=roomLevelId) {
                            opener.document.getElementById('price_'+i).value = number_format(room_levels[roomLevelId]['price'],2);
                            opener.document.getElementById('usd_price_'+i).value = number_format(room_levels[roomLevelId]['usd_price'],2);
                        }
            		opener.document.getElementById('room_id_'+i).value = '';
            		opener.document.getElementById('room_name_'+i).value = '#' + i;
            		opener.document.getElementById('room_level_name_'+i).value = roomLevelName;
            		opener.document.getElementById('room_level_id_'+i).value = roomLevelId;
                }
        		
            }
        	window.close();
         }
         else
         {
            if(confirm("Thao tác này sẽ thay đổi ngày đến và ngày đi của toàn đoàn lần lượt là:"+jQuery("#from_date").val()+" và "+jQuery("#to_date").val()+" Bạn chắc chắn thực hiện chúng ?"))
            {
                for(var i=101;i<=inputCount;i++)
                {
                    if( (opener.document.getElementById("check_box_"+i).checked==true || chec_box_tick(inputCount)==true) && (opener.document.getElementById("status_"+i).value=='BOOKED' || cmd=='add') )
                    {
                        if(opener.document.getElementById('room_level_id_'+i).value!=roomLevelId) {
                            opener.document.getElementById('price_'+i).value = number_format(room_levels[roomLevelId]['price'],2);
                            opener.document.getElementById('usd_price_'+i).value = number_format(room_levels[roomLevelId]['usd_price'],2);
                        }
                		opener.document.getElementById('room_id_'+i).value = '';
                		opener.document.getElementById('room_name_'+i).value = '#' + i;
                		opener.document.getElementById('room_level_name_'+i).value = roomLevelName;
                		opener.document.getElementById('room_level_id_'+i).value = roomLevelId;
                        opener.document.getElementById('arrival_time_'+i).value = jQuery("#from_date").val();
                        opener.document.getElementById('departure_time_'+i).value = jQuery("#to_date").val();
                        opener.count_price(i,true);
                    }
                }
            	window.close();
            }
         }
     }
     else
     {
        alert("số phòng trống không đủ để đổi hạng phòng này !");
     }
}
function chec_box_tick(inputCount)
{
    var check=true;
    var tick=0;
    var none_tick=0;
    for(var i=101;i<=inputCount;i++)
    {
        if(opener.document.getElementById("check_box_"+i).checked==true)
            tick++;
        else
            none_tick++;
    }
    if(tick!=0 && none_tick!=0)
    {
        check=false;
    }
    return check;
}
function check_value_date(flag)
{
    var from_date = jQuery("#from_date").val().split("/");
    var to_date = jQuery("#to_date").val().split("/");
    var from_date_arr = new Date(from_date[2],from_date[1],from_date[0]);
    var to_date_arr = new Date(to_date[2],to_date[1],to_date[0]);
    if(to_date_arr==''){
        if(flag)
        {
            //tu ngay => tinh den ngay = tu ngay + 1
             var from_date = document.getElementById('from_date').value;
             var from_date = Date.parseExact(from_date,"dd/MM/yyyy");
             var to_date = from_date.add(1).day();
             var str_to_date = to_date.toString('dd/MM/yyyy');
             document.getElementById('to_date').value = str_to_date;
        }
        else
        {
            //den ngay => tu ngay = den ngay -1
            var to_date = document.getElementById('to_date').value;
            var to_date = Date.parseExact(to_date,"dd/MM/yyyy");
            var from_date = to_date.add(-1).day();
            var str_from_date = from_date.toString('dd/MM/yyyy');
            document.getElementById('from_date').value = str_from_date;
        }                               
    }else{
        if(to_date_arr<=from_date_arr){
            if(flag)
            {
                //tu ngay => tinh den ngay = tu ngay + 1
                 var from_date = document.getElementById('from_date').value;
                 var from_date = Date.parseExact(from_date,"dd/MM/yyyy");
                 var to_date = from_date.add(1).day();
                 var str_to_date = to_date.toString('dd/MM/yyyy');
                 document.getElementById('to_date').value = str_to_date;
            }
            else
            {
                //den ngay => tu ngay = den ngay -1
                var to_date = document.getElementById('to_date').value;
                var to_date = Date.parseExact(to_date,"dd/MM/yyyy");
                var from_date = to_date.add(-1).day();
                var str_from_date = from_date.toString('dd/MM/yyyy');
                document.getElementById('from_date').value = str_from_date;
            } 
        }
    }
      
}
function fun_check_submit()
{
    check_submit++;
    HotelRoomAvailabilityForm.submit();
}
</script>