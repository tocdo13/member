<form method="POST" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; " align="left" >MENU</td>
                    </tr>
                </table>
                    
                </div>
                <table width="800px" style="border: 1px solid #087E85;" >
                    <tr>
                        <td width ="150px" height="430px" align="center" style="background-color: silver; " >
                        
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <!--<input class="function" id="create" type="button" value="Create" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />-->
                            &nbsp;&nbsp;&nbsp;
                            <input class="function" id="ip_sever" type="button" value="Set IP" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />
                            &nbsp;&nbsp;&nbsp;
                            <input class="function" id="manage_door" type="button" value="Manage Door" style="width: 140px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />
                            
                            <!--
                            <input class="function" id="checkin" type="button" value="Checkin" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />
                            &nbsp;&nbsp;&nbsp;
                            <input class="function" id="checkout" type="button" value="Checkout" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />
                            -->
                            <br /><br />
                            <!--<input class="function" id="create_group" type="button" value="Create group" style="width: 140px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />-->
                            <!--<input class="function" id="lost_card" type="button" value="Lost card" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />
                            &nbsp;&nbsp;&nbsp;
                            <input class="function" id="read" type="button" value="Read" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />
                            &nbsp;&nbsp;&nbsp;
                            <input class="function" id="checkout" type="button" value="Check out" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />-->
                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <input type="hidden" value="" name="width" id="width" />
</form>

<script>
    jQuery(document).ready(function(){
        jQuery("#width").val(screen.width);
        jQuery(".function").click(function(e)
        {
            var url = '<?php echo Url::build_current(array('portal'=>PORTAL_ID));?>'+'&cmd='+this.id;
            window.location.href = url;            
        });
    });
</script>