<form name="HousekeepingRevenueReportFrom" method="post"><div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>"  /></div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<p style="margin-left: 15px;"><?php echo Portal::language('price_unit');?>: <?php echo HOTEL_CURRENCY;?></p>
<table cellpadding="5" cellspacing="0" width="98%" border="1" bordercolor="#CCCCCC" style="text-align: center; margin: 5px auto;" id="export">
    <tr style="font-weight: bold; background: #eeeeee;">
        <th><?php echo Portal::language('stt');?></th>
        
        <th><?php echo Portal::language('product_code');?></th>
        <th><?php echo Portal::language('product_name');?></th>
        <th><?php echo Portal::language('unit');?></th>
        <th><?php echo Portal::language('price');?></th>
        <th><?php echo Portal::language('discount');?>(%)</th>
        <th><?php echo Portal::language('quantity');?></th>
        <!--<th><?php echo Portal::language('promotion');?></th>-->        
        <th><?php echo Portal::language('amount');?></th>
    </tr>
    <?php 
    $i = 1;
    $product_id = '';
    $total_before_tax = '';
    $discount = '';
    //System::debug($this ->map['count'])
    ?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr>
    <?php if($this->map['items']['current']['product_id'] != $product_id)
    {
        $total_before_tax ='';
        $discount ='';
        $product_id = $this->map['items']['current']['product_id'];
        $rowspan = $this -> map['count'][$product_id]['count'];
    ?>
        <td rowspan="<?php echo $rowspan ?>"><?php echo $i++ ?></td>
        
        <td rowspan="<?php echo $rowspan ?>"><?php echo $this->map['items']['current']['product_id'];?></td>
        <td rowspan="<?php echo $rowspan ?>"><?php echo $this->map['items']['current']['product_name'];?></td>   
        <td rowspan="<?php echo $rowspan ?>"><?php echo $this->map['items']['current']['unit'];?></td>
    <?php
    }
     if($this->map['items']['current']['product_id'].'_'.$this->map['items']['current']['total_before_tax'].'_'.$this->map['items']['current']['discount'] != $product_id.'_'.$total_before_tax.'_'.$discount)
     {
        $total_before_tax = $this->map['items']['current']['total_before_tax'];
        $discount = $this->map['items']['current']['discount'];
        $rowspan_child =$this->map['count'][$product_id][$total_before_tax][$discount];
        //echo $rowspan_child;
     ?>
       
        <td rowspan="<?php echo $rowspan_child ?>" class="change_numTr"><?php echo System::display_number(round($this->map['items']['current']['total_before_tax']));?></td>
        <td rowspan="<?php echo $rowspan_child ?>"><?php echo $this->map['items']['current']['discount'];?>(%)</td>
        <td rowspan="<?php echo $rowspan_child ?>"><?php echo $this->map['count'][$product_id][$total_before_tax][$discount."_quantity"];  ?></td>
        <td style="text-align: right;" rowspan="<?php echo $rowspan_child ?>" class="change_numTr" ><?php echo System::display_number(round($this->map['count'][$product_id][$total_before_tax][$discount."_total_before"]));  ?></td>
      <?php
       }
      ?>
        
       <!-- <td><?php echo $this->map['items']['current']['promotion'];?></td> -->
        
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7"><?php echo Portal::language('summary');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_befor_tax'])); ?></td>
    </tr>
    <?php
        if($this->map['type']==2)
        {
    ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7"><?php echo Portal::language('total_express_rate');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_express_rate'])); ?></td>
    </tr>
    <?php } ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7"><?php echo Portal::language('total_fee');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_fee_rate'])); ?></td>
    </tr>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7"><?php echo Portal::language('total_tax');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_tax_rate'])); ?></td>
    </tr>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7"><?php echo Portal::language('total_revenue');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_amount'])); ?></td>
    </tr>
</table>
<script>
jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>