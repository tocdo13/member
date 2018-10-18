<link rel="stylesheet" href="skins/default/report.css"/>
<style>
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
</style>
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
    <font class="report_title"><?php echo Portal::language('occupancy_forecast_report');?></font><br />
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
			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo date('d/m/Y');}?>';
			  
	</script>
    </td>
    <td> &nbsp;&nbsp;<?php echo Portal::language('to_day');?> &nbsp;&nbsp;</td><td><input type="text" name="to_day" id="to_day" class="date-input" onChange="changefromday();"/>
    <script>
			  $('to_day').value='<?php if(Url::get('to_day')){echo Url::get('to_day');}else{  echo date('d/m/Y',(Date_Time::to_time(date('d/m/Y')) + 7*24*3600));}?>';
			  
	</script>
    <?php $tong_foc = 0;  ?>
    </td>
    <td><?php echo Portal::language('hotel');?>: <select  name="portal_id" id="portal_id" <!--onChange="SearchForm.submit();"--><?php
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
	></select></td>
    <td><?php echo Portal::language('status');?>: <select  name="status" id="status" onchange="SearchForm.submit();" ><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
	<td>&nbsp;<input type="submit" name="do_search" value="<?php echo Portal::language('report');?>" id="btnsubmit"></td>
    <td><button id="export"><?php echo Portal::language('export');?></button></td>
     </tr></table>
     </fieldset>
     </td>
    </tr>
     <tr>
        <td style="padding-left:10px;">
         		 <div style="width:100%; padding-bottom:10px; font-size:11px;">        
            <table id="tblExport" style="width:100%; border:1px solid #666; margin-top:10px; font-size:11px;" id="report">
               <tr valign="middle" bgcolor="#EFEFEF" style="padding-left:10px; height:30px;">
               		<th style="width:50px;"><?php echo Portal::language('Date');?></th>
                    <th style="text-align:center; width:50px;"><?php echo Portal::language('total_room');?> (1)</th>
                    <th style="text-align:center; width:50px;"><?php echo Portal::language('total_occ');?> (2)</th>
                    <th style="text-align:center; width:50px;"><?php echo Portal::language('arr_room');?> (3)</th>
                    <th style="text-align:center; width:50px;"><?php echo Portal::language('li');?> (4)</th>
                    <th style="text-align:center; width:50px;"><?php echo Portal::language('ei_lo');?> (5)</th>
                    <th style="text-align:center; width:70px;"><?php echo Portal::language('total_occ');?> + <?php echo Portal::language('arr_room');?> (6)</th>
                    <th style="text-align:center; width:70px;"><?php echo Portal::language('available_room1');?>(7)</th>                     
                    <th style="text-align:center; width:50px;">%<?php echo Portal::language('oc');?> (8)</th>          
                    <th style="text-align:center; width:50px;"><?php echo Portal::language('ooo');?> (9)</th>
                    <th style="text-align:center; width:50px;"><?php echo Portal::language('foc');?> (10)</th>
                    <th style="text-align:center; width:50px;"><?php echo Portal::language('dpt');?> (11)</th>
					<?php if($this->map['check_user']!=1){ ?>
                    <th style="text-align:center; width:100px;"><?php echo Portal::language('room_rev');?> (12)</th>
                    <th style="text-align:center; width:100px;"><?php echo Portal::language('extra_rev');?> (13)</th>
                    <th style="text-align:center; width:100px;"><?php echo Portal::language('total_room_rev');?> (14)</th>
                    <th style="text-align:center; width:100px;"><?php echo Portal::language('avg_rm');?> (15)</th>
				    <?php }?>
               </tr>
               <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
               <tr style="height:30px; border:1px solid #ccc;">
                   <td style="text-align:center;<?php if(Date('D',Date_time::to_time($this->map['items']['current']['id']))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time($this->map['items']['current']['id']))=='Sat'){ echo 'color: blue';} ?>"><?php echo $this->map['items']['current']['id'];?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['avail_room'];?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['occ_room'];?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['arr_room'];?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['li_room'];?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['ei_lo_room'];?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['room_soild'];?></td>
                   <td style="text-align:center;"><?php echo ($this->map['items']['current']['avail_room']-$this->map['items']['current']['room_soild']-$this->map['items']['current']['repair_room']); ?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['oc'];?> %</td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['repair_room'];?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['foc'];?></td>
                   <td style="text-align:center;"><?php echo $this->map['items']['current']['dep_room'];?></td>
                   <?php if($this->map['check_user']!=1){ ?>
                   <td style="text-align:right; padding-right:10px;"><?php echo System::display_number(round($this->map['items']['current']['room_revenue']));?></td>
                   <td style="text-align:right; padding-right:10px;"><?php echo System::display_number(round($this->map['items']['current']['other_revenue']));?></td>
                   <td style="text-align:right; padding-right:10px;"><?php echo System::display_number(round($this->map['items']['current']['total_revenue']));?></td>
                   <td style="text-align:right; padding-right:10px;"><?php echo $this->map['items']['current']['avg_price'];?></td>
				   <?php }?>
               </tr>
               <?php }}unset($this->map['items']['current']);} ?>
               <tr height="26px;">
                <td style="text-align:centert;"><strong><?php echo Portal::language('total');?></strong></td>
                <td style="text-align:center;"><strong><?php echo $this->map['total_avail_room'];?></strong></td>
                <td style="text-align:center "><strong><?php echo $this->map['total_occ_room'];?></strong></td>
                <td style="text-align:center; "><strong><?php echo $this->map['total_arr_room'];?></strong></td>
                <td style="text-align:center; "><strong><?php echo $this->map['total_li_room'];?></strong></td>
                <td style="text-align:center; "><strong><?php echo $this->map['total_ei_lo_room'];?></strong></td>
                <!---<td style="border:1px solid #ccc; text-align:center;  width:30px;"></td>--->
                <td style="text-align:center "><strong><?php echo $this->map['total_room_soild'];?></strong></td>
                <td style="text-align:center; "><strong><?php echo $this->map['total_avail_room']-$this->map['total_room_soild']-$this->map['total_repair_room']; ?></strong></td>
                <td style="text-align:center;"><?php echo $this->map['total_oc'];?> %</td>
                <td style="text-align:center; "><strong><?php echo $this->map['total_repair_room'];?></strong></td>
                <td style="text-align:center; "><strong><?php echo $this->map['total_foc'];?></strong></td>
                <td style="text-align:center; "><strong><?php echo $this->map['total_dep_room'];?></strong></td>
				 <?php if($this->map['check_user']!=1){ ?>
                <td style="text-align:right; padding-right:10px;"><strong><?php echo System::display_number(round($this->map['total_room_revenue']));?></td>
                <td style="text-align:right; padding-right:10px;"><strong><?php echo System::display_number(round($this->map['total_other_revenue']));?></td>
                <td style="text-align:right; padding-right:10px;"><strong><?php echo System::display_number(round($this->map['total_total_revenue']));?></td>
                <td style="text-align:right; padding-right:10px;"><strong><?php echo $this->map['total_avg_price'];?></strong></td>
				<?php }?>
	        <!---<td style="border:1px solid #ccc; text-align:center;"></td>--->
    	       <!-- <td style="border:1px solid #ccc; text-align:center; width:40px;"><strong></strong></td>-->
         </tr>
            </table>
        </div>
        </td>
     </tr> 
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<p align="left" style="font-size:11px;"><b><?php echo Portal::language('interpretation');?>:</b><br />
&nbsp;- (1) <?php echo Portal::language('total_room_in_hotel');?> <br />
&nbsp;- (2) <?php echo Portal::language('total_occupancy_in_hotel');?> <br />
&nbsp;- (3) <?php echo Portal::language('arrival_room');?> (<?php echo Portal::language('inlucde_dayuse_room');?>)<br />
&nbsp;- (4) <?php echo Portal::language('room');?> LI<br />
&nbsp;- (5) <?php echo Portal::language('room');?> EI, LO<br />
&nbsp;- (6) = (2) + (3) + (4)<br />
&nbsp;- (7) = (1-6-9)<br />
&nbsp;- (8) = ((6)*100)/(1-9)<br />
&nbsp;- (9) <?php echo Portal::language('out_of_order_room');?> <br />
&nbsp;- (10) <?php echo Portal::language('FOC');?> (Phòng miễn phí) <br />
&nbsp;- (11) <?php echo Portal::language('depature_room');?> <br />
&nbsp;- (12) <?php echo Portal::language('room_revenue');?> (không tính phòng miễn phí) <br />
&nbsp;- (13) <?php echo Portal::language('extra_revenue');?> (Doanh thu của phụ trội tiền phòng - Li) <br />
&nbsp;- (14) <?php echo Portal::language('total_room_rev');?> = (12) + (13) <br />
&nbsp;- (15) <?php echo Portal::language('average_room_rate');?> =  (12)/(06)</p>
<script>
jQuery("#from_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
jQuery("#to_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
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
);
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
</script>
