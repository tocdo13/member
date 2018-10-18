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
                        
                            <!--<input class="function" id="create" type="button" value="Create" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />-->
                            &nbsp;&nbsp;&nbsp;
                            <input class="function" id="ip_server" type="button" value="Set IP" style="width: 120px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />
                            &nbsp;&nbsp;&nbsp;
                            <input class="function" id="manage_door" type="button" value="Manage Door" style="width: 140px; height: 80px; font-size: 20px; font-weight: bold; color: #1F6B7A; " />

                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" value="" name="width" id="width" />
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

<script>
    jQuery(document).ready(function(){
        jQuery("#width").val(screen.width);
        jQuery(".function").click(function(e)
        {
            var url = '<?php echo Url::build('manager_key_hune');?>'+'&cmd='+this.id;
            window.location.href = url;            
        });
    });
</script>