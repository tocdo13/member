<form method="POST" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >DELETE KEY</td>
                        <td style="width: 260px; " align="right" >
                            <input type="button" id="home" value="Home" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />
                            
                        </td>
                    </tr>
                </table>
                    
                </div>
                <table width="800px" style="border: 1px solid #087E85;" >
                    <tr>
                        <td width ="150px" height="430px" align="center" style="background-color: silver; " >
                            <span style="color: red; font-size: 28px; " ><b>[[|status|]]</b></span>
                            <br /><br />
                            <span style="font-size: 20px;" >[[|detail|]]</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
<script>
    jQuery(document).ready(function(){
        jQuery("#home").click(function(e)
        {
            window.location.href = '?page=manager_key&portal=default';
        });
        
    });
</script>