<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#">
            <span class="multi-input"><input name="mi_group[#xxxx#][id]" type="hidden" id="id_#xxxx#"  tabindex="-1" style=""/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][id_data]" type="hidden" id="id_data_#xxxx#"  tabindex="-1" style=""/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][stt]" type="text" id="stt_#xxxx#"  tabindex="-1" style="width:50px ; text-align: center; margin-left: 40px; background-color: #C0C0C0;font-size: 14px; " readonly="readonly" /></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][name]" type="text" id="name_#xxxx#"  tabindex="-1" style=" width:150px ; text-align: left; font-size: 14px; "/></span>
		</div>
        <br clear="all" />
	</span>
</span>
<form method="POST" name="ManageDoorform">
    <table width ="100%">
        <tr>
            <td align="center" width="100%">
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px;"><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >MANAGE COMMDOOR</td>
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
                                            <span class="multi-input-header" style="float:left;width:150px; text-align: center; ">Name</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" >
                                            <div >
                                				<span id="mi_group_all_elems">
                                				</span>
                                			</div>
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

    
</script>