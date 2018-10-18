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
        <th><?php echo Portal::language('original_price');?></th>
        <th><?php echo Portal::language('price');?></th>
        <th><?php echo Portal::language('discount');?>(%)</th>
        <th><?php echo Portal::language('quantity');?></th>
        <th><?php echo Portal::language('promotion');?></th>
        <th><?php echo Portal::language('original_amount');?></th>   
        <th><?php echo Portal::language('amount');?></th>
        <th><?php echo Portal::language('interest_rate');?></th>
    </tr>
    <?php 
    $i = 1;
    $product_id = '';
    $total_before_tax ='';
    $discount ='';
    //System::debug($this ->map['count'])
    ?>
    <?php if(isset($this->map['categories']) and is_array($this->map['categories'])){ foreach($this->map['categories'] as $key1=>&$item1){if($key1!='current'){$this->map['categories']['current'] = &$item1;?>
    <tr>
        <td colspan="12" style="text-transform: uppercase; font-weight: bold;"><?php echo $this->map['categories']['current']['name'];?></td>
    </tr>
    <?php $total1=0;$total2=0;$total3=0; ?>
    <?php if(isset($this->map['categories']['current']['items']) and is_array($this->map['categories']['current']['items'])){ foreach($this->map['categories']['current']['items'] as $key2=>&$item2){if($key2!='current'){$this->map['categories']['current']['items']['current'] = &$item2;?>
    <tr>
    <?php 
    if($this->map['categories']['current']['items']['current']['product_id'] != $product_id)
    {
        $total_before_tax ='';
        $discount ='';
        $product_id = $this->map['categories']['current']['items']['current']['product_id'];
        $rowspan = $this -> map['count'][$product_id]['count'];
    ?>
        <td rowspan="<?php echo $rowspan ?>"><?php echo $i++ ?></td>
        <td rowspan="<?php echo $rowspan ?>"><?php echo $this->map['categories']['current']['items']['current']['product_id'];?></td>
        <td rowspan="<?php echo $rowspan ?>"><?php echo $this->map['categories']['current']['items']['current']['product_name'];?></td>   
        <td rowspan="<?php echo $rowspan ?>"><?php echo $this->map['categories']['current']['items']['current']['unit'];?></td>
    <?php
    }
     if($this->map['categories']['current']['items']['current']['product_id'].'_'.$this->map['categories']['current']['items']['current']['total_before_tax'].'_'.$this->map['categories']['current']['items']['current']['discount'] != $product_id.'_'.$total_before_tax.'_'.$discount)
     {
        $total_before_tax = $this->map['categories']['current']['items']['current']['total_before_tax'];
        $discount = $this->map['categories']['current']['items']['current']['discount'];
        $rowspan_child =$this->map['count'][$product_id][$total_before_tax][$discount];
     ?>
        <td rowspan="<?php echo $rowspan_child ?>" class="change_numTr"><?php echo System::display_number(round($this->map['categories']['current']['items']['current']['original_total_before_tax'],2));?></td>
        <td rowspan="<?php echo $rowspan_child ?>" class="change_numTr"><?php echo System::display_number(round($this->map['categories']['current']['items']['current']['total_before_tax']));?></td>
        <td rowspan="<?php echo $rowspan_child ?>"><?php echo $this->map['categories']['current']['items']['current']['discount'];?>(%)</td>
        <td rowspan="<?php echo $rowspan_child ?>"><?php echo $this->map['count'][$product_id][$total_before_tax][$discount."_quantity"];  ?></td>
        <td rowspan="<?php echo $rowspan_child ?>"><?php echo $this->map['categories']['current']['items']['current']['promotion'];?></td>
        <td rowspan="<?php echo $rowspan_child ?>" style="text-align: right;" class="change_numTr"><?php echo System::display_number(round($this->map['count'][$product_id][$total_before_tax][$discount."_original_total_before"])); $total1+=$this->map['count'][$product_id][$total_before_tax][$discount."_original_total_before"];  ?></td>
        <td rowspan="<?php echo $rowspan_child ?>" style="text-align: right;" class="change_numTr"><?php echo System::display_number(round($this->map['count'][$product_id][$total_before_tax][$discount."_total_before"])); $total2+=$this->map['count'][$product_id][$total_before_tax][$discount."_total_before"];  ?></td>
        <td rowspan="<?php echo $rowspan_child ?>" style="text-align: right;" class="change_numTr"><?php echo System::display_number(round($this->map['count'][$product_id][$total_before_tax][$discount."_total_before"]-$this->map['count'][$product_id][$total_before_tax][$discount."_original_total_before"])); $total3+=$this->map['count'][$product_id][$total_before_tax][$discount."_total_before"]-$this->map['count'][$product_id][$total_before_tax][$discount."_original_total_before"];  ?></td>
      <?php
      }
      ?>
      
    <?php }}unset($this->map['categories']['current']['items']['current']);} ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="9"><?php echo Portal::language('total_revenue');?> <?php echo Portal::language('of');?> <?php echo $this->map['categories']['current']['name'];?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($total1)); ?></td>
        <td class="change_numTr"><?php echo System::display_number(round($total2)); ?></td>
        <td class="change_numTr"><?php echo System::display_number(round($total3)); ?></td>
      </tr>  
    </tr>
    <?php }}unset($this->map['categories']['current']);} ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="9"><?php echo Portal::language('total_revenue');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['original_total_befor_tax'])); ?></td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_befor_tax'])); ?></td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['interest_rate_total'])); ?></td>
    </tr>
    <?php
        if($this->map['type']==2)
        {
    ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="10"><?php echo Portal::language('total_express_rate');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_express_rate'])); ?></td>
        <td></td>
    </tr>
    <?php } ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="10"><?php echo Portal::language('total_fee');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_fee_rate'])); ?></td>
        <td></td>
    </tr>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="10"><?php echo Portal::language('total_tax');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_tax_rate'])); ?></td>
        <td></td>
    </tr>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="10"><?php echo Portal::language('summary');?>:</td>
        <td class="change_numTr"><?php echo System::display_number(round($this->map['total_amount'])); ?></td>
        <td></td>
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