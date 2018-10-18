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
                <?php 
                if(isset([[=result=]]))
                {
                    $result = [[=result=]];
                    ?>
               <table width="80%" style="border: 1px solid #087E85;" >
                    <tr>
                        <td width ="100%" height="auto" align="center" style="background-color: silver; " >
                            <div style=" width: 60%; height: auto; border: 3px inset gray; background-color: white; margin: 20px 10px; ">
                                <div style="width: 100%; height: auto;">
                                    <p style=" font-size: 16px; color:blue; font-family: tahoma;">[[.information_card.]]</p>
                                </div>
                                <table width="100%" align="center" style="margin: 20px 40px;" >
            
                                    <tr>
                                        <td width ="100px" valign="top" style=" font-size: 16px;">[[.rooms.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="room_name" type="text" id="room_name" value="<?php echo $result["room_name"] ?>" style="width: 200px; background-color: #E2E2E2;" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    
                                    
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.start_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_start" type="text" id="date_start" value="<?php echo $result["BeginTime"]; ?>" style="text-align: center; background-color: #E2E2E2;" size="15"  readonly="readonly"/>
                                            <input name="time_start" type="text" id="time_start" value="<?php echo $result["Time_B"]; ?>" style="text-align: center; background-color: #E2E2E2;" size="5" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.expiry_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_expiry" type="text" id="date_expiry" value="<?php echo $result["EndTime"]; ?>" style="text-align: center; background-color: #E2E2E2;" size="15" readonly="readonly"/>
                                            <input name="time_expiry" type="text" id="time_expiry" value="<?php echo $result["Time_E"]; ?>" style="text-align: center;background-color: #E2E2E2;" size="5" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    
                                    <tr height="30px">
                                        <td style="font-size: 16px;">[[.reservation.]]</td>
                                        <td style="font-size: 16px;"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$result["reservation_id"]));?>" style=" font-size: 16px; color: blue;"><?php if(isset($result["reservation_id"])) {echo '#'.$result["reservation_id"];} ?></a>&nbsp;
                                            
                                        </td>
                                    </tr>
                                    
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.arrival_date.]]</td>
                                        <td style=" font-size: 16px;">
                                        <input name="arrival_date" type="text" id="arrival_date" value="<?php if(isset($result["arrival_date"])) { echo $result["arrival_date"];} ?>" style="text-align: center; background-color: #E2E2E2;" size="15" readonly="readonly"/> &nbsp;
                                            To<input name="departure_date" type="text" id="departure_date" value="<?php if(isset($result["departure_date"])) { echo $result["departure_date"];} ?>" style="text-align: center; background-color: #E2E2E2;" size="15" readonly="readonly"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>                
            </td>
        </tr>
    </table>
    <?php
    } 
    ?>
    <input type="hidden" value="" name="width" id="width" />
</form>
<script>
    jQuery(document).ready(function(){
        jQuery("#width").val(screen.width);
        jQuery("#home").click(function(e)
        {
            window.location.href = '?page=manager_key_adel&portal=' + '<?php echo PORTAL_ID; ?>';
        });
        
    });
</script>