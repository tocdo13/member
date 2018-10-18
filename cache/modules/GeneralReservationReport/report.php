<link rel="stylesheet" href="skins/default/report.css"/>
<style>
.multiselect_customer {
  width: 120px;
}
.selectBox_customer {
  position: relative;
}

.selectBox_customer select {
  width: 100%;
  font-weight: bold;
}
.overSelect_customer {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}
#checkboxes_customer {
  display: none;
  border: 1px #1e90ff solid;
  overflow: auto;    
  padding: 2px 15px 2px 5px;
  position: absolute;
  background: white;  
}
#checkboxes_customer{
    height: 300px;
}
#checkboxes_customer label {
  display: block;
}
#checkboxes_customer label:hover {
  background-color: #1e90ff;
}
.date_moth{
	display:none;
}
.report{
	border:1px solid #ccc;
	height:27px;
}
@media print{
.date_moth{
	display:block;
	padding-top:10px;
	font-size:14px;
}
.no_print{
	display:none;
}
#tblExport th{
    padding: 0px !important;
}
</style>
<div style="width: 100%;overflow-x: scroll;">
<form name="SearchForm" method="post">
<table style="width:100%;">
<tr valign="top">
<td align="left" width="100%">
	<table border="0" cellSpacing=0 cellpadding="5" width="100%">
			<tr valign="middle">
			  <td align="left">
			  	<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?>
			  </td>
			  <td align="right">
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
			  </td>
			</tr>	
		</table>
</td>
</tr>
<tr>
    <td style="text-align:center; padding-top:26px;">
    <font class="report_title"><?php echo Portal::language('general_reservation_report');?></font><br />
    <label id="date_moth"><?php echo Portal::language('from_date');?> : <?php echo $this->map['from_date'];?> - <?php echo Portal::language('to_date');?> : <?php echo $this->map['to_date'];?></label> 
    </td>
</tr>
<tr class="no_print">
  <td colspan="3" style="padding-left:50px; padding-right:50px;">
  
<fieldset>
  <legend><?php echo Portal::language('time_select');?></legend>
    <table style="margin-left:60px;">
    <tr> <td nowrap="nowrap"><?php echo Portal::language('by_day');?> &nbsp;&nbsp;</td>
    <td><input type="text" name="from_day" id="from_day" class="date-input" onChange="changevalue();"/>
    <script>
			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo date('01/m/Y');}?>';
			  
	</script>
    </td>
    <td> &nbsp;&nbsp;<?php echo Portal::language('to_day');?> &nbsp;&nbsp;</td><td><input type="text" name="to_day" id="to_day" class="date-input" onChange="changefromday();"/>
    <script>
			  $('to_day').value='<?php if(Url::get('to_day')){echo Url::get('to_day');}else{  echo date('d/m/Y',(Date_Time::to_time(date('t/m/Y',time()))));}?>';
			  
	</script>
    <?php $tong_foc = 0;  ?>
    </td>
    <td>&nbsp;&nbsp;<?php echo Portal::language('recode');?>&nbsp;&nbsp;<input  name="re_code" id="re_code" style="width: 70px;"  class="input_number"/ type ="text" value="<?php echo String::html_normalize(URL::get('re_code'));?>"></td>
    <td><?php echo Portal::language('customer_name');?>:</td>
    <td>
        <div class="multiselect_customer">
            <div style="width: 80px;" class="selectBox_customer" onclick="showCheckboxes('customer');">
                  <select  style="height: 20px;">
                    <option></option>
                  </select>
                  <div class="overSelect_customer"></div>
            </div> 
            <?php echo $this->map['list_customer'];?>
            <input  name="customer_ids" id="customer_ids" / type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_ids'));?>">
            <input  name="customer_id_" id="customer_id_" / type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_id_'));?>">
        </div>     
    </td>   
    <td>&nbsp;&nbsp;<?php echo Portal::language('creater');?>&nbsp;&nbsp;<select  name="create_user" id="create_user" style="width: 170px;height: 20px;"><?php
					if(isset($this->map['create_user_list']))
					{
						foreach($this->map['create_user_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('create_user',isset($this->map['create_user'])?$this->map['create_user']:''))
                    echo "<script>$('create_user').value = \"".addslashes(URL::get('create_user',isset($this->map['create_user'])?$this->map['create_user']:''))."\";</script>";
                    ?>
	</select></td>
    <td>&nbsp;&nbsp;<?php echo Portal::language('cut_of_date');?>&nbsp;&nbsp;<input type="text" name="cut_of_date" id="cut_of_date" class="date-input" style="width: 80px;"/></td>
    <td>&nbsp;&nbsp;<?php echo Portal::language('hotel');?>&nbsp;&nbsp; <select  name="portal_id" id="portal_id" style="height: 20px;"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
	<td>&nbsp;<input type="submit" name="do_search" value="<?php echo Portal::language('report');?>" id="btnsubmit"/></td>
    <!--<td><button id="export"><?php echo Portal::language('export');?></button></td>-->
     </tr></table>
     </fieldset>
     </td>
    </tr>
     <tr>
        <td style="padding-left:10px;">
            <div style="width:100%; padding-bottom:10px; font-size:11px;">        
                <table border="1px" id="tblExport" style="width:100%; margin-top:10px; font-size:11px;">
                   <?php $rl = 1;?>
                   <?php if(isset($this->map['room_level']) and is_array($this->map['room_level'])){ foreach($this->map['room_level'] as $key1=>&$item1){if($key1!='current'){$this->map['room_level']['current'] = &$item1;?>
                   <?php if($rl==1){ ?>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;">
                        <td colspan="2" rowspan="<?php echo sizeof($this->map['room_level'])-1;?>" style="width:50px;text-align: left;padding-left: 3px;" bgcolor="#F0FFF0"><b><?php echo Portal::language('room_level');?></b></td>
                        <td colspan="2" style="text-align: left;padding-left: 3px;" bgcolor="#F0FFF0"><b><?php echo $this->map['room_level']['current']['name'];?></b></td>
                        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
                            <?php if($this->map['room_level']['current']['id']==$this->map['items']['current']['room_level_id']){?>
                                <td bgcolor="#F0FFF0" style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['items']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['items']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><?php echo $this->map['items']['current']['room_soild'];?></td>
                            <?php }?>
                        <?php }}unset($this->map['items']['current']);} ?>
                        <td style="text-align: right;padding-right: 5px;"bgcolor="#F0FFF0"><b><?php echo $this->map['room_level']['current']['room_soild'];?></b></td>
                        <td colspan="4" style="padding: 0px;" bgcolor="#EFEFEF" rowspan="<?php echo sizeof($this->map['room_level'])-1+3;?>"></td>
                   </tr>
                   <?php $rl++;}else{?>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#F0FFF0">
                        <td colspan="2" style="text-align: left;padding-left: 3px;"><b><?php echo $this->map['room_level']['current']['name'];?></b></td>
                        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current'] = &$item3;?>
                            <?php if($this->map['room_level']['current']['id']==$this->map['items']['current']['room_level_id']){?>
                                <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['items']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['items']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><?php echo $this->map['items']['current']['room_soild'];?></td>
                            <?php }?>
                        <?php }}unset($this->map['items']['current']);} ?>
                        <td style="text-align: right;padding-right: 5px;"><b><?php echo $this->map['room_level']['current']['room_soild'];?></b></td>
                   </tr>
                   <?php }?>
                   <?php }}unset($this->map['room_level']['current']);} ?>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#C6E2FF">
                        <td colspan="4" style="text-align: left;padding-left: 3px;"><b><?php echo Portal::language('total_room_occupied');?></td>
                        <?php if(isset($this->map['total_day']) and is_array($this->map['total_day'])){ foreach($this->map['total_day'] as $key4=>&$item4){if($key4!='current'){$this->map['total_day']['current'] = &$item4;?>
                        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><b><?php echo $this->map['total_day']['current']['room_soild'];?></b></td>
                        <?php }}unset($this->map['total_day']['current']);} ?>
                        <td style="text-align: right;padding-right: 5px;"><b><?php echo $this->map['total_room_soild'];?></b></td>
                   </tr>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#FAF0E6">
                        <td colspan="4" style="text-align: left;padding-left: 3px;"><b>%<?php echo Portal::language('oc');?></b></td>
                        <?php if(isset($this->map['total_day']) and is_array($this->map['total_day'])){ foreach($this->map['total_day'] as $key5=>&$item5){if($key5!='current'){$this->map['total_day']['current'] = &$item5;?>
                        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><b><?php echo $this->map['total_day']['current']['oc'];?></b></td>
                        <?php }}unset($this->map['total_day']['current']);} ?>
                        <td style="text-align: right;padding-right: 5px;border-left: none;"><b><?php echo $this->map['total_oc'];?></b></td>
                   </tr>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#FAF0E6">
                        <td colspan="4" style="text-align: left;padding-left: 3px;"><b><?php echo Portal::language('available_room1');?></b></td>
                        <?php if(isset($this->map['total_day']) and is_array($this->map['total_day'])){ foreach($this->map['total_day'] as $key6=>&$item6){if($key6!='current'){$this->map['total_day']['current'] = &$item6;?>
                        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><b><?php echo $this->map['total_day']['current']['avail_room']-$this->map['total_day']['current']['room_soild']-$this->map['total_day']['current']['repair_room'];?></b></td>
                        <?php }}unset($this->map['total_day']['current']);} ?>
                        <td style="text-align: right;padding-right: 5px;"><b><?php echo $this->map['total_avail_room']-$this->map['total_room_soild']-$this->map['total_repair_room'];?></b></td>
                   </tr>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" class="w3-light-gray">
                        <td style="text-align: center;padding: 0px; width: 50px;"><b><?php echo Portal::language('stt');?></b></td>
                        <td style="text-align: center;padding: 0px; width: 50px;"><b><?php echo Portal::language('recode');?></b></td>
                        <td style="text-align: center;padding: 0px; width: 150px;"><b><?php echo Portal::language('customer');?></b></td>
                        <td style="text-align: center;padding: 0px; width: 150px;"><b><?php echo Portal::language('booker');?></b></td>
                        <?php for($i=$this->map['time_from_day'] ; $i<=$this->map['time_to_day']; $i +=24*3600){?>
                        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',$i)=='Sun'){echo 'color: red';}elseif(Date('D',$i)=='Sat'){ echo 'color: blue';} ?>"><b><?php echo date('d',$i);?></b></th>
                        <?php }?>
                        <td style="text-align: right;padding-right: 5px;"><b><?php echo Portal::language('total');?></b></td>
                        <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('number_phone');?></b></td>
                        <!--<td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('email');?></b></td> -->
                        <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('deposit');?></b></td>
                        <td style="text-align: center;"><b><?php echo Portal::language('cut_of_date');?></b></td>
                        <td style="text-align: left;"><b><?php echo Portal::language('group_note');?></b></td>
                   </tr>
                   <?php $j=1;?>
                   <?php if(isset($this->map['recode_info']) and is_array($this->map['recode_info'])){ foreach($this->map['recode_info'] as $key7=>&$item7){if($key7!='current'){$this->map['recode_info']['current'] = &$item7;?>
                   <tr style="height:30px; border:1px solid #ccc;">
                        <td style="text-align:center;"><?php echo $j++;?></td>
                        <td style="text-align:center;"><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['recode_info']['current']['id']));?>" target="_blank"><?php echo $this->map['recode_info']['current']['id'];?></a></td>
                        <td style="text-align:left;padding-left: 3px;"><?php echo $this->map['recode_info']['current']['customer_name'];?></td>
                        <td style="text-align:left;padding-left: 3px;"><?php echo $this->map['recode_info']['current']['booker'];?></td>
                        <?php for($i=$this->map['time_from_day'] ; $i<=$this->map['time_to_day']; $i +=24*3600){?>
                                <?php if(isset($this->map['recode_info']['current']['num_room'][date('d/m/Y',$i)]) and $this->map['recode_info']['current']['num_room'][date('d/m/Y',$i)]['room_count']!=0){?>
                                    <td style="text-align:right;padding-right: 5px;<?php if(Date('D',$i)=='Sun'){echo 'color: red';}elseif(Date('D',$i)=='Sat'){ echo 'color: blue';} ?>" title="<?php echo $this->map['recode_info']['current']['room_level_info'];?>"><?php echo $this->map['recode_info']['current']['num_room'][date('d/m/Y',$i)]['room_count'];?></td>
                                <?php }else{?>
                                    <td></td>
                                <?php }?>
                            
                        <?php }?>
                        <td style="text-align:right;padding-right: 5px;"><b><?php echo System::display_number(round($this->map['recode_info']['current']['revenue']));?></b></td>
                        <td style="text-align:center;width: 80px;"><?php echo $this->map['recode_info']['current']['phone_booker'];?></td>
                        <!--<td style="text-align:left;padding-left: 3px;"><?php echo $this->map['recode_info']['current']['email_booker'];?></td> -->
                        <td style="text-align:right;padding-right: 5px"><?php echo System::display_number($this->map['recode_info']['current']['deposit']);?></td>
                        <td style="text-align:center;width: 70px;"><?php echo $this->map['recode_info']['current']['cut_of_date'];?></td>
                        <td style="text-align:left;"><?php echo $this->map['recode_info']['current']['group_note'];?></td>
                   </tr>
                   <?php }}unset($this->map['recode_info']['current']);} ?>
                </table>
            </div> 		 
        </td>
     </tr> 
</table>
<table border="1px" style="width:100%; font-size:11px;background: white; position: absolute; top:0px; z-index:99999; left:15px;display:none;"  id="table_head">
    <?php $rl2 = 1;?>
   <?php if(isset($this->map['room_level']) and is_array($this->map['room_level'])){ foreach($this->map['room_level'] as $key8=>&$item8){if($key8!='current'){$this->map['room_level']['current'] = &$item8;?>
   <?php if($rl2==1){ ?>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;">
        <td colspan="2" rowspan="<?php echo sizeof($this->map['room_level'])-1;?>" style="width:100px;text-align: left;padding-left: 3px;" bgcolor="#F0FFF0"><b><?php echo Portal::language('room_level');?></b></td>
        <td colspan="2" style="text-align: left;padding-left: 3px;" bgcolor="#F0FFF0"><b><?php echo $this->map['room_level']['current']['name'];?></b></td>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key9=>&$item9){if($key9!='current'){$this->map['items']['current'] = &$item9;?>
            <?php if($this->map['room_level']['current']['id']==$this->map['items']['current']['room_level_id']){?>
                <td bgcolor="#F0FFF0" style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['items']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['items']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><?php echo $this->map['items']['current']['room_soild'];?></td>
            <?php }?>
        <?php }}unset($this->map['items']['current']);} ?>
        <td style="text-align: right;padding-right: 5px;"bgcolor="#F0FFF0"><b><?php echo $this->map['room_level']['current']['room_soild'];?></b></td>
        <td colspan="4" style="padding: 0px;" bgcolor="#EFEFEF" rowspan="<?php echo sizeof($this->map['room_level'])-1+3;?>"></td>
   </tr>
   <?php $rl2++;}else{?>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#F0FFF0">
        <td colspan="2" style="text-align: left;padding-left: 3px;"><b><?php echo $this->map['room_level']['current']['name'];?></b></td>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key10=>&$item10){if($key10!='current'){$this->map['items']['current'] = &$item10;?>
            <?php if($this->map['room_level']['current']['id']==$this->map['items']['current']['room_level_id']){?>
                <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['items']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['items']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><?php echo $this->map['items']['current']['room_soild'];?></td>
            <?php }?>
        <?php }}unset($this->map['items']['current']);} ?>
        <td style="text-align: right;padding-right: 5px;"><b><?php echo $this->map['room_level']['current']['room_soild'];?></b></td>
   </tr>
   <?php }?>
   <?php }}unset($this->map['room_level']['current']);} ?>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#C6E2FF">
        <td colspan="4" style="text-align: left;padding-left: 3px;"><b><?php echo Portal::language('total_room_occupied');?></td>
        <?php if(isset($this->map['total_day']) and is_array($this->map['total_day'])){ foreach($this->map['total_day'] as $key11=>&$item11){if($key11!='current'){$this->map['total_day']['current'] = &$item11;?>
        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><b><?php echo $this->map['total_day']['current']['room_soild'];?></b></td>
        <?php }}unset($this->map['total_day']['current']);} ?>
        <td style="text-align: right;padding-right: 5px;"><b><?php echo $this->map['total_room_soild'];?></b></td>
   </tr>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;"bgcolor="#FAF0E6">
        <td colspan="4" style="text-align: left;padding-left: 3px;"><b>%<?php echo Portal::language('oc');?></b></td>
        <?php if(isset($this->map['total_day']) and is_array($this->map['total_day'])){ foreach($this->map['total_day'] as $key12=>&$item12){if($key12!='current'){$this->map['total_day']['current'] = &$item12;?>
        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><b><?php echo $this->map['total_day']['current']['oc'];?></b></td>
        <?php }}unset($this->map['total_day']['current']);} ?>
        <td style="text-align: right;padding-right: 5px;border-left: none;"><b><?php echo $this->map['total_oc'];?></b></td>
   </tr>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;"bgcolor="#FAF0E6">
        <td colspan="4" style="text-align: left;padding-left: 3px;"><b><?php echo Portal::language('available_room1');?></b></td>
        <?php if(isset($this->map['total_day']) and is_array($this->map['total_day'])){ foreach($this->map['total_day'] as $key13=>&$item13){if($key13!='current'){$this->map['total_day']['current'] = &$item13;?>
        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['total_day']['current']['in_date']))=='Sat'){ echo 'color: blue';} ?>"><b><?php echo $this->map['total_day']['current']['avail_room']-$this->map['total_day']['current']['room_soild']-$this->map['total_day']['current']['repair_room'];?></b></td>
        <?php }}unset($this->map['total_day']['current']);} ?>
        <td style="text-align: right;padding-right: 5px;"><b><?php echo $this->map['total_avail_room']-$this->map['total_room_soild']-$this->map['total_repair_room'];?></b></td>
   </tr>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;" class="w3-light-gray">
        <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('stt');?></b></td>
        <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('recode');?></b></td>
        <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('customer');?></b></td>
        <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('booker');?></b></td>
        <?php for($i=$this->map['time_from_day'] ; $i<=$this->map['time_to_day']; $i +=24*3600){?>
        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',$i)=='Sun'){echo 'color: red';}elseif(Date('D',$i)=='Sat'){ echo 'color: blue';} ?>"><b><?php echo date('d',$i);?></b></th>
        <?php }?>
        <td style="text-align: right;padding-right: 5px;"><b><?php echo Portal::language('total');?></b></td>
        <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('phone');?></b></td>
        <!-- <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('email');?></b></td> -->
        <td style="text-align: center;padding: 0px;"><b><?php echo Portal::language('deposit');?></b></td>
        <td style="text-align: center;"><b><?php echo Portal::language('cut_of_date');?></b></td>
        <td style="text-align: left;"><b><?php echo Portal::language('group_note');?></b></td>
   </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
jQuery("#from_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
jQuery("#to_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
jQuery("#cut_of_date").datepicker();
var customer = <?php echo $this->map['customer_js'];?>; 
/**
jQuery(document).ready(
    function()
    {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
);**/
jQuery(document).ready(
        function()
        {   
            jQuery("#table_head").width(jQuery("#tblExport").width());
            jQuery("table#table_head").width(jQuery("table#tblExport").outerWidth()+"px");
            jQuery("table#table_head tr td").each(function(){
                var index = jQuery("table#table_head tr td").index(jQuery(this));
                var element  = jQuery("table#tblExport tr td").get(index);
                var width = to_numeric(jQuery(element).outerWidth());
                jQuery(this).width(width+"px");
            });
            jQuery(window).scroll(function() {
                var documentScrollTop = jQuery(window).scrollTop();
                if(documentScrollTop>300){
                   jQuery("table#table_head").css({"top":documentScrollTop+"px","display":""}); // Bi thay doi boi "display":"in-line"
                }
                else{
                   jQuery("table#table_head").css("display","none");  
                }
            });
            for(var i in customer){                
                jQuery('.customer').each(function(){                    
                    if(jQuery('#'+this.id).attr('flag') == customer[i])
                    {
                        jQuery('#'+this.id).attr('checked', true);                                                
                    }
                })
            }
        }); 
 
function changevalue()
    {
        var myfromdate = $('from_day').value.split("/");
        var mytodate = $('to_day').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_day").val(jQuery("#from_day").val());
        }
    }
function changefromday()
    {
        var myfromdate = $('from_day').value.split("/");
        var mytodate = $('to_day').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_day").val(jQuery("#to_day").val());
        }
    }
var expanded_customer = false;    
function showCheckboxes(value) {
  if(value =='customer'){
    var checkboxes_customer = document.getElementById("checkboxes_customer");
      if (!expanded_customer) {
        checkboxes_customer.style.display = "block";
        expanded_customer = true;
      } else {
        checkboxes_customer.style.display = "none";        
        expanded_customer = false;
      }
  }            
}
jQuery(document).on('click', function(e) {
      var $clicked = jQuery(e.target);
     if (!$clicked.parents().hasClass("multiselect_customer")) jQuery('#checkboxes_customer').hide();
    });
function get_ids(value)
{           
    var strids = "";
    var str_customer = "";
    var customer_id = "";  
    if(value=='customer'){
        var inputs = jQuery('.customer:checkbox:checked');            
        for (var i=0;i<inputs.length;i++)
        {  
            if(inputs[i].id.indexOf('customer_')==0)
            {
                str_customer +=","+"'"+inputs[i].id.replace("customer_","")+"'";
                customer_id +=","+inputs[i].id.replace("customer_","");                
            }
        }                
        str_customer = str_customer.replace(",","");
        customer_id = customer_id.replace(",","");             
        jQuery('#customer_ids').val(str_customer);
        jQuery('#customer_id_').val(customer_id);             
    }                
}
</script>
