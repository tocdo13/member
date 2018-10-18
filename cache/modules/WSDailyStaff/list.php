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
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 10px;"><i class="fa fa-calendar w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('work_sheet_daily');?>&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="font-size: 11px;text-transform: lowercase;font-weight: normal;">
                    <?php echo Portal::language('from_date');?><input  name="from_date" id="from_date" onchange="check_date_from()" style="margin: 0px 20px 0px 5px; height: 26px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                    <?php echo Portal::language('to_date');?><input  name="to_date" id="to_date" onchange="check_date_to()" style="margin: 0px 20px 0px 5px; height: 26px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                    <input class="w3-btn w3-gray" type="button" value="<?php echo Portal::language('search');?>" onclick="ListForm.submit()" style="height: 26px; padding-top: 5px;"/>
                </span>
            </td>
            <td width="40%" nowrap="nowrap" style="padding-right: 50px; text-align: right;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" ><?php echo Portal::language('Add');?></a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('Bạn thực sự muốn xóa những nhân viên đãc chọn')){return false};ListForm.cmd.value='delete_group';ListForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a><?php }?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="margin-bottom: 20px;" >
    		  <tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
    			  <th width="10px" align="center"><input  name="all_item_check_box" id="all_item_check_box" onclick="select_all();"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('all_item_check_box'));?>"></th>
                  <th align="center" width="50px">STT</th>
    			  <th align="center" width="100px">Ngày</th>
                  <th align="center" width="200px">Nhân viên</th>
    			  <th align="center">Phòng</th>
                  <th align="center" width="50px"><?php echo Portal::language('view');?></th>
                  <th align="center" width="50px"><?php echo Portal::language('edit');?></th>
    			  <th align="center"width="50px"><?php echo Portal::language('delete');?></th>
    		  </tr>
              <?php
                $date_ws = false;
              ?>
    		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                <?php if(isset($this->map['items']['current']['staffs']) and is_array($this->map['items']['current']['staffs'])){ foreach($this->map['items']['current']['staffs'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['staffs']['current'] = &$item2;?>
                    <?php
                        $str_room = empty($this->map['items']['current']['staffs']['current']['str_room'])==false?$this->map['items']['current']['staffs']['current']['str_room']:'  ';
                        $str_room = substr($str_room,0,strlen($str_room)-2);
                        if($date_ws!=$this->map['items']['current']['date_ws']) 
                        {
                            ?>
                            <tr>
                                <td align="center"><input  name="chb_<?php echo $this->map['items']['current']['staffs']['current']['ws_daily_staff_id'];?>" id="chb_<?php echo $this->map['items']['current']['staffs']['current']['ws_daily_staff_id'];?>" onclick="select_object(this,<?php echo $this->map['items']['current']['staffs']['current']['ws_daily_staff_id'];?>);" class="col"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('chb_'.$this->map['items']['current']['staffs']['current']['ws_daily_staff_id']));?>"></td>
                                <td align="center" rowspan="<?php echo $this->map['items']['current']['row_span'];?>"><?php echo $this->map['items']['current']['index'];?></td>
                                <td align="center" rowspan="<?php echo $this->map['items']['current']['row_span'];?>"><?php echo Date_Time::convert_orc_date_to_date($this->map['items']['current']['date_ws'],"/");?>
                                <?php if(User::can_view(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'view_all','day_view'=>$this->map['items']['current']['date_ws']));?>" style="display: block;text-decoration: none;background: #1fa5b0;padding: 3px;text-align: center; border-radius: 5px;color: white;font-weight: bold;"><?php echo Portal::language('view_work_sheet');?></a><?php }?>
                                </td>
                                <td align="center"><?php echo $this->map['items']['current']['staffs']['current']['staff_name'];?></td>
                                <td><?php echo $str_room; ?></td>
                                <td align="center">
                                <?php if(User::can_view(false,ANY_CATEGORY)){?>
                                <a  href="<?php echo Url::build_current(array('cmd'=>'report','id'=>$this->map['items']['current']['staffs']['current']['ws_daily_staff_id']));?>" target="_blank"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a>
                                <?php }?>
                                </td>
                                <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','ws_daily_staff_id'=>$this->map['items']['current']['staffs']['current']['ws_daily_staff_id'],'date_ws'=>$this->map['items']['current']['date_ws']));?>"><i class="far fa-edit" style="color: green;"></i></a><?php }?></td>
                                <td align="center">
                                
                                <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                                <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['staffs']['current']['ws_daily_staff_id']));?>" onclick="return con_firm('<?php echo $this->map['items']['current']['date_ws'];?>','<?php echo $this->map['items']['current']['staffs']['current']['staff_name'];?>');"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
                                <?php }?>
                                </td>
                            </tr>
                            <?php 
                            $date_ws=$this->map['items']['current']['date_ws'];
                        }
                        else
                        {
                            ?>
                            <tr>
                                <td align="center"><input  name="chb_<?php echo $this->map['items']['current']['staffs']['current']['ws_daily_staff_id'];?>" id="chb_<?php echo $this->map['items']['current']['staffs']['current']['ws_daily_staff_id'];?>" onclick="select_object(this,<?php echo $this->map['items']['current']['staffs']['current']['ws_daily_staff_id'];?>);" class="col"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('chb_'.$this->map['items']['current']['staffs']['current']['ws_daily_staff_id']));?>"></td>
                                <td align="center"><?php echo $this->map['items']['current']['staffs']['current']['staff_name'];?></td>
                                <td><?php echo $str_room; ?></td>
                                <td align="center">
                                <?php if(User::can_view(false,ANY_CATEGORY)){?>
                                <a href="<?php echo Url::build_current(array('cmd'=>'report','id'=>$this->map['items']['current']['staffs']['current']['ws_daily_staff_id']));?>" target="_blank"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a>
                                <?php }?>
                                </td>
                                <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','ws_daily_staff_id'=>$this->map['items']['current']['staffs']['current']['ws_daily_staff_id'],'date_ws'=>$this->map['items']['current']['date_ws']));?>"><i class="far fa-edit" style="color: green;"></i></a><?php }?></td>
                                <td align="center">
                                <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                                <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['staffs']['current']['ws_daily_staff_id']));?>" onclick="return con_firm('<?php echo $this->map['items']['current']['date_ws'];?>','<?php echo $this->map['items']['current']['staffs']['current']['staff_name'];?>');"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
                                <?php }?>
                                </td>
                            </tr>
                            <?php 
                        }
                    ?>
                    
                <?php }}unset($this->map['items']['current']['staffs']['current']);} ?>
                <tr>
                </tr>
    		  <?php }}unset($this->map['items']['current']);} ?>			
    		</table>
            </td>
        </tr>
    </table>
    
    <input  name="selected_chb" id="selected_chb" style="width: 300px;"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('selected_chb'));?>">
	
	<input  name="cmd" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
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
                foreach($this->map['items'] as $row) 
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