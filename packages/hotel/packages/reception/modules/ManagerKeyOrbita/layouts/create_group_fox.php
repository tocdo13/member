<?php System::set_page_title("Make card");?>
<span style="display:none">
    <span id="mi_group_sample">
        <div id="input_group_#xxxx#">
            <input name="mi_group[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:29px; background:#F2F4CE"/>
            <input name="mi_group[#xxxx#][rr_id]" type="hidden" readonly="" id="rr_id_#xxxx#"  tabindex="-1" style="width:29px; background:#F2F4CE"/>
            <span class="multi-input"><input name="mi_group[#xxxx#][index]" type="text" readonly="true" id="index_#xxxx#"  tabindex="1" style="background:#CCC; width:30px ; text-align: center; "/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][room_id]" type="text" id="room_id_#xxxx#"  tabindex="1" style="display: none;"/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][door_id]" type="text" id="door_id_#xxxx#"  tabindex="1" style="display: none;"/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][building]" type="text" id="building_#xxxx#"  tabindex="1" style="display: none;"/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  tabindex="1" style="background:#F2F4CE; width:56px ; text-align: center; " readonly="readonly" /></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][date_start]" type="text" id="date_start_#xxxx#"  tabindex="1" style="background:#F2F4CE; width:74px ; text-align: center; " readonly="readonly" /></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][time_start]" type="text" id="time_start_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:45px ; text-align: center; " readonly="readonly" /></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][date_expiry]" type="text" id="date_expiry_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:74px ; text-align: center; " readonly="readonly"/></span>
            <span class="multi-input"><input name="mi_group[#xxxx#][time_expiry]" type="text" id="time_expiry_#xxxx#"  tabindex="-1" style="background:#F2F4CE; width:45px ; text-align: center; " readonly="readonly"/></span>
            <span class="multi-input">
                <select  name="mi_group[#xxxx#][number_key]" style="width:85px;"  id="number_key_#xxxx#">
                    <option value="0">0</option>
                    <option value="1" selected="true">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                </select>
            </span>
            <span class="multi-input" >
                <div style="width: 120px; z-index: 99;" onclick="showCheckboxes(#xxxx#)">
                    <input id="commdoor_#xxxx#" name="mi_group[#xxxx#][commdoor]" type="text" style="width: 120px; " readonly="true" />
                    <div></div>
                </div>
                <div id="checkboxes_#xxxx#" style="overflow: auto; float: left; position: absolute; z-index: -1; background-color:grey; width: 124px; border: 1;">
                    <!--LIST:commdoor-->
                        <label  style="float: left;"><input type="checkbox" class="comm_#xxxx#" name="comm_#xxxx#_<?php echo [[=commdoor.id=]]; ?>" value="[[|commdoor.id|]]" onclick="setComm(#xxxx#)" />[[|commdoor.name|]]</label><br />
                    <!--/LIST:commdoor-->
                </div>
            </span>
            <span class="multi-input"><input name="mi_group[#xxxx#][loss_key]" type="checkbox" id="loss_key_#xxxx#" value="1"  tabindex="-1" style="width:77px ; text-align: center; "/></span>
            <!--<span class="multi-input" style="width:40px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_group','#xxxx#',''); input_count--;" style="cursor:pointer;"/><!--</span>--> 
        </div>
        <br clear="all" />
    </span>
</span>
<form method="POST" name="Createform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.manage_key.]]</td>
                        <td style="width: 260px; " align="right" >
                            <input type="button" id="exit" value="Exit" onclick="window.close();" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />
                        </td>
                    </tr>
                </table>
                <table width="800px" style="border: 1px solid #087E85;" >
                    <tr>
                        <td width ="150px" align="center" style="background-color: silver; height: auto;" >
                            <div style=" width:90%; border: 3px inset gray; background-color: white; margin-top:10px; margin-bottom: 10px;">
                                <table width="95%" style="margin-left: 10px; margin-top: 10px;vertical-align: central;" >
                                    <tr>
                                        <td>
                                            <span class="multi-input-header" style="float:left;width:30px; text-align: center; ">[[.stt.]]</span>
                                            <span class="multi-input-header" style="float:left;width:56px; text-align: center; ">[[.rooms.]]</span>
                                            <span class="multi-input-header" style="float:left;width:125px; text-align: center; ">[[.create_date.]]</span>
                                            <span class="multi-input-header" style="float:left;width:123px; text-align: center; ">[[.expiry_date.]]</span>
                                            <span class="multi-input-header" style="float:left;width:80px; text-align: center; ">[[.number_key.]]</span>
                                            <span class="multi-input-header" style="float:left;width:120px; text-align: center; ">[[.commdoor.]]</span>
                                            <span class="multi-input-header" style="float:left;width:80px; text-align: center; ">[[.loss_key.]]</span>
                                            <!--<span class="multi-input-header" style="float:left;width:40px; text-align: center; ">[[.delete.]]</span>-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" >
                                            <div>
                                                <span id="mi_group_all_elems">
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr height="60px" >
                                        
                                        <td align="center" >
                                            <span style="font-weight: bold; font-size: 14px;">[[.reception.]]</span>
                                            <select id="reception_id" name="reception_id" style="width: 80px;" >
                                                [[|reception_id|]]
                                            </select>
                                            &nbsp;&nbsp;&nbsp;
                                            <input name="create" id="create" type="submit" value="[[.create_key.]]" onclick="return check_input();" style="width: 100px; height: 40px; font-size: 16px; font-weight: bold; color: #1F6B7A; " />
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
</form>

<script type="text/javascript">
    <?php if(isset($_REQUEST['mi_group'])){echo 'var bars = '.String::array2js($_REQUEST['mi_group']).';';}else{echo 'var bars = [];';}?>
    mi_init_rows('mi_group',bars);
    var expanded = false;
    jQuery(document).ready(function(){
        for(var i=101;i<=input_count;i++){
            jQuery("#index_"+i).val(i-100);
            /*if(jQuery("#date_expiry_"+i)){
                jQuery("#date_expiry_"+i).datepicker();//{ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' }
            }
            if(jQuery("#time_expiry_"+i)){
                jQuery("#time_expiry_"+i).mask('99:99');
            }*/
        }
    });
    
    function showCheckboxes(index,hide=false) {
        var checkboxes = document.getElementById("checkboxes_"+index);
        if (checkboxes.style.zIndex == -1 && !hide) {
            checkboxes.style.display = "block";
            checkboxes.style.zIndex = 100;
            //expanded = true;
        } else {
            checkboxes.style.display = "none";
            checkboxes.style.zIndex = -1;
            //expanded = false;
        }
    }
    
    function setComm(index) {
        var val = "";
        jQuery(".comm_"+index+":checkbox:checked").each(function () {
            if(val != "")
            {
                val += ",";
            }
            val += this.value;
        });
        jQuery("#commdoor_"+index).val(val);
    }
    
    function check_input()
    {
        for(var i=101;i<=input_count;i++){
            if(!jQuery("#date_start_"+i).val())
            {
                alert('Nhập thời gian tạo thẻ!');
                return false;
            }
            if(!jQuery("#date_expiry_"+i).val())
            {
                alert('Nhập thời gian hết hạn thẻ!');
                return false;
            }
        }
        return true;
    }
</script>