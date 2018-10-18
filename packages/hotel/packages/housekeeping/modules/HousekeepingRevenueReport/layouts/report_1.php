<form name="HousekeepingRevenueReportFrom" method="post"><div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></div>
</form>
<p style="margin-left: 15px;">[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></p>
<table cellpadding="5" cellspacing="0" width="98%" border="1" bordercolor="#CCCCCC" style="text-align: center; margin: 5px auto;" id="export">
    <tr style="font-weight: bold; background: #eeeeee;">
        <th>[[.stt.]]</th>
        
        <th>[[.product_code.]]</th>
        <th>[[.product_name.]]</th>
        <th>[[.unit.]]</th>
        <th>[[.price.]]</th>
        <th>[[.discount.]](%)</th>
        <th>[[.quantity.]]</th>
        <!--<th>[[.promotion.]]</th>-->        
        <th>[[.amount.]]</th>
    </tr>
    <?php 
    $i = 1;
    $product_id = '';
    $total_before_tax = '';
    $discount = '';
    //System::debug($this ->map['count'])
    ?>
    <!--LIST:items-->
    <tr>
    <?php if([[=items.product_id=]] != $product_id)
    {
        $total_before_tax ='';
        $discount ='';
        $product_id = [[=items.product_id=]];
        $rowspan = $this -> map['count'][$product_id]['count'];
    ?>
        <td rowspan="<?php echo $rowspan ?>"><?php echo $i++ ?></td>
        
        <td rowspan="<?php echo $rowspan ?>">[[|items.product_id|]]</td>
        <td rowspan="<?php echo $rowspan ?>">[[|items.product_name|]]</td>   
        <td rowspan="<?php echo $rowspan ?>">[[|items.unit|]]</td>
    <?php
    }
     if([[=items.product_id=]].'_'.[[=items.total_before_tax=]].'_'.[[=items.discount=]] != $product_id.'_'.$total_before_tax.'_'.$discount)
     {
        $total_before_tax = [[=items.total_before_tax=]];
        $discount = [[=items.discount=]];
        $rowspan_child =$this->map['count'][$product_id][$total_before_tax][$discount];
        //echo $rowspan_child;
     ?>
       
        <td rowspan="<?php echo $rowspan_child ?>" class="change_numTr"><?php echo System::display_number(round([[=items.total_before_tax=]]));?></td>
        <td rowspan="<?php echo $rowspan_child ?>">[[|items.discount|]](%)</td>
        <td rowspan="<?php echo $rowspan_child ?>"><?php echo $this->map['count'][$product_id][$total_before_tax][$discount."_quantity"];  ?></td>
        <td style="text-align: right;" rowspan="<?php echo $rowspan_child ?>" class="change_numTr" ><?php echo System::display_number(round($this->map['count'][$product_id][$total_before_tax][$discount."_total_before"]));  ?></td>
      <?php
       }
      ?>
        
       <!-- <td>[[|items.promotion|]]</td> -->
        
    </tr>
    <!--/LIST:items-->
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7">[[.summary.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_befor_tax=]])); ?></td>
    </tr>
    <?php
        if([[=type=]]==2)
        {
    ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7">[[.total_express_rate.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_express_rate=]])); ?></td>
    </tr>
    <?php } ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7">[[.total_fee.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_fee_rate=]])); ?></td>
    </tr>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7">[[.total_tax.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_tax_rate=]])); ?></td>
    </tr>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="7">[[.total_revenue.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_amount=]])); ?></td>
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