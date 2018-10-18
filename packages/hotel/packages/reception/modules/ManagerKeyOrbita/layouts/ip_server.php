<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#">
            <span class="multi-input"><input name="mi_group[#xxxx#][id]" type="hidden" id="id_#xxxx#"  tabindex="-1" style=""/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][index]" type="text" id="index_#xxxx#"  tabindex="-1" style="width:50px ; text-align: center; margin-left: 40px; background-color: #C0C0C0;font-size: 14px; " readonly="readonly" /></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][ip]" type="text" id="ip_#xxxx#"  tabindex="-1" style=" width:150px ; text-align: center; font-size: 14px; "/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][port]" type="text" id="port_#xxxx#"  tabindex="-1" style="width:100px ; text-align: center;font-size: 14px; "/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][port_websocket]" type="text" id="port_websocket_#xxxx#"  tabindex="-1" style="width:100px ; text-align: center;font-size: 14px; "/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][reception]" type="text" id="reception_#xxxx#"  tabindex="-1" style="width:100px ; text-align: center;font-size: 14px;"/></span>
            
            
            <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:40px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_group','#xxxx#','');input_count--; " style="cursor:pointer;"/>
			</span>
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>
<form method="POST" name="Ipseverform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >IP SEVER</td>
                        <td style="width: 260px; " align="right">
                            <input type="submit" id="save" value="Save" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold;" />
                            &nbsp;&nbsp;&nbsp;
                            <input type="button" id="exit" value="Exit" onclick="window.close();" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold;" />
                        </td>
                    </tr>
                </table>
                <table width="800px" style="border: 1px solid #087E85; " >
                    <tr>
                        <td width ="100%" height="auto" align="center" style="background-color: silver; " >
                            <div style=" width:750px; border: 3px inset gray; background-color: white; padding-right: 10px; margin: 20px 20px;">
                                <table width="100%" style="margin-top: 10px;">
                                    <tr>
                                        <td>
                                            <span class="multi-input-header" style="float:left;width:50px; text-align: center; margin-left: 40px; ">Index</span>
                                            <span class="multi-input-header" style="float:left;width:150px; text-align: center; ">IP</span>
                                            <span class="multi-input-header" style="float:left;width:100px; text-align: center; ">Port</span>
                                            <span class="multi-input-header" style="float:left;width:100px; text-align: center; ">Port websocket</span>
                                            <span class="multi-input-header" style="float:left;width:100px; text-align: center; ">Reception</span>
                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" >
                                            <div>
                                				<span id="mi_group_all_elems">
                                				</span>
                                			</div>
                                			<div style="padding-top: 2px; margin-left: 40px;" ><a href="javascript:void(0);" onclick="mi_add_new_row('mi_group');AddInput(input_count);" class="button-medium-add">[[.Add.]]</a></div>
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
    <input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
</form>

<script type="text/javascript">
    <?php if(isset($_REQUEST['mi_group'])){echo 'var bars = '.String::array2js($_REQUEST['mi_group']).';';}else{echo 'var bars = [];';}?>
    mi_init_rows('mi_group',bars);
function AddInput(input_count){
        jQuery("#index_"+input_count).val(input_count-100);
    }
</script>