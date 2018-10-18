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
				<td align="left" width="65%"><strong><?php echo HOTEL_NAME;?></strong><br />ADD: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="35%"><strong><?php echo Portal::language('template_code');?></strong></td>
			</tr>	
             <tr valign="top">
				<td align="left" width="65%"></td>
				<td align="right" nowrap width="35%"></td>
			</tr>
            <tr valign="top">
				<td align="left" width="65%"><?php echo Portal::language('print_by');?> : <?php echo Session::get('user_id');?></td>
				<td align="right" nowrap width="35%"></td>
			</tr>
            <tr valign="top">
				<td align="left" width="65%"><?php echo Portal::language('print_date');?> : <?php echo date('h:i d/m/Y',time());?></td>
				<td align="right" nowrap width="35%"></td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<font class="report_title" style="font-size:20px; text-align:center;"><b><?php echo Portal::language('payment_list');?></b></font>

		<br><br />
        <label id="timehidden">ngày: <?php echo $this->map['from_date'];?></label>
		<form name="WeeklyViewFolioForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b><?php echo Portal::language('search');?></b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td><?php echo Portal::language('room_id');?> : <select  name="room_id" id="room_id"  style="width:80px;" ><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select> </td>
            	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('guest_name');?> : <input  name="guest_name" id="guest_name"  type ="text" value="<?php echo String::html_normalize(URL::get('guest_name'));?>"> </td>
                <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('date');?>:
            	<input type="text" name="from_date" id="from_date" onchange="check_from_date();" class="date-input" style="float:right;"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
            <tr>
            	<td><?php echo Portal::language('code');?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input  name="code" id="code" style="width:80px;"  type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>"> </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('customer_name');?> :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input  name="customer_name" id="customer_name" onkeypress="Autocomplete();"  autocomplete="off"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"> </td>
                <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="do_search" value="  <?php echo Portal::language('search');?>  " style="height:30px;"></td>
            </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</td></tr></table>
</td>
<div><table width="100%" cellpadding="3" style="border:1px solid #DFDFDF; line-height:30px; margin:10px auto;" id="revenue">
	<tr height="30" style="background:#DFDFDF;">
    	<td width="2%" align="center"><?php echo Portal::language('no');?></td>
        <td width="4%" align="center"><?php echo Portal::language('code');?></td>
        <td width="4%" align="center"><?php echo Portal::language('room_name');?></td>
        <td width="7%" align="center"><?php echo Portal::language('arrival_date');?></td>
        <td width="7%" align="center"><?php echo Portal::language('departure_date');?></td>
        <td width="9%" align="center"><?php echo Portal::language('price');?></td>
        <td width="15%" align="center"><?php echo Portal::language('guest');?></td>
        <td width="15%" align="center"><?php echo Portal::language('customer');?></td>
        <td width="10%" align="center"><?php echo Portal::language('total');?></td>
        <td width="12%" align="center"><?php echo Portal::language('Note');?></td>
        <td width="5%" align="center"><?php echo Portal::language('paid_for_guest');?></td>
        <td width="5%" align="center"><?php echo Portal::language('paid_for_customer');?></td>
        
    </tr>
    <?php $i= 1;$total=0;$night = 0;?>
    <?php Module::get_sub_regions("folio");?>
<?php Module::get_sub_regions("payment");?>
<?php $customer_name = ''; ?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr>
    	<td width="2%" align="center" ><?php echo $i; $i++; $night+=$this->map['items']['current']['nights'];?></td>
         <td width="4%" align="center"><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'],'r_r_id'=>$this->map['items']['current']['id']));?>" target="_blank"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
        <td width="4%" align="center"><?php echo $this->map['items']['current']['room_name'];?></td>
        <td width="7%" align="center"><?php echo date('h:i',$this->map['items']['current']['time_in']);?> <?php echo $this->map['items']['current']['arrival_date'];?></td>
        <td width="7%" align="center"><?php echo $this->map['items']['current']['departure_date'];?></td>
        <td width="9%" align="right" ><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
        <td width="15%" align="center"><?php echo $this->map['items']['current']['name_traveller'];?></td>
        <td width="15%" align="center"><?php echo $this->map['items']['current']['customer_name'];?></td>
        <td width="4%" align="right"><?php echo System::display_number($this->map['items']['current']['total_amount']);$total +=$this->map['items']['current']['total_amount']; ?></td>
        <td width="12%" align="center"><?php echo $this->map['items']['current']['note'];?></td>
         <td width="5%" align="center"><?php 
				if(($this->map['items']['current']['name_traveller']))
				{?><input  type="button" id="split_invoice" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=429&cmd=create_folio&rr_id=<?php echo $this->map['items']['current']['id'];?>&r_id=<?php echo $this->map['items']['current']['reservation_id'];?>&customer_id=<?php echo $this->map['items']['current']['customer_id'];?>',Array('folio_<?php echo $this->map['items']['current']['id'];?>','<?php echo Portal::language('create_folio');?>','80','210','950','500'));" value="<?php echo Portal::language('folio');?>" title="<?php echo Portal::language('payment');?>" class="view-order-button">
				<?php
				}
				?></td>
         <?php if($this->map['items']['current']['countt']>0 && $customer_name!=$this->map['items']['current']['reservation_id']){?>
          <td width="5%" align="center" rowspan="<?php echo $this->map['items']['current']['countt'];?>"><input  type="button" id="split_invoice" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=429&cmd=group_folio&id=<?php echo $this->map['items']['current']['reservation_id'];?>&customer_id=<?php echo $this->map['items']['current']['customer_id'];?>',Array('create_folio','<?php echo Portal::language('create_folio');?>','80','210','950','500'));" value="<?php echo Portal::language('folio');?>" title="<?php echo Portal::language('group_payment');?>" class="view-order-button"></td>
          <?php }else if($this->map['items']['current']['countt']==0){ echo '<td width="5%"></td>';}
		  if($customer_name!=$this->map['items']['current']['reservation_id'] && $this->map['items']['current']['reservation_id']!=''){$customer_name=$this->map['items']['current']['reservation_id'];} 
		  ?>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
     <tr>
    	<td align="right"  ><b><i><?php echo Portal::language('total');?></i></b></td>
        <td align="center"  ></td>
        <td align="center"  ><b><?php echo ($i-1);?> </b></td>
         <td align="center" colspan="5"  ></td>
        <td align="right"  ><b><?php echo System::display_number($total);?></b> </td>
        <td width="6%" align="center" colspan="3">&nbsp;</td>
    </tr>
        </table>
</div>
</tr>
</table>
<script>
		$('from_date').value = '<?php echo $this->map['from_date'];?>';
		jQuery("#from_date").datepicker({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
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
    function Autocomplete()
{
    jQuery("#customer_name").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item) {
             //console.log(item);
            document.getElementById('customer_id').value = item.data[0];
           // console.log(document.getElementById('customer_id').value);
        }
    }) ;
}
</script>