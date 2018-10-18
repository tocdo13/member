<style>
.export_xml
{
    float: left;
    display: none;
}

</style>
<table cellpadding="15" cellspacing="0" width="100%" border="0" class="table-bound">
		<tr>
        	<td width="90%" class="" style="text-transform: uppercase; font-size: 20px;"><i class="fa fa-address-book-o w3-text-orange" style="font-size: 30px;"></i> [[.traveller_list.]]</td>
        </tr>
</table> 
<table bgcolor="#FFFFFF" cellspacing="0" width="100%">
    <tr>
        <td width="100%" align="center" > 
            <fieldset>
            <legend class="title">[[.search.]]</legend>
            <form name="SearchTravellerForm" method="post">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
                <td align="right" nowrap="nowrap">[[.full_name.]]</td>
                <td>:</td>
                <td nowrap>
                <input name="full_name" type="text" id="full_name" style="width:100px" /></td><td align="right" nowrap>[[.passport.]]</td>
                <td>:</td>
                <td nowrap>
                <input name="passport" type="text" id="passport" style="width:100px" /></td>
                <td align="right" nowrap="nowrap">[[.nationality.]]</td>
                <td>&nbsp;</td>
                <td nowrap="nowrap"><select name="nationality" id="nationality" style="width:100px" onchange="SearchTravellerForm.submit();"></select></td>
                
                <td align="right" nowrap="nowrap"><input type="submit" value="[[.search.]]" style="width:120px;" /></td>
                <!--<td nowrap="nowrap"><a id="export_new_excel" href="?page=traveller&cmd=export&type=1" class="button-medium-add button_stylea">[[.export_new_traveller.]]</a></td>-->
                <td nowrap><input name="export_xml_nn" type="submit" value="[[.export_xml_nn.]]" style="width: 160px;"/></td>
            </tr>
            <tr>
                <td align="right" nowrap>[[.arrival_date.]]</td>
                <td>:</td>
                <td nowrap>
                <input name="arrival_time" type="text" id="arrival_time" style="width:100px" /></td>
                <td align="right" nowrap="nowrap">[[.group.]]</td>
                <td>:</td>
                <td nowrap="nowrap"><select name="reservation_id" id="reservation_id" style="width:100px" onchange="SearchTravellerForm.submit();"></select></td>
                <td align="right" nowrap="nowrap">[[.arrival_room_today.]]</td>
                <td>:</td>
                <td nowrap="nowrap"><select name="reservation_room_id" id="reservation_room_id" style="width:100px" onchange="SearchTravellerForm.submit();"></select>
                </td>
                
                <td nowrap><input name="export_file_excel" type="submit" value="[[.export_file_excel.]]" style="width:120px;"/></td>
                <td nowrap><input onclick="dowload_file();" type="button" value="[[.dowload_template_file.]]" style="width: 160px;" /></td>
            </tr>
            </table>
            <input name="id_check_box" type="hidden" id="id_check_box" />
            </fieldset>
           <br />
            <div class="export_xml">
                <a href="<?php echo [[=file_name=]].'-pa18-'.date('d-m-Y', Date_Time::to_time(Url::get('arrival_time'))) . '.xml'; ?>" target="_blank" download="<?php echo [[=file_name=]].'-pa18-'.date('d-m-Y', Date_Time::to_time(Url::get('arrival_time'))) . '.xml'; ?>" style="text-decoration: none;" onclick="donwload_xml();"><span style="color: red;">Đã xuất file thành công! Vui lòng click vào đây để tải về!</span></a> 
            </div>
            <div class="w3-container">
            <div class="w3-responsive">
              <table class="w3-table-all" id="mytable"  cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
                    <h4 style="float: left;" class="w3-text-indigo">1. DANH SÁCH PA18 NGƯỜI VIỆT NAM</h4>
                    <tr class="w3-light-gray w3-border" style="height: 24px;">
                        <th width="1%" title="[[.check_all.]]">[[.no.]]</th>
                        <th align="left" nowrap="nowrap">[[.first_name.]]</th>
                        <th align="left" nowrap="nowrap">[[.last_name.]]</th>
                        <th align="left" nowrap="nowrap">[[.gender.]]</th>
                        <th align="left" nowrap="nowrap">[[.nationality.]]</th>
                        <th align="left" nowrap="nowrap">[[.overseas_vietnamese.]]</th>
                        <th align="left" nowrap="nowrap">[[.birth_day.]]</th>
                        <th align="left" nowrap="nowrap">[[.certification_type.]]</th>
                        <th align="left" nowrap="nowrap">[[.certification_number.]]</th>
                        <th align="left" nowrap="nowrap">[[.religion.]]</th>
                        <th align="left" nowrap="nowrap">[[.room_number.]]</th>
                        <th align="left" nowrap="nowrap">[[.from_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.to_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.reason_for_stay.]]</th>
                        <th align="left" nowrap="nowrap">[[.note.]]</th>
                        <th align="left" nowrap="nowrap">[[.moved.]]</th>
                        <th align="left" nowrap="nowrap">[[.ethnic.]]</th>
                        <th align="left" nowrap="nowrap">[[.province.]]</th>
                        <th align="left" nowrap="nowrap">[[.address.]]</th>
                        <th align="left" nowrap="nowrap">[[.entry_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.back_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.port_of_entry.]]</th>
                        <th align="left" nowrap="nowrap">[[.create_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.number_xnc.]]</th>
                        <th align="left" nowrap="nowrap">[[.number_hc.]]</th>
                        <th align="left" nowrap="nowrap">[[.visa_number1.]]</th>
                        <th align="left" nowrap="nowrap">[[.visa_issue_organ1.]]</th>
                        <th align="left" nowrap="nowrap">[[.job.]]</th>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?><th class="hiden_col">[[.edit.]]</th><?php }
                        if(User::can_delete(false,ANY_CATEGORY)){?><th class="hiden_col">[[.delete.]]</th><?php }?></tr>
                        <?php $i=1;?>
                    <!--LIST:items-->
                        <?php if([[=items.code_nationality=]] == 'VNM'):?>
                    <tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}?> id="Traveller_tr_[[|items.id|]]">
                        <td align="right" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=[[|items.id|]]';">[[|items.i|]]</td>
                        <td align="left" nowrap onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';"> [[|items.first_name|]]</td>
                        <td align="left" nowrap onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';">[[|items.last_name|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.gender|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.name_nationality_vn|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.overseas_vietnamese|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.birth_date|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.certification_name|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.passport|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.religion_name|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.room_name|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.time_in|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.time_out|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.entry_target|]]</td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap">[[|items.check_pa18|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.ethnic_name|]]</td>
                        <td align="right" nowrap="nowrap">[[|items.province_name|]]</td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <td align="right" nowrap="nowrap"></td>
                        <?php  if(User::can_edit(false,ANY_CATEGORY)){ ?>
                        <td class="hiden_col" nowrap width="15" <?php echo (![[=items.inputed_pa18_info=]])?' bgcolor="#FF0000"':'';?>>
        					<a href="<?php echo Url::build_current(array( 'reservation_id','cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" border="0"></a></td>
        				<?php }
        				if(User::can_delete(false,ANY_CATEGORY)){ ?>
                        <td class="hiden_col" nowrap width="15">
        					<a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" border="0"></a></td>
        				<?php }?>
                    </tr>
                        <?php $i++; endif;?>
                    <!--/LIST:items-->
              </table>
              <table class="w3-table-all"  id="mytable" width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
                    <h4 style="float: left;" class="w3-text-orange">2. DANH SÁCH PA18 NGƯỜI NƯỚC NGOÀI</h4>
            		<tr class="w3-light-gray w3-border" style="height: 24px;">
                        <th width="1%" title="[[.check_all.]]">[[.no.]]</th>
                        <th align="left" nowrap="nowrap">[[.first_name.]]</th>
                        <th align="left" nowrap="nowrap">[[.last_name.]]</th>
                        <th align="left" nowrap="nowrap">[[.gender.]]</th>
                        <th align="left" nowrap="nowrap">[[.nationality.]]</th>
                        <th align="left" nowrap="nowrap">[[.overseas_vietnamese.]]</th>
                        <th align="left" nowrap="nowrap">[[.birth_day.]]</th>
                        <th align="left" nowrap="nowrap">[[.certification_type.]]</th>
                        <th align="left" nowrap="nowrap">[[.certification_number.]]</th>
                        <th align="left" nowrap="nowrap">[[.religion.]]</th>
                        <th align="left" nowrap="nowrap">[[.room_number.]]</th>
                        <th align="left" nowrap="nowrap">[[.from_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.to_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.reason_for_stay.]]</th>
                        <th align="left" nowrap="nowrap">[[.note.]]</th>
                        <th align="left" nowrap="nowrap">[[.moved.]]</th>
                        <th align="left" nowrap="nowrap">[[.ethnic.]]</th>
                        <th align="left" nowrap="nowrap">[[.province.]]</th>
                        <th align="left" nowrap="nowrap">[[.address.]]</th>
                        <th align="left" nowrap="nowrap">[[.entry_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.back_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.port_of_entry.]]</th>
                        <th align="left" nowrap="nowrap">[[.create_date.]]</th>
                        <th align="left" nowrap="nowrap">[[.number_xnc.]]</th>
                        <th align="left" nowrap="nowrap">[[.number_hc.]]</th>
                        <th align="left" nowrap="nowrap">[[.visa_number1.]]</th>
                        <th align="left" nowrap="nowrap">[[.visa_issue_organ1.]]</th>
                        <th align="left" nowrap="nowrap">[[.job.]]</th>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?><th class="hiden_col">[[.edit.]]</th><?php }
                        if(User::can_delete(false,ANY_CATEGORY)){?><th class="hiden_col">[[.delete.]]</th><?php }?></tr>
                    </tr>    
                    <?php $i=1;?>
                    <!--LIST:items-->
                        <?php if([[=items.code_nationality=]] != 'VNM'):?>
                        <tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}?> id="Traveller_tr_[[|items.id|]]">
                            <td align="right" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=[[|items.id|]]';">[[|items.i|]]</td>
                            <td align="left" nowrap onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';"> [[|items.first_name|]]</td>
                            <td align="left" nowrap onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';">[[|items.last_name|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.gender|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.name_nationality_en|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.overseas_vietnamese|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.birth_date|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.certification_name|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.passport|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.religion_name|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.room_name|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.time_in|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.time_out|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.entry_target|]]</td>
                            <td align="right" nowrap="nowrap"></td>
                            <td align="right" nowrap="nowrap">[[|items.check_pa18|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.ethnic_name|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.province_name|]]</td>
                            <td align="right" nowrap="nowrap"></td>
                            <td align="right" nowrap="nowrap">[[|items.entry_date|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.back_date|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.port_of_entry|]]</td>
                            <td align="right" nowrap="nowrap"></td>
                            <td align="right" nowrap="nowrap"></td>
                            <td align="right" nowrap="nowrap"></td>
                            <td align="right" nowrap="nowrap">[[|items.visa_number|]]</td>
                            <td align="right" nowrap="nowrap">[[|items.expire_date_of_visa|]]</td>
                            <td align="right" nowrap="nowrap"></td>
                            <?php  if(User::can_edit(false,ANY_CATEGORY)){ ?>
                            <td class="hiden_col" nowrap width="15" <?php echo (![[=items.inputed_pa18_info=]])?' bgcolor="#FF0000"':'';?>>
            					<a href="<?php echo Url::build_current(array( 'reservation_id','cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" border="0"></a></td>
            				<?php }
            				if(User::can_delete(false,ANY_CATEGORY)){ ?>
                            <td class="hiden_col" nowrap width="15">
            					<a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" border="0"></a></td>
            				<?php }?>
                        </tr>
                    <?php $i++; endif;?>
                    <!--/LIST:items-->
                </table>
            </div>
            </div>
	       </form>
    	  </div>
        </td>
    </tr>
</table>
<div class="paging">[[|paging|]]</div>
<script>
	jQuery("#arrival_time").datepicker();
    function dowload_file(){
        window.open('ThongTinLuuTru.xls');
    }
    var link= jQuery('#export_excel').attr('href');
    jQuery('#export_excel').attr('href',link+'&arrival_time='+jQuery('#arrival_time').val()+'');
    jQuery('document').ready(function(){
        var cmd = '<?php echo isset($_REQUEST['export_xml_nn'])?$_REQUEST['export_xml_nn']:'';?>';
        if(cmd == 'Xuất xml khách nước ngoài')
        {
            jQuery('.export_xml').css('display', 'block');             
        }
    })
    function donwload_xml()
    {
        jQuery('.export_xml').css('display', 'none');        
    }
</script>
