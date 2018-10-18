<style>
#timehidden{
		display:none;	
	}
	@media print{
		#hidden{
			display:none;
		}
		#timehidden{
			display:block;	
		}
	}
	
	#revenue{
		border:2px solid #DFDFDF;
		margin:auto; 
		margin-top:50px;
	}
	#revenue tr td{
		border:1px solid silver;
	}
	#revenue tr{
		line-height:20px; 
		border:1px solid silver;	
	}
</style>
<table width="80%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%"><strong><?php echo HOTEL_NAME;?></strong><br />Địa chỉ: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="35%"><strong><?php echo Portal::language('template_code');?></strong>
                <br />
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                </td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title" style="font-size:20px; text-align:center;"><b><?php echo Portal::language('restaurant_discount_report');?></b></font>

		<br><br />
        <label id="timehidden">Từ ngày: <?php echo $this->map['from_date'];?>  Đến ngày: <?php echo $this->map['to_date'];?></label>
		<form name="WeeklyRevenueThuyForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b><?php echo Portal::language('time_select');?></b></legend>
		<table border="0" style="margin:auto;">
        	<tr>
            	
            	<td><?php echo Portal::language('from_date');?>:</td>
            	<td><input type="text" name="from_date" id="from_date" class="date-input" onchange="changevalue();" style="width: 140px;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td><?php echo Portal::language('to_date');?>:</td>
                <td>
                <input type="text" name="to_date" id="to_date" class="date-input" onchange="changefromday();" style="width: 140px;"/></td>
                <!--Start Luu Nguyen Giap add portal -->
                <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                <td ><?php echo Portal::language('hotel');?>:</td>
                <td>
                <select  name="portal_id" id="portal_id" style="width: 140px;" onchange="choice_restaurant();"><?php
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
                <?php //}?>
                <!--End Luu Nguyen Giap add portal -->
                <td><input type="submit" name="do_search" value="<?php echo Portal::language('report');?>" onclick="return check_choose_restaurant();"/></td>
                
			</tr>
            
            <tr>
            	<td><?php echo Portal::language('customer');?>:</td>
                <td style="margin: 0;padding:0;"><select  name="customer" id="customer" style="width: 140px;"><?php
					if(isset($this->map['customer_list']))
					{
						foreach($this->map['customer_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('customer',isset($this->map['customer'])?$this->map['customer']:''))
                    echo "<script>$('customer').value = \"".addslashes(URL::get('customer',isset($this->map['customer'])?$this->map['customer']:''))."\";</script>";
                    ?>
	<?php echo $this->map['guest'];?></select></td>
                
            	<td><?php echo Portal::language('guest_name');?>:</td>
                <td style="margin: 0;padding:0;"><select  name="receiver" id="receiver" style="width: 140px;"><?php
					if(isset($this->map['receiver_list']))
					{
						foreach($this->map['receiver_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('receiver',isset($this->map['receiver'])?$this->map['receiver']:''))
                    echo "<script>$('receiver').value = \"".addslashes(URL::get('receiver',isset($this->map['receiver'])?$this->map['receiver']:''))."\";</script>";
                    ?>
	<?php echo $this->map['receiver'];?></select></td>
                
                <td><?php echo Portal::language('room');?>:</td>
                <td colspan="2"><select  name="room" id="room" style="width: 140px;"><?php
					if(isset($this->map['room_list']))
					{
						foreach($this->map['room_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room',isset($this->map['room'])?$this->map['room']:''))
                    echo "<script>$('room').value = \"".addslashes(URL::get('room',isset($this->map['room'])?$this->map['room']:''))."\";</script>";
                    ?>
	<?php echo $this->map['rooms'];?></select></td>
			</tr>
            </table>
            <table style="margin: 0 auto;">
            <tr>
             		<td align="right"> <input  name="checked_all" id="checked_all" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('checked_all'));?>"></td> 
                    <td align="left"><b><label for="checked_all"><?php echo Portal::language('select_all_bar');?></label></b></td>
             </tr>
             <?php if(isset($this->map['bars']) and is_array($this->map['bars'])){ foreach($this->map['bars'] as $key1=>&$item1){if($key1!='current'){$this->map['bars']['current'] = &$item1;?>
             <tr>
                  <td align="right"><input name="bars[]"  type="checkbox" value="<?php echo $this->map['bars']['current']['id'];?>" id="bar_id_<?php echo $this->map['bars']['current']['id'];?>" <?php if($this->map['bars']['current']['check']) echo 'checked="checked"' ?> class="check_box"  /></td>
                  <td><label for="bar_id_<?php echo $this->map['bars']['current']['id'];?>"><?php echo $this->map['bars']['current']['name'];?></label></td>
    		 </tr>
             <?php }}unset($this->map['bars']['current']);} ?>
		  </table>
			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</td></tr></table>
</td>
<div>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" style="line-height:30px; margin:10px auto;">
	<tr height="30" style="background:#DFDFDF;">
    	<td width="3%" align="center" rowspan="2"><?php echo Portal::language('no');?></td>
        <td width="3%" align="center" rowspan="2"><?php echo Portal::language('order_id');?></td>
        <td width="7%" align="center" rowspan="2"><?php echo Portal::language('arrival_time');?></td>
        <td width="5%" align="center" rowspan="2"><?php echo Portal::language('room');?></td>
        <td width="7%" align="center" rowspan="2"><?php echo Portal::language('customer');?></td>
        <td width="7%" align="center" rowspan="2"><?php echo Portal::language('guest_name');?></td>
        <td width="7%" align="center" rowspan="2"><?php echo Portal::language('product_id');?></td>
        <td width="17%" align="center" rowspan="2"><?php echo Portal::language('product_name');?></td>
        <td width="7%" align="center" rowspan="2"><?php echo Portal::language('price_standard');?></td>
        
        <td width="4%" align="center" rowspan="2"><?php echo Portal::language('quantity');?></td>
        
        <td width="24%" align="center" colspan="3"><?php echo Portal::language('res_discount_percent');?></td>
        
        
        <td width="10%" align="center" style="display:none;"><b><?php echo Portal::language('total');?></b></td>
        
        <!--<td width="10%" align="center" rowspan="2"><?php echo Portal::language('discount_money');?></td> -->
        
        <td width="7%" align="center" rowspan="2"><?php echo Portal::language('creator');?></td>
    </tr>
    
    <tr style="background:#DFDFDF;">
        <td width="7%" align="center"><?php echo Portal::language('res_discount_rate');?></td>
        <td width="7%" align="center"><?php echo Portal::language('res_discount_full');?></td>
        <td width="10%" align="center"><?php echo Portal::language('res_discount');?></td>
    </tr>
    <?php $i= 1; $quantiy=0;?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
    <tr>
    	<td rowspan="<?php echo $this->map['items']['current']['flag'];?>"><?php echo $i;?></td>
        <td rowspan="<?php echo $this->map['items']['current']['flag'];?>"><a href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>" target="_blank"><?php echo $this->map['items']['current']['code'];?></a></td>
        <td align="left" rowspan="<?php echo $this->map['items']['current']['flag'];?>"><?php echo $this->map['items']['current']['arrival_time'];?> -- <?php echo $this->map['items']['current']['arrival_time_hour'];?></td>
        <td align="left" rowspan="<?php echo $this->map['items']['current']['flag'];?>"><?php echo $this->map['items']['current']['room_name'];?></td>
          
        
        <td align="left" rowspan="<?php echo $this->map['items']['current']['flag'];?>"><?php if(empty($this->map['items']['current']['agent_name'])){ ?><?php echo $this->map['items']['current']['customer_name'];?><?php }else { ?><?php echo $this->map['items']['current']['agent_name'];?><?php } ?></td>
        <td align="left" rowspan="<?php echo $this->map['items']['current']['flag'];?>"><?php echo $this->map['items']['current']['receiver_name'];?></td>
         <?php if(isset($this->map['item_details']) and is_array($this->map['item_details'])){ foreach($this->map['item_details'] as $key3=>&$item3){if($key3!='current'){$this->map['item_details']['current'] = &$item3;?>
        <?php if(($this->map['item_details']['current']['bar_reservation_id'] == $this->map['items']['current']['id']) && ($this->map['item_details']['current']['flag'] == 1)){?>
         <td align="left"><?php echo $this->map['item_details']['current']['product_id'];?></td>
         <td align="left"><?php echo $this->map['item_details']['current']['product_name'];?></td>
        <td align="right"><?php echo $this->map['item_details']['current']['price'];?></td>
        <td align="right"><?php echo $this->map['item_details']['current']['quantity'];?></td><?php $quantiy+=$this->map['item_details']['current']['quantity'] ?>
        <td align="right"><?php echo $this->map['item_details']['current']['discount_rate'];?>&nbsp;%</td>
        <td align="center"><?php echo $this->map['item_details']['current']['full_discount'];?>&nbsp;%</td>
        
        <td align="right"><?php echo $this->map['item_details']['current']['discount_real'];?></td>
        <td align="right" style="display:none;"><b><?php echo $this->map['item_details']['current']['total'];?></b></td>
        <!--<td align="left" rowspan="<?php echo $this->map['items']['current']['flag'];?>"><?php //echo System::display_number($this->map['items']['current']['discount']);?></td>-->
        <td align="center" rowspan="<?php echo $this->map['items']['current']['flag'];?>"><?php echo $this->map['item_details']['current']['receptionist_id'];?></td>
        
        <?php }else if(($this->map['item_details']['current']['bar_reservation_id'] == $this->map['items']['current']['id']) && ($this->map['item_details']['current']['flag'] > 1)){?>
        <tr>
         <td align="left"><?php echo $this->map['item_details']['current']['product_id'];?></td>
         <td align="left"><?php echo $this->map['item_details']['current']['product_name'];?></td>
        <td align="right"><?php echo $this->map['item_details']['current']['price'];?></td>
        <td align="right"><?php echo $this->map['item_details']['current']['quantity'];?></td><?php $quantiy+=$this->map['item_details']['current']['quantity'] ?>
        <td align="right"><?php echo $this->map['item_details']['current']['discount_rate'];?>&nbsp;%</td>
        <td width="7%" align="center"><?php echo $this->map['item_details']['current']['full_discount'];?>&nbsp;%</td>
        
        <td align="right"><?php echo $this->map['item_details']['current']['discount_real'];?></td>
        <td align="right" style="display:none;"><b><?php echo $this->map['item_details']['current']['total'];?></b></td>
        
        </tr>
        <?php }?>
     <?php }}unset($this->map['item_details']['current']);} ?>
    </tr>
    <?php $i++;?>
	<?php }}unset($this->map['items']['current']);} ?>
    
     <tr>
        <td align="right" colspan="9"><b><?php echo Portal::language('total');?>:&nbsp;</b></td>
        <td align="right"><?php echo System::display_number($quantiy); ?></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"><b><?php echo $this->map['total_discount'];?></b></td>
        <td align="right" style="display:none;"><b><?php echo $this->map['total'];?></b></td>
        <td align="right"></td>
    </tr>
        </table>
</div>
</tr>
</table>
<script>
		$('from_date').value = '<?php echo $this->map['from_date'];?>';
		$('to_date').value = '<?php echo $this->map['to_date'];?>';
		function changevalue()
        {
            var myfromdate = $('from_date').value.split("/");
            var mytodate = $('to_date').value.split("/");
            
            var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
            var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
            if(arr_myfromdate>arr_mytodate)
            {
                jQuery("#to_date").val(jQuery("#from_date").val());
            }
        }
        function choice_restaurant()
        {
            WeeklyRevenueThuyForm.submit();
        }
        function check_choose_restaurant()
        {
            var check_box_arr = document.getElementsByClassName('check_box');
            var flag = false;
            for(var i=0;i<check_box_arr.length;i++)
            {
                if(check_box_arr[i].checked==true)
                    flag = true;
            }
            if(flag== false)
                alert('Bạn chưa chọn nhà hàng!');
            return flag;
        }
        function changefromday()
        {
            var myfromdate = $('from_date').value.split("/");
            var mytodate = $('to_date').value.split("/");
            var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
            var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
            if(arr_myfromdate>arr_mytodate)
            {
                jQuery("#from_date").val(jQuery("#to_date").val());
            }
        }
		jQuery("#from_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
		jQuery("#to_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
	<?php 
				if(($this->map['view_all']!=1))
				{?>
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	
				<?php
				}
				?>
    jQuery("#checked_all").click(function (){
       // console.log("!!");
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });
</script>