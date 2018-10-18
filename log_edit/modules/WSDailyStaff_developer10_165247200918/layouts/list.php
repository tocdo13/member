<style>
.simple-layout-middle{width:100%;}
#style_list
{
      width: 95%;
      margin: 10px auto;
      border-radius: 10px;
      -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
      -o-border-radius: 10px;
      box-shadow: 0px 0px 5px #999;
      -webkit-box-shadow: 0px 0px 5px #999;
      -moz-box-shadow: 0px 0px 5px #999;
      -o-box-shadow: 0px 0px 5px #999;
}
.content
{
    margin-bottom: 20px;
}
</style>
<div class="customer-type-supplier-bound" id="style_list">
<form name="ListForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 10px;"><i class="fa fa-calendar w3-text-orange" style="font-size: 26px;"></i> [[.work_sheet_daily.]]&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="font-size: 11px;text-transform: lowercase;font-weight: normal;">
                    [[.from_date.]]<input name="from_date" type="text" id="from_date" onchange="check_date_from()" style="margin: 0px 20px 0px 5px; height: 26px;"/>
                    [[.to_date.]]<input name="to_date" type="text" id="to_date" onchange="check_date_to()" style="margin: 0px 20px 0px 5px; height: 26px;"/>
                    <input class="w3-btn w3-gray" type="button" value="[[.search.]]" onclick="ListForm.submit()" style="height: 26px; padding-top: 5px;"/>
                </span>
            </td>
            <td width="40%" nowrap="nowrap" style="padding-right: 50px; text-align: right;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" >[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('Bạn thực sự muốn xóa những nhân viên đãc chọn')){return false};ListForm.cmd.value='delete_group';ListForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="margin-bottom: 20px;" >
    		  <tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
    			  <th width="10px" align="center"><input name="all_item_check_box" type="checkbox" id="all_item_check_box" onclick="select_all();"/></th>
                  <th align="center" width="50px">STT</th>
    			  <th align="center" width="100px">Ngày</th>
                  <th align="center" width="200px">Nhân viên</th>
    			  <th align="center">Phòng</th>
                  <th align="center" width="50px">[[.view.]]</th>
                  <th align="center" width="50px">[[.edit.]]</th>
    			  <th align="center"width="50px">[[.delete.]]</th>
    		  </tr>
              <?php
                $date_ws = false;
              ?>
    		  <!--LIST:items-->
                <!--LIST:items.staffs-->
                    <?php
                        $str_room = empty([[=items.staffs.str_room=]])==false?[[=items.staffs.str_room=]]:'  ';
                        $str_room = substr($str_room,0,strlen($str_room)-2);
                        if($date_ws!=[[=items.date_ws=]]) 
                        {
                            ?>
                            <tr>
                                <td align="center"><input name="chb_[[|items.staffs.ws_daily_staff_id|]]" type="checkbox" id="chb_[[|items.staffs.ws_daily_staff_id|]]" onclick="select_object(this,[[|items.staffs.ws_daily_staff_id|]]);" class="col"/></td>
                                <td align="center" rowspan="[[|items.row_span|]]">[[|items.index|]]</td>
                                <td align="center" rowspan="[[|items.row_span|]]"><?php echo Date_Time::convert_orc_date_to_date([[=items.date_ws=]],"/");?>
                                <?php if(User::can_view(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'view_all','day_view'=>[[=items.date_ws=]]));?>" style="display: block;text-decoration: none;background: #1fa5b0;padding: 3px;text-align: center; border-radius: 5px;color: white;font-weight: bold;">[[.view_work_sheet.]]</a><?php }?>
                                </td>
                                <td align="center">[[|items.staffs.staff_name|]]</td>
                                <td><?php echo $str_room; ?></td>
                                <td align="center">
                                <?php if(User::can_view(false,ANY_CATEGORY)){?>
                                <a  href="<?php echo Url::build_current(array('cmd'=>'report','id'=>[[=items.staffs.ws_daily_staff_id=]]));?>" target="_blank"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a>
                                <?php }?>
                                </td>
                                <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','ws_daily_staff_id'=>[[=items.staffs.ws_daily_staff_id=]],'date_ws'=>[[=items.date_ws=]]));?>"><i class="far fa-edit" style="color: green;"></i></a><?php }?></td>
                                <td align="center">
                                
                                <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                                <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.staffs.ws_daily_staff_id=]]));?>" onclick="return con_firm('[[|items.date_ws|]]','[[|items.staffs.staff_name|]]');"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
                                <?php }?>
                                </td>
                            </tr>
                            <?php 
                            $date_ws=[[=items.date_ws=]];
                        }
                        else
                        {
                            ?>
                            <tr>
                                <td align="center"><input name="chb_[[|items.staffs.ws_daily_staff_id|]]" type="checkbox" id="chb_[[|items.staffs.ws_daily_staff_id|]]" onclick="select_object(this,[[|items.staffs.ws_daily_staff_id|]]);" class="col"/></td>
                                <td align="center">[[|items.staffs.staff_name|]]</td>
                                <td><?php echo $str_room; ?></td>
                                <td align="center">
                                <?php if(User::can_view(false,ANY_CATEGORY)){?>
                                <a href="<?php echo Url::build_current(array('cmd'=>'report','id'=>[[=items.staffs.ws_daily_staff_id=]]));?>" target="_blank"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a>
                                <?php }?>
                                </td>
                                <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','ws_daily_staff_id'=>[[=items.staffs.ws_daily_staff_id=]],'date_ws'=>[[=items.date_ws=]]));?>"><i class="far fa-edit" style="color: green;"></i></a><?php }?></td>
                                <td align="center">
                                <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                                <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.staffs.ws_daily_staff_id=]]));?>" onclick="return con_firm('[[|items.date_ws|]]','[[|items.staffs.staff_name|]]');"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
                                <?php }?>
                                </td>
                            </tr>
                            <?php 
                        }
                    ?>
                    
                <!--/LIST:items.staffs-->
                <tr>
                </tr>
    		  <!--/LIST:items-->			
    		</table>
            </td>
        </tr>
    </table>
    
    <input name="selected_chb" type="hidden" id="selected_chb" style="width: 300px;"/>
	
	<input name="cmd" type="hidden" value="" />
</form>	
</div>

<script type="text/javascript">
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function select_all()
    {
        var check = document.getElementById('all_item_check_box').checked;
        if(check)
        {
            jQuery('form .col').attr('checked',true);
            
            var str="";
            <?php
                foreach([[=items=]] as $row) 
                {
                    foreach($row['staffs'] as $staff)
                    {
                        echo ' str +="'.$staff["ws_daily_staff_id"].',";';
                    } 
                }
            ?>
            document.getElementById('selected_chb').value = str;
        }
        else
        {
            jQuery('form .col').attr('checked',false);
            document.getElementById('selected_chb').value = "";
        }
    }
    
    function select_object(object,id)
    {
        if(object.checked)
        {
            var str =id + ",";
            document.getElementById('selected_chb').value +=str;
        }
        else
        {
            var str =id + ",";
            var arr_select = document.getElementById('selected_chb').value;
            
            arr_select = arr_select.replace(str, "");
            document.getElementById('selected_chb').value = arr_select;
        }
    }
    
    function con_firm(date_ws,staff){
	return confirm('Bạn thực sự muốn xóa nhân viên '+staff+' trong ngày '+date_ws +'?');  
}
 function check_date_to(){
    var f_date=jQuery('#from_date').val();
    var t_date=jQuery('#to_date').val();
    var f_day_date=f_date.substring(0,2);
    var f_month_date=f_date.substring(3,2);
    var f_year_date=f_date.substring(6,4);
    var t_day_date=t_date.substring(0,2);
    var t_month_date=t_date.substring(3,2);
    var t_year_date=t_date.substring(6,4);
    if(f_year_date>t_year_date || (f_year_date==t_year_date &&(f_month_date>t_month_date))|| (f_year_date==t_year_date &&(f_month_date==t_month_date))&&(f_day_date>t_day_date)){
        jQuery('#from_date').val(t_date);
    } 
 }
  function check_date_from(){
    var f_date=jQuery('#from_date').val();
    var t_date=jQuery('#to_date').val();
    var f_day_date=f_date.substring(0,2);
    var f_month_date=f_date.substring(3,2);
    var f_year_date=f_date.substring(6,4);
    var t_day_date=t_date.substring(0,2);
    var t_month_date=t_date.substring(3,2);
    var t_year_date=t_date.substring(6,4);
    if(t_year_date<f_year_date || (t_year_date==f_year_date &&(t_month_date<f_month_date))|| (t_year_date==f_year_date &&(f_month_date==t_month_date))&&(t_day_date<f_day_date)){
        jQuery('#to_date').val(f_date);
    } 
 } 
</script>