
<form method="POST" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="80%" align="center">
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.read_key_title.]]</td>
                        <td style="width: 260px; " align="right" >
                            <!--<input type="button" id="home" value="Home" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />-->
                        </td>
                    </tr>
                </table>
                
               <table width="80%" style="border: 1px solid #087E85;" >
                    <tr>
                        <td width ="100%" height="auto" align="center" style="background-color: silver; " >
                            <div style=" width: 60%; height: auto; border: 3px inset gray; background-color: white; margin: 20px 10px; ">
                                <div style="width: 100%; height: auto;">
                                    <p style=" font-size: 16px;color:blue; font-family: tahoma;">[[.information_card.]]</p>
                                </div>
                                <table width="100%" align="center" style="margin: 20px 40px;" >
                                    <?php
                                        if(isset([[=result=]]))
                                        { 
                                    ?>
                                    <tr height="30px">
                                        <td width ="150px" valign="top" style=" font-size: 16px;">[[.room.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input type="text" name="door_id"  id="door_id" value="<?php echo [[=result=]]['door_id']; ?>" style="width: 200px; background-color: #E2E2E2;" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    
                                    <?php
                                        if([[=result=]]['can_open_rooms']!='')
                                        {
                                            ?>
                                            <tr height="30px">
                                                <td width ="100px" valign="top" style=" font-size: 16px;">[[.open_room_other.]]</td>
                                                <td style=" font-size: 16px;">
                                                    <input type="text" name="can_open_rooms"  id="can_open_rooms" value="<?php echo [[=result=]]['can_open_rooms']; ?>" style="width: 200px; background-color: #E2E2E2;" readonly="readonly"/>
                                                </td>
                                            </tr>
                                            <?php 
                                        } 
                                    ?>
                                    
                                    <!--<tr height="30px">
                                        <td style=" font-size: 16px;">Start date</td>
                                        <td style=" font-size: 16px;">
                                            <input  type="text" name="date_start" id="date_start" value="<?php //echo [[=result=]]['s_start']; ?>" style="text-align: center; background-color: #E2E2E2;" size="15"  readonly="readonly"/>
                                            <input  type="text" name="time_start" id="time_start" value="<?php //echo [[=result=]]['s_time']; ?>" style="text-align: center; background-color: #E2E2E2;" size="5" readonly="readonly"/>
                                        </td>
                                    </tr>-->
                                    
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.expiry_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_expiry" type="text" id="date_expiry" value="<?php echo [[=result=]]['s_end']; ?>" style="text-align: center; background-color: #E2E2E2;" size="15" readonly="readonly"/>
                                            <input name="time_expiry" type="text" id="time_expiry" value="<?php echo [[=result=]]['e_time']; ?>" style="text-align: center;background-color: #E2E2E2;" size="5" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    
                                    <tr height="30px">
                                        <td style="font-size: 16px;">Recode</td>
                                        <td style="font-size: 16px;"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=result=]]['recode']));?>" style=" font-size: 16px; color: blue;">#<?php echo [[=result=]]['recode']; ?></a>&nbsp; 
                                        </td>
                                    </tr>
                                    
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.arrival_date.]]</td>
                                        <td style=" font-size: 16px;">
                                        <input  type="text" name="arrival_date" id="arrival_date" value="<?php echo [[=result=]]['arrival']; ?>" style="text-align: center; background-color: #E2E2E2;" size="15" readonly="readonly"/> &nbsp;
                                            [[.to.]] &nbsp;<input type="text" name="departure_date" id="departure_date" value="<?php echo [[=result=]]['departure']; ?>" style="text-align: center; background-color: #E2E2E2;" size="15" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    <?php
                                        } 
                                    ?>
                                </table>
                            </div>
                        </td>
                    </tr>                
            </td>
        </tr>
    </table>
    <input type="hidden" value="" name="width" id="width" />
</form>
<script>
    jQuery(document).ready(function(){
        jQuery("#width").val(screen.width);
        jQuery("#home").click(function(e)
        {
            window.location.href = '?page=manager_key_salto&portal=' + '<?php echo PORTAL_ID; ?>';
        });
        
    });
</script>