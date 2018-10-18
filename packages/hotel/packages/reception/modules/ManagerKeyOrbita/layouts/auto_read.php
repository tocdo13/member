<?php System::set_page_title("Read card");?>
<form method="POST" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="80%" align="center">
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >READ CARD</td>
                        
                    </tr>
                </table>
               <table width="80%" style="border: 1px solid #087E85;" >
                    <tr>
                        <td width ="100%" height="auto" align="center" style="background-color: silver; margin: 20px 10px;" >
                            <table width="500px" align="center" style="background: white; border: 1px solid; margin: 20px 40px;" >
                                <tr>
                                    <td colspan="2" align="center"><p style=" font-size: 16px; color:blue; font-family: tahoma;">[[.information_card.]]</p></td>
                                </tr>
                                
                                <tr>
                                    <td width ="100px" valign="top" style=" font-size: 16px; padding-left: 25px">[[.card_no.]]</td>
                                    <td style=" font-size: 16px;" align="center">
                                        <input name="card_no" type="text" id="card_no" value="" style="width: 191px; background-color: #E2E2E2; padding-left: 5px;" readonly="readonly"/>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td width ="100px" valign="top" style=" font-size: 16px; padding-left: 25px;">[[.building.]]</td>
                                    <td style=" font-size: 16px;" align="center">
                                        <input name="building" type="text" id="building" value="" style="width: 191px; background-color: #E2E2E2; padding-left: 5px;" readonly="readonly"/>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td width ="100px" valign="top" style=" font-size: 16px; padding-left: 25px;">[[.rooms.]]</td>
                                    <td style=" font-size: 16px;" align="center">
                                        <input name="room_name" type="text" id="room_name" value="" style="width: 191px; background-color: #E2E2E2; padding-left: 5px;" readonly="readonly"/>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td width ="100px" valign="top" style=" font-size: 16px; padding-left: 25px">[[.lock_no.]]</td>
                                    <td style=" font-size: 16px;" align="center">
                                        <input name="lock_no" type="text" id="lock_no" value="" style="width: 191px; background-color: #E2E2E2; padding-left: 5px;" readonly="readonly"/>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td width ="100px" valign="top" style=" font-size: 16px; padding-left: 25px">[[.commdoor.]]</td>
                                    <td style=" font-size: 16px;" align="center">
                                        <label id="commdoor" style="width: 200px; text-align: left; display: block; background-color: #E2E2E2;"></label>
                                    </td>
                                </tr>
                                
                                <tr height="30px">
                                    <td style=" font-size: 16px; padding-left: 25px">[[.start_date.]]</td>
                                    <td style=" font-size: 16px;" align="center">
                                        <input name="date_start" type="text" id="date_start" value="" style="text-align: center; background-color: #E2E2E2;" size="15"  readonly="readonly"/>
                                        <input name="time_start" type="text" id="time_start" value="" style="text-align: center; background-color: #E2E2E2; width: 70px;" size="5" readonly="readonly"/>
                                    </td>
                                </tr>
                                
                                <tr height="30px">
                                    <td style=" font-size: 16px; padding-left: 25px">[[.expiry_date.]]</td>
                                    <td style=" font-size: 16px;" align="center">
                                        <input name="date_expiry" type="text" id="date_expiry" value="" style="text-align: center; background-color: #E2E2E2;" size="15" readonly="readonly"/>
                                        <input name="time_expiry" type="text" id="time_expiry" value="" style="text-align: center;background-color: #E2E2E2; width: 70px;" size="5" readonly="readonly"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" style="padding-top: 10px;">
                                        <input type="text" name="notifi" id="notifi" style="border: 0; background: transparent; color: red; text-align: center;" readonly="true" />
                                        <input type="button" id="re_connect" name="re_connect" onclick="connectServer();" value="Reconnect" style="display: none;" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" >&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>                
            </td>
        </tr>
    </table>
    <input type="hidden" value="" name="width" id="width" />
</form>
<script>
    var ip = '<?php echo [[=ip=]]; ?>';
    var port = <?php echo [[=port=]]; ?>;
    jQuery(document).ready(function(){
        jQuery("#width").val(screen.width);
        connectServer();
    });
    
    function connectServer()
    {
        var ws = new WebSocket("ws://"+ip+":"+port);
		ws.onopen = function() {
            jQuery("#notifi").val("Connect to server success");
            jQuery("#re_connect").css("display","none");
		};

		ws.onmessage = function(evt) {
			var received_msg = evt.data;
            var arr_data = received_msg.split("_");
            //console.log(received_msg);
            if(arr_data.length == 6)
            {
                
                var card_no = arr_data[0];
                var building = arr_data[1];
                var roomno = arr_data[2];
                var tr_commdoor = arr_data[3];
                var intime = arr_data[4];
                var outtime = arr_data[5];
                jQuery("#room_name").val(roomno);
                jQuery("#building").val(building);
                jQuery("#card_no").val(card_no);
                jQuery("#lock_no").val(roomno);
                var arr_intime = intime.split(" ");
                jQuery("#date_start").val(arr_intime[0]);
                jQuery("#time_start").val(arr_intime[1]);
                var arr_outtime = outtime.split(" ");
                jQuery("#date_expiry").val(arr_outtime[0]);
                jQuery("#time_expiry").val(arr_outtime[1]);
                
                //for(var key in door_id_js)
//                {
//                    if(padLeft(door_id_js[key]['door_id'], roomno.length, "0") == roomno)
//                    {
//                        jQuery("#room_name").val(door_id_js[key]['room_name']);
//                        jQuery("#building").val(door_id_js[key]['building']);
//                    }
//                }
                
                var commdoor = "";
                for(var key in commdoor_js)
                {
                    if(tr_commdoor.indexOf(commdoor_js[key]['stt']) >= 0)
                    {
                        if(commdoor != "")
                        {
                            commdoor += "<br/>+ "+commdoor_js[key]['name'];
                        }
                        else
                        {
                            commdoor += "+ "+commdoor_js[key]['name'];
                        }
                    }
                }
                
                jQuery("#commdoor").html(commdoor);
            }
            else if(to_numeric(received_msg)<0 && to_numeric(received_msg) > -15)
            {
                jQuery("#room_name").val("");
                jQuery("#building").val("");
                jQuery("#card_no").val("");
                jQuery("#lock_no").val("");
                jQuery("#date_start").val("");
                jQuery("#time_start").val("");
                jQuery("#date_expiry").val("");
                jQuery("#time_expiry").val("");
                jQuery("#commdoor").html("");
            }
		};
        
		ws.onclose = function() {
			alert("Connection is closed...");
            jQuery("#notifi").val("Can not connect to server");
            jQuery("#re_connect").css("display","");
		};
    }
    
    function padLeft(string, pad_length, padString)
    {
        if(string.length >= pad_length)
            return string;
        else
            return padLeft(padString+string, pad_length, padString);
    }
</script>