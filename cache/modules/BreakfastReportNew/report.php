<style>
	 @media print {
	   fieldset {
	       display: none;
	   }
  }	
</style>
<div id="header">
    <table style="width: 98%; margin: 0px auto;">
        <tr>
            <td style="font-weight: bold;"><?php echo HOTEL_NAME; ?><br /><?php echo HOTEL_ADDRESS; ?></td>
            <td style="text-align: right; font-weight: bold;"><?php echo Portal::language('template_code');?><br /><?php echo Portal::language('date');?>: <?php echo date('H:i d/m/Y'); ?><br /><?php echo Portal::language('user');?>: <?php echo User::id(); ?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><p style="font-size: 20px; font-weight: bold; text-transform: uppercase;"><?php echo Portal::language('breakfast_report');?></p><p><?php echo Portal::language('from_date');?>: <?php echo $this->map['from_date'];?> <?php echo Portal::language('to_date');?>: <?php echo $this->map['to_date'];?> </p><p><?php echo Portal::language('breakfast_from_time');?>: <?php echo BREAKFAST_FROM_TIME; ?> <?php echo Portal::language('breakfast_to_time');?>: <?php echo BREAKFAST_TO_TIME; ?></p></td>
        </tr>
    </table>
</div><!-- end header -->

<div id="search" style="width: 98%; margin: 0px auto;">
    <form name="BreakfastReportForm" method="post">
        <fieldset style="border: 1px solid #00b2f9;">
            <legend  style="border: 1px solid #00b2f9; background: #ffffff; border-left: 3px solid #00b2f9; border-right: 3px solid #00b2f9; padding: 5px 20px;"><?php echo Portal::language('option');?></legend>
            <table style="width: 100%;">
                <tr>
                    <td>
                        <?php echo Portal::language('hotel');?>: <select  name="portal_id" id="portal_id" style="width: 120px; text-align: center;"><?php
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
	</select>
                    </td>
                    <td>
                        <?php echo Portal::language('from_date');?>: <input  name="from_time" id="from_time" style="width: 35px; text-align: center; display: none;" autocomplete="off" / type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>">
                        <input  name="from_date" id="from_date" style="width: 80px; text-align: center;"  autocomplete="off"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                    </td>
                    <td>
                        <?php echo Portal::language('to_date');?>: <input  name="to_time" id="to_time" style="width: 35px; text-align: center; display: none;"  autocomplete="off"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>">
                        <input  name="to_date" id="to_date" style="width: 80px; text-align: center;"  autocomplete="off"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                    </td>
                    <!--trung add search customer-->
                    <td>
                        <?php echo Portal::language('customer_name');?> : <input  name="customer_name" id="customer_name" onkeypress="Autocomplete();"  autocomplete="off"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"> 
                        <input  name="customer_id" id="customer_id" class="hidden" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                    </td>
                    <!-- ednd :trung add search customer-->
                    <td>
                        <input name="search" value="<?php echo Portal::language('view_report');?>" type="submit" style="border: 1px solid #00b2f9; background: #ffffff; padding: 5px; font-weight: bold;" />
                    </td>
                </tr>
            </table>
        </fieldset>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div><!-- end search -->

<div id="report" style="width: 98%; margin: 0px auto;">
    <table style="width: 100%; margin: 10px auto; border-spacing: inherit; border-collapse: 1px;" cellpadding="5" cellspacing="0" border="1" bordercolor="#5d5d5d">
        <tr style="background: #eeeeee;">
            <th style="text-align: center; width: 40px;"><?php echo Portal::language('stt');?></th>
            <th style="text-align: center; width: 50px;"><?php echo Portal::language('recode');?></th>
            <th style="text-align: center; width: 40px;"><?php echo Portal::language('barcode');?></th>
            <th style="text-align: center; width: 15%;"><?php echo Portal::language('customer1');?></th>
            <th style="text-align: center;"><?php echo Portal::language('traveller_name');?></th>
            <th style="text-align: center;"><?php echo Portal::language('room_name');?></th>
            <th style="text-align: center;"><?php echo Portal::language('is_adult');?></th> 
            <th style="text-align: center;"><?php echo Portal::language('is_child');?></th>
            <th style="text-align: center;"><?php echo Portal::language('breakfast_indate');?></th>
            <th style="text-align: center;"><?php echo Portal::language('breakfast_time');?></th>
            <th style="text-align: center;"><?php echo Portal::language('Note');?></th>
        </tr>
        <?php
            $i = 1;
        ?>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr>
            <td ><?php echo $i; $i++; ?></td>
            <td ><a href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['reservation_id'];?>&r_r_id=<?php echo $this->map['items']['current']['reservation_room_id'];?>"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
            <td ><?php echo $this->map['items']['current']['barcode'];?></td>    
            <td ><?php echo $this->map['items']['current']['customer_name'];?></td>  
            <td ><?php echo $this->map['items']['current']['guest_name'];?></td> 
            <td style="text-align: left;" ><?php echo $this->map['items']['current']['room_name'];?></td>  
            <td style="text-align:center;" class="is_adult"><?php if($this->map['items']['current']['is_child']==0) echo "x"; ?></td> 
            <td style="text-align:center;" class="is_child"><?php if($this->map['items']['current']['is_child']==1) echo "x"; ?></td> 
            <td class="tt_voucher" style="text-align: center;" ><?php echo !empty($this->map['items']['current']['in_date']) ? $this->map['items']['current']['in_date'] : ""; ?></td>  
            <td class="tt_use" style="text-align: center;" ><?php echo !empty($this->map['items']['current']['real_use_date']) ? date("H:i d/m/Y",$this->map['items']['current']['real_use_date']) : ""; ?></td>  
            <td style="text-align: left;" ><?php if(!empty($this->map['items']['current']['reprint'])) echo "In lại lần ".$this->map['items']['current']['reprint']; ?></td>  
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
        <tr>
            <td style="text-align: right; font-weight:bold;" colspan="6"><?php echo Portal::language('Total_final');?></td>
            <td style="text-align: center; font-weight:bold;" id="total_adult"></td>
            <td style="text-align: center; font-weight:bold;" id="total_child"></td>
            <td style="text-align: center; font-weight:bold;" id="total_voucher"></td>
            <td style="text-align: center; font-weight:bold;" id="total_use"></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight:bold;" colspan="8"><?php echo Portal::language('Sub');?></td>
            <td colspan="2" style="text-align: center; font-weight:bold;" id="sub_voucher"></td>
            <td></td>
        </tr>
    </table>
</div><!-- end report -->

<div id="footer">

</div><!-- end footer -->
<script>
    jQuery(document).ready(function(){
        jQuery("#total_voucher").html(jQuery(".tt_voucher").length);
        var stt = 0;
        jQuery(".tt_use").each(function(){
            if(jQuery(this).html().trim()!="")
            {
                stt++;
            }
        });
        jQuery("#total_use").html(stt);
        jQuery("#sub_voucher").html(jQuery(".tt_voucher").length - stt);
        
        var count_adult = 0;
        jQuery(".is_adult").each(function(){
            if(jQuery(this).html().trim()!="")
            {
                count_adult++;
            }
        });
        jQuery("#total_adult").html(count_adult);
        
        var count_child = 0;
        jQuery(".is_child").each(function(){
            if(jQuery(this).html().trim()!="")
            {
                count_child++;
            }
        });
        jQuery("#total_child").html(count_child);
    });

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


    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>