<?php System::set_page_title("Cancel card");?>
<form method="POST" name="Deletekeyform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >CHECKOUT CARD</td>
                        <td style="width: 260px; " align="right">
                            <input type="button" id="exit" value="Exit" onclick="window.close();" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold;" />
                        </td>
                    </tr>
                </table>
                <table width="800px" style="border: 1px solid #087E85; " >
                    <tr>
                        <td width ="100%"align="center" style="background-color: silver; " >
                            <div style=" width:600px; border: 3px inset gray; background-color: white; padding-right: 10px; margin: 20px 20px;">
                                <?php
                                if([[=result=]]!='')
                                {
                                    ?>
                                    <div style="width: 100%; height: auto;">
                                        <p style=" font-size: 16px;color:blue; font-family: tahoma;"><?php echo [[=result=]]; ?></p>
                                    </div>
                                    <?php
                                } 
                                ?>
                                
                                <table width="600px" style="margin-top: 10px; margin-bottom: 10px;">
                                   
                                    <tr height="25px">
                                        <td align="center">
                                            <span style=" font-size: 14px;">[[.num_key.]] : </span>
                                            <input type="text" name="number_keys" style="width:35px;"  id="number_keys" value="1" />
                                            <span style=" font-size: 14px;">[[.reception.]] : </span>
                                            <select id="reception_id" name="reception_id" style="width: 85px;" >
                                                [[|reception_id|]]
                                            </select>
                                            
                                            <input id="delete" name="delete" type="submit" value="Checkout card" style="width: 150px; height: 40px; font-size: 14px; font-weight: bold; color: #1F6B7A; " />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" value="" name="width" id="width" />
</form>
<script type="text/javascript">
    jQuery("#width").val(screen.width);
    
</script>
